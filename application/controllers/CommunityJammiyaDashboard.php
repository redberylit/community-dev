<?php

class CommunityJammiyaDashboard extends ERP_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('CommunityJammiya_dash_modal');
        $this->load->helper('community_ngo_helper');
    }


    function communityDashSum_Count()
    {
        echo json_encode($this->CommunityJammiya_dash_modal->communityDashSum_Count());
    }

    function commPopulation_Count()
    {


        $countyID = $this->input->post("countyID");
        $provinceID = $this->input->post("provinceID");
        $districtID = $this->input->post("districtID");
        $districtDivisionID = $this->input->post("districtDivisionID");

        $areaMemId = $this->input->post('areaMemId');
        $gsDivitnId = $this->input->post('gsDivitnId');

        $areaMemIdS = "";
        if (isset($areaMemId) && !empty($areaMemId)) {
            $areaMemIdS = "AND srp_erp_ngo_com_communitymaster.RegionID IN(" . join(',', $areaMemId) . ")";
        }

        $gsDivitnIdS = "";
        if (isset($gsDivitnId) && !empty($gsDivitnId)) {
            $gsDivitnIdS = "AND srp_erp_ngo_com_communitymaster.GS_Division IN(" . join(',', $gsDivitnId) . ")";
        }

        $filter_req = array("AND (srp_erp_ngo_com_communitymaster.countyID=" . $countyID . ")" => $countyID, "AND (srp_erp_ngo_com_communitymaster.provinceID=" . $provinceID . ")" => $provinceID, "AND (srp_erp_ngo_com_communitymaster.districtID=" . $districtID . ")" => $districtID, "AND (srp_erp_ngo_com_communitymaster.districtDivisionID=" . $districtDivisionID . ")" => $districtDivisionID);
        $set_filter_req = array_filter($filter_req);
        $where_clsDsh = join(" ", array_keys($set_filter_req));


        $data['comMaster'] = $this->db->query("SELECT srp_erp_ngo_com_communitymaster.SerialNo AS SerialNos FROM srp_erp_ngo_com_communitymaster WHERE isDeleted='0' ")->row_array();


        $member_count = $this->db->query("SELECT COUNT(*) as membersCount FROM srp_erp_ngo_com_communitymaster where (isDeleted IS NULL OR isDeleted = '' OR isDeleted = 0) AND srp_erp_ngo_com_communitymaster.isActive='1' AND comVerifiApproved='1' $where_clsDsh " . " $areaMemIdS  " . " $gsDivitnIdS ")->row_array();

        $male_count = $this->db->query("SELECT COUNT(*) as malesCount FROM srp_erp_ngo_com_communitymaster where (isDeleted IS NULL OR isDeleted = '' OR isDeleted = 0) AND srp_erp_ngo_com_communitymaster.isActive='1' AND comVerifiApproved='1' AND GenderID='1' $where_clsDsh " . " $areaMemIdS  " . " $gsDivitnIdS")->row_array();

        $female_count = $this->db->query("SELECT COUNT(*) as femalesCount FROM srp_erp_ngo_com_communitymaster where (isDeleted IS NULL OR isDeleted = '' OR isDeleted = 0) AND srp_erp_ngo_com_communitymaster.isActive='1' AND comVerifiApproved='1' AND GenderID='2' $where_clsDsh " . " $areaMemIdS  " . " $gsDivitnIdS")->row_array();


        $data['members'] = $member_count['membersCount'];
        $data['males'] = $male_count['malesCount'];
        $data['females'] = $female_count['femalesCount'];

        //get Occupation-Wise
        $data['OccupationBase'] = $this->db->query("SELECT srp_erp_ngo_com_occupationtypes.Description FROM  srp_erp_ngo_com_occupationtypes INNER JOIN srp_erp_ngo_com_memjobs ON srp_erp_ngo_com_memjobs.OccTypeID=srp_erp_ngo_com_occupationtypes.OccTypeID LEFT JOIN srp_erp_ngo_com_communitymaster ON srp_erp_ngo_com_memjobs.Com_MasterID =srp_erp_ngo_com_communitymaster.Com_MasterID WHERE srp_erp_ngo_com_communitymaster.isActive='1' AND srp_erp_ngo_com_communitymaster.isDeleted='0' AND comVerifiApproved='1' $areaMemIdS  " . " $gsDivitnIdS GROUP BY srp_erp_ngo_com_occupationtypes.OccTypeID ")->result_array();
        $occupation_count = $this->db->query("SELECT COUNT(*) as occupationCount FROM  srp_erp_ngo_com_memjobs LEFT JOIN srp_erp_ngo_com_communitymaster ON srp_erp_ngo_com_memjobs.Com_MasterID =srp_erp_ngo_com_communitymaster.Com_MasterID INNER JOIN srp_erp_ngo_com_occupationtypes ON srp_erp_ngo_com_memjobs.OccTypeID=srp_erp_ngo_com_occupationtypes.OccTypeID WHERE comVerifiApproved='1' AND srp_erp_ngo_com_communitymaster.isActive='1' AND srp_erp_ngo_com_communitymaster.isDeleted='0'")->row_array();
        $data['occupation_type1'] = $this->db->query("SELECT COUNT(*) as occType1,srp_erp_ngo_com_occupationtypes.Description FROM  srp_erp_ngo_com_memjobs LEFT JOIN srp_erp_ngo_com_communitymaster ON srp_erp_ngo_com_memjobs.Com_MasterID =srp_erp_ngo_com_communitymaster.Com_MasterID INNER JOIN srp_erp_ngo_com_occupationtypes ON srp_erp_ngo_com_memjobs.OccTypeID=srp_erp_ngo_com_occupationtypes.OccTypeID WHERE srp_erp_ngo_com_communitymaster.isActive='1' AND srp_erp_ngo_com_communitymaster.isDeleted='0' AND comVerifiApproved='1' AND srp_erp_ngo_com_memjobs.OccTypeID='1'  $areaMemIdS  " . " $gsDivitnIdS")->row_array();
        $data['occupation_type2'] = $this->db->query("SELECT COUNT(*) as occType2,srp_erp_ngo_com_occupationtypes.Description FROM  srp_erp_ngo_com_memjobs LEFT JOIN srp_erp_ngo_com_communitymaster ON srp_erp_ngo_com_memjobs.Com_MasterID =srp_erp_ngo_com_communitymaster.Com_MasterID INNER JOIN srp_erp_ngo_com_occupationtypes ON srp_erp_ngo_com_memjobs.OccTypeID=srp_erp_ngo_com_occupationtypes.OccTypeID WHERE srp_erp_ngo_com_communitymaster.isActive='1' AND srp_erp_ngo_com_communitymaster.isDeleted='0' AND comVerifiApproved='1' AND srp_erp_ngo_com_memjobs.OccTypeID='2'  $areaMemIdS  " . " $gsDivitnIdS ")->row_array();
        $data['occupation_type3'] = $this->db->query("SELECT COUNT(*) as occType3,srp_erp_ngo_com_occupationtypes.Description FROM  srp_erp_ngo_com_memjobs LEFT JOIN srp_erp_ngo_com_communitymaster ON srp_erp_ngo_com_memjobs.Com_MasterID =srp_erp_ngo_com_communitymaster.Com_MasterID INNER JOIN srp_erp_ngo_com_occupationtypes ON srp_erp_ngo_com_memjobs.OccTypeID=srp_erp_ngo_com_occupationtypes.OccTypeID WHERE srp_erp_ngo_com_communitymaster.isActive='1' AND srp_erp_ngo_com_communitymaster.isDeleted='0' AND comVerifiApproved='1' AND srp_erp_ngo_com_memjobs.OccTypeID='3'  $areaMemIdS  " . " $gsDivitnIdS ")->row_array();
        $data['occupation_type4'] = $this->db->query("SELECT COUNT(*) as occType4,srp_erp_ngo_com_occupationtypes.Description FROM  srp_erp_ngo_com_memjobs LEFT JOIN srp_erp_ngo_com_communitymaster ON srp_erp_ngo_com_memjobs.Com_MasterID =srp_erp_ngo_com_communitymaster.Com_MasterID INNER JOIN srp_erp_ngo_com_occupationtypes ON srp_erp_ngo_com_memjobs.OccTypeID=srp_erp_ngo_com_occupationtypes.OccTypeID WHERE srp_erp_ngo_com_communitymaster.isActive='1' AND srp_erp_ngo_com_communitymaster.isDeleted='0' AND comVerifiApproved='1' AND srp_erp_ngo_com_memjobs.OccTypeID='4' $areaMemIdS  " . " $gsDivitnIdS ")->row_array();
        $data['occupation_type5'] = $this->db->query("SELECT COUNT(*) as occType5,srp_erp_ngo_com_occupationtypes.Description FROM  srp_erp_ngo_com_memjobs LEFT JOIN srp_erp_ngo_com_communitymaster ON srp_erp_ngo_com_memjobs.Com_MasterID =srp_erp_ngo_com_communitymaster.Com_MasterID INNER JOIN srp_erp_ngo_com_occupationtypes ON srp_erp_ngo_com_memjobs.OccTypeID=srp_erp_ngo_com_occupationtypes.OccTypeID WHERE srp_erp_ngo_com_communitymaster.isActive='1' AND srp_erp_ngo_com_communitymaster.isDeleted='0' AND comVerifiApproved='1' AND srp_erp_ngo_com_memjobs.OccTypeID='5' $areaMemIdS  " . " $gsDivitnIdS ")->row_array();
        $data['occupation_type6'] = $this->db->query("SELECT COUNT(*) as occType6,srp_erp_ngo_com_occupationtypes.Description FROM  srp_erp_ngo_com_memjobs LEFT JOIN srp_erp_ngo_com_communitymaster ON srp_erp_ngo_com_memjobs.Com_MasterID =srp_erp_ngo_com_communitymaster.Com_MasterID INNER JOIN srp_erp_ngo_com_occupationtypes ON srp_erp_ngo_com_memjobs.OccTypeID=srp_erp_ngo_com_occupationtypes.OccTypeID WHERE srp_erp_ngo_com_communitymaster.isActive='1' AND srp_erp_ngo_com_communitymaster.isDeleted='0' AND comVerifiApproved='1' AND srp_erp_ngo_com_memjobs.OccTypeID='6'  $areaMemIdS  " . " $gsDivitnIdS ")->row_array();


        $data['occupationTot'] = $occupation_count['occupationCount'];
        //end of  Occupation-Wise

        //get blood group counts

        $delBlood = $this->db->query("SELECT BloodTypeID,BloodDescription FROM srp_erp_bloodgrouptype ORDER BY BloodTypeID ASC ");
        $res_bloodGrp = $delBlood->result();
        $BloodTypeIDs = array();
        foreach ($res_bloodGrp as $row_bloodGrps) {
            $BloodTypeIDs[] = $row_bloodGrps->BloodTypeID;
        }

        $BloodTypeID = "'" . implode("', '", $BloodTypeIDs) . "'";

        //$data['loadBloodDes'] = $this->db->query("SELECT BloodTypeID,BloodDescription FROM srp_erp_bloodgrouptype WHERE BloodTypeID IN ($BloodTypeID) ORDER BY BloodTypeID DESC ")->result_array();

        $data['loadBloodCount'] = $this->db->query("SELECT COUNT(*) AS `NoOfGrpMem`,BloodDescription FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_erp_bloodgrouptype ON srp_erp_ngo_com_communitymaster.BloodGroupID=srp_erp_bloodgrouptype.BloodTypeID WHERE srp_erp_ngo_com_communitymaster.isActive='1' AND srp_erp_ngo_com_communitymaster.isDeleted='0' AND comVerifiApproved='1' AND BloodGroupID IN ($BloodTypeID) $where_clsDsh " . " $areaMemIdS  " . " $gsDivitnIdS GROUP BY BloodGroupID")->result_array();

        //end of blood group counts

        //get Marital Status
        $data['maritalBase'] = $this->db->query("SELECT srp_erp_ngo_com_maritalstatus.maritalstatus FROM  srp_erp_ngo_com_maritalstatus INNER JOIN srp_erp_ngo_com_communitymaster ON srp_erp_ngo_com_communitymaster.CurrentStatus=srp_erp_ngo_com_maritalstatus.maritalstatusID WHERE srp_erp_ngo_com_communitymaster.isDeleted='0' AND srp_erp_ngo_com_communitymaster.isActive='1' AND comVerifiApproved='1' $where_clsDsh " . " $areaMemIdS  " . " $gsDivitnIdS GROUP BY srp_erp_ngo_com_maritalstatus.maritalstatusID ORDER BY srp_erp_ngo_com_maritalstatus.maritalstatusID DESC")->result_array();
        $marital_count = $this->db->query("SELECT COUNT(*) as maritalStCount FROM  srp_erp_ngo_com_communitymaster INNER JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_communitymaster.CurrentStatus=srp_erp_ngo_com_maritalstatus.maritalstatusID WHERE srp_erp_ngo_com_communitymaster.isDeleted='0' AND srp_erp_ngo_com_communitymaster.isActive='1' AND comVerifiApproved='1' $where_clsDsh " . " $areaMemIdS  " . " $gsDivitnIdS ")->row_array();
        $data['maritalSt_type1'] = $this->db->query("SELECT COUNT(*) as merrType1,srp_erp_ngo_com_maritalstatus.maritalstatus FROM  srp_erp_ngo_com_communitymaster INNER JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_communitymaster.CurrentStatus=srp_erp_ngo_com_maritalstatus.maritalstatusID WHERE srp_erp_ngo_com_communitymaster.isDeleted='0' AND srp_erp_ngo_com_communitymaster.isActive='1' AND comVerifiApproved='1' AND srp_erp_ngo_com_communitymaster.CurrentStatus='1' $where_clsDsh " . " $areaMemIdS  " . " $gsDivitnIdS ")->row_array();
        $data['maritalSt_type2'] = $this->db->query("SELECT COUNT(*) as merrType2,srp_erp_ngo_com_maritalstatus.maritalstatus FROM  srp_erp_ngo_com_communitymaster INNER JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_communitymaster.CurrentStatus=srp_erp_ngo_com_maritalstatus.maritalstatusID WHERE srp_erp_ngo_com_communitymaster.isDeleted='0' AND srp_erp_ngo_com_communitymaster.isActive='1' AND comVerifiApproved='1' AND srp_erp_ngo_com_communitymaster.CurrentStatus='2' $where_clsDsh " . " $areaMemIdS  " . " $gsDivitnIdS ")->row_array();
        $data['maritalSt_type3'] = $this->db->query("SELECT COUNT(*) as merrType3,srp_erp_ngo_com_maritalstatus.maritalstatus FROM  srp_erp_ngo_com_communitymaster INNER JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_communitymaster.CurrentStatus=srp_erp_ngo_com_maritalstatus.maritalstatusID WHERE srp_erp_ngo_com_communitymaster.isDeleted='0' AND srp_erp_ngo_com_communitymaster.isActive='1' AND comVerifiApproved='1' AND srp_erp_ngo_com_communitymaster.CurrentStatus='3' $where_clsDsh " . " $areaMemIdS  " . " $gsDivitnIdS ")->row_array();
        $data['maritalSt_type4'] = $this->db->query("SELECT COUNT(*) as merrType4,srp_erp_ngo_com_maritalstatus.maritalstatus FROM  srp_erp_ngo_com_communitymaster INNER JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_communitymaster.CurrentStatus=srp_erp_ngo_com_maritalstatus.maritalstatusID WHERE srp_erp_ngo_com_communitymaster.isDeleted='0' AND srp_erp_ngo_com_communitymaster.isActive='1' AND comVerifiApproved='1' AND srp_erp_ngo_com_communitymaster.CurrentStatus='4' $where_clsDsh " . " $areaMemIdS  " . " $gsDivitnIdS ")->row_array();
        $data['maritalSt_type5'] = $this->db->query("SELECT COUNT(*) as merrType5,srp_erp_ngo_com_maritalstatus.maritalstatus FROM  srp_erp_ngo_com_communitymaster INNER JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_communitymaster.CurrentStatus=srp_erp_ngo_com_maritalstatus.maritalstatusID WHERE srp_erp_ngo_com_communitymaster.isDeleted='0' AND srp_erp_ngo_com_communitymaster.isActive='1' AND comVerifiApproved='1' AND srp_erp_ngo_com_communitymaster.CurrentStatus='5' $where_clsDsh " . " $areaMemIdS  " . " $gsDivitnIdS ")->row_array();


        $data['maritalStCount'] = $marital_count['maritalStCount'];
        //end of Marital Status
        //get Family Ancestry
        $query_ancesData = $this->db->query("SELECT DISTINCT AncestryCatID,AncestryDes FROM srp_erp_ngo_com_ancestrycategory");
        $res_ancesData = $query_ancesData->result();
        $AncestryCatID = array();
        foreach ($res_ancesData as $row_ancesData) {
            $AncestryCatID[] = $row_ancesData->AncestryCatID;
        }

        $AncestryCatIDS = "'" . implode("', '", $AncestryCatID) . "'";

        $data['loadFamAnces'] = $this->db->query("SELECT * FROM srp_erp_ngo_com_ancestrycategory WHERE AncestryCatID IN ($AncestryCatIDS)")->result_array();
        $data['loadPerFamilyAnces'] = $this->db->query("SELECT COUNT(*) AS `count` FROM srp_erp_ngo_com_familymaster INNER JOIN srp_erp_ngo_com_communitymaster ON srp_erp_ngo_com_communitymaster.Com_MasterID=srp_erp_ngo_com_familymaster.LeaderID LEFT JOIN srp_erp_ngo_com_ancestrycategory ON srp_erp_ngo_com_ancestrycategory.AncestryCatID=srp_erp_ngo_com_familymaster.AncestryCatID WHERE srp_erp_ngo_com_familymaster.isDeleted = '0' AND srp_erp_ngo_com_familymaster.isVerifyDocApproved='1' AND srp_erp_ngo_com_familymaster.FamAncestory = '1' AND srp_erp_ngo_com_familymaster.AncestryCatID IN ($AncestryCatIDS) $where_clsDsh " . " $areaMemIdS  " . " $gsDivitnIdS  GROUP BY srp_erp_ngo_com_familymaster.AncestryCatID")->result_array();
        //end of Family Ancestry

        //get Econ State
        $query_econData = $this->db->query("SELECT EconStateID,EconStateDes,EconStateVal FROM srp_erp_ngo_com_familyeconomicstatemaster ORDER BY EconStateID DESC");
        $res_econData = $query_econData->result();
        $EconStateID = array();
        foreach ($res_econData as $row_econData) {
            $EconStateID[] = $row_econData->EconStateID;
        }

        $EconStateIDS = "'" . implode("', '", $EconStateID) . "'";

        $data['loadEconState'] = $this->db->query("SELECT * FROM srp_erp_ngo_com_familyeconomicstatemaster WHERE EconStateID IN ($EconStateIDS) ORDER BY EconStateID DESC")->result_array();
        // column Chart //

        $data['loadEconSte'] = $this->db->query("SELECT COUNT(*) AS `countEconSte` FROM srp_erp_ngo_com_familymaster LEFT JOIN srp_erp_ngo_com_familyeconomicstatemaster ON srp_erp_ngo_com_familymaster.ComEconSteID=srp_erp_ngo_com_familyeconomicstatemaster.EconStateID LEFT JOIN srp_erp_ngo_com_communitymaster ON srp_erp_ngo_com_familymaster.LeaderID=srp_erp_ngo_com_communitymaster.Com_MasterID WHERE srp_erp_ngo_com_familymaster.isDeleted='0' AND srp_erp_ngo_com_familymaster.isVerifyDocApproved='1' AND srp_erp_ngo_com_familymaster.ComEconSteID IN ($EconStateIDS) $where_clsDsh " . " $areaMemIdS  " . " $gsDivitnIdS GROUP BY srp_erp_ngo_com_familymaster.ComEconSteID ORDER BY srp_erp_ngo_com_familymaster.ComEconSteID ASC")->result_array();

        //end of  Econ State

        // End of column Chart //

        $this->load->view('system/communityNgo/ajax/load_com_dash_jammiya', $data);
    }

    function load_houseEnrolling_del()
    {

        $areaMemId = $this->input->post('areaMemId');
        $gsDivitnId = $this->input->post('gsDivitnId');

        $areaMemIdS = "";
        if (isset($areaMemId) && !empty($areaMemId)) {
            $areaMemIdS = "AND cm.RegionID IN(" . join(',', $areaMemId) . ")";
        }

        $gsDivitnIdS = "";
        if (isset($gsDivitnId) && !empty($gsDivitnId)) {
            $gsDivitnIdS = "AND cm.GS_Division IN(" . join(',', $gsDivitnId) . ")";
        }

        $data['housingEnrl'] = $this->db->query("SELECT hEnr.hEnrollingID,fm.FamilySystemCode,fm.FamilyName,hEnr.FamHouseSt,hEnr.Link_hEnrollingID,cm.CName_with_initials,cm.C_Address,onrSp.ownershipDescription,tpMas.hTypeDescription,hEnr.hESizeInPerches,hEnr.isHmElectric,hEnr.isHmWaterSup,hEnr.isHmToilet,hEnr.isHmBathroom,hEnr.isHmTelephone,hEnr.isHmKitchen FROM srp_erp_ngo_com_house_enrolling hEnr 
LEFT JOIN srp_erp_ngo_com_familymaster fm ON fm.FamMasterID = hEnr.FamMasterID 
LEFT JOIN srp_erp_ngo_com_house_ownership_master onrSp ON onrSp.ownershipAutoID = hEnr.ownershipAutoID
LEFT JOIN srp_erp_ngo_com_house_type_master tpMas ON tpMas.hTypeAutoID = hEnr.hTypeAutoID
LEFT JOIN srp_erp_ngo_com_communitymaster cm ON cm.Com_MasterID = fm.LeaderID
WHERE (hEnr.FamHouseSt = '0' OR hEnr.FamHouseSt IS NULL) AND (fm.isDeleted = '0' OR fm.isDeleted IS NULL) AND cm.comVerifiApproved='1' AND (cm.isDeleted = '0' OR cm.isDeleted IS NULL) AND cm.comVerifiApproved='1' $areaMemIdS  " . " $gsDivitnIdS  ORDER BY hEnr.hEnrollingID")->result_array();

        $data["type"] = "html";
        $html = $this->load->view('system/communityNgo/ajax/load_com_dash_jammiya_housingDel.php', $data, true);

        // if ($this->input->post('html')) {
        echo $html;
        //  } else {
        //  $this->load->library('pdf');
        /*$pdf = $this->pdf->printed($html, 'A4-L', $data['extra']['master']['approvedYN']);*/
        //  }
    }

    function load_houseEnrolling_del_pdf()
    {

        $areaMemId = $this->input->post('areaMemId');
        $gsDivitnId = $this->input->post('gsDivitnId');

        $areaMemIdS = "";
        if (isset($areaMemId) && !empty($areaMemId)) {
            $areaMemIdS = "AND cm.RegionID IN(" . join(',', $areaMemId) . ")";
        }

        $gsDivitnIdS = "";
        if (isset($gsDivitnId) && !empty($gsDivitnId)) {
            $gsDivitnIdS = "AND cm.GS_Division IN(" . join(',', $gsDivitnId) . ")";
        }

        $data['housingEnrl'] = $this->db->query("SELECT hEnr.hEnrollingID,fm.FamilySystemCode,fm.FamilyName,hEnr.FamHouseSt,hEnr.Link_hEnrollingID,cm.CName_with_initials,cm.C_Address,onrSp.ownershipDescription,tpMas.hTypeDescription,hEnr.hESizeInPerches,hEnr.isHmElectric,hEnr.isHmWaterSup,hEnr.isHmToilet,hEnr.isHmBathroom,hEnr.isHmTelephone,hEnr.isHmKitchen FROM srp_erp_ngo_com_house_enrolling hEnr 
LEFT JOIN srp_erp_ngo_com_familymaster fm ON fm.FamMasterID = hEnr.FamMasterID 
LEFT JOIN srp_erp_ngo_com_house_ownership_master onrSp ON onrSp.ownershipAutoID = hEnr.ownershipAutoID
LEFT JOIN srp_erp_ngo_com_house_type_master tpMas ON tpMas.hTypeAutoID = hEnr.hTypeAutoID
LEFT JOIN srp_erp_ngo_com_communitymaster cm ON cm.Com_MasterID = fm.LeaderID
WHERE (hEnr.FamHouseSt = '0' OR hEnr.FamHouseSt IS NULL) AND (cm.isDeleted = '0' OR cm.isDeleted IS NULL) AND cm.comVerifiApproved='1' AND (fm.isDeleted = '0' OR fm.isDeleted IS NULL) $areaMemIdS  " . " $gsDivitnIdS  ORDER BY hEnr.hEnrollingID")->result_array();

        $data["type"] = "pdf";
        $html = $this->load->view('system/communityNgo/ajax/load_com_dash_jammiya_housingDel', $data, true);
        $this->load->library('pdf');
        $pdf = $this->pdf->printed($html, 'A4');
    }

    //area filtering
    function fetch_provinceBased_countryDropdown()
    {
        $countyID = $this->input->post('countyID');

        if ($countyID) {
            $province = $this->db->query("SELECT stateID,Description FROM srp_erp_statemaster WHERE countyID = {$countyID} AND type = 1")->result_array();

            echo '<option value="">Select a Province</option>';
            foreach ($province as $row) {

                echo '<option value="' . trim($row['stateID']) . '">' . trim($row['Description']) . '</option>';
            }
        }
    }

    function fetch_provinceBased_districtDropdown()
    {
        $masterID = $this->input->post('masterID');

        $dataStGet = $this->db->query("SELECT srp_erp_statemaster.countyID,srp_erp_ngo_com_regionmaster.stateID,srp_erp_statemaster.type,srp_erp_statemaster.divisionTypeCode FROM srp_erp_ngo_com_regionmaster INNER JOIN srp_erp_statemaster ON srp_erp_ngo_com_regionmaster.stateID=srp_erp_statemaster.stateID");
        $stateGet = $dataStGet->row();

        if (!empty($masterID)) {
            $district = $this->db->query("SELECT stateID,Description FROM srp_erp_statemaster WHERE masterID = {$masterID} AND type = 2")->result_array();
        }

        echo '<option  value="">Select a District</option>';
        if (!empty($district)) {
            foreach ($district as $row) {

                if ((!empty($stateGet) && $stateGet->type == 2) && ($stateGet->stateID == $row['stateID'])) {

                    echo '<option value="' . trim($row['stateID']) . '" selected="selected">' . trim($row['Description']) . '</option>';
                } else {
                    echo '<option value="' . trim($row['stateID']) . '">' . trim($row['Description']) . '</option>';
                }
            }
        }
    }

    function fetch_district_based_jammiyaDropdown()
    {
        $masterID = $this->input->post('masterID');

        $dataStGet = $this->db->query("SELECT srp_erp_statemaster.countyID,srp_erp_ngo_com_regionmaster.stateID,srp_erp_statemaster.type,srp_erp_statemaster.divisionTypeCode FROM srp_erp_ngo_com_regionmaster INNER JOIN srp_erp_statemaster ON srp_erp_ngo_com_regionmaster.stateID=srp_erp_statemaster.stateID");
        $stateGet = $dataStGet->row();

        if (!empty($masterID)) {
            $division = $this->db->query("SELECT stateID,Description FROM srp_erp_statemaster WHERE masterID = {$masterID} AND type = 3 AND divisionTypeCode = 'JD'")->result_array();
        }

        echo '<option value="">Select a Jammiyah Division</option>';
        if (!empty($division)) {
            foreach ($division as $row) {
                if ((!empty($stateGet) && $stateGet->type == 3 && $stateGet->divisionTypeCode == 'JD') && ($stateGet->stateID == $row['stateID'])) {
                    echo '<option value="' . trim($row['stateID']) . '" selected="selected">' . trim($row['Description']) . '</option>';
                } else {
                    echo '<option value="' . trim($row['stateID']) . '">' . trim($row['Description']) . '</option>';
                }
            }
        }
    }

    function fetch_district_divisionDropdown()
    {
        $masterID = $this->input->post('masterID');

        $dataStGet = $this->db->query("SELECT srp_erp_statemaster.countyID,srp_erp_ngo_com_regionmaster.stateID,srp_erp_statemaster.type,srp_erp_statemaster.divisionTypeCode FROM srp_erp_ngo_com_regionmaster INNER JOIN srp_erp_statemaster ON srp_erp_ngo_com_regionmaster.stateID=srp_erp_statemaster.stateID");
        $stateGet = $dataStGet->row();

        if (!empty($masterID)) {
            $division = $this->db->query("SELECT stateID,Description FROM srp_erp_statemaster WHERE masterID = {$masterID} AND type = 3 AND divisionTypeCode = 'DD'")->result_array();
        }

        echo '<option value="">Select a District Division</option>';
        if (!empty($division)) {
            foreach ($division as $row) {
                if ((!empty($stateGet) && $stateGet->type == 3 && $stateGet->divisionTypeCode == 'DD') && ($stateGet->stateID == $row['stateID'])) {
                    echo '<option value="' . trim($row['stateID']) . '" selected="selected">' . trim($row['Description']) . '</option>';
                } else {
                    echo '<option value="' . trim($row['stateID']) . '">' . trim($row['Description']) . '</option>';
                }
            }
        }
    }

    function fetch_division_based_GSDropdown()
    {
        $masterID = $this->input->post('masterID');
        if (!empty($masterID)) {
            $GSDrop = $this->db->query("SELECT stateID,Description FROM srp_erp_statemaster WHERE masterID = {$masterID} AND type = 4 AND divisionTypeCode = 'GN'")->result_array();

            $data['gsDiviDrop'] = $GSDrop;
        } else {
            $data['gsDiviDrop'] = '';
        }

        echo $this->load->view('system/communityNgo/ajax/com_gsDivision_dropDown', $data, true);
    }

    function fetch_distric_diviBase_Area_Dropdown1()
    {
        $masterID = $this->input->post('masterID');

        $this->load->model('CommunityJammiya_dash_modal');
        $this->CommunityJammiya_dash_modal->fetch_distric_diviBase_AreaDsh($masterID);
    }

    function fetch_distric_diviBase_Area_Dropdown()
    {

        $masterID = $this->input->post('masterID');

        if (!empty($masterID)) {
            $result = $this->db->query("SELECT stateID,Description FROM srp_erp_statemaster WHERE masterID = {$masterID} AND type = 4 AND divisionTypeCode = 'MH'")->result_array();

            $data['areaDrop'] = $result;
        } else {
            $data['areaDrop'] = '';
        }

        echo $this->load->view('system/communityNgo/ajax/communiy_area_dropDown', $data, true);
    }

    //community relevant modal popup

    /*community members */
    function load_comMembers_del()
    {

        $countyID = $this->input->post("countyID");
        $provinceID = $this->input->post("provinceID");
        $districtID = $this->input->post("districtID");
        $districtDivisionID = $this->input->post("districtDivisionID");

        $areaMemId = $this->input->post('areaMemId');
        $gsDivitnId = $this->input->post('gsDivitnId');

        $areaMemIdS = "";
        if (isset($areaMemId) && !empty($areaMemId)) {
            $areaMemIdS = "AND srp_erp_ngo_com_communitymaster.RegionID IN(" . join(',', $areaMemId) . ")";
        }

        $gsDivitnIdS = "";
        if (isset($gsDivitnId) && !empty($gsDivitnId)) {
            $gsDivitnIdS = "AND srp_erp_ngo_com_communitymaster.GS_Division IN(" . join(',', $gsDivitnId) . ")";
        }

        $filter_req = array("AND (srp_erp_ngo_com_communitymaster.countyID=" . $countyID . ")" => $countyID, "AND (srp_erp_ngo_com_communitymaster.provinceID=" . $provinceID . ")" => $provinceID, "AND (srp_erp_ngo_com_communitymaster.districtID=" . $districtID . ")" => $districtID, "AND (srp_erp_ngo_com_communitymaster.districtDivisionID=" . $districtDivisionID . ")" => $districtDivisionID);
        $set_filter_req = array_filter($filter_req);
        $where_clsDsh = join(" ", array_keys($set_filter_req));

        $data['comMembers'] = $this->db->query("SELECT *,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber,area.Description AS Region ,division.Description AS GS_Division,srp_erp_ngo_com_communitymaster.isDeleted FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster division ON division.stateID = srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster area ON area.stateID = srp_erp_ngo_com_communitymaster.RegionID WHERE (srp_erp_ngo_com_communitymaster.isDeleted IS NULL OR srp_erp_ngo_com_communitymaster.isDeleted = '' OR srp_erp_ngo_com_communitymaster.isDeleted = 0) AND srp_erp_ngo_com_communitymaster.isActive='1' AND srp_erp_ngo_com_communitymaster.comVerifiApproved='1' $where_clsDsh " . " $areaMemIdS  " . " $gsDivitnIdS ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();


        $data["type"] = "html";
        $html = $this->load->view('system/communityNgo/ajax/load_com_dash_other_membersDel.php', $data, true);

        // if ($this->input->post('html')) {
        echo $html;
        //  } else {
        //  $this->load->library('pdf');
        /*$pdf = $this->pdf->printed($html, 'A4-L', $data['extra']['master']['approvedYN']);*/
        //  }
    }

    function load_comMembers_del_pdf()
    {

        $countyID = $this->input->post("countyID");
        $provinceID = $this->input->post("provinceID");
        $districtID = $this->input->post("districtID");
        $districtDivisionID = $this->input->post("districtDivisionID");

        $areaMemId = $this->input->post('areaMemId');
        $gsDivitnId = $this->input->post('gsDivitnId');

        $areaMemIdS = "";
        if (isset($areaMemId) && !empty($areaMemId)) {
            $areaMemIdS = "AND srp_erp_ngo_com_communitymaster.RegionID IN(" . join(',', $areaMemId) . ")";
        }

        $gsDivitnIdS = "";
        if (isset($gsDivitnId) && !empty($gsDivitnId)) {
            $gsDivitnIdS = "AND srp_erp_ngo_com_communitymaster.GS_Division IN(" . join(',', $gsDivitnId) . ")";
        }

        $filter_req = array("AND (srp_erp_ngo_com_communitymaster.countyID=" . $countyID . ")" => $countyID, "AND (srp_erp_ngo_com_communitymaster.provinceID=" . $provinceID . ")" => $provinceID, "AND (srp_erp_ngo_com_communitymaster.districtID=" . $districtID . ")" => $districtID, "AND (srp_erp_ngo_com_communitymaster.districtDivisionID=" . $districtDivisionID . ")" => $districtDivisionID);
        $set_filter_req = array_filter($filter_req);
        $where_clsDsh = join(" ", array_keys($set_filter_req));

        $data['comMembers'] = $this->db->query("SELECT *,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber,area.Description AS Region ,division.Description AS GS_Division,srp_erp_ngo_com_communitymaster.isDeleted FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster division ON division.stateID = srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster area ON area.stateID = srp_erp_ngo_com_communitymaster.RegionID WHERE (isDeleted IS NULL OR isDeleted = '' OR isDeleted = 0) AND srp_erp_ngo_com_communitymaster.isActive='1' AND comVerifiApproved='1' $where_clsDsh " . " $areaMemIdS $gsDivitnIdS ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();

        $data["type"] = "pdf";
        $html = $this->load->view('system/communityNgo/ajax/load_com_dash_other_membersDel', $data, true);
        $this->load->library('pdf');
        $pdf = $this->pdf->printed($html, 'A4');
    }

    /*community families */
    function load_comFamilies_del()
    {

        $convertFormat = convert_date_format_sql();

        $areaMemId = $this->input->post('areaMemId');
        $gsDivitnId = $this->input->post('gsDivitnId');

        $countyID = $this->input->post("countyID");
        $provinceID = $this->input->post("provinceID");
        $districtID = $this->input->post("districtID");
        $districtDivisionID = $this->input->post("districtDivisionID");

        $areaMemIdS = "";
        if (isset($areaMemId) && !empty($areaMemId)) {
            $areaMemIdS = "AND srp_erp_ngo_com_communitymaster.RegionID IN(" . join(',', $areaMemId) . ")";
        }

        $gsDivitnIdS = "";
        if (isset($gsDivitnId) && !empty($gsDivitnId)) {
            $gsDivitnIdS = "AND srp_erp_ngo_com_communitymaster.GS_Division IN(" . join(',', $gsDivitnId) . ")";
        }

        $filter_req = array("AND (srp_erp_ngo_com_communitymaster.countyID=" . $countyID . ")" => $countyID, "AND (srp_erp_ngo_com_communitymaster.provinceID=" . $provinceID . ")" => $provinceID, "AND (srp_erp_ngo_com_communitymaster.districtID=" . $districtID . ")" => $districtID, "AND (srp_erp_ngo_com_communitymaster.districtDivisionID=" . $districtDivisionID . ")" => $districtDivisionID);
        $set_filter_req = array_filter($filter_req);
        $where_clsDsh = join(" ", array_keys($set_filter_req));

        $data['comuFamilies'] = $this->db->query("SELECT srp_erp_ngo_com_familymaster.createdUserID,LeaderID,FamilyCode,FamilyName,confirmedYN,srp_erp_ngo_com_familymaster.FamMasterID,FamilySystemCode,DATE_FORMAT(FamilyAddedDate,'{$convertFormat}') AS FamilyAddedDate, LedgerNo, CName_with_initials,FamAncestory,ComEconSteID,TP_home,TP_Mobile FROM srp_erp_ngo_com_familymaster INNER JOIN srp_erp_ngo_com_communitymaster on Com_MasterID=srp_erp_ngo_com_familymaster.LeaderID  WHERE srp_erp_ngo_com_familymaster.isDeleted = '0' AND srp_erp_ngo_com_familymaster.isVerifyDocApproved='1' $where_clsDsh " . " $areaMemIdS  " . " $gsDivitnIdS ORDER BY FamMasterID DESC")->result_array();

        $data["type"] = "html";
        $html = $this->load->view('system/communityNgo/ajax/load_com_dash_jammiya_familiesDel.php', $data, true);

        // if ($this->input->post('html')) {
        echo $html;
        //  } else {
        //  $this->load->library('pdf');
        /*$pdf = $this->pdf->printed($html, 'A4-L', $data['extra']['master']['approvedYN']);*/
        //  }
    }

    /*community committees */
    function load_comCommittees_del()
    {


        $commitAreaId = $this->input->post('areaMemId');
        // $gscomitDivitnId = $this->input->post('gsDivitnId');

        $data['comCommittees'] = $this->db->query("SELECT * FROM srp_erp_ngo_com_committeesmaster INNER JOIN srp_erp_ngo_com_committeeareawise ON srp_erp_ngo_com_committeesmaster.CommitteeID=srp_erp_ngo_com_committeeareawise.CommitteeID WHERE (isDeleted IS NULL OR isDeleted = '' OR isDeleted = 0) AND srp_erp_ngo_com_committeesmaster.isActive = '1' GROUP BY srp_erp_ngo_com_committeesmaster.CommitteeID ORDER BY srp_erp_ngo_com_committeesmaster.CommitteeID DESC ")->result_array();

        $data['commitAreaId'] = $commitAreaId;

        $data["type"] = "html";
        $html = $this->load->view('system/communityNgo/ajax/load_com_dash_other_committeesDel.php', $data, true);
        echo $html;
    }

    //end of community relevant modal popup

    function comJammiya_findPeople()
    {

        $this->load->view('system/communityNgo/ajax/load_com_dash_find_people');
    }

    function load_people_searchPersonDel()
    {

        $convertFormat = convert_date_format_sql();

        $text = trim($this->input->post('searchTask'));

        $search_string = '';
        if (isset($text) && !empty($text)) {
            $search_string = " ((MemberCode Like '%" . $text . "%') OR (CName_with_initials Like '%" . $text . "%') OR (division.Description Like '%" . $text . "%') OR (area.Description Like '%" . $text . "%') OR (CNIC_No Like '%" . $text . "%') OR (CONCAT(TP_home,TP_Mobile) Like '%" . $text . "%'))";
        }

        $where = $search_string;
        if ($where) {
            $data['srchPeopleDel'] = $this->db->query("SELECT *,srp_erp_ngo_com_communitymaster.Com_MasterID,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOBs,srp_erp_gender.name AS genderName,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber,area.Description AS Region ,division.Description AS GS_Division,srp_erp_ngo_com_maritalstatus.maritalstatusID,srp_erp_ngo_com_maritalstatus.maritalstatus,BloodDescription FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster division ON division.stateID = srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster area ON area.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus LEFT JOIN srp_erp_bloodgrouptype ON srp_erp_ngo_com_communitymaster.BloodGroupID=srp_erp_bloodgrouptype.BloodTypeID WHERE comVerifiApproved='1' AND srp_erp_ngo_com_communitymaster.isDeleted='0' AND $where ")->result_array();
        } else {
            $data['srchPeopleDel'] = '';
        }

        $this->load->view('system/communityNgo/ajax/load_com_dash_find_people_result', $data);
    }

    //end of job apply-for posting attachment
    function load_people_attachments()
    {
        $this->db->where('documentAutoID', $this->input->post('Com_MasterID'));
        $this->db->where('documentID', $this->input->post('documentID'));
        $data = $this->db->get('srp_erp_ngo_attachments')->result_array();

        $result = '';
        $x = 1;
        if (!empty($data)) {
            foreach ($data as $val) {
                $burl = base_url("attachments") . '/' . $val['myFileName'];
                $type = '<i class="color fa fa-file-pdf-o" aria-hidden="true"></i>';
                if ($val['fileType'] == '.xlsx') {
                    $type = '<i class="color fa fa-file-excel-o" aria-hidden="true"></i>';
                } else if ($val['fileType'] == '.xls') {
                    $type = '<i class="color fa fa-file-excel-o" aria-hidden="true"></i>';
                } else if ($val['fileType'] == '.xlsxm') {
                    $type = '<i class="color fa fa-file-excel-o" aria-hidden="true"></i>';
                } else if ($val['fileType'] == '.doc') {
                    $type = '<i class="color fa fa-file-word-o" aria-hidden="true"></i>';
                } else if ($val['fileType'] == '.docx') {
                    $type = '<i class="color fa fa-file-word-o" aria-hidden="true"></i>';
                } else if ($val['fileType'] == '.ppt') {
                    $type = '<i class="color fa fa-file-powerpoint-o" aria-hidden="true"></i>';
                } else if ($val['fileType'] == '.pptx') {
                    $type = '<i class="color fa fa-file-powerpoint-o" aria-hidden="true"></i>';
                } else if ($val['fileType'] == '.jpg') {
                    $type = '<i class="color fa fa-file-image-o" aria-hidden="true"></i>';
                } else if ($val['fileType'] == '.jpeg') {
                    $type = '<i class="color fa fa-file-image-o" aria-hidden="true"></i>';
                } else if ($val['fileType'] == '.gif') {
                    $type = '<i class="color fa fa-file-image-o" aria-hidden="true"></i>';
                } else if ($val['fileType'] == '.png') {
                    $type = '<i class="color fa fa-file-image-o" aria-hidden="true"></i>';
                } else if ($val['fileType'] == '.txt') {
                    $type = '<i class="color fa fa-file-text-o" aria-hidden="true"></i>';
                }

                $link = generate_encrypt_link_only($burl);
                $result .= '<tr id="' . $val['attachmentID'] . '"><td>' . $x . '</td><td>' . $val['myFileName'] . '</td><td>' . $val['attachmentDescription'] . '</td><td class="text-center">' . $type . '</td><td class="text-center"><a target="_blank" href="' . $link . '" ><i class="fa fa-download" aria-hidden="true"></i></a> &nbsp; | &nbsp; <a onclick="delete_fJob_attachment(' . $val['attachmentID'] . ',\'' . $val['myFileName'] . '\')"></a></td></tr>';
                $x++;
            }
        } else {
            $result = '<tr class="danger"><td colspan="5" class="text-center">No Attachment Found</td></tr>';
        }
        echo json_encode($result);
    }

    /*community family */
    function load_otrFamily_del()
    {

        $convertFormat = convert_date_format_sql();

        $FamMasterID = $this->input->post('FamMasterID');

        $data['otrFamily'] = $this->db->query("SELECT srp_erp_ngo_com_familymaster.createdUserID,LeaderID,FamilyCode,FamilyName,confirmedYN,srp_erp_ngo_com_familymaster.FamMasterID,FamilySystemCode,DATE_FORMAT(FamilyAddedDate,'{$convertFormat}') AS FamilyAddedDate, LedgerNo, CName_with_initials,FamAncestory,ComEconSteID,TP_home,TP_Mobile FROM srp_erp_ngo_com_familymaster INNER JOIN srp_erp_ngo_com_communitymaster on Com_MasterID=srp_erp_ngo_com_familymaster.LeaderID  WHERE srp_erp_ngo_com_familymaster.isDeleted = '0' AND srp_erp_ngo_com_familymaster.isVerifyDocApproved='1' AND srp_erp_ngo_com_familymaster.FamMasterID ='" . $FamMasterID . "'")->result_array();

        $data["type"] = "html";
        $html = $this->load->view('system/communityNgo/ajax/load_com_dash_other_familyDel.php', $data, true);

        // if ($this->input->post('html')) {
        echo $html;
        //  } else {
        //  $this->load->library('pdf');
        /*$pdf = $this->pdf->printed($html, 'A4-L', $data['extra']['master']['approvedYN']);*/
        //  }
    }
}

/**
 * Created by PhpStorm.
 * User: Moufiya
 * Date: 11/27/2018
 * Time: 3:48 PM
 */
