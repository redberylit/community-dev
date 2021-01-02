<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Registration_app extends CI_Model {

	public function get_country_details(){

		$res = $this->db->select('countryID AS id,CONCAT(CountryDes," - ",countryShortCode) AS name_')->order_by('CountryDes')->get('srp_erp_countrymaster')->result();

		$this->db->close();
		echo json_encode($res);
	}

	public function get_province_or_state(){
		$id = $this->input->get('id');

		$res = $this->db->select('stateID as id,Description as name_')->where('countyID',$id)->where('masterID IS NULL')->where('type',1)->order_by('Description')->get('srp_erp_statemaster')->result();

		$this->db->close();
		echo json_encode($res);
	}

	public function get_district(){
		$id = $this->input->get('id');

		$res = $this->db->select('stateID as id,Description as name_')->where('masterID',$id)->where('divisionTypeCode IS NULL')->order_by('Description')->get('srp_erp_statemaster')->result();

		$this->db->close();
		echo json_encode($res);
	}

	public function get_area(){
		$id = $this->input->get('id');

		$res = $this->db->select('stateID as id,Description as name_')->where('masterID',$id)->where('divisionTypeCode','DD')->order_by('Description')->get('srp_erp_statemaster')->result();

		$this->db->close();
		echo json_encode($res);
	}

	public function get_company(){
		$id = $this->input->get('id');

		$res = $this->db->select('stateID as id,Description as name_')->where('masterID',$id)->where('divisionTypeCode','MH')->order_by('Description')->get('srp_erp_statemaster')->result();

		$this->db->close();
		echo json_encode($res);
	}

	public function check_username_availability(){

		$username = $this->input->post('username');
		$res = $this->db->select('FamUsername')->where('(REPLACE(TRIM(LOWER(FamUsername))," ","")) ="'.str_replace(" ","",strtolower($username)).'"'  )->limit(1)->get('srp_erp_ngo_com_familymaster')->row();

		if($res){
			echo json_encode(true);
		} else {
			echo json_encode(false);
		}
	}

	public function register_user(){
		
		$os = $this->input->post('os');
		$res = array();

		$this->db->trans_start();
		$birthday = $this->input->post('birthday');
		$today = date("Y-m-d");
		$diff = date_diff(date_create($birthday), date_create($today));
		$age = $diff->format('%y').'yrs';

		$basicdetails = array(
			'CFullName' => $this->input->post('fullname'),
			'CName_with_initials' => $this->input->post('fullname'),
			'IsNIC_NoYes' => 1,
			'CNIC_No' => $this->input->post('nicno'),
			'CDOB' => $birthday,
			'Age' => $age,
			'GenderID' => $this->input->post('gender'),
			'countyID' => $this->input->post('country'),
			'provinceID' => $this->input->post('provincestate'),
			'districtID' => $this->input->post('district'),
			'RegionID' => $this->input->post('area'),
			'companyID' => $this->input->post('company'),
			'EmailID' => $this->input->post('email') 
		);
		$this->db->insert('srp_erp_ngo_com_communitymaster',$basicdetails);
		$basicid = $this->db->insert_id();

		$familydetails = array(
			'FamilyCode' => 'FAM',
			'LeaderID' => $basicid,
			'companyID' => $this->input->post('company'),
			'FamUsername' => $this->input->post('username'),
			'FamPassword' => md5($this->input->post('password')) 
		);
		$this->db->insert('srp_erp_ngo_com_familymaster',$familydetails);
		$familyid = $this->db->insert_id();

		$membercode = 'MHJM/MEM'.sprintf('%06d',$basicid);
		$familycode = 'MHJM/FAM'.sprintf('%06d',$familyid);

		$this->db->set('SerialNo',$basicid)->set('MemberCode',$membercode)->where('Com_MasterID',$basicid)->limit(1)->update('srp_erp_ngo_com_communitymaster');

		$this->db->set('FamilySerialNo',$familyid)->set('FamilySystemCode',$familycode)->where('FamMasterID',$familyid)->limit(1)->update('srp_erp_ngo_com_familymaster');

		$famdet = array(
			'FamMasterID' => $familyid,
			'Com_MasterID' => $basicid,
			'isLeader' => 1,
			'FamMemAddedDate' => $today,
			'companyID' => $this->input->post('company')
		);
		$this->db->insert('srp_erp_ngo_com_familydetails',$famdet);

		$this->db->trans_complete();
		if($this->db->trans_status() == true){
			$res['s'] = true;
			$res['user_id'] = $basicid;
			$res['family_id'] = $familyid;
		} else {
			$res['s'] = false;
			$res['user_id'] = 0;
			$res['family_id'] = 0;
		}
		
		$this->db->close();
		echo json_encode($res);
	}

	public function get_document_types(){
		$res = $this->db->select('id,description as name_')->get('srp_erp_system_document_types')->result();
		$this->db->close();
		echo json_encode($res);
	}

	public function upload_varification_docs(){
		$family_id = $this->input->get('family_id');
		$user_id = $this->input->get('user_id');
		$doc_id = $this->input->get('doc_id');

		$this->load->library('s3');

		$filename_front = "attachments/Community/family_registration/".time().rand(10,100).$_FILES['imagefront']['name'];
		$input_front = $this->s3->inputFile($_FILES['imagefront']['tmp_name']);
		$uploadedname_front = $this->s3->putMyObject($input_front, $filename_front);


		$filename_back = "attachments/Community/family_registration/".time().rand(10,100).$_FILES['imageback']['name'];
		$input_back = $this->s3->inputFile($_FILES['imageback']['tmp_name']);
		$uploadedname_back = $this->s3->putMyObject($input_back, $filename_back);

		$this->db->trans_start();
		if($uploadedname_front && $uploadedname_back){
			$this->db->set('VerificationDocID',$doc_id)->set('VerifyDocAttachmentFront',$filename_front)->set('VerifyDocAttachmentBack',$filename_back)->where('FamMasterID',$family_id)->where('LeaderID',$user_id)->limit(1)->update('srp_erp_ngo_com_familymaster');

		}
		$this->db->trans_complete();
		$res = $this->db->trans_status();
		$this->db->close();
		echo json_encode($res);
		
	}


	public function send_varification_code(){
		$family_id = $this->input->get('family_id');
		$user_id = $this->input->get('user_id');
		$phone_no = $this->input->get('phone_no');
		$code = rand(1000, 9999);

		$this->db->trans_start();

		$this->db->set('smsVerificationCode',$code)->where('FamMasterID',$family_id)->where('LeaderID',$user_id)->limit(1)->update('srp_erp_ngo_com_familymaster');

        $this->load->library('send_sms');
		$this->send_sms->send($phone_no,$code);

		
		$this->db->trans_complete();
		echo json_encode($this->db->trans_status());
	}

	public function check_varification_code(){
		$family_id = $this->input->get('family_id');
		$user_id = $this->input->get('user_id');
		$phone_no = $this->input->get('phone_no');
		$code = $this->input->get('code');

		$this->db->trans_start();
		$res = false;
		$data = $this->db->select('smsVerificationCode')->where('FamMasterID',$family_id)->where('LeaderID',$user_id)->limit(1)->get('srp_erp_ngo_com_familymaster')->row();
		if(trim($code) == trim($data->smsVerificationCode)){
			$this->db->set('isSmsVerified',1)->where('FamMasterID',$family_id)->where('LeaderID',$user_id)->limit(1)->update('srp_erp_ngo_com_familymaster');

			$this->db->set('TP_Mobile',$phone_no)->where('Com_MasterID',$user_id)->limit(1)->update('srp_erp_ngo_com_communitymaster');
			$res = true;
		}
		
		$this->db->trans_complete();
		echo json_encode($res);
	}

	public function get_user_details(){
		$family_id = $this->input->get('family_id');
		$user_id = $this->input->get('user_id');
		
		$this->load->model('mobile_api/Common_app','common');
		$res = $this->common->get_user_all_details($family_id,$user_id);
		echo json_encode($res);
	}

}