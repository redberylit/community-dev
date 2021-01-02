<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Common_app extends CI_Model {

	public function get_user_all_details($family_id,$user_id){
		$this->db->trans_start();
		$columns = array(
			'CFullName',
			'CName_with_initials',
			'IsNIC_NoYes',
			'CNIC_No',
			'CDOB',
			'Age',
			'GenderID',
			'countyID',
			'provinceID',
			'districtID',
			'RegionID',
			'companyID',
			'EmailID',
			'CImage',
			'TP_Mobile',
			'P_Address'
		);
		$res = $this->db->select($columns)->where('Com_MasterID',$user_id)->limit(1)->get('srp_erp_ngo_com_communitymaster')->row();
		$this->db->trans_complete();
		return $res;
	}

}