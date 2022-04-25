<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_m extends CI_Model 
{
	public function __construct()
	{
        parent::__construct();		
		$this->load->database();
	}

	public function sms_auth_reg($data)
	{
		$this->db->trans_begin();

		$sql = "update sms_auth
				set
					is_complete = 'y'
				where
					phone_number = ?
					and is_complete = 'n' ";
						
			$this->db->query($sql, array($data['mem_phone']));

		$sql = "INSERT INTO
					sms_auth
				(
					phone_number,
					auth_number,
                	exp_dtm,
					is_complete,
                	ins_date
				)
				values
				(
					? ,
					? ,
					DATE_ADD(NOW(), INTERVAL 3 MINUTE) ,
					'n' ,
					now()
				) ";

		$this->db->query($sql,array($data['mem_phone'], $data['auth_number']));

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}

	public function sms_auth_check($data)
	{
		$this->db->trans_begin();

		$sql = "select
					COUNT(*) AS cnt
				from
					sms_auth
				where
					phone_number = ? 
					and auth_number = ? 
					and is_complete = 'n'
					and exp_dtm >= now() ";

		$result = $this->db->query($sql,array($data['mem_phone'], $data['auth_number']))->row_array();

		if($result['cnt'] > 0) {
			$sql = "update sms_auth
					set
						is_complete = 'y'
					where
						phone_number = ?
						and auth_number = ? ";
						
			$this->db->query($sql, array($data['mem_phone'], $data['auth_number']));
						
			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				return false;
			}else{
				$this->db->trans_commit();
				return true;
			}
		}
		else {
			$this->db->trans_rollback();
			return false;
		}
	}
	
	public function chk_exists_phone($phone)
	{
		$sql = "select
					t1.mem_id
				from
					member t1
				where
					FN_DECRYPT(t1.mem_phone) = ? ";
		
		return $this->db->query($sql, array($phone));
	}
	
	public function bank_code() {
		$this->db->where('code_type', 'bank');
		$this->db->order_by('code', 'asc');
		return $this->db->get('cmall_inicis_code');	
	}
	
	public function insert_file($vals) {
		$this->db->insert_batch('cmall_file', $vals);	
	}

	public function delete_file($seq) {
		$this->db->where_in('file_seq', $seq, false);
		$this->db->set('is_delete', 'y');
		$this->db->update('cmall_file');
	}
	
	public function file_list($gbn, $seq) {
		$this->db->where('parent_gbn', $gbn);
		$this->db->where('parent_cd', $seq);
		$this->db->where('is_delete', 'n');
		return $this->db->get('cmall_file');
	}
	
	public function board_category_list($type) {
		$sql = "SELECT
					t1.*
				FROM
					cmall_board_category t1
				WHERE
					t1.cbc_type = ?
					and t1.is_delete = 'n'
					and t1.is_show = 'y'
				ORDER BY t1.order_no ";

		return $this->db->query($sql, array($type));
	}
	
	public function insert_bizmessage($req, $res) {
		$item = array();
		if($res->code == '0') {
			foreach($req['messages'] as $row) {
				$tmp = "('" . $row['custom_key'] . "', '" . $req['template_id'] . "', '" . $row['tel_num'] . "', '0', '')";
				$item[] = $tmp;
			}
		}
		else if($res->code == '-3') {
			foreach($req['messages'] as $row) {
				$tmp = "('" . $row['custom_key'] . "', '" . $req['template_id'] . "', '" . $row['tel_num'] . "', '-3', '')";
				$item[] = $tmp;
			}
		}
		else {
			foreach($req['messages'] as $row) {
				$res_code = '0';
				$res_msg = '';
				if(!empty($res->msg->messages)) {
					foreach($res->msg->messages as $row2) {
						if($row['no'] == $row2->no) {
							$res_code = $row2->result_code;
							$res_msg = $row2->result_msg;
							break;
						}
					}
				}
				$tmp = "('" . $row['custom_key'] . "', '" . $req['template_id'] . "', '" . $row['tel_num'] . "', '" . $res_code . "', '" . $res_msg . "')";
				$item[] = $tmp;
			}
		}
		
		if(count($item) > 0) {
			$sql = "insert into cmall_biz_message
					(
						id
						, template_id
						, tel
						, res_code
						, res_msg
					)
					values " . implode(',', $item);

			$this->db->query($sql, array());
		}
	}
	
	public function insert_bizmessage_result($req) {
		$this->db->where('id', $req->custom_key);
		$this->db->set('webhook_type', $req->type);
		$this->db->set('webhook_code', $req->result_code);
		$this->db->update('cmall_biz_message');
	}
	
	public function shop_info() {
		return $this->db->get('shop_info');	
	}

	public function shop_info2() {
		$sql = "select
					ceo_name
					, office_addr
					, delivery_addr
					, biz_no
					, shop_no
					, personal_name
					, tel
					, qna_email
					, partner_email
					, recruit_email
					, shop_copyright
					, shop_title
					, shop_desc
					, shop_keyword
					, sns_ga
					, sns_pixel
					, sns_kakao
					, sns_facebook
					, sns_instagram
					, sns_blog
					, sns_youtube
				from
					shop_info
				limit 0, 1";
		return $this->db->query($sql, array());
	}

	public function shop_main() {
		return $this->db->get('shop_main');
	}
	
	public function check_delivery_price($code) {
		$sql = "select
					delivery_price
				from
					shop_delivery_price
				where
					? between start_code and end_code ";
		return $this->db->query($sql, array($code));
	}
}