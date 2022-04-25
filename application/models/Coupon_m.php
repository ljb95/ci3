<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coupon_m extends CI_Model 
{
	public function __construct()
	{
        parent::__construct();		
		$this->load->database();
	}

	public function coupon_list($req, $offset, $perpage)
	{
		$sql = "SELECT
					t1.is_use
					, date_format(t1.use_dtm, '%Y/%m/%d') as use_dtm
					, date_format(t1.down_dtm, '%Y/%m/%d') as down_dtm
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
					, (select group_concat(a.cit_name) from cmall_item a where FIND_IN_SET(a.cit_id, t2.product_id)) as product_name
				FROM
					cmall_coupon_log t1
				INNER JOIN cmall_coupon t2 on t2.ccp_id = t1.ccp_id and t2.is_delete = 'n'
				WHERE
					t1.mem_id = ?  
				ORDER BY t2.use_start_date desc, t2.use_start_time desc
				LIMIT ?, ? ";
		return $this->db->query($sql, array($req['mem_id'], $offset, $perpage));
	}

	public function coupon_list_cnt($req)
	{
		$sql = "SELECT
					count(*) as cnt
				FROM
					cmall_coupon_log t1
				INNER JOIN cmall_coupon t2 on t2.ccp_id = t1.ccp_id and t2.is_delete = 'n' 
				WHERE
					t1.mem_id = ?  ";
		$tmp = $this->db->query($sql, array($req['mem_id']))->row_array();
		return $tmp['cnt'];
	}

	public function coupon_down_list($req, $offset, $perpage)
	{
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
						, (select group_concat(a.cit_name) from cmall_item a where FIND_IN_SET(a.cit_id, t2.product_id)) as product_name
						, ifnull((select count(*) as cnt from cmall_coupon_log a where a.ccp_id = t2.ccp_id and a.mem_id = ?), '') as ccl_cnt
					FROM
						cmall_coupon t2
					WHERE
						date_format(now(), '%Y-%m-%d %H:%i') between concat(t2.down_start_date, ' ', t2.down_start_time) and concat(t2.down_end_date, ' ', t2.down_end_time) 
						and t2.is_delete = 'n'
				) TB1
				WHERE
					TB1.ccl_cnt = 0
				ORDER BY TB1.use_start_date desc, TB1.use_start_time desc
				LIMIT ?, ? ";
		return $this->db->query($sql, array($req['mem_id'], $offset, $perpage));
	}

	public function coupon_down_list_cnt($req)
	{
		$sql = "SELECT
					count(*) as cnt
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
						, ifnull((select group_concat(a.cit_name) from cmall_item a where FIND_IN_SET(a.cit_id, t2.product_id)), '') as product_name
						, ifnull((select count(*) as cnt from cmall_coupon_log a where a.ccp_id = t2.ccp_id and a.mem_id = ?), '') as ccl_cnt
					FROM
						cmall_coupon t2
					WHERE
						date_format(now(), '%Y-%m-%d %H:%i') between concat(t2.down_start_date, ' ', t2.down_start_time) and concat(t2.down_end_date, ' ', t2.down_end_time) 
						and t2.is_delete = 'n'
				) TB1
				WHERE
					TB1.ccl_cnt = 0 ";
		$tmp = $this->db->query($sql, array($req['mem_id']))->row_array();
		return $tmp['cnt'];
	}
	
	public function coupon_auto_list($req)
	{
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
						and t2.is_delete = 'n'
						and down_type = '2'
						and event_type = ?
				) TB1
				WHERE
					TB1.ccl_id = '' ";
		return $this->db->query($sql, array($req['mem_id'], $req['event_type']));
	}


	public function coupon_list_for_cart($seq, $product, $sum)
	{
		$sql = "SELECT
					t1.is_use
					, t1.ccl_id
					, date_format(t1.use_dtm, '%Y/%m/%d') as use_dtm
					, date_format(t1.down_dtm, '%Y/%m/%d') as down_dtm
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
					, t2.product_id
				FROM
					cmall_coupon_log t1
				INNER JOIN cmall_coupon t2 on t2.ccp_id = t1.ccp_id and t2.is_delete = 'n'
				WHERE
					t1.mem_id = ?  
					and t1.is_use = 'n'
					and date_format(now(), '%Y-%m-%d %H:%i') between concat(t2.use_start_date, ' ', t2.use_start_time) and concat(t2.use_end_date, ' ', t2.use_end_time) 
					and " . $sum . " >= (case t2.use_min when 'y' then t2.min_val else " . $sum . " end) ";
		if(!empty($product)) {
			$or = array();
			foreach($product as $id) {
				$or[] = " FIND_IN_SET('" . $id . "', t2.product_id) ";
			}
			$sql .= " and (t2.product_id = '' or " . implode(' or ', $or) . ") ";
		}
		$sql .= " ORDER BY t2.use_start_date desc, t2.use_start_time desc ";

		return $this->db->query($sql, array($seq));
	}

	public function coupon_cnt_for_cart($seq)
	{
		$sql = "SELECT
					count(*) as cnt
				FROM
					cmall_coupon_log t1
				INNER JOIN cmall_coupon t2 on t2.ccp_id = t1.ccp_id and t2.is_delete = 'n'
				WHERE
					t1.mem_id = ?  
					and t1.is_use = 'n'
					and date_format(now(), '%Y-%m-%d %H:%i') between concat(t2.use_start_date, ' ', t2.use_start_time) and concat(t2.use_end_date, ' ', t2.use_end_time) ";
		$tmp = $this->db->query($sql, array($seq))->row_array();
		return $tmp['cnt'];
	}
	
	public function coupon_download($req)
	{
		$this->db->trans_begin();
		
		$this->db->where('ccp_id', $req['ccp_id']);
		$res = $this->db->get('cmall_coupon')->row_array();
		
		if($res['ccp_type'] === '3' && $res['point_type'] === '1') {
			$this->db->set('mem_id', $req['mem_id']);
			$this->db->set('ccp_id', $req['ccp_id']);
			$this->db->set('is_use', 'y');
			$this->db->set('use_dtm', 'now()', false);
			$this->db->set('use_val', $res['ccp_val']);
			$this->db->set('down_type', 'user');
			$this->db->set('down_dtm', 'now()', false);
			$this->db->set('down_user', $req['mem_email']);
			$this->db->insert('cmall_coupon_log');

			$sql = "update member
					set
						mem_point = mem_point + " . $res['ccp_val'] . "
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
						, " . $res['ccp_val'] . "
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
						, '쿠폰증정'
						, " . $res['ccp_val'] . "
						, 'plus'
						, now()
						, 'admin'
						, ?
					) ";		
			$this->db->query($sql, array($req['mem_id'], $req['mem_id']));
		}
		else {
			$this->db->set('mem_id', $req['mem_id']);
			$this->db->set('ccp_id', $req['ccp_id']);
			$this->db->set('is_use', 'n');
			$this->db->set('down_type', 'user');
			$this->db->set('down_dtm', 'now()', false);
			$this->db->set('down_user', $req['mem_email']);
			$this->db->insert('cmall_coupon_log');
		}
		
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}
	
}
