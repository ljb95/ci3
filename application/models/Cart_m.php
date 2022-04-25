<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart_m extends CI_Model 
{
	public function __construct()
	{
        parent::__construct();		
		$this->load->database();
	}

	public function insert_cart($val) {

		$this->db->trans_begin();
		
		$sql = "select
					cct_id
				FROM
					cmall_cart
				WHERE
					cit_id = ?
					and cde_id = ?
					and mem_id= ?
					and cart_type = ?
					and is_delete = 'n' 
					and is_order = 'n' ";
		$res = $this->db->query($sql, array($val['cit_id'], $val['cde_id'], $val['mem_id'], $val['cart_type']))->row_array();

		if(empty($res)) {
			$sql = "insert into cmall_cart
					(cart_type
					, mem_id
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
					, ins_dtm
					, upd_dtm
					, is_order
					, is_delete
					, is_subscribe)
					VALUES
					(?
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
					, now(), now(), 'n', 'n', ?);";
					$this->db->query($sql, array($val['cart_type']
												, $val['mem_id'], $val['cit_id'], $val['cde_id'], $val['cit_name'], $val['cit_price'], $val['cit_sale_price'], $val['cit_subscribe_price']
												, $val['qty'], $val['cde_title'], $val['product_code'], $val['barcode_no'], $val['is_subscribe']));

		}
		else {
			$sql = "update cmall_cart
					set
						qty = qty + " . $val['qty'] . "
					where
						cct_id = ? ";
			$this->db->query($sql, array($res['cct_id']));						
		}
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}
	
	public function cart_list($seq, $type)
	{
		$sql = "select
					t1.*
					, t2.cit_file_1
					, t2.is_sale
				from
					cmall_cart t1
				inner join cmall_item t2 on t2.cit_id = t1.cit_id and t2.is_delete = 'n'
				inner join cmall_item_detail t3 on t3.cde_id = t1.cde_id and t3.barcode_no = t1.barcode_no and t3.product_code = t1.product_code and t3.cde_title = t1.cde_title
				where
					t1.mem_id = ?
					and cart_type = ?
					and t1.is_delete = 'n'
					and t1.is_order = 'n' ";
		return $this->db->query($sql, array($seq, $type));
	}

	public function cart_cnt($seq)
	{
		$sql = "select
					count(*) as cnt
				from
					cmall_cart
				where
					mem_id = ?
					and is_delete = 'n'
					and is_order = 'n' ";
		$tmp = $this->db->query($sql, array($seq))->row_array();
		return $tmp['cnt'];	
	}
	
	public function delivery_address_default($seq)
	{
		$sql = "SELECT
					t1.mde_id
					, t1.mde_title
					, FN_DECRYPT(t1.recipient_name) as recipient_name
					, if(t1.recipient_phone is not null, FN_DECRYPT(recipient_phone), '') as recipient_phone
					, FN_DECRYPT(t1.zipcode) as zipcode
					, FN_DECRYPT(t1.road_addr) as road_addr
					, FN_DECRYPT(t1.jibun_addr) as jibun_addr
					, FN_DECRYPT(t1.detail_addr) as detail_addr
					, ifnull(t1.memo, '') as memo
				FROM
					member_delivery t1
				WHERE
					is_delete = 'n'
					and is_default = 'y'
					and mem_id = ?";
		return $this->db->query($sql, array($seq));
	}
	
	public function delivery_address_list($seq)
	{
		$sql = "SELECT
					t1.mde_id
					, FN_DECRYPT(t1.recipient_name) as recipient_name
					, if(t1.recipient_phone is not null, FN_DECRYPT(recipient_phone), '') as recipient_phone
					, FN_DECRYPT(t1.zipcode) as zipcode
					, FN_DECRYPT(t1.road_addr) as road_addr
					, FN_DECRYPT(t1.jibun_addr) as jibun_addr
					, FN_DECRYPT(t1.detail_addr) as detail_addr
					, ifnull(t1.memo, '') as memo
					, t1.is_default
				FROM
					member_delivery t1
				WHERE
					t1.is_delete = 'n'
					and t1.mem_id = ?";
		return $this->db->query($sql, array($seq));
	}

	public function delivery_address_cnt($seq)
	{
		$sql = "SELECT
					count(*) as cnt
				FROM
					member_delivery t1
				WHERE
					t1.is_delete = 'n'
					and t1.mem_id = ?";
		$tmp = $this->db->query($sql, array($seq))->row_array();
		return $tmp['cnt'];
	}

	public function insert_delivery_address($val)
	{
		$this->db->trans_begin();

		if($val['is_default'] == 'y') {
			$this->db->where('mem_id', $val['mem_id']);
			$this->db->set('is_default', 'n');
			$this->db->update('member_delivery');
		}
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
		$this->db->query($sql, array($val['mde_title']
									, $val['mem_id']
									, $val['recipient_name']
									, $val['recipient_phone']
									, $val['zipcode']
									, $val['road_addr']
									, $val['jibun_addr']
									, $val['detail_addr']
									, $val['is_default']
									, $val['memo']));

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}
	
	public function update_delivery_default($val)
	{
		$this->db->trans_begin();

		$this->db->where('mem_id', $val['mem_id']);
		$this->db->set('is_default', 'n');
		$this->db->update('member_delivery');
		
		$this->db->where('mde_id', $val['mde_id']);
		$this->db->set('is_default', 'y');
		$this->db->update('member_delivery');

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}
		
	public function update_delivery($val)
	{
		$this->db->trans_begin();

		$sql = "UPDATE member_delivery
				SET
					mde_title = ?
					, recipient_name = FN_ENCRYPT(?)
					, recipient_phone = FN_ENCRYPT(?)
					, zipcode = FN_ENCRYPT(?)
					, road_addr = FN_ENCRYPT(?)
					, jibun_addr = FN_ENCRYPT(?)
					, detail_addr = FN_ENCRYPT(?)
					, upd_dtm = now()
					, memo = ?
				WHERE
					mde_id = ? ";
		$this->db->query($sql, array($val['mde_title']
									, $val['recipient_name']
									, $val['recipient_phone']
									, $val['zipcode']
									, $val['road_addr']
									, $val['jibun_addr']
									, $val['detail_addr']
									, $val['memo']
									, $val['mde_id']));
									

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}

	public function update_delivery2($val)
	{
		$this->db->trans_begin();

		if($val['is_default'] == 'y') {
			$this->db->where('mem_id', $val['mem_id']);
			$this->db->set('is_default', 'n');
			$this->db->update('member_delivery');
		}

		$sql = "UPDATE member_delivery
				SET
					mde_title = ?
					, recipient_name = FN_ENCRYPT(?)
					, recipient_phone = FN_ENCRYPT(?)
					, zipcode = FN_ENCRYPT(?)
					, road_addr = FN_ENCRYPT(?)
					, jibun_addr = FN_ENCRYPT(?)
					, detail_addr = FN_ENCRYPT(?)
					, upd_dtm = now()
					, memo = ?
					, is_default = ?
				WHERE
					mde_id = ? ";
		$this->db->query($sql, array($val['mde_title']
									, $val['recipient_name']
									, $val['recipient_phone']
									, $val['zipcode']
									, $val['road_addr']
									, $val['jibun_addr']
									, $val['detail_addr']
									, $val['memo']
									, $val['is_default']
									, $val['mde_id']));
									

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}
	
	public function delete_addr($seq) {
		$this->db->where('mde_id', $seq);
		$this->db->set('is_delete', 'y');
		$this->db->set('upd_dtm', 'now()', false);
		$this->db->update('member_delivery');	
	}
	
	public function update_cart_qty($seq, $qty) {
		$this->db->trans_begin();

		$sql = "update cmall_cart
				set 
					qty = qty + (" . $qty . ")
				where
					cct_id = ?";
		$this->db->query($sql, array($seq));

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}

	public function delete_cart($seq) {
		$this->db->trans_begin();

		$sql = "update cmall_cart
				set 
					is_delete = 'y'
				where
					cct_id = ?";
		$this->db->query($sql, array($seq));

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}
	
	public function delivery_list($seq, $offset, $perpage)
	{
		$sql = "SELECT
					t1.mde_id
					, t1.mde_title
					, FN_DECRYPT(t1.recipient_name) as recipient_name
					, if(t1.recipient_phone is not null, FN_DECRYPT(recipient_phone), '') as recipient_phone
					, FN_DECRYPT(t1.zipcode) as zipcode
					, FN_DECRYPT(t1.road_addr) as road_addr
					, FN_DECRYPT(t1.jibun_addr) as jibun_addr
					, FN_DECRYPT(t1.detail_addr) as detail_addr
					, ifnull(t1.memo, '') as memo
					, t1.is_default
				FROM
					member_delivery t1
				WHERE
					t1.is_delete = 'n'
					and t1.mem_id = ?
				ORDER BY t1.is_default DESC, t1.ins_dtm
				LIMIT ?, ?";
		return $this->db->query($sql, array($seq, $offset, $perpage));
	}

	public function delivery_list_cnt($seq)
	{
		$sql = "SELECT
					count(*) as cnt
				FROM
					member_delivery t1
				WHERE
					t1.is_delete = 'n'
					and t1.mem_id = ?";
		$tmp = $this->db->query($sql, array($seq))->row_array();
		return $tmp['cnt'];
	}
	
	public function change_cart($seq)
	{
		$sql = "select
					*
				FROM
					cmall_cart
				WHERE
					mem_id= ?
					and is_delete = 'n' 
					and is_order = 'n' ";
		$res = $this->db->query($sql, array($seq))->result_array();
		
		foreach($res as $row) {
			if($row['is_subscribe'] == 'n' || $row['cart_type'] == 'subscribe') continue;
			$bExists = false;
			foreach($res as $row2) {
				if($row['cit_id'] == $row2['cit_id'] && $row['cde_title'] == $row2['cde_title'] && $row2['cart_type'] == 'subscribe') {
					$this->db->reset_query();
					$this->db->where('cct_id', $row2['cct_id']);
					$this->db->set('qty', $row2['qty'] + $row['qty']);
					$this->db->update('cmall_cart');

					$this->db->reset_query();
					$this->db->where('cct_id', $row['cct_id']);
					$this->db->set('is_delete', 'y');
					$this->db->update('cmall_cart');
					$bExists = true;
					break;
				}
			}
			if(!$bExists) {
				$this->db->reset_query();
				$this->db->where('cct_id', $row['cct_id']);
				$this->db->set('cart_type', 'subscribe');
				$this->db->update('cmall_cart');
			}
		}
		
	}
}