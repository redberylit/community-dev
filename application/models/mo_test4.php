<?php
if (!empty($rowFSide)) {

    $queryFJob = $this->db->query("SELECT DISTINCT MemJobID,specializationID FROM srp_erp_ngo_com_memjobs WHERE srp_erp_ngo_com_memjobs.Com_MasterID={$Com_MasterID} AND srp_erp_ngo_com_memjobs.companyID={$companyID} ");
    $rowFJob = $queryFJob->row();
    if(!empty($rowFJob)){
        $fatheJob = $rowFJob->specializationID;
    }
    else{
        $fatheJob = '';
    }

    $fatherIsAlive = 1;
    $cfFullName = $rowFSide->CFullName;
    $cFatherDOB = $rowFSide->CDOB;
    $cfBornCountry = $rowFSide->countyID;
    $cfBornArea = $rowFSide->RegionID;
    $cfBC_No = $rowFSide->CBC_No;
    $cfBCDate = $rowFSide->CBC_Date;
    $cfOccupationId = $fatheJob;
    $cfNIC_No = $rowFSide->CNIC_No;
    $cf_GrandFIsAlive = $rowFSide->fatherIsAlive;
    $cf_GrandFDateOfDeath = $rowFSide->cfDateOfDeath;
    $cf_GrandFFullName = $rowFSide->cfFullName;
    $cf_GrandFDOB = $rowFSide->cFatherDOB;
    $cf_GrandFBornCountry = $rowFSide->cfBornCountry;
    $cf_GrandFBornArea = $rowFSide->cfBornArea;
    $cf_GrandFBC_No = $rowFSide->cfBC_No;
    $cf_GrandFBCDate = $rowFSide->cfBCDate;
    $cf_GrandFOccupationId = $rowFSide->cfOccupationId;
    $cf_GrandFNIC_No = $rowFSide->cfNIC_No;
    $cf_GrandFDC_No = $rowFSide->cfDC_No;
    $cf_GrandFDCDate = $rowFSide->cfDCDate;
    $cf_GrandMIsAlive = $rowFSide->motherIsAlive;
    $cf_GrandMDateOfDeath = $rowFSide->cmDateOfDeath;
    $cf_GrandMFullName = $rowFSide->cmFullName;
    $cf_GrandMDOB = $rowFSide->cMotherDOB;
    $cf_GrandMBornCountry = $rowFSide->cmBornCountry;
    $cf_GrandMBornArea = $rowFSide->cmBornArea;
    $cf_GrandMBC_No = $rowFSide->cmBC_No;
    $cf_GrandMBCDate = $rowFSide->cmBCDate;
    $cf_GrandMOccupationId = $rowFSide->cmOccupationId;
    $cf_GrandMNIC_No = $rowFSide->cmNIC_No;
    $cf_GrandMDC_No = $rowFSide->cmDC_No;
    $cf_GrandMDCDate = $rowFSide->cmDCDate;
    $cf_grt_GrandFIsAlive = $rowFSide->cf_GrandFIsAlive;
    $cf_grt_GrandFDateOfDeath = $rowFSide->cf_GrandFDateOfDeath;
    $cf_grt_GrandFFullName = $rowFSide->cf_GrandFFullName;
    $cf_grt_GrandFDOB = $rowFSide->cf_GrandFDOB;
    $cf_grt_GrandFBornCountry = $rowFSide->cf_GrandFBornCountry;
    $cf_grt_GrandFBornArea = $rowFSide->cf_GrandFBornArea;
    $cf_grt_GrandFBC_No = $rowFSide->cf_GrandFBC_No;
    $cf_grt_GrandFBCDate = $rowFSide->cf_GrandFBCDate;
    $cf_grt_GrandFOccupationId = $rowFSide->cf_GrandFOccupationId;
    $cf_grt_GrandFNIC_No = $rowFSide->cf_GrandFNIC_No;
    $cf_grt_GrandFDC_No = $rowFSide->cf_GrandFDC_No;
    $cf_grt_GrandFDCDate = $rowFSide->cf_GrandFDCDate;
    $cf_grt_GrandMIsAlive = $rowFSide->cf_GrandMIsAlive;
    $cf_grt_GrandMDateOfDeath = $rowFSide->cf_GrandMDateOfDeath;
    $cf_grt_GrandMFullName = $rowFSide->cf_GrandMFullName;
    $cf_grt_GrandMDOB = $rowFSide->cf_GrandMDOB;
    $cf_grt_GrandMBornCountry = $rowFSide->cf_GrandMBornCountry;
    $cf_grt_GrandMBornArea = $rowFSide->cf_GrandMBornArea;
    $cf_grt_GrandMBC_No = $rowFSide->cf_GrandMBC_No;
    $cf_grt_GrandMBCDate = $rowFSide->cf_GrandMBCDate;
    $cf_grt_GrandMOccupationId = $rowFSide->cf_GrandMOccupationId;
    $cf_grt_GrandMNIC_No = $rowFSide->cf_GrandMNIC_No;
    $cf_grt_GrandMDC_No = $rowFSide->cf_GrandMDC_No;
    $cf_grt_GrandMDCDate = $rowFSide->cf_GrandMDCDate;

    $ftrSdData['Age'] = $memAge . '' . 'yrs';
    $ftrSdData['countyID'] = $countyID;
    $ftrSdData['provinceID'] = $provinceID;
    $ftrSdData['districtID'] = $districtID;
    $ftrSdData['districtDivisionID'] = $districtDivisionID;
    $ftrSdData['jammiyahDivisionID'] = $jammiyahDivisionID;
    $ftrSdData['RegionID'] = $RegionID;
    $ftrSdData['GS_Division'] = $GS_Division;
    $ftrSdData['GS_No'] = $GS_No;
    $ftrSdData['P_Address'] = $P_Address;
    $ftrSdData['C_Address'] = $C_Address;
    $ftrSdData['TP_home'] = $TP_home;
    $ftrSdData['TP_Mobile'] = $TP_Mobile;
    $ftrSdData['CountryCodePrimary'] = $CountryCodePrimary;
    $ftrSdData['AreaCodePrimary'] = $AreaCodePrimary;
    $ftrSdData['HouseNo'] = $HouseNo;
    $ftrSdData['comVerifiApproved'] = '1';

    $ftrSdData['modifiedPCID'] = $this->common_data['current_pc'];
    $ftrSdData['modifiedUserID'] = $this->common_data['current_userID'];
    $ftrSdData['modifiedUserName'] = $this->common_data['current_user'];
    $ftrSdData['modifiedDateTime'] = $this->common_data['current_date'];

    $ftrSdData['companyID'] = $this->common_data['company_data']['company_id'];

    $ftrSdData['createdPCID'] = $this->common_data['current_pc'];
    $ftrSdData['createdUserID'] = $this->common_data['current_userID'];
    $ftrSdData['createdUserName'] = $this->common_data['current_user'];
    $ftrSdData['createdDateTime'] = $this->common_data['current_date'];

    $this->db->insert('srp_erp_ngo_com_communitymaster', $ftrSdData);

}
