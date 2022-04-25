<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscribe_m extends CI_Model 
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
					, FN_DECRYPT(t6.mem_username) as mem_username
					, FN_DECRYPT(t6.mem_email) as mem_email
					, FN_DECRYPT(t6.mem_phone) as mem_phone
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
					, if(t4.ccg_id is null, '', (select sum(qty) from cmall_change_detail a where a.order_id = t1.order_id)) as change_cnt
					, if(t4.request_dtm is null, '', date_format(t4.request_dtm, '%Y/%m/%d')) as change_request_dtm
					, if(t4.complete_dtm is null, '', date_format(t4.complete_dtm, '%Y/%m/%d')) as change_complete_dtm
					, (select count(*) from cmall_order_detail a where a.order_id = t1.order_id) as item_cnt
					, (select count(*) from cmall_review a where a.order_id = t1.order_id) as review_cnt
				FROM
					cmall_subscribe_history t5
				INNER JOIN cmall_order t1 on t1.order_id = t5.order_id and t1.order_type = 'billing' and t1.is_delete = 'n' AND t1.mem_id = ? 
				INNER JOIN member t6 on t6.mem_id = t1.mem_id
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
					t5.csu_id = ?
					and t5.result_code = '00'
				ORDER BY t1.ins_dtm DESC 
				LIMIT ?, ? ";

		return $this->db->query($sql, array($req['mem_id'], $req['seq'], $offset, $perpage));
	}

	public function order_list_cnt($req) {
		$sql = "SELECT
					count(*) as cnt
				FROM
					cmall_subscribe_history t5
				INNER JOIN cmall_order t1 on t1.order_id = t5.order_id and t1.order_type = 'billing' and t1.is_delete = 'n' AND t1.mem_id = ? 
				INNER JOIN member t6 on t6.mem_id = t1.mem_id
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
					t5.csu_id = ? 
					and t5.result_code = '00' ";
				
		$tmp = $this->db->query($sql, array($req['mem_id'], $req['seq']))->row_array();
		return $tmp['cnt'];
	}
	
	public function order_detail($seq) {
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
					, date_format(t1.ins_dtm, '%Y/%m/%d') as ins_dtm
					, date_format(t1.ins_dtm, '%Y-%m-%d %H:%i') as order_dtm
					, t1.upd_dtm
					, (SELECT group_concat(a.cit_name) FROM cmall_order_detail a WHERE a.order_id = t1.order_id GROUP BY a.order_id) as cit_name
					, ifnull(t2.order_cnt, '-') as order_cnt
					, ifnull(t2.start_date, '-') as start_date
					, ifnull(t2.delivery_period, '-') as delivery_period
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
				FROM
					cmall_order t1
				INNER JOIN member t5 on t5.mem_id = t1.mem_id
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
					AND t1.order_id = ? ";

		return $this->db->query($sql, array($seq));
	}

	public function subscribe_list($req, $offset, $perpage)
	{
		$sql = "SELECT
					t1.csu_id
					, t1.csu_title
					, t1.order_id
					, t1.mem_id
					, t1.delivery_day
					, t1.delivery_period
					, t1.start_date
					, t1.last_date
					, t1.new_date
					, t1.order_cnt
					, t1.is_cancel
					, t1.ins_dtm
					, (SELECT
							b.cit_file_1
						FROM
							cmall_subscribe_detail a
						INNER JOIN cmall_item b on b.cit_id = a.cit_id and a.csu_id = t1.csu_id
						LIMIT 1) as img_file
					, (select sum(cit_subscribe_price * qty) from cmall_subscribe_detail a where a.csu_id = t1.csu_id) as total_price
				FROM
					cmall_subscribe t1
				WHERE
					t1.mem_id = ?
					AND is_cancel = 'n' 
				ORDER BY is_cancel, t1.ins_dtm desc 
				limit ?, ? ";
		return $this->db->query($sql, array($req['mem_id'], $offset, $perpage));
	}

	public function subscribe_list_cnt($req)
	{
		$sql = "SELECT
					count(*) as cnt
				FROM
					cmall_subscribe t1
				WHERE
					t1.mem_id = ?
					AND is_cancel = 'n' 
				ORDER BY is_cancel, t1.ins_dtm desc  ";
		$tmp = $this->db->query($sql, array($req['mem_id']))->row_array();
		return $tmp['cnt'];
	}

	public function subscribe_list_all($req)
	{
		$sql = "SELECT
					t1.csu_id
					, t1.csu_title
					, t1.order_id
					, t1.mem_id
					, t1.delivery_day
					, t1.delivery_period
					, t1.start_date
					, t1.last_date
					, t1.new_date
					, t1.order_cnt
					, t1.is_cancel
					, t1.ins_dtm
					, (SELECT
							b.cit_file_1
						FROM
							cmall_subscribe_detail a
						INNER JOIN cmall_item b on b.cit_id = a.cit_id and a.csu_id = t1.csu_id
						LIMIT 1) as img_file
					, (select sum(cit_subscribe_price * qty) from cmall_subscribe_detail a where a.csu_id = t1.csu_id) as total_price
				FROM
					cmall_subscribe t1
				WHERE
					t1.mem_id = ?
					AND is_cancel = 'n' 
				ORDER BY is_cancel, t1.ins_dtm desc  ";
		return $this->db->query($sql, array($req['mem_id']));
	}

	public function subscribe_total_cnt()
	{
		$sql = "SELECT
					count(*) as cnt
				FROM
					cmall_subscribe t1
				WHERE
					is_cancel = 'n' ";
		$tmp = $this->db->query($sql, array())->row_array();
		return $tmp['cnt'];
	}

	public function subscribe_info($seq, $id)
	{
		$sql = "SELECT
					t1.csu_id
					, t1.csu_title
					, t1.order_id
					, t1.mem_id
					, t1.delivery_day
					, t1.delivery_period
					, t1.start_date
					, t1.last_date
					, t1.new_date
					, t1.order_cnt
					, t1.is_cancel
					, t1.ins_dtm
					, t1.billing_key
					, t1.card_num
					, t1.card_code
					, t1.card_name
					, FN_DECRYPT(t1.recipient_name) as recipient_name
					, FN_DECRYPT(t1.recipient_phone) as recipient_phone
					, FN_DECRYPT(t1.recipient_zip) as recipient_zip
					, FN_DECRYPT(t1.recipient_addr1) as recipient_addr1
					, FN_DECRYPT(t1.recipient_addr2) as recipient_addr2
					, t1.recipient_memo
					, (SELECT name FROM cmall_inicis_code a where a.code = t1.card_code and a.code_type = 'card') as card_code_name
					, (SELECT
							b.cit_file_1
						FROM
							cmall_subscribe_detail a
						INNER JOIN cmall_item b on b.cit_id = a.cit_id and a.csu_id = t1.csu_id
						LIMIT 1) as img_file
					, (SELECT SUM(cit_price) FROM cmall_subscribe_detail a where a.csu_id = t1.csu_id) as org_price
					, (SELECT SUM(cit_subscribe_price * qty) FROM cmall_subscribe_detail a where a.csu_id = t1.csu_id) as total_price
					, (SELECT SUM(qty) FROM cmall_subscribe_detail a where a.csu_id = t1.csu_id) as total_qty
				FROM
					cmall_subscribe t1
				WHERE
					t1.csu_id = ? 
					and t1.mem_id = ?
					and t1.is_cancel = 'n' ";
		return $this->db->query($sql, array($seq, $id));
	}

	public function subscribe_info2($seq)
	{
		$sql = "SELECT
					t1.csu_id
					, t1.csu_title
					, t1.order_id
					, t1.mem_id
					, t1.delivery_day
					, t1.delivery_period
					, t1.start_date
					, t1.last_date
					, t1.new_date
					, t1.order_cnt
					, t1.is_cancel
					, t1.ins_dtm
					, t1.billing_key
					, t1.card_num
					, t1.card_code
					, t1.card_name
                                        , t1.payMethod 
					, FN_DECRYPT(t1.recipient_name) as recipient_name
					, FN_DECRYPT(t1.recipient_phone) as recipient_phone
					, FN_DECRYPT(t1.recipient_zip) as recipient_zip
					, FN_DECRYPT(t1.recipient_addr1) as recipient_addr1
					, FN_DECRYPT(t1.recipient_addr2) as recipient_addr2
					, t1.recipient_memo
					, (SELECT name FROM cmall_inicis_code a where a.code = t1.card_code and a.code_type = 'card') as card_code_name
					, (SELECT
							b.cit_file_1
						FROM
							cmall_subscribe_detail a
						INNER JOIN cmall_item b on b.cit_id = a.cit_id and a.csu_id = t1.csu_id
						LIMIT 1) as img_file
					, (SELECT SUM(cit_price) FROM cmall_subscribe_detail a where a.csu_id = t1.csu_id) as org_price
					, (SELECT SUM(cit_subscribe_price) FROM cmall_subscribe_detail a where a.csu_id = t1.csu_id) as total_price
					, (SELECT SUM(qty) FROM cmall_subscribe_detail a where a.csu_id = t1.csu_id) as total_qty
				FROM
					cmall_subscribe t1
				WHERE
					t1.csu_id = ?  ";
		return $this->db->query($sql, array($seq));
	}

	public function subscribe_detail_list($seq)
	{
		$sql = "SELECT
					*
				FROM
					cmall_subscribe_detail t1
				WHERE
					t1.csu_id = ? ";
		return $this->db->query($sql, array($seq));
	}
	
	public function update_subscribe($req)
	{
		$this->db->trans_begin();
		
		$this->db->where('csu_id', $req['csu_id']);
		if(!empty($req['new_period']) && $req['org_period'] != $req['new_period']) {
			$this->db->set('delivery_period', $req['new_period']);	
		}
		if(!empty($req['new_date']) && $req['org_date'] != $req['new_date']) {
			$this->db->set('new_date', $req['new_date']);	
		}
		$this->db->update('cmall_subscribe');
		
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}

	public function update_subscribe_addr($req)
	{
		$this->db->trans_begin();

		$this->db->where('csu_id', $req['csu_id']);
		$this->db->set('recipient_name', "FN_ENCRYPT('" . $req['recipient_name'] . "')", false);	
		$this->db->set('recipient_phone', "FN_ENCRYPT('" . $req['recipient_phone'] . "')", false);	
		$this->db->set('recipient_zip', "FN_ENCRYPT('" . $req['recipient_zip'] . "')", false);	
		$this->db->set('recipient_addr1', "FN_ENCRYPT('" . $req['recipient_addr1'] . "')", false);	
		$this->db->set('recipient_addr2', "FN_ENCRYPT('" . $req['recipient_addr2'] . "')", false);	
		$this->db->set('recipient_memo', $req['recipient_memo']);
		$this->db->update('cmall_subscribe');

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}

	public function update_subscribe_title($req)
	{
		$this->db->trans_begin();

		$this->db->where('csu_id', $req['csu_id']);
		$this->db->set('csu_title', $req['csu_title']);	
		$this->db->update('cmall_subscribe');

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}
	
	public function cancel_subscribe($req)
	{
		$this->db->trans_begin();
		
		$this->db->where('csu_id', $req['csu_id']);
		$this->db->set('is_cancel', 'y');
		$this->db->set('cancel_dtm', 'now()', false);
		$this->db->update('cmall_subscribe');
		
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}
	
	public function subscribe_order_info($seq)
	{
		$sql = "select
					t1.order_id
					, t2.total_price
				from
					cmall_subscribe_history t1
				inner join cmall_order t2 on t2.order_id = t1.order_id and t2.status = 'PAYMENT' and t2.order_type = 'billing' and t2.is_delete = 'n'
				where
					t1.result_code = '00'
					and t1.csu_id = ? ";
		return $this->db->query($sql, array($seq));
	}
}