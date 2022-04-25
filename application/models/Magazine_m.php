<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Magazine_m extends CI_Model 
{
	public function __construct()
	{
        parent::__construct();		
		$this->load->database();
	}

	public function magazine_list($req, $offset, $perpage) {
		$sql = "SELECT
					t1.cmg_id
					, t1.cmg_title
					, CASE WHEN LENGTH(t1.cmg_content) > 80 THEN concat(left(t1.cmg_content, 80), '...')
						ELSE t1.cmg_content END  as cmg_content
					, t1.cmg_summary
					, t1.main_img
					, t1.is_show
					, t1.view_cnt
					, date_format(t1.ins_dtm, '%Y-%m-%d %H:%i') as ins_dtm
					, date_format(t1.upd_dtm, '%Y-%m-%d %H:%i') as upd_dtm
				FROM
					cmall_board_magazine t1
				WHERE
					t1.is_delete = 'n' 
					AND t1.is_show = 'y' ";
		if(!empty($req['searchText'])) {
			$sql .= " AND (t1.cmg_title LIKE '%" . $req['searchText'] . "%' OR t1.cmg_content LIKE '%" . $req['searchText'] . "%' ) ";
		}
		$sql .= " ORDER BY t1.ins_dtm DESC
					LIMIT ?, ? ";

		return $this->db->query($sql, array($offset, $perpage));
	}

	public function magazine_list_cnt($req) {
		$sql = "SELECT
					count(*) as cnt
				FROM
					cmall_board_magazine t1
				WHERE
					t1.is_delete = 'n'
					AND t1.is_show = 'y' ";
		if(!empty($req['searchText'])) {
			$sql .= " AND (t1.cmg_title LIKE '%" . $req['searchText'] . "%' OR t1.cmg_content LIKE '%" . $req['searchText'] . "%' ) ";
		}
				
		$tmp = $this->db->query($sql, array())->row_array();
		return $tmp['cnt'];
	}

	public function magazine_detail($seq) {
		$this->db->where('cmg_id', $seq);
		$this->db->set('view_cnt', 'view_cnt + 1', false);
		$this->db->update('cmall_board_magazine');
		
		$sql = "SELECT
					t1.cmg_id
					, t1.cmg_title
					, t1.cmg_content
					, t1.main_img
					, t1.is_show
					, t1.view_cnt
					, date_format(t1.ins_dtm, '%Y/%m/%d') as ins_dtm
					, date_format(t1.upd_dtm, '%Y/%m/%d') as upd_dtm
				FROM
					cmall_board_magazine t1
				WHERE
					t1.cmg_id = ? ";
		$info = $this->db->query($sql, array($seq))->row_array();

		if(!empty($info)) {
			$sql = "SELECT
						t1.cmg_id
						, t1.cmg_title
						, t1.cmg_content
						, t1.main_img
						, t1.is_show
						, t1.view_cnt
						, date_format(t1.ins_dtm, '%Y/%m/%d') as ins_dtm
						, date_format(t1.upd_dtm, '%Y/%m/%d') as upd_dtm
					FROM
						cmall_board_magazine t1
					WHERE
						? < t1.cmg_id 
						and t1.is_delete = 'n' 
						and t1.is_show = 'y'  
					ORDER BY t1.ins_dtm LIMIT 1 ";
			$info['next'] = $this->db->query($sql, array($seq))->row_array();
	
			$sql = "SELECT
						t1.cmg_id
						, t1.cmg_title
						, t1.cmg_content
						, t1.main_img
						, t1.is_show
						, t1.view_cnt
						, date_format(t1.ins_dtm, '%Y/%m/%d') as ins_dtm
						, date_format(t1.upd_dtm, '%Y/%m/%d') as upd_dtm
					FROM
						cmall_board_magazine t1
					WHERE
						? > t1.cmg_id 
						and t1.is_delete = 'n' 
						and t1.is_show = 'y'  
					ORDER BY t1.ins_dtm DESC LIMIT 1 ";
			$info['prev'] = $this->db->query($sql, array($seq))->row_array();
		}
		return $info;
	}
}