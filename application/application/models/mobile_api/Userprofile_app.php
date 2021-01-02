<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Userprofile_app extends CI_Model {

	public function get_user_details(){
		$family_id = $this->input->get('family_id');
		$user_id = $this->input->get('user_id');
		
		$this->load->model('mobile_api/Common_app','common');
		$basicdetails = $this->common->get_user_all_details($family_id,$user_id);


		$user_image = 'http://cloud.educal-int.com/assets/img/user.png';
		$this->load->library('s3');
		if(strpos($basicdetails->CImage, 'Community/HMS/MemberImages/') !== false){
			if( $this->s3->getMyObjectInfo($basicdetails->CImage) ){
				$user_image = $this->s3->getMyAuthenticatedURL($basicdetails->CImage, 3600);
			}
		}

		$postcount = "0";
		$pendingpostcount = "0";

		$res = $this->db->select(' IFNULL(COUNT(ComUploadID),"0") AS postcount')->where('ComUploadSubmited',1)->where('FamMasterID',$family_id)->limit(1)->get('srp_erp_ngo_com_uploads')->row();
		if($res){
			$postcount = $res->postcount;
		}

		$res = $this->db->select(' IFNULL(COUNT(ComUploadID),"0") AS postcount')->where('ComUploadSubmited',0)->where('FamMasterID',$family_id)->limit(1)->get('srp_erp_ngo_com_uploads')->row();
		if($res){
			$pendingpostcount = $res->postcount;
		}

		$data = array(
			'name' => $basicdetails->CFullName,
			'image' => $user_image,
			'phone' => $basicdetails->TP_Mobile,
			'email' => $basicdetails->EmailID,
			'postcount' => $postcount,
			'pendingpostcount' => $pendingpostcount
		);
		echo json_encode($data);
	}


	public function get_user_post_details(){
		$family_id = $this->input->get('family_id');
		$user_id = $this->input->get('user_id');

		$res = $this->db->select('ComUploadID AS id,FamMasterID AS family_id,ComUploadSubject AS title,ComUpload_url AS upload_url,ComUploadDescription AS description,UploadPublishedDate AS publish_date,isPublic')->where('FamMasterID',$family_id)->where('ComUploadSubmited',1)->order_by('UploadPublishedDate','DESC')->get('srp_erp_ngo_com_uploads')->result();

		if($res){
			$this->load->library('s3');
			foreach ($res as $key) {
				if (strpos($key->upload_url, 'attachments/Community/uploads/') !== false) {
				    if( $this->s3->getMyObjectInfo($key->upload_url) ){
						$key->upload_url = $this->s3->getMyAuthenticatedURL($key->upload_url, 3600);
					}
				} 
			}
		}
		echo json_encode($res);
	}

}