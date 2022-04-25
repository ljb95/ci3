<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice_m extends CI_Model 
{
	public function __construct()
	{
        parent::__construct();		
		$this->load->database();
	}

	public function notice_list($offset, $perpage) {
		$sql = "SELECT
					t1.cbn_id
					, t1.cbn_title
					, t1.cbn_content
					, t1.is_show
					, t1.view_cnt
					, t1.is_top
					, date_format(t1.ins_dtm, '%Y/%m/%d') as ins_dtm
				FROM
					cmall_board_notice t1
				WHERE
					t1.is_delete = 'n'
					AND t1.is_show = 'y'
				ORDER BY t1.is_top desc, t1.ins_dtm desc
				LIMIT ?, ? ";

		return $this->db->query($sql, array($offset, $perpage));
	}

	public function notice_list_cnt() {
		$sql = "SELECT
					count(*) as cnt
				FROM
					cmall_board_notice t1
				WHERE
					t1.is_delete = 'n'
					AND t1.is_show = 'y' ";
				
		$tmp = $this->db->query($sql, array())->row_array();
		return $tmp['cnt'];
	}

	public function notice_detail($seq, $offset, $perpage) {
		$this->db->where('cbn_id', $seq);
		$this->db->set('view_cnt', 'view_cnt + 1', false);
		$this->db->update('cmall_board_notice');

		$sql = "SELECT
					t1.cbn_id
					, t1.cbn_title
					, t1.cbn_content
					, t1.is_show
					, t1.view_cnt
					, t1.is_top
					, date_format(t1.ins_dtm, '%Y/%m/%d') as ins_dtm
				FROM
					cmall_board_notice t1
				WHERE
					t1.is_delete = 'n'
					AND t1.is_show = 'y'
				ORDER BY t1.is_top desc, t1.ins_dtm desc
				LIMIT ?, ? ";

		$list = $this->db->query($sql, array($offset, $perpage))->result_array();

		$info = array();
		$idx = 1;
		foreach($list as $row) {
			if($row['cbn_id'] == $seq) {
				$info = $row;
				break;	
			}
			$idx++;
		}
		
		if(!empty($info)) {
			$sql = "SELECT
						TB1.*
					FROM
					(
						SELECT
							@rownum:=@rownum+1 as rnum
							, t1.cbn_id
							, t1.cbn_title
							, t1.cbn_content
							, t1.is_show
							, t1.view_cnt
							, t1.is_top
							, date_format(t1.ins_dtm, '%Y/%m/%d') as ins_dtm
						FROM
							cmall_board_notice t1
						WHERE
							(@rownum:=0)=0
							and t1.is_delete = 'n' 
							and t1.is_show = 'y'
						ORDER BY t1.is_top desc, t1.ins_dtm desc
					) TB1
					WHERE
						? < TB1.rnum
					ORDER BY TB1.rnum
					 LIMIT 1 ";
			$info['next'] = $this->db->query($sql, array($idx))->row_array();
	
			$sql = "SELECT
						TB1.*
					FROM
					(
						SELECT
							@rownum:=@rownum+1 as rnum
							, t1.cbn_id
							, t1.cbn_title
							, t1.cbn_content
							, t1.is_show
							, t1.view_cnt
							, t1.is_top
							, date_format(t1.ins_dtm, '%Y/%m/%d') as ins_dtm
						FROM
							cmall_board_notice t1
						WHERE
							(@rownum:=0)=0
							and t1.is_delete = 'n' 
							and t1.is_show = 'y'
						ORDER BY t1.is_top desc, t1.ins_dtm desc
					) TB1
					WHERE
						? > TB1.rnum
					ORDER BY TB1.rnum DESC LIMIT 1 ";
			$info['prev'] = $this->db->query($sql, array($idx))->row_array();
		}
		return $info;
	}
	
}