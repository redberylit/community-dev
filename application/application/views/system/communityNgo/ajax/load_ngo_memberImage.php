<?php
$primaryLanguage = getPrimaryLanguage();

$this->lang->load('communityngo', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);

$date_format_policy = date_format_policy();
$current_date = current_format_date();
$companyCode = $this->common_data['company_data']['company_code'];
$currencyCode = $this->common_data['company_data']['company_default_currency'];
$decimals = $this->common_data['company_data']['company_default_decimal'];
?>

    <style>
        #profileInfoTable tr td:first-child {
            color: #095db3;
        }
        #profileInfoTable tr td:nth-child(3) {
            color: #095db3;
        }
        #profileInfoTable tr td:nth-child(2) {
            font-weight: bold;
        }
        #profileInfoTable tr td:nth-child(4) {
            font-weight: bold;
        }

        #recordInfoTable tr td:first-child {
            color: #095db3;
        }

        #recordInfoTable tr td:nth-child(2) {
            font-weight: bold;
        }

        .title {
            color: #aaa;
            padding: 4px 10px 0 0;
            font-size: 13px;
        }

        .nav-tabs > li > a {
            font-size: 11px;
            line-height: 30px;
            height: 30px;
            position: relative;
            padding: 0 25px;
            float: left;
            display: block;
            /*color: rgb(44, 83, 158);*/
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: bold;
            text-align: center;
            text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.3);
            color: rgb(130, 130, 130);
        }

        .nav-tabs > li > a:hover {
            background: rgb(230, 231, 234);
            font-size: 12px;
            line-height: 30px;
            height: 30px;
            position: relative;
            padding: 0 25px;
            float: left;
            display: block;
            /*color: rgb(44, 83, 158);*/
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: bold;
            text-align: center;
            border-radius: 3px 3px 0 0;
            border-color: transparent;
        }

        .nav-tabs > li.active > a,
        .nav-tabs > li.active > a:hover,
        .nav-tabs > li.active > a:focus {
            color: #5e7bf1;
            cursor: default;
            background-color: #fff;
            font-weight: bold;
            border-bottom: 3px solid #095db3;
        }
    </style>
<?php
if (!empty($master)) {
    ?>
    <?php if ($master['isActive'] == 1) {
        ?>

        <?php
        $company_id = current_companyID();
        $page = $this->db->query("SELECT createPageLink FROM srp_erp_templatemaster
                              LEFT JOIN srp_erp_templates ON srp_erp_templatemaster.TempMasterID = srp_erp_templates.TempMasterID
                              WHERE srp_erp_templates.FormCatID = 530 AND companyID={$company_id}
                              ORDER BY srp_erp_templatemaster.FormCatID")->row('createPageLink');
        ?>

        <div class="row">
            <div class="col-md-9">
            </div>
            <div class="col-md-3 text-right">
                <button type="button" class="btn-xs btn-primary CA_Alter_btn"
                        onclick="fetchPage('<?php echo $page; ?>','<?php echo $master['Com_MasterID'] ?>','Edit Member - <?php echo $master['MemberCode'] ?> |  <?php echo $master['CName_with_initials']; ?>','Community Member')">
                    <span title="" rel="tooltip" class="glyphicon glyphicon-pencil" data-original-title="Edit"></span>
                    <?php echo $this->lang->line('common_edit'); ?>
                </button> &nbsp;
                <button type="button" class="btn-xs btn-primary CA_Alter_btn"
                        onclick="memberReportPdf('<?php echo $master['Com_MasterID']; ?>','Print Details - ','<?php echo $master['MemberCode']; ?>')">
                    <span title="" rel="tooltip" class="glyphicon glyphicon-print" data-original-title="Edit"></span>
                    <?php echo $this->lang->line('common_print'); ?>
                </button> &nbsp;
                <button type="button" class="btn-xs btn-primary pull-right CA_Alter_btn"
                        onclick="memberFillupPdf()">
                    <span title="" rel="tooltip" class="glyphicon glyphicon-duplicate" data-original-title="Fill up form"></span>
                    <?php echo $this->lang->line('communityngo_fillup_form'); ?>
                </button>
            </div>
        </div>
        <?php
    } else {
    } ?>

    <br>
    <ul class="nav nav-tabs" id="main-tabs">
        <li class="active"><a href="#about" data-toggle="tab"><i class="fa fa-television"></i>About</a></li>
        <li><a class="CA_Alter_btn" href="#files" onclick="member_attachments()" data-toggle="tab"><i class="fa fa-television"></i>Attachments
            </a></li>
    </ul>


    <div class="tab-content">
            <input type="hidden" id="editCom_MasterID" name="editCom_MasterID" value="<?php echo $master['Com_MasterID'] ?>">
        <div class="tab-pane active" id="about">
            <br>
            <div class="row">
                <div class="col-md-12 animated zoomIn">
                    <header class="head-title">
                        <h2>MEMBER NAME AND DETAIL</h2>
                    </header>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-9">
                    <table class="table table-striped" id="profileInfoTable"
                           style="background-color: #ffffff; width: 100%">
                        <tbody>
                        <tr>
                            <td>
                                <strong id="_TitleDescription"><?php echo $this->lang->line('communityngo_title'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php echo $master['TitleDescription']; ?>
                            </td>
                            <td>
                                <strong id="_Ename1"><?php echo $this->lang->line('communityngo_name'); ?>: </strong>
                            </td>
                            <td>
                                <?php echo $master['CFullName']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong
                                    id="_nameWithInitial"><?php echo $this->lang->line('communityngo_name_with_initial'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php echo $master['CName_with_initials']; ?>
                            </td>
                            <td>
                                <strong
                                    id="_OtherName"><?php echo $this->lang->line('communityngo_memberOtherName'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php echo $master['OtherName']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong id="_EDOB"><?php echo $this->lang->line('communityngo_dob'); ?>: </strong>
                            </td>
                            <td>
                                <?php echo date('dS F Y (l)', strtotime($master['CDOB'])) ?>
                            </td>
                            <td>
                                <strong id="_Gender"><?php echo $this->lang->line('communityngo_gender'); ?>: </strong>
                            </td>
                            <td>
                                <?php echo $master['name'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong id="_NIC"><?php echo $this->lang->line('communityngo_nic'); ?>: </strong>
                            </td>
                            <td>
                                <?php echo $master['CNIC_No']; ?>
                            </td>
                            <td>
                                <strong id="_BG"><?php echo $this->lang->line('communityngo_bloodGroup'); ?>: </strong>
                            </td>
                            <td>
                                <?php echo $master['BloodDescription']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong id="_CmHeight"><?php echo $this->lang->line('communityngo_height'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php echo $master['CmHeight'].' '.'cm'; ?>
                            </td>
                            <td>
                                <strong id="_CmWeight"><?php echo $this->lang->line('communityngo_weight'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php echo $master['CmWeight'].' '.'kg'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong id="_MaritialStatus"><?php echo $this->lang->line('communityngo_status'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php echo $master['maritalstatus'];  if($master['maritalstatusID'] == '2'){?>
                                   ( <strong style="color: #095db3;"><?php echo $this->lang->line('communityngo_mrgCertificateNo'); ?>: </strong>
                                    <?php echo $master['MrgCertificateNo']; ?>
                                    <strong style="color: #095db3;"><?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong>
                                    <?php echo $master['MrgRegisteredDate']; ?> )
                                <?php }?>
                            </td>
                            <td><strong><?php echo $this->lang->line('communityngo_email'); ?>: </strong></td>
                            <td>
                                <?php echo $master['EmailID'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong id="_EcMobile"><?php echo $this->lang->line('communityngo_TP_MobileNo'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php
                                if ($master['AreaCodePrimary'] == 0 || $master['AreaCodePrimary'] == null) {
                                    echo $master['CountryCodePrimary'] . ' - ' . $master['TP_Mobile'];
                                } else {
                                    echo $master['CountryCodePrimary'] . ' - ' . $master['AreaCodePrimary'] . ' - ' . $master['TP_Mobile'];
                                }
                                ?>
                            </td>
                            <td>
                                <strong id="_TP_home"><?php echo $this->lang->line('communityngo_TP_Home'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php
                                if ($master['TP_home'] == 0 || $master['TP_home'] == null) {
                                } else {
                                    if ($master['CountryCodeSecondary'] == 0 || $master['CountryCodeSecondary'] == null) {
                                        if ($master['AreaCodeSecondary'] == 0 || $master['AreaCodeSecondary'] == null) {
                                            echo $master['TP_home'];
                                        } else {
                                            echo $master['AreaCodeSecondary'] . ' - ' . $master['TP_home'];
                                        }
                                    } else {
                                        if ($master['AreaCodeSecondary'] == 0 || $master['AreaCodeSecondary'] == null) {
                                            echo $master['CountryCodeSecondary'] . ' - ' . $master['TP_home'];
                                        } else {
                                            echo $master['CountryCodeSecondary'] . ' - ' . $master['AreaCodeSecondary'] . ' - ' . $master['TP_home'];
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
                            <td style="font-size: 12px;color: black;">
                                <?php echo $master['CmPassportNo']; ?>
                                <strong style="color: #095db3;"><?php echo $this->lang->line('common_issue_date'); ?>: </strong>
                                <?php echo $master['CmPassportIssueDate'] ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;"><strong><?php echo $this->lang->line('common_expire_date'); ?>: </strong></td>
                            <td style="font-size: 12px;color: black;">
                                <?php echo $master['CmPassportExpiryDate'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_drivingLicense"><?php echo $this->lang->line('communityngo_drivingLicense'); ?>
                                    : </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php echo $master['CmDrivingLicenseNo']; ?>
                                <strong style="color: #095db3;"><?php echo $this->lang->line('common_issue_date'); ?>: </strong>
                                <?php echo $master['CmDrvLicIssueDate'] ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;"><strong><?php echo $this->lang->line('common_expire_date'); ?>: </strong></td>
                            <td style="font-size: 12px;color: black;">
                                <?php echo $master['CmDrvLicExpiryDate'] ?>
                            </td>
                        </tr>
                        <tr style="background-color: rgba(245,208,157,0.56);">
                            <td colspan="4" style="font-size:22px;color: black;font-weight: bold;">Address</td>
                        </tr>
                        <tr>
                            <td>
                                <strong id="_GS"><?php echo $this->lang->line('communityngo_GS_Division'); ?>: </strong>
                            </td>
                            <td>
                                <?php echo $master['GS_DivisionSt'] . ' - ' . $master['GS_No'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong id="_Rid"><?php echo $this->lang->line('communityngo_region'); ?>: </strong>
                            </td>
                            <td>
                                <?php echo $master['Description'] ?>
                            </td>
                            <td>
                                <strong id="_HouseNo"><?php echo $this->lang->line('communityngo_houseNo'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php echo $master['HouseNo'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong id="_address_P"><?php echo $this->lang->line('communityngo_perAddress'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php echo $master['P_Address'] ?>
                            </td>
                            <td>
                                <strong id="_address"><?php echo $this->lang->line('communityngo_contactAddress'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php echo $master['C_Address'] ?>
                            </td>
                        </tr>
                        <tr style="background-color: rgba(245,208,157,0.56);">
                            <td colspan="4" style="font-size:22px;color: black;font-weight: bold;">Other Details</td>
                        </tr>
                        <tr>
                            <td>
                                <strong id="_isAbroad"><?php echo $this->lang->line('communityngo_isAbroad'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php if($master['IsAbroad'] == '1'){echo 'Yes'; }else{ echo 'No';} ?>
                            </td>
                            <td>
                                <strong id="_cmCountry"><?php echo $this->lang->line('communityngo_country'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php echo $master['CountryDes'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong id="_IsSchooling"><?php echo $this->lang->line('communityngo_IsSchoolCompleted'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php if($master['IsSchoolCompleted'] == '1'){echo 'No'; }else{ echo 'Yes';} ?>
                            </td>
                            <td>
                                <strong id="_cmSchool"><?php echo $this->lang->line('communityngo_School'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php echo $master['schoolComDes'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong id="_isConverted"><?php echo $this->lang->line('communityngo_isConverted'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php if($master['isConverted'] == '1'){echo 'Yes'; }else{ echo 'No';} ?>
                            </td>
                            <td>
                                <strong id="_converted_place"><?php echo $this->lang->line('communityngo_converted_place'); ?> / <?php echo $this->lang->line('communityngo_ConvertedYear'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php echo $master['ConvertedPlace'] .' / '.$master['ConvertedYear'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong id="_isVoter"><?php echo $this->lang->line('communityngo_isVoter'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php if($master['isVoter'] == '1'){echo 'Yes'; }else{ echo 'No';} ?>
                            </td>
                            <td>
                                <strong
                                        id="_Status"><?php echo $this->lang->line('communityngo_com_member_header_Status'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php if ($master['isActive'] == 1) {
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
                            <td colspan="2" style="font-size:22px;color: black;font-weight: bold;">Languages <?php  $language = drill_down_emp_language();if (!empty($language)) {
                                foreach ($language as $valLang) {  echo $valLang['description'] . ' ,' ; }} ?> Other (Specify) :</td>
                            <td colspan="2"><?php  foreach ($cmLanguage as $key => $det) { echo $det['description'] . ' &nbsp;'; } ?></td>
                        </tr>
                        <tr style="background-color: rgba(245,208,157,0.56);">
                            <td colspan="4" style="font-size:22px;color: black;font-weight: bold;">Occupation</td>
                        </tr>
                                <?php
                                foreach ($cmOccupation as $key => $row) {

                                    if ($row['OccTypeID'] == 1) {
                                        if ($row['isActive'] == 0 || $row['isActive'] == null) {
                                            $isActiveYN = '';
                                        } else {
                                            $isActiveYN = '-  <lable style="color: green;">' . $this->lang->line('communityngo_Active').'</lable>';
                                        }
                                        echo'<tr><td colspan="4" style="font-size:15px;color: black;font-weight: bold;">Schooling '.$isActiveYN.'</td></tr>';
                                        $school = $row['schoolComDes'];
                                        $schType = $row['schoolTypeDes'];
                                        $Grade = $row['gradeComDes'];
                                        $Medium = $row['Medium'];
                                        if (!empty($row['DateFrom']) && $row['DateFrom'] != '0000-00-00') {
                                            $DateFrom = format_date($row['DateFrom']) . ' - ' . format_date($row['DateTo']);
                                        } else {
                                            $DateFrom = '';
                                        }

                                        echo'<tr><td>
                                         '.$this->lang->line('communityngo_School').' </td><td>';
                                        echo $school . ' ('.$schType .')';
                                        echo '</td><td>'.$this->lang->line('communityngo_SchoolGrade').' </td><td>'.$Grade .'</td>';
                                        echo '</tr><tr><td>'.$this->lang->line('communityngo_medium').'  </td><td>'.$Medium .'</td>';
                                        echo '</td><td>'.$this->lang->line('common_period').'  </td><td>'.$DateFrom .'</td></tr>';

                                    }
                                    else {

                                        if ($row['isPrimary'] == 0 || $row['isPrimary'] == null) {
                                            $isPrimaryYN = '';
                                        } else {
                                            $isPrimaryYN = '- <lable style="color: green;">' . $this->lang->line('communityngo_headPrimary').'</lable>';
                                        }
                                        echo'<tr><td colspan="4" style="font-size:15px;color: black;font-weight: bold;">Professional '.$isPrimaryYN.'</td></tr>';
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


                                        echo'<tr><td>
                                         '.$this->lang->line('communityngo_Job_Category').' </td><td>';
                                        echo $JobCategory . ' ('.$JobSpecialization .')';
                                        echo '</td><td>'.$this->lang->line('communityngo_Job_WorkingPlace').' </td><td>'.$WorkingPlace .'</td>';
                                        echo '</tr><tr><td>'.$this->lang->line('communityngo_Job_Address').'  </td><td>'.$Address .'</td>';
                                        echo '</td><td>'.$this->lang->line('common_period').'  </td><td>'.$DateFrom .'</td></tr>';

                                    }

                                }
                                ?>
                        <tr style="background-color: rgba(245,208,157,0.56);">
                            <td colspan="4" style="font-size:22px;color: black;font-weight: bold;">Qualifications</td>
                        </tr>
                            <?php
                        foreach ($cmQualification as $key => $row) {
                           ?>
                        <tr>
                            <td>
                                <strong id="_Qualification"><?php echo $this->lang->line('communityngo_QualificationType'); ?>
                                    : </strong>
                            </td>
                            <td>
                                <?php echo $row['DegreeDescription']; ?>
                            </td>
                            <td>
                                <strong id="_qYear"><?php echo $this->lang->line('communityngo_Year'); ?>: </strong>
                            </td>
                            <td>
                                <?php echo $row['Year']; ?>
                            </td>
                        </tr>
                            <?php
                        }
                            ?>
                        <tr style="background-color: rgba(245,208,157,0.56);">
                            <td colspan="4" style="font-size:22px;color: black;font-weight: bold;">Permanent Sickness</td>
                        </tr>
                        <?php
                        foreach ($cmSickness as $key => $det) {
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
                                <td>
                                    <strong id="_sickDescription"><?php echo $this->lang->line('common_description'); ?>
                                        : </strong>
                                </td>
                                <td>
                                    <?php echo $det['sickDescription']; ?>
                                </td>
                                <td>
                                    <strong id="_sFrom"><?php echo $this->lang->line('communityngo_sickness_from'); ?>: </strong>
                                </td>
                                <td>
                                    <?php echo $startedFrom; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong id="_medicalCondition"><?php echo $this->lang->line('communityngo_medicalcondiation'); ?>
                                        : </strong>
                                </td>
                                <td>
                                    <?php echo $det['medicalCondition']; ?>
                                </td>
                                <td>
                                    <strong id="_mExpenses"><?php echo $this->lang->line('CommunityNgo_member_expenses'); ?>: </strong>
                                </td>
                                <td>
                                    <?php echo $monthlyExpenses; ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr style="background-color: rgba(245,208,157,0.56);">
                            <td colspan="4" style="font-size:22px;color: black;font-weight: bold;">Property Details</td>
                        </tr>
                        <?php
    if(!empty($cmVehicleConfig)) {
        ?>
                        <tr>
                            <td>
                                <strong id="_pType" style="color: black;font-size: 12px;"><?php echo $this->lang->line('common_type'); ?></strong>
                            </td>
                            <td>
                                <strong id="_pDescription"><?php echo $this->lang->line('common_description'); ?> </strong>
                            </td>
                            <td>
                                <strong id="_own_lease"><?php echo $this->lang->line('communityngo_property_status'); ?></strong>
                            </td>
                            <td>
                                <strong id="_pValue"><?php echo $this->lang->line('common_value'); ?> </strong>
                            </td>
                        </tr>
                        <?php
                        foreach ($cmVehicleConfig as $key => $det) {
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
                            <td>
                                <strong id="_pType"><?php echo $PropertyDes; ?></strong>
                            </td>
                            <td>
                                <strong id="_pDescription"><?php echo $vehiDesc; ?></strong>
                            </td>
                            <td>
                                <strong id="_own_lease"><?php echo $vehiStatus; ?></strong>
                            </td>
                            <td>
                                <strong id="_pValue"><?php echo $propertyValue; ?></strong>
                            </td>
                        </tr>
                            <?php
                        }}

                        if(!empty($cmHelpReqConGv) || !empty($cmHelpReqConPv) || !empty($cmHelpReqConCs) || !empty($cmHelpReqComOther)) {
                            ?>
                        <tr style="background-color: rgba(245,208,157,0.56);">
                            <td colspan="4" style="font-size:22px;color: black;font-weight: bold;">Help Requirement</td>
                        </tr>
                        <?php
                        if(!empty($cmHelpReqConGv)) {
                        ?>
                            <tr>
                                <td colspan="4" style="color: #095db3;">Requirement Type : Government Help</td>
                            </tr>
                            <?php
                            $gv = 1;
                            foreach ($cmHelpReqConGv as $key => $det) {

                            if ($det['hlprDescription'] == null) {
                            $helpDesc = '';
                            } else {
                            $helpDesc = $det['hlprDescription'];
                            }
                            ?>
                            <tr>
                                <td colspan="4" style="color: black;"><?php echo $gv .' '. $det['helpRequireDesc']; ?> &nbsp;<small><?php echo $helpDesc; ?><small></td>
                            </tr>
                        <?php
                                $gv++;
                        }}
                        if(!empty($cmHelpReqConPv)) {
                        ?>
                        <tr>
                            <td colspan="4" style="color: #095db3;">Requirement Type : Private Help</td>
                        </tr>
                        <?php
                        $pr = 1;
                        foreach ($cmHelpReqConPv as $key => $det) {

                        if ($det['hlprDescription'] == null) {
                            $helpDesc = '';
                        } else {
                            $helpDesc = $det['hlprDescription'];
                        }
                        ?>
                        <tr>
                            <td colspan="4" style="color: black;"><?php echo $pr .' '. $det['helpRequireDesc']; ?> &nbsp;<small><?php echo $helpDesc; ?><small></td>
                        </tr>
                            <?php
                            $pr++;
                        }}
                            if(!empty($cmHelpReqConCs)) {
                                ?>
                                <tr>
                                    <td colspan="4" style="color: #095db3;">Requirement Type : Consultancy</td>
                                </tr>
                                <?php
                            $cn = 1;
                            foreach ($cmHelpReqConCs as $key => $det) {

                                if ($det['hlprDescription'] == null) {
                                    $helpDesc = '';
                                } else {
                                    $helpDesc = $det['hlprDescription'];
                                }
                                ?>
                                <tr>
                                    <td colspan="4" style="color: black;"><?php echo $cn .' '. $det['helpRequireDesc']; ?> &nbsp;<small><?php echo $helpDesc; ?><small></td>
                                </tr>
                            <?php $cn++;
                            } }
                            if(!empty($cmHelpReqComOther)) {
                                ?>
                                <tr>
                                    <td colspan="4" style="color: #095db3;">Requirement Type : Other</td>
                                </tr>
                                <?php
                                $otr = 1;
                                foreach ($cmHelpReqComOther as $key => $det) {

                                    if ($det['hlprDescription'] == null) {
                                        $helpDesc = '';
                                    } else {
                                        $helpDesc = $det['hlprDescription'];
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="4" style="color: black;"><?php echo $otr .' '. $det['helpRequireDesc']; ?> &nbsp;<small><?php echo $helpDesc; ?><small></td>
                                    </tr>
                                    <?php $otr++;
                                } }

                            ?>
                        <?php } ?>

                        <?php
                        if(!empty($cmWillingToHelp)) {
                            ?>
                            <tr style="background-color: rgba(245,208,157,0.56);">
                                <td colspan="4" style="font-size:22px;color: black;font-weight: bold;">Willing to Help</td>
                            </tr>

                                <?php
                                $willing = 1;
                                foreach ($cmWillingToHelp as $key => $det) {

                                    if ($det['helpComments'] == null) {
                                        $helpDesc = '';
                                    } else {
                                        $helpDesc = 'Comments :' . $det['helpComments'];
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="4" style="color: black;"><?php echo $willing .' '. $det['helpCategoryDes']; ?> &nbsp;<small><?php echo $helpDesc; ?><small></td>
                                    </tr>
                                    <?php
                                    $willing++;
                                }
                             }
                        ?>

                        <?php
                        if ($master['isActive'] == 0) { ?>
                            <tr>
                                <td>
                                    <strong id="_Reason"><?php echo $this->lang->line('communityngo_deactivatedFor'); ?>
                                        : </strong>
                                </td>
                                <td>
                                    <?php if ($master['DeactivatedFor'] == 1) {
                                        echo 'Death';
                                    } else if ($master['DeactivatedFor'] == 2) {
                                        echo 'Migrate';
                                    } ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong id="_Date"><?php echo $this->lang->line('communityngo_deactivatedDate'); ?>
                                        : </strong>
                                </td>
                                <td>
                                    <?php echo date('dS F Y ', strtotime($master['deactivatedDate'])) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong
                                        id="_Comment"><?php echo $this->lang->line('communityngo_deactivatedComment'); ?>
                                        : </strong>
                                </td>
                                <td>
                                    <?php echo $master['deactivatedComment'] ?>
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
                            <td style="font-size: 12px;color: black;">
                                <?php if($master['fatherIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_memMother"><?php echo $this->lang->line('communityNgo_is_alive'); ?> (<?php echo $this->lang->line('communityngo_com_member_mother'); ?>): </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php if($master['motherIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_memFather"><?php echo $this->lang->line('communityngo_name'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php echo $master['cfFullName']; ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_memMother"><?php echo $this->lang->line('communityngo_name'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php echo $master['cmFullName']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_memFather"><?php echo $this->lang->line('communityNgo_born_country'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php echo $master['fCountry']; ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_memMother"><?php echo $this->lang->line('communityNgo_born_country'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php echo $master['mCountry']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_memFather"><?php echo $this->lang->line('communityNgo_born_area'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php echo $master['fArea']; ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_memMother"><?php echo $this->lang->line('communityNgo_born_area'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php echo $master['mArea']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_memFather"><?php echo $this->lang->line('communityngo_dob'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php echo $master['cFatherDOB']; ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_memMother"><?php echo $this->lang->line('communityngo_dob'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php echo $master['cMotherDOB']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_memFather"><?php echo $this->lang->line('communityNgo_Sbc_no'); ?>/<?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php echo $master['cfBC_No']; ?> / <?php echo $master['cfBCDate']; ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_memMother"><?php echo $this->lang->line('communityNgo_Sbc_no'); ?>/<?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php echo $master['cmBC_No']; ?> / <?php echo $master['cmBCDate'];?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_memFather"><?php echo $this->lang->line('communityngo_nic'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php echo $master['cfNIC_No']; ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_memMother"><?php echo $this->lang->line('communityngo_nic'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php echo $master['cmNIC_No']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_memFather"><?php echo $this->lang->line('communityNgo_dateOfDeath'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php if($master['fatherIsAlive'] == '0'){ echo $master['cfDateOfDeath']; } else { echo '-'; } ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_memMother"><?php echo $this->lang->line('communityNgo_dateOfDeath'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php if($master['motherIsAlive'] == '0'){echo $master['cmDateOfDeath'];} else { echo '-';} ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_memFather"><?php echo $this->lang->line('communityNgo_Sdc_no'); ?>/<?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php if($master['fatherIsAlive'] == '0'){echo $master['cfDC_No']; ?> / <?php echo $master['cfDCDate'];} else { echo '-';}?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_memMother"><?php echo $this->lang->line('communityNgo_Sdc_no'); ?>/<?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php if($master['motherIsAlive'] == '0'){ echo $master['cmDC_No']; ?> / <?php echo $master['cmDCDate'];} else { echo '-';}?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_memFather"><?php echo $this->lang->line('communityngo_Job'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php echo $master['fJobDes']; ?>
                            </td>
                            <td style="font-size: 12px;color:#095db3;">
                                <strong id="_memMother"><?php echo $this->lang->line('communityngo_Job'); ?>: </strong>
                            </td>
                            <td style="font-size: 12px;color: black;">
                                <?php echo $master['mJobDes']; ?>
                            </td>
                        </tr>
                        <tr style="background-color: rgba(245,208,157,0.56);">
                            <td colspan="4" style="font-size:12px;color: black;font-weight: bold;background-color: rgba(245,208,157,0.56);"><?php echo $this->lang->line('communityngo_com_mem_header_grandparent'); ?></td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <table border="0">
                                    <tr>
                                        <td style="font-size: 12px;color: black;">
                                        </td>
                                        <td style="font-size: 12px;color:#095db3;" colspan="2">
                                            <b><?php echo $this->lang->line('communityngo_fatherSide_grandparent'); ?> </b>
                                        </td>
                                        <td style="font-size: 12px;color:#095db3;" colspan="2">
                                            <b><?php echo $this->lang->line('communityngo_motherSide_grandparent'); ?> </b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color: black;">
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
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cf_GrandFIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cf_GrandMIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cm_GrandFIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cm_GrandMIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color:#095db3;">
                                            <strong id="_grandPrts"><?php echo $this->lang->line('communityngo_name'); ?>: </strong>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cf_GrandFFullName']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cf_GrandMFullName']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cm_GrandFFullName']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cm_GrandMFullName']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color:#095db3;">
                                            <strong id="_grandPrts"><?php echo $this->lang->line('communityNgo_born_country'); ?>: </strong>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['fgpCountry']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['fgmCountry']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['mgpCountry']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['mgmCountry']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color:#095db3;">
                                            <strong id="_grandPrts"><?php echo $this->lang->line('communityNgo_born_area'); ?>: </strong>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['fgpArea']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['fgmArea']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['mgpArea']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['mgmArea']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color:#095db3;">
                                            <strong id="_grandPrts"><?php echo $this->lang->line('communityngo_dob'); ?>: </strong>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cf_GrandFDOB']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cf_GrandMDOB']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cm_GrandFDOB']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cm_GrandMDOB']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color:#095db3;">
                                            <strong id="_grandPrts"><?php echo $this->lang->line('communityNgo_Sbc_no'); ?>/<?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cf_GrandFBC_No']; ?> / <?php echo $master['cf_GrandFBCDate']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cf_GrandMBC_No']; ?> / <?php echo $master['cf_GrandMBCDate']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cm_GrandFBC_No']; ?> / <?php echo $master['cm_GrandFBCDate']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cm_GrandMBC_No']; ?> / <?php echo $master['cm_GrandMBCDate']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color:#095db3;">
                                            <strong id="_grandPrts"><?php echo $this->lang->line('communityngo_nic'); ?>: </strong>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cf_GrandFNIC_No']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cf_GrandMNIC_No']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cm_GrandFNIC_No']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cm_GrandMNIC_No']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color:#095db3;">
                                            <strong id="_grandPrts"><?php echo $this->lang->line('communityNgo_dateOfDeath'); ?>: </strong>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cf_GrandFIsAlive'] == '0'){ echo $master['cf_GrandFDateOfDeath']; } else { echo '-'; } ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cf_GrandMIsAlive'] == '0'){ echo $master['cf_GrandMDateOfDeath']; } else { echo '-'; } ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cm_GrandFIsAlive'] == '0'){ echo $master['cm_GrandFDateOfDeath']; } else { echo '-'; } ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cm_GrandMIsAlive'] == '0'){ echo $master['cm_GrandMDateOfDeath']; } else { echo '-'; } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color:#095db3;">
                                            <strong id="_grandPrts"><?php echo $this->lang->line('communityNgo_Sdc_no'); ?>/<?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cf_GrandFIsAlive'] == '0'){echo $master['cf_GrandFDC_No']; ?> / <?php echo $master['cf_GrandMDCDate'];} else { echo '-';}?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cf_GrandMIsAlive'] == '0'){echo $master['cf_GrandMDC_No']; ?> / <?php echo $master['cf_GrandMDCDate'];} else { echo '-';}?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cm_GrandFIsAlive'] == '0'){ echo $master['cm_GrandFDC_No']; ?> / <?php echo $master['cm_GrandFDCDate'];} else { echo '-';}?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cm_GrandMIsAlive'] == '0'){ echo $master['cm_GrandMDC_No']; ?> / <?php echo $master['cm_GrandMDCDate'];} else { echo '-';}?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color:#095db3;">
                                            <strong id="_grandPrts"><?php echo $this->lang->line('communityngo_Job'); ?>: </strong>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['fgpJobDes']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['fgmJobDes']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['mgpJobDes']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['mgmJobDes']; ?>
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
                                        <td style="font-size: 12px;color: black;">
                                        </td>
                                        <td style="font-size: 12px;color:#095db3;" colspan="2">
                                            <b><?php echo $this->lang->line('communityngo_fatherSideGrt_grandparent'); ?> </b>
                                        </td>
                                        <td style="font-size: 12px;color:#095db3;" colspan="2">
                                            <b><?php echo $this->lang->line('communityngo_motherSideGrt_grandparent'); ?> </b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color: black;">
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
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cf_grt_GrandFIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cf_grt_GrandMIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cm_grt_GrandFIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cm_grt_GrandMIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color:#095db3;">
                                            <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityngo_name'); ?>: </strong>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cf_grt_GrandFFullName']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cf_grt_GrandMFullName']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cm_grt_GrandFFullName']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cm_grt_GrandMFullName']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color:#095db3;">
                                            <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityNgo_born_country'); ?>: </strong>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['fgptCountry']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['fgmtCountry']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['mgptCountry']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['mgmtCountry']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color:#095db3;">
                                            <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityNgo_born_area'); ?>: </strong>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['fgptArea']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['fgmtArea']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['mgptArea']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['mgmtArea']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color:#095db3;">
                                            <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityngo_dob'); ?>: </strong>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cf_grt_GrandFDOB']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cf_grt_GrandMDOB']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cm_grt_GrandFDOB']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cm_grt_GrandMDOB']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color:#095db3;">
                                            <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityNgo_Sbc_no'); ?>/<?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cf_grt_GrandFBC_No']; ?> / <?php echo $master['cf_grt_GrandFBCDate']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cf_grt_GrandMBC_No']; ?> / <?php echo $master['cf_grt_GrandMBCDate']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cm_grt_GrandFBC_No']; ?> / <?php echo $master['cm_grt_GrandFBCDate']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cm_grt_GrandMBC_No']; ?> / <?php echo $master['cm_grt_GrandMBCDate']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color:#095db3;">
                                            <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityngo_nic'); ?>: </strong>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cf_grt_GrandFNIC_No']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cf_grt_GrandMNIC_No']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cm_grt_GrandFNIC_No']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['cm_grt_GrandMNIC_No']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color:#095db3;">
                                            <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityNgo_dateOfDeath'); ?>: </strong>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cf_grt_GrandFIsAlive'] == '0'){ echo $master['cf_grt_GrandFDateOfDeath']; } else { echo '-'; } ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cf_grt_GrandMIsAlive'] == '0'){ echo $master['cf_grt_GrandMDateOfDeath']; } else { echo '-'; } ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cm_grt_GrandFIsAlive'] == '0'){ echo $master['cm_grt_GrandFDateOfDeath']; } else { echo '-'; } ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cm_grt_GrandMIsAlive'] == '0'){ echo $master['cm_grt_GrandMDateOfDeath']; } else { echo '-'; } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color:#095db3;">
                                            <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityNgo_Sdc_no'); ?>/<?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cf_grt_GrandFIsAlive'] == '0'){echo $master['cf_grt_GrandFDC_No']; ?> / <?php echo $master['cf_grt_GrandMDCDate'];} else { echo '-';}?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cf_grt_GrandMIsAlive'] == '0'){echo $master['cf_grt_GrandMDC_No']; ?> / <?php echo $master['cf_grt_GrandMDCDate'];} else { echo '-';}?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cm_grt_GrandFIsAlive'] == '0'){ echo $master['cm_grt_GrandFDC_No']; ?> / <?php echo $master['cm_grt_GrandFDCDate'];} else { echo '-';}?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php if($master['cm_grt_GrandMIsAlive'] == '0'){ echo $master['cm_grt_GrandMDC_No']; ?> / <?php echo $master['cm_grt_GrandMDCDate'];} else { echo '-';}?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;color:#095db3;">
                                            <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityngo_Job'); ?>: </strong>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['fgptJobDes']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['fgmtJobDes']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['mgptJobDes']; ?>
                                        </td>
                                        <td style="font-size: 12px;color: black;">
                                            <?php echo $master['mgmtJobDes']; ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-3">
                    <div class="fileinput-new thumbnail">
                        <?php
                        $communityimage = get_all_community_images($master['CImage'],'Community/'.$companyCode.'/MemberImages/','communityNoImg');

                        if ($master['CImage'] == '') {

                            ?>
                            <img src="<?php echo $communityimage; ?>" id="changeImg"
                                 style="width: 200px; height: 145px;">
                            <?php
                        } else {

                            $linkStart = '<i class="fa fa-times-circle pull-right" aria-hidden="true"
                                         onclick="delete_member_image(' . $master['Com_MasterID'] . ', \'' . $master['CImage'] . '\')"></i>';

                            $linkEnd = '</a>';

                            echo '
                        ' . $linkStart . '
                        <img class="" src="'. $communityimage .'" id="changeImg" style="width: 200px; height: 145px;">
                        ' . $linkEnd . '
                        ';
                        }
                        ?>

                        <input type="file" name="contactImage" id="itemImage" style="display: none;"
                               onchange="loadImage(this)"/>
                    </div>
                    <h4 style="text-align: center;margin: 0;color: #095db3;font-weight: bold">
                        <?php echo empty($master['CName_with_initials']) ? '' : $master['CName_with_initials']; ?>
                    </h4>
                    <?php
                    if(!empty($master['C_Latitude']) && !empty($master['C_Longitude'])){
                        ?>
                    <div class="fileinput-new thumbnail" style="margin-top: 10px;">
                                <div style="text-align: center;">
                            <iframe style="width: 200px; height: 145px;" src="https://www.google.com/maps?q=<?php echo $master['C_Latitude']; ?>,<?php echo $master['C_Longitude']; ?>&output=embed"></iframe>
                                </div>
                        <h5 style="text-align: center;margin: 0;color: #095db3;text-decoration: overline;">
                            <?php echo $this->lang->line('communityngo_com_member_header_location'); ?>
                        </h5>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12 animated zoomIn">
                    <header class="head-title">
                        <h2>RECORD DETAILS</h2>
                    </header>
                </div>
            </div>
            <table class="table table-striped" id="recordInfoTable"
                   style="background-color: #ffffff;width: 100%">
                <tbody>
                <tr>
                    <td>
                        <strong id="_CD"><?php echo $this->lang->line('communityngo_CreatedDate'); ?>:</strong>
                    </td>
                    <td>
                        <?php
                        echo $master['createdDateTime'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong id="_MCB"><?php echo $this->lang->line('communityngo_MemberCreatedBy'); ?>:</strong>
                    </td>
                    <td>
                        <?php echo $master['createdUserName'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong id="_CD"><?php echo $this->lang->line('communityngo_LastUpdated'); ?>:</strong>
                    </td>
                    <td>
                        <?php echo $master['modifiedDateTime'] ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="tab-pane" id="files">
            <br>

            <div class="row" id="show_add_files_button">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <button type="button" onclick="show_add_file()" class="btn btn-primary pull-right"><i
                            class="fa fa-plus"></i> Add Attachments
                    </button>
                </div>
            </div>
            <div class="row hide" id="add_attachment_show">
                <?php echo form_open_multipart('', 'id="attachment_Upload_form" class="form-inline"'); ?>
                <div class="col-sm-12" style="">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label">
                                <?php echo $this->lang->line('communityngo_Description'); ?><!--Description--><?php required_mark(); ?></label>
                            <input type="text" class="form-control" id="contactattachmentDescription"
                                   name="attachmentDescription" placeholder="Description..." style="width: 115%;">
                            <input type="hidden" class="form-control" id="documentID" name="documentID" value="7">
                            <input type="hidden" class="form-control" id="contact_documentAutoID" name="documentAutoID"
                                   value="<?php echo $master['Com_MasterID']; ?>">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label">
                                <?php echo $this->lang->line('communityngo_ExpiryDate'); ?><!--Expiry Date--></label>
                            <div class="input-group datepic">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input type="text" name="docExpiryDate" style="width: 120%;"
                                       data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                                       value="" id="docExpiryDate" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8" style="margin-top: -8px;">
                        <div class="form-group">
                            <label class=" control-label" style="visibility: hidden;">UPLOAD</label>
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput"
                                 style="margin-top: 8px;">
                                <div class="form-control" data-trigger="fileinput"><i
                                        class="glyphicon glyphicon-file color fileinput-exists"></i> <span
                                        class="fileinput-filename"></span></div>
                                <span class="input-group-addon btn btn-default btn-file"><span
                                        class="fileinput-new"><span class="glyphicon glyphicon-plus"
                                                                    aria-hidden="true"></span></span><span
                                        class="fileinput-exists"><span class="glyphicon glyphicon-repeat"
                                                                       aria-hidden="true"></span></span><input
                                        type="file" name="document_file" id="document_file"></span>
                                <a class="input-group-addon btn btn-default fileinput-exists" id="remove_id"
                                   data-dismiss="fileinput"><span class="glyphicon glyphicon-remove"
                                                                  aria-hidden="true"></span></a>
                            </div>
                        </div>
                        <button type="button" class="btn btn-default" style="margin-top: 3%"
                                onclick="attchment_Upload()"><span
                                class="glyphicon glyphicon-floppy-open color" aria-hidden="true"></span></button>
                        </form>
                    </div>
                </div>

            </div>
            <br>

            <div id="show_all_attachments"></div>
        </div>
    </div>


    <?php
}
?>
    <script type="text/javascript">

        $(document).ready(function () {

            var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';

            $('.datepic').datetimepicker({
                useCurrent: false,
                format: date_format_policy,
            }).on('dp.change', function (ev) {
            });

            Inputmask().mask(document.querySelectorAll("input"));
        });

        function delete_member_image(id, fileName) {
            swal({
                    title: "Are you sure?",
                    text: "You want to Delete!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes!"
                },
                function () {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {'attachmentID': id, 'myFileName': fileName},
                        url: "<?php echo site_url('CommunityNgo/delete_member_image'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            if (data == true) {
                                myAlert('s', 'Deleted Successfully');
                                get_member_DetailsView(id);
                            } else {
                                myAlert('e', 'Deletion Failed');
                            }
                        },
                        error: function () {
                            stopLoad();
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });

        }


        function attchment_Upload() {
            var formData = new FormData($("#attachment_Upload_form")[0]);
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: "<?php echo site_url('CommunityNgo/ngo_Memberattachement_upload'); ?>",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data['type'], data['message'], 1000);
                    if (data['status']) {
                        $('#add_attachment_show').addClass('hide');
                        $('#remove_id').click();
                        $('#contactattachmentDescription').val('');
                        member_attachments();
                    }
                },
                error: function (data) {
                    stopLoad();
                    swal("Cancelled", "No File Selected :)", "error");
                }
            });
            return false;
        }

        function member_attachments() {
            var Com_MasterID = $('#editCom_MasterID').val();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {Com_MasterID: Com_MasterID},
                url: "<?php echo site_url('CommunityNgo/load_member_all_attachments'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    $('#show_all_attachments').html(data);
                    stopLoad();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function show_add_file() {
            $('#add_attachment_show').removeClass('hide');
            $('#docExpiryDate').val('');
        }

        function delete_member_attachment(id, fileName) {
            swal({
                    title: "Are you sure?",
                    text: "You want to Delete!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes!"
                },
                function () {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {'attachmentID': id, 'myFileName': fileName},
                        url: "<?php echo site_url('CommunityNgo/delete_member_attachment'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            if (data == true) {
                                myAlert('s', 'Deleted Successfully');
                                member_attachments();
                            } else {
                                myAlert('e', 'Deletion Failed');
                            }
                        },
                        error: function () {
                            stopLoad();
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });

        }


        $('#changeImg').click(function () {
            $('#itemImage').click();
        });

        function loadImage(obj) {
            if (obj.files && obj.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#changeImg').attr('src', e.target.result);
                };
                reader.readAsDataURL(obj.files[0]);
                profileImageUpload();
            }
        }

        function profileImageUpload() {
            var imgageVal = new FormData();
            imgageVal.append('MemberID', $('#editCom_MasterID').val());

            var files = $("#itemImage")[0].files[0];
            imgageVal.append('files', files);

            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                data: imgageVal,
                contentType: false,
                cache: false,
                processData: false,
                url: "<?php echo site_url('CommunityNgo/member_image_upload'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1]);
                    if (data[0] == 's') {
                    }

                }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        }

    </script>


<?php
/**
 * Created by PhpStorm.
 * User: Hishama
 * Date: 1/29/2018
 * Time: 9:46 AM
 */