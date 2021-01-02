<?php
$primaryLanguage = getPrimaryLanguage();

$this->lang->load('communityngo', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$companyCode = $this->common_data['company_data']['company_code'];
$currencyCode = $this->common_data['company_data']['company_default_currency'];
$decimals = $this->common_data['company_data']['company_default_decimal'];

$date_format_policy = date_format_policy();
?>
<div class="table-responsive">
    <table style="width: 100%">
        <tbody>
        <tr>
            <td style="width:55%;">
                <table>
                    <tr>
                        <td style="font-size: 12px;">
                            <img alt="Logo" style="height: 130px" src="<?php
                            echo mPDFImage.$this->common_data['company_data']['company_logo']; ?>">
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:45%;">
                <table>
                    <tr>
                        <td colspan="3">
                            <h4><strong><?php echo $this->common_data['company_data']['company_name'].' ('.$this->common_data['company_data']['company_code'].').'; ?></strong></h4>
                            <p><?php echo $this->common_data['company_data']['company_address1'].' '.$this->common_data['company_data']['company_address2'].' '.$this->common_data['company_data']['company_city'].' '.$this->common_data['company_data']['company_country']; ?></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<?php
$personalState ='No';
$pRelationship ='';
$pEconStateDes ='';
$pFamHouseSt ='';
$pExitFamilyName ='';
$AncestorySt ='';
$pAncestryDes ='';
$pMonthlyExpenses ='';
$pOwnershipDescription ='';
$pHTypeDescription ='';
if (!empty($extra['master'])) {
    $communityimage = get_all_community_images($extra['master']['CImage'],'Community/'.$companyCode.'/MemberImages/','communityNoImg');
if(!empty($extra['cmFamilyDetails'])) {
    $personalState ='Yes';
    $pRelationship = $extra['cmFamilyDetails']['relationship'];
    if($extra['cmFamilyDetails']['FamAncestory']=='1'){
        $AncestorySt = 'Outside';
        $pAncestryDes = $extra['cmFamilyDetails']['AncestryDes'];
    }
    else{
        $AncestorySt ='Local';
    $pAncestryDes ='';
    }
    $pEconStateDes =$extra['cmFamilyDetails']['EconStateDes'];
    if($extra['cmFamilyDetails']['FamHouseSt']=='1') {
        $pFamHouseSt= 'Yes';
       $pExitFamilyName = $extra['cmFamilyDetails']['exitFamilyName'];
    } else{
        $pFamHouseSt= 'No';
        $pExitFamilyName= '';
    }
    $pMonthlyExpenses = $currencyCode . ' ' . number_format($extra['cmFamilyDetails']['monthlyExpenses'],$decimals);
    $pOwnershipDescription = $extra['cmFamilyDetails']['ownershipDescription'];
    $pHTypeDescription = $extra['cmFamilyDetails']['hTypeDescription'];
}
    ?>
<div class="table-responsive">
    <div class="row">
        <table class="table table-striped" id="profileInfoTable"
               style="background-color: #ffffff; width: 100%;font-size: 15px;">
            <tbody>
        <tr>
            <td colspan="2" style="width:50%;">
                <h4><strong style="font-weight: bold;">Masjid Data Collection Form</strong></h4>
            </td>
            <td style="width: 20%;font-weight: bold;font-size: 14px;">Ref # : <?php echo $extra['master']['MemberCode'] ?></td>
            <td style="width:30%;">
             <img alt="Member Image" style="height:75px;width:110px;" src="<?php echo $communityimage; ?>">

            </td>
        </tr>
        <tr>
            <td style="font-size: 14px;">
                <strong id="_fHead" style="color: #095db3;"><?php echo $this->lang->line('CommunityNgo_leader'); ?>: </strong>
                <?php echo $personalState; ?>
            </td>
            <td>
            </td>
            <td style="font-size: 14px;">
                <strong id="_relationship" style="color: #095db3;"><?php echo $this->lang->line('communityngo_relationship'); ?>: </strong>
                <?php echo $pRelationship; ?>
            </td>
            <td>
            </td>
        </tr>
        <tr style="background-color: #e0d3ed;">
            <td style="font-size: 12px;background-color: #e0d3ed;">
                <strong id="_ancestryState" style="font-weight: bold;"><?php echo $this->lang->line('CommunityNgo_fam_ancestryState'); ?>: </strong>
                <?php echo $AncestorySt; ?>
            </td>
            <td style="font-size: 12px;background-color: #e0d3ed;">
                <strong id="_economic_status" style="font-weight: bold;"><?php echo $this->lang->line('comNgo_dash_jammiya_economic_status'); ?>: </strong>
                <?php echo $pEconStateDes; ?>
            </td>
            <td style="font-size: 12px;background-color: #e0d3ed;">
                <strong id="_famExistHouse" style="font-weight: bold;"><?php echo $this->lang->line('communityngo_famExistHouse'); ?>: </strong>
                <?php echo $pFamHouseSt; ?>
            </td>
            <td style="font-size: 12px;background-color: #e0d3ed;">
                <?php echo $pExitFamilyName; ?>
            </td>
        </tr>
        <tr style="background-color: #e0d3ed;">
            <td style="font-size: 12px;background-color: #e0d3ed;">
                <strong id="_ancestry" style="font-weight: bold;"><?php echo $this->lang->line('CommunityNgo_fam_ancestry'); ?>: </strong>
                <?php echo $pAncestryDes; ?>
            </td>
            <td style="font-size: 12px;background-color: #e0d3ed;">
                <strong id="_mExpenses" style="font-weight: bold;"><?php echo $this->lang->line('CommunityNgo_fam_expenses'); ?>:</strong>
                <?php echo $pMonthlyExpenses; ?>
            </td>
            <td style="font-size: 12px;background-color: #e0d3ed;">
                <strong id="_famOwnType" style="font-weight: bold;"><?php echo $this->lang->line('communityngo_famOwnType'); ?>: </strong>
                <?php echo $pOwnershipDescription; ?>
            </td>
            <td style="font-size: 12px;background-color: #e0d3ed;">
                <strong id="_famHouseType" style="font-weight: bold;"><?php echo $this->lang->line('communityngo_famHouseType'); ?>: </strong>
                <?php echo $pHTypeDescription; ?>
            </td>
        </tr>

                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_TitleDescription"><?php echo $this->lang->line('communityngo_title'); ?>
                                    : </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php echo $extra['master']['TitleDescription']; ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_Ename1"><?php echo $this->lang->line('communityngo_name'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php echo $extra['master']['CFullName']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong
                                        id="_nameWithInitial"><?php echo $this->lang->line('communityngo_name_with_initial'); ?>
                                    : </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php echo $extra['master']['CName_with_initials']; ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong
                                        id="_OtherName"><?php echo $this->lang->line('communityngo_memberOtherName'); ?>
                                    : </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php echo $extra['master']['OtherName']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_wasakama"><?php echo $this->lang->line('communityngo_wasakamaName'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php echo date('dS F Y (l)', strtotime($extra['master']['CmWasakamaName'])) ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_Gender"><?php echo $this->lang->line('communityngo_gender'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php echo $extra['master']['name'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_EDOB"><?php echo $this->lang->line('communityngo_dob'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php echo date('dS F Y (l)', strtotime($extra['master']['CDOB'])) ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_NIC"><?php echo $this->lang->line('communityngo_nic'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php echo $extra['master']['CNIC_No']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_BG"><?php echo $this->lang->line('communityngo_bloodGroup'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php echo $extra['master']['BloodDescription']; ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;"><strong><?php echo $this->lang->line('communityngo_email'); ?>: </strong></td>
                            <td style="font-size: 12px;">
                                <?php echo $extra['master']['EmailID'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_CmHeight"><?php echo $this->lang->line('communityngo_height'); ?>
                                    : </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php echo $extra['master']['CmHeight'].' '.'cm'; ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_CmWeight"><?php echo $this->lang->line('communityngo_weight'); ?>
                                    : </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php echo $extra['master']['CmWeight'].' '.'kg'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_MaritialStatus"><?php echo $this->lang->line('communityngo_status'); ?>
                                    : </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php echo $extra['master']['maritalstatus'];  if($extra['master']['maritalstatusID'] == '2'){?>
                            <strong style="color: #095db3;"><?php echo $this->lang->line('communityngo_mrgCertificateNo'); ?>: </strong>
                                <?php echo $extra['master']['MrgCertificateNo']; }?>
                            </td>
                            <?php if($extra['master']['maritalstatusID'] == '2'){?>
                            <td style="font-size: 12px;color:#095db3;"><strong><?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong></td>
                            <td style="font-size: 12px;">
                                <?php echo $extra['master']['MrgRegisteredDate'] ?>
                            </td>
                            <?php } else{ ?>
                                <td colspan="2"></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_EcMobile"><?php echo $this->lang->line('communityngo_TP_MobileNo'); ?>
                                    : </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php
                                if ($extra['master']['AreaCodePrimary'] == 0 || $extra['master']['AreaCodePrimary'] == null) {
                                    echo $extra['master']['CountryCodePrimary'] . ' - ' . $extra['master']['TP_Mobile'];
                                } else {
                                    echo $extra['master']['CountryCodePrimary'] . ' - ' . $extra['master']['AreaCodePrimary'] . ' - ' . $extra['master']['TP_Mobile'];
                                }
                                ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_TP_home"><?php echo $this->lang->line('communityngo_TP_Home'); ?>
                                    : </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php
                                if ($extra['master']['TP_home'] == 0 || $extra['master']['TP_home'] == null) {
                                } else {
                                    if ($extra['master']['CountryCodeSecondary'] == 0 || $extra['master']['CountryCodeSecondary'] == null) {
                                        if ($extra['master']['AreaCodeSecondary'] == 0 || $extra['master']['AreaCodeSecondary'] == null) {
                                            echo $extra['master']['TP_home'];
                                        } else {
                                            echo $extra['master']['AreaCodeSecondary'] . ' - ' . $extra['master']['TP_home'];
                                        }
                                    } else {
                                        if ($extra['master']['AreaCodeSecondary'] == 0 || $extra['master']['AreaCodeSecondary'] == null) {
                                            echo $extra['master']['CountryCodeSecondary'] . ' - ' . $extra['master']['TP_home'];
                                        } else {
                                            echo $extra['master']['CountryCodeSecondary'] . ' - ' . $extra['master']['AreaCodeSecondary'] . ' - ' . $extra['master']['TP_home'];
                                        }
                                    }
                                }

                                ?>
                            </td>
                        </tr>
        <tr>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_passport"><?php echo $this->lang->line('common_passport_number_no'); ?>
                    : </strong>
            </td>
            <td style="font-size: 12px;">
                <?php echo $extra['master']['CmPassportNo']; ?>
                <strong style="color: #095db3;"><?php echo $this->lang->line('common_issue_date'); ?>: </strong>
                <?php echo $extra['master']['CmPassportIssueDate'] ?>
            </td>
            <td style="font-size: 12px;color:#095db3;"><strong><?php echo $this->lang->line('common_expire_date'); ?>: </strong></td>
            <td style="font-size: 12px;">
                <?php echo $extra['master']['CmPassportExpiryDate'] ?>
            </td>
        </tr>
        <tr>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_drivingLicense"><?php echo $this->lang->line('communityngo_drivingLicense'); ?>
                    : </strong>
            </td>
            <td style="font-size: 12px;">
                <?php echo $extra['master']['CmDrivingLicenseNo']; ?>
                <strong style="color: #095db3;"><?php echo $this->lang->line('common_issue_date'); ?>: </strong>
                <?php echo $extra['master']['CmDrvLicIssueDate'] ?>
            </td>
            <td style="font-size: 12px;color:#095db3;"><strong><?php echo $this->lang->line('common_expire_date'); ?>: </strong></td>
            <td style="font-size: 12px;">
                <?php echo $extra['master']['CmDrvLicExpiryDate'] ?>
            </td>
        </tr>
                        <tr style="background-color: rgba(245,208,157,0.56);">
                            <td colspan="4" style="font-size:12px;color: black;font-weight: bold;background-color: rgba(245,208,157,0.56);">Address</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_GS"><?php echo $this->lang->line('communityngo_GS_Division'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php echo $extra['master']['GS_DivisionSt'] . ' - ' . $extra['master']['GS_No'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_Rid"><?php echo $this->lang->line('communityngo_region'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php echo $extra['master']['Description'] ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_HouseNo"><?php echo $this->lang->line('communityngo_houseNo'); ?>
                                    : </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php echo $extra['master']['HouseNo'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_address_P"><?php echo $this->lang->line('communityngo_perAddress'); ?>
                                    : </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php echo $extra['master']['P_Address'] ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_address"><?php echo $this->lang->line('communityngo_contactAddress'); ?>
                                    : </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php echo $extra['master']['C_Address'] ?>
                            </td>
                        </tr>
                        <tr style="background-color: rgba(245,208,157,0.56);">
                            <td colspan="4" style="font-size:12px;color: black;font-weight: bold;background-color: rgba(245,208,157,0.56);">Other Details</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_isAbroad"><?php echo $this->lang->line('communityngo_isAbroad'); ?>
                                    : </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php if($extra['master']['IsAbroad'] == '1'){echo 'Yes'; }else{ echo 'No';} ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_cmCountry"><?php echo $this->lang->line('communityngo_country'); ?>
                                    : </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php echo $extra['master']['CountryDes'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_IsSchooling"><?php echo $this->lang->line('communityngo_IsSchoolCompleted'); ?>
                                    : </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php if($extra['master']['IsSchoolCompleted'] == '1'){echo 'No'; }else{ echo 'Yes';} ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_cmSchool"><?php echo $this->lang->line('communityngo_School'); ?>
                                    : </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php echo $extra['master']['schoolComDes'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_isConverted"><?php echo $this->lang->line('communityngo_isConverted'); ?>
                                    : </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php if($extra['master']['isConverted'] == '1'){echo 'Yes'; }else{ echo 'No';} ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <?php if($extra['master']['isConverted'] == '1'){ ?>
                                <strong id="_converted_place"><?php echo $this->lang->line('communityngo_converted_place'); ?> / <?php echo $this->lang->line('communityngo_ConvertedYear'); ?>
                                    : </strong>
                                <?php }?>
                            </td>
                            <td style="font-size: 12px;">
                                <?php if($extra['master']['isConverted'] == '1'){ ?>

                                <?php echo $extra['master']['ConvertedPlace'] .' / '.$extra['master']['ConvertedYear'] ?>
                                <?php }?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_isVoter"><?php echo $this->lang->line('communityngo_isVoter'); ?>
                                    : </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php if($extra['master']['isVoter'] == '1'){echo 'Yes'; }else{ echo 'No';} ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong
                                        id="_Status"><?php echo $this->lang->line('communityngo_com_member_header_Status'); ?>
                                    : </strong>
                            </td>
                            <td style="font-size: 12px;">
                                <?php if ($extra['master']['isActive'] == 1) {
                                    ?>
                                    <span class="label"
                                          style="background-color:#8bc34a; color: #FFFFFF; font-size: 11px;"><?php echo $this->lang->line('communityngo_Active'); ?><!--Active--></span>
                                    <?php
                                } else {
                                    ?>
                                    <span class="label"
                                          style="background-color: rgba(255, 72, 49, 0.96); color: #FFFFFF; font-size: 11px;"><?php echo $this->lang->line('communityngo_Inactive'); ?><!--inactive--></span>
                                    <?php

                                } ?>
                            </td>
                        </tr>
                        <tr style="">
                            <td colspan="2" style="font-size:12px;color: black;font-weight: bold;">Languages <?php  $language = drill_down_emp_language();if (!empty($language)) {
                                    foreach ($language as $valLang) {  echo $valLang['description'] . ' ,' ; }} ?> Other (Specify) :</td>
                            <td colspan="2" style="font-size: 12px;"><?php  foreach ($extra['cmLanguage'] as $key => $det) { echo $det['description'] . ' &nbsp;'; } ?></td>
                        </tr>
                        <tr style="background-color: rgba(245,208,157,0.56);">
                            <td colspan="4" style="background-color: rgba(245,208,157,0.56);font-size:12px;color: black;font-weight: bold;">Occupation</td>
                        </tr>
                        <?php
                        foreach ($extra['cmOccupation'] as $key => $row) {

                            if ($row['OccTypeID'] == 1) {
                                if ($row['isActive'] == 0 || $row['isActive'] == null) {
                                    $isActiveYN = '';
                                } else {
                                    $isActiveYN = '-  <lable style="color: green;">' . $this->lang->line('communityngo_Active').'</lable>';
                                }
                                echo'<tr><td colspan="4" style="font-size:12px;color: black;font-weight: bold;">Schooling '.$isActiveYN.'</td></tr>';
                                $school = $row['schoolComDes'];
                                $schType = $row['schoolTypeDes'];
                                $Grade = $row['gradeComDes'];
                                $Medium = $row['Medium'];
                                if (!empty($row['DateFrom']) && $row['DateFrom'] != '0000-00-00') {
                                    $DateFrom = format_date($row['DateFrom']) . ' - ' . format_date($row['DateTo']);
                                } else {
                                    $DateFrom = '';
                                }

                                echo'<tr><td style="font-size: 12px;color:#095db3;">
                                         '.$this->lang->line('communityngo_School').' </td><td style="font-size: 12px;">';
                                echo $school . ' ('.$schType .')';
                                echo '</td><td style="font-size: 12px;color:#095db3;">'.$this->lang->line('communityngo_SchoolGrade').' </td><td style="font-size: 12px;">'.$Grade .'</td>';
                                echo '</tr><tr><td style="font-size: 12px;color:#095db3;">'.$this->lang->line('communityngo_medium').'  </td><td style="font-size: 12px;">'.$Medium .'</td>';
                                echo '</td><td style="font-size: 12px;color:#095db3;">'.$this->lang->line('common_period').'  </td><td style="font-size: 12px;">'.$DateFrom .'</td></tr>';

                            }
                            else {

                                if ($row['isPrimary'] == 0 || $row['isPrimary'] == null) {
                                    $isPrimaryYN = '';
                                } else {
                                    $isPrimaryYN = '- <lable style="color: green;">' . $this->lang->line('communityngo_headPrimary').'</lable>';
                                }
                                echo'<tr><td colspan="4" style="font-size:12px;color: black;font-weight: bold;">Professional '.$isPrimaryYN.'</td></tr>';
                                $JobCategory = $row['JobCatDescription'];
                                if ($row['jobDescription'] == '' || $row['jobDescription'] == null) {
                                    $jobDescription = '';
                                } else {
                                    $jobDescription = 'Job Description : ' . $row['jobDescription'];
                                }

                                if ($row['Specialization'] == '' || $row['Specialization'] == null) {
                                    $JobSpecialization = '';
                                } else {
                                    $JobSpecialization = 'Job Specialization : ' . $row['Specialization'];
                                }

                                if ($row['WorkingPlace'] == '' || $row['WorkingPlace'] == null) {
                                    $WorkingPlace = '';
                                } else {
                                    $WorkingPlace = 'Working Place : ' . $row['WorkingPlace'];
                                }

                                if ($row['Address'] == '' || $row['Address'] == null) {
                                    $Address = '';
                                } else {
                                    $Address = 'Address : ' . $row['Address'];
                                }

                                if (!empty($row['DateFrom']) && $row['DateFrom'] != '0000-00-00') {
                                    $DateFrom = format_date($row['DateFrom']) . ' - ' . format_date($row['DateTo']);
                                } else {
                                    $DateFrom = '';
                                }


                                echo'<tr><td style="font-size: 12px;color:#095db3;">
                                         '.$this->lang->line('communityngo_Job_Category').' </td><td style="font-size: 12px;">';
                                echo $JobCategory . ' ('.$JobSpecialization .')';
                                echo '</td><td style="font-size: 12px;color:#095db3;">'.$this->lang->line('communityngo_Job_WorkingPlace').' </td><td style="font-size: 12px;">'.$WorkingPlace .'</td>';
                                echo '</tr>
                                 <tr><td style="font-size: 12px;color:#095db3;">'.$this->lang->line('communityngo_Job_Address').'  </td><td style="font-size: 12px;">'.$Address .'</td>';
                                echo '</td><td style="font-size: 12px;color:#095db3;">'.$this->lang->line('common_period').'  </td><td style="font-size: 12px;">'.$DateFrom .'</td></tr>';

                            }

                        }
                        ?>
                        <tr style="background-color: rgba(245,208,157,0.56);">
                            <td colspan="4" style="font-size:12px;color: black;font-weight: bold;background-color: rgba(245,208,157,0.56);">Qualifications</td>
                        </tr>

                        <?php
                        if(!empty($extra['cmQualification'])){
                          ?>
                            <tr>
                                <td style="font-size: 12px;color:#095db3;">
                                    <strong id="_Qualification"><?php echo $this->lang->line('communityngo_QualificationType'); ?>
                                         </strong>
                                </td>
                                <td style="font-size: 12px;color:#095db3;">
                                    <strong id="_qYear"><?php echo $this->lang->line('communityngo_Year'); ?> </strong>
                                </td>

                            </tr>
                            <?php
                        foreach ($extra['cmQualification'] as $key => $row) {
                            ?>
                            <tr>
                                <td style="font-size: 12px;">
                                    <?php echo $row['DegreeDescription']; ?>
                                </td>
                                <td style="font-size: 12px;">
                                    <?php echo $row['Year']; ?>
                                </td>
                            </tr>
                            <?php
                        }
                        }
                        ?>
                        <tr style="background-color: rgba(245,208,157,0.56);">
                            <td colspan="4" style="font-size:12px;color: black;font-weight: bold;background-color: rgba(245,208,157,0.56);">Permanent Sickness</td>
                        </tr>
                        <?php
                        if(!empty($extra['cmSickness'])){
                            ?>
                            <tr>
                                <td style="font-size: 12px;color:#095db3;">
                                    <strong id="_sickDescription"><?php echo $this->lang->line('common_description'); ?>
                                        : </strong>
                                </td>
                                <td style="font-size: 12px;color:#095db3;">
                                    <strong id="_sFrom"><?php echo $this->lang->line('communityngo_sickness_from'); ?> </strong>
                                </td>
                                <td style="font-size: 12px;color:#095db3;">
                                    <strong id="_medicalCondition"><?php echo $this->lang->line('communityngo_medicalcondiation'); ?></strong>
                                </td>
                                <td style="font-size: 12px;color:#095db3;">
                                    <strong id="_mExpenses"><?php echo $this->lang->line('CommunityNgo_member_expenses'); ?>: </strong>
                                </td>
                            </tr>
                            <?php
                        foreach ($extra['cmSickness'] as $key => $det) {
                            if ($det['startedFrom'] == 0 || $det['startedFrom'] == null) {
                                $startedFrom = '';
                            } else {
                                $startedFrom =  $det['startedFrom'];
                            }

                            if ($det['monthlyExpenses'] == 0 || $det['monthlyExpenses'] == null) {
                                $monthlyExpenses = '';
                            } else {
                                $monthlyExpenses =  $currencyCode . ' ' . number_format($det['monthlyExpenses'],$decimals);
                            }
                            ?>
                            <tr>
                                <td style="font-size: 12px;">
                                    <?php echo $det['sickDescription']; ?>
                                </td>
                                <td style="font-size: 12px;">
                                    <?php echo $startedFrom; ?>
                                </td>
                                <td style="font-size: 12px;">
                                    <?php echo $det['medicalCondition']; ?>
                                </td>
                                <td style="font-size: 12px;">
                                    <?php echo $monthlyExpenses; ?>
                                </td>
                            </tr>
                            <?php
                        }
                        }
                        ?>
                        <tr style="background-color: rgba(245,208,157,0.56);">
                            <td colspan="4" style="font-size:12px;color: black;font-weight: bold;background-color: rgba(245,208,157,0.56);">Property Details</td>
                        </tr>
                        <?php
                        if(!empty($extra['cmVehicleConfig'])) {
                            ?>
                            <tr>
                                <td style="font-size: 12px;">
                                    <strong id="_pType" style="color:#095db3;font-size: 12px;"><?php echo $this->lang->line('common_type'); ?></strong>
                                </td>
                                <td style="font-size: 12px;color:#095db3;">
                                    <strong id="_pDescription"><?php echo $this->lang->line('common_description'); ?> </strong>
                                </td>
                                <td style="font-size: 12px;color:#095db3;">
                                    <strong id="_own_lease"><?php echo $this->lang->line('communityngo_property_status'); ?></strong>
                                </td>
                                <td style="font-size: 12px;color:#095db3;">
                                    <strong id="_pValue"><?php echo $this->lang->line('common_value'); ?> </strong>
                                </td>
                            </tr>
                            <?php
                            foreach ($extra['cmVehicleConfig'] as $key => $det) {
                                if ($det['PropertyDes'] == null) {
                                    $PropertyDes = '';
                                } else {
                                    $PropertyDes = $det['PropertyDes'];
                                }
                                if ($det['vehiDescription'] == null) {
                                    $vehiDesc = '';
                                } else {
                                    $vehiDesc = $det['vehiDescription'];
                                }
                                if ($det['vehiStatus'] == 0 || $det['vehiStatus'] == null) {
                                    $vehiStatus = '<span class="label label-success">Own</span>';
                                } else {
                                    $vehiStatus = '<span class="label label-warning">Lease</span>';
                                }

                                if ($det['propertyValue'] == 0 || $det['propertyValue'] == null) {
                                    $propertyValue = '';
                                } else {
                                    $propertyValue = $currencyCode . ' ' . number_format($det['propertyValue'],$decimals);
                                }

                                if ($det['vehiRemark'] == '') {
                                    $vehiRemarks = '';
                                } else {
                                    $vehiRemarks = $det['vehiRemark'];
                                }
                                ?>
                                <tr>
                                    <td style="font-size: 12px;">
                                        <strong id="_pType"><?php echo $PropertyDes; ?></strong>
                                    </td>
                                    <td style="font-size: 12px;">
                                        <strong id="_pDescription"><?php echo $vehiDesc; ?></strong>
                                    </td>
                                    <td style="font-size: 12px;">
                                        <strong id="_own_lease"><?php echo $vehiStatus; ?></strong>
                                    </td>
                                    <td style="font-size: 12px;">
                                        <strong id="_pValue"><?php echo $propertyValue; ?></strong>
                                    </td>
                                </tr>
                                <?php
                            }}

                        if(!empty($extra['cmHelpReqConGv']) || !empty($extra['cmHelpReqConPv']) || !empty($extra['cmHelpReqConCs']) || !empty($extra['cmHelpReqComOther'])) {
                            ?>
                            <tr style="background-color: rgba(245,208,157,0.56);">
                                <td colspan="4" style="font-size:12px;color: black;font-weight: bold;background-color: rgba(245,208,157,0.56);">Help Requirement</td>
                            </tr>
                            <?php
                            if(!empty($extra['cmHelpReqConGv'])) {
                                ?>
                                <tr>
                                    <td colspan="4" style="color: #095db3;font-size: 12px;">Requirement Type : Government Help</td>
                                </tr>
                                <?php
                                $gv = 1;
                                foreach ($extra['cmHelpReqConGv'] as $key => $det) {

                                    if ($det['hlprDescription'] == null) {
                                        $helpDesc = '';
                                    } else {
                                        $helpDesc = $det['hlprDescription'];
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="4" style="color: black;font-size: 12px;"><?php echo $gv .' '. $det['helpRequireDesc']; ?> &nbsp;<small><?php echo $helpDesc; ?><small></td>
                                    </tr>
                                    <?php
                                    $gv++;
                                }}
                            if(!empty($extra['cmHelpReqConPv'])) {
                                ?>
                                <tr>
                                    <td colspan="4" style="color: #095db3;font-size: 12px;">Requirement Type : Private Help</td>
                                </tr>
                                <?php
                                $pr = 1;
                                foreach ($extra['cmHelpReqConPv'] as $key => $det) {

                                    if ($det['hlprDescription'] == null) {
                                        $helpDesc = '';
                                    } else {
                                        $helpDesc = $det['hlprDescription'];
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="4" style="color: black;font-size: 12px;"><?php echo $pr .' '. $det['helpRequireDesc']; ?> &nbsp;<small><?php echo $helpDesc; ?><small></td>
                                    </tr>
                                    <?php
                                    $pr++;
                                }}
                            if(!empty($extra['cmHelpReqConCs'])) {
                                ?>
                                <tr>
                                    <td colspan="4" style="color: #095db3;font-size: 12px;">Requirement Type : Consultancy</td>
                                </tr>
                                <?php
                                $cn = 1;
                                foreach ($extra['cmHelpReqConCs'] as $key => $det) {

                                    if ($det['hlprDescription'] == null) {
                                        $helpDesc = '';
                                    } else {
                                        $helpDesc = $det['hlprDescription'];
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="4" style="color: black;font-size: 12px;"><?php echo $cn .' '. $det['helpRequireDesc']; ?> &nbsp;<small><?php echo $helpDesc; ?><small></td>
                                    </tr>
                                    <?php $cn++;
                                } }
                            if(!empty($extra['cmHelpReqComOther'])) {
                                ?>
                                <tr>
                                    <td colspan="4" style="color: #095db3;font-size: 12px;">Requirement Type : Other</td>
                                </tr>
                                <?php
                                $otr = 1;
                                foreach ($extra['cmHelpReqComOther'] as $key => $det) {

                                    if ($det['hlprDescription'] == null) {
                                        $helpDesc = '';
                                    } else {
                                        $helpDesc = $det['hlprDescription'];
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="4" style="color: black;font-size: 12px;"><?php echo $otr .' '. $det['helpRequireDesc']; ?> &nbsp;<small><?php echo $helpDesc; ?><small></td>
                                    </tr>
                                    <?php $otr++;
                                } }

                            ?>
                        <?php } ?>

                        <?php
                        if(!empty($extra['cmWillingToHelp'])) {
                            ?>
                            <tr style="background-color: rgba(245,208,157,0.56);">
                                <td colspan="4" style="font-size:12px;color: black;font-weight: bold;background-color: rgba(245,208,157,0.56);">Willing to Help</td>
                            </tr>

                            <?php
                            $willing = 1;
                            foreach ($extra['cmWillingToHelp'] as $key => $det) {

                                if ($det['helpComments'] == null) {
                                    $helpDesc = '';
                                } else {
                                    $helpDesc = 'Comments :' . $det['helpComments'];
                                }
                                ?>
                                <tr>
                                    <td colspan="4" style="color: black;font-size: 12px;"><?php echo $willing .' '. $det['helpCategoryDes']; ?> &nbsp;<small><?php echo $helpDesc; ?><small></td>
                                </tr>
                                <?php
                                $willing++;
                            }
                        }
                        ?>
                        <?php
                        if ($extra['master']['isActive'] == 0) { ?>
                            <tr>
                                <td style="font-size: 12px;">
                                    <strong id="_Reason"><?php echo $this->lang->line('communityngo_deactivatedFor'); ?>
                                        : </strong>
                                </td>
                                <td style="font-size: 12px;">
                                    <?php if ($extra['master']['DeactivatedFor'] == 1) {
                                        echo 'Death';
                                    } else if ($extra['master']['DeactivatedFor'] == 2) {
                                        echo 'Migrate';
                                    } ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 12px;">
                                    <strong id="_Date"><?php echo $this->lang->line('communityngo_deactivatedDate'); ?>
                                        : </strong>
                                </td>
                                <td style="font-size: 12px;">
                                    <?php echo date('dS F Y ', strtotime($extra['master']['deactivatedDate'])) ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 12px;">
                                    <strong
                                            id="_Comment"><?php echo $this->lang->line('communityngo_deactivatedComment'); ?>
                                        : </strong>
                                </td>
                                <td style="font-size: 12px;">
                                    <?php echo $extra['master']['deactivatedComment'] ?>
                                </td>
                            </tr>

                            <?php
                        }
                        ?>
        <tr style="background-color: rgba(245,208,157,0.56);">
            <td colspan="4" style="font-size:12px;color: black;font-weight: bold;background-color: rgba(245,208,157,0.56);"><?php echo $this->lang->line('communityngo_com_mem_header_parent'); ?></td>
        </tr>
        <tr>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memFather"><?php echo $this->lang->line('communityNgo_is_alive'); ?> (<?php echo $this->lang->line('communityngo_com_member_father'); ?>): </strong>
            </td>
            <td style="font-size: 12px;">
                <?php if($extra['master']['fatherIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
            </td>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memMother"><?php echo $this->lang->line('communityNgo_is_alive'); ?> (<?php echo $this->lang->line('communityngo_com_member_mother'); ?>): </strong>
            </td>
            <td style="font-size: 12px;">
                <?php if($extra['master']['motherIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
            </td>
        </tr>
        <tr>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memFather"><?php echo $this->lang->line('communityngo_name'); ?>: </strong>
            </td>
            <td style="font-size: 12px;">
                <?php echo $extra['master']['cfFullName']; ?>
            </td>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memMother"><?php echo $this->lang->line('communityngo_name'); ?>: </strong>
            </td>
            <td style="font-size: 12px;">
                <?php echo $extra['master']['cmFullName']; ?>
            </td>
        </tr>
        <tr>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memFather"><?php echo $this->lang->line('communityNgo_born_country'); ?>: </strong>
            </td>
            <td style="font-size: 12px;">
                <?php echo $extra['master']['fCountry']; ?>
            </td>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memMother"><?php echo $this->lang->line('communityNgo_born_country'); ?>: </strong>
            </td>
            <td style="font-size: 12px;">
                <?php echo $extra['master']['mCountry']; ?>
            </td>
        </tr>
        <tr>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memFather"><?php echo $this->lang->line('communityNgo_born_area'); ?>: </strong>
            </td>
            <td style="font-size: 12px;">
                <?php echo $extra['master']['fArea']; ?>
            </td>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memMother"><?php echo $this->lang->line('communityNgo_born_area'); ?>: </strong>
            </td>
            <td style="font-size: 12px;">
                <?php echo $extra['master']['mArea']; ?>
            </td>
        </tr>
        <tr>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memFather"><?php echo $this->lang->line('communityngo_dob'); ?>: </strong>
            </td>
            <td style="font-size: 12px;">
                <?php echo $extra['master']['cFatherDOB']; ?>
            </td>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memMother"><?php echo $this->lang->line('communityngo_dob'); ?>: </strong>
            </td>
            <td style="font-size: 12px;">
                <?php echo $extra['master']['cMotherDOB']; ?>
            </td>
        </tr>
        <tr>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memFather"><?php echo $this->lang->line('communityNgo_Sbc_no'); ?>/<?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong>
            </td>
            <td style="font-size: 12px;">
                <?php echo $extra['master']['cfBC_No']; ?> / <?php echo $extra['master']['cfBCDate']; ?>
            </td>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memMother"><?php echo $this->lang->line('communityNgo_Sbc_no'); ?>/<?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong>
            </td>
            <td style="font-size: 12px;">
                <?php echo $extra['master']['cmBC_No']; ?> / <?php echo $extra['master']['cmBCDate'];?>
            </td>
        </tr>
        <tr>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memFather"><?php echo $this->lang->line('communityngo_nic'); ?>: </strong>
            </td>
            <td style="font-size: 12px;">
                <?php echo $extra['master']['cfNIC_No']; ?>
            </td>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memMother"><?php echo $this->lang->line('communityngo_nic'); ?>: </strong>
            </td>
            <td style="font-size: 12px;">
                <?php echo $extra['master']['cmNIC_No']; ?>
            </td>
        </tr>
        <tr>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memFather"><?php echo $this->lang->line('communityNgo_dateOfDeath'); ?>: </strong>
            </td>
            <td style="font-size: 12px;">
                <?php if($extra['master']['fatherIsAlive'] == '0'){ echo $extra['master']['cfDateOfDeath']; } else { echo '-'; } ?>
            </td>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memMother"><?php echo $this->lang->line('communityNgo_dateOfDeath'); ?>: </strong>
            </td>
            <td style="font-size: 12px;">
                <?php if($extra['master']['motherIsAlive'] == '0'){echo $extra['master']['cmDateOfDeath'];} else { echo '-';} ?>
            </td>
        </tr>
        <tr>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memFather"><?php echo $this->lang->line('communityNgo_Sdc_no'); ?>/<?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong>
            </td>
            <td style="font-size: 12px;">
                <?php if($extra['master']['fatherIsAlive'] == '0'){echo $extra['master']['cfDC_No']; ?> / <?php echo $extra['master']['cfDCDate'];} else { echo '-';}?>
            </td>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memMother"><?php echo $this->lang->line('communityNgo_Sdc_no'); ?>/<?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong>
            </td>
            <td style="font-size: 12px;">
                <?php if($extra['master']['motherIsAlive'] == '0'){ echo $extra['master']['cmDC_No']; ?> / <?php echo $extra['master']['cmDCDate'];} else { echo '-';}?>
            </td>
        </tr>
        <tr>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memFather"><?php echo $this->lang->line('communityngo_Job'); ?>: </strong>
            </td>
            <td style="font-size: 12px;">
                <?php echo $extra['master']['fJobDes']; ?>
            </td>
            <td style="font-size: 12px;color:#095db3;">
                <strong id="_memMother"><?php echo $this->lang->line('communityngo_Job'); ?>: </strong>
            </td>
            <td style="font-size: 12px;">
                <?php echo $extra['master']['mJobDes']; ?>
            </td>
        </tr>
        <tr style="background-color: rgba(245,208,157,0.56);">
            <td colspan="4" style="font-size:12px;color: black;font-weight: bold;background-color: rgba(245,208,157,0.56);"><?php echo $this->lang->line('communityngo_com_mem_header_grandparent'); ?></td>
        </tr>
        <tr>
            <td colspan="4">
                <table border="0">
                    <tr>
                        <td style="font-size: 12px;">
                        </td>
                        <td style="font-size: 12px;color:#095db3;" colspan="2">
                            <b><?php echo $this->lang->line('communityngo_fatherSide_grandparent'); ?> </b>
                        </td>
                        <td style="font-size: 12px;color:#095db3;" colspan="2">
                            <b><?php echo $this->lang->line('communityngo_motherSide_grandparent'); ?> </b>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;">
                        </td>
                        <td style="font-size: 12px;font-weight:bold;">
                            <strong><?php echo $this->lang->line('communityngo_com_member_gfather'); ?> </strong>
                        </td>
                        <td style="font-size: 12px;font-weight:bold;">
                            <strong><?php echo $this->lang->line('communityngo_com_member_gmother'); ?> </strong>
                        </td>
                        <td style="font-size: 12px;font-weight:bold;">
                            <strong><?php echo $this->lang->line('communityngo_com_member_gfather'); ?> </strong>
                        </td>
                        <td style="font-size: 12px;font-weight:bold;">
                            <strong><?php echo $this->lang->line('communityngo_com_member_gmother'); ?> </strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grandPrts"><?php echo $this->lang->line('communityNgo_is_alive'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cf_GrandFIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cf_GrandMIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cm_GrandFIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cm_GrandMIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grandPrts"><?php echo $this->lang->line('communityngo_name'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cf_GrandFFullName']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cf_GrandMFullName']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cm_GrandFFullName']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cm_GrandMFullName']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grandPrts"><?php echo $this->lang->line('communityNgo_born_country'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['fgpCountry']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['fgmCountry']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['mgpCountry']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['mgmCountry']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grandPrts"><?php echo $this->lang->line('communityNgo_born_area'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['fgpArea']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['fgmArea']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['mgpArea']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['mgmArea']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grandPrts"><?php echo $this->lang->line('communityngo_dob'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cf_GrandFDOB']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cf_GrandMDOB']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cm_GrandFDOB']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cm_GrandMDOB']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grandPrts"><?php echo $this->lang->line('communityNgo_Sbc_no'); ?>/<?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cf_GrandFBC_No']; ?> / <?php echo $extra['master']['cf_GrandFBCDate']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cf_GrandMBC_No']; ?> / <?php echo $extra['master']['cf_GrandMBCDate']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cm_GrandFBC_No']; ?> / <?php echo $extra['master']['cm_GrandFBCDate']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cm_GrandMBC_No']; ?> / <?php echo $extra['master']['cm_GrandMBCDate']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grandPrts"><?php echo $this->lang->line('communityngo_nic'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cf_GrandFNIC_No']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cf_GrandMNIC_No']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cm_GrandFNIC_No']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cm_GrandMNIC_No']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grandPrts"><?php echo $this->lang->line('communityNgo_dateOfDeath'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cf_GrandFIsAlive'] == '0'){ echo $extra['master']['cf_GrandFDateOfDeath']; } else { echo '-'; } ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cf_GrandMIsAlive'] == '0'){ echo $extra['master']['cf_GrandMDateOfDeath']; } else { echo '-'; } ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cm_GrandFIsAlive'] == '0'){ echo $extra['master']['cm_GrandFDateOfDeath']; } else { echo '-'; } ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cm_GrandMIsAlive'] == '0'){ echo $extra['master']['cm_GrandMDateOfDeath']; } else { echo '-'; } ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grandPrts"><?php echo $this->lang->line('communityNgo_Sdc_no'); ?>/<?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cf_GrandFIsAlive'] == '0'){echo $extra['master']['cf_GrandFDC_No']; ?> / <?php echo $extra['master']['cf_GrandMDCDate'];} else { echo '-';}?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cf_GrandMIsAlive'] == '0'){echo $extra['master']['cf_GrandMDC_No']; ?> / <?php echo $extra['master']['cf_GrandMDCDate'];} else { echo '-';}?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cm_GrandFIsAlive'] == '0'){ echo $extra['master']['cm_GrandFDC_No']; ?> / <?php echo $extra['master']['cm_GrandFDCDate'];} else { echo '-';}?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cm_GrandMIsAlive'] == '0'){ echo $extra['master']['cm_GrandMDC_No']; ?> / <?php echo $extra['master']['cm_GrandMDCDate'];} else { echo '-';}?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grandPrts"><?php echo $this->lang->line('communityngo_Job'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['fgpJobDes']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['fgmJobDes']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['mgpJobDes']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['mgmJobDes']; ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style="background-color: rgba(245,208,157,0.56);">
            <td colspan="4" style="font-size:12px;color: black;font-weight: bold;background-color: rgba(245,208,157,0.56);"><?php echo $this->lang->line('communityngo_com_mem_header_great-grandparent'); ?></td>
        </tr>
        <tr>
            <td colspan="4">
                <table border="0">
                    <tr>
                        <td style="font-size: 12px;">
                        </td>
                        <td style="font-size: 12px;color:#095db3;" colspan="2">
                            <b><?php echo $this->lang->line('communityngo_fatherSideGrt_grandparent'); ?> </b>
                        </td>
                        <td style="font-size: 12px;color:#095db3;" colspan="2">
                            <b><?php echo $this->lang->line('communityngo_motherSideGrt_grandparent'); ?> </b>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;">
                        </td>
                        <td style="font-size: 12px;font-weight:bold;">
                            <strong><?php echo $this->lang->line('communityngo_com_member_ggfather'); ?> </strong>
                        </td>
                        <td style="font-size: 12px;font-weight:bold;">
                            <strong><?php echo $this->lang->line('communityngo_com_member_ggmother'); ?> </strong>
                        </td>
                        <td style="font-size: 12px;font-weight:bold;">
                            <strong><?php echo $this->lang->line('communityngo_com_member_ggfather'); ?> </strong>
                        </td>
                        <td style="font-size: 12px;font-weight:bold;">
                            <strong><?php echo $this->lang->line('communityngo_com_member_ggmother'); ?> </strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityNgo_is_alive'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cf_grt_GrandFIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cf_grt_GrandMIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cm_grt_GrandFIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cm_grt_GrandMIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityngo_name'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cf_grt_GrandFFullName']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cf_grt_GrandMFullName']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cm_grt_GrandFFullName']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cm_grt_GrandMFullName']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityNgo_born_country'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['fgptCountry']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['fgmtCountry']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['mgptCountry']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['mgmtCountry']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityNgo_born_area'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['fgptArea']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['fgmtArea']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['mgptArea']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['mgmtArea']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityngo_dob'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cf_grt_GrandFDOB']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cf_grt_GrandMDOB']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cm_grt_GrandFDOB']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cm_grt_GrandMDOB']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityNgo_Sbc_no'); ?>/<?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cf_grt_GrandFBC_No']; ?> / <?php echo $extra['master']['cf_grt_GrandFBCDate']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cf_grt_GrandMBC_No']; ?> / <?php echo $extra['master']['cf_grt_GrandMBCDate']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cm_grt_GrandFBC_No']; ?> / <?php echo $extra['master']['cm_grt_GrandFBCDate']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cm_grt_GrandMBC_No']; ?> / <?php echo $extra['master']['cm_grt_GrandMBCDate']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityngo_nic'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cf_grt_GrandFNIC_No']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cf_grt_GrandMNIC_No']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cm_grt_GrandFNIC_No']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['cm_grt_GrandMNIC_No']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityNgo_dateOfDeath'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cf_grt_GrandFIsAlive'] == '0'){ echo $extra['master']['cf_grt_GrandFDateOfDeath']; } else { echo '-'; } ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cf_grt_GrandMIsAlive'] == '0'){ echo $extra['master']['cf_grt_GrandMDateOfDeath']; } else { echo '-'; } ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cm_grt_GrandFIsAlive'] == '0'){ echo $extra['master']['cm_grt_GrandFDateOfDeath']; } else { echo '-'; } ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cm_grt_GrandMIsAlive'] == '0'){ echo $extra['master']['cm_grt_GrandMDateOfDeath']; } else { echo '-'; } ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityNgo_Sdc_no'); ?>/<?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cf_grt_GrandFIsAlive'] == '0'){echo $extra['master']['cf_grt_GrandFDC_No']; ?> / <?php echo $extra['master']['cf_grt_GrandMDCDate'];} else { echo '-';}?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cf_grt_GrandMIsAlive'] == '0'){echo $extra['master']['cf_grt_GrandMDC_No']; ?> / <?php echo $extra['master']['cf_grt_GrandMDCDate'];} else { echo '-';}?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cm_grt_GrandFIsAlive'] == '0'){ echo $extra['master']['cm_grt_GrandFDC_No']; ?> / <?php echo $extra['master']['cm_grt_GrandFDCDate'];} else { echo '-';}?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php if($extra['master']['cm_grt_GrandMIsAlive'] == '0'){ echo $extra['master']['cm_grt_GrandMDC_No']; ?> / <?php echo $extra['master']['cm_grt_GrandMDCDate'];} else { echo '-';}?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;color:#095db3;">
                            <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityngo_Job'); ?>: </strong>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['fgptJobDes']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['fgmtJobDes']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['mgptJobDes']; ?>
                        </td>
                        <td style="font-size: 12px;">
                            <?php echo $extra['master']['mgmtJobDes']; ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

            </tbody>
                    </table>

            </div>
            <br>
            <div class="row">
                <div class="col-md-12 animated zoomIn">
                    <header class="head-title">
                        <h4>RECORD DETAILS</h4>
                    </header>
                </div>
            </div>
            <table class="table table-striped" id="recordInfoTable"
                   style="background-color: #ffffff;width: 100%">
                <tfoot>
                <tr>
                    <td style="font-size: 12px;">
                        <strong id="_CD"><?php echo $this->lang->line('communityngo_CreatedDate'); ?>:</strong>
                    </td>
                    <td style="font-size: 12px;">
                        <?php
                        echo $extra['master']['createdDateTime'];
                        ?>
                    </td>
                    <td style="font-size: 12px;" colspan="2"></td>
                </tr>
                <tr>
                    <td style="font-size: 12px;">
                        <strong id="_MCB"><?php echo $this->lang->line('communityngo_MemberCreatedBy'); ?>:</strong>
                    </td>
                    <td style="font-size: 12px;">
                        <?php echo $extra['master']['createdUserName'] ?>
                    </td>
                    <td style="font-size: 12px;" colspan="2"></td>
                </tr>
                <tr>
                    <td style="font-size: 12px;">
                        <strong id="_CD"><?php echo $this->lang->line('communityngo_LastUpdated'); ?>:</strong>
                    </td>
                    <td style="font-size: 12px;">
                        <?php echo $extra['master']['modifiedDateTime'] ?>
                    </td>
                    <td style="font-size: 12px;" colspan="2"></td>
                </tr>
                </tfoot>
            </table>
    </div>

    <?php
}
?>

<script>

    a_link=  "<?php echo site_url('CommunityNgo/load_community_member_details'); ?>/<?php echo $extra['master']['Com_MasterID'] ?>";

    $("#a_link").attr("href",a_link);
    $('.review').removeClass('hide');

</script>