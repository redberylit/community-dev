<?php

class CommunityJammiyaNgo extends ERP_Controller
{


    /*common -Genral division report details*/

    function get_generalMem_diviReport()
    {
        $date_format_policy = date_format_policy();

        $countyID = $this->input->post("countyID");
        $provinceID = $this->input->post("provinceID");
        $districtID = $this->input->post("districtID");
        $districtDivisionID = $this->input->post("districtDivisionID");
        $areaMemId = $this->input->post("areaMemId");
        $gsDivitnId = $this->input->post("gsDivitnId");
        $text = trim($this->input->post('divitnSerch'));

        $convertFormat = convert_date_format_sql();

        if ($areaMemId == NULL || $areaMemId == "") {

            $filter_req = array("AND (srp_erp_ngo_com_communitymaster.countyID=" . $countyID . ")" => $countyID, "AND (srp_erp_ngo_com_communitymaster.provinceID=" . $provinceID . ")" => $provinceID, "AND (srp_erp_ngo_com_communitymaster.districtID=" . $districtID . ")" => $districtID, "AND (srp_erp_ngo_com_communitymaster.districtDivisionID=" . $districtDivisionID . ")" => $districtDivisionID, "AND (srp_erp_ngo_com_communitymaster.GS_Division='" . $gsDivitnId . "')" => $gsDivitnId);
            $set_filter_req = array_filter($filter_req);
            $where_clauseq = join(" ", array_keys($set_filter_req));
        } else {

            $filter_req = array("AND (srp_erp_ngo_com_communitymaster.countyID=" . $countyID . ")" => $countyID, "AND (srp_erp_ngo_com_communitymaster.provinceID=" . $provinceID . ")" => $provinceID, "AND (srp_erp_ngo_com_communitymaster.districtID=" . $districtID . ")" => $districtID, "AND (srp_erp_ngo_com_communitymaster.districtDivisionID=" . $districtDivisionID . ")" => $districtDivisionID, "AND (srp_erp_ngo_com_communitymaster.RegionID=" . $areaMemId . ")" => $areaMemId, "AND (srp_erp_ngo_com_communitymaster.GS_Division='" . $gsDivitnId . "')" => $gsDivitnId);
            $set_filter_req = array_filter($filter_req);
            $where_clauseq = join(" ", array_keys($set_filter_req));
        }

        $srch_string = '';
        if (isset($text) && !empty($text)) {

            $srch_string = " AND ((MemberCode Like '%" . $text . "%') OR (CName_with_initials Like '%" . $text . "%') OR (CNIC_No Like '%" . $text . "%') OR (name Like '%" . $text . "%') OR (CDOB Like '%" . $text . "%') OR(CONCAT(TP_Mobile,' | ',TP_home) Like '%" . $text . "%') OR (areac.Description Like '%" . $text . "%') OR (divisionc.Description Like '%" . $text . "%'))";
        }

        $isActive = " AND srp_erp_ngo_com_communitymaster.isActive = '1' ";
        $comVerifiApproved = " AND srp_erp_ngo_com_communitymaster.comVerifiApproved = '1' ";

        $where = "srp_erp_ngo_com_communitymaster.isDeleted = '0' " . $isActive . $comVerifiApproved . $srch_string;

        if ($areaMemId == NULL || $areaMemId == "") {
            $data['diviReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID  LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID WHERE $where " . $where_clauseq . "  ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else {
            $areaType = $areaMemId;

            $data['diviReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID WHERE srp_erp_ngo_com_communitymaster.RegionID='" . $areaType . "' AND $where " . $where_clauseq . "  ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        }

        $data["type"] = "html";
        echo $html = $this->load->view('system/communityNgo/ajax/load_community_division_report', $data, true);
    }

    function get_generalMem_diviReport_pdf()
    {
        $date_format_policy = date_format_policy();

        $countyID = $this->input->post("countyID");
        $provinceID = $this->input->post("provinceID");
        $districtID = $this->input->post("districtID");
        $districtDivisionID = $this->input->post("districtDivisionID");
        $areaMemId = $this->input->post("areaMemId");
        $gsDivitnId = $this->input->post("gsDivitnId");
        $text = trim($this->input->post('divitnSerch'));

        $convertFormat = convert_date_format_sql();

        if ($areaMemId == NULL || $areaMemId == "") {

            $filter_req = array("AND (srp_erp_ngo_com_communitymaster.countyID=" . $countyID . ")" => $countyID, "AND (srp_erp_ngo_com_communitymaster.provinceID=" . $provinceID . ")" => $provinceID, "AND (srp_erp_ngo_com_communitymaster.districtID=" . $districtID . ")" => $districtID, "AND (srp_erp_ngo_com_communitymaster.districtDivisionID=" . $districtDivisionID . ")" => $districtDivisionID, "AND (srp_erp_ngo_com_communitymaster.GS_Division='" . $gsDivitnId . "')" => $gsDivitnId);
            $set_filter_req = array_filter($filter_req);
            $where_clauseq = join(" ", array_keys($set_filter_req));
        } else {

            $filter_req = array("AND (srp_erp_ngo_com_communitymaster.countyID=" . $countyID . ")" => $countyID, "AND (srp_erp_ngo_com_communitymaster.provinceID=" . $provinceID . ")" => $provinceID, "AND (srp_erp_ngo_com_communitymaster.districtID=" . $districtID . ")" => $districtID, "AND (srp_erp_ngo_com_communitymaster.districtDivisionID=" . $districtDivisionID . ")" => $districtDivisionID, "AND (srp_erp_ngo_com_communitymaster.RegionID=" . $areaMemId . ")" => $areaMemId, "AND (srp_erp_ngo_com_communitymaster.GS_Division='" . $gsDivitnId . "')" => $gsDivitnId);
            $set_filter_req = array_filter($filter_req);
            $where_clauseq = join(" ", array_keys($set_filter_req));
        }

        $srch_string = '';
        if (isset($text) && !empty($text)) {

            $srch_string = " AND ((MemberCode Like '%" . $text . "%') OR (CName_with_initials Like '%" . $text . "%') OR (CNIC_No Like '%" . $text . "%') OR (name Like '%" . $text . "%') OR (CDOB Like '%" . $text . "%') OR(CONCAT(TP_Mobile,' | ',TP_home) Like '%" . $text . "%') OR (areac.Description Like '%" . $text . "%') OR (divisionc.Description Like '%" . $text . "%'))";
        }

        $isActive = " AND srp_erp_ngo_com_communitymaster.isActive = '1' ";
        $comVerifiApproved = " AND srp_erp_ngo_com_communitymaster.comVerifiApproved = '1' ";

        $where = "srp_erp_ngo_com_communitymaster.isDeleted = '0' " . $isActive . $comVerifiApproved . $srch_string;

        if ($areaMemId == NULL || $areaMemId == '') {
            $data['diviReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID WHERE $where " . $where_clauseq . "  ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else {
            $areaType = $areaMemId;

            $data['diviReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID WHERE srp_erp_ngo_com_communitymaster.RegionID='" . $areaType . "' AND $where " . $where_clauseq . "  ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        }


        $data["type"] = "pdf";
        $html = $this->load->view('system/communityNgo/ajax/load_community_division_report', $data, true);
        $this->load->library('pdf');
        $pdf = $this->pdf->printed($html, 'A4');
    }

    /*community Occupation report details*/

    function get_generalComStatus_report()
    {

        $memberTypes = $this->input->post("memberType");
        $JobCatIds = $this->input->post("JobCatId");
        $schlIds = $this->input->post("schlId");
        $MedumIds = $this->input->post("MedumId");
        $classIds = $this->input->post("classId");

        $text = trim($this->input->post('PlaceDes'));

        if ($memberTypes == '1') {
            $JobCatId = '';
            $schlId = $schlIds;
            $MedumId = $MedumIds;
            $classId = $classIds;
        } else {
            $JobCatId = $JobCatIds;
            $schlId = '';
            $MedumId = '';
            $classId = '';
        }

        $convertFormat = convert_date_format_sql();

        $filter_re = array("AND (srp_erp_ngo_com_memjobs.JobCategoryID=" . $JobCatId . ")" => $JobCatId, "AND (srp_erp_ngo_com_memjobs.schoolComID='" . $schlId . "')" => $schlId, "AND (srp_erp_ngo_com_memjobs.LanguageID='" . $MedumId . "')" => $MedumId, "AND (srp_erp_ngo_com_memjobs.gradeComID='" . $classId . "')" => $classId);
        $set_filter_re = array_filter($filter_re);
        $where_clause = join(" ", array_keys($set_filter_re));

        $srch_string = '';
        if (isset($text) && !empty($text)) {
            if ($memberTypes == '8') {
                $srch_string = " AND ((CName_with_initials Like '%" . $text . "%') OR (CNIC_No Like '%" . $text . "%') OR (name Like '%" . $text . "%') OR (MemberCode Like '%" . $text . "%') OR (EmailID Like '%" . $text . "%') OR (CONCAT(TP_home,TP_Mobile) Like '%" . $text . "%'))";
            } else {
                $srch_string = " AND ((WorkingPlace Like '%" . $text . "%') OR (MemberCode Like '%" . $text . "%') OR (srp_erp_ngo_com_occupationtypes.Description Like '%" . $text . "%') OR (srp_erp_ngo_com_memjobs.DateFrom Like '%" . $text . "%') OR (gradeComDes Like '%" . $text . "%') OR (JobCatDescription Like '%" . $text . "%') OR(srp_erp_ngo_com_memjobs.Address Like '%" . $text . "%') OR (CName_with_initials Like '%" . $text . "%') OR (CNIC_No Like '%" . $text . "%') OR (name Like '%" . $text . "%') OR (EmailID Like '%" . $text . "%') OR (CONCAT(TP_home,TP_Mobile) Like '%" . $text . "%'))";
            }
        }


        $isActive = " AND srp_erp_ngo_com_communitymaster.isActive = '1' ";
        $comVerifiApproved = " AND srp_erp_ngo_com_communitymaster.comVerifiApproved='1' ";

        $where = "srp_erp_ngo_com_communitymaster.isDeleted = '0'" . $isActive . $comVerifiApproved . $srch_string;

        $queryFP = $this->db->query("SELECT DISTINCT srp_erp_ngo_com_memjobs.Com_MasterID  FROM srp_erp_ngo_com_memjobs");
        $rowFP = $queryFP->result();
        $memInr = array();
        foreach ($rowFP as $resFP) {

            $memInr[] = $resFP->Com_MasterID;
        }

        $in_memPrt = "'" . implode("', '", $memInr) . "'";

        $this->form_validation->set_rules('memberType', 'Occupation Type', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo ' <div class="alert alert-warning" role="alert">
              ' . validation_errors() . '
          </div>';
        } else {


            if (!empty($memberTypes)) {
                if ($memberTypes == '-1') {

                    $data['memReport'] = $this->db->query("SELECT MemberCode,srp_erp_ngo_com_communitymaster.createdUserID,srp_erp_ngo_com_memjobs.gradeComID,srp_erp_ngo_com_memjobs.WorkingPlace,srp_erp_ngo_com_memjobs.Address,DATE_FORMAT(srp_erp_ngo_com_memjobs.DateFrom,'{$convertFormat}') AS DateFrom,srp_erp_ngo_com_memjobs.DateTo,srp_erp_ngo_com_memjobs.MemJobID,srp_erp_ngo_com_memjobs.OccTypeID,srp_erp_ngo_com_memjobs.JobCategoryID,srp_erp_ngo_com_memjobs.isPrimary,CName_with_initials,TP_home,CNIC_No,TP_Mobile,EmailID,C_Address,P_Address,HouseNo,GS_Division,GS_No,srp_erp_ngo_com_occupationtypes.OccTypeID,(srp_erp_ngo_com_occupationtypes.Description) AS OcDescription,srp_erp_gender.genderID,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber,srp_erp_ngo_com_grades.gradeComID,srp_erp_ngo_com_grades.gradeComDes,srp_erp_ngo_com_jobcategories.JobCategoryID,srp_erp_ngo_com_jobcategories.JobCatDescription,srp_erp_ngo_com_schools.schoolComID,srp_erp_ngo_com_schools.schoolComDes FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_erp_ngo_com_memjobs ON srp_erp_ngo_com_communitymaster.Com_MasterID=srp_erp_ngo_com_memjobs.Com_MasterID  LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster ON srp_erp_statemaster.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_occupationtypes ON srp_erp_ngo_com_occupationtypes.OccTypeID=srp_erp_ngo_com_memjobs.OccTypeID LEFT JOIN srp_erp_ngo_com_grades ON srp_erp_ngo_com_memjobs.gradeComID=srp_erp_ngo_com_grades.gradeComID LEFT JOIN srp_erp_ngo_com_schools ON srp_erp_ngo_com_memjobs.schoolComID=srp_erp_ngo_com_schools.schoolComID LEFT JOIN srp_erp_ngo_com_jobcategories ON srp_erp_ngo_com_jobcategories.JobCategoryID=srp_erp_ngo_com_memjobs.JobCategoryID WHERE $where " . $where_clause . " ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC")->result_array();
                } elseif ($memberTypes == '8') {

                    $data['memReport'] = $this->db->query("SELECT *,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster ON srp_erp_statemaster.stateID = srp_erp_ngo_com_communitymaster.RegionID WHERE Com_MasterID NOT IN ($in_memPrt) AND $where ORDER BY Com_MasterID DESC ")->result_array();
                } else {
                    $MemType = $memberTypes;

                    $data['memReport'] = $this->db->query("SELECT MemberCode,srp_erp_ngo_com_memjobs.createdUserID,srp_erp_ngo_com_memjobs.gradeComID,srp_erp_ngo_com_memjobs.WorkingPlace,srp_erp_ngo_com_memjobs.Address,DATE_FORMAT(srp_erp_ngo_com_memjobs.DateFrom,'{$convertFormat}') AS DateFrom,srp_erp_ngo_com_memjobs.DateTo,srp_erp_ngo_com_memjobs.MemJobID,srp_erp_ngo_com_memjobs.OccTypeID,srp_erp_ngo_com_memjobs.JobCategoryID,srp_erp_ngo_com_memjobs.isPrimary, CName_with_initials,TP_home,CNIC_No,TP_Mobile,EmailID,C_Address,P_Address,HouseNo,GS_Division,GS_No,srp_erp_ngo_com_occupationtypes.OccTypeID,(srp_erp_ngo_com_occupationtypes.Description) AS OcDescription,srp_erp_gender.genderID,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber,srp_erp_ngo_com_grades.gradeComID,srp_erp_ngo_com_grades.gradeComDes,srp_erp_ngo_com_jobcategories.JobCategoryID,srp_erp_ngo_com_jobcategories.JobCatDescription,srp_erp_ngo_com_schools.schoolComID,srp_erp_ngo_com_schools.schoolComDes FROM srp_erp_ngo_com_memjobs INNER JOIN srp_erp_ngo_com_communitymaster on srp_erp_ngo_com_communitymaster.Com_MasterID=srp_erp_ngo_com_memjobs.Com_MasterID  LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster ON srp_erp_statemaster.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_occupationtypes ON srp_erp_ngo_com_occupationtypes.OccTypeID=srp_erp_ngo_com_memjobs.OccTypeID LEFT JOIN srp_erp_ngo_com_grades ON srp_erp_ngo_com_memjobs.gradeComID=srp_erp_ngo_com_grades.gradeComID LEFT JOIN srp_erp_ngo_com_schools ON srp_erp_ngo_com_memjobs.schoolComID=srp_erp_ngo_com_schools.schoolComID LEFT JOIN srp_erp_ngo_com_jobcategories ON srp_erp_ngo_com_jobcategories.JobCategoryID=srp_erp_ngo_com_memjobs.JobCategoryID WHERE srp_erp_ngo_com_memjobs.OccTypeID='" . $MemType . "' AND $where " . $where_clause . " ORDER BY srp_erp_ngo_com_memjobs.Com_MasterID DESC")->result_array();
                }
            }

            $data["type"] = "html";
            echo $html = $this->load->view('system/communityNgo/ajax/load-community-member-status-report', $data, true);
        }
    }

    function get_generalComStatus_report_pdf()
    {
        $date_format_policy = date_format_policy();
        $memberTypes = $this->input->post("memberType");
        $JobCatIds = $this->input->post("JobCatId");
        $schlIds = $this->input->post("schlId");
        $MedumIds = $this->input->post("MedumId");
        $classIds = $this->input->post("classId");
        $text = trim($this->input->post('PlaceDes'));

        if ($memberTypes == '1') {
            $JobCatId = '';
            $schlId = $schlIds;
            $MedumId = $MedumIds;
            $classId = $classIds;
        } else {
            $JobCatId = $JobCatIds;
            $schlId = '';
            $MedumId = '';
            $classId = '';
        }

        $convertFormat = convert_date_format_sql();

        $filter_re = array("AND (srp_erp_ngo_com_memjobs.JobCategoryID=" . $JobCatId . ")" => $JobCatId, "AND (srp_erp_ngo_com_memjobs.schoolComID='" . $schlId . "')" => $schlId, "AND (srp_erp_ngo_com_memjobs.LanguageID='" . $MedumId . "')" => $MedumId, "AND (srp_erp_ngo_com_memjobs.gradeComID='" . $classId . "')" => $classId);
        $set_filter_re = array_filter($filter_re);
        $where_clause = join(" ", array_keys($set_filter_re));

        $srch_string = '';
        if (isset($text) && !empty($text)) {
            if ($memberTypes == '8') {
                $srch_string = " AND ((CName_with_initials Like '%" . $text . "%') OR (MemberCode Like '%" . $text . "%') OR (CNIC_No Like '%" . $text . "%') OR (name Like '%" . $text . "%') OR (EmailID Like '%" . $text . "%') OR (CONCAT(TP_home,TP_Mobile) Like '%" . $text . "%'))";
            } else {
                $srch_string = " AND ((WorkingPlace Like '%" . $text . "%') OR (MemberCode Like '%" . $text . "%') OR (srp_erp_ngo_com_occupationtypes.Description Like '%" . $text . "%') OR (srp_erp_ngo_com_memjobs.DateFrom Like '%" . $text . "%') OR (gradeComDes Like '%" . $text . "%') OR (JobCatDescription Like '%" . $text . "%') OR(Address Like '%" . $text . "%') OR (CName_with_initials Like '%" . $text . "%') OR (CNIC_No Like '%" . $text . "%') OR (name Like '%" . $text . "%') OR (EmailID Like '%" . $text . "%') OR (CONCAT(TP_home,TP_Mobile) Like '%" . $text . "%'))";
            }
        }

        $isActive = " AND srp_erp_ngo_com_communitymaster.isActive = '1' ";
        $comVerifiApproved = " AND srp_erp_ngo_com_communitymaster.comVerifiApproved='1' ";

        $where = "srp_erp_ngo_com_communitymaster.isDeleted = '0' " . $isActive . $comVerifiApproved . $srch_string;

        $queryFP = $this->db->query("SELECT DISTINCT srp_erp_ngo_com_memjobs.Com_MasterID  FROM srp_erp_ngo_com_memjobs");
        $rowFP = $queryFP->result();
        $memInr = array();
        foreach ($rowFP as $resFP) {

            $memInr[] = $resFP->Com_MasterID;
        }

        $in_memPrt = "'" . implode("', '", $memInr) . "'";

        $this->form_validation->set_rules('memberType', 'Occupation Type', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo ' <div class="alert alert-warning" role="alert">
              ' . validation_errors() . '
          </div>';
        } else {

            if (!empty($memberTypes)) {
                if ($memberTypes == '-1') {

                    $data['memReport'] = $this->db->query("SELECT MemberCode,srp_erp_ngo_com_communitymaster.createdUserID,srp_erp_ngo_com_memjobs.gradeComID,srp_erp_ngo_com_memjobs.WorkingPlace,srp_erp_ngo_com_memjobs.Address,DATE_FORMAT(srp_erp_ngo_com_memjobs.DateFrom,'{$convertFormat}') AS DateFrom,srp_erp_ngo_com_memjobs.DateTo,srp_erp_ngo_com_memjobs.MemJobID,srp_erp_ngo_com_memjobs.OccTypeID,srp_erp_ngo_com_memjobs.JobCategoryID,srp_erp_ngo_com_memjobs.isPrimary, CName_with_initials,TP_home,CNIC_No,TP_Mobile,EmailID,C_Address,P_Address,HouseNo,GS_Division,GS_No,srp_erp_ngo_com_occupationtypes.OccTypeID,(srp_erp_ngo_com_occupationtypes.Description) AS OcDescription,srp_erp_gender.genderID,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber,srp_erp_ngo_com_grades.gradeComID,srp_erp_ngo_com_grades.gradeComDes,srp_erp_ngo_com_jobcategories.JobCategoryID,srp_erp_ngo_com_jobcategories.JobCatDescription,srp_erp_ngo_com_schools.schoolComID,srp_erp_ngo_com_schools.schoolComDes FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_erp_ngo_com_memjobs ON srp_erp_ngo_com_communitymaster.Com_MasterID=srp_erp_ngo_com_memjobs.Com_MasterID  LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID  LEFT JOIN srp_erp_statemaster ON srp_erp_statemaster.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_occupationtypes ON srp_erp_ngo_com_occupationtypes.OccTypeID=srp_erp_ngo_com_memjobs.OccTypeID LEFT JOIN srp_erp_ngo_com_grades ON srp_erp_ngo_com_memjobs.gradeComID=srp_erp_ngo_com_grades.gradeComID LEFT JOIN srp_erp_ngo_com_schools ON srp_erp_ngo_com_memjobs.schoolComID=srp_erp_ngo_com_schools.schoolComID LEFT JOIN srp_erp_ngo_com_jobcategories ON srp_erp_ngo_com_jobcategories.JobCategoryID=srp_erp_ngo_com_memjobs.JobCategoryID WHERE $where " . $where_clause . " ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC")->result_array();
                } elseif ($memberTypes == '8') {

                    $data['memReport'] = $this->db->query("SELECT *,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster ON srp_erp_statemaster.stateID = srp_erp_ngo_com_communitymaster.RegionID  WHERE Com_MasterID NOT IN ($in_memPrt) AND $where ORDER BY Com_MasterID DESC ")->result_array();
                } else {
                    $MemType = $memberTypes;

                    $data['memReport'] = $this->db->query("SELECT MemberCode,srp_erp_ngo_com_memjobs.createdUserID,srp_erp_ngo_com_memjobs.OccTypeID,srp_erp_ngo_com_memjobs.gradeComID,srp_erp_ngo_com_memjobs.WorkingPlace,srp_erp_ngo_com_memjobs.Address,DATE_FORMAT(srp_erp_ngo_com_memjobs.DateFrom,'{$convertFormat}') AS DateFrom,srp_erp_ngo_com_memjobs.DateTo,srp_erp_ngo_com_memjobs.MemJobID,srp_erp_ngo_com_memjobs.JobCategoryID,srp_erp_ngo_com_memjobs.isPrimary, CName_with_initials,TP_home,CNIC_No,TP_Mobile,EmailID,C_Address,P_Address,HouseNo,GS_Division,GS_No,srp_erp_ngo_com_occupationtypes.OccTypeID,(srp_erp_ngo_com_occupationtypes.Description) AS OcDescription,srp_erp_gender.genderID,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber,srp_erp_ngo_com_grades.gradeComID,srp_erp_ngo_com_grades.gradeComDes,srp_erp_ngo_com_jobcategories.JobCategoryID,srp_erp_ngo_com_jobcategories.JobCatDescription,srp_erp_ngo_com_schools.schoolComID,srp_erp_ngo_com_schools.schoolComDes FROM srp_erp_ngo_com_memjobs INNER JOIN srp_erp_ngo_com_communitymaster ON srp_erp_ngo_com_communitymaster.Com_MasterID=srp_erp_ngo_com_memjobs.Com_MasterID  LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster ON srp_erp_statemaster.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_occupationtypes ON srp_erp_ngo_com_occupationtypes.OccTypeID=srp_erp_ngo_com_memjobs.OccTypeID LEFT JOIN srp_erp_ngo_com_grades ON srp_erp_ngo_com_memjobs.gradeComID=srp_erp_ngo_com_grades.gradeComID LEFT JOIN srp_erp_ngo_com_schools ON srp_erp_ngo_com_memjobs.schoolComID=srp_erp_ngo_com_schools.schoolComID LEFT JOIN srp_erp_ngo_com_jobcategories ON srp_erp_ngo_com_jobcategories.JobCategoryID=srp_erp_ngo_com_memjobs.JobCategoryID WHERE srp_erp_ngo_com_memjobs.OccTypeID='" . $MemType . "' AND $where " . $where_clause . " ORDER BY srp_erp_ngo_com_memjobs.Com_MasterID DESC")->result_array();
                }
            }

            $data["type"] = "pdf";
            $html = $this->load->view('system/communityNgo/ajax/load-community-member-status-report', $data, true);
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4');
        }
    }

    /*community Qualification report details*/

    function get_generalQualficatn_report()
    {

        $qualMemType = $this->input->post("qualMemType");
        $InstituteId = $this->input->post("InstituteId");

        $text = trim($this->input->post('qualSerch'));

        if ($qualMemType == '-4') {
            $filter_req = array("AND (srp_erp_ngo_com_qualifications.UniversityID='" . $InstituteId . "')" => $InstituteId);
            $set_filter_req = array_filter($filter_req);
            $where_clauseq = join(" ", array_keys($set_filter_req));
        } else {
            $filter_req = array("AND (srp_erp_ngo_com_qualifications.DegreeID=" . $qualMemType . ")" => $qualMemType, "AND (srp_erp_ngo_com_qualifications.UniversityID='" . $InstituteId . "')" => $InstituteId);
            $set_filter_req = array_filter($filter_req);
            $where_clauseq = join(" ", array_keys($set_filter_req));
        }


        $srch_string = '';
        if (isset($text) && !empty($text)) {
            if ($qualMemType == '-5') {
                $srch_string = " AND ((CName_with_initials Like '%" . $text . "%') OR (MemberCode Like '%" . $text . "%') OR (CNIC_No Like '%" . $text . "%') OR (name Like '%" . $text . "%') OR (EmailID Like '%" . $text . "%') OR (CONCAT(TP_home,TP_Mobile) Like '%" . $text . "%'))";
            } else {
                $srch_string = " AND ((Year Like '%" . $text . "%') OR (MemberCode Like '%" . $text . "%') OR (Remarks Like '%" . $text . "%')  OR (UniversityDescription Like '%" . $text . "%') OR (DegreeDescription Like '%" . $text . "%') OR (CName_with_initials Like '%" . $text . "%') OR (CNIC_No Like '%" . $text . "%') OR (name Like '%" . $text . "%') OR (EmailID Like '%" . $text . "%') OR (CONCAT(TP_home,TP_Mobile) Like '%" . $text . "%'))";
            }
        }


        $isActive = " AND srp_erp_ngo_com_communitymaster.isActive = '1' ";
        $comVerifiApproved = " AND srp_erp_ngo_com_communitymaster.comVerifiApproved='1' ";

        $where = "srp_erp_ngo_com_communitymaster.isDeleted = '0'" . $isActive . $comVerifiApproved . $srch_string;

        $queryFP = $this->db->query("SELECT DISTINCT srp_erp_ngo_com_qualifications.Com_MasterID  FROM srp_erp_ngo_com_qualifications");
        $rowFP = $queryFP->result();
        $memInr = array();
        foreach ($rowFP as $resFP) {

            $memInr[] = $resFP->Com_MasterID;
        }

        $in_memPrt = "'" . implode("', '", $memInr) . "'";

        $this->form_validation->set_rules('qualMemType', 'Qualification', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo ' <div class="alert alert-warning" role="alert">
              ' . validation_errors() . '
          </div>';
        } else {


            if (!empty($qualMemType)) {
                if ($qualMemType == '-4') {

                    $data['qualReport'] = $this->db->query("SELECT *,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber,srp_erp_ngo_com_qualifications.Com_MasterID,srp_erp_ngo_com_qualifications.UniversityID,srp_erp_ngo_com_qualifications.DegreeID,srp_erp_ngo_com_qualifications.CurrentlyReading,srp_erp_ngo_com_qualifications.Year,srp_erp_ngo_com_qualifications.Remarks,srp_erp_ngo_com_universities.UniversityID,srp_erp_ngo_com_universities.UniversityDescription,srp_erp_ngo_com_degreecategories.DegreeID,srp_erp_ngo_com_degreecategories.DegreeDescription FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster ON srp_erp_statemaster.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_qualifications ON srp_erp_ngo_com_communitymaster.Com_MasterID=srp_erp_ngo_com_qualifications.Com_MasterID LEFT JOIN srp_erp_ngo_com_universities ON srp_erp_ngo_com_qualifications.UniversityID=srp_erp_ngo_com_universities.UniversityID LEFT JOIN srp_erp_ngo_com_degreecategories ON srp_erp_ngo_com_degreecategories.DegreeID=srp_erp_ngo_com_qualifications.DegreeID WHERE $where " . $where_clauseq . " ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
                } elseif ($qualMemType == '-5') {

                    $data['qualReport'] = $this->db->query("SELECT *,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster ON srp_erp_statemaster.stateID = srp_erp_ngo_com_communitymaster.RegionID WHERE Com_MasterID NOT IN ($in_memPrt) AND $where ORDER BY Com_MasterID DESC ")->result_array();
                } else {
                    $qualType = $qualMemType;

                    $data['qualReport'] = $this->db->query("SELECT srp_erp_ngo_com_qualifications.createdUserID,srp_erp_ngo_com_qualifications.UniversityID,srp_erp_ngo_com_qualifications.DegreeID,srp_erp_ngo_com_qualifications.CurrentlyReading,srp_erp_ngo_com_qualifications.Year,srp_erp_ngo_com_qualifications.Remarks,MemberCode,CName_with_initials,TP_home,CNIC_No,TP_Mobile,EmailID,C_Address,P_Address,HouseNo,GS_Division,GS_No,srp_erp_gender.genderID,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber,srp_erp_ngo_com_universities.UniversityID,srp_erp_ngo_com_universities.UniversityDescription,srp_erp_ngo_com_degreecategories.DegreeID,srp_erp_ngo_com_degreecategories.DegreeDescription FROM srp_erp_ngo_com_qualifications INNER JOIN srp_erp_ngo_com_communitymaster on srp_erp_ngo_com_communitymaster.Com_MasterID=srp_erp_ngo_com_qualifications.Com_MasterID  LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster ON srp_erp_statemaster.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_universities ON srp_erp_ngo_com_qualifications.UniversityID=srp_erp_ngo_com_universities.UniversityID LEFT JOIN srp_erp_ngo_com_degreecategories ON srp_erp_ngo_com_degreecategories.DegreeID=srp_erp_ngo_com_qualifications.DegreeID WHERE srp_erp_ngo_com_qualifications.DegreeID='" . $qualType . "' AND $where " . $where_clauseq . " ORDER BY srp_erp_ngo_com_qualifications.Com_MasterID DESC")->result_array();
                }
            }

            $data["type"] = "html";
            echo $html = $this->load->view('system/communityNgo/ajax/load_community_qualification_report', $data, true);
        }
    }

    function get_generalQualficatn_report_pdf()
    {

        $qualMemType = $this->input->post("qualMemType");
        $InstituteId = $this->input->post("InstituteId");

        $text = trim($this->input->post('qualSerch'));


        if ($qualMemType == '-4') {
            $filter_req = array("AND (srp_erp_ngo_com_qualifications.UniversityID='" . $InstituteId . "')" => $InstituteId);
            $set_filter_req = array_filter($filter_req);
            $where_clauseq = join(" ", array_keys($set_filter_req));
        } else {
            $filter_req = array("AND (srp_erp_ngo_com_qualifications.DegreeID=" . $qualMemType . ")" => $qualMemType, "AND (srp_erp_ngo_com_qualifications.UniversityID='" . $InstituteId . "')" => $InstituteId);
            $set_filter_req = array_filter($filter_req);
            $where_clauseq = join(" ", array_keys($set_filter_req));
        }

        $srch_string = '';
        if (isset($text) && !empty($text)) {
            if ($qualMemType == '-5') {
                $srch_string = " AND ((CName_with_initials Like '%" . $text . "%') OR (MemberCode Like '%" . $text . "%') OR (CNIC_No Like '%" . $text . "%') OR (name Like '%" . $text . "%') OR (EmailID Like '%" . $text . "%') OR (CONCAT(TP_home,TP_Mobile) Like '%" . $text . "%'))";
            } else {
                $srch_string = " AND ((Year Like '%" . $text . "%') OR (MemberCode Like '%" . $text . "%') OR (Remarks Like '%" . $text . "%')  OR (UniversityDescription Like '%" . $text . "%') OR (DegreeDescription Like '%" . $text . "%') OR (CName_with_initials Like '%" . $text . "%') OR (CNIC_No Like '%" . $text . "%') OR (name Like '%" . $text . "%') OR (EmailID Like '%" . $text . "%') OR (CONCAT(TP_home,TP_Mobile) Like '%" . $text . "%'))";
            }
        }

        $isActive = " AND srp_erp_ngo_com_communitymaster.isActive = '1' ";
        $comVerifiApproved = " AND srp_erp_ngo_com_communitymaster.comVerifiApproved='1' ";

        $where = "srp_erp_ngo_com_communitymaster.isDeleted = '0'" . $isActive . $comVerifiApproved . $srch_string;

        $queryFP = $this->db->query("SELECT DISTINCT srp_erp_ngo_com_qualifications.Com_MasterID  FROM srp_erp_ngo_com_qualifications");
        $rowFP = $queryFP->result();
        $memInr = array();
        foreach ($rowFP as $resFP) {

            $memInr[] = $resFP->Com_MasterID;
        }

        $in_memPrt = "'" . implode("', '", $memInr) . "'";

        $this->form_validation->set_rules('qualMemType', 'Qualification', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo ' <div class="alert alert-warning" role="alert">
              ' . validation_errors() . '
          </div>';
        } else {


            if (!empty($qualMemType)) {
                if ($qualMemType == '-4') {

                    $data['qualReport'] = $this->db->query("SELECT *,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber,srp_erp_ngo_com_qualifications.Com_MasterID,srp_erp_ngo_com_qualifications.UniversityID,srp_erp_ngo_com_qualifications.DegreeID,srp_erp_ngo_com_qualifications.CurrentlyReading,srp_erp_ngo_com_qualifications.Year,srp_erp_ngo_com_qualifications.Remarks,srp_erp_ngo_com_universities.UniversityID,srp_erp_ngo_com_universities.UniversityDescription,srp_erp_ngo_com_degreecategories.DegreeID,srp_erp_ngo_com_degreecategories.DegreeDescription FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster ON srp_erp_statemaster.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_qualifications ON srp_erp_ngo_com_communitymaster.Com_MasterID=srp_erp_ngo_com_qualifications.Com_MasterID LEFT JOIN srp_erp_ngo_com_universities ON srp_erp_ngo_com_qualifications.UniversityID=srp_erp_ngo_com_universities.UniversityID LEFT JOIN srp_erp_ngo_com_degreecategories ON srp_erp_ngo_com_degreecategories.DegreeID=srp_erp_ngo_com_qualifications.DegreeID WHERE $where " . $where_clauseq . " ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
                } elseif ($qualMemType == '-5') {

                    $data['qualReport'] = $this->db->query("SELECT *,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster ON srp_erp_statemaster.stateID = srp_erp_ngo_com_communitymaster.RegionID WHERE Com_MasterID NOT IN ($in_memPrt) AND $where ORDER BY Com_MasterID DESC ")->result_array();
                } else {
                    $qualType = $qualMemType;

                    $data['qualReport'] = $this->db->query("SELECT srp_erp_ngo_com_qualifications.createdUserID,srp_erp_ngo_com_qualifications.UniversityID,srp_erp_ngo_com_qualifications.DegreeID,srp_erp_ngo_com_qualifications.CurrentlyReading,srp_erp_ngo_com_qualifications.Year,srp_erp_ngo_com_qualifications.Remarks,MemberCode,CName_with_initials,TP_home,CNIC_No,TP_Mobile,EmailID,C_Address,P_Address,HouseNo,GS_Division,GS_No,srp_erp_gender.genderID,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber,srp_erp_ngo_com_universities.UniversityID,srp_erp_ngo_com_universities.UniversityDescription,srp_erp_ngo_com_degreecategories.DegreeID,srp_erp_ngo_com_degreecategories.DegreeDescription FROM srp_erp_ngo_com_qualifications INNER JOIN srp_erp_ngo_com_communitymaster on srp_erp_ngo_com_communitymaster.Com_MasterID=srp_erp_ngo_com_qualifications.Com_MasterID  LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster ON srp_erp_statemaster.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_universities ON srp_erp_ngo_com_qualifications.UniversityID=srp_erp_ngo_com_universities.UniversityID LEFT JOIN srp_erp_ngo_com_degreecategories ON srp_erp_ngo_com_degreecategories.DegreeID=srp_erp_ngo_com_qualifications.DegreeID WHERE srp_erp_ngo_com_qualifications.DegreeID='" . $qualType . "' AND $where " . $where_clauseq . " ORDER BY srp_erp_ngo_com_qualifications.Com_MasterID DESC")->result_array();
                }
            }

            $data["type"] = "pdf";
            $html = $this->load->view('system/communityNgo/ajax/load_community_qualification_report', $data, true);
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4');
        }
    }
    /*community general MEMBER report details*/

    function get_geralaMember_delReport()
    {
        $date_format_policy = date_format_policy();

        $genType = $this->input->post("genType");
        $IsAbroad = $this->input->post("IsAbroad");
        $isConvert = $this->input->post("isConvertId");
        $stateId = $this->input->post("stateId");

        $ageOps = $this->input->post("ageOps");
        $ageFrm = trim($this->input->post('ageFrm'));
        $ageTo = trim($this->input->post('ageTo'));

        $text = $this->input->post("PlaceComDes");
        $bloodGrpID = $this->input->post("bloodGrpID");
        $sicknessID = $this->input->post("sicknessID");

        $BloodGroupID = "";
        if (!empty($bloodGrpID)) {
            $BloodGroupID = "AND srp_erp_ngo_com_communitymaster.BloodGroupID IN(" . join(',', $bloodGrpID) . ")";
        }

        $sickAutoID = "";
        if (!empty($sicknessID)) {
            $sickAutoID = "AND sicknessr.sickAutoID IN(" . join(',', $sicknessID) . ")";
        }

        $srch_string = '';
        if (isset($text) && !empty($text)) {

            $srch_string = " AND ((MemberCode Like '%" . $text . "%') OR (areac.Description Like '%" . $text . "%') OR (divisionc.Description Like '%" . $text . "%') OR (familyr.FamilySystemCode Like '%" . $text . "%') OR (CDOB Like '%" . $text . "%') OR (CName_with_initials Like '%" . $text . "%') OR (CNIC_No Like '%" . $text . "%') OR (name Like '%" . $text . "%') OR (EmailID Like '%" . $text . "%') OR (CONCAT(TP_home,TP_Mobile) Like '%" . $text . "%'))";
        }

        if ($ageOps == '1') {
            $ageOppId = '=';
        } elseif ($ageOps == '2') {
            $ageOppId = '<';
        } elseif ($ageOps == '3') {
            $ageOppId = '>';
        } elseif ($ageOps == '4') {
            $ageOppId = '<=';
        } elseif ($ageOps == '5') {
            $ageOppId = '>=';
        } elseif ($ageOps == '6') {
            $ageOppId = '';
        }

        $convertFormat = convert_date_format_sql();

        $srch_age = '';
        if (isset($ageFrm) && !empty($ageFrm)) {
            if ($ageOps == '6') {
                $srch_age = " AND (trim(Age) BETWEEN " . $ageFrm . " AND " . $ageTo . ")";
            } else {
                $srch_age = " AND (trim(Age)" . $ageOppId . "" . $ageFrm . ")";
            }
        }

        $isActive = " AND srp_erp_ngo_com_communitymaster.isActive = '1' ";
        $comVerifiApproved = " AND srp_erp_ngo_com_communitymaster.comVerifiApproved='1' ";
        $isMove = " AND srp_erp_ngo_com_familydetails.isMove = '0' ";

        $where = "srp_erp_ngo_com_communitymaster.isDeleted ='0' " . $isActive . $comVerifiApproved . $srch_age . $srch_string;

        if ($genType != '-9' && ($IsAbroad == '-7' || $IsAbroad == '') && ($isConvert == '-8' || $isConvert == '') && ($stateId == '-6' || $stateId == '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.GenderID='" . $genType . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType == '-9' && ($IsAbroad == '-7' || $IsAbroad == '') && ($isConvert == '-8' || $isConvert == '') && ($stateId == '-6' || $stateId == '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType == '-9' && ($isConvert == '-8' || $isConvert == '') && ($stateId == '-6' || $stateId == '') && ($IsAbroad != '-7' || $IsAbroad != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.IsAbroad='" . $IsAbroad . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType == '-9' && ($IsAbroad == '-7' || $IsAbroad == '') && ($stateId == '-6' || $stateId == '') && ($isConvert != '-8' || $isConvert != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.isConverted='" . $isConvert . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType == '-9' && ($IsAbroad == '-7' || $IsAbroad == '') && ($isConvert == '-8' || $isConvert == '') && ($stateId != '-6' || $stateId != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.CurrentStatus='" . $stateId . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType == '-9' && ($stateId == '-6' || $stateId == '') && ($IsAbroad != '-7' || $IsAbroad != '') && ($isConvert != '-8' || $isConvert != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.IsAbroad='" . $IsAbroad . "' AND srp_erp_ngo_com_communitymaster.isConverted='" . $isConvert . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType == '-9' && ($isConvert == '-8' || $isConvert == '') && ($IsAbroad != '-7' || $IsAbroad != '') && ($stateId != '-6' || $stateId != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.IsAbroad='" . $IsAbroad . "' AND srp_erp_ngo_com_communitymaster.CurrentStatus='" . $stateId . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType == '-9' && ($IsAbroad == '-7' || $IsAbroad == '') && ($isConvert != '-8' || $isConvert != '') && ($stateId != '-6' || $stateId != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.isConverted='" . $isConvert . "' AND srp_erp_ngo_com_communitymaster.CurrentStatus='" . $stateId . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType == '-9' && ($IsAbroad != '-7' || $IsAbroad != '') && ($isConvert != '-8' || $isConvert != '') && ($stateId != '-6' || $stateId != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.IsAbroad='" . $IsAbroad . "' AND srp_erp_ngo_com_communitymaster.isConverted='" . $isConvert . "' AND srp_erp_ngo_com_communitymaster.CurrentStatus='" . $stateId . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if (($isConvert == '-8' || $isConvert == '') && ($stateId == '-6' || $stateId == '') && $genType != '-9' && ($IsAbroad != '-7' || $IsAbroad != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.GenderID='" . $genType . "' AND srp_erp_ngo_com_communitymaster.IsAbroad='" . $IsAbroad . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if (($IsAbroad == '-7' || $IsAbroad == '') && ($isConvert == '-8' || $isConvert == '') && $genType != '-9' && ($stateId != '-6' || $stateId != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.GenderID='" . $genType . "' AND srp_erp_ngo_com_communitymaster.CurrentStatus='" . $stateId . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if (($IsAbroad == '-7' || $IsAbroad == '') && $genType != '-9' && ($isConvert != '-8' || $isConvert != '') && ($stateId != '-6' || $stateId != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.GenderID='" . $genType . "' AND srp_erp_ngo_com_communitymaster.isConverted='" . $isConvert . "' AND srp_erp_ngo_com_communitymaster.CurrentStatus='" . $stateId . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if (($IsAbroad == '-7' || $IsAbroad == '') && ($stateId == '-6' || $stateId == '') && $genType != '-9' && ($isConvert != '-8' || $isConvert != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.GenderID='" . $genType . "' AND srp_erp_ngo_com_communitymaster.isConverted='" . $isConvert . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if (($stateId == '-6' || $stateId == '') && $genType != '-9' && ($IsAbroad != '-7' || $IsAbroad != '') && ($isConvert != '-8' || $isConvert != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.GenderID='" . $genType . "' AND srp_erp_ngo_com_communitymaster.IsAbroad='" . $IsAbroad . "' AND srp_erp_ngo_com_communitymaster.isConverted='" . $isConvert . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if (($isConvert == '-8' || $isConvert == '') && $genType != '-9' && ($IsAbroad != '-7' || $IsAbroad != '') && ($stateId != '-6' || $stateId != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.GenderID='" . $genType . "' AND srp_erp_ngo_com_communitymaster.IsAbroad='" . $IsAbroad . "' AND srp_erp_ngo_com_communitymaster.CurrentStatus='" . $stateId . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType != '-9' && ($stateId != '-6' || $stateId != '') && ($IsAbroad != '-7' || $IsAbroad != '') && ($isConvert != '-8' || $isConvert != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.GenderID='" . $genType . "' AND srp_erp_ngo_com_communitymaster.IsAbroad='" . $IsAbroad . "' AND srp_erp_ngo_com_communitymaster.isConverted='" . $isConvert . "' AND srp_erp_ngo_com_communitymaster.CurrentStatus='" . $stateId . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType != '-9' && ($IsAbroad != '-7' || $IsAbroad != '') && ($isConvert != '-8' || $isConvert != '') && ($stateId != '-6' || $stateId != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.GenderID='" . $genType . "' AND srp_erp_ngo_com_communitymaster.IsAbroad='" . $IsAbroad . "' AND srp_erp_ngo_com_communitymaster.isConverted='" . $isConvert . "' AND srp_erp_ngo_com_communitymaster.CurrentStatus='" . $stateId . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else {
            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        }

        $data["type"] = "html";
        echo $html = $this->load->view('system/communityNgo/ajax/load_community_other_report', $data, true);
    }

    function get_geralaMember_delReport_pdf()
    {
        $date_format_policy = date_format_policy();

        $genType = $this->input->post("genType");
        $IsAbroad = $this->input->post("IsAbroad");
        $isConvert = $this->input->post("isConvertId");
        $stateId = $this->input->post("stateId");

        $ageOps = $this->input->post("ageOps");
        $ageFrm = trim($this->input->post('ageFrm'));
        $ageTo = trim($this->input->post('ageTo'));
        $text = $this->input->post("PlaceComDes");

        if ($ageOps == '1') {
            $ageOppId = '=';
        } elseif ($ageOps == '2') {
            $ageOppId = '<';
        } elseif ($ageOps == '3') {
            $ageOppId = '>';
        } elseif ($ageOps == '4') {
            $ageOppId = '<=';
        } elseif ($ageOps == '5') {
            $ageOppId = '>=';
        } elseif ($ageOps == '6') {
            $ageOppId = '';
        }


        $bloodGrpID = $this->input->post("bloodGrpID");
        $sicknessID = $this->input->post("sicknessID");

        $BloodGroupID = "";
        if (!empty($bloodGrpID)) {
            $BloodGroupID = "AND srp_erp_ngo_com_communitymaster.BloodGroupID IN(" . join(',', $bloodGrpID) . ")";
        }

        $sickAutoID = "";
        if (!empty($sicknessID)) {
            $sickAutoID = "AND sicknessr.sickAutoID IN(" . join(',', $sicknessID) . ")";
        }

        $srch_string = '';
        if (isset($text) && !empty($text)) {

            $srch_string = " AND ((MemberCode Like '%" . $text . "%') OR (areac.Description Like '%" . $text . "%') OR (divisionc.Description Like '%" . $text . "%') OR (familyr.FamilySystemCode Like '%" . $text . "%') OR (CDOB Like '%" . $text . "%') OR (CName_with_initials Like '%" . $text . "%') OR (CNIC_No Like '%" . $text . "%') OR (name Like '%" . $text . "%') OR (EmailID Like '%" . $text . "%') OR (CONCAT(TP_home,TP_Mobile) Like '%" . $text . "%'))";
        }

        $convertFormat = convert_date_format_sql();

        $srch_age = '';
        if (isset($ageFrm) && !empty($ageFrm)) {
            if ($ageOps == '6') {
                $srch_age = " AND (trim(Age) BETWEEN " . $ageFrm . " AND " . $ageTo . ")";
            } else {
                $srch_age = " AND (trim(Age)" . $ageOppId . "" . $ageFrm . ")";
            }
        }

        $isActive = " AND srp_erp_ngo_com_communitymaster.isActive = '1' ";
        $comVerifiApproved = " AND srp_erp_ngo_com_communitymaster.comVerifiApproved = '1' ";
        $isMove = " AND srp_erp_ngo_com_familydetails.isMove = '0' ";

        $where = "srp_erp_ngo_com_communitymaster.isDeleted ='0'" . $isActive . $comVerifiApproved . $srch_age . $srch_string;

        if ($genType != '-9' && ($IsAbroad == '-7' || $IsAbroad == '') && ($isConvert == '-8' || $isConvert == '') && ($stateId == '-6' || $stateId == '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.GenderID='" . $genType . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType == '-9' && ($IsAbroad == '-7' || $IsAbroad == '') && ($isConvert == '-8' || $isConvert == '') && ($stateId == '-6' || $stateId == '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType == '-9' && ($isConvert == '-8' || $isConvert == '') && ($stateId == '-6' || $stateId == '') && ($IsAbroad != '-7' || $IsAbroad != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.IsAbroad='" . $IsAbroad . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType == '-9' && ($IsAbroad == '-7' || $IsAbroad == '') && ($stateId == '-6' || $stateId == '') && ($isConvert != '-8' || $isConvert != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.isConverted='" . $isConvert . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType == '-9' && ($IsAbroad == '-7' || $IsAbroad == '') && ($isConvert == '-8' || $isConvert == '') && ($stateId != '-6' || $stateId != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.CurrentStatus='" . $stateId . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType == '-9' && ($stateId == '-6' || $stateId == '') && ($IsAbroad != '-7' || $IsAbroad != '') && ($isConvert != '-8' || $isConvert != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.IsAbroad='" . $IsAbroad . "' AND srp_erp_ngo_com_communitymaster.isConverted='" . $isConvert . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType == '-9' && ($isConvert == '-8' || $isConvert == '') && ($IsAbroad != '-7' || $IsAbroad != '') && ($stateId != '-6' || $stateId != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.IsAbroad='" . $IsAbroad . "' AND srp_erp_ngo_com_communitymaster.CurrentStatus='" . $stateId . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType == '-9' && ($IsAbroad == '-7' || $IsAbroad == '') && ($isConvert != '-8' || $isConvert != '') && ($stateId != '-6' || $stateId != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.isConverted='" . $isConvert . "' AND srp_erp_ngo_com_communitymaster.CurrentStatus='" . $stateId . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType == '-9' && ($IsAbroad != '-7' || $IsAbroad != '') && ($isConvert != '-8' || $isConvert != '') && ($stateId != '-6' || $stateId != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.IsAbroad='" . $IsAbroad . "' AND srp_erp_ngo_com_communitymaster.isConverted='" . $isConvert . "' AND srp_erp_ngo_com_communitymaster.CurrentStatus='" . $stateId . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if (($isConvert == '-8' || $isConvert == '') && ($stateId == '-6' || $stateId == '') && $genType != '-9' && ($IsAbroad != '-7' || $IsAbroad != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.GenderID='" . $genType . "' AND srp_erp_ngo_com_communitymaster.IsAbroad='" . $IsAbroad . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if (($IsAbroad == '-7' || $IsAbroad == '') && ($isConvert == '-8' || $isConvert == '') && $genType != '-9' && ($stateId != '-6' || $stateId != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.GenderID='" . $genType . "' AND srp_erp_ngo_com_communitymaster.CurrentStatus='" . $stateId . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if (($IsAbroad == '-7' || $IsAbroad == '') && $genType != '-9' && ($isConvert != '-8' || $isConvert != '') && ($stateId != '-6' || $stateId != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.GenderID='" . $genType . "' AND srp_erp_ngo_com_communitymaster.isConverted='" . $isConvert . "' AND srp_erp_ngo_com_communitymaster.CurrentStatus='" . $stateId . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if (($IsAbroad == '-7' || $IsAbroad == '') && ($stateId == '-6' || $stateId == '') && $genType != '-9' && ($isConvert != '-8' || $isConvert != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.GenderID='" . $genType . "' AND srp_erp_ngo_com_communitymaster.isConverted='" . $isConvert . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if (($stateId == '-6' || $stateId == '') && $genType != '-9' && ($IsAbroad != '-7' || $IsAbroad != '') && ($isConvert != '-8' || $isConvert != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.GenderID='" . $genType . "' AND srp_erp_ngo_com_communitymaster.IsAbroad='" . $IsAbroad . "' AND srp_erp_ngo_com_communitymaster.isConverted='" . $isConvert . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if (($isConvert == '-8' || $isConvert == '') && $genType != '-9' && ($IsAbroad != '-7' || $IsAbroad != '') && ($stateId != '-6' || $stateId != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.GenderID='" . $genType . "' AND srp_erp_ngo_com_communitymaster.IsAbroad='" . $IsAbroad . "' AND srp_erp_ngo_com_communitymaster.CurrentStatus='" . $stateId . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType != '-9' && ($stateId != '-6' || $stateId != '') && ($IsAbroad != '-7' || $IsAbroad != '') && ($isConvert != '-8' || $isConvert != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.GenderID='" . $genType . "' AND srp_erp_ngo_com_communitymaster.IsAbroad='" . $IsAbroad . "' AND srp_erp_ngo_com_communitymaster.isConverted='" . $isConvert . "' AND srp_erp_ngo_com_communitymaster.CurrentStatus='" . $stateId . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else if ($genType != '-9' && ($IsAbroad != '-7' || $IsAbroad != '') && ($isConvert != '-8' || $isConvert != '') && ($stateId != '-6' || $stateId != '')) {

            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE srp_erp_ngo_com_communitymaster.GenderID='" . $genType . "' AND srp_erp_ngo_com_communitymaster.IsAbroad='" . $IsAbroad . "' AND srp_erp_ngo_com_communitymaster.isConverted='" . $isConvert . "' AND srp_erp_ngo_com_communitymaster.CurrentStatus='" . $stateId . "' AND $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        } else {
            $data['commReport'] = $this->db->query("SELECT MemberCode,CName_with_initials,CNIC_No,srp_erp_gender.name AS Gender,TP_Mobile,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB,areac.Description AS Region,divisionc.stateID,divisionc.Description AS divDescription,familyr.LeaderID,familyr.FamilySystemCode,familyr.FamMasterID,familyr.FamilyName,familDel.isMove,srp_erp_ngo_com_permanent_sickness.sickAutoID,srp_erp_ngo_com_permanent_sickness.sickDescription,srp_erp_ngo_com_maritalstatus.maritalstatus FROM srp_erp_ngo_com_communitymaster LEFT JOIN srp_titlemaster ON srp_titlemaster.TitleID = srp_erp_ngo_com_communitymaster.TitleID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster divisionc ON divisionc.stateID= srp_erp_ngo_com_communitymaster.GS_Division LEFT JOIN srp_erp_statemaster areac ON areac.stateID = srp_erp_ngo_com_communitymaster.RegionID LEFT JOIN srp_erp_ngo_com_familydetails familDel ON familDel.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_familymaster familyr ON  familyr.FamMasterID =familDel.FamMasterID LEFT JOIN srp_erp_ngo_com_memberpersickness sicknessr ON sicknessr.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID LEFT JOIN srp_erp_ngo_com_permanent_sickness ON srp_erp_ngo_com_permanent_sickness.sickAutoID=sicknessr.sickAutoID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE $where $BloodGroupID $sickAutoID ORDER BY srp_erp_ngo_com_communitymaster.Com_MasterID DESC ")->result_array();
        }

        $data["type"] = "pdf";
        $html = $this->load->view('system/communityNgo/ajax/load_community_other_report', $data, true);
        $this->load->library('pdf');
        $pdf = $this->pdf->printed($html, 'A4');
    }


    function fetchGen_province_districtDropdown()
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

    function fetchGen_district_districtDivisionDropdown()
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
                    //  echo '<option value="' . trim($row['stateID']) . '">' . trim($row['Description']) . '</option>';
                }
            }
        }
    }


    //start of helping del
    /*community Help Requirements report details*/

    function get_generalMemHelpRq_report()
    {

        $helpRqType = $this->input->post("helpRqType");
        $helpDelIds = $this->input->post("helpDelIds");

        $text = trim($this->input->post('helpRqSerch'));

        if ($helpRqType == 1) {
            $helpRqTypes = 'GOV';
        } elseif ($helpRqType == 2) {
            $helpRqTypes = 'PVT';
        } elseif ($helpRqType == 3) {
            $helpRqTypes = 'CONS';
        } elseif ($helpRqType == 4) {
            $helpRqTypes = 'OTHER';
        } else {
            $helpRqTypes = '';
        }

        $filter_req = array("AND (srp_erp_ngo_com_helprequirements.helpRequireType='" . $helpRqTypes . "')" => $helpRqTypes, "AND (srp_erp_ngo_com_helprequirements.helpRequireID='" . $helpDelIds . "')" => $helpDelIds);
        $set_filter_req = array_filter($filter_req);
        $where_clauseq = join(" ", array_keys($set_filter_req));

        $srch_string = '';
        if (isset($text) && !empty($text)) {

            $srch_string = " AND ((helpRequireType Like '%" . $text . "%') OR (helpRequireDesc Like '%" . $text . "%') OR (CName_with_initials Like '%" . $text . "%') OR (CNIC_No Like '%" . $text . "%') OR (name Like '%" . $text . "%') OR (EmailID Like '%" . $text . "%') OR (CONCAT(TP_home,TP_Mobile) Like '%" . $text . "%'))";
        }

        $isActive = " AND srp_erp_ngo_com_communitymaster.isActive = '1' ";
        $comVerifiApproved = " AND srp_erp_ngo_com_communitymaster.comVerifiApproved = '1' ";

        $where = "srp_erp_ngo_com_communitymaster.isDeleted = '0'" . $isActive . $comVerifiApproved . $srch_string;

        $queryFP = $this->db->query("SELECT DISTINCT srp_erp_ngo_com_memberhelprequirements.Com_MasterID  FROM srp_erp_ngo_com_memberhelprequirements ");
        $rowFP = $queryFP->result();
        $memInr = array();
        foreach ($rowFP as $resFP) {

            $memInr[] = $resFP->Com_MasterID;
        }

        // $in_memPrt = "'" . implode("', '", $memInr) . "'";

        $this->form_validation->set_rules('helpRqType', 'Requirements Type', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo ' <div class="alert alert-warning" role="alert">
                ' . validation_errors() . '
            </div>';
        } else {


            $data['helpReqReport'] = $this->db->query("SELECT MemberCode,srp_erp_ngo_com_helprequirements.helpRequireType,srp_erp_ngo_com_memberhelprequirements.createdUserID,srp_erp_ngo_com_memberhelprequirements.helpRequireID,srp_erp_ngo_com_memberhelprequirements.hlprDescription,CName_with_initials,TP_home,CNIC_No,TP_Mobile,EmailID,C_Address,P_Address,HouseNo,GS_Division,GS_No,srp_erp_gender.genderID,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber,srp_erp_ngo_com_helprequirements.helpRequireDesc FROM srp_erp_ngo_com_memberhelprequirements INNER JOIN srp_erp_ngo_com_communitymaster on srp_erp_ngo_com_communitymaster.Com_MasterID=srp_erp_ngo_com_memberhelprequirements.Com_MasterID INNER JOIN srp_erp_ngo_com_helprequirements ON srp_erp_ngo_com_helprequirements.helpRequireID=srp_erp_ngo_com_memberhelprequirements.helpRequireID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster ON srp_erp_statemaster.stateID = srp_erp_ngo_com_communitymaster.RegionID WHERE $where " . $where_clauseq . " ORDER BY srp_erp_ngo_com_memberhelprequirements.Com_MasterID DESC")->result_array();

            $data["type"] = "html";
            echo $html = $this->load->view('system/communityNgo/ajax/load_comMem_helpRequirements_report', $data, true);
        }
    }

    function get_generalMemHelpRq_report_pdf()
    {
        $helpRqType = $this->input->post("helpRqType");
        $helpDelIds = $this->input->post("helpDelIds");

        $text = trim($this->input->post('helpRqSerch'));

        if ($helpRqType == 1) {
            $helpRqTypes = 'GOV';
        } elseif ($helpRqType == 2) {
            $helpRqTypes = 'PVT';
        } elseif ($helpRqType == 3) {
            $helpRqTypes = 'CONS';
        } elseif ($helpRqType == 4) {
            $helpRqTypes = 'OTHER';
        } else {
            $helpRqTypes = '';
        }

        $filter_req = array("AND (srp_erp_ngo_com_helprequirements.helpRequireType='" . $helpRqTypes . "')" => $helpRqTypes, "AND (srp_erp_ngo_com_helprequirements.helpRequireID='" . $helpDelIds . "')" => $helpDelIds);
        $set_filter_req = array_filter($filter_req);
        $where_clauseq = join(" ", array_keys($set_filter_req));

        $srch_string = '';
        if (isset($text) && !empty($text)) {

            $srch_string = " AND ((helpRequireType Like '%" . $text . "%') OR (helpRequireDesc Like '%" . $text . "%') OR (CName_with_initials Like '%" . $text . "%') OR (CNIC_No Like '%" . $text . "%') OR (name Like '%" . $text . "%') OR (EmailID Like '%" . $text . "%') OR (CONCAT(TP_home,TP_Mobile) Like '%" . $text . "%'))";
        }

        $isActive = " AND srp_erp_ngo_com_communitymaster.isActive = '1' ";
        $comVerifiApproved = " AND srp_erp_ngo_com_communitymaster.comVerifiApproved = '1' ";

        $where = "srp_erp_ngo_com_communitymaster.isDeleted = '0'" . $isActive . $comVerifiApproved . $srch_string;

        $queryFP = $this->db->query("SELECT DISTINCT srp_erp_ngo_com_memberhelprequirements.Com_MasterID  FROM srp_erp_ngo_com_memberhelprequirements ");
        $rowFP = $queryFP->result();
        $memInr = array();
        foreach ($rowFP as $resFP) {

            $memInr[] = $resFP->Com_MasterID;
        }

        // $in_memPrt = "'" . implode("', '", $memInr) . "'";

        $this->form_validation->set_rules('helpRqType', 'Requirements Type', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo ' <div class="alert alert-warning" role="alert">
                ' . validation_errors() . '
            </div>';
        } else {

            $data['helpReqReport'] = $this->db->query("SELECT MemberCode,srp_erp_ngo_com_helprequirements.helpRequireType,srp_erp_ngo_com_memberhelprequirements.createdUserID,srp_erp_ngo_com_memberhelprequirements.helpRequireID,srp_erp_ngo_com_memberhelprequirements.hlprDescription,CName_with_initials,TP_home,CNIC_No,TP_Mobile,EmailID,C_Address,P_Address,HouseNo,GS_Division,GS_No,srp_erp_gender.genderID,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber,srp_erp_ngo_com_helprequirements.helpRequireDesc FROM srp_erp_ngo_com_memberhelprequirements INNER JOIN srp_erp_ngo_com_communitymaster on srp_erp_ngo_com_communitymaster.Com_MasterID=srp_erp_ngo_com_memberhelprequirements.Com_MasterID INNER JOIN srp_erp_ngo_com_helprequirements ON srp_erp_ngo_com_helprequirements.helpRequireID=srp_erp_ngo_com_memberhelprequirements.helpRequireID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster ON srp_erp_statemaster.stateID = srp_erp_ngo_com_communitymaster.RegionID WHERE $where " . $where_clauseq . " ORDER BY srp_erp_ngo_com_memberhelprequirements.Com_MasterID DESC")->result_array();

            $data["type"] = "pdf";
            $html = $this->load->view('system/communityNgo/ajax/load_comMem_helpRequirements_report', $data, true);
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4');
        }
    }

    /*community willing to help report details*/

    function get_generalWillingToHelp_report()
    {

        $helpCategoryID = $this->input->post("helpCategoryID");

        if ($helpCategoryID == -2) {
            $helpCategoryIDs = '';
        } else {
            $helpCategoryIDs = $helpCategoryID;
        }

        $text = trim($this->input->post('helpWillSerch'));


        $filter_req = array("AND (srp_erp_ngo_com_helpcategories.helpCategoryID='" . $helpCategoryIDs . "')" => $helpCategoryIDs);
        $set_filter_req = array_filter($filter_req);
        $where_clauseq = join(" ", array_keys($set_filter_req));

        $srch_string = '';
        if (isset($text) && !empty($text)) {

            $srch_string = " AND ((srp_erp_ngo_com_memberwillingtohelp.helpCategoryID Like '%" . $text . "%') OR (helpCategoryDes Like '%" . $text . "%') OR (CName_with_initials Like '%" . $text . "%') OR (CNIC_No Like '%" . $text . "%') OR (name Like '%" . $text . "%') OR (EmailID Like '%" . $text . "%') OR (CONCAT(TP_home,TP_Mobile) Like '%" . $text . "%'))";
        }

        $isActive = " AND srp_erp_ngo_com_communitymaster.isActive = '1' ";
        $comVerifiApproved = " AND srp_erp_ngo_com_communitymaster.comVerifiApproved = '1' ";

        $where = "srp_erp_ngo_com_communitymaster.isDeleted = '0'" . $isActive . $comVerifiApproved . $srch_string;

        $queryFP = $this->db->query("SELECT DISTINCT srp_erp_ngo_com_memberwillingtohelp.Com_MasterID  FROM srp_erp_ngo_com_memberwillingtohelp ");
        $rowFP = $queryFP->result();
        $memInr = array();
        foreach ($rowFP as $resFP) {

            $memInr[] = $resFP->Com_MasterID;
        }

        $in_memPrt = "'" . implode("', '", $memInr) . "'";

        $this->form_validation->set_rules('helpCategoryID', 'Help Category', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo ' <div class="alert alert-warning" role="alert">
                ' . validation_errors() . '
            </div>';
        } else {


            $data['willingHelpRprt'] = $this->db->query("SELECT MemberCode,srp_erp_ngo_com_memberwillingtohelp.helpCategoryID,srp_erp_ngo_com_memberwillingtohelp.createdUserID,srp_erp_ngo_com_helpcategories.helpCategoryDes,srp_erp_ngo_com_memberwillingtohelp.helpComments,CName_with_initials,TP_home,CNIC_No,TP_Mobile,EmailID,C_Address,P_Address,HouseNo,GS_Division,GS_No,srp_erp_gender.genderID,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber,srp_erp_ngo_com_helpcategories.helpCategoryDes FROM srp_erp_ngo_com_memberwillingtohelp INNER JOIN srp_erp_ngo_com_communitymaster on srp_erp_ngo_com_communitymaster.Com_MasterID=srp_erp_ngo_com_memberwillingtohelp.Com_MasterID INNER JOIN srp_erp_ngo_com_helpcategories ON srp_erp_ngo_com_helpcategories.helpCategoryID=srp_erp_ngo_com_memberwillingtohelp.helpCategoryID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster ON srp_erp_statemaster.stateID = srp_erp_ngo_com_communitymaster.RegionID WHERE $where " . $where_clauseq . " ORDER BY srp_erp_ngo_com_memberwillingtohelp.Com_MasterID DESC")->result_array();

            $data["type"] = "html";
            echo $html = $this->load->view('system/communityNgo/ajax/load_comMem_willingToHelp_report', $data, true);
        }
    }

    function get_generalWillingToHelp_report_pdf()
    {

        $helpCategoryID = $this->input->post("helpCategoryID");

        if ($helpCategoryID == -2) {
            $helpCategoryIDs = '';
        } else {
            $helpCategoryIDs = $helpCategoryID;
        }

        $text = trim($this->input->post('helpWillSerch'));


        $filter_req = array("AND (srp_erp_ngo_com_helpcategories.helpCategoryID='" . $helpCategoryIDs . "')" => $helpCategoryIDs);
        $set_filter_req = array_filter($filter_req);
        $where_clauseq = join(" ", array_keys($set_filter_req));

        $srch_string = '';
        if (isset($text) && !empty($text)) {

            $srch_string = " AND ((helpCategoryID Like '%" . $text . "%') OR (helpCategoryDes Like '%" . $text . "%') OR (CName_with_initials Like '%" . $text . "%') OR (CNIC_No Like '%" . $text . "%') OR (name Like '%" . $text . "%') OR (EmailID Like '%" . $text . "%') OR (CONCAT(TP_home,TP_Mobile) Like '%" . $text . "%'))";
        }

        $isActive = " AND srp_erp_ngo_com_communitymaster.isActive = '1' ";
        $comVerifiApproved = " AND srp_erp_ngo_com_communitymaster.comVerifiApproved = '1' ";

        $where = "srp_erp_ngo_com_communitymaster.isDeleted = '0'"  . $isActive . $comVerifiApproved . $srch_string;

        $queryFP = $this->db->query("SELECT DISTINCT srp_erp_ngo_com_memberwillingtohelp.Com_MasterID  FROM srp_erp_ngo_com_memberwillingtohelp ");
        $rowFP = $queryFP->result();
        $memInr = array();
        foreach ($rowFP as $resFP) {

            $memInr[] = $resFP->Com_MasterID;
        }

        $in_memPrt = "'" . implode("', '", $memInr) . "'";

        $this->form_validation->set_rules('helpCategoryID', 'Help Category', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo ' <div class="alert alert-warning" role="alert">
                ' . validation_errors() . '
            </div>';
        } else {


            $data['willingHelpRprt'] = $this->db->query("SELECT MemberCode,srp_erp_ngo_com_helpcategories.helpCategoryID,srp_erp_ngo_com_memberwillingtohelp.createdUserID,srp_erp_ngo_com_helpcategories.helpCategoryDes,srp_erp_ngo_com_memberwillingtohelp.helpComments,CName_with_initials,TP_home,CNIC_No,TP_Mobile,EmailID,C_Address,P_Address,HouseNo,GS_Division,GS_No,srp_erp_gender.genderID,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber,srp_erp_ngo_com_helpcategories.helpCategoryDes FROM srp_erp_ngo_com_memberwillingtohelp INNER JOIN srp_erp_ngo_com_communitymaster on srp_erp_ngo_com_communitymaster.Com_MasterID=srp_erp_ngo_com_memberwillingtohelp.Com_MasterID INNER JOIN srp_erp_ngo_com_helpcategories ON srp_erp_ngo_com_helpcategories.helpCategoryID=srp_erp_ngo_com_memberwillingtohelp.helpCategoryID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_statemaster ON srp_erp_statemaster.stateID = srp_erp_ngo_com_communitymaster.RegionID WHERE $where " . $where_clauseq . " ORDER BY srp_erp_ngo_com_memberwillingtohelp.Com_MasterID DESC")->result_array();

            $data["type"] = "pdf";
            $html = $this->load->view('system/communityNgo/ajax/load_comMem_willingToHelp_report', $data, true);
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4');
        }
    }

    /* end of community willing to help and help req .. report details*/
    function get_generalBothHelp_report()
    {

        $bothHelpRqID = $this->input->post("bothHelpRqID");

        if ($bothHelpRqID == -1) {
            $bothHelpRqIDs = '';
        } else {
            $bothHelpRqIDs = $bothHelpRqID;
        }

        $text = trim($this->input->post('bothHelpSerch'));


        $filter_req = array("AND (comHlpingMas.Com_MasterID='" . $bothHelpRqIDs . "')" => $bothHelpRqIDs);
        $set_filter_req = array_filter($filter_req);
        $where_clauseq = join(" ", array_keys($set_filter_req));

        $srch_string = '';
        if (isset($text) && !empty($text)) {

            $srch_string = " AND ((comHlpingMas.Com_MasterID Like '%" . $text . "%') OR (helpCategoryDes Like '%" . $text . "%') OR (hlprDescription Like '%" . $text . "%') OR (CName_with_initials Like '%" . $text . "%') OR (CNIC_No Like '%" . $text . "%') OR (name Like '%" . $text . "%') OR (EmailID Like '%" . $text . "%') OR (CONCAT(TP_home,TP_Mobile) Like '%" . $text . "%'))";
        }

        $isActive = " AND comHlpingMas.isActive = '1' ";
        $comVerifiApproved = " AND comHlpingMas.comVerifiApproved = '1' ";

        $where = "comHlpingMas.isDeleted = '0'" . $isActive . $comVerifiApproved . $srch_string;


        $this->form_validation->set_rules('bothHelpRqID', 'Member', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo ' <div class="alert alert-warning" role="alert">
                ' . validation_errors() . '
            </div>';
        } else {


            $data['bothHelpMem'] = $this->db->query("SELECT DISTINCT comHlpingMas.Com_MasterID,MemberCode,CName_with_initials,CNIC_No,C_Address,EmailID,P_Address,HouseNo,GS_Division,GS_No,srp_erp_gender.genderID,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber FROM srp_erp_ngo_com_communitymaster comHlpingMas INNER JOIN srp_erp_ngo_com_memberhelprequirements helpRq ON helpRq.Com_MasterID=comHlpingMas.Com_MasterID INNER JOIN srp_erp_ngo_com_memberwillingtohelp helpWilling ON helpWilling.Com_MasterID=comHlpingMas.Com_MasterID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = comHlpingMas.GenderID LEFT JOIN srp_erp_statemaster ON srp_erp_statemaster.stateID = comHlpingMas.RegionID WHERE $where " . $where_clauseq . "")->result_array();

            $data["type"] = "html";
            echo $html = $this->load->view('system/communityNgo/ajax/load_comMem_helpWillingAndReq_report', $data, true);
        }
    }

    function get_generalBothHelp_report_pdf()
    {

        $bothHelpRqID = $this->input->post("bothHelpRqID");

        if ($bothHelpRqID == -1) {
            $bothHelpRqIDs = '';
        } else {
            $bothHelpRqIDs = $bothHelpRqID;
        }

        $text = trim($this->input->post('bothHelpSerch'));


        $filter_req = array("AND (comHlpingMas.Com_MasterID='" . $bothHelpRqIDs . "')" => $bothHelpRqIDs);
        $set_filter_req = array_filter($filter_req);
        $where_clauseq = join(" ", array_keys($set_filter_req));

        $srch_string = '';
        if (isset($text) && !empty($text)) {

            $srch_string = " AND ((comHlpingMas.Com_MasterID Like '%" . $text . "%') OR (helpCategoryDes Like '%" . $text . "%') OR (hlprDescription Like '%" . $text . "%') OR (CName_with_initials Like '%" . $text . "%') OR (CNIC_No Like '%" . $text . "%') OR (name Like '%" . $text . "%') OR (EmailID Like '%" . $text . "%') OR (CONCAT(TP_home,TP_Mobile) Like '%" . $text . "%'))";
        }

        $isActive = " AND comHlpingMas.isActive = '1' ";
        $comVerifiApproved = " AND comHlpingMas.comVerifiApproved = '1' ";

        $where = "comHlpingMas.isDeleted = '0'" . $isActive . $comVerifiApproved . $srch_string;


        $this->form_validation->set_rules('bothHelpRqID', 'Member', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo ' <div class="alert alert-warning" role="alert">
                ' . validation_errors() . '
            </div>';
        } else {


            $data['bothHelpMem'] = $this->db->query("SELECT DISTINCT comHlpingMas.Com_MasterID,MemberCode,CName_with_initials,CNIC_No,C_Address,EmailID,P_Address,HouseNo,GS_Division,GS_No,srp_erp_gender.genderID,srp_erp_gender.name AS Gender,CONCAT(TP_Mobile,' | ',TP_home) AS PrimaryNumber FROM srp_erp_ngo_com_communitymaster comHlpingMas INNER JOIN srp_erp_ngo_com_memberhelprequirements helpRq ON helpRq.Com_MasterID=comHlpingMas.Com_MasterID INNER JOIN srp_erp_ngo_com_memberwillingtohelp helpWilling ON helpWilling.Com_MasterID=comHlpingMas.Com_MasterID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID = comHlpingMas.GenderID LEFT JOIN srp_erp_statemaster ON srp_erp_statemaster.stateID = comHlpingMas.RegionID WHERE $where " . $where_clauseq . "")->result_array();

            $data["type"] = "pdf";
            $html = $this->load->view('system/communityNgo/ajax/load_comMem_helpWillingAndReq_report', $data, true);
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4');
        }
    }

    function fetch_helpType_delDropdown()
    {
        $memHelpType = $this->input->post('memHelpType');

        if ($memHelpType) {
            if ($memHelpType == 1) {
                $govHelp = $this->db->query("SELECT helpRequireID,helpRequireDesc FROM srp_erp_ngo_com_helprequirements WHERE helpRequireType = 'GOV' ")->result_array();

                echo '<option value="">Select a Help Des.</option>';
                foreach ($govHelp as $row) {
                    echo '<option value="' . trim($row['helpRequireID']) . '">' . trim($row['helpRequireDesc']) . '</option>';
                }
            }
            if ($memHelpType == 2) {
                $prvHelp = $this->db->query("SELECT helpRequireID,helpRequireDesc FROM srp_erp_ngo_com_helprequirements WHERE helpRequireType = 'PVT' ")->result_array();

                echo '<option value="">Select a Help Des.</option>';
                foreach ($prvHelp as $row) {
                    echo '<option value="' . trim($row['helpRequireID']) . '">' . trim($row['helpRequireDesc']) . '</option>';
                }
            }
            if ($memHelpType == 3) {
                $consHelp = $this->db->query("SELECT helpRequireID,helpRequireDesc FROM srp_erp_ngo_com_helprequirements WHERE helpRequireType = 'CONS' ")->result_array();

                echo '<option value="">Select a Help Des.</option>';
                foreach ($consHelp as $row) {
                    echo '<option value="' . trim($row['helpRequireID']) . '">' . trim($row['helpRequireDesc']) . '</option>';
                }
            }
            if ($memHelpType == 4) {
                $consHelp = $this->db->query("SELECT helpRequireID,helpRequireDesc FROM srp_erp_ngo_com_helprequirements WHERE helpRequireType = 'OTHER' ")->result_array();

                echo '<option value="">Select a Help Des.</option>';
                foreach ($consHelp as $row) {
                    echo '<option value="' . trim($row['helpRequireID']) . '">' . trim($row['helpRequireDesc']) . '</option>';
                }
            }
        }
    }

    //end of helping del

    /*end of community general report dropdown*/
    /*OP community */
}




/**
 * Created by PhpStorm.
 * User: Moufiya
 * Date: 12/27/2020
 * Time: 15:01 PM
 */
