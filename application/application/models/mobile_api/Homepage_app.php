<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Homepage_app extends CI_Model {

	public function load_dahboard(){
		$family_id = $this->input->get('family_id');
		$user_id = $this->input->get('user_id');
		
		$this->load->model('mobile_api/Common_app','common');
		$basicdetails = $this->common->get_user_all_details($family_id,$user_id);

		$com = $this->db->select('stateID as id')->where('masterID',$basicdetails->RegionID)->where('divisionTypeCode','MH')->order_by('Description')->get('srp_erp_statemaster')->result();
		$wherein = '';
		if($com){
			foreach ($com as $key) {
			    $wherein .= $key->id.',';
			}
			$wherein = substr_replace($wherein ,"",-1);
		}

		$data = $this->db->query('SELECT id,title,upload_url,description,content_type,published_date,upload_user_id, upload_user,upload_user_image,isPublic,tb_user.FamMasterID FROM (SELECT FamMasterID,srp_erp_ngo_com_uploads.ComUploadID AS id,ComUploadSubject AS title,ComUpload_url AS upload_url,ComUploadDescription AS description,ComUploadType AS content_type,UploadPublishedDate AS published_date,isPublic FROM srp_erp_ngo_com_uploads WHERE isPublic = 1 AND ComUploadSubmited = 1 UNION ALL SELECT FamMasterID,srp_erp_ngo_com_uploads.ComUploadID AS id,ComUploadSubject AS subject,ComUpload_url AS upload_url,ComUploadDescription AS description,ComUploadType AS content_type,UploadPublishedDate AS published_date,isPublic FROM srp_erp_ngo_com_uploads INNER JOIN srp_erp_ngo_com_uploadsdivision ON srp_erp_ngo_com_uploadsdivision.ComUploadID = srp_erp_ngo_com_uploads.ComUploadID WHERE isPublic = 0 AND srp_erp_ngo_com_uploadsdivision.uploadGSDiviID IN ('.$wherein.') AND ComUploadSubmited = 1 GROUP BY srp_erp_ngo_com_uploadsdivision.ComUploadID) AS tb_uploads INNER JOIN (SELECT CFullName AS upload_user,CImage AS upload_user_image,srp_erp_ngo_com_familymaster.FamMasterID,srp_erp_ngo_com_communitymaster.Com_MasterID AS upload_user_id FROM srp_erp_ngo_com_familymaster INNER JOIN srp_erp_ngo_com_communitymaster ON srp_erp_ngo_com_familymaster.LeaderID = srp_erp_ngo_com_communitymaster.Com_MasterID) AS tb_user ON tb_user.FamMasterID = tb_uploads.FamMasterID GROUP BY id ORDER BY published_date DESC')->result();
		$res = array();

		if($data){
			$this->load->library('s3');

			foreach ($data as $key) {
				$upload_url = '';
				$user_image = '';

				if (strpos($key->upload_url, 'attachments/Community/uploads/') !== false) {
				    if( $this->s3->getMyObjectInfo($key->upload_url) ){
						$upload_url = $this->s3->getMyAuthenticatedURL($key->upload_url, 3600);
					}
				} else {
					$upload_url = $key->upload_url;
				}

				if(strpos($key->upload_user_image, 'Community/HMS/MemberImages/') !== false){
					if( $this->s3->getMyObjectInfo($key->upload_user_image) ){
						$user_image = $this->s3->getMyAuthenticatedURL($key->upload_user_image, 3600);
					}
				} else {
					$user_image = 'http://cloud.educal-int.com/assets/img/user.png';
				}

				array_push($res, [
					'id' => $key->id,
					'title' => $key->title,
					'upload_url' => $upload_url,
					'description' => $key->description,
					'content_type' => $key->content_type,
					'published_date' => $key->published_date,
					'upload_user_family_id' => $key->FamMasterID,
					'upload_user_id' => $key->upload_user_id,
					'upload_user' => $key->upload_user,
					'upload_user_image' => $user_image,
					'is_public' => $key->isPublic
				]);
			}
		}

		$this->db->close();
		echo json_encode($res);
	}

	public function upload_new_post(){
		$family_id = $this->input->get('family_id');
		$user_id = $this->input->get('user_id');	
		$subject = $this->input->post('subject');
		$desctiption = $this->input->post('desctiption');
		$sharelink = $this->input->post('sharelink');
		$ispublic = $this->input->post('ispublic');
		$areas = json_decode($this->input->post('areas'));

		$path = null;
		if(isset($_FILES['image'])){
			$this->load->library('s3');
			$filename = "attachments/Community/uploads/".time().rand(10,100).$_FILES['image']['name'];
			$input = $this->s3->inputFile($_FILES['image']['tmp_name']);
			$uploadedname = $this->s3->putMyObject($input, $filename);

			$path = $filename;

		} else if($sharelink != '') {
			$path = $sharelink;
		}

		$masterdata = array(
		     'FamMasterID' => $family_id,
		     'ComUploadSubject' => $subject,
		     'ComUpload_url' => $path,
		     'ComUploadDescription' => $desctiption,
		     'isPublic' => $ispublic,
		     'CreatedPC' => $_SERVER['REMOTE_ADDR'],
		     'CreatedDate' => date("Y-m-d h:i:s"),
		     'CreatedUserName' => 'Mobile app',
		     'UploadPublishedDate' => date("Y-m-d")
	    );

	    $this->db->trans_start();

	    $this->db->insert('srp_erp_ngo_com_uploads', $masterdata);
	    $masterid = $this->db->insert_id();

	    if($areas){
		    $this->load->model('mobile_api/Common_app','common');
		    $basicdetails = $this->common->get_user_all_details($family_id,$user_id);

			$data = array();
		    foreach ($areas as $key) {
		    	array_push($data, ['ComUploadID' => $masterid,'uploadGSDiviID' => $key,'companyID' => $basicdetails->companyID]);
		    }
		    
		    $this->db->insert_batch('srp_erp_ngo_com_uploadsdivision',$data);
	    }

	    $this->db->trans_complete();
		$res = $this->db->trans_status();

		$this->db->close();
		echo json_encode($res);
	}


	public function get_old_all_posts(){
		$family_id = $this->input->get('family_id');
		$user_id = $this->input->get('user_id');

		$res = $this->db->select('ComUploadSubmited AS issubmit,ComUploadID AS id,FamMasterID AS family_id,ComUploadSubject AS title,ComUpload_url AS upload_url,ComUploadDescription AS description,UploadPublishedDate AS publish_date,isPublic')->where('FamMasterID',$family_id)->order_by('ComUploadID','DESC')->get('srp_erp_ngo_com_uploads')->result();

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