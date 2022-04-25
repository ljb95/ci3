<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Diagnosis_m extends CI_Model 
{
	public function __construct()
	{
        parent::__construct();		
		$this->load->database();
	}

	public function diagnosis_list($req, $offset, $perpage) {
		$sql = "SELECT
					t1.*
					, concat(date_format(t1.ins_dtm, '%Y년 %m월 %d일자 '), t1.user_name, '님 진단') as cdg_title
				FROM
					cmall_diagnosis t1
				WHERE
					t1.mem_id = ? 
				ORDER BY t1.ins_dtm DESC
				LIMIT ?, ? ";
		return $this->db->query($sql, array($req['mem_id'], $offset, $perpage));
	}

	public function diagnosis_list_cnt($req) {
		$sql = "SELECT
					count(*) as cnt
				FROM
					cmall_diagnosis t1
				WHERE
					t1.mem_id = ? ";
		$tmp = $this->db->query($sql, array($req['mem_id']))->row_array();
		return $tmp['cnt'];
	}
	
	public function diagnosis_list_item($items) {
		$sql = "SELECT
					t2.*
				FROM
					cmall_diagnosis_item t1
				INNER JOIN cmall_item t2 on t2.cit_id = t1.cit_id
				WHERE
					t1.diagnosis_name in ('" . implode("','", $items) . "') ";
		return $this->db->query($sql, array());
	}

	public function diagnosis_info($seq) {
		$sql = "SELECT
					t1.*
				FROM
					cmall_diagnosis t1
				WHERE
					t1.cdg_id = ?  ";
		return $this->db->query($sql, array($seq));
	}

	public function diagnosis_info2($seq, $seq2) {
		$sql = "SELECT
					t1.*
				FROM
					cmall_diagnosis t1
				WHERE
					t1.cdg_id = ?  
					and t1.mem_id = ? ";
		return $this->db->query($sql, array($seq, $seq2));
	}

	public function diagnosis_item() {
		$sql = "SELECT
					t1.cit_id
					, t1.cit_name
					, t1.cit_price
					, t1.cit_sale_price
					, t1.cit_subscribe_price
					, t2.diagnosis_name
				FROM
					cmall_item t1
				INNER JOIN cmall_diagnosis_item t2 on t2.cit_id = t1.cit_id
				WHERE
					t1.is_delete = 'n'
					and t1.is_subscribe = 'y'  ";
		return $this->db->query($sql, array());
	}

	public function insert_diagnosis($req) {
		$this->db->trans_begin();

		$this->db->set('mem_id', $req['mem_id']);
		$this->db->set('user_name', $req['user_name']);
		$this->db->set('user_age', $req['user_age']);
		$this->db->set('user_sex', $req['user_sex']);
		$this->db->set('user_height', $req['user_height']);
		$this->db->set('user_weight', $req['user_weight']);
		$this->db->set('structure', $req['structure']);
		$this->db->set('brush_cnt', $req['brush_cnt']);
		$this->db->set('brush_time', $req['brush_time']);
		for($i = 0; $i < count($req['life']); $i++) {
			if($req['life'][$i] == 8) continue;
			$this->db->set('is_life' . $req['life'][$i] , 'y');
		}
		for($i = 0; $i < count($req['health']); $i++) {
			if($req['health'][$i] == 10) continue;
			$this->db->set('is_health' . $req['health'][$i] , 'y');
		}
		for($i = 0; $i < count($req['blood']); $i++) {
			if($req['blood'][$i] == 4) continue;
			$this->db->set('is_blood' . $req['blood'][$i] , 'y');
		}
		$this->db->set('is_scaling', $req['scaling'] == '1' ? 'n' : 'y');
		for($i = 0; $i < count($req['tooth']); $i++) {
			if($req['tooth'][$i] == 7) continue;
			$this->db->set('is_tooth' . $req['tooth'][$i], 'y');	
		}
		$this->db->set('is_concern', $req['concern']);
		$this->db->set('brush_line_score', $req['brush_line_score']);
		$this->db->set('brush_strong_score', $req['brush_strong_score']);
		$this->db->set('brush_shape1_score', $req['brush_shape1_score']);
		$this->db->set('brush_shape2_score', $req['brush_shape2_score']);
		$this->db->set('score_oral', $req['score_oral']);
		$this->db->set('score_gum', $req['score_gum']);
		$this->db->set('score_tooth', $req['score_tooth']);
		$this->db->set('score_total', $req['score_total']);
		$this->db->set('brush_line_name', $req['brush_line_name']);
		$this->db->set('brush_strong_name', $req['brush_strong_name']);
		$this->db->set('brush_shape_name', $req['brush_shape_name']);
		$this->db->set('ins_dtm', 'now()', false);

		$this->db->insert('cmall_diagnosis');
		$cdg_id = $this->db->insert_id();

		if($req['mem_id'] > 0) {
			$this->db->reset_query();
			$this->db->where('mem_id', $req['mem_id']);
			$res = $this->db->get('cmall_diagnosis');
			if($res->num_rows() == 1) {
				$points = $this->db->get('cmall_point_manage')->row_array();

				$point = $points['diagnosis'];
				
				$this->db->reset_query();
				$this->db->set('mem_id', $req['mem_id']);
				$this->db->set('add_type', 'diagnosis');
				$this->db->set('add_val', $point);
				$this->db->set('rest_val', '0');
				$this->db->set('use_val', '0');
				$this->db->set('ins_dtm', date('Y-m-d H:i:s'));
				$this->db->set('exp_dtm', date('Y-m-d', strtotime('+1 year')));
				$this->db->insert('member_point');
				$mpo_id = $this->db->insert_id();
				
				$this->db->reset_query();
				$this->db->set('mem_id', $req['mem_id']);
				$this->db->set('point_type', '처음진단실행');
				$this->db->set('point_val', $point);
				$this->db->set('point_dir', 'plus');
				$this->db->set('ins_dtm', date('Y-m-d H:i:s'));
				$this->db->set('mpo_id', $mpo_id);
				$this->db->insert('member_point_log');
				
				$this->db->reset_query();
				$this->db->where('mem_id', $req['mem_id']);
				$this->db->set('mem_point', 'mem_point + ' . $point, false);
				$this->db->update('member');
				
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
								and event_type = '3'
								and is_delete = 'n'
						) TB1
						WHERE
							TB1.ccl_id = '' ";
				$res = $this->db->query($sql, array($req['mem_id']))->result_array();
				
				foreach($res as $row) {
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
		}
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return -1;
		}else{
			$this->db->trans_commit();
			return $cdg_id;
		}
	}
	
	public function update_diagnosis($req) {
		$this->db->trans_begin();

		$this->db->set('mem_id', $req['mem_id']);
		$this->db->where('cdg_id', $req['cdg_id']);

		$this->db->update('cmall_diagnosis');

		if($req['mem_id'] > 0) {
			$this->db->reset_query();
			$this->db->where('mem_id', $req['mem_id']);
			$res = $this->db->get('cmall_diagnosis');
			if($res->num_rows() == 1) {
				$points = $this->db->get('cmall_point_manage')->row_array();

				$point = $points['diagnosis'];
				
				$this->db->reset_query();
				$this->db->set('mem_id', $req['mem_id']);
				$this->db->set('add_type', 'diagnosis');
				$this->db->set('add_val', $point);
				$this->db->set('rest_val', '0');
				$this->db->set('use_val', '0');
				$this->db->set('ins_dtm', date('Y-m-d H:i:s'));
				$this->db->set('exp_dtm', date('Y-m-d', strtotime('+1 year')));
				$this->db->insert('member_point');
				$mpo_id = $this->db->insert_id();
				
				$this->db->reset_query();
				$this->db->set('mem_id', $req['mem_id']);
				$this->db->set('point_type', '처음진단실행');
				$this->db->set('point_val', $point);
				$this->db->set('point_dir', 'plus');
				$this->db->set('ins_dtm', date('Y-m-d H:i:s'));
				$this->db->set('mpo_id', $mpo_id);
				$this->db->insert('member_point_log');
				
				$this->db->reset_query();
				$this->db->where('mem_id', $req['mem_id']);
				$this->db->set('mem_point', 'mem_point + ' . $point, false);
				$this->db->update('member');
				
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
								and event_type = '3'
								and is_delete = 'n'
						) TB1
						WHERE
							TB1.ccl_id = '' ";
				$res = $this->db->query($sql, array($req['mem_id']))->result_array();
				
				foreach($res as $row) {
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