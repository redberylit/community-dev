<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Profilesettings_app extends CI_Model {

	private function remove_image_from_database($family_id,$user_id){
		$this->load->model('mobile_api/Common_app','common');
	    $basicdetails = $this->common->get_user_all_details($family_id,$user_id);

	    $user_image = 'http://cloud.educal-int.com/assets/img/user.png';

		$this->load->library('s3');
		if(strpos($basicdetails->CImage, 'Community/HMS/MemberImages/') !== false){
			$this->s3->deleteMyObject($basicdetails->CImage);
		}

		$this->db->trans_start();
		$this->db->set('CImage',null)->where('Com_MasterID',$user_id)->limit(1)->update('srp_erp_ngo_com_communitymaster');
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			$user_image = null;
		}
		return $user_image;
	}


	public function update_profile_picture(){
		$family_id = $this->input->get('family_id');
		$user_id = $this->input->get('user_id');

		$filename = null;
		$this->db->trans_start();
		if(isset($_FILES['image'])){

			$this->load->library('s3');
			$filename = "Community/HMS/MemberImages/".time().rand(10,100).$_FILES['image']['name'];
			$input = $this->s3->inputFile($_FILES['image']['tmp_name']);
			$uploadedname = $this->s3->putMyObject($input, $filename);
		

			$this->remove_image_from_database($family_id,$user_id);

			$this->db->set('CImage',$filename)->where('Com_MasterID',$user_id)->limit(1)->update('srp_erp_ngo_com_communitymaster');
			if( $this->s3->getMyObjectInfo($filename) ){
				$filename = $this->s3->getMyAuthenticatedURL($filename, 3600);
			}
		}

		$this->db->trans_complete();
		if($this->db->trans_status() === TRUE){
			echo json_encode($filename);
		}
		
	}

	public function remove_image(){
		$family_id = $this->input->get('family_id');
		$user_id = $this->input->get('user_id');
		echo json_encode( $this->remove_image_from_database($family_id,$user_id) );
	}


	public function change_password(){
		$family_id = $this->input->get('family_id');
		$user_id = $this->input->get('user_id');
		$oldpass = $this->input->post('oldpass');
		$newpass = $this->input->post('newpass');
		$conpass = $this->input->post('conpass');

		$fam = $this->db->select('FamPassword')->where('FamMasterID',$family_id)->limit(1)->get('srp_erp_ngo_com_familymaster')->row();
		$data = array();
		if($fam->FamPassword == md5($oldpass)){
			if($newpass == $conpass){

				$this->db->trans_start();
				$this->db->set('FamPassword', md5($newpass) )->where('FamMasterID',$family_id)->limit(1)->update('srp_erp_ngo_com_familymaster');
				$this->db->trans_complete();

				$data['s'] = $this->db->trans_status();
				$data['msg'] = 'Your password has been changed successfully';
			} else {
				$data['s'] = false;
				$data['msg'] = 'New password and confirmation password is not match';
			}
		} else {
			$data['s'] = false;
			$data['msg'] = 'Current password you entered is wrong';
		}
		echo json_encode($data);
	}

	public function get_post_history(){
		$family_id = $this->input->get('family_id');
		$user_id = $this->input->get('user_id');
		$status = (int)$this->input->get('status');

		$this->db->select('ComUploadSubmited AS issubmit,ComUploadID AS id,FamMasterID AS family_id,ComUploadSubject AS title,ComUpload_url AS upload_url,ComUploadDescription AS description,UploadPublishedDate AS publish_date,isPublic');

		if($status == 1 || $status == 0){
			$this->db->where('ComUploadSubmited',$status);
		}

		$res = $this->db->where('FamMasterID',$family_id)->order_by('ComUploadID','DESC')->get('srp_erp_ngo_com_uploads')->result();

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