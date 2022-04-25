<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common extends CD_Controller{

	function __construct(){
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->model('common_m');
		$this->load->library('icode_l');
	}
	public function ajaxSendAuth() {
		$req = $this->input->post();

		$result = array();
		
		if(empty($req['mem_phone'])) {
			$result['status'] = 'fail';
			$result['code'] = '-1';
			$result['msg'] = '휴대폰 번호를 입력해 주세요.';	
		}
		else {
			$_res = $this->common_m->chk_exists_phone($req['mem_phone'])->result_array();
			
			if(count($_res) > 0) {
				$result['status'] = 'fail';
				$result['target'] = 'mem_phone';
				$result['msg'] = '이미 사용중인 휴대폰 번호입니다. 관리자에게 문의해 주세요.';
			}
			else { 
				
				if(preg_match("/^01[0-9]{8,9}$/", $req['mem_phone'])) {
	
					$rand_num = sprintf('%06d',rand(000000,999999));
					$data['mem_phone'] = $req['mem_phone'];
					$data['auth_number'] = $rand_num;
					
					list($microtime,$timestamp) = explode(' ',microtime());
					$time = $timestamp.substr($microtime, 2, 3);
		
					$id = '50065';
					$msg = '인증번호 : ' . $rand_num . ' 클린디 계정 인증번호입니다.';
	
					$this->common_m->sms_auth_reg($data);
	
					$messages = array();
		
					$message = array();
					$message['no'] = '0';
					$message['tel_num'] = $req['mem_phone'];
					$message['custom_key'] = $time . '000';
					$message['msg_content'] = $msg;
					$message['sms_content'] = $msg;
					$message['use_sms'] = '1';
					$messages[] = $message;
			
					$this->sendBizMessage($id, $messages);
						
					$result['status'] = "succ";
					$result['msg'] = "인증번호를 발송했습니다.";
				}
				else {
					$result['status'] = 'fail';
					$result['target'] = 'mem_phone';
					$result['msg'] = '정상적인 휴대폰 번호가 아닙니다.';	
				}
			}
		}
	

		
    	echo json_encode($result);
	    exit;		
	}

	public function ajaxSendAuth2() 
	{
		$req = $this->input->post();

		$result = array();

		if(empty($req['mem_phone'])) 
		{
			$result['status'] = 'fail';
			$result['code'] = '-1';
			$result['msg'] = '휴대폰 번호를 입력해 주세요.';	
		}
		else 
		{
			if(preg_match("/^01[0-9]{8,9}$/", $req['mem_phone'])) 
			{
				$rand_num = sprintf('%06d',rand(000000,999999));
				$data['mem_phone'] = $req['mem_phone'];
				$data['auth_number'] = $rand_num;

				list($microtime,$timestamp) = explode(' ',microtime());
				$time = $timestamp.substr($microtime, 2, 3);
		
				$id = '50064';
				$msg = '인증번호 : ' . $rand_num . ' 클린디 계정 인증번호입니다.';
	
				$this->common_m->sms_auth_reg($data);
	
				$messages = array();
		
				$message = array();
				$message['no'] = '0';
				$message['tel_num'] = $req['mem_phone'];
				$message['custom_key'] = $time . '000';
				$message['msg_content'] = $msg;
				$message['sms_content'] = $msg;
				$message['use_sms'] = '1';
				$messages[] = $message;
			
				$this->sendBizMessage($id, $messages);

				$result['status'] = "succ";
				$result['msg'] = "인증번호를 발송했습니다.";
			}
			else 
			{
				$result['status'] = 'fail';
				$result['target'] = 'mem_phone';
				$result['msg'] = '정상적인 휴대폰 번호가 아닙니다.';	
			}
		}

		echo json_encode($result);
		exit;		
	}

	public function ajaxCheckAuth()
	{
		$req = $this->input->post();
		
	
		$result = array();

		if(empty($req['mem_phone'])) {
			$result['status'] = 'fail';
			$result['target'] = 'mem_phone';
			$result['msg'] = '휴대폰 번호를 입력해 주세요.';
		}
		else if(empty($req['auth_number'])) {
			$result['status'] = 'fail';
			$result['target'] = 'auth_number';
			$result['msg'] = '인증 번호를 입력해 주세요.';
		}
		else {
			$_res = $this->common_m->sms_auth_check($req);
				
		    if(!$_res) {
				$result['status'] = 'fail';
				$result['target'] = 'auth_number';
				$result['msg'] = "문자 인증에 실패했습니다. 다시시도해 주십시요.";
			}
			else{
				$result['status'] = 'succ';
				$result['msg'] = '인증완료하였습니다.';
			}
		}
    	echo json_encode($result);
	    exit;

	}

	public function ajaxFileUpload()
	{
		$req = $this->input->post();

		if(!is_dir(DATA_PATH . '/' . $req['target'] . '/')){
			mkdir(DATA_PATH . '/' . $req['target'] . '/',0777);
		}
		if(!is_dir(DATA_PATH . '/' . $req['target'] . '/'.date('Y').'/')){
			mkdir(DATA_PATH . '/' . $req['target'] . '/'.date('Y').'/',0777);
		}
		if(!is_dir(DATA_PATH . '/' . $req['target'] . '/'.date('Y').'/'.date('m').'/')){
			mkdir(DATA_PATH . '/' . $req['target'] . '/'.date('Y').'/'.date('m').'/',0777);
		}
		if(!is_dir(DATA_PATH . '/' . $req['target'] . '/'.date('Y').'/'.date('m').'/'.date('d').'/')){
			mkdir(DATA_PATH . '/' . $req['target'] . '/'.date('Y').'/'.date('m').'/'.date('d').'/',0777);
		}
		$file_path=DATA_PATH . '/' . $req['target'] . '/'.date('Y').'/'.date('m').'/'.date('d').'/';
		$file_target_path = '/' . $req['target'] . '/'.date('Y').'/'.date('m').'/'.date('d').'/';

		$config['upload_path'] = $file_path;
		$config['allowed_types'] = 'gif|jpg|png|jpeg|gif|doc|docx|ppt|pptx|xlslxlsx|zip|pdf';
		$config['max_size']	= 0;
		$config['encrypt_name']  = TRUE;
		$config['remove_spaces']  = TRUE;

		$this->load->library('upload', $config);

		$result = array();
		$result['status'] = 'succ';
		$result['fileinfo'] = array();
		for($i = 0; $i < count($_FILES['files']['name']); $i++) {
			 $_FILES['tmp']['name']= $_FILES['files']['name'][$i];
			 $_FILES['tmp']['type']= $_FILES['files']['type'][$i];
			 $_FILES['tmp']['tmp_name']= $_FILES['files']['tmp_name'][$i];
			 $_FILES['tmp']['error']= $_FILES['files']['error'][$i];
			 $_FILES['tmp']['size']= $_FILES['files']['size'][$i];
			if($this->upload->do_upload('tmp')){
				$data = $this->upload->data();
	
				$_file['newname'] = $data['file_name'];
				$_file['orgname'] = $data['orig_name'];
				$_file['filepath'] = $file_target_path;
				$fileinfo = pathinfo($data['orig_name']);
				$ext = $fileinfo['extension'];
				$_file['ext'] = $ext;
				$_file['size'] = round($data['file_size'] * 1024);
				$result['fileinfo'][] = $_file;
			}else{
				$result['status'] = 'fail';
				$result['msg'] = $this->upload->display_errors();
			}
		}
		echo json_encode($result);
		exit;
	}

	public function ajaxImgUpload()
	{
		$req = $this->input->post();

		if(!is_dir(DATA_PATH . '/' . $req['target'] . '/')){
			mkdir(DATA_PATH . '/' . $req['target'] . '/',0777);
		}
		if(!is_dir(DATA_PATH . '/' . $req['target'] . '/'.date('Y').'/')){
			mkdir(DATA_PATH . '/' . $req['target'] . '/'.date('Y').'/',0777);
		}
		if(!is_dir(DATA_PATH . '/' . $req['target'] . '/'.date('Y').'/'.date('m').'/')){
			mkdir(DATA_PATH . '/' . $req['target'] . '/'.date('Y').'/'.date('m').'/',0777);
		}
		if(!is_dir(DATA_PATH . '/' . $req['target'] . '/'.date('Y').'/'.date('m').'/'.date('d').'/')){
			mkdir(DATA_PATH . '/' . $req['target'] . '/'.date('Y').'/'.date('m').'/'.date('d').'/',0777);
		}
		$file_path=DATA_PATH . '/' . $req['target'] . '/'.date('Y').'/'.date('m').'/'.date('d').'/';
		$file_target_path = '/' . $req['target'] . '/'.date('Y').'/'.date('m').'/'.date('d').'/';

		$config['upload_path'] = $file_path;
		$config['allowed_types'] = 'gif|jpg|png|jpeg|gif';
		$config['max_size']	= 0;
		$config['encrypt_name']  = TRUE;
		$config['remove_spaces']  = TRUE;

		$this->load->library('upload', $config);

		$result = array();
		$result['status'] = 'succ';
		$result['fileinfo'] = array();
		for($i = 0; $i < count($_FILES['files']['name']); $i++) {
			 $_FILES['tmp']['name']= $_FILES['files']['name'][$i];
			 $_FILES['tmp']['type']= $_FILES['files']['type'][$i];
			 $_FILES['tmp']['tmp_name']= $_FILES['files']['tmp_name'][$i];
			 $_FILES['tmp']['error']= $_FILES['files']['error'][$i];
			 $_FILES['tmp']['size']= $_FILES['files']['size'][$i];
			if($this->upload->do_upload('tmp')){
				$data = $this->upload->data();

				$_file['newname'] = $data['file_name'];
				$_file['orgname'] = $data['orig_name'];
				$_file['filepath'] = $file_target_path;
				$fileinfo = pathinfo($data['orig_name']);
				$ext = $fileinfo['extension'];
				$_file['ext'] = $ext;
				$_file['size'] = round($data['file_size'] * 1024);
				$result['fileinfo'][] = $_file;
			}else{
				$result['status'] = 'fail';
//				echo $this->upload->display_errors();
				$result['msg'] = $this->upload->display_errors();
			}
		}
		echo json_encode($result);
		exit;
	}
	
	public function img_view()
	{
		$req = $this->input->get();
		$file = DATA_PATH . $req['img_path'] . $req['img_file'];
		if( file_exists($file) ){

		    $fsize = filesize($file);   // 다운로드로 사용할 경우를 대비한 파일 크기
		    $path_parts = pathinfo($file);  // 경로 정보
    		$ext = strtolower($path_parts["extension"]);  // 확장자 정보
			switch ($ext) { 
      			case "pdf": $ctype="application/pdf"; $cdispostion = true; break; 
      			case "exe": $ctype="application/octet-stream"; $cdispostion =true; break; 
				case "zip": $ctype="application/zip"; $cdispostion = true; break; 
      			case "doc": $ctype="application/msword"; $cdispostion = true; break; 
      			case "xls": $ctype="application/vnd.ms-excel"; $cdispostion = true; break; 
      			case "ppt": $ctype="application/vnd.ms-powerpoint"; $cdispostion =true; break; 
      			case "gif": $ctype="image/gif"; $cdispostion = false; break; 
      			case "png": $ctype="image/png"; $cdispostion = false; break; 
      			case "svg": $ctype="image/svg+xml"; $cdispostion = false; break; 
      			case "jpeg": 
      			case "jpg": $ctype="image/jpg"; $cdispostion = false; break; 
      			default: $ctype="application/force-download";  $cdispostion = true; 
    		} 
     
    		header("Pragma: public"); // required 
    		header("Expires: 0"); 
    		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
    		header("Cache-Control: private",false); // required for certain browsers 
    		header("Content-Type: $ctype"); 
    		if($cdispostion == true) {  // 다운로드로 전환할 경우에 사용함
        		header("Content-Disposition: attachment; filename=\"".$req['org_file']."\";" ); 
    		}
    		header("Content-Transfer-Encoding: binary"); 
    		header("Content-Length: ".$fsize); 
    		ob_clean(); 
    		flush(); 
	    	readfile( $file); 
//			echo 'data:' . $ctype . ';base64,' . base64_encode(fread(fopen($file, 'r'), $fsize));
		} 
		else {
			echo 'error';	
		}
	}
	
	public function ajaxTerms()
	{
		$req = $this->input->post();
		
		$info = $this->common_m->shop_info()->row_array();
		
		$result = array();
		$result['status'] = 'succ';
		$result['data'] = $info[$req['type']];
		
		echo json_encode($result);
	}
	
	public function ajaxDeliveryPrice()
	{
		$req = $this->input->post();
		
		$shop_info = $this->common_m->shop_info()->row_array();
		$delivery_price = $shop_info['delivery_price'];
		$tmp = $this->common_m->check_delivery_price($req['code'])->row_array();
		if(!empty($tmp)) {
			$delivery_price = $tmp['delivery_price'];	
		}	

		$result = array();
		$result['status'] = 'succ';
		$result['data'] = $delivery_price;
		
		echo json_encode($result);
	}
}