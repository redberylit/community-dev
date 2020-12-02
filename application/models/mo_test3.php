<?php
$fatherJob ='';
$motherJob ='';
$get_fatherSide = $this->db->query("SELECT srp_erp_ngo_com_familydetails.Com_MasterID,CFullName,CDOB,CNIC_No,countyID,RegionID,CBC_No,CBC_Date,
 fatherIsAlive,cfDateOfDeath,cfFullName,cFatherDOB,cfBornCountry,cfBornArea,cfBC_No,cfBCDate,cfOccupationId,cfNIC_No,cfDC_No,cfDCDate,
 motherIsAlive,cmDateOfDeath,cmFullName,cMotherDOB,cmBornCountry,cmBornArea,cmBC_No,cmBCDate,cmOccupationId,cmNIC_No,cmDC_No,cmDCDate,
 cf_GrandFIsAlive,cf_GrandFDateOfDeath,cf_GrandFFullName,cf_GrandFDOB,cf_GrandFBornCountry,cf_GrandFBornArea,cf_GrandFBC_No,cf_GrandFBCDate,cf_GrandFOccupationId,cf_GrandFNIC_No,cf_GrandFDC_No,cf_GrandFDCDate,
 cf_GrandMIsAlive,cf_GrandMDateOfDeath,cf_GrandMFullName,cf_GrandMDOB,cf_GrandMBornCountry,cf_GrandMBornArea,cf_GrandMBC_No,cf_GrandMBCDate,cf_GrandMOccupationId,cf_GrandMNIC_No,cf_GrandMDC_No,cf_GrandMDCDate
 FROM srp_erp_ngo_com_familydetails INNER JOIN srp_erp_ngo_com_communitymaster ON srp_erp_ngo_com_familydetails.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID WHERE srp_erp_ngo_com_familydetails.FamMasterID ={$FamMasterID} AND srp_erp_ngo_com_familydetails.relationshipID='4'");
$rowFSide = $get_fatherSide->row();
if (!empty($rowFSide)) {
    $queryFJob = $this->db->query("SELECT DISTINCT MemJobID,specializationID FROM srp_erp_ngo_com_memjobs WHERE srp_erp_ngo_com_memjobs.Com_MasterID={$rowFSide->Com_MasterID} AND srp_erp_ngo_com_memjobs.companyID={$companyID} ");
    $rowFJob = $queryFJob->row();
    if(!empty($rowFJob)){$fatherJob = $rowFJob->specializationID;} else{$fatherJob = '';}
}

$get_motherSide = $this->db->query("SELECT srp_erp_ngo_com_familydetails.Com_MasterID,CFullName,CDOB,CNIC_No,countyID,RegionID,CBC_No,CBC_Date,
 fatherIsAlive,cfDateOfDeath,cfFullName,cFatherDOB,cfBornCountry,cfBornArea,cfBC_No,cfBCDate,cfOccupationId,cfNIC_No,cfDC_No,cfDCDate,
 motherIsAlive,cmDateOfDeath,cmFullName,cMotherDOB,cmBornCountry,cmBornArea,cmBC_No,cmBCDate,cmOccupationId,cmNIC_No,cmDC_No,cmDCDate,
 cm_GrandFIsAlive,cm_GrandFDateOfDeath,cm_GrandFFullName,cm_GrandFDOB,cm_GrandFBornCountry,cm_GrandFBornArea,cm_GrandFBC_No,cm_GrandFBCDate,cm_GrandFOccupationId,cm_GrandFNIC_No,cm_GrandFDC_No,cm_GrandFDCDate,
 cm_GrandMIsAlive,cm_GrandMDateOfDeath,cm_GrandMFullName,cm_GrandMDOB,cm_GrandMBornCountry,cm_GrandMBornArea,cm_GrandMBC_No,cm_GrandMBCDate,cm_GrandMOccupationId,cm_GrandMNIC_No,cm_GrandMDC_No,cm_GrandMDCDate
 FROM srp_erp_ngo_com_familydetails INNER JOIN srp_erp_ngo_com_communitymaster ON srp_erp_ngo_com_familydetails.Com_MasterID=srp_erp_ngo_com_communitymaster.Com_MasterID WHERE srp_erp_ngo_com_familydetails.FamMasterID ={$FamMasterID} AND srp_erp_ngo_com_familydetails.relationshipID='5'");
$rowMSide = $get_motherSide->row();
if (!empty($rowMSide)) {
    $queryMJob = $this->db->query("SELECT DISTINCT MemJobID,specializationID FROM srp_erp_ngo_com_memjobs WHERE srp_erp_ngo_com_memjobs.Com_MasterID={$rowMSide->Com_MasterID} AND srp_erp_ngo_com_memjobs.companyID={$companyID} ");
    $rowMJob = $queryMJob->row();
    if(!empty($rowMJob)){$motherJob = $rowMJob->specializationID;} else{$motherJob = '';}
}

if($NewRelatnID[$key]== 2 || $NewRelatnID[$key] == 3){
    //update father
    if (!empty($rowFSide)) {

        $ftrSdData['fatherIsAlive'] = 1;
        $ftrSdData['cfFullName'] = $rowFSide->CFullName;
        $ftrSdData['cFatherDOB'] = $rowFSide->CDOB;
        $ftrSdData['cfBornCountry'] = $rowFSide->countyID;
        $ftrSdData['cfBornArea'] = $rowFSide->RegionID;
        $ftrSdData['cfBC_No'] = $rowFSide->CBC_No;
        $ftrSdData['cfBCDate'] = $rowFSide->CBC_Date;
        $ftrSdData['cfOccupationId'] = $fatherJob;
        $ftrSdData['cfNIC_No'] = $rowFSide->CNIC_No;
        $ftrSdData['cf_GrandFIsAlive'] = $rowFSide->fatherIsAlive;
        $ftrSdData['cf_GrandFDateOfDeath'] = $rowFSide->cfDateOfDeath;
        $ftrSdData['cf_GrandFFullName'] = $rowFSide->cfFullName;
        $ftrSdData['cf_GrandFDOB'] = $rowFSide->cFatherDOB;
        $ftrSdData['cf_GrandFBornCountry'] = $rowFSide->cfBornCountry;
        $ftrSdData['cf_GrandFBornArea'] = $rowFSide->cfBornArea;
        $ftrSdData['cf_GrandFBC_No'] = $rowFSide->cfBC_No;
        $ftrSdData['cf_GrandFBCDate'] = $rowFSide->cfBCDate;
        $ftrSdData['cf_GrandFOccupationId'] = $rowFSide->cfOccupationId;
        $ftrSdData['cf_GrandFNIC_No'] = $rowFSide->cfNIC_No;
        $ftrSdData['cf_GrandFDC_No'] = $rowFSide->cfDC_No;
        $ftrSdData['cf_GrandFDCDate'] = $rowFSide->cfDCDate;
        $ftrSdData['cf_GrandMIsAlive'] = $rowFSide->motherIsAlive;
        $ftrSdData['cf_GrandMDateOfDeath'] = $rowFSide->cmDateOfDeath;
        $ftrSdData['cf_GrandMFullName'] = $rowFSide->cmFullName;
        $ftrSdData['cf_GrandMDOB'] = $rowFSide->cMotherDOB;
        $ftrSdData['cf_GrandMBornCountry'] = $rowFSide->cmBornCountry;
        $ftrSdData['cf_GrandMBornArea'] = $rowFSide->cmBornArea;
        $ftrSdData['cf_GrandMBC_No'] = $rowFSide->cmBC_No;
        $ftrSdData['cf_GrandMBCDate'] = $rowFSide->cmBCDate;
        $ftrSdData['cf_GrandMOccupationId'] = $rowFSide->cmOccupationId;
        $ftrSdData['cf_GrandMNIC_No'] = $rowFSide->cmNIC_No;
        $ftrSdData['cf_GrandMDC_No'] = $rowFSide->cmDC_No;
        $ftrSdData['cf_GrandMDCDate'] = $rowFSide->cmDCDate;
        $ftrSdData['cf_grt_GrandFIsAlive'] = $rowFSide->cf_GrandFIsAlive;
        $ftrSdData['cf_grt_GrandFDateOfDeath'] = $rowFSide->cf_GrandFDateOfDeath;
        $ftrSdData['cf_grt_GrandFFullName'] = $rowFSide->cf_GrandFFullName;
        $ftrSdData['cf_grt_GrandFDOB'] = $rowFSide->cf_GrandFDOB;
        $ftrSdData['cf_grt_GrandFBornCountry'] = $rowFSide->cf_GrandFBornCountry;
        $ftrSdData['cf_grt_GrandFBornArea'] = $rowFSide->cf_GrandFBornArea;
        $ftrSdData['cf_grt_GrandFBC_No'] = $rowFSide->cf_GrandFBC_No;
        $ftrSdData['cf_grt_GrandFBCDate'] = $rowFSide->cf_GrandFBCDate;
        $ftrSdData['cf_grt_GrandFOccupationId'] = $rowFSide->cf_GrandFOccupationId;
        $ftrSdData['cf_grt_GrandFNIC_No'] = $rowFSide->cf_GrandFNIC_No;
        $ftrSdData['cf_grt_GrandFDC_No'] = $rowFSide->cf_GrandFDC_No;
        $ftrSdData['cf_grt_GrandFDCDate'] = $rowFSide->cf_GrandFDCDate;
        $ftrSdData['cf_grt_GrandMIsAlive'] = $rowFSide->cf_GrandMIsAlive;
        $ftrSdData['cf_grt_GrandMDateOfDeath'] = $rowFSide->cf_GrandMDateOfDeath;
        $ftrSdData['cf_grt_GrandMFullName'] = $rowFSide->cf_GrandMFullName;
        $ftrSdData['cf_grt_GrandMDOB'] = $rowFSide->cf_GrandMDOB;
        $ftrSdData['cf_grt_GrandMBornCountry'] = $rowFSide->cf_GrandMBornCountry;
        $ftrSdData['cf_grt_GrandMBornArea'] = $rowFSide->cf_GrandMBornArea;
        $ftrSdData['cf_grt_GrandMBC_No'] = $rowFSide->cf_GrandMBC_No;
        $ftrSdData['cf_grt_GrandMBCDate'] = $rowFSide->cf_GrandMBCDate;
        $ftrSdData['cf_grt_GrandMOccupationId'] = $rowFSide->cf_GrandMOccupationId;
        $ftrSdData['cf_grt_GrandMNIC_No'] = $rowFSide->cf_GrandMNIC_No;
        $ftrSdData['cf_grt_GrandMDC_No'] = $rowFSide->cf_GrandMDC_No;
        $ftrSdData['cf_grt_GrandMDCDate'] = $rowFSide->cf_GrandMDCDate;

        $ftrSdData['modifiedPCID'] = $this->common_data['current_pc'];
        $ftrSdData['modifiedUserID'] = $this->common_data['current_userID'];
        $ftrSdData['modifiedUserName'] = $this->common_data['current_user'];
        $ftrSdData['modifiedDateTime'] = $this->common_data['current_date'];

        $this->db->where('Com_MasterID', $last_id);
        $update = $this->db->update('srp_erp_ngo_com_communitymaster', $ftrSdData);

    }
    //update mother
    if (!empty($rowMSide)) {

        $mtrSdData['motherIsAlive'] = 1;
        $mtrSdData['cmFullName'] = $rowMSide->CFullName;
        $mtrSdData['cMotherDOB'] = $rowMSide->CDOB;
        $mtrSdData['cmBornCountry'] = $rowMSide->countyID;
        $mtrSdData['cmBornArea'] = $rowMSide->RegionID;
        $mtrSdData['cmBC_No'] = $rowMSide->CBC_No;
        $mtrSdData['cmBCDate'] = $rowMSide->CBC_Date;
        $mtrSdData['cmOccupationId'] = $motherJob;
        $mtrSdData['cmNIC_No'] = $rowMSide->CNIC_No;
        $mtrSdData['cm_GrandFIsAlive'] = $rowMSide->fatherIsAlive;
        $mtrSdData['cm_GrandFDateOfDeath'] = $rowMSide->cfDateOfDeath;
        $mtrSdData['cm_GrandFFullName'] = $rowMSide->cfFullName;
        $mtrSdData['cm_GrandFDOB'] = $rowMSide->cFatherDOB;
        $mtrSdData['cm_GrandFBornCountry'] = $rowMSide->cfBornCountry;
        $mtrSdData['cm_GrandFBornArea'] = $rowMSide->cfBornArea;
        $mtrSdData['cm_GrandFBC_No'] = $rowMSide->cfBC_No;
        $mtrSdData['cm_GrandFBCDate'] = $rowMSide->cfBCDate;
        $mtrSdData['cm_GrandFOccupationId'] = $rowMSide->cfOccupationId;
        $mtrSdData['cm_GrandFNIC_No'] = $rowMSide->cfNIC_No;
        $mtrSdData['cm_GrandFDC_No'] = $rowMSide->cfDC_No;
        $mtrSdData['cm_GrandFDCDate'] = $rowMSide->cfDCDate;
        $mtrSdData['cm_GrandMIsAlive'] = $rowMSide->motherIsAlive;
        $mtrSdData['cm_GrandMDateOfDeath'] = $rowMSide->cmDateOfDeath;
        $mtrSdData['cm_GrandMFullName'] = $rowMSide->cmFullName;
        $mtrSdData['cm_GrandMDOB'] = $rowMSide->cMotherDOB;
        $mtrSdData['cm_GrandMBornCountry'] = $rowMSide->cmBornCountry;
        $mtrSdData['cm_GrandMBornArea'] = $rowMSide->cmBornArea;
        $mtrSdData['cm_GrandMBC_No'] = $rowMSide->cmBC_No;
        $mtrSdData['cm_GrandMBCDate'] = $rowMSide->cmBCDate;
        $mtrSdData['cm_GrandMOccupationId'] = $rowMSide->cmOccupationId;
        $mtrSdData['cm_GrandMNIC_No'] = $rowMSide->cmNIC_No;
        $mtrSdData['cm_GrandMDC_No'] = $rowMSide->cmDC_No;
        $mtrSdData['cm_GrandMDCDate'] = $rowMSide->cmDCDate;
        $mtrSdData['cm_grt_GrandFIsAlive'] = $rowMSide->cm_GrandFIsAlive;
        $mtrSdData['cm_grt_GrandFDateOfDeath'] = $rowMSide->cm_GrandFDateOfDeath;
        $mtrSdData['cm_grt_GrandFFullName'] = $rowMSide->cm_GrandFFullName;
        $mtrSdData['cm_grt_GrandFDOB'] = $rowMSide->cm_GrandFDOB;
        $mtrSdData['cm_grt_GrandFBornCountry'] = $rowMSide->cm_GrandFBornCountry;
        $mtrSdData['cm_grt_GrandFBornArea'] = $rowMSide->cm_GrandFBornArea;
        $mtrSdData['cm_grt_GrandFBC_No'] = $rowMSide->cm_GrandFBC_No;
        $mtrSdData['cm_grt_GrandFBCDate'] = $rowMSide->cm_GrandFBCDate;
        $mtrSdData['cm_grt_GrandFOccupationId'] = $rowMSide->cm_GrandFOccupationId;
        $mtrSdData['cm_grt_GrandFNIC_No'] = $rowMSide->cm_GrandFNIC_No;
        $mtrSdData['cm_grt_GrandFDC_No'] = $rowMSide->cm_GrandFDC_No;
        $mtrSdData['cm_grt_GrandFDCDate'] = $rowMSide->cm_GrandFDCDate;
        $mtrSdData['cm_grt_GrandMIsAlive'] = $rowMSide->cm_GrandMIsAlive;
        $mtrSdData['cm_grt_GrandMDateOfDeath'] = $rowMSide->cm_GrandMDateOfDeath;
        $mtrSdData['cm_grt_GrandMFullName'] = $rowMSide->cm_GrandMFullName;
        $mtrSdData['cm_grt_GrandMDOB'] = $rowMSide->cm_GrandMDOB;
        $mtrSdData['cm_grt_GrandMBornCountry'] = $rowMSide->cm_GrandMBornCountry;
        $mtrSdData['cm_grt_GrandMBornArea'] = $rowMSide->cm_GrandMBornArea;
        $mtrSdData['cm_grt_GrandMBC_No'] = $rowMSide->cm_GrandMBC_No;
        $mtrSdData['cm_grt_GrandMBCDate'] = $rowMSide->cm_GrandMBCDate;
        $mtrSdData['cm_grt_GrandMOccupationId'] = $rowMSide->cm_GrandMOccupationId;
        $mtrSdData['cm_grt_GrandMNIC_No'] = $rowMSide->cm_GrandMNIC_No;
        $mtrSdData['cm_grt_GrandMDC_No'] = $rowMSide->cm_GrandMDC_No;
        $mtrSdData['cm_grt_GrandMDCDate'] = $rowMSide->cm_GrandMDCDate;

        $mtrSdData['modifiedPCID'] = $this->common_data['current_pc'];
        $mtrSdData['modifiedUserID'] = $this->common_data['current_userID'];
        $mtrSdData['modifiedUserName'] = $this->common_data['current_user'];
        $mtrSdData['modifiedDateTime'] = $this->common_data['current_date'];

        $this->db->where('Com_MasterID', $last_id);
        $update = $this->db->update('srp_erp_ngo_com_communitymaster', $mtrSdData);

    }
}
