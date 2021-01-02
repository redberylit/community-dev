<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Comments_app extends CI_Model {

	private function get_unique_id(){
		$randomid = rand(1,999999);
		$res = $this->db->select('commentID')->where('commentID',$randomid)->limit(1)->get('srp_erp_ngo_com_uploads_comments')->row();
		if($res){
			$this->get_unique_id();
		} else {
			return $randomid;
		}
	}

	public function get_comments(){
		$id = $this->input->get('id');
		$user_id = $this->input->get('user_id');


		$this->load->model('mobile_api/Common_app','common');
		$basicdetails = $this->common->get_user_all_details(0,$user_id);

		$row = $this->db->select('ComUploadID AS id,FamMasterID AS family_id,ComUploadSubject AS title,ComUpload_url AS upload_url,ComUploadDescription AS description,UploadPublishedDate AS publish_date,isPublic')->where('ComUploadID',$id)->where('ComUploadSubmited',1)->limit(1)->get('srp_erp_ngo_com_uploads')->row();


		$data = array();
		$this->load->library('s3');

		if($row){
			if (strpos($row->upload_url, 'attachments/Community/uploads/') !== false) {
			    if( $this->s3->getMyObjectInfo($row->upload_url) ){
					$row->upload_url = $this->s3->getMyAuthenticatedURL($row->upload_url, 3600);
				}
			}

			$this->load->library('s3');
			$row->upload_user = $basicdetails->CFullName;
			$row->upload_user_image = 'http://cloud.educal-int.com/assets/img/user.png';
			if(strpos($basicdetails->CImage, 'Community/HMS/MemberImages/') !== false){
				if( $this->s3->getMyObjectInfo($basicdetails->CImage) ){
					$row->upload_user_image = $this->s3->getMyAuthenticatedURL($basicdetails->CImage, 3600);
				}
			}
		}

		$data['post'] = $row;
		$comments = $this->db->select('commentID AS comment_id,srp_erp_ngo_com_uploads_comments.Com_MasterID AS user_id,CFullName AS user_name,comments,CImage AS user_image,DATE(CreatedDate) AS comment_date')->join('srp_erp_ngo_com_communitymaster','srp_erp_ngo_com_communitymaster.Com_MasterID = srp_erp_ngo_com_uploads_comments.Com_MasterID','INNER')->where('ComUploadID',$id)->get('srp_erp_ngo_com_uploads_comments')->result();
		
		foreach ($comments as $comment) {
			
			$familydet = $this->db->select('IFNULL(FamMasterID,"0") AS family_id')->where('LeaderID',$comment->user_id)->limit(1)->get('srp_erp_ngo_com_familymaster')->row();

			if($familydet){
				$comment->family_id = $familydet->family_id;
			} else {
				$comment->family_id = "0";
			}

			if(strpos($comment->user_image, 'Community/HMS/MemberImages/') !== false){
				if( $this->s3->getMyObjectInfo($comment->user_image) ){
					$comment->user_image = $this->s3->getMyAuthenticatedURL($comment->user_image, 3600);
				}
			} else {
				$comment->user_image  = 'http://cloud.educal-int.com/assets/img/user.png';
			}
		} 
		$data['comments'] = $comments;
		echo json_encode($data);
	}

	public function post_comment(){
		$id = $this->input->get('id');
		$user_id = $this->input->get('user_id');
		$comment = $this->input->post('comment');

		$this->db->trans_start();
			$data = array(
				'commentID' => $this->get_unique_id(),
				'Com_MasterID' => $user_id,
				'ComUploadID' => $id,
				'comments' => $comment,
				'CreatedUserName' => 'mobile app',
				'CreatedDate' => date("Y-m-d h:i:s"),
				'CreatedPC' => $_SERVER['REMOTE_ADDR']
			);
		$this->db->insert('srp_erp_ngo_com_uploads_comments',$data);
		$this->db->trans_complete();
		echo json_encode($this->db->trans_status());
	}

	public function delete_comment(){
		$id = $this->input->get('id');

		$this->db->trans_start();
		$this->db->where('commentID',$id)->limit(1)->delete('srp_erp_ngo_com_uploads_comments');
		$this->db->trans_complete();
		echo json_encode($this->db->trans_status());
	}
}