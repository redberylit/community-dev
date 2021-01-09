<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('send_requestEmail')) {
    function send_requestEmail($mailData)
    {
        $CI = &get_instance();
        $CI->load->library('email_manual');

        $toEmail = $mailData['toEmail'];
        $subject = $mailData['subject'];
        $param = $mailData['param'];

        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['wordwrap'] = TRUE;
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'smtp.sendgrid.net';
        $config['smtp_user'] = 'apikey';
        //$config['smtp_pass'] = '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';
        $config['smtp_pass'] = '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';
        $config['smtp_crypto'] = 'tls';
        $config['smtp_port'] = '587';
        $config['crlf'] = "\r\n";
        $config['newline'] = "\r\n";
        $CI->load->library('email', $config);
        if (array_key_exists("from", $mailData)) {
            $CI->email->from('noreply@spur-int.com', $mailData['from']);
        } else {
            $CI->email->from('noreply@spur-int.com', SYS_NAME);
        }

        if (!empty($param)) {
            $CI->email->to($toEmail);
            $CI->email->subject($subject);
            $CI->email->message($CI->load->view('system/communityNgo/template/request_email_template', $param, TRUE));
        }

        $result = $CI->email->send();
        $CI->email->clear(TRUE);
    }
}


/*blood group dropdown*/
if (!function_exists('selectOnTab')) {
    function selectOnTab()
    {
        $data = '<script> 

 var tabindex = 1;

            $(\'input,select,textarea\').each(function() {
                if (this.type != "hidden") {
                    $(this).attr("tabindex", tabindex);
                    tabindex++;
                }
            });

// https://stackoverflow.com/a/50535297/2782670
            $(document).on(\'focus\', \'.select2\', function (e) {
                if (e.originalEvent) {
                    var s2element = $(this).siblings(\'select\');
                    s2element.select2(\'open\');
                    // Set focus back to select2 element on closing.
                    s2element.on(\'select2:closing\', function (e) {
                        s2element.select2(\'focus\');
                    });
                }
            });
            
</script>';
        return $data;
    }
}
/*blood group dropdown*/
if (!function_exists('load_bloodGroup')) {
    function load_bloodGroup()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT * FROM srp_erp_bloodgrouptype")->result_array();
        return $data;
    }
}

/*country dropdown*/
if (!function_exists('load_country')) {
    function load_country()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT * FROM srp_erp_countrymaster")->result_array();
        return $data;
    }
}

/*language dropdown*/
if (!function_exists('load_language')) {
    function load_language()
    {
        $CI = &get_instance();
        $CI->db->SELECT("*");
        $CI->db->FROM('srp_erp_lang_languages');
        $CI->db->WHERE('isActive', 1);
        return $CI->db->get()->result_array();
    }
}

/*school type dropdown*/
if (!function_exists('load_schoolTypes')) {
    function load_schoolTypes()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_schooltypes")->result_array();
        return $data;
    }
}

/*degree dropdown*/
if (!function_exists('load_degree')) {
    function load_degree()
    {
        $CI = &get_instance();
        $companyID = $CI->common_data['company_data']['company_id'];
        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_degreecategories")->result_array();
        return $data;
    }
}

/*university dropdown*/
if (!function_exists('load_university')) {
    function load_university()
    {
        $CI = &get_instance();
        $companyID = $CI->common_data['company_data']['company_id'];
        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_universities")->result_array();
        return $data;
    }
}

/*Job category dropdown*/
if (!function_exists('load_Jobcategories')) {
    function load_Jobcategories()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_jobcategories ")->result_array();
        return $data;
    }
}

/* */
if (!function_exists('load_memParentJob')) {
    function load_memParentJob()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_jobspecialization ")->result_array();
        return $data;
    }
}


/*Load all countries for select2*/
if (!function_exists('load_all_countries')) {
    function load_all_countries($status = true)
    {
        $CI = &get_instance();
        $CI->db->SELECT("countryID,countryShortCode,CountryDes");
        $CI->db->FROM('srp_erp_countrymaster');
        $countries = $CI->db->get()->result_array();
        $countries_arr = array('' => 'Select Country');
        if (isset($countries)) {
            foreach ($countries as $row) {
                $countries_arr[trim($row['countryID'])] = trim($row['CountryDes']);
            }
        }
        return $countries_arr;
    }
}

if (!function_exists('load_default_data')) {
    function load_default_data($status = true)
    {
        $CI = &get_instance();
        $company_id = current_companyID();

        $MainArea = $CI->db->query("SELECT * FROM srp_erp_ngo_com_regionmaster INNER JOIN srp_erp_statemaster ON srp_erp_statemaster.stateID = srp_erp_ngo_com_regionmaster.stateID WHERE companyID = {$company_id} ")->row_array();

        if (empty($MainArea)) {
            $CountryID = '';
            $DD_ID = '';
            $DD_Description = '';
            $DistrictID = '';
            $ProvinceID = '';
            $DistrictCode = '';
            $DDivisionCode = '';
            $ProvinceCode = '';
        } else {
            $CountryID = $MainArea['countyID'];
            $DD_ID = $MainArea['stateID'];
            $DD_Description = $MainArea['Description'];
            $DistrictID = $MainArea['masterID'];
            $DDivisionCode = $MainArea['shortCode'];
            $ProvinceCode = '';
            //province
            $Province = $CI->db->query("SELECT masterID,shortCode FROM srp_erp_statemaster WHERE countyID = {$CountryID} AND stateID = {$DistrictID} AND type = 2")->row_array();
            $ProvinceID = $Province['masterID'];
            $DistrictCode = $Province['shortCode'];
            if ($ProvinceID) {
                $ProvinceDel = $CI->db->query("SELECT masterID,shortCode FROM srp_erp_statemaster WHERE countyID = {$CountryID} AND stateID = {$ProvinceID} AND type = 1")->row_array();
                $ProvinceCode = $ProvinceDel['shortCode'];
            }
        }


        $data = array(
            "country" => $CountryID,
            "DD" => $DD_ID,
            "DD_Des" => $DD_Description,
            "district" => $DistrictID,
            "province" => $ProvinceID,
            "DistrictCode" => $DistrictCode,
            "DDivisionCode" => $DDivisionCode,
            "ProvinceCode" => $ProvinceCode,

        );
        return $data;
    }
}

// ngo policies
if (!function_exists('fetch_ngo_policies')) {
    function fetch_ngo_policies($policyCode)
    {

        $CI = &get_instance();
        $companyID = $CI->common_data['company_data']['company_id'];
        $CI->db->SELECT("policyCode");
        $CI->db->FROM('srp_erp_ngo_policies');
        $CI->db->WHERE('companyID', $companyID);
        $CI->db->WHERE('value', 1);
        $CI->db->WHERE('policyCode', $policyCode);
        return $CI->db->get()->row('policyCode');
    }
}

/*region dropdown*/
if (!function_exists('load_region')) {
    function load_region()
    {
        $CI = &get_instance();
        $def_data = load_default_data();

        $CI->db->SELECT("*");
        $CI->db->FROM('srp_erp_statemaster');
        $CI->db->WHERE('countyID', $def_data['country']);
        $CI->db->WHERE('masterID', $def_data['DD']);
        $CI->db->WHERE('type', 4);
        $CI->db->WHERE('divisionTypeCode', 'MH');
        $CI->db->order_by('Description');
        $countries = $CI->db->get()->result_array();
        $countries_arr = array('' => 'Select Area/Mahalla');
        if (isset($countries)) {
            foreach ($countries as $row) {
                $countries_arr[trim($row['stateID'])] = trim($row['Description']);
            }
        }
        return $countries_arr;
    }
}

if (!function_exists('load_region_fo_members')) {
    function load_region_fo_members()
    {
        $CI = &get_instance();
        $def_data = load_default_data();

        $CI->db->SELECT("*");
        $CI->db->FROM('srp_erp_statemaster');
        $CI->db->WHERE('countyID', $def_data['country']);
        $CI->db->WHERE('masterID', $def_data['DD']);
        $CI->db->WHERE('type', 4);
        $CI->db->WHERE('divisionTypeCode', 'MH');
        $CI->db->order_by('Description');
        $countries = $CI->db->get()->result_array();
        //   $countries_arr = array('' => 'Select Area/Mahalla');
        $countries_arr = '';
        if (isset($countries)) {
            foreach ($countries as $row) {
                $countries_arr[trim($row['stateID'])] = trim($row['Description']);
            }
        }
        return $countries_arr;
    }
}

/*division dropdown*/
if (!function_exists('load_division')) {
    function load_division()
    {
        $CI = &get_instance();
        $def_data = load_default_data();

        $CI->db->SELECT("*");
        $CI->db->FROM('srp_erp_statemaster');
        $CI->db->WHERE('countyID', $def_data['country']);
        $CI->db->WHERE('masterID', $def_data['DD']);
        $CI->db->WHERE('type', 4);
        $CI->db->WHERE('divisionTypeCode', 'GN');
        $CI->db->order_by('Description');
        $countries = $CI->db->get()->result_array();
        $countries_arr = array('' => 'Select GS Division');
        if (isset($countries)) {
            foreach ($countries as $row) {
                $countries_arr[trim($row['stateID'])] = trim($row['Description']);
            }
        }
        return $countries_arr;
    }
}

if (!function_exists('load_division_for_member')) {
    function load_division_for_member()
    {
        $CI = &get_instance();
        $def_data = load_default_data();
        $countries_arr = '';

        $CI->db->SELECT("*");
        $CI->db->FROM('srp_erp_statemaster');
        $CI->db->WHERE('countyID', $def_data['country']);
        $CI->db->WHERE('masterID', $def_data['DD']);
        $CI->db->WHERE('type', 4);
        $CI->db->WHERE('divisionTypeCode', 'GN');
        $CI->db->order_by('Description');
        $countries = $CI->db->get()->result_array();
        //   $countries_arr = array('' => '');
        if (isset($countries)) {
            foreach ($countries as $row) {
                $countries_arr[trim($row['stateID'])] = trim($row['Description']);
            }
        }
  
        return $countries_arr;
    }
}


/*occupation type dropdown*/
if (!function_exists('load_occupationTypes')) {
    function load_occupationTypes()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_occupationtypes")->result_array();
        return $data;
    }
}

/*schools dropdown*/
if (!function_exists('load_ngoSchools')) {
    function load_ngoSchools()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_schools")->result_array();
        return $data;
    }
}

/* property type dropdown*/
if (!function_exists('load_propertyTypes')) {
    function load_propertyTypes()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_propertytypes")->result_array();
        return $data;
    }
}

/*help category dropdown*/
if (!function_exists('load_help_category')) {
    function load_help_category()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_helpcategories")->result_array();
        return $data;
    }
}

/* social grant dropdown*/
if (!function_exists('load_socialGrants')) {
    function load_socialGrants()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_socialgrants")->result_array();
        return $data;
    }
}

/* Community Periods dropdown*/
if (!function_exists('load_communityPeriods')) {
    function load_communityPeriods()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_financialperiodtypes")->result_array();
        return $data;
    }
}
/*school grades dropdown*/
if (!function_exists('load_grades')) {
    function load_grades()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_grades ORDER BY SortOrder ASC")->result_array();
        return $data;
    }
}

/*both help com member dropdown*/
if (!function_exists('load_both_help_member')) {
    function load_both_help_member()
    {
        $CI = &get_instance();
        $company_id = current_companyID();
        $data = $CI->db->query("SELECT DISTINCT memMaster.Com_MasterID,CName_with_initials,CNIC_No,C_Address FROM srp_erp_ngo_com_communitymaster memMaster INNER JOIN srp_erp_ngo_com_memberhelprequirements helpRq ON helpRq.Com_MasterID=memMaster.Com_MasterID INNER JOIN srp_erp_ngo_com_memberwillingtohelp helpWilling ON helpWilling.Com_MasterID=memMaster.Com_MasterID WHERE memMaster.companyID='{$company_id}' AND memMaster.isDeleted='0'")->result_array();

        return $data;
    }
}

/*get all titles*/
if (!function_exists('load_titles')) {
    function load_titles()
    {
        $CI = &get_instance();
        $CI->db->SELECT("TitleID,TitleDescription");
        $CI->db->FROM('srp_titlemaster');
        $CI->db->WHERE('Erp_companyID', current_companyID());
        $CI->db->order_by('TitleDescription');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select a title');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['TitleID'])] = trim($row['TitleDescription']);
            }
        }
        return $data_arr;
    }
}


/*Load company country codes*/
if (!function_exists('all_country_codes')) {
    function all_country_codes()
    {
        $CI = &get_instance();
        $CI->db->select("countryCode");
        $CI->db->from('srp_erp_countrymaster');
        $CI->db->where('countryCode !=', '');
        $CI->db->order_by('countryCode', 'ASC');
        $countryCode = $CI->db->get()->result_array();

        $countryCode_arr = array('' => 'Country Code');
        if (isset($countryCode)) {
            foreach ($countryCode as $row) {
                $countryCode_arr[trim($row['countryCode'])] = (trim($row['countryCode']));
            }
        }
        return $countryCode_arr;
    }
}

/*period type*/
if (!function_exists('all_periodType_drop')) {
    function all_periodType_drop()
    {
        $CI = &get_instance();

        $data = $CI->db->query("SELECT PeriodTypeID,Description FROM srp_erp_ngo_com_financialperiodtypes")->result_array();
        $data_arr = array('' => 'Select Period Type');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['PeriodTypeID'])] = trim($row['Description']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('periodType_without_Daily')) {
    function periodType_without_Daily()
    {
        $CI = &get_instance();

        $data = $CI->db->query("SELECT PeriodTypeID,Description FROM srp_erp_ngo_com_financialperiodtypes WHERE PeriodTypeID != 5 ")->result_array();
        $data_arr = array('' => 'Select Period Type');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['PeriodTypeID'])] = trim($row['Description']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('fetch_all_segment')) {
    function fetch_all_segment()
    {
        $CI = &get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
        $CI->db->select('segmentCode,description,segmentID');
        $CI->db->from('srp_erp_segment');
        $CI->db->where('status', 1);
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $data = $CI->db->get()->result_array();

        $data_arr = array('' => $CI->lang->line('common_select_segment')/*'Select Segment'*/);

        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['segmentID'])] = trim($row['segmentCode']) . ' | ' . trim($row['description']);
            }
        }

        return $data_arr;
    }
}

if (!function_exists('load_gender')) {
    function load_gender()
    {
        $CI = &get_instance();
        $CI->db->SELECT("genderID,name");
        $CI->db->FROM('srp_erp_gender');
        $CI->db->order_by('genderID', 'ASC');
        $data = $CI->db->get()->result_array();
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['genderID'])] = trim($row['name']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('load_gender_for_collection')) {
    function load_gender_for_collection()
    {
        $CI = &get_instance();
        $CI->db->SELECT("genderID,name");
        $CI->db->FROM('srp_erp_gender');
        $CI->db->order_by('genderID', 'ASC');
        $data = $CI->db->get()->result_array();
        $data_arr = array('0' => 'Both');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['genderID'])] = trim($row['name']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('fetch_occupationType_drop')) {
    function fetch_occupationType_drop()
    {
        $CI = &get_instance();
        $CI->db->SELECT("OccTypeID,Description");
        $CI->db->FROM('srp_erp_ngo_com_occupationtypes');
        $CI->db->order_by('OccTypeID', 'ASC');
        $Description = $CI->db->get()->result_array();

        if (isset($Description)) {
            foreach ($Description as $row) {
                $Description_arr[trim($row['OccTypeID'])] = trim($row['Description']);
            }
        }
        return $Description_arr;
    }
}


if (!function_exists('load_collectionType')) {
    function load_collectionType()
    {
        $CI = &get_instance();
        $CI->db->SELECT("*");
        $CI->db->FROM('srp_erp_ngo_com_collectiontypes');
        return $CI->db->get()->result_array();
    }
}

/*issue items*/
if (!function_exists('load_prq_action')) { /*get po action list*/
    function load_prq_action($itemIssueAutoID, $POConfirmedYN, $isDeleted)
    {
        $CI = &get_instance();
        $CI->load->library('session');
        $status = '<span class="pull-right">';

        if ($POConfirmedYN != 1 && $isDeleted == 0) {
            $status .= '<a onclick=\'fetchPage("system/communityNgo/ngo_hi_rental_item_issue_new",' . $itemIssueAutoID . ',"Edit Item Request","RTL"); \'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';
            $status .= '<a target="_blank" onclick="PageView_modal(\'RTL\',\'' . $itemIssueAutoID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';
            $status .= '<a onclick="delete_item(' . $itemIssueAutoID . ',\'Rental Item Request\');"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';
        }
        if ($POConfirmedYN == 1 && $isDeleted == 0) {
            $status .= '<a target="_blank" onclick="PageView_modal(\'RTL\',\'' . $itemIssueAutoID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';
            $status .= '<a target="_blank" onclick="return_item_modal_old(\'' . $itemIssueAutoID . '\')" ><span title="Return" rel="tooltip" class="glyphicon glyphicon-backward"></span></a>';
            //  $status .= '<a target="_blank" onclick="return_item_modal(\'' . $itemIssueAutoID . '\')" ><span title="Return" rel="tooltip" class="glyphicon glyphicon-backward"></span></a>';
        }

        $status .= '</span>';
        return $status;
    }
}

/*item return status*/
if (!function_exists('return_status')) {
    function return_status($con)
    {
        $status = '<center>';
        if ($con == 0) {
            $status .= '<span class="label label-danger">Not Returned</span>';
        } elseif ($con == 1) {
            $status .= '<span class="label label-success">Returned</span>';
        } else {
            $status .= '-';
        }
        $status .= '</center>';

        return $status;
    }
}


if (!function_exists('member_active_status')) {
    function member_active_status($active)
    {
        $status = '<center>';
        if ($active == 1) {
            $status .= '<span class="label" style="background-color:#8bc34a; color: #FFFFFF;">&nbsp;</span>';
        } elseif ($active == 0) {
            $status .= '<span class="label" style="background-color: rgba(255, 72, 49, 0.96); color: #FFFFFF;">&nbsp;</span>';
        } else {
            $status .= '-';
        }
        $status .= '</center>';

        return $status;
    }
}

if (!function_exists('viewImage')) {
    function viewImage($Image)
    {

        $companyCode = current_companyCode();
        $communityimage = get_all_community_images($Image, 'Community/' . $companyCode . '/MemberImages/', 'commMemNoImg');

        $status = '<center>';
        if ($Image) {
            $status .= '<img class="align-left"
                 src="' . $communityimage . '"
                 width="32" height="32">';
        } else {
            $status .= '<img class="align-left" src="' . $communityimage . '"
                                     alt="" width="32" height="32">';
        }
        $status .= '</center>';

        return $status;
    }
}

if (!function_exists('load_com_member_action')) { /*get member action list*/
    function load_com_member_action($Com_MasterID, $isActive, $isDeleted, $MemberCode, $CName_with_initials)
    {
        $CI = &get_instance();
        $CI->load->library('session');

        $company_id = current_companyID();
        $page = $CI->db->query("SELECT createPageLink FROM srp_erp_templatemaster
                              LEFT JOIN srp_erp_templates ON srp_erp_templatemaster.TempMasterID = srp_erp_templates.TempMasterID
                              WHERE srp_erp_templates.FormCatID = 530 AND companyID={$company_id}
                              ORDER BY srp_erp_templatemaster.FormCatID")->row('createPageLink');

        $status = '<span class="pull-right">';

        if ($isActive == 0) {
            $status .= '<a href="#"
                                   onclick="fetchPage(\'system/communityNgo/ngo_member_view\',' . $Com_MasterID . ',\'View Details - ' . $MemberCode . '\')"><span
                                        title="" rel="tooltip" class="glyphicon glyphicon-eye-open"
                                        data-original-title="View"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';
            $status .= '<a href="#"
                                   onclick="memberReportPdf(' . $Com_MasterID . ',\'Print Details - ' . $MemberCode . '\')"><span
                                        title="" rel="tooltip" class="glyphicon glyphicon-print"
                                        data-original-title="Print"></span></a>';
        } else {

            $status .= '<a href="#"
                                   onclick="fetchPage(\'system/communityNgo/ngo_member_view\',' . $Com_MasterID . ',\'View Details - ' . $MemberCode . ' \')"><span
                                        title="" rel="tooltip" class="glyphicon glyphicon-eye-open"
                                        data-original-title="View"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';
            $status .= '<a href="#"
                               onclick="fetchPage(\'' . $page . '\',' . $Com_MasterID . ',\'Edit Member - ' . $MemberCode . ' \')"><span
                                        title="Edit" rel="tooltip"
                                        class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';

            $status .= '<a href="#"
                                   onclick="memberReportPdf(' . $Com_MasterID . ',\'Print Details - ' . $MemberCode . '\')"><span
                                        title="" rel="tooltip" class="glyphicon glyphicon-print"
                                        data-original-title="Print"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';

            $status .= '<a onclick="delete_communityMembers(' . $Com_MasterID . ')"><span
                                        title="Delete"
                                        rel="tooltip"
                                        class="glyphicon glyphicon-trash CA_Alter_btn"
                                        style="color:#d15b47;"></span></a>';
        }

        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('view_detail_modal')) {
    function view_detail_modal($Com_MasterID, $MemberCode, $CName_with_initials)
    {
        $data = '<a href="#"  onclick="fetchPage(\'system/communityNgo/ngo_member_view\',' . $Com_MasterID . ',\'View Details - ' . $MemberCode . ' | ' . $CName_with_initials . '\',\'NGO\')">' . $CName_with_initials . '</a>';
        return $data;
    }
}


if (!function_exists('all_member_drop')) {
    function all_member_drop($status = TRUE)
    {
        $CI = &get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
        $CI->db->select("Com_MasterID,MemberCode,CName_with_initials");
        $CI->db->from('srp_erp_ngo_com_communitymaster');
        $CI->db->where('companyID', current_companyID());
        $CI->db->WHERE('comVerifiApproved', 1);
        $CI->db->where('isDeleted', 0);
        $CI->db->where('isActive', 1);

        $customer = $CI->db->get()->result_array();
        if ($status == TRUE) {
            //   $customer_arr = array('' => 'Select Member');
            if (isset($customer)) {
                foreach ($customer as $row) {
                    $customer_arr[trim($row['Com_MasterID'])] = trim($row['MemberCode']) . ' | ' . trim($row['CName_with_initials']);
                }
            }
        } else {
            $customer_arr = $customer;
        }
        return $customer_arr;
    }
}


if (!function_exists('all_member_drop_for_community')) {
    function all_member_drop_for_community($status = TRUE)
    {
        $CI = &get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
        $CI->db->select("Com_MasterID,MemberCode,CName_with_initials");
        $CI->db->from('srp_erp_ngo_com_communitymaster');
        $CI->db->where('companyID', current_companyID());
        $CI->db->WHERE('comVerifiApproved', 1);
        $CI->db->where('isDeleted', 0);

        $customer = $CI->db->get()->result_array();
        if ($status == TRUE) {
            $customer_arr = '';
            if (isset($customer)) {
                foreach ($customer as $row) {
                    $customer_arr[trim($row['Com_MasterID'])] = trim($row['MemberCode']) . ' | ' . trim($row['CName_with_initials']);
                }
            }
        } else {
            $customer_arr = $customer;
        }
        return $customer_arr;
    }
}

if (!function_exists('fetch_rental_item_issue')) {

    function fetch_rental_item_issue()
    {
        $CI = &get_instance();
        $companyID = $CI->common_data['company_data']['company_id'];

        $data = $CI->db->query('SELECT rentalItemID,rentalItemType,itemAutoID,rentalItemCode,rentalItemDes,PeriodTypeID,defaultUnitOfMeasureID,defaultUnitOfMeasure,currentStock,RentalPrice,srp_erp_ngo_com_rentalitems.SortOrder,rentalStatus,faID FROM srp_erp_ngo_com_rentalitems  WHERE  srp_erp_ngo_com_rentalitems.companyID = "' . $companyID . '" AND srp_erp_ngo_com_rentalitems.rentalStatus = "1" AND srp_erp_ngo_com_rentalitems.isDeleted = "0" ')->result_array();
        return $data;
    }
}


if (!function_exists('other_attachments')) {
    function other_attachments()
    {
        echo '<div class="modal fade" id="other_attachment_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="other_attachment_modal_label">Modal title</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2">&nbsp;</div>
                        <div class="col-md-10"><span class="pull-right">';

        echo form_open_multipart('', 'id="other_attachment_upload_form" class="form-inline"');

        echo '<div class="form-group">
                                <input type="text" class="form-control" id="other_attachmentDescription"
                                       name="other_attachmentDescription"
                                       placeholder="Description...">
                                    <!--Description-->
                                <input type="hidden" class="form-control" id="other_documentSystemCode"
                                       name="other_documentSystemCode">
                                <input type="hidden" class="form-control" id="other_documentID" name="other_documentID">
                                <input type="hidden" class="form-control" id="other_document_name" name="other_document_name">
                            </div>
                          <div class="form-group">
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
                                          type="file" name="other_document_file" id="other_document_file"></span>
                                  <a class="input-group-addon btn btn-default fileinput-exists" id="other_remove_id"
                                     data-dismiss="fileinput"><span class="glyphicon glyphicon-remove"
                                                                    aria-hidden="true"></span></a>
                              </div>
                          </div>
                          <button type="button" class="btn btn-default" onclick="other_attachment_upload()"><span
                                  class="glyphicon glyphicon-floppy-open color" aria-hidden="true"></span></button>
                                </form></span>
                        </div>
                    </div>
                    <table class="table table-striped table-condensed table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>File Name</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="other_attachment_modal_body" class="no-padding">
                        <tr class="danger">
                            <td colspan="5" class="text-center">No Attachment Found</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>';
    }
}

if (!function_exists('fetch_all_gl_codes')) {
    function fetch_all_gl_codes($code = NULL, $category = NULL)
    {
        $CI = &get_instance();
        $CI->db->SELECT("GLAutoID,systemAccountCode,GLSecondaryCode,GLDescription,systemAccountCode,subCategory,accountCategoryTypeID");
        $CI->db->from('srp_erp_chartofaccounts');
        if ($code) {
            $CI->db->where('subCategory', $code);
        }
        if ($category) {
            $CI->db->where('subCategory !=', $category);
        }
        $CI->db->where('controllAccountYN', 0);
        $CI->db->WHERE('masterAccountYN', 0);
        $CI->db->WHERE('accountCategoryTypeID !=', 4);
        $CI->db->where('approvedYN', 1);
        $CI->db->where('isActive', 1);
        $CI->db->where('isBank', 0);
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select GL Code');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['GLAutoID'])] = trim($row['systemAccountCode']) . ' | ' . trim($row['GLSecondaryCode']) . ' | ' . trim($row['GLDescription']) . ' | ' . trim($row['subCategory']);
            }
        }

        return $data_arr;
    }
}

if (!function_exists('income_gl_drop')) {
    function income_gl_drop()
    {
        $CI = &get_instance();
        $CI->db->select("GLAutoID,systemAccountCode,GLSecondaryCode,GLDescription,subCategory");
        $CI->db->from('srp_erp_chartofaccounts');
        $CI->db->where('masterCategory', 'PL');
        $CI->db->where('controllAccountYN', 0);
        $CI->db->where('masterAccountYN', 0);
        $CI->db->where('accountCategoryTypeID !=', 4);
        $CI->db->where('approvedYN', 1);
        $CI->db->where('isActive', 1);
        $CI->db->where('isBank', 0);
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Supplier GL Account');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['GLAutoID'])] = trim($row['systemAccountCode']) . ' | ' . trim($row['GLSecondaryCode']) . ' | ' . trim($row['GLDescription']) . ' | ' . trim($row['subCategory']);
            }
        }

        return $data_arr;
    }
}

if (!function_exists('receivable_gl_drop')) {
    function receivable_gl_drop()
    {
        $CI = &get_instance();
        $CI->db->select("GLAutoID,systemAccountCode,GLSecondaryCode,GLDescription,subCategory");
        $CI->db->from('srp_erp_chartofaccounts');
        $CI->db->where('masterCategory', 'BS');
        // $CI->db->where('controllAccountYN', 0);
        $CI->db->where('masterAccountYN', 0);
        $CI->db->where('accountCategoryTypeID !=', 4);
        $CI->db->where('approvedYN', 1);
        $CI->db->where('isActive', 1);
        $CI->db->where('isBank', 0);
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Supplier GL Account');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['GLAutoID'])] = trim($row['systemAccountCode']) . ' | ' . trim($row['GLSecondaryCode']) . ' | ' . trim($row['GLDescription']) . ' | ' . trim($row['subCategory']);
            }
        }

        return $data_arr;
    }
}


/*community master */
if (!function_exists('fetch_comMaster_lead')) {

    function fetch_comMaster_lead()
    {

        $CI = &get_instance();
        $companyID = $CI->common_data['company_data']['company_id'];
        $data = $CI->db->query("SELECT Com_MasterID,CName_with_initials,CNIC_No,companyID,C_Address FROM srp_erp_ngo_com_communitymaster WHERE companyID='{$companyID}' AND comVerifiApproved='1' AND isDeleted='0'")->result_array();
        return $data;
    }
}

/*fetch heads of family */
if (!function_exists('fetch_headsOf_family')) {

    function fetch_headsOf_family()
    {

        $CI = &get_instance();
        $companyID = $CI->common_data['company_data']['company_id'];

        $data = $CI->db->query("SELECT Com_MasterID,CName_with_initials,CNIC_No,srp_erp_ngo_com_familymaster.companyID,C_Address FROM srp_erp_ngo_com_familymaster INNER JOIN srp_erp_ngo_com_communitymaster ON srp_erp_ngo_com_familymaster.LeaderID=srp_erp_ngo_com_communitymaster.Com_MasterID WHERE srp_erp_ngo_com_familymaster.companyID='{$companyID}' AND srp_erp_ngo_com_familymaster.isDeleted='0' AND srp_erp_ngo_com_familymaster.isVerifyDocApproved='1'")->result_array();

        return $data;
    }
}

/*community member occupation */
if (!function_exists('fetch_memOccupation')) {
    function fetch_memOccupation()
    {
        $CI = &get_instance();
        $companyID = $CI->common_data['company_data']['company_id'];

        $data = $CI->db->query("SELECT Com_MasterID,MemJobID,JobCategoryID,gradeComID,companyID,IFNULL(WorkingPlace ,'')AS WorkingPlaceS,IFNULL(Address,'') AS AddressS FROM srp_erp_ngo_com_memjobs WHERE companyID='{$companyID}'")->result_array();

        return $data;
    }
}

/*Fam Ancestry */
if (!function_exists('fetch_family_ancestry')) {
    function fetch_family_ancestry()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT AncestryCatID,AncestryDes FROM srp_erp_ngo_com_ancestrycategory ")->result_array();

        return $data;
    }
}

/*Fam Economic Status */
if (!function_exists('fetch_fam_econStatus')) {
    function fetch_fam_econStatus()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT EconStateID,EconStateDes FROM srp_erp_ngo_com_familyeconomicstatemaster")->result_array();

        return $data;
    }
}

/*Fam existed hoses in enrolling */
if (!function_exists('fetch_house_exitInEnroll')) {
    function fetch_house_exitInEnroll()
    {
        $CI = &get_instance();
        $companyID = $CI->common_data['company_data']['company_id'];

        $data = $CI->db->query("SELECT hEnrollingID,FamilySystemCode,FamilyName,LeaderID FROM srp_erp_ngo_com_house_enrolling INNER JOIN srp_erp_ngo_com_familymaster ON srp_erp_ngo_com_familymaster.FamMasterID=srp_erp_ngo_com_house_enrolling.FamMasterID WHERE srp_erp_ngo_com_house_enrolling.companyID={$companyID} AND srp_erp_ngo_com_familymaster.isDeleted='0' AND srp_erp_ngo_com_familymaster.isVerifyDocApproved='1' AND (FamHouseSt ='0' OR FamHouseSt IS NULL)")->result_array();

        return $data;
    }
}

/*Fam Ownership Type master */
if (!function_exists('fetch_house_house_ownership')) {
    function fetch_house_house_ownership()
    {
        $CI = &get_instance();

        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_house_ownership_master WHERE ownershipAutoID !='4'")->result_array();

        return $data;
    }
}

/*Fam house type master */
if (!function_exists('fetch_house_type_master')) {
    function fetch_house_type_master()
    {
        $CI = &get_instance();

        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_house_type_master")->result_array();

        return $data;
    }
}

/* house facilities */
if (!function_exists('fetch_water_supply_master')) {
    function fetch_water_supply_master()
    {
        $CI = &get_instance();

        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_source_of_water_master")->result_array();

        return $data;
    }
}

if (!function_exists('fetch_sanitation_status_master')) {
    function fetch_sanitation_status_master()
    {
        $CI = &get_instance();

        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_sanitation_status_master")->result_array();

        return $data;
    }
}

if (!function_exists('fetch_disaster_type_master')) {
    function fetch_disaster_type_master()
    {
        $CI = &get_instance();

        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_disaster_type_master")->result_array();

        return $data;
    }
}

if (!function_exists('load_houseOwnership')) {
    function load_houseOwnership()
    {
        $CI = &get_instance();

        $CI->db->SELECT("*");
        $CI->db->FROM('srp_erp_ngo_com_house_ownership_master');
        $CI->db->order_by('ownershipAutoID');
        $hsOwnerSp = $CI->db->get()->result_array();
        $hsOwnerSp_arr = array('' => 'Select Ownership Type');
        if (isset($hsOwnerSp)) {
            foreach ($hsOwnerSp as $row) {
                $hsOwnerSp_arr[trim($row['ownershipAutoID'])] = trim($row['ownershipDescription']);
            }
        }
        return $hsOwnerSp_arr;
    }
}

if (!function_exists('load_houseTypes')) {
    function load_houseTypes()
    {
        $CI = &get_instance();

        $CI->db->SELECT("*");
        $CI->db->FROM('srp_erp_ngo_com_house_type_master');
        $CI->db->order_by('hTypeAutoID');
        $hsTypes = $CI->db->get()->result_array();
        $hsTypes_arr = array('' => 'Select House Type');
        if (isset($hsTypes)) {
            foreach ($hsTypes as $row) {
                $hsTypes_arr[trim($row['hTypeAutoID'])] = trim($row['hTypeDescription']);
            }
        }
        return $hsTypes_arr;
    }
}

/*donor project dropdown*/
if (!function_exists('fetch_project_com_drop')) {
    function fetch_project_com_drop()
    {
        $CI = &get_instance();
        $CI->db->SELECT("ngoProjectID,projectName");
        $CI->db->FROM('srp_erp_ngo_projects');
        $CI->db->WHERE('companyID', current_companyID());
        $CI->db->WHERE('masterID', 0);
        $CI->db->order_by('ngoProjectID', 'ASC');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Project');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['ngoProjectID'])] = trim($row['projectName']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('fetch_familyMemAct_drop')) { /*fetch fetch_familyMem act drop*/
    function fetch_familyMemAct_drop()
    {
        $CI = &get_instance();
        $CI->db->SELECT("companyID,Com_MasterID,CName_with_initials,HouseNo,C_Address");
        $CI->db->FROM('srp_erp_ngo_com_communitymaster');
        $CI->db->WHERE('companyID', current_companyID());
        $CI->db->WHERE('comVerifiApproved', 1);
        $CI->db->WHERE('isActive', '1');
        $CI->db->order_by('Com_MasterID', 'ASC');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Member');
        if (isset($data)) {
            foreach ($data as $row) {

                $data_arr[trim($row['Com_MasterID'])] = trim($row['CName_with_initials']) . ' | ' . trim($row['HouseNo']) . ' | ' . trim($row['C_Address']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('fetch_familyMems_drop')) { /*fetch fetch_familyMems_drop*/
    function fetch_familyMems_drop()
    {
        $CI = &get_instance();
        $CI->db->SELECT("companyID,Com_MasterID,CName_with_initials,HouseNo,C_Address");
        $CI->db->FROM('srp_erp_ngo_com_communitymaster');
        $CI->db->WHERE('companyID', current_companyID());
        $CI->db->WHERE('comVerifiApproved', 1);
        $CI->db->order_by('Com_MasterID', 'ASC');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Member');
        if (isset($data)) {
            foreach ($data as $row) {

                $data_arr[trim($row['Com_MasterID'])] = trim($row['CName_with_initials']) . ' | ' . trim($row['HouseNo']) . ' | ' . trim($row['C_Address']);
            }
        }
        return $data_arr;
    }
}

/* member parent drop */
if (!function_exists('fetch_parentMale_drop')) { /*fetch parent male_drop*/
    function fetch_parentMale_drop()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT Com_MasterID,CName_with_initials,CNIC_No,companyID,C_Address FROM srp_erp_ngo_com_communitymaster WHERE GenderID='1' AND comVerifiApproved='1' AND isDeleted='0' ORDER BY Com_MasterID")->result_array();
        return $data;
    }
}

if (!function_exists('fetch_parentFemale_drop')) { /*fetch parent female_drop*/
    function fetch_parentFemale_drop()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT Com_MasterID,CName_with_initials,CNIC_No,companyID,C_Address FROM srp_erp_ngo_com_communitymaster WHERE GenderID='2' AND comVerifiApproved='1' AND isDeleted='0' ORDER BY Com_MasterID")->result_array();
        return $data;
    }
}

/*fetch Fam Relationship dropdown*/
if (!function_exists('fetch_family_relationship')) {
    function fetch_family_relationship()
    {
        $CI = &get_instance();
        $companyID = $CI->common_data['company_data']['company_id'];
        if ($companyID == '79') {
            $CI->db->SELECT("relationshipID,relationship,genderID");
            $CI->db->FROM('srp_erp_family_relationship');
            $CI->db->order_by('relationshipID', 'ASC');
        } else {
            $CI->db->SELECT("relationshipID,relationship,genderID");
            $CI->db->FROM('srp_erp_family_relationship');
            $CI->db->where('relationshipID !=', '12');
            $CI->db->order_by('relationshipID', 'ASC');
        }
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Relationship');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['relationshipID'])] = trim($row['relationship']);
            }
        }
        return $data_arr;
    }
}

/*fetch community Relationship report dropdown*/
if (!function_exists('fetch_ngo_relationType_drop')) {
    function fetch_ngo_relationType_drop()
    {
        $CI = &get_instance();
        $companyID = $CI->common_data['company_data']['company_id'];
        if ($companyID == '79') {
            $data = $CI->db->query("SELECT relationshipID,relationship,genderID FROM srp_erp_family_relationship")->result_array();
        } else {
            $data = $CI->db->query("SELECT relationshipID,relationship,genderID FROM srp_erp_family_relationship WHERE relationshipID !='12'")->result_array();
        }
        return $data;
    }
}

/*fetch Fam Relationship dropdown*/
if (!function_exists('fetch_com_gender')) {
    function fetch_com_gender()
    {
        $CI = &get_instance();
        $CI->db->SELECT("genderID,name");
        $CI->db->FROM('srp_erp_gender');
        $CI->db->order_by('genderID', 'ASC');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Gender');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['genderID'])] = trim($row['name']);
            }
        }
        return $data_arr;
    }
}

/*fetch community Occupation report dropdown*/
if (!function_exists('fetch_ngo_memberType_drop')) {
    function fetch_ngo_memberType_drop()
    {
        $CI = &get_instance();

        $data = $CI->db->query("SELECT OccTypeID,Description FROM srp_erp_ngo_com_occupationtypes")->result_array();

        return $data;
    }
}

/*fetch all countries for select2*/
if (!function_exists('fetch_all_countries')) {
    function fetch_all_countries($status = true)/*Load all Supplier*/
    {
        $CI = &get_instance();
        $CI->db->SELECT("countryID,countryShortCode,CountryDes");
        $CI->db->FROM('srp_erp_countrymaster');
        $countries = $CI->db->get()->result_array();
        $countries_arr = array('' => 'Select Country');
        if (isset($countries)) {
            foreach ($countries as $row) {
                $countries_arr[trim($row['countryID'])] = trim($row['CountryDes']);
            }
        }
        return $countries_arr;
    }
}

if (!function_exists('fetch_com_beneficiary_types')) {
    function fetch_com_beneficiary_types()
    {
        $CI = &get_instance();
        $CI->db->SELECT("beneficiaryTypeID,description");
        $CI->db->FROM('srp_erp_ngo_benificiarytypes');
        $CI->db->WHERE('companyID', current_companyID());
        $CI->db->order_by('description');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select a type');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['beneficiaryTypeID'])] = trim($row['description']);
            }
        }
        return $data_arr;
    }
}

/*Load all campaign statemaster*/
if (!function_exists('all_statemaster')) {
    function all_statemaster($custom = true)
    {
        $CI = &get_instance();
        $CI->db->select("stateID,Description");
        $CI->db->from('srp_erp_statemaster');
        $states = $CI->db->get()->result_array();
        $states_arr = array('' => 'Select Province');
        if (isset($states)) {
            foreach ($states as $row) {
                $states_arr[trim($row['stateID'])] = (trim($row['Description']));
            }
        }
        return $states_arr;
    }
}

if (!function_exists('fetch_com_project_shortCode')) {
    function fetch_com_project_shortCode($beneficiaryID)
    {
        $CI = &get_instance();
        $CI->db->SELECT("projectShortCode");
        $CI->db->FROM('srp_erp_ngo_beneficiaryprojects bp');
        $CI->db->join('srp_erp_ngo_projects pro', 'bp.projectID = pro.ngoProjectID');
        $CI->db->WHERE('bp.beneficiaryID', $beneficiaryID);
        return $CI->db->get()->result_array();
    }
}

if (!function_exists('fetch_com_title')) {
    function fetch_com_title()
    {
        $CI = &get_instance();
        $CI->db->SELECT("TitleID,TitleDescription");
        $CI->db->FROM('srp_titlemaster');
        $CI->db->WHERE('Erp_companyID', current_companyID());
        $CI->db->order_by('TitleDescription');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select a title');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['TitleID'])] = trim($row['TitleDescription']);
            }
        }
        return $data_arr;
    }
}

/*gender dropdown*/
if (!function_exists('drop_gender')) {
    function drop_gender()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT srp_erp_gender.genderID,srp_erp_gender.name FROM srp_erp_gender ")->result_array();
        return $data;
    }
}
/*marital status dropdown*/
if (!function_exists('drop_maritalstatus')) {
    function drop_maritalstatus()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_maritalstatus ")->result_array();
        return $data;
    }
}

/*no. of houses count */
if (!function_exists('load_totHouses')) {
    function load_totHouses()
    {
        $CI = &get_instance();
        $companyID = $CI->common_data['company_data']['company_id'];

        $data = $CI->db->query("SELECT COUNT(*) AS totHouseCount1  FROM srp_erp_ngo_com_house_enrolling LEFT JOIN srp_erp_ngo_com_familymaster ON srp_erp_ngo_com_house_enrolling.FamMasterID=srp_erp_ngo_com_familymaster.FamMasterID WHERE srp_erp_ngo_com_house_enrolling.companyID='" . $companyID . "' AND (srp_erp_ngo_com_house_enrolling.FamHouseSt = '0' OR srp_erp_ngo_com_house_enrolling.FamHouseSt = NULL) AND srp_erp_ngo_com_familymaster.isDeleted = '0' AND srp_erp_ngo_com_familymaster.isVerifyDocApproved='1'")->row_array();

        return $data;
    }
}

/*community rental warehouse setup */

if (!function_exists('comNgoWarehouseBinFilter')) {
    function comNgoWarehouseBinFilter()
    {
        $CI = &get_instance();
        $CI->db->SELECT("srp_erp_warehousemaster.*");
        $CI->db->FROM('srp_erp_warehousemaster');
        $CI->db->WHERE('companyID', current_companyID());
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select a warehouse');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['wareHouseAutoID'])] = trim($row['wareHouseCode'] . " |" . $row['wareHouseDescription']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('alter_CommitteePosition')) {
    function alter_CommitteePosition($CommitteePositionID, $CommitteePositionDes, $usageCount)
    {
        $posDescription = "'" . $CommitteePositionDes . "'";
        $action = '<a onclick="edit_CommitteePosition(' . $CommitteePositionID . ', ' . $posDescription . ')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';

        if ($usageCount == 0) {
            $action .= '&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="delete_CommitteePosition(' . $CommitteePositionID . ', ' . $posDescription . ')">';
            $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';
        }

        return '<span class="pull-right">' . $action . '</span>';
    }
}

if (!function_exists('alter_CommitteeMas')) {
    function alter_CommitteeMas($CommitteeID, $CommitteeDes, $isActive, $usageCount)
    {
        $CommitteeDs = "'" . $CommitteeDes . "'";

        if ($isActive == '1') {
            $action = '<a onclick=\'fetchPage("system/communityNgo/ngo_mo_committee_contents",' . $CommitteeID . ',"Committee - ' . $CommitteeDes . '","NGO"); \'><span title="Sub Committees" style="color:#009688;font-size:18px;" rel="tooltip" class="fa fa-sitemap fa-lg" data-original-title="Sub Committees"></span></a>';
        } else {
            $action = '<a><span title="Sub Committees - Inactive" style="color:#d9534f;font-size:18px;" rel="tooltip" class="fa fa-sitemap fa-lg" data-original-title="Sub Committees"></span></a>';
        }

        $action .= '&nbsp;&nbsp; | &nbsp;&nbsp;<a class="CA_Alter_btn" onclick="editCommitteeMas(' . $CommitteeID . ', ' . $CommitteeDs . ', ' . $isActive . ')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';

        if ($usageCount == 0) {
            $action .= '&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="deleteCommitteeMas(' . $CommitteeID . ', ' . $CommitteeDs . ')">';
            $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';
        }

        return '<span class="pull-right">' . $action . '</span>';
    }
}


/*fetch sub committees dropdown*/
if (!function_exists('fetch_subCommittees_drop')) {
    function fetch_subCommittees_drop()
    {
        $CI = &get_instance();
        $companyID = $CI->common_data['company_data']['company_id'];

        $data = $CI->db->query("SELECT CommitteeAreawiseID,CommitteeAreawiseDes FROM srp_erp_ngo_com_committeeareawise WHERE companyID='" . $companyID . "'")->result_array();

        return $data;
    }
}

if (!function_exists('active_Member')) {
    function active_Member($YN)
    {
        if ($YN == 1) {
            $clCode = 'color:rgb(6,2,2);';
        } else {
            $clCode = 'color:rgb(203,203,203);';
        }
        return '<div style="text-align: center"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-ok" style="' . $clCode . '"></span></div>';
    }
}

/*committee positions */
if (!function_exists('fetch_committee_postitn')) {

    function fetch_committee_postitn()
    {

        $CI = &get_instance();
        $companyID = $CI->common_data['company_data']['company_id'];

        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_committeeposition WHERE companyID='{$companyID}' AND isDeleted='0'")->result_array();

        return $data;
    }
}

/*committee member service fetching */
if (!function_exists('cmtee_memServiceDel')) {
    function cmtee_memServiceDel($CmtMemServiceID, $value, $name, $CommitteeMemID)
    {

        $date_format_policy = date_format_policy();
        switch ($name) {

            case 'cmtmemservice':
                $html = '<div class="hideinput hide xxx_' . $CmtMemServiceID . '">
<input class="' . $name . '" type="text" value="' . $value . '" id="' . $name . '_' . $CmtMemServiceID . '" name="' . $name . '" >
</div>
<div class="showinput xx_' . $CmtMemServiceID . '" id="' . $name . '_1' . $CmtMemServiceID . '">' . $value . '</div>';
                break;
            case 'servicedate':
                $html = '<div class="hideinput hide xxx_' . $CmtMemServiceID . '">
                <input class="' . $name . '" onchange="' . $name . '_' . $CmtMemServiceID . '.val(this.value);" type="text" data-inputmask="alias:' . $date_format_policy . '" value="' . $value . '" id="' . $name . '_' . $CmtMemServiceID . '" name="' . $name . '" >
                </div>
                <div class="showinput xx_' . $CmtMemServiceID . '" id="' . $name . '_1' . $CmtMemServiceID . '">' . $value . '</div>';
                break;
            case 'order':

                $CI = &get_instance();
                $companyID = $CI->common_data['company_data']['company_id'];
                $sort = $CI->db->query("SELECT sortOrder FROM srp_erp_ngo_com_committeememberservices WHERE companyID='{$companyID}' AND CmtMemServiceID='{$CmtMemServiceID}'")->result_array();
                $select = '<div class="hideinput hide xxx_' . $CmtMemServiceID . '"><select class="" id="' . $name . '_' . $CmtMemServiceID . '" name="' . $name . '" >';
                if ($sort) {
                    foreach ($sort as $val) {
                        $selected = '';
                        if ($value == $val['sortOrder']) {
                            $selected = 'selected';
                        }
                        $select .= '<option ' . $selected . ' value="' . $val['sortOrder'] . '" >' . $val['sortOrder'] . '</option>';
                    }
                }

                $select .= '</select ></div>';

                $html = $select . '<div class="showinput xx_' . $CmtMemServiceID . '" id="' . $name . '_1' . $CmtMemServiceID . '">' . $value . '</div>';
                break;
        }
        return $html;
    }
}

/* families */
if (!function_exists('fetch_familyMaster')) {

    function fetch_familyMaster($status = true)
    {
        $CI = &get_instance();
        $CI->db->SELECT("FamMasterID,FamilySystemCode,FamilyName,CName_with_initials");
        $CI->db->FROM('srp_erp_ngo_com_familymaster');
        $CI->db->join('srp_erp_ngo_com_communitymaster', 'srp_erp_ngo_com_familymaster.LeaderID = srp_erp_ngo_com_communitymaster.Com_MasterID');
        $CI->db->WHERE('srp_erp_ngo_com_familymaster.isDeleted', '0');
        $CI->db->WHERE('srp_erp_ngo_com_familymaster.isVerifyDocApproved', '1');
        $CI->db->WHERE('srp_erp_ngo_com_familymaster.companyID', current_companyID());
        $CI->db->order_by('FamMasterID', 'ASC');
        $family = $CI->db->get()->result_array();
        if ($status) {
            $family_arr = array('' => 'Select Family');
        } else {
            $family_arr = '';
        }
        if (isset($family)) {
            foreach ($family as $row) {
                $family_arr[trim($row['FamMasterID'])] = trim($row['FamilySystemCode']) . ' |' . trim($row['CName_with_initials']);
            }
        }
        return $family_arr;
    }
}

/*committees */
if (!function_exists('fetch_committeesMaster')) {

    function fetch_committeesMaster($status = true)
    {
        $CI = &get_instance();
        $CI->db->SELECT("CommitteeID,CommitteeDes");
        $CI->db->FROM('srp_erp_ngo_com_committeesmaster');
        $CI->db->WHERE('isDeleted', '0');
        $CI->db->WHERE('companyID', current_companyID());
        $CI->db->order_by('CommitteeID', 'ASC');
        $cmntMas = $CI->db->get()->result_array();
        if ($status) {
            $cmnt_arr = array('' => 'Select Committee');
        } else {
            $cmnt_arr = '';
        }
        if (isset($cmntMas)) {
            foreach ($cmntMas as $row) {
                $cmnt_arr[trim($row['CommitteeID'])] = trim($row['CommitteeDes']);
            }
        }
        return $cmnt_arr;
    }
}

if (!function_exists('load_committeesMem')) {
    function load_committeesMem()
    {
        $CI = &get_instance();
        $CI->db->SELECT("CommitteeAreawiseID,CommitteeID,CommitteeHeadID,Com_MasterID,CName_with_initials");
        $CI->db->FROM('srp_erp_ngo_com_committeeareawise');
        $CI->db->join('srp_erp_ngo_com_communitymaster', 'srp_erp_ngo_com_committeeareawise.CommitteeHeadID = srp_erp_ngo_com_communitymaster.Com_MasterID');
        $CI->db->WHERE('srp_erp_ngo_com_committeeareawise.isActive', '1');
        $CI->db->WHERE('srp_erp_ngo_com_committeeareawise.companyID', current_companyID());
        $CI->db->group_by('CommitteeHeadID');
        $commitHd = $CI->db->get()->result_array();

        $commitHead_arr = array('' => 'Select Committee Head');

        if (isset($commitHd)) {
            foreach ($commitHd as $row) {
                $commitHead_arr[trim($row['Com_MasterID'])] = trim($row['CName_with_initials']);
            }
        }
        return $commitHead_arr;
    }
}

if (!function_exists('active_famLog')) {
    function active_famLog($YN)
    {
        if ($YN == 1) {
            $clCode = 'color:rgb(6,2,2);';
        } else {
            $clCode = 'color:rgb(203,203,203);';
        }
        return '<div style="text-align: center"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-ok" style="' . $clCode . '"></span></div>';
    }
}

/* Permanent Sickness */
if (!function_exists('fetch_permanentSickness')) {

    function fetch_permanentSicknes($status = true)
    {
        $CI = &get_instance();
        $CI->db->SELECT("sickAutoID,sickDescription");
        $CI->db->FROM('srp_erp_ngo_com_permanent_sickness');
        $CI->db->order_by('sickAutoID', 'ASC');
        $sickGrp = $CI->db->get()->result_array();
        if ($status) {
            $sick_arr = array('' => 'Select Sickness');
        } else {
            $sick_arr = '';
        }
        if (isset($sickGrp)) {
            foreach ($sickGrp as $row) {
                $sick_arr[trim($row['sickAutoID'])] = trim($row['sickDescription']);
            }
        }
        return $sick_arr;
    }
}

/* Vehicle Master */
if (!function_exists('fetch_vehicleMaster')) {

    function fetch_vehicleMaster($status = true)
    {
        $CI = &get_instance();
        $CI->db->SELECT("vehicleAutoID,vehicleDescription");
        $CI->db->FROM('srp_erp_ngo_com_vehicles_master');
        $CI->db->order_by('vehicleAutoID', 'ASC');
        $vehiGrp = $CI->db->get()->result_array();
        if ($status) {
            $vehi_arr = array('' => 'Select Vehicle');
        } else {
            $vehi_arr = '';
        }
        if (isset($vehiGrp)) {
            foreach ($vehiGrp as $row) {
                $vehi_arr[trim($row['vehicleAutoID'])] = trim($row['vehicleDescription']);
            }
        }
        return $vehi_arr;
    }
}

if (!function_exists('fetch_BloodGrpsDes')) {

    function fetch_BloodGrpsDes($status = true)
    {
        $CI = &get_instance();
        $CI->db->SELECT("BloodTypeID,BloodDescription");
        $CI->db->FROM('srp_erp_bloodgrouptype');
        $CI->db->order_by('BloodTypeID', 'ASC');
        $bloodGrp = $CI->db->get()->result_array();
        if ($status) {
            $blood_arr = array('' => 'Select Blood Group');
        } else {
            $blood_arr = '';
        }
        if (isset($bloodGrp)) {
            foreach ($bloodGrp as $row) {
                $blood_arr[trim($row['BloodTypeID'])] = trim($row['BloodDescription']);
            }
        }
        return $blood_arr;
    }
}

if (!function_exists('allEconState_drop')) {
    function allEconState_drop($type = 0)
    {
        $CI = &get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
        $CI->db->SELECT("t1.EconStateID,EconStateDes");
        $CI->db->FROM('srp_erp_ngo_com_familyeconomicstatemaster t1');
        $CI->db->order_by('EconStateID');
        $data = $CI->db->get()->result_array();


        if ($type == 0) {
            $data_arr = array('' => $CI->lang->line('CommunityNgo_fam_selEconState')/*'Select Economic Status'*/);
            if (isset($data)) {
                foreach ($data as $row) {
                    $data_arr[trim($row['EconStateID'])] = trim($row['EconStateDes']);
                }
                return $data_arr;
            }
        } else {
            return $data;
        }
    }
}


if (!function_exists('fetch_com_countryMaster_code')) {
    function fetch_com_countryMaster_code($countryID)
    {
        $CI = &get_instance();
        $CI->db->SELECT("countryShortCode");
        $CI->db->FROM('srp_erp_countrymaster');
        $CI->db->WHERE('countryID', $countryID);
        return $CI->db->get()->row('countryShortCode');
    }
}

if (!function_exists('fetch_com_stateMaster_name')) {
    function fetch_com_stateMaster_name($stateID)
    {
        $CI = &get_instance();
        $CI->db->SELECT("shortCode");
        $CI->db->FROM('srp_erp_statemaster');
        $CI->db->WHERE('stateID', $stateID);
        return $CI->db->get()->row('shortCode');
    }
}

/*Zakat project proposal*/
if (!function_exists('fetch_ngo_status')) {
    function fetch_ngo_status($documentID)
    {
        $CI = &get_instance();
        $CI->db->SELECT("statusID,description");
        $CI->db->FROM('srp_erp_ngo_status');
        $CI->db->WHERE('documentID', $documentID);
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $status = $CI->db->get()->result_array();
        $status_arr = array('' => 'Select');
        if (isset($status)) {
            foreach ($status as $row) {
                $status_arr[trim($row['statusID'])] = trim($row['description']);
            }
        }
        return $status_arr;
    }
}

if (!function_exists('fetch_project_donor_drop')) {
    function fetch_project_donor_drop($status = false)
    {
        $CI = &get_instance();
        $CI->db->SELECT("ngoProjectID,projectName");
        $CI->db->FROM('srp_erp_ngo_projects pro');

        if ($status == true) {
            $CI->db->WHERE('pro.companyID', current_companyID());
            $CI->db->WHERE('masterID', 0);
            $CI->db->JOIN('srp_erp_ngo_projectowners pr', 'pr.projectID = pro.ngoProjectID');
            $CI->db->WHERE('isAdd', 1);
            $CI->db->WHERE('employeeID', $CI->common_data['current_userID']);
        } else {
            $CI->db->WHERE('companyID', current_companyID());
            $CI->db->WHERE('masterID', 0);
        }
        $CI->db->order_by('ngoProjectID', 'ASC');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Project');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['ngoProjectID'])] = trim($row['projectName']);
            }
        }
        return $data_arr;
    }
}

/*ZAKAT AGE GROUPING */
if (!function_exists('fetch_fam_ageGroup')) {
    function fetch_fam_ageGroup()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT * FROM srp_erp_ngo_com_zakatagegroupmaster")->result_array();

        return $data;
    }
}

if (!function_exists('econ_status')) {
    function econ_status($benificiaryID)
    {
        $CI = &get_instance();
        $CI->db->SELECT("EconStateID,EconStateDes");
        $CI->db->FROM('srp_erp_ngo_com_familyeconomicstatemaster');
        $CI->db->order_by('srp_erp_ngo_com_familyeconomicstatemaster.EconStateID', 'ASC');
        $econState = $CI->db->get()->result_array();

        $select = '<div><select class="" id="EconStateIDs" name="EconStateIDs[]">';
        $select .= '<option value=""></option>';
        foreach ($econState as $val) {

            $select .= '<option value="' . $val['EconStateID'] . '" >' . $val['EconStateDes'] . '</option>';
        }

        $select .= '</select ></div>';

        return $select;
    }
}

/* Notice Board */

/*region dropdown*/
if (!function_exists('report_load_region')) {
    function report_load_region()
    {
        $CI = &get_instance();
        $def_data = load_default_data();

        $companyID = $CI->common_data['company_data']['company_id'];

        $data = '';
        if (!empty($def_data) && !empty($def_data['country'])) {
            $data = $CI->db->query("SELECT * FROM srp_erp_statemaster WHERE countyID={$def_data['country']} AND masterID={$def_data['DD']} AND srp_erp_statemaster.type='4' AND divisionTypeCode='MH' ORDER BY Description")->result_array();
        } else {

            $data2Count = $CI->db->query("SELECT countryID FROM srp_erp_company WHERE srp_erp_company.company_id = {$companyID}")->row_array();
            $countryFil = $data2Count['countryID'];
            if (!empty($countryFil)) {
                $countrysFil = $countryFil;
            } else {
                $countrysFil = '';
            }
            $data = $CI->db->query("SELECT * FROM srp_erp_statemaster WHERE countyID={$countrysFil} AND srp_erp_statemaster.type='4' AND divisionTypeCode='MH' ORDER BY Description")->result_array();
        }

        return $data;
    }
}
/*division dropdown*/
if (!function_exists('load_divisionForUploads')) {
    function load_divisionForUploads()
    {
        $CI = &get_instance();
        $def_data = load_default_data();

        $companyID = $CI->common_data['company_data']['company_id'];

        $data = '';
        if (!empty($def_data) && !empty($def_data['country'])) {
            $data = $CI->db->query("SELECT * FROM srp_erp_statemaster WHERE countyID={$def_data['country']} AND masterID={$def_data['DD']} AND srp_erp_statemaster.type='4' AND divisionTypeCode='GN' ORDER BY Description")->result_array();
        } else {

            $data2Count = $CI->db->query("SELECT countryID FROM srp_erp_company WHERE srp_erp_company.company_id = {$companyID}")->row_array();
            $countryFil = $data2Count['countryID'];
            if (!empty($countryFil)) {
                $countrysFil = $countryFil;
            } else {
                $countrysFil = '';
            }
            $data = $CI->db->query("SELECT * FROM srp_erp_statemaster WHERE countyID={$countrysFil} AND srp_erp_statemaster.type='4' AND divisionTypeCode='GN' ORDER BY Description")->result_array();
        }

        return $data;
    }
}

if (!function_exists('Notice_Type_drop')) {
    function Notice_Type_drop()
    {
        $CI = &get_instance();
        $CI->db->SELECT("*");
        $CI->db->FROM('srp_erp_ngo_com_noticeboardmaster');
        $CI->db->order_by('NoticeTypeID');
        $data = $CI->db->get()->result_array();

        $data_arr = array('' => 'Select Type');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['NoticeTypeID'])] = trim($row['NoticeType']);
            }
        }
        return $data_arr;
    }
}
if (!function_exists('Notice_Type_filter')) {
    function Notice_Type_filter()
    {
        $CI = &get_instance();
        $CI->db->SELECT("*");
        $CI->db->FROM('srp_erp_ngo_com_noticeboardmaster');
        $CI->db->order_by('NoticeTypeID');
        $data = $CI->db->get()->result_array();

        $data_arr = array('' => 'All');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['NoticeTypeID'])] = trim($row['NoticeType']);
            }
        }
        return $data_arr;
    }
}

/*Load all campaign status*/
if (!function_exists('all_states')) {
    function all_states($custom = true)
    {
        $CI = &get_instance();
        $companyID = $CI->common_data['company_data']['company_id'];

        $data2Count = $CI->db->query("SELECT countryID FROM srp_erp_company WHERE srp_erp_company.company_id = {$companyID}")->row_array();
        $countryFil = $data2Count['countryID'];
        if (!empty($countryFil)) {
            $countrysFil = $countryFil;
        } else {
            $countrysFil = '';
        }

        $CI->db->select("stateID,Description");
        $CI->db->from('srp_erp_statemaster');
        $CI->db->where('countyID', $countrysFil);
        $states = $CI->db->get()->result_array();
        $states_arr = array('' => 'Select Province');
        if (isset($states)) {
            foreach ($states as $row) {
                $states_arr[trim($row['stateID'])] = (trim($row['Description']));
            }
        }
        return $states_arr;
    }
}

/*Load all countries for select2*/
if (!function_exists('load_countries_compare')) {
    function load_countries_compare($status = true)
    {

        $CI = &get_instance();

        $companyID = $CI->common_data['company_data']['company_id'];

        $dataCount = $CI->db->query("SELECT srp_erp_statemaster.countyID FROM srp_erp_ngo_com_regionmaster INNER JOIN srp_erp_statemaster ON srp_erp_ngo_com_regionmaster.stateID=srp_erp_statemaster.stateID WHERE srp_erp_ngo_com_regionmaster.companyID = {$companyID}")->row_array();

        if (empty($dataCount)) {

            $data2Count = $CI->db->query("SELECT countryID FROM srp_erp_company WHERE srp_erp_company.company_id = {$companyID}")->row_array();
            $countryFil = $data2Count['countryID'];
            if (!empty($countryFil)) {
                $countrysFil = $countryFil;
            } else {
                $countrysFil = '';
            }
        } else {
            $countrysFil = $dataCount['countyID'];
        }
        $data = $CI->db->query("SELECT countryID,countryShortCode,CountryDes FROM srp_erp_countrymaster WHERE countryID={$countrysFil}")->result_array();

        return $data;
    }
}

/*all community companies drop down*/
if (!function_exists('load_allComCompanies')) {
    function load_allComCompanies()
    {
        $CI = &get_instance();
        $data = $CI->db->query("SELECT * FROM srp_erp_company")->result_array();
        return $data;
    }
}

if (!function_exists('get_all_community_images')) {
    function get_all_community_images($communityImgtesrt, $path, $type)
    {
        $CI = &get_instance();
        $CI->load->library('s3');
        if ($type == "commMemNoImg") {
            $noimage = $CI->s3->getMyAuthenticatedURL('Community/no-memberImg.png', 3600);
        } else if ($type == "communityNoImg") {
            $noimage = $CI->s3->getMyAuthenticatedURL('Community/no-image.png', 3600);
        }
        if ($communityImgtesrt != '') {
            $comm_image = $CI->s3->getMyAuthenticatedURL($communityImgtesrt, 3600);
        } else {
            $comm_image = $noimage;
        }

        return $comm_image;
    }
}


/*community member approval list */
if (!function_exists('fetch_comMasters_approval')) {

    function fetch_comMasters_approval()
    {

        $CI = &get_instance();
        $CI->db->SELECT("FamMasterID,FamilySystemCode,FamilyName,CName_with_initials");
        $CI->db->FROM('srp_erp_ngo_com_familymaster');
        $CI->db->join('srp_erp_ngo_com_communitymaster', 'srp_erp_ngo_com_familymaster.LeaderID = srp_erp_ngo_com_communitymaster.Com_MasterID');
        $CI->db->WHERE('srp_erp_ngo_com_familymaster.isDeleted', '0');
        $CI->db->WHERE('srp_erp_ngo_com_familymaster.companyID', current_companyID());
        $CI->db->WHERE('srp_erp_ngo_com_familymaster.VerificationDocID !=', 0);
        $CI->db->WHERE('srp_erp_ngo_com_familymaster.VerificationDocID IS NOT NULL', NULL);
        $CI->db->order_by('FamMasterID', 'ASC');
        return $CI->db->get()->result_array();
    }
}

if (!function_exists('fetch_memApproval_status')) {
    function fetch_memApproval_status()
    {
        $companyID = current_companyID();
        $CI = &get_instance();
        $statusCount = $CI->db->query("SELECT * FROM (
                                          SELECT  (SELECT COUNT(FamMasterID) AS femCount FROM srp_erp_ngo_com_familymaster WHERE companyID={$companyID} AND isDeleted=0 AND (srp_erp_ngo_com_familymaster.VerificationDocID !=0 OR srp_erp_ngo_com_familymaster.VerificationDocID IS NOT NULL)
                                          AND isVerifyDocApproved=1) AS memApproved,(SELECT COUNT(FamMasterID) AS femCount FROM srp_erp_ngo_com_familymaster WHERE companyID={$companyID}
                                          AND isDeleted=0 AND (srp_erp_ngo_com_familymaster.VerificationDocID !=0 OR srp_erp_ngo_com_familymaster.VerificationDocID IS NOT NULL) AND isVerifyDocApproved = 4) AS noMemApproved,(SELECT COUNT(FamMasterID) AS femCount FROM srp_erp_ngo_com_familymaster WHERE companyID={$companyID}
                                          AND isDeleted=0 AND (srp_erp_ngo_com_familymaster.VerificationDocID !=0 OR srp_erp_ngo_com_familymaster.VerificationDocID IS NOT NULL) AND isVerifyDocApproved = 5) AS memAppClarify, (SELECT COUNT(FamMasterID) AS femCount FROM
                                          srp_erp_ngo_com_familymaster WHERE companyID={$companyID} AND isDeleted=0 AND (VerificationDocID !=0 OR VerificationDocID IS NOT NULL) AND (isVerifyDocApproved =0 OR isVerifyDocApproved IS NULL)) AS pendingApprovals
                                       ) AS t1")->row_array();
        return $statusCount;
    }
}


if (!function_exists('fetch_approvalRegions')) {
    function fetch_approvalRegions()
    {
        $CI = &get_instance();
        $companyID = current_companyID();

        $data = $CI->db->query("SELECT srp_erp_statemaster.stateID,srp_erp_statemaster.Description, count(FamMasterID) AS femCount
                                FROM srp_erp_statemaster
                                JOIN srp_erp_ngo_com_communitymaster AS comAr ON comAr.RegionID = srp_erp_statemaster.stateID
                                JOIN srp_erp_ngo_com_familymaster AS femAr ON femAr.LeaderID = comAr.Com_MasterID
                                WHERE comAr.companyID={$companyID} AND comAr.isDeleted = 0 AND femAr.isDeleted=0 AND srp_erp_statemaster.type = 4 AND srp_erp_statemaster.divisionTypeCode = 'MH' AND (femAr.VerificationDocID != 0 OR femAr.VerificationDocID IS NOT NULL) GROUP BY comAr.RegionID")->result_array();

        return $data;
    }
}

/*gender dropdown*/
if (!function_exists('fetch_memGender_approvals')) {
    function fetch_memGender_approvals()
    {
        $CI = &get_instance();
        $companyID = current_companyID();
        $data = $CI->db->query("SELECT srp_erp_gender.genderID,srp_erp_gender.name as genderName, count(FamMasterID) AS femCount FROM srp_erp_gender LEFT JOIN srp_erp_ngo_com_communitymaster comGender ON comGender.GenderID=srp_erp_gender.genderID INNER JOIN srp_erp_ngo_com_familymaster femGen ON femGen.LeaderID = comGender.Com_MasterID WHERE comGender.companyID={$companyID} AND comGender.isDeleted = 0 AND femGen.isDeleted=0 AND (femGen.VerificationDocID != 0 OR femGen.VerificationDocID IS NOT NULL) GROUP BY comGender.GenderID")->result_array();
        return $data;
    }
}

if (!function_exists('memApprovalsPagination')) {
    function memApprovalsPagination()
    {
        $CI = &get_instance();
        $CI->load->library("pagination");
        //$CI->load->library("s3");

        $data_pagination = $CI->input->post('data_pagination');
        $per_page = 10;
        $companyID = current_companyID();

        $count = $CI->db->query("SELECT COUNT(FamMasterID) AS femCount FROM srp_erp_ngo_com_familymaster WHERE companyID={$companyID}
                                 AND isDeleted=0 AND (srp_erp_ngo_com_familymaster.VerificationDocID !=0 OR srp_erp_ngo_com_familymaster.VerificationDocID IS NOT NULL)")->row('femCount');


        $isFiltered = 0;
        $searchKey_filter = '';
        $alpha_filter = '';
        $area_filter = '';
        $gender_filter = '';
        $apprvlStatus_filter = '';

        $searchKey = $CI->input->post('searchKey');
        $letter = $CI->input->post('letter');
        $genderApr = $CI->input->post('genderApr');
        $regionAr = $CI->input->post('regionAr');
        $apprvlStatus = $CI->input->post('apprvlStatus');


        if ($apprvlStatus != '' && $apprvlStatus != 'null') {
            if ($apprvlStatus == 3) {
                $apprvlStatus_filter = " AND isVerifyDocApproved = 0 ";
            } else {
                $apprvlStatus_filter = " AND isVerifyDocApproved = " . $apprvlStatus;
            }
            $isFiltered = 1;
        }

        if (!empty($genderApr) && $genderApr != 'null') {
            $genderApr = array($CI->input->post('genderApr'));
            $whereIN = "( " . join("' , '", $genderApr) . " )";
            $gender_filter = " AND t1.GenderID IN " . $whereIN;
            $isFiltered = 1;
        }

        if (!empty($regionAr) && $regionAr != 'null') {
            $regionAr = array($CI->input->post('regionAr'));
            $whereIN = "( " . join("' , '", $regionAr) . " )";
            $area_filter = " AND stateAr.stateID IN " . $whereIN;
            $isFiltered = 1;
        }

        if ($letter != null) {
            $alpha_filter = ' AND (CName_with_initials LIKE \'' . $letter . '%\') ';
            $isFiltered = 1;
        }

        if ($searchKey != '') {

            $searchKey_filter = " WHERE ((MemberCode Like '%" . $searchKey . "%') OR (CName_with_initials Like '%" . $searchKey . "%') OR (provinceAr Like '%" . $searchKey . "%') OR (regionAr Like '%" . $searchKey . "%') OR (genderName = '" . $searchKey . "') OR (CNIC_No Like '%" . $searchKey . "%') OR (PrimaryNumber Like '%" . $searchKey . "%') OR (familyNm Like '%" . $searchKey . "%'))";
            $isFiltered = 1;
        }

        $countFilter = 0;

        if ($isFiltered == 1) {
            $countFilterWhere = $gender_filter . $area_filter . $alpha_filter . $apprvlStatus_filter;
            $convertFormat = convert_date_format_sql();
            $countFilter = $CI->db->query("SELECT COUNT(FamMasterID) AS femCount FROM(
                                                   SELECT  t1.Com_MasterID,t1.MemberCode,t1.CName_with_initials,srp_erp_gender.genderID AS Gender,t1.TP_Mobile AS PrimaryNumber,stateAr.Description AS regionAr ,stateProvince.Description AS provinceAr,
                                    t1.C_Address, TitleDescription,t1.EmailID, t1.CNIC_No, IFNULL(srp_erp_ngo_com_familymaster.LeaderID,0) AS pendingData,
                                    DATE_FORMAT(CDOB, '{$convertFormat}') AS CDOBs,IF(t1.GenderID=1, 'Male', 'Female') AS genderStr,stateProvince.Description,srp_erp_gender.name as genderName, CImage, srp_erp_ngo_com_familymaster.FamMasterID, CONCAT(FamilySystemCode,' - ', FamilyName )AS familyNm,
                                    sysDocType.description AS sysDocDescription,isVerifyDocApproved,VerificationDocID
                                    FROM srp_erp_ngo_com_communitymaster AS t1
                                    INNER JOIN srp_erp_ngo_com_familymaster ON t1.Com_MasterID=srp_erp_ngo_com_familymaster.LeaderID
                                    JOIN srp_titlemaster ON srp_titlemaster.TitleID=t1.TitleID
                                    LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID=t1.GenderID
                                    LEFT JOIN srp_erp_statemaster AS stateAr ON stateAr.stateID=t1.RegionID
                                    LEFT JOIN srp_erp_statemaster AS stateCoun ON stateCoun.stateID=t1.countyID
                                    LEFT JOIN srp_erp_statemaster AS stateProvince ON stateProvince.stateID=t1.provinceID
                                    LEFT JOIN srp_erp_system_document_types AS sysDocType ON sysDocType.id=srp_erp_ngo_com_familymaster.VerificationDocID
                                               WHERE t1.companyID={$companyID} AND t1.isDeleted = 0 AND srp_erp_ngo_com_familymaster.isDeleted=0 AND (srp_erp_ngo_com_familymaster.VerificationDocID !=0 OR srp_erp_ngo_com_familymaster.VerificationDocID IS NOT NULL) {$countFilterWhere}
                                           ) AS t1 {$searchKey_filter} ")->row('femCount');
        }

        // var_dump($countFilter);
        //  exit;

        $config = array();
        $config["base_url"] = "#memApprovals_list";
        $config["total_rows"] =  ($isFiltered == 1) ? $countFilter : $count;
        $config["per_page"] = $per_page;
        $config["data_page_attr"] = 'data-emp-pagination';
        $config["uri_segment"] = 3;

        $CI->pagination->initialize($config);

        $page = (!empty($data_pagination)) ? (($data_pagination - 1) * $per_page) : 0;
        $employeeData = load_approvalMembers_data($page, $per_page);
        $dataCount = $employeeData['dataCount'];

        $data["femCount"] = $count;
        $data["memApproval_list"] = $employeeData['memApproval_list'];
        $data["pagination"] = $CI->pagination->create_links_memApproval_master();
        $data["per_page"] = $per_page;
        $thisPageStartNumber = ($page + 1);
        $thisPageEndNumber = $page + $dataCount;

        if ($isFiltered == 1) {
            $data["filterDisplay"] = "Showing {$thisPageStartNumber} to {$thisPageEndNumber} of {$countFilter} entries (filtered from {$count} total entries)";
        } else {
            $data["filterDisplay"] = "Showing {$thisPageStartNumber} to {$thisPageEndNumber} of {$count} entries";
        }

        return $data;
    }
}

if (!function_exists('load_approvalMembers_data')) {
    function load_approvalMembers_data($page, $per_page)
    {

        //var_dump($page,$per_page);

        $searchKey_filter = '';
        $alpha_filter = '';
        $area_filter = '';
        $designation_filter = '';
        $apprvlStatus_filter = '';

        $CI = &get_instance();
        $letter = $CI->input->post('letter');
        $searchKey = $CI->input->post('searchKey');
        $genderApr = $CI->input->post('genderApr');
        $regionAr = $CI->input->post('regionAr');
        $apprvlStatus = $CI->input->post('apprvlStatus');

        if ($apprvlStatus != '' && $apprvlStatus != 'null') {
            if ($apprvlStatus == 3) {
                $apprvlStatus_filter = " AND isVerifyDocApproved = 0 ";
            } else {
                $apprvlStatus_filter = " AND isVerifyDocApproved = " . $apprvlStatus;
            }
        }

        if (!empty($genderApr) && $genderApr != 'null') {
            $genderApr = array($CI->input->post('genderApr'));
            $whereIN = "( " . join("' , '", $genderApr) . " )";
            $designation_filter = " AND t1.GenderID IN " . $whereIN;
        }

        if (!empty($regionAr) && $regionAr != 'null') {
            $regionAr = array($CI->input->post('regionAr'));
            $whereIN = "( " . join("' , '", $regionAr) . " )";
            $area_filter = " AND stateAr.stateID IN " . $whereIN;
        }

        if ($letter != null) {
            $alpha_filter = ' AND ( CName_with_initials LIKE \'' . $letter . '%\') ';
        }

        if ($searchKey != '') {
            $searchKey_filter = " WHERE ((MemberCode Like '%" . $searchKey . "%') OR (CName_with_initials Like '%" . $searchKey . "%') OR (provinceAr Like '%" . $searchKey . "%') OR (regionAr Like '%" . $searchKey . "%') OR (genderName = '" . $searchKey . "') OR (CNIC_No Like '%" . $searchKey . "%') OR (PrimaryNumber Like '%" . $searchKey . "%') OR (familyNm Like '%" . $searchKey . "%'))";
        }

        $companyID = current_companyID();
        $convertFormat = convert_date_format_sql();
        $where = "t1.isDeleted = 0 AND srp_erp_ngo_com_familymaster.isDeleted=0 AND (srp_erp_ngo_com_familymaster.VerificationDocID !=0 OR srp_erp_ngo_com_familymaster.VerificationDocID IS NOT NULL) AND t1.companyID = " . $companyID . $designation_filter . $area_filter . $apprvlStatus_filter . $alpha_filter;

        $data = $CI->db->query("SELECT * FROM(
                                    SELECT  t1.Com_MasterID,t1.MemberCode,t1.CName_with_initials,srp_erp_gender.genderID AS Gender,t1.CountryCodePrimary,t1.TP_Mobile AS PrimaryNumber,stateAr.Description AS regionAr ,stateProvince.Description AS provinceAr,
                                    t1.C_Address, TitleDescription,t1.EmailID, t1.CNIC_No,DATE_FORMAT(CDOB, '{$convertFormat}') AS CDOBs,IF(t1.GenderID=1, 'Male', 'Female') AS genderStr,DATE_FORMAT(FamilyAddedDate, '{$convertFormat}') AS FamilyAddedDates,stateProvince.Description,srp_erp_gender.name as genderName, CImage, srp_erp_ngo_com_familymaster.FamMasterID, CONCAT( FamilySystemCode,' - ', FamilyName )AS familyNm,
                                    sysDocType.description AS sysDocDescription,isVerifyDocApproved,srp_erp_ngo_com_familymaster.VerificationDocID
                                    FROM srp_erp_ngo_com_communitymaster AS t1
                                    INNER JOIN srp_erp_ngo_com_familymaster ON t1.Com_MasterID=srp_erp_ngo_com_familymaster.LeaderID
                                    JOIN srp_titlemaster ON srp_titlemaster.TitleID=t1.TitleID
                                    LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID=t1.GenderID
                                    LEFT JOIN srp_erp_statemaster AS stateAr ON stateAr.stateID=t1.RegionID
                                    LEFT JOIN srp_erp_statemaster AS stateCoun ON stateCoun.stateID=t1.countyID
                                    LEFT JOIN srp_erp_statemaster AS stateProvince ON stateProvince.stateID=t1.provinceID
                                    LEFT JOIN srp_erp_system_document_types AS sysDocType ON sysDocType.id=srp_erp_ngo_com_familymaster.VerificationDocID
                                    WHERE {$where}
                                ) t1 {$searchKey_filter} ORDER BY t1.MemberCode LIMIT {$page}, {$per_page}")->result_array();
        //echo $CI->db->last_query();
        //  var_dump($data);
        $memApproval_list = $data;
        $returnData = '';
        $color = "#FF0";
        if (!empty($memApproval_list)) {

            $CI->load->library('s3');
            $male_img = $CI->s3->getMyAuthenticatedURL('images/users/male.png', 3600);
            $female_img = $CI->s3->getMyAuthenticatedURL('images/users/female.png', 3600);

            foreach ($memApproval_list as $key => $appMemData) {
                $appMemID = $appMemData['Com_MasterID'];

                //$CImage = CImageCheck($appMemData['CImage'], $appMemData['Gender']);
                $CImage = trim($appMemData['CImage']);
                if ($CImage == '') {
                    $CImage = ($appMemData['Gender'] == 1) ? $male_img : $female_img;
                } elseif ($CImage == 'images/users/male.png') {
                    $CImage = $male_img;
                } elseif ($CImage == 'images/users/female.png') {
                    $CImage = $female_img;
                } else {
                    $CImage = $CI->s3->getMyAuthenticatedURL($CImage, 3600);
                    /*if( $CI->s3->getMyObjectInfo($CImage) ){
                        $CImage = $CI->s3->getMyAuthenticatedURL($CImage, 3600);
                    }
                    else{
                        $CImage = ($appMemData['Gender'] == 1)? $male_img: $female_img;
                    }*/
                }


                $firstDivStyle = ($key == 0) ? ' style="margin-top: 1px;"' : '';
                $firstDivInput = ($key == 0) ? '<input id="first-in-emp-list" />' : '';


                $mailID = $appMemData['EmailID'];
                $appMemName = $appMemData['CName_with_initials'];
                $empCode = $appMemData['MemberCode'];
                $DOJ = $appMemData['doj'];
                $familyNm = $appMemData['familyNm'];
                $provinceAr = $appMemData['provinceAr'];
                $regionAr = $appMemData['regionAr'];
                $genderStr = $appMemData['genderStr'];
                $CountryCode = $appMemData['CountryCodePrimary'];
                $mobileNo = $appMemData['PrimaryNumber'];
                $CNIC_No = $appMemData['CNIC_No'];
                $C_Address = $appMemData['C_Address'];
                $FamilyAddedDate = $appMemData['FamilyAddedDates'];
                $sysDocDescription = $appMemData['sysDocDescription'];
                $VerificationDocID = $appMemData['VerificationDocID'];
                $isVerifyDocApproved = $appMemData['isVerifyDocApproved'];
                $phone_no = preg_replace('/[^0-9]/', '', ($CountryCode . '|' . $mobileNo));

                if ($isVerifyDocApproved == '1') {
                    $label = 'success';
                    $apprvlStatus = 'Approved';
                } elseif ($isVerifyDocApproved == '4') {
                    $label = 'danger';
                    $apprvlStatus = 'Cancelled';
                } elseif ($isVerifyDocApproved == '5') {
                    $label = 'warning';
                    $apprvlStatus = 'Pending For Clarification';
                } else {
                    $label = 'info';
                    $apprvlStatus = 'Remaining';
                }

                $returnData .= $firstDivInput;
                $returnData .= '<div class="candidate-description client-description applicants-content" ' . $firstDivStyle . '>
                                    <div class="language-print client-des clearfix">
                                        <div class="aplicants-pic pull-left">
                                            <img src="' . $CImage . '" alt="">
                                            <ul class="list-inline">

                                            </ul>
                                        </div>

                                        <div class="clearfix">
                                            <div class="pull-left">
                                                <h5 class="memAppNameLink" onclick="openVerify_docs(' . $appMemID . ', \'' . $appMemName . '\', ' . $VerificationDocID . ',\'' . $sysDocDescription . '\',' . $phone_no . ');"> <a href="#" onclick="openVerify_docs(' . $appMemID . ', \'' . $appMemName . '\', ' . $VerificationDocID . ',\'' . $sysDocDescription . '\',' . $phone_no . ');">' . $empCode . ' |</a>' . $appMemName . '</h5>
                                               
                                            </div>
                                            <span class="pull-right label label-' . $label . ' emp-status-label">' . $apprvlStatus . '</span>
                                            <span class="pull-right label notfi-label" onclick="openPersonal_notifiyModal(' . $appMemID . ')"> <i class="fa fa-bell" aria-hidden="true"></i> </span>
                                        </div>

                                        <div class="aplicant-details-show clearfix">
                                            <ul class="list-unstyled pull-left">
                                                <li><span>Family Name: <b class="aplicant-detail">' . $familyNm . '</b></span></li>
                                                <li><span>Family Added Date: <b class="aplicant-detail">' . $FamilyAddedDate . '</b></span></li>
                                                <li><span>Area: <b class="aplicant-detail">' . $regionAr . '</b></span></li>
                                                <li><span>Date Of Birth : <b class="aplicant-detail">' . $DOJ . '</b></span></li>
                                                <li><span>Gender: <b class="aplicant-detail">' . $genderStr . '</b></span></li>
                                                <li><span>Address: <b class="aplicant-detail">' . $C_Address . '</b></span></li>
                                                <li><span>N.I.C.: <b class="aplicant-detail">' . $CNIC_No . '</b></span></li>
                                                <li><span>Primary E-Mail: <b class="aplicant-detail">' . $mailID . '</b></span></li>
                                                <li><span>Mobile: <b class="aplicant-detail">' . $CountryCode . '|' . $mobileNo . '</b></span></li>
                                            </ul>

                                            <ul class="list-unstyled pull-left">
                                             
                                                <li><span>        <div class="row-fluid" id="albums">
     <div id="defDiv" style="display: block;">
        <div style="width: 180px;" class="col-sm-2">
             <!-- small box -->
             <div class="small-box bg-teal bs">
                 <div style="background-color: grey;text-align: center;">Document Verification</div>
                 <a href="#" onclick="openVerify_docs(' . $appMemID . ', \'' . $appMemName . '\', ' . $VerificationDocID . ',\'' . $sysDocDescription . '\',' . $phone_no . ');">
                     <div style="background-color: grey;height: 90px;text-align: center;">
                         <i class="fa fa-file-image-o fa-5x" style="color: white;margin-top: 10px;"></i>
                   
                     </div>
                 </a>
                 <div style="padding: 3px 0;background: rgba(0,0,0,0.2);text-align: center;" data-toggle="tooltip" data-placement="bottom" title =""> <!--Assigned Classes : php foreach ($title2 as $tit){echo $tit.\', \';} ? -->
                     <a href="#" onclick="openVerify_docs(' . $appMemID . ', \'' . $appMemName . '\', ' . $VerificationDocID . ',\'' . $sysDocDescription . '\',' . $phone_no . ');" style="color: #fff;">
                         ' . $sysDocDescription . '</a>
                 </div>

             </div>
         </div>
     </div>
     </div></span></li>            
                                            </ul>
                                        </div>
                                 
                                       
                                        
                                    </div>
                                </div>';
            }
        } else {
            $returnData .= '<div class="candidate-description client-description applicants-content">No records</div>';
        }
        return [
            'dataCount' => count($memApproval_list),
            'memApproval_list' => $returnData
        ];
    }
}

/*advertisement  Approval*/

if (!function_exists('addApprovalsPagination')) {
    function addApprovalsPagination()
    {
        $CI = &get_instance();
        $CI->load->library("pagination");
        //$CI->load->library("s3");

        $data_pagination = $CI->input->post('data_pagination');
        $per_page = 10;
        $companyID = current_companyID();

        $count = $CI->db->query("SELECT COUNT(advertise.FamMasterID) AS comAdCount FROM srp_erp_ngo_com_advertisments advertise INNER JOIN srp_erp_ngo_com_familymaster femMas ON femMas.FamMasterID = advertise.FamMasterID WHERE companyID={$companyID}
                                 AND femMas.isDeleted=0 AND (advertise.is_deleted =0 OR advertise.is_deleted IS NULL)")->row('comAdCount');


        $isFiltered = 0;
        $searchKey_filter = '';
        $alpha_filter = '';
        $adType_filter = '';
        $gender_filter = '';
        $apprvlStatus_filter = '';

        $searchKey = $CI->input->post('searchKey');
        $letter = $CI->input->post('letter');
        $categoryApr = $CI->input->post('categoryApr');
        $adTypeAr = $CI->input->post('adTypeAr');
        $apprvlStatus = $CI->input->post('apprvlStatus');


        if ($apprvlStatus != '' && $apprvlStatus != 'null') {
            if ($apprvlStatus == 3) {
                $apprvlStatus_filter = " AND is_approved = 0 ";
            } else {
                $apprvlStatus_filter = " AND is_approved = " . $apprvlStatus;
            }
            $isFiltered = 1;
        }

        if (!empty($categoryApr) && $categoryApr != 'null') {
            $categoryApr = array($CI->input->post('categoryApr'));
            $whereIN = "( " . join("' , '", $categoryApr) . " )";
            $gender_filter = " AND ad_category.id IN " . $whereIN;
            $isFiltered = 1;
        }
        if (!empty($adTypeAr) && $adTypeAr != 'null') {
            $adTypeAr = array($CI->input->post('adTypeAr'));
            $whereIN = "( " . join("' , '", $adTypeAr) . " )";
            $adType_filter = " AND advertise.type_id IN " . $whereIN;
            $isFiltered = 1;
        }

        if ($letter != null) {
            $alpha_filter = ' AND (CName_with_initials LIKE \'' . $letter . '%\') ';
            $isFiltered = 1;
        }

        if ($searchKey != '') {

            $searchKey_filter = " WHERE ((MemberCode Like '%" . $searchKey . "%') OR (CName_with_initials Like '%" . $searchKey . "%') OR (provinceAr Like '%" . $searchKey . "%') OR (advertisementType Like '%" . $searchKey . "%') OR (category_name = '" . $searchKey . "') OR (CNIC_No Like '%" . $searchKey . "%') OR (PrimaryNumber Like '%" . $searchKey . "%') OR (familyNm Like '%" . $searchKey . "%'))";
            $isFiltered = 1;
        }

        $countFilter = 0;

        if ($isFiltered == 1) {
            $countFilterWhere = $gender_filter . $adType_filter . $alpha_filter . $apprvlStatus_filter;
            $convertFormat = convert_date_format_sql();
            $countFilter = $CI->db->query("SELECT COUNT(advertise.FamMasterID) AS comAdCount FROM(
                                           SELECT advertise.id advertiseId,advertise.FamMasterID,comMas.Com_MasterID,comMas.MemberCode,comMas.CName_with_initials,adSub_category.advertisement_category_id AS Gender,comMas.CountryCodePrimary,advertise.mobile,stateAr.Description AS stateArDes ,stateProvince.Description AS provinceAr,
                                    comMas.C_Address, TitleDescription,comMas.EmailID, comMas.CNIC_No,DATE_FORMAT(insert_date, '{$convertFormat}') AS insert_dates,DATE_FORMAT(published_date, '{$convertFormat}') AS published_dates,DATE_FORMAT(expire_date, '{$convertFormat}') AS expire_dates,ad_category.id,ad_category.category_name,stateProvince.Description, CImage, CONCAT( FamilySystemCode,' - ', FamilyName )AS familyNm,
                                    adSub_category.sub_category_name,is_approved,advertise.type_id,advertisementtypes.advertisementType,advertise.is_public,advertise.amount,advertise.upload_url,advertise.title AS upload_title,advertise.description AS upload_description
                                    FROM srp_erp_ngo_com_advertisments advertise
                                     INNER JOIN srp_erp_ngo_com_familymaster femMas ON femMas.FamMasterID = advertise.FamMasterID INNER JOIN srp_erp_ngo_com_advertisementtypes advertisementtypes ON advertisementtypes.typeID=advertise.type_id
                                      LEFT JOIN advertisement_sub_category adSub_category ON adSub_category.id =advertise.advertisement_sub_category_id LEFT JOIN advertisement_category ad_category ON adSub_category.advertisement_category_id=ad_category.id
                                     INNER JOIN srp_erp_ngo_com_communitymaster comMas ON comMas.Com_MasterID=femMas.LeaderID
                                    JOIN srp_titlemaster ON srp_titlemaster.TitleID=comMas.TitleID
                                    LEFT JOIN srp_erp_statemaster AS stateAr ON stateAr.stateID=comMas.RegionID
                                    LEFT JOIN srp_erp_statemaster AS stateCoun ON stateCoun.stateID=comMas.countyID
                                    LEFT JOIN srp_erp_statemaster AS stateProvince ON stateProvince.stateID=comMas.provinceID
                   WHERE comMas.companyID={$companyID} AND comMas.isDeleted = 0 AND femMas.isDeleted=0 AND (advertise.is_deleted =0 OR advertise.is_deleted IS NULL) {$countFilterWhere}
                                           ) AS advertise {$searchKey_filter} ")->row('comAdCount');
        }

        // var_dump($countFilter);
        //  exit;

        $config = array();
        $config["base_url"] = "#adApprovals_list";
        $config["total_rows"] =  ($isFiltered == 1) ? $countFilter : $count;
        $config["per_page"] = $per_page;
        $config["data_page_attr"] = 'data-emp-pagination';
        $config["uri_segment"] = 3;

        $CI->pagination->initialize($config);

        $page = (!empty($data_pagination)) ? (($data_pagination - 1) * $per_page) : 0;
        $employeeData = load_approvalAdvertisement_data($page, $per_page);
        $dataCount = $employeeData['dataCount'];

        $data["comAdCount"] = $count;
        $data["adApprovals_list"] = $employeeData['adApprovals_list'];
        $data["pagination"] = $CI->pagination->create_links_addApproval_master();
        $data["per_page"] = $per_page;
        $thisPageStartNumber = ($page + 1);
        $thisPageEndNumber = $page + $dataCount;

        if ($isFiltered == 1) {
            $data["filterDisplay"] = "Showing {$thisPageStartNumber} to {$thisPageEndNumber} of {$countFilter} entries (filtered from {$count} total entries)";
        } else {
            $data["filterDisplay"] = "Showing {$thisPageStartNumber} to {$thisPageEndNumber} of {$count} entries";
        }

        return $data;
    }
}

if (!function_exists('load_approvalAdvertisement_data')) {
    function load_approvalAdvertisement_data($page, $per_page)
    {

        $searchKey_filter = '';
        $alpha_filter = '';
        $adType_filter = '';
        $designation_filter = '';
        $apprvlStatus_filter = '';

        $CI = &get_instance();
        $letter = $CI->input->post('letter');
        $searchKey = $CI->input->post('searchKey');
        $categoryApr = $CI->input->post('categoryApr');
        $adTypeAr = $CI->input->post('adTypeAr');
        $apprvlStatus = $CI->input->post('apprvlStatus');

        if ($apprvlStatus != '' && $apprvlStatus != 'null') {
            if ($apprvlStatus == 3) {
                $apprvlStatus_filter = " AND is_approved = 0 ";
            } else {
                $apprvlStatus_filter = " AND is_approved = " . $apprvlStatus;
            }
        }

        if (!empty($categoryApr) && $categoryApr != 'null') {
            $categoryApr = array($CI->input->post('categoryApr'));
            $whereIN = "( " . join("' , '", $categoryApr) . " )";
            $designation_filter = " AND ad_category.id IN " . $whereIN;
        }

        if (!empty($adTypeAr) && $adTypeAr != 'null') {
            $adTypeAr = array($CI->input->post('adTypeAr'));
            $whereIN = "( " . join("' , '", $adTypeAr) . " )";
            $adType_filter = " AND advertise.type_id IN " . $whereIN;
        }

        if ($letter != null) {
            $alpha_filter = ' AND ( CName_with_initials LIKE \'' . $letter . '%\') ';
        }

        if ($searchKey != '') {
            $searchKey_filter = " WHERE ((MemberCode Like '%" . $searchKey . "%') OR (CName_with_initials Like '%" . $searchKey . "%') OR (provinceAr Like '%" . $searchKey . "%') OR (advertisementType Like '%" . $searchKey . "%') OR (category_name = '" . $searchKey . "') OR (CNIC_No Like '%" . $searchKey . "%') OR (PrimaryNumber Like '%" . $searchKey . "%') OR (familyNm Like '%" . $searchKey . "%'))";
        }

        $companyID = current_companyID();
        $convertFormat = convert_date_format_sql();
        $where = "comMas.isDeleted = 0 AND femMas.isDeleted=0 AND (advertise.is_deleted =0 OR advertise.is_deleted IS NULL) AND comMas.companyID = " . $companyID . $designation_filter . $adType_filter . $apprvlStatus_filter . $alpha_filter;

        $data = $CI->db->query("SELECT * FROM(
                                    SELECT advertise.id advertiseId,advertise.FamMasterID,comMas.Com_MasterID,comMas.MemberCode,comMas.CName_with_initials,adSub_category.advertisement_category_id AS Gender,comMas.CountryCodePrimary,advertise.mobile,stateAr.Description AS stateArDes ,stateProvince.Description AS provinceAr,
                                    comMas.C_Address, TitleDescription,comMas.EmailID, comMas.CNIC_No,DATE_FORMAT(insert_date, '{$convertFormat}') AS insert_dates,DATE_FORMAT(published_date, '{$convertFormat}') AS published_dates,DATE_FORMAT(expire_date, '{$convertFormat}') AS expire_dates,ad_category.id,ad_category.category_name,stateProvince.Description, CImage, CONCAT( FamilySystemCode,' - ', FamilyName )AS familyNm,
                                    adSub_category.sub_category_name,is_approved,advertise.type_id,advertisementtypes.advertisementType,advertise.is_public,advertise.amount,advertise.upload_url,advertise.title AS upload_title,advertise.description AS upload_description,advertise.expire_days
                                    FROM srp_erp_ngo_com_advertisments advertise
                                     INNER JOIN srp_erp_ngo_com_familymaster femMas ON femMas.FamMasterID = advertise.FamMasterID
                                    INNER JOIN srp_erp_ngo_com_communitymaster comMas ON comMas.Com_MasterID=femMas.LeaderID
                                    INNER JOIN srp_erp_ngo_com_advertisementtypes advertisementtypes ON advertisementtypes.typeID=advertise.type_id
                                     LEFT JOIN advertisement_sub_category adSub_category ON adSub_category.id =advertise.advertisement_sub_category_id LEFT JOIN advertisement_category ad_category ON adSub_category.advertisement_category_id=ad_category.id
                                    JOIN srp_titlemaster ON srp_titlemaster.TitleID=comMas.TitleID
                                    LEFT JOIN srp_erp_statemaster AS stateAr ON stateAr.stateID=comMas.RegionID
                                    LEFT JOIN srp_erp_statemaster AS stateCoun ON stateCoun.stateID=comMas.countyID
                                    LEFT JOIN srp_erp_statemaster AS stateProvince ON stateProvince.stateID=comMas.provinceID
                                    WHERE {$where}
                                ) comMas {$searchKey_filter} ORDER BY comMas.MemberCode LIMIT {$page}, {$per_page}")->result_array();
        //echo $CI->db->last_query();
        //  var_dump($data);
        $adApprovals_list = $data;
        $returnData = '';
        $color = "#FF0";
        if (!empty($adApprovals_list)) {

            $CI->load->library('s3');
            $male_img = $CI->s3->getMyAuthenticatedURL('images/users/male.png', 3600);
            $female_img = $CI->s3->getMyAuthenticatedURL('images/users/female.png', 3600);

            foreach ($adApprovals_list as $key => $appMemData) {
                $adMemID = $appMemData['Com_MasterID'];

                //$CImage = CImageCheck($appMemData['CImage'], $appMemData['Gender']);
                $CImage = trim($appMemData['CImage']);
                if ($CImage == '') {
                    $CImage = ($appMemData['Gender'] == 1) ? $male_img : $female_img;
                } elseif ($CImage == 'images/users/male.png') {
                    $CImage = $male_img;
                } elseif ($CImage == 'images/users/female.png') {
                    $CImage = $female_img;
                } else {
                    $CImage = $CI->s3->getMyAuthenticatedURL($CImage, 3600);
                    /*if( $CI->s3->getMyObjectInfo($CImage) ){
                        $CImage = $CI->s3->getMyAuthenticatedURL($CImage, 3600);
                    }
                    else{
                        $CImage = ($appMemData['Gender'] == 1)? $male_img: $female_img;
                    }*/
                }


                $firstDivStyle = ($key == 0) ? ' style="margin-top: 1px;"' : '';
                $firstDivInput = ($key == 0) ? '<input id="first-in-emp-list" />' : '';


                $advertiseId = $appMemData['advertiseId'];
                $type_id = $appMemData['type_id'];
                $advertisementType = $appMemData['advertisementType'];
                $adMemName = $appMemData['CName_with_initials'];
                $empCode = $appMemData['MemberCode'];
                $familyNm = $appMemData['familyNm'];
                $mailID = $appMemData['EmailID'];
                $provinceAr = $appMemData['provinceAr'];
                $stateArDes = $appMemData['stateArDes'];
                $categoryStr = $appMemData['category_name'];
                $mobileNo = $appMemData['mobile'];
                $sub_category_name = $appMemData['sub_category_name'];
                $amount = $appMemData['amount'];
                $added_date = $appMemData['insert_dates'];
                $expire_days = $appMemData['expire_days'];
                $published_date = $appMemData['published_dates'];
                $expire_date = $appMemData['expire_dates'];
                $is_approved = $appMemData['is_approved'];
                $is_public = $appMemData['is_public'];
                $uploadAdd_url = $appMemData['upload_url'];
                $upload_title = $appMemData['upload_title'];
                $upload_description = $appMemData['upload_description'];

                if ($is_approved == '1') {
                    $label = 'success';
                    $apprvlStatus = 'Approved';
                } elseif ($is_approved == '4') {
                    $label = 'danger';
                    $apprvlStatus = 'Cancelled';
                } elseif ($is_approved == '5') {
                    $label = 'warning';
                    $apprvlStatus = 'Pending For Clarification';
                } else {
                    $label = 'info';
                    $apprvlStatus = 'Remaining';
                }

                if ($is_public == '1') {
                    $is_publicStatus = '<lable style="color: green;">Yes</lable>';
                } else {
                    $is_publicStatus = '<lable style="color: red;">No</lable>';
                }

                if ($published_date == null || $published_date == '') {
                    $published_dates = convert_date_format_sql();
                } else {
                    $published_dates = $published_date;
                }

                if ($expire_date == null || $expire_date == '') {
                    $expire_dates = convert_date_format_sql();
                } else {
                    $expire_dates = $expire_date;
                }

                $returnData .= $firstDivInput;
                $returnData .= '<div class="candidate-description client-description applicants-content" ' . $firstDivStyle . '>
                                    <div class="language-print client-des clearfix">
                                        <div class="aplicants-pic pull-left">
                                            <img src="' . $CImage . '" alt="">
                                            <ul class="list-inline">

                                            </ul>
                                        </div>

                                        <div class="clearfix">
                                            <div class="pull-left">
                                              <b>Advertised By : </b> ' . $empCode . '|' . $adMemName . '
                                               
                                            </div>
                                            <span class="pull-right label label-' . $label . ' emp-status-label"><a style="color:#ffffff;" onclick="openAdVerify_docs(' . $advertiseId . ', \'' . $adMemName . '\', ' . $type_id . ',\'' . $sub_category_name . '\',' . $mobileNo . ',' . $expire_days . ');">' . $apprvlStatus . '</a></span>
                                            <span class="pull-right label notfi-label" onclick="openMemAdd_notifiyModal(' . $adMemID . ')"> <i class="fa fa-bell" aria-hidden="true"></i> </span>
                                        </div>

                                        <div class="aplicant-details-show clearfix">
                                        
                                           <ul class="list-unstyled pull-left">
                                                 <li><span>Title : <b class="aplicant-detail">' . $upload_title . '</b></span></li>
                                                 <li><span>Category : <b class="aplicant-detail">' . $categoryStr . '</b></span></li>
                                                <li><span>Sub Category: <b class="aplicant-detail">' . $sub_category_name . '</b></span></li>
                                                <li><span>Added Date: <b class="aplicant-detail">' . $added_date . '</b></span></li>
                                                <li><span>Amount : <b class="aplicant-detail">' . $amount . '</b></span></li>
                                                <li><span>Is Public: <b class="aplicant-detail">' . $is_publicStatus . '</b></span></li>

                                                <li style="margin-top: 10px;"><span>Family Name: <b class="aplicant-detail">' . $familyNm . '</b></span></li>
                                                <li><span>Mobile: <b class="aplicant-detail">' . $mobileNo . '</b></span></li>
                                                <li><span>Province: <b class="aplicant-detail">' . $provinceAr . '</b></span></li>
                                                <li><span>Primary E-Mail: <b class="aplicant-detail">' . $mailID . '</b></span></li>
                                            </ul>
                                            <ul class="list-unstyled pull-left">
                                             
                                                <li><span>        <div class="row-fluid" id="albums">
     <div id="defDiv" style="display: block;">
        <div style="width:350px;" class="col-sm-2">
             <!-- small box -->
             <div class="small-box bg-maroon bs">
                 <div style="background-color: grey;text-align: center;">Advertisement Verification</div>
                 <a href="#" onclick="openAdVerify_docs(' . $advertiseId . ', \'' . $adMemName . '\', ' . $type_id . ',\'' . $sub_category_name . '\',' . $mobileNo . ',' . $expire_days . ');">  </a>
                     <div style="background-color: grey;height: 90px;text-align: center;">';
                if ($type_id == '2') {
                    $returnData .= '<span class="tipped-top"><a data-modal="#modal_adVideo" onclick="openAdVideo_mod(\'' . $uploadAdd_url . '\', \'' . $upload_title . '\');"><img style="height:88px;width:200px;"
                                                                       src="' . base_url("images/community/videoImg2.jpg") . '"></a></span>';
                } elseif ($type_id == '1') {
                    $returnData .= '<span class="tipped-top"><a data-modal="#modal_adAudio" onclick="openAdAudio_modal(\'' . $uploadAdd_url . '\', \'' . $upload_title . '\');" style="height:90px;"><img style="height:88px;width:200px;"
                                                                       src="' . base_url("images/community/audio2.jpg") . '"></a></span>';
                } elseif ($type_id == '4') {
                    $returnData .= '<span class="tipped-top"><a onclick="open_ApproveAddViewer(' . $advertiseId . ');"><img style="height:88px;width:200px;"
                                                                       src="' . base_url("images/community/document-icon2.png") . '"></a></span>';
                } elseif ($type_id == '3') {
                    $returnData .= '<span class="tipped-top"><a onclick="open_pageUrlInNewTab(\'' . $uploadAdd_url . '\')"><img style="height:88px;width:200px;"
                                                                       src="' . base_url("images/community/pageUrls.png") . '"></a></span>';
                }
                $returnData .= '</div>
               
                 <div style="padding: 3px 0;background: rgba(0,0,0,0.2);text-align: center;" data-toggle="tooltip" data-placement="bottom" title =""> <!--Assigned Classes : php foreach ($title2 as $tit){echo $tit.\', \';} ? -->
                     <a href="#" onclick="openAdVerify_docs(' . $advertiseId . ', \'' . $adMemName . '\', ' . $type_id . ',\'' . $sub_category_name . '\',' . $mobileNo . ',' . $expire_days . ');" style="color: #AAAAAA;">
                         ' . $sub_category_name . '</a>
                 </div>

             </div>
         </div>
     </div>
     </div></span></li>

                                             
                                            </ul>
                                            <div><span style="color: transparent;">space</span><span style="color: brown;">Published Date: <b class="aplicant-detail">' . $published_dates . '</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Expire Date: <b class="aplicant-detail">' . $expire_dates . '</b></span></div>

                                        </div>
                                    </div>
                                </div>';
            }
        } else {
            $returnData .= '<div class="candidate-description client-description applicants-content">No records</div>';
        }
        return [
            'dataCount' => count($adApprovals_list),
            'adApprovals_list' => $returnData
        ];
    }
}

if (!function_exists('fetch_adApproval_status')) {
    function fetch_adApproval_status()
    {
        $companyID = current_companyID();
        $CI = &get_instance();
        $statusCount = $CI->db->query("SELECT * FROM (
                                          SELECT  (SELECT COUNT(advertise.FamMasterID) AS comAdCount FROM srp_erp_ngo_com_advertisments advertise INNER JOIN srp_erp_ngo_com_familymaster femMas ON femMas.FamMasterID = advertise.FamMasterID WHERE femMas.companyID={$companyID} AND femMas.isDeleted=0 AND (advertise.is_deleted =0 OR advertise.is_deleted IS NULL)
                                          AND advertise.is_approved=1) AS adApproved,(SELECT COUNT(advertise.FamMasterID) AS comAdCount FROM srp_erp_ngo_com_advertisments advertise INNER JOIN srp_erp_ngo_com_familymaster femMas ON femMas.FamMasterID = advertise.FamMasterID WHERE femMas.companyID={$companyID}
                                          AND femMas.isDeleted=0 AND (advertise.is_deleted =0 OR advertise.is_deleted IS NULL) AND advertise.is_approved = 4) AS noAdApproved,(SELECT COUNT(advertise.FamMasterID) AS comAdCount FROM srp_erp_ngo_com_advertisments advertise INNER JOIN srp_erp_ngo_com_familymaster femMas ON femMas.FamMasterID = advertise.FamMasterID WHERE femMas.companyID={$companyID}
                                          AND femMas.isDeleted=0 AND (advertise.is_deleted =0 OR advertise.is_deleted IS NULL) AND advertise.is_approved = 5) AS adAppClarify, (SELECT COUNT(advertise.FamMasterID) AS comAdCount FROM
                                           srp_erp_ngo_com_advertisments advertise INNER JOIN srp_erp_ngo_com_familymaster femMas ON femMas.FamMasterID = advertise.FamMasterID WHERE femMas.companyID={$companyID} AND femMas.isDeleted=0 AND (advertise.is_deleted =0 OR advertise.is_deleted IS NULL) AND (advertise.is_approved =0 OR advertise.is_approved IS NULL)) AS pendingAdApprovals
                                       ) AS t1")->row_array();
        return $statusCount;
    }
}

if (!function_exists('fetch_advertiseTypes')) {
    function fetch_advertiseTypes()
    {
        $CI = &get_instance();
        $companyID = current_companyID();

        $data = $CI->db->query("SELECT advertise.type_id,adType.advertisementType, count(advertise.FamMasterID) AS comAdCount
                                FROM srp_erp_ngo_com_advertisementtypes adType
                                JOIN srp_erp_ngo_com_advertisments AS advertise ON advertise.type_id = adType.typeID
                                JOIN srp_erp_ngo_com_familymaster AS femAdMas ON femAdMas.FamMasterID = advertise.FamMasterID
                                WHERE femAdMas.companyID={$companyID} AND femAdMas.isDeleted = 0 AND (advertise.is_deleted!= 0 OR advertise.is_deleted IS NOT NULL) GROUP BY advertise.type_id")->result_array();

        return $data;
    }
}

/*advertisement category dropdown*/
if (!function_exists('fetch_advertisement_cat_approval')) {
    function fetch_advertisement_cat_approval()
    {
        $CI = &get_instance();
        $companyID = current_companyID();
        $data = $CI->db->query("SELECT ad_category.id,ad_category.category_name as categoryName, count(advertise.FamMasterID) AS comAdCount FROM srp_erp_ngo_com_advertisments advertise LEFT JOIN advertisement_sub_category adSub_category ON adSub_category.id =advertise.advertisement_sub_category_id LEFT JOIN advertisement_category ad_category ON adSub_category.advertisement_category_id=ad_category.id INNER JOIN srp_erp_ngo_com_familymaster femMas ON femMas.FamMasterID = advertise.FamMasterID LEFT JOIN srp_erp_ngo_com_communitymaster comMas ON comMas.Com_MasterID=femMas.LeaderID WHERE femMas.companyID={$companyID} AND femMas.isDeleted = 0 AND advertise.is_deleted=0 GROUP BY adSub_category.advertisement_category_id")->result_array();
        return $data;
    }
}

/* community aws uploads */
if (!function_exists('get_all_community_uploads')) {
    function get_all_community_uploads($imagename, $path, $type = null)
    {
        $CI = &get_instance();
        $CI->load->library('s3');
        $image = $CI->s3->getMyAuthenticatedURL($path . $imagename, 3600);
        return $image;
    }
}
