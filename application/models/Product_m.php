<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_m extends CI_Model 
{
	public function __construct()
	{
        parent::__construct();		
		$this->load->database();
	}

	public function category_list() {
		$sql = "SELECT 
					t1.cca_id
					, t1.cca_value 
					, (SELECT count(*) FROM cmall_item a WHERE a.cca_id = t1.cca_id and a.is_list = 'y' and a.is_delete = 'n' ) as cca_cnt
				FROM 
					cmall_category t1
				WHERE
					t1.is_delete = 'n'
				ORDER BY t1.cca_order";
		return $this->db->query($sql, array());
	}
	
	public function product_list($seq) {
		$sql = "SELECT
					t1.cit_id
					, t1.cit_name
					, t1.cit_summary
					, t1.cit_price
					, t1.cit_sale_price
					, t1.is_sale
					, t1.cit_file_1
					, t1.is_order
				FROM
					cmall_item t1
				WHERE
					t1.is_delete = 'n'
					and t1.is_list = 'y' ";
		if(!empty($seq)) {
			$sql .= " and t1.cca_id = '" . $seq . "' ";	
		}
		$sql .= " order by t1.cca_id, t1.cit_order ";
		return $this->db->query($sql, array());
	}
	
	public function product_item($seq) {
		$sql = "SELECT
					t1.cit_id
					, t1.cca_id
					, t1.cit_name
					, t1.cit_summary
					, t1.cit_price
					, t1.cit_sale_price
					, t1.is_sale
					, t1.cit_file_1
					, t1.cit_file_2
					, t1.cit_file_3
					, t1.cit_file_4
					, t1.cit_file_5
					, t1.cit_file_6
					, t1.cit_file_7
					, t1.cit_file_8
					, t1.cit_file_9
					, t1.cit_file_10
					, t1.cit_content
					, t1.is_subscribe
					, t1.cit_subscribe_price
					, t1.is_order
				FROM
					cmall_item t1
				WHERE
					t1.is_delete = 'n'
					and t1.is_list = 'y' 
					and t1.cit_id = ? ";	
		return $this->db->query($sql, array($seq));
	}
	
	public function product_option($seq) {
		$sql = "SELECT
					t1.cio_id
					, t1.cit_id
					, t1.option_name
					, t1.option_val
				FROM
					cmall_item_option t1
				WHERE
					t1.is_delete = 'n'
					and t1.cit_id = ? ";	
		return $this->db->query($sql, array($seq));
	}
	
	public function product_item_detail($seq, $option) {
		$sql = "SELECT
					t1.cde_id
					, t1.cde_filename
					, t1.product_code
					, t1.barcode_no
					, t1.cde_title
				FROM
					cmall_item_detail t1
				WHERE
					t1.cit_id = ? ";
		if(!empty($option)) {
			foreach($option as $row) {
				if(empty(trim($row))) continue;
				
				$sql .= " and FIND_IN_SET('" . $row . "', t1.cde_title) ";
			}
		}
		return $this->db->query($sql, array($seq));
	}
	
}