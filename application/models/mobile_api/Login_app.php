<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login_app extends CI_Model {

	public function login(){
		$user = $this->input->post('user');
		$pass = $this->input->post('pass');
		$os = $this->input->post('os');
		$res = array();

		$auth = $this->db->select('FamMasterID,LeaderID')->where('FamUsername',trim($user))->where('FamPassword',trim(md5($pass)))->limit(1)->get('srp_erp_ngo_com_familymaster')->row();

		if($auth){
			$res['s'] = true;
			$res['user_id'] = $auth->LeaderID;
			$res['family_id'] = $auth->FamMasterID;
		} else {
			$res['s'] = false;
		}

		$this->db->close();
		echo json_encode($res);
	}

	public function check_user_status(){
		$family_id = $this->input->get('family_id');
		$user_id = $this->input->get('user_id');

		$res = null;

		$row = $this->db->select('isVerifyDocApproved,VerifyDocAttachmentBack,VerifyDocAttachmentFront,smsVerificationCode,isSmsVerified')->where('LeaderID',$user_id)->where('FamMasterID',$family_id)->limit(1)->get('srp_erp_ngo_com_familymaster')->row();


		if($row){
			if($row->isSmsVerified == 0){
				$res = -2;
			} else {
				if($row->VerifyDocAttachmentFront == null && $row->VerifyDocAttachmentBack == null){
				    $res = -1;
				} else {
					if($row->isVerifyDocApproved == 1){
					    $res = 1;
					} else if($row->isVerifyDocApproved == 0){
						$res = 0;
					}
				}
			}
		}
		$this->db->close();
		echo json_encode($res);
	}

}