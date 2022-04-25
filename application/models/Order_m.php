<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_m extends CI_Model 
{
	public function __construct()
	{
        parent::__construct();		
		$this->load->database();
	}

	public function order_list($req, $offset, $perpage) {
		$sql = "SELECT
					t1.order_id
					, t1.order_type
					, t1.mem_id
					, FN_DECRYPT(t1.mem_username) as mem_username
					, FN_DECRYPT(t1.mem_email) as mem_email
					, FN_DECRYPT(t1.mem_phone) as mem_phone
					, t1.total_price
					, t1.total_qty
					, t1.use_point
					, t1.delivery_price
					, FN_DECRYPT(t1.recipient_name) as recipient_name
					, FN_DECRYPT(t1.recipient_phone) as recipient_phone
					, FN_DECRYPT(t1.recipient_zip) as recipient_zip
					, FN_DECRYPT(t1.recipient_addr1) as recipient_addr1
					, FN_DECRYPT(t1.recipient_addr2) as recipient_addr2
					, t1.recipient_memo as recipient_memo
					, t1.product_name
					, t1.status
					, FN_STATUSNAME(t1.status) as status_name
					, t1.tid
					, t1.payMethod
					, FN_PAYNAME(t1.payMethod) as payName
					, t1.card_code
					, t1.card_name
					, t1.card_num
					, t1.payDevice
					, t1.applDate
					, t1.applTime
					, date_format(t1.ins_dtm, '%Y/%m/%d') as ins_dtm
					, t1.upd_dtm
					, (SELECT group_concat(a.cit_name) FROM cmall_order_detail a WHERE a.order_id = t1.order_id GROUP BY a.order_id) as cit_name
					, ifnull(t2.order_cnt, '-') as order_cnt
					, ifnull(t2.start_date, '-') as start_date
					, ifnull(t2.delivery_period, '-') as delivery_period
					, ifnull(t3.crf_id, '') as crf_id
					, ifnull(t3.refund_type, '') as refund_type
					, if(t3.refund_type is not null, FN_REFUNDNAME(t3.refund_type), '') as refund_type_name
					, if(t1.delivery_start_dtm is null, '', date_format(t1.delivery_start_dtm, '%Y/%m/%d')) as delivery_start_dtm
					, if(t3.request_dtm is null, '', date_format(t3.request_dtm, '%Y/%m/%d')) as cancel_request_dtm
					, if(t3.complete_dtm is null, '', date_format(t3.complete_dtm, '%Y/%m/%d')) as cancel_complete_dtm
					, case t1.order_type when 'starter' then '1'
						else if(t4.ccg_id is null, '', (select sum(qty) from cmall_change_detail a where a.order_id = t1.order_id)) end as change_cnt
					, if(t4.request_dtm is null, '', date_format(t4.request_dtm, '%Y/%m/%d')) as change_request_dtm
					, if(t4.complete_dtm is null, '', date_format(t4.complete_dtm, '%Y/%m/%d')) as change_complete_dtm
					, (select count(*) from cmall_order_detail a where a.order_id = t1.order_id) as item_cnt
					, (select count(*) from cmall_review a where a.order_id = t1.order_id) as review_cnt
					, t1.delivery_invoice
				FROM
					cmall_order t1
				LEFT OUTER JOIN (SELECT
									b.order_id
									, (select count(*) from cmall_subscribe_history aa where aa.csu_id = a.csu_id and aa.csh_id <= b.csh_id) as order_cnt
									, a.start_date
									, a.delivery_period
								FROM
									cmall_subscribe a
								INNER JOIN cmall_subscribe_history b on b.csu_id = a.csu_id ) t2 on t2.order_id = t1.order_id
				LEFT OUTER JOIN cmall_refund t3 on t3.order_id = t1.order_id
				LEFT OUTER JOIN cmall_change t4 on t4.order_id = t1.order_id
				WHERE
					t1.order_type != 'subscribe'
					AND t1.is_delete = 'n'
					AND t1.mem_id = ? ";
		if($req['order_status'] == 'order') {
			$sql .= " ORDER BY t1.ins_dtm DESC ";	
		}
		else if($req['order_status'] == 'cancel') {
			$sql .= " AND t1.status = 'CANCEL' 
					ORDER BY t3.request_dtm DESC ";
		}
		else {
			$sql .= " AND t1.status IN ('" . implode("','", $req['order_status']) . "') AND t4.request_dtm is not null
					ORDER BY t4.request_dtm DESC ";
		}
		
		$sql .= " LIMIT ?, ? ";

		return $this->db->query($sql, array($req['mem_id'], $offset, $perpage));
	}

	public function order_list_cnt($req) {
		$sql = "SELECT
					count(*) as cnt
				FROM
					cmall_order t1
				LEFT OUTER JOIN (SELECT
									b.order_id
									, (select count(*) from cmall_subscribe_history aa where aa.csu_id = a.csu_id and aa.csh_id <= b.csh_id) as order_cnt
									, a.start_date
									, a.delivery_period
								FROM
									cmall_subscribe a
								INNER JOIN cmall_subscribe_history b on b.csu_id = a.csu_id ) t2 on t2.order_id = t1.order_id
				LEFT OUTER JOIN cmall_refund t3 on t3.order_id = t1.order_id
				LEFT OUTER JOIN cmall_change t4 on t4.order_id = t1.order_id
				WHERE
					t1.order_type != 'subscribe'
					AND t1.is_delete = 'n'
					AND t1.mem_id = ? ";
		if($req['order_status'] == 'order') {
			$sql .= " ORDER BY t1.ins_dtm DESC ";	
		}
		else if($req['order_status'] == 'cancel') {
			$sql .= " AND t1.status = 'CANCEL' 
					ORDER BY t3.request_dtm DESC ";
		}
		else {
			$sql .= " AND t1.status IN ('" . implode("','", $req['order_status']) . "') AND t4.request_dtm is not null 
					ORDER BY t4.request_dtm DESC ";
		}
				
		$tmp = $this->db->query($sql, array($req['mem_id']))->row_array();
		return $tmp['cnt'];
	}
	
	public function order_detail($seq) {
		$sql = "SELECT
					t1.order_id
                                        , t1.aid 
					, t1.order_type
					, t1.mem_id
					, FN_DECRYPT(t5.mem_username) as mem_username
					, FN_DECRYPT(t5.mem_email) as mem_email
					, FN_DECRYPT(t5.mem_phone) as mem_phone
					, t1.total_price
					, t1.total_qty
					, t1.use_point
					, t1.delivery_price
					, FN_DECRYPT(t1.recipient_name) as recipient_name
					, FN_DECRYPT(t1.recipient_phone) as recipient_phone
					, FN_DECRYPT(t1.recipient_zip) as recipient_zip
					, FN_DECRYPT(t1.recipient_addr1) as recipient_addr1
					, FN_DECRYPT(t1.recipient_addr2) as recipient_addr2
					, t1.recipient_memo as recipient_memo
					, t1.product_name
					, t1.status
					, FN_STATUSNAME(t1.status) as status_name
					, t1.tid
					, t1.payMethod
					, FN_PAYNAME(t1.payMethod) as payName
					, t1.card_code
					, t1.card_name
					, (select name from cmall_inicis_code a where a.code = t1.card_code and a.code_type = 'card') as card_name2
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
					, t1.use_coupon
					, t1.use_coupon_id
					, t1.use_coupon_type

					, date_format(t1.ins_dtm, '%Y/%m/%d') as ins_dtm
					, date_format(t1.ins_dtm, '%Y-%m-%d %H:%i') as order_dtm
					, t1.upd_dtm
					, (SELECT group_concat(a.cit_name) FROM cmall_order_detail a WHERE a.order_id = t1.order_id GROUP BY a.order_id) as cit_name
					, ifnull(t2.order_cnt, '-') as order_cnt
					, ifnull(t2.start_date, '-') as start_date
					, ifnull(t2.delivery_period, '-') as delivery_period
					, if(t2.csu_id is not null, (case when t2.new_date is not null and t2.new_date != '' then t2.new_date
												when t2.last_date is not null and t2.last_date != '' then date_format(DATE_ADD(STR_TO_DATE(t2.last_date, '%Y-%m-%d'), INTERVAL t2.delivery_period * 7 DAY), '%Y-%m-%d')
												ELSE t2.start_date end), '') as check_date
					, ifnull(t3.crf_id, '') as crf_id
					, ifnull(t3.refund_type, '') as refund_type
					, if(t3.refund_type is not null, FN_REFUNDNAME(t3.refund_type), '') as refund_type_name
					, if(t1.delivery_start_dtm is null, '', date_format(t1.delivery_start_dtm, '%Y/%m/%d')) as delivery_start_dtm
					, if(t3.request_dtm is null, '', date_format(t3.request_dtm, '%Y/%m/%d')) as request_dtm
					, if(t3.complete_dtm is null, '', date_format(t3.complete_dtm, '%Y/%m/%d')) as complete_dtm
					, if(t4.ccg_id is null, '', (select sum(qty) from cmall_change_detail a where a.order_id = t1.order_id)) as change_cnt
					, (select count(*) from cmall_order_detail a where a.order_id = t1.order_id) as item_cnt
					, (select count(*) from cmall_review a where a.order_id = t1.order_id) as review_cnt
					, (select 
							b.cit_file_1 
						from 
							cmall_order_detail a 
						inner join cmall_item b on b.cit_id = a.cit_id
						where 
							a.order_id = t1.order_id
						order by a.cod_id desc 
						limit 0, 1) as cit_file_1
					, t1.delivery_invoice
					, t2.csu_id
				FROM
					cmall_order t1
				INNER JOIN member t5 on t5.mem_id = t1.mem_id
				LEFT OUTER JOIN (SELECT
									b.order_id
									, (select count(*) from cmall_subscribe_history aa where aa.csu_id = a.csu_id and aa.csh_id <= b.csh_id) as order_cnt
                                    , a.csu_id
									, a.start_date
                                    , a.last_date
                                    , a.new_date
									, a.delivery_period
								FROM
									cmall_subscribe a
								INNER JOIN cmall_subscribe_history b on b.csu_id = a.csu_id ) t2 on t2.order_id = t1.order_id
				LEFT OUTER JOIN cmall_refund t3 on t3.order_id = t1.order_id
				LEFT OUTER JOIN cmall_change t4 on t4.order_id = t1.order_id
				WHERE
					t1.order_type != 'subscribe'
					AND t1.is_delete = 'n'
					AND t1.order_id = ? ";

		return $this->db->query($sql, array($seq));
	}
	
	public function order_detail2($seq, $mem) {
		$sql = "SELECT
					t1.order_id
					, t1.mem_id
					, t1.status
				FROM
					cmall_order t1
				WHERE
					t1.order_type != 'subscribe'
					AND t1.is_delete = 'n'
					AND t1.order_id = ? 
					AND t1.mem_id = ? ";

		return $this->db->query($sql, array($seq, $mem));
	}
	
	public function order_detail_cancel($seq) {
		$sql = "SELECT
					t1.order_id
					, t1.order_type
					, t1.mem_id
					, FN_DECRYPT(t5.mem_username) as mem_username
					, FN_DECRYPT(t5.mem_email) as mem_email
					, FN_DECRYPT(t5.mem_phone) as mem_phone
					, total_price
					, total_qty
					, use_point
					, delivery_price
					, FN_DECRYPT(t1.recipient_name) as recipient_name
					, FN_DECRYPT(t1.recipient_phone) as recipient_phone
					, FN_DECRYPT(t1.recipient_zip) as recipient_zip
					, FN_DECRYPT(t1.recipient_addr1) as recipient_addr1
					, FN_DECRYPT(t1.recipient_addr2) as recipient_addr2
					, t1.recipient_memo as recipient_memo
					, t1.product_name
					, t1.status
					, FN_STATUSNAME(t1.status) as status_name
					, t1.tid
					, t1.payMethod
					, FN_PAYNAME(t1.payMethod) as payName
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
					, date_format(t1.ins_dtm, '%Y/%m/%d') as ins_dtm
					, date_format(t1.ins_dtm, '%Y-%m-%d %H:%i') as order_dtm
					, t1.upd_dtm
					, t2.crf_id
					, t2.refund_type
					, FN_REFUNDNAME(t2.refund_type) as refund_type_name
					, if(t2.request_dtm is null, '', date_format(t2.request_dtm, '%Y/%m/%d')) as request_dtm
					, if(t2.complete_dtm is null, '', date_format(t2.complete_dtm, '%Y/%m/%d')) as complete_dtm
					, t2.bank_num
					, t2.bank_code
					, t2.bank_name
					, t2.bank_owner
					, t2.payment_status
					, t2.refund_memo
					, t1.use_coupon
					, t1.use_coupon_id
					, t1.use_coupon_type
				FROM
					cmall_order t1
				INNER JOIN member t5 on t5.mem_id = t1.mem_id
				INNER JOIN cmall_refund t2 on t2.order_id = t1.order_id
				WHERE
					t1.order_type != 'subscribe'
					AND t1.is_delete = 'n'
					AND t1.order_id = ? ";

		return $this->db->query($sql, array($seq));
	}
		
	public function order_detail_change($seq) {
		$sql = "SELECT
					t1.order_id
					, t1.order_type
					, t1.mem_id
					, FN_DECRYPT(t5.mem_username) as mem_username
					, FN_DECRYPT(t5.mem_email) as mem_email
					, FN_DECRYPT(t5.mem_phone) as mem_phone
					, t1.total_price
					, t1.total_qty
					, t1.use_point
					, t1.delivery_price
					, FN_DECRYPT(t1.recipient_name) as recipient_name
					, FN_DECRYPT(t1.recipient_phone) as recipient_phone
					, FN_DECRYPT(t1.recipient_zip) as recipient_zip
					, FN_DECRYPT(t1.recipient_addr1) as recipient_addr1
					, FN_DECRYPT(t1.recipient_addr2) as recipient_addr2
					, t1.recipient_memo as recipient_memo
					, t1.product_name
					, t1.status
					, FN_STATUSNAME(t1.status) as status_name
					, t1.tid
					, t1.payMethod
					, FN_PAYNAME(t1.payMethod) as payName
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
					, date_format(t1.ins_dtm, '%Y/%m/%d') as ins_dtm
					, date_format(t1.ins_dtm, '%Y-%m-%d %H:%i') as order_dtm
					, t1.upd_dtm
					, t2.change_type
					, if(t2.request_dtm is null, '', date_format(t2.request_dtm, '%Y/%m/%d')) as request_dtm
					, if(t2.complete_dtm is null, '', date_format(t2.complete_dtm, '%Y/%m/%d')) as complete_dtm
					, t2.reason_msg as change_reason_msg
					, t2.reason_etc as change_reason_etc
					, t2.total_price as change_total_price
					, t2.delivery_price as change_delivery_price
					, FN_DECRYPT(t2.recipient_name) as change_recipient_name
					, FN_DECRYPT(t2.recipient_phone1) as change_recipient_phone1
					, IF(t2.recipient_phone2 is not null, FN_DECRYPT(t2.recipient_phone2), '') as change_recipient_phone2
					, FN_DECRYPT(t2.recipient_zip) as change_recipient_zip
					, FN_DECRYPT(t2.recipient_addr1) as change_recipient_addr1
					, FN_DECRYPT(t2.recipient_addr2) as change_recipient_addr2
					, t2.recipient_memo as change_recipient_memo
					, t2.delivery_way as change_delivery_way
					, t2.refund_bank_name
					, if(t2.refund_bank_num is not null and t2.refund_bank_num != '', FN_DECRYPT(t2.refund_bank_num), '') as refund_bank_num
					, if(t2.refund_bank_owner is not null and t2.refund_bank_owner != '',FN_DECRYPT(t2.refund_bank_owner), '') as refund_bank_owner
				FROM
					cmall_order t1
				INNER JOIN member t5 on t5.mem_id = t1.mem_id
				INNER JOIN cmall_change t2 on t2.order_id = t1.order_id
				WHERE
					t1.order_type != 'subscribe'
					AND t1.is_delete = 'n'
					AND t1.order_id = ? ";

		$info =  $this->db->query($sql, array($seq))->row_array();
		
		$sql = "select
					t1.*
					, cde_title
					, cit_price
				from
					cmall_change_detail t1
				inner join cmall_order_detail t2 on t2.cod_id = t1.cod_id
				where
					t1.order_id = ? ";
		$info['changes'] = $this->db->query($sql, array($seq))->result_array();
		
		return $info;
	}
			
	public function order_detail_list($seq) {
		$this->db->where('order_id', $seq);
		return $this->db->get('cmall_order_detail');	
	}
	
	public function order_cancel($req) {
		$this->db->trans_begin();

		$this->db->where('order_id', $req['order_id']);
		$this->db->set('status', 'CANCEL');
		$this->db->update('cmall_order');
		
		$sql = "insert into cmall_order_history
				(
					order_id
					, old_status
					, new_status
					, change_type
					, ins_user
					, ins_dtm
				)
				VALUES
				(
					?
					, ?
					, ?
					, ?
					, ?
					, now()
				) ";
		$this->db->query($sql, array($req['order_id'], $req['old_status'], $req['new_status'], $req['change_type'], $req['ins_user']));

		$sql = "insert into cmall_refund
				(
					crf_id
					, order_id
					, payment_status
					, refund_type
					, bank_code
					, bank_name
					, bank_owner
					, bank_num
					, refund_memo
					, request_dtm
					, complete_dtm
					, refund_price
				)
				VALUES
				(
					(SELECT
						CONCAT('C', date_format(now(), '%y%m%d'), LPAD(IFNULL(MAX(right(t1.crf_id, 3)), 0) + 1, '3', '0'))  as crf_id
					FROM
						cmall_refund t1)
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
				) ";
		$this->db->query($sql, array($req['order_id'], $req['old_status'], $req['refund_type'], $req['bank_code'], $req['bank_name'], $req['bank_owner'], $req['bank_num'], 
									$req['reason_msg'], $req['refund_price'])); 

		if($req['refund_point'] > 0) {
			$sql = "update member
					set
						mem_point = mem_point + " . $req['refund_point'] . "
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
						, '구매취소'
						, ?
						, 'plus'
						, now()
					) ";
			$this->db->query($sql, array($req['mem_id'], $req['refund_point']));

			$sql = "SELECT * FROM member_point where mem_id = ? and use_val > 0 and exp_dtm >= date_format(now(), '%Y-%m-%d') order by exp_dtm desc ";
			$res = $this->db->query($sql, array($req['mem_id']))->result_array();
			$refund_point = $req['refund_point'];
			foreach($res as $row) {
				if($refund_point > $row['use_val']) {
					$this->db->where('mpo_id', $row['mpo_id']);
					$this->db->set('use_val', 0);
					$this->db->update('member_point');
				}
				else {
					$this->db->where('mpo_id', $row['mpo_id']);
					$this->db->set('use_val', $row['use_val'] - $refund_point);
					$this->db->update('member_point');
					break;
				}
				$refund_point = $refund_point - $row['use_val'];
			}
		}
		
		if(!empty($req['refund_coupon_id'])) {
			$sql = "update cmall_coupon_log
					set
						is_use = 'n'
						, use_val = null
						, order_id = null
					where
						ccl_id = ? ";
			$this->db->query($sql, array($req['refund_coupon_id']));
		}
		
		if($req['order_type'] == 'starter') {
			$this->db->reset_query();
			$this->db->where('mem_id', $req['mem_id']);
			$this->db->set('is_starter', 'n');
			$this->db->update('member');
		}
					
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}
	
	public function update_refund($seq, $val)
	{
		$this->db->where('crf_id', $seq);
		$this->db->update('cmall_refund', $val);
	}
	
	public function order_complete($req)
	{
		$this->db->trans_begin();
		
		$this->db->where('order_id', $req['order_id']);
		$res = $this->db->get('cmall_order')->row_array();
		
		$this->db->reset_query();
		$this->db->where('order_id', $req['order_id']);
		$this->db->set('complete_dtm', 'now()', false);
		$this->db->set('complete_type', 'user');
		$this->db->set('status', 'COMPLETE');
		$this->db->update('cmall_order');
		
		$sql = "insert into cmall_order_history
				(
					order_id
					, old_status
					, new_status
					, change_type
					, ins_user
					, ins_dtm
				)
				VALUES
				(
					?
					, ?
					, ?
					, ?
					, ?
					, now()
				) ";
		$this->db->query($sql, array($req['order_id'], $req['old_status'], $req['new_status'], $req['change_type'], $req['ins_user']));
		
		$points = $this->db->get('cmall_point_manage')->row_array();
		if($res['order_type'] == 'billing') {
			$sql = "select
						order_cnt
					FROM
						cmall_subscribe t1
					WHERE
						t1.csu_id = (select a.csu_id from cmall_subscribe_history a where order_id = ? AND a.result_code = '00') ";
			$cnt = $this->db->query($sql, array($req['order_id']))->row_array();
			$point = 0;
			if($cnt['order_cnt'] == 1) {
				$point = $res['total_price'] * ($points['subscribe_1'] / 100);
			}
			else if($cnt['order_cnt'] == 2) {
				$point = $res['total_price'] * ($points['subscribe_2'] / 100);
			}
			else if($cnt['order_cnt'] == 3) {
				$point = $res['total_price'] * ($points['subscribe_3'] / 100);
			}
			else if($cnt['order_cnt'] == 4) {
				$point = $res['total_price'] * ($points['subscribe_4'] / 100);
			}
			else if($cnt['order_cnt'] == 5) {
				$point = $res['total_price'] * ($points['subscribe_5'] / 100);
			}
			else if($cnt['order_cnt'] > 5) {
				$point = $res['total_price'] * ($points['subscribe_6'] / 100);
			}
		}
		else {
			$point = ($res['total_price'] - $res['delivery_price']) * ($points['item'] / 100);
		}
		
		$this->db->reset_query();
		$this->db->set('mem_id', $req['mem_id']);
		$this->db->set('add_type', 'order');
		$this->db->set('add_val', $point);
		$this->db->set('rest_val', '0');
		$this->db->set('use_val', '0');
		$this->db->set('ins_dtm', date('Y-m-d H:i:s'));
		$this->db->set('exp_dtm', date('Y-m-d', strtotime('+1 year')));
		$this->db->insert('member_point');
		$mpo_id = $this->db->insert_id();
		
		$this->db->reset_query();
		$this->db->set('mem_id', $req['mem_id']);
		$this->db->set('point_type', '상품구매');
		$this->db->set('point_val', $point);
		$this->db->set('point_dir', 'plus');
		$this->db->set('ins_dtm', date('Y-m-d H:i:s'));
		$this->db->set('mpo_id', $mpo_id);
		$this->db->insert('member_point_log');
		
		$this->db->reset_query();
		$this->db->where('mem_id', $req['mem_id']);
		$this->db->set('mem_point', 'mem_point + ' . $point, false);
		$this->db->update('member');
		
		if($req['use_coupon_type'] === '3') {
			$this->db->reset_query();
			$this->db->set('mem_id', $req['mem_id']);
			$this->db->set('add_type', 'coupon');
			$this->db->set('add_val', $req['use_coupon']);
			$this->db->set('rest_val', '0');
			$this->db->set('use_val', '0');
			$this->db->set('ins_dtm', date('Y-m-d H:i:s'));
			$this->db->set('exp_dtm', date('Y-m-d', strtotime('+1 year')));
			$this->db->insert('member_point');
			$mpo_id = $this->db->insert_id();
			
			$this->db->reset_query();
			$this->db->set('mem_id', $req['mem_id']);
			$this->db->set('point_type', '쿠폰증정(상품구매)');
			$this->db->set('point_val', $req['use_coupon']);
			$this->db->set('point_dir', 'plus');
			$this->db->set('ins_dtm', date('Y-m-d H:i:s'));
			$this->db->set('mpo_id', $mpo_id);
			$this->db->insert('member_point_log');
			
			$this->db->reset_query();
			$this->db->where('mem_id', $req['mem_id']);
			$this->db->set('mem_point', 'mem_point + ' . $req['use_coupon'], false);
			$this->db->update('member');
		}		
		
		$sql = "SELECT
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
				FROM
					cmall_coupon t2
				WHERE
					date_format(now(), '%Y-%m-%d %H:%i') between concat(t2.down_start_date, ' ', t2.down_start_time) and concat(t2.down_end_date, ' ', t2.down_end_time) 
					and down_type = '2'
					and event_type = '2' 
					and is_delete = 'n' ";
		$coupon = $this->db->query($sql, array())->result_array();
		
		if(!empty($coupon)) {				
			foreach($coupon as $row) {
				if($row['ccp_type'] === '3' && $row['point_type'] === '1') {
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
				}
				else {
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
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}
	
	public function change_request($req)
	{
		$this->db->trans_begin();

		$items = array();
		$sum = 0;
		if($req['order_type'] == 'starter') {
			$sum = $req['total_price'];
			$this->db->where('order_id', $req['order_id']);
			$res = $this->db->get('cmall_order_detail')->result_array();
			foreach($res as $row) {
				$tmp = array();
				$tmp['cod_id'] = $row['cod_id'];
				$tmp['unit_price'] = 0;
				$tmp['qty'] = 1;
				$tmp['order_id'] = $req['order_id'];
				$tmp['cit_name'] = $row['cit_name'];
				$items[] = $tmp;
			}
		}
		else {
			for($i = 0; $i < count($req['chk']); $i++) {
				for($j = 0; $j < count($req['cod_id']); $j++) {
					if($req['chk'][$i] == $req['cod_id'][$j]) {
						$tmp = array();
						$tmp['cod_id'] = $req['cod_id'][$j];
						$tmp['unit_price'] = $req['unit_price'][$j];
						$tmp['qty'] = $req['req_qty'][$j];
						$tmp['order_id'] = $req['order_id'];
						$tmp['cit_name'] = $req['cit_name'][$i];
						$items[] = $tmp;
						$sum += $req['unit_price'][$i] * $req['req_qty'][$i];
						break;	
					}
				}
			}
		}
		
		$this->db->set('order_type', $req['order_type']);
		$this->db->set('change_type', $req['action_type']);
		$this->db->set('status', $req['new_status']);
		$this->db->set('order_id', $req['order_id']);
		$this->db->set('mem_id', $req['ins_user']);
		$this->db->set('reason_code', $req['reason_code']);
		$this->db->set('reason_msg', $req['reason']);
		$this->db->set('reason_etc', $req['reason_etc']);
		$this->db->set('delivery_price', $req['reason_code'] == '1' ? 6000 : 0);
		$this->db->set('total_price', $sum);
		$this->db->set('recipient_name', "FN_ENCRYPT('" . $req['recipient_name'] . "')", false);
		$this->db->set('recipient_phone1', "FN_ENCRYPT('" . $req['recipient_phone'] . "')", false);
		if(!empty($req['recipient_phone2'])) {
			$this->db->set('recipient_phone2', "FN_ENCRYPT('" . $req['recipient_phone2'] . "')", false);
		}
		$this->db->set('recipient_zip', "FN_ENCRYPT('" . $req['recipient_zip'] . "')", false);
		$this->db->set('recipient_addr1', "FN_ENCRYPT('" . $req['recipient_addr1'] . "')", false);
		$this->db->set('recipient_addr2', "FN_ENCRYPT('" . $req['recipient_addr2'] . "')", false);
		$this->db->set('request_dtm', 'now()', false);
		if($req['action_type'] == 'return') {
			$this->db->set('refund_bank_code', $req['bank_code']);			
			$this->db->set('refund_bank_name', "(select name from cmall_inicis_code a where a.code_type='bank' and code='" . $req['bank_code'] . "')", false);
			$this->db->set('refund_bank_owner', "FN_ENCRYPT('" . $req['bank_owner'] . "')", false);
			$this->db->set('refund_bank_num', "FN_ENCRYPT('" . $req['bank_num'] . "')", false);
		}
		if(isset($req['delivery_way'])) {
			$this->db->set('delivery_way', $req['delivery_way']);	
		}
		$this->db->insert('cmall_change');
		$id = $this->db->insert_id();
		
		for($i = 0; $i < count($items); $i++) {
			$items[$i]['ccg_id'] = 	$id;
		}
		$this->db->reset_query();
		$this->db->insert_batch('cmall_change_detail', $items);
		
		if(isset($req['newname'])) {
			$files = array();
			for($i = 0; $i < count($req['newname']); $i++) {
				$tmp = array();
				$tmp['parent_gbn'] = 'change';
				$tmp['parent_cd'] = $id;
				$tmp['file_no'] = $i;
				$tmp['org_filename'] = $req['orgname'][$i];
				$tmp['new_filepath'] = $req['filepath'][$i];
				$tmp['new_filename'] = $req['newname'][$i];
				$tmp['file_ext'] = $req['ext'][$i];
				$tmp['file_size'] = $req['size'][$i];
				$files[] = $tmp;
			}
			$this->db->reset_query();
			$this->db->insert_batch('cmall_file', $files);
		}
		
		$this->db->reset_query();
		$this->db->where('order_id', $req['order_id']);
		$this->db->set('status', $req['new_status']);
		$this->db->update('cmall_order');

		$this->db->reset_query();
		$sql = "insert into cmall_order_history
				(
					order_id
					, old_status
					, new_status
					, change_type
					, ins_user
					, ins_dtm
				)
				VALUES
				(
					?
					, ?
					, ?
					, ?
					, ?
					, now()
				) ";
		$this->db->query($sql, array($req['order_id'], $req['old_status'], $req['new_status'], $req['change_type'], $req['ins_user']));
		
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}
	
	public function check_order($req)
	{
		$sql = "select
					count(*) as cnt
				from
					cmall_order
				where
					mem_id = ?
					and (status != 'COMPLETE' and status != 'CANCEL' and status != 'REFUND_COMPLETE' and status != 'RETURN_COMPLETE' and status != 'CHANGE_COMPLETE')
					and is_delete = 'n' ";
					
		$tmp = $this->db->query($sql, array($req['mem_id']))->row_array();
		return $tmp['cnt'];
	}
	
	public function delivery_change($val)
	{
		$this->db->trans_begin();

		if($val['is_default'] == 'y') {
			$this->db->where('mem_id', $val['mem_id']);
			$this->db->set('is_default', 'n');
			$this->db->update('member_delivery');
		}

		if(empty($val['mde_id'])) {
			$sql = "insert into
						member_delivery
					(
						mde_title
						, mem_id
						, recipient_name
						, recipient_phone
						, zipcode
						, road_addr
						, jibun_addr
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
						, FN_ENCRYPT(?)
						, ?
						, 'n'
						, now()
						, now()
						, ?
					)";
			$this->db->query($sql, array($val['recipient_name']
										, $val['mem_id']
										, $val['recipient_name']
										, $val['recipient_phone']
										, $val['zipcode']
										, $val['road_addr']
										, $val['jibun_addr']
										, $val['detail_addr']
										, $val['is_default']
										, $val['memo']));
		}
		else {
			$this->db->where('mde_id', $val['mde_id']);
			$this->db->set('is_default', 'y');
			$this->db->update('member_delivery');
		}
		
		$sql = "update cmall_order
				set
					recipient_name = FN_ENCRYPT(?)
					, recipient_phone = FN_ENCRYPT(?)
					, recipient_zip = FN_ENCRYPT(?)
					, recipient_addr1 = FN_ENCRYPT(?)
					, recipient_addr2 = FN_ENCRYPT(?)
					, recipient_memo = ?
				where
					order_id = ? ";

		$this->db->query($sql, array($val['recipient_name']
									, $val['recipient_phone']
									, $val['zipcode']
									, $val['road_addr']
									, $val['detail_addr']
									, $val['memo']
									, $val['order_id']));
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		} 
	}
}