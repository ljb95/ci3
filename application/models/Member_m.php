<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_m extends CI_Model 
{
	public function __construct()
	{
        parent::__construct();		
		$this->load->database();
	}

	public function member_info($seq)
	{
		$sql = "SELECT
					t1.mem_id
					, FN_DECRYPT(t1.mem_username) as mem_username
					, FN_DECRYPT(t1.mem_phone) as mem_phone
					, FN_DECRYPT(t1.mem_userid) as mem_userid
					, FN_DECRYPT(t1.mem_email) as mem_email
					, mem_point
					, is_rcv_email
					, is_rcv_sms
					, is_rcv_kakao
					, mem_sns_type
					, is_starter
					, mem_sns_type
				FROM
					member t1
				WHERE
					mem_id = ? ";
		return $this->db->query($sql, array($seq));
	}

	public function member_info_for_home($seq)
	{
		$sql = "SELECT
					t1.mem_id
					, mem_point
					, (select count(*) from cmall_order a where a.mem_id = t1.mem_id and a.is_delete = 'n' and a.order_type = 'item' and a.status = 'COMPLETE') as item_cnt
					, (select count(*) from cmall_order a where a.mem_id = t1.mem_id and a.is_delete = 'n' and a.order_type = 'billing' and a.status = 'COMPLETE') as billing_cnt
					, (select count(*) from cmall_diagnosis a where a.mem_id = t1.mem_id and a.is_delete = 'n' ) as diagnosis_cnt
					, (select count(*) from cmall_coupon_log a inner join cmall_coupon b on b.ccp_id = a.ccp_id and b.is_delete = 'n' where a.mem_id = t1.mem_id and a.is_use = 'n' ) as coupon_cnt
				FROM
					member t1
				WHERE
					mem_id = ? ";
		return $this->db->query($sql, array($seq));
	}
	
	public function member_c($values) 
	{
		$sql = "insert into member 
				set
					mem_userid = FN_ENCRYPT(?)
				,	mem_email = FN_ENCRYPT(?)
				,	mem_password = SHA2(?, 512) 
				,	mem_username = FN_ENCRYPT(?)
				,	mem_phone = FN_ENCRYPT(?)
				,	mem_sns_type = ?
				,	is_rcv_sms = ?
				,	is_rcv_email = ?
				,	is_rcv_kakao = ?
				,	mem_register_datetime = ?
				,	mem_register_ip = ?
				";
		return $this->db->query($sql, $values);
	}
	
	public function member_c2($values) 
	{
		$sql = "insert into member 
				set
					mem_userid = FN_ENCRYPT(?)
				,	mem_email = FN_ENCRYPT(?)
				,	mem_username = FN_ENCRYPT(?)
				,	mem_sns_type = ?
				,	mem_register_datetime = ?
				,	mem_register_ip = ?
				";
		return $this->db->query($sql, $values);
	}
	
	public function member_r($where) 
	{	
//		$this->db->where('mem_email', $where['mem_email']);
//		return $this->db->get('member');
		
		$sql = "select *
				from member
				where mem_email = FN_ENCRYPT(?)
				";
		return $this->db->query($sql, $where);
	}
	
	public function member_r2($where) 
	{	
		$sql = "select *
				from member
				where mem_userid = FN_ENCRYPT(?)
				and mem_password = SHA2(?, 512)
				";
		return $this->db->query($sql, $where);
	}
	
	public function member_r3($where) 
	{	
		$sql = "select *
				from member
				where mem_userid = FN_ENCRYPT(?)
				and mem_sns_type = ?
				";
		return $this->db->query($sql, $where);
	}
	
	public function member_r4($where) 
	{	
		$this->db->where('mem_id', $where['mem_id']);
		return $this->db->get('member');
	}
	
	public function member_r5($where) 
	{	
		$sql = "select
					mem_id
				,	FN_DECRYPT(mem_userid) mem_userid
				,	FN_DECRYPT(mem_username) mem_username
				,	FN_DECRYPT(mem_phone) mem_phone
				,	mem_sns_type
				,	mem_register_datetime
				from member
				where mem_username = FN_ENCRYPT(?)
				and mem_phone = FN_ENCRYPT(?)
				";
		return $this->db->query($sql, $where);
	}
	
	public function member_r6($where) 
	{	
		$sql = "select *
				from member
				where 
					mem_id = ?
					and mem_password = SHA2(?, 512)	";
		return $this->db->query($sql, $where);
	}
	
	public function member_r7($where) 
	{	
		$sql = "select *
				from member
				where mem_username = FN_ENCRYPT(?)
				and mem_email = FN_ENCRYPT(?)
				";
		return $this->db->query($sql, $where);
	}
	
	public function member_r8($where) 
	{	
		$this->db->where('otp', $where['otp']);
		return $this->db->get('member');
	}
	
	public function member_u($set, $where)
	{
		$sql = "update member 
				set
					mem_email = FN_ENCRYPT(?)
				,	mem_username = FN_ENCRYPT(?)
				,	mem_phone = FN_ENCRYPT(?)
				,	is_rcv_sms = ?
				,	is_rcv_email = ?
				,	is_rcv_kakao = ?
				where mem_id = ?
				";
		return $this->db->query($sql, array_merge($set, $where));
	}
	
	public function member_u2($set, $where)
	{
		$sql = "update member 
				set
					mem_password = SHA2(?, 512) 
				where mem_id = ?
				";
		return $this->db->query($sql, array_merge($set, $where));
	}

	public function member_u3($val, $mem_id)
	{
		$this->db->where('mem_id', $mem_id);
		$this->db->update('member', $val);
	}

	public function member_u4($val)
	{
		$sql = "update member 
				set
					mem_password = SHA2(?, 512) 
				where mem_id = ?
				";
		return $this->db->query($sql, array($val['mem_password'], $val['mem_id']));
	}
	
	public function member_u5($val)
	{
		$sql = "update member 
				set
					mem_username = FN_ENCRYPT(?)
				,	mem_phone = FN_ENCRYPT(?)
				where mem_id = ?
				";
		return $this->db->query($sql, array($val['mem_username'], $val['mem_phone'], $val['mem_id']));
	}
	
	public function member_u6($set, $where)
	{
		$sql = "update member 
				set
					mem_password = SHA2(?, 512) 
				,	otp = ?
				,	otp_limit_dt = ?
				where mem_id = ?
				";
		return $this->db->query($sql, array_merge($set, $where));
	}

	public function member_login_log_c($values) 
	{	
        $this->db->insert('member_login_log', $values);
    }
	
	public function member_leave($req)
	{
		$this->db->trans_begin();

		$this->db->where('mem_id', $req['mem_id']);
		$this->db->set('mem_username', '');
		$this->db->set('mem_password', '');
		$this->db->set('mem_phone', '');
		$this->db->set('mem_sns_type', '');
		$this->db->set('mem_email', '');
		$this->db->set('is_leave', 'y');
		$this->db->set('leave_reason_code', $req['leave_reason_code']);
		$this->db->set('leave_reason_msg', $req['leave_reason_msg']);
		$this->db->set('leave_dtm', 'now()', false);
		$this->db->update('member');

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}
	
	public function update_rcv_status($seq, $val)
	{
		$this->db->trans_begin();

		$this->db->where('mem_id', $seq);
		$this->db->set('is_rcv_email', $val);
		$this->db->set('is_rcv_sms', $val);
		$this->db->set('is_rcv_kakao', $val);
		$this->db->update('member');	

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}
}
