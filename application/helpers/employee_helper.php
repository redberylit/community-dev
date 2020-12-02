<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('monthlyDeclarationsAction')) {
    function monthlyDeclarationsAction($id, $confirmedYN = 0, $approvedYN = 0, $isProcess = 0, $monthType, $code, $isNonPayroll)
    {
        $edit = '';
        $delete = '';
        $view = '';
        $viewType = '';
        $referBack = '';
        if ($monthType == 'A') {
            $page = 'addition';
            $t = 'MA';
        } else {
            $page = 'deduction';
            $t = 'MD';
        }

        $isNonPayroll = ( $isNonPayroll == 'Y' )? 2 : 1;
        $fetch = "fetchPage('system/hrm/emp_monthly_salary_" . $page . "',".$id." ,'HRMS','', ".$isNonPayroll.")";


        $print = '&nbsp;&nbsp; | &nbsp;&nbsp;<a target="_blank" href="' . site_url('Employee/monthlyAD_print') . '/' . $t . '/' . $id . '/' . $code . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>';

        if ($confirmedYN != 1) {
            $code = "'" . $code . "'";
            $edit = '<a onclick="' . $fetch . '"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
            $delete = '&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="delete_details(' . $id . ' , ' . $code . ')"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';
        } elseif ($confirmedYN == 1) {
            $view = '<a onclick="' . $fetch . '"><span title="View" rel="tooltip" class="fa fa-fw fa-eye"></span></a>';
        }

        if ($isProcess == 0 && $confirmedYN == 1) {
            $referBack = '<a onclick="referBackConformation(' . $id . ')"><span style="color:#d15b47;" title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat"></span></a>&nbsp;&nbsp;|&nbsp;';
        }

        return '<span class="pull-right">' . $referBack . '' . $view . '' . $edit . '' . $delete . '' . $print . ' </span>';
    }
}

if (!function_exists('des')) {
    function des($des)
    {
        return '<input type="text" name="description[]" class="trInputs"  value="' . $des . '">';
    }
}

$amount = 0;
if (!function_exists('monthlyAmount')) {
    function monthlyAmount($tAmount, $dPlaces, $empID, $localExchangeRate)
    {
        global $amount;
        $amount = $amount + 1;
        $val = ($tAmount == 0) ? '' : number_format($tAmount, $dPlaces);
        $str = '<input type="text" name="amount[]" class="trInputs number" id="amount_' . $amount . '"  value="' . $val . '"';
        $str .= ' onkeyup="empAmount(this, ' . $amount . ', \''.$localExchangeRate.'\')" onchange="formatAmount(this, '.$dPlaces.')">';
        return $str;
    }
}

if (!function_exists('action')) {
    function action($empID, $currency, $dPlace)
    {

        $details = '<span class="glyphicon glyphicon-trash traceIcon" onclick="removeEmpTB(this)" style="color:#d15b47;"></span>
                <input type="hidden" name="empHiddenID[]" class="recordTB_empID" value="' . $empID . '">
                <input type="hidden" name="empCurrencyCode[]" class="empCurrencyCode" value="' . $currency . '">
                <input type="hidden" name="empCurrencyDPlace[]" class="empCurrencyDPlace" value="' . $dPlace . '">';

        return '<div align="right" >' . $details . '</div>';
    }
}

$amountSpan = 0;
if (!function_exists('localAmount')) {
    function localAmount($localAmount, $dPlaces)
    {
        global $amountSpan;
        $amountSpan = $amountSpan + 1;
        return '<div align="right" class="localAmount" id="amountSpan_' . $amountSpan . '" >' . number_format($localAmount, $dPlaces) . '</div>';

    }
}

$exRateSpan = 0;
if (!function_exists('exRate')) {
    function exRate($exRate)
    {
        global $exRateSpan;
        $exRateSpan = $exRateSpan + 1;
        return '<div align="right" class="exRate" id="exRateSpan_' . $exRateSpan . '" >' . round($exRate, 6) . '</div>';

    }
}

if (!function_exists('action_leaveTypes')) {
    function action_leaveTypes($id, $des, $isExist, $isPaidLeave, $isAnnualLeave,$attachmentRequired=0, $isPlanApplicable=1, $isSickLeave,$isShortLeave,$shortLeaveMaxHours,$shortLeaveMaxMins)
    {
        $des = "'" . $des . "'";
        $action = '';
        if($isSickLeave == 1){
            $action .= '<a onclick="leaveSetup(' . $id . ', ' . $des . ', this)"><span title="Setup" rel="tooltip">';
            $action .= '<i class="fa fa-cogs" aria-hidden="true" style="color:black"></i></span></a>&nbsp; | &nbsp;';
        }
        $action .= '<a onclick="edit_LeaveType(' . $id . ', ' . $des . ', ' . $isPaidLeave. ', ' . $isAnnualLeave  . ', '.$attachmentRequired.', '.$isPlanApplicable.', '.$isShortLeave.', '.$shortLeaveMaxHours.', '.$shortLeaveMaxMins.')">';
        $action .= '<span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
        if ($isExist == null) {
            $action .= '&nbsp; | &nbsp;<a onclick="delete_LeaveType(' . $id . ', ' . $des . ')"><span title="Delete" rel="tooltip" ';
            $action .= 'class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';
        }
        return '<span class="pull-right">' . $action . '</span>';
    }
}

if (!function_exists('leaveTypes_drop')) {
    function leaveTypes_drop($isPaidLeave = null)
    {
        $companyID = current_companyID();
        $CI =& get_instance();
        $where2 = '';
        if ($isPaidLeave != null) {
            $where2 = 'AND isPaidLeave=1';
        }
        $leaveTypes = $CI->db->query("SELECT leaveTypeID, t1.description, policyDescription, isPaidLeave
                                      FROM srp_erp_leavetype t1
                                      JOIN srp_erp_leavepolicymaster t2 ON t1.policyID=t2.policyMasterID
                                      WHERE companyID={$companyID}  $where2 ")->result_array();
        return $leaveTypes;
        /*$i = 0;
        $arr = array('leaveTypeID'=>'', 'description'=>'Select', 'policy'=>'');
        if (isset($leaveTypes)) {
            foreach ($leaveTypes as $row) {
                $arr[$i] = array(
                    'leaveTypeID' => trim($row['leaveTypeID']),
                    'description' => trim($row['description']),
                    'policy' => trim($row['policy'])
                );
                $i++;
            }
        }
        return $arr;*/
    }
}

if (!function_exists('isAlreadyExistInThisArray')) {
    function isAlreadyExistInThisArray($arr, $val, $no, $empID)
    {
        $CI =& get_instance();
        $j = 0;
        $returnVal = null;
        foreach ($arr as $row) {
            if ($row == $val && $no != $j) {
                $description = $CI->db->query("SELECT description FROM srp_erp_leavetype WHERE leaveTypeID={$row}")->row_array();
                $returnVal = $description['description'] . ' is more than one time added';
            } else {
                $isEntered = $CI->db->query("SELECT description FROM srp_erp_leaveentitled AS t1
                                             JOIN srp_erp_leavetype AS t2 ON t1.leaveTypeID = t2.leaveTypeID
                                             WHERE empID={$empID} AND t2.leaveTypeID={$row}")->row_array();
                if (count($isEntered) > 0) {
                    $returnVal = $isEntered['description'] . ' is Already Exist ';
                }
            }
            $j++;
        }

        return ($returnVal != null) ? array('e', $returnVal) : array('s');
    }
}

if (!function_exists('leaveApplicationAction')) {
    function leaveApplicationAction($id, $code, $confirmedYN, $approvedYN, $requestForCancelYN, $cancelledYN)
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('hrms_leave_management', $primaryLanguage);
        $cancel = $edit = $delete =  $view = $referBack = '';
        $leaveApp =  $CI->lang->line('hrms_leave_management_leave_application');/*Leave Application*/
        $fetch = "openLeaveDetails($id, '" . $code . "')";
        $delete_Fn = "delete_leave($id, '" . $code . "')";
        $ref_fn = "refer_leave($id , '" . $code . "')";

        $att = '<a onclick=\'attachment_modal(' . $id . ',"'.$leaveApp.'","LA",'.$confirmedYN.');\'><span title="Attachment" rel="tooltip" ';
        $att .= 'class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';
        $print = '&nbsp;&nbsp; | &nbsp;&nbsp;<a target="_blank" href="' . site_url('Employee/leave_print/') . '/' . $id . '/' . $code . '" >';
        $print .= '<span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>';

        if ($confirmedYN != 1) {
            $edit = '<a onclick="' . $fetch . '"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
            $delete = '&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="' . $delete_Fn . '"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" ';
            $delete .= 'style="color:#d15b47;"></span></a>';
        } elseif ($confirmedYN == 1) {
            $view = '<a onclick="' . $fetch . '"><span title="View" rel="tooltip" class="fa fa-fw fa-eye"></span></a>';
        }

        if ($approvedYN == 0 && $confirmedYN == 1) {
            $referBack = '<a onclick="' . $ref_fn . '"><span title="Refer Back" rel="tooltip" style="color:#d15b47;" ';
            $referBack .= 'class="glyphicon glyphicon-repeat"></span></a>&nbsp;&nbsp;|&nbsp;';
        }

        if($approvedYN == 1 && $requestForCancelYN != 1){
            $cancel_fn = "cancel_leave($id , '" . $code . "')";
            $cancel = '<a onclick="' . $cancel_fn . '" title="Cancel" rel="tooltip"><i class="fa fa-ban fa-fw"></i>';
            $cancel .= '</a>&nbsp;&nbsp;|&nbsp;';
        }

        if($approvedYN == 1 && $requestForCancelYN == 1 && $cancelledYN != 1){
            $ref_fn = "refer_leave_cancellation($id , '" . $code . "')";
            $referBack = '<a onclick="' . $ref_fn . '"><span title="Refer Back Cancellation" rel="tooltip" style="color:#d15b47;" ';
            $referBack .= 'class="glyphicon glyphicon-repeat"></span></a>&nbsp;&nbsp;|&nbsp;';
        }

        return '<span class="pull-right">'.$cancel.''.$att.'' . $referBack . '' . $view . '' . $edit . '' . $delete . '' . $print . ' </span>';
    }
}

if (!function_exists('leavePolicy_drop')) {
    function leavePolicy_drop()
    {
        $CI =& get_instance();
        $leaveTypes = $CI->db->query("SELECT * FROM srp_erp_leavepolicymaster")->result_array();
        return $leaveTypes;
        /*$i = 0;
        $arr = array('leaveTypeID'=>'', 'description'=>'Select', 'policy'=>'');
        if (isset($leaveTypes)) {
            foreach ($leaveTypes as $row) {
                $arr[$i] = array(
                    'leaveTypeID' => trim($row['leaveTypeID']),
                    'description' => trim($row['description']),
                    'policy' => trim($row['policy'])
                );
                $i++;
            }
        }
        return $arr;*/
    }
}

if (!function_exists('leave_action_approval')) { /*get po action list*/
    function leave_action_approval($leaveID, $approvalLevelID, $leaveCode)
    {
        $status = '<span class="pull-right">';

        $status .= '<a onclick="load_emp_leaveDet(' . $leaveID . ', ' . $approvalLevelID . ')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>';

        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('fetch_emp_title')) {
    function fetch_emp_title()
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
        $CI->db->SELECT("TitleID,TitleDescription");
        $CI->db->FROM('srp_titlemaster');
        $CI->db->WHERE('Erp_companyID', current_companyID());
        $CI->db->order_by('TitleDescription');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => $CI->lang->line('common_select_title')/*'Select a title'*/);
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['TitleID'])] = trim($row['TitleDescription']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('fetch_emp_religion')) {
    function fetch_emp_religion()
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
        $CI->db->SELECT("RId,Religion");
        $CI->db->FROM('srp_religion');
        $CI->db->WHERE('Erp_companyID', current_companyID());
        $CI->db->order_by('Religion');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => $CI->lang->line('common_select_a_religion')/*'Select a Religion'*/);
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['RId'])] = trim($row['Religion']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('fetch_emp_nationality')) {
    function fetch_emp_nationality()
    {
        $companyID = current_companyID();

        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);

        $CI->db->SELECT("NId,Nationality");
        $CI->db->FROM('srp_nationality');
        $CI->db->where('Erp_companyID', $companyID);
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => $CI->lang->line('common_select_a_nationality')/*'Select a Nationality'*/);
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['NId'])] = trim($row['Nationality']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('fetch_emp_maritialStatus')) {
    function fetch_emp_maritialStatus($returnType=0)
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
        $CI->db->SELECT("maritialstatusID,description");
        $CI->db->FROM('srp_erp_maritialstatus');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => $CI->lang->line('common_select_a_maritial_status')/*'Select a Maritial Status'*/);

        if( $returnType == 1){
            return $data;
        }

        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['maritialstatusID'])] = trim($row['description']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('fetch_sysEmpContractType')) {
    function fetch_sysEmpContractType()
    {
        $CI =& get_instance();
        $CI->db->SELECT("employeeTypeID,employeeType");
        $CI->db->FROM('srp_erp_systememployeetype');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => ''); //Select a Employee Status
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['employeeTypeID'])] = trim($row['employeeType']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('fetch_employee_details_columns')) {
    function fetch_employee_details_columns()
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->from('srp_erp_employeedetailreport');
        $data = $CI->db->get()->result_array();
        $data_arr = [];
        $d = [];
        if (isset($data)) {
            foreach ($data as $row) {
                $d[] = ($row['columnName']);
                $data_arr[trim($row['columnName'])] = trim($row['columnTitle']);
            }
        }

        //echo implode(', ', $d);

        return $data_arr;
    }
}

if (!function_exists('fetch_empContractType')) {
    function fetch_empContractType($drop = null)
    {
        $CI =& get_instance();
        $CI->db->SELECT("EmpContractTypeID, Description, employeeTypeID, period, probation_period");
        $CI->db->FROM('srp_empcontracttypes AS t1');
        $CI->db->JOIN('srp_erp_systememployeetype AS t2', 't1.typeID=t2.employeeTypeID');
        $CI->db->WHERE('Erp_CompanyID', current_companyID());
        $data = $CI->db->get()->result_array();

        if($drop == 'drop'){
            $data_arr = array('' => ''); //Select a Employee Status
            if (isset($data)) {
                foreach ($data as $row) {
                    $data_arr[trim($row['EmpContractTypeID'])] = trim($row['Description']);
                }
            }
            return $data_arr;

        }else{
            return $data;
        }

    }
}

if (!function_exists('fetch_emp_blood_type')) {
    function fetch_emp_blood_type($returnType=0)
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
        $CI->db->SELECT("BloodTypeID,BloodDescription");
        $CI->db->FROM('srp_erp_bloodgrouptype');
        $data = $CI->db->get()->result_array();

        if($returnType ==1){
            return $data;
        }

        $data_arr = array('' => $CI->lang->line('common_select_a_blood_group')/*'Select a Blood Group'*/);
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['BloodTypeID'])] = trim($row['BloodDescription']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('fetch_emp_designation')) {
    function fetch_emp_designation()
    {
        $CI =& get_instance();
        $CI->db->SELECT("DesignationID,DesDescription");
        $CI->db->FROM('srp_designation');
        $CI->db->WHERE('Erp_companyID', current_companyID());
        $CI->db->WHERE('isDeleted', 0);
        $CI->db->order_by('DesDescription');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Designation');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['DesignationID'])] = trim($row['DesDescription']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('fetch_emp_countries')) {
    function fetch_emp_countries()
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
        $CI->db->SELECT("countryID,CountryDes");
        $CI->db->FROM('srp_countrymaster');
        $CI->db->WHERE('Erp_companyID', current_companyID());
        $CI->db->order_by('CountryDes');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' =>$CI->lang->line('common_select_country')/* 'Select Country'*/);
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['countryID'])] = trim($row['CountryDes']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('empMaster_action')) {
    function empMaster_action($id, $empName, $type1=null)
    {

        $action = '<a onclick="edit_empDet(' . $id . ', \'' . $empName . '\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';

        if( $type1 == null ){
            $action = '<span class="pull-right">' . $action . '</span>';
        }
        else{
            $action = '<a onclick="edit_empDet(' . $id . ', \'\')">' . $empName . '</a>';
        }
        return $action;
    }
}

if (!function_exists('empCodeGenerate')) {
    function empCodeGenerate($tibianType=null)
    {
        //Generate Employee Code
        $CI =& get_instance();
        $CI->load->library('sequence');

        if($tibianType == null){
            return $CI->sequence->sequence_generator('EMP');
        }

        return $CI->sequence->sequence_generator_employee($tibianType);
    }
}

if (!function_exists('current_schMasterID')) {
    function current_schMasterID()
    {
        $CI =& get_instance();
        return trim($CI->common_data['company_data']['company_link_id']);
    }
}

if (!function_exists('current_schBranchID')) {
    function current_schBranchID()
    {
        $CI =& get_instance();
        return trim($CI->common_data['company_data']['branch_link_id']);
    }
}

if (!function_exists('action_religion')) {
    function action_religion($RId, $Religion, $usageCount)
    {
        $Religion = "'" . $Religion . "'";
        $action = '<a onclick="edit_religion(' . $RId . ', ' . $Religion . ')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
        if ($usageCount == 0) {
            $action .= '&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="delete_religion(' . $RId . ', ' . $Religion . ')">';
            $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';
        }

        return '<span class="pull-right">' . $action . '</span>';

    }
}

if (!function_exists('action_country')) {
    function action_country($countryID, $CountryDes, $usageCount)
    {
        $CountryDes = "'" . $CountryDes . "'";
        $action = '';
        if ($usageCount == 0) {
            $action .= '<a onclick="deleteCountry(' . $countryID . ', ' . $CountryDes . ')">';
            $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';
        }

        return '<span class="pull-right">' . $action . '</span>';

    }
}

if (!function_exists('action_selectCountry')) {
    function action_selectCountry($countryID, $name, $code)
    {
        $outPut = '<div align="center">';
        $outPut .= '<input type="checkbox" name="countrySelChk[]" class="countrySelChk" style="margin: 0px"';
        $outPut .= 'value="' . $countryID . '" data-name="' . $name . '" data-code="' . $code . '">';
        $outPut .= '</div>';
        return $outPut;
    }
}

if (!function_exists('action_designation')) {
    function action_designation($DesignationID, $DesDescription, $usageCount)
    {
        $DesDescription = "'" . $DesDescription . "'";
        $action = '<a onclick="edit_designation(' . $DesignationID . ', ' . $DesDescription . ')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';

        if ($usageCount == 0) {
            $action .= '&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="delete_designation(' . $DesignationID . ', ' . $DesDescription . ')">';
            $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';
        }

        return '<span class="pull-right">' . $action . '</span>';

    }
}

if (!function_exists('action_department')) {
    function action_department($departmentID, $depDescription, $isActive, $usageCount)
    {
        $depDescription = "'" . $depDescription . "'";
        $action = '<a onclick="edit_department(' . $departmentID . ', ' . $depDescription . ', ' . $isActive . ')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';

        if ($usageCount == 0) {
            $action .= '&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="delete_department(' . $departmentID . ', ' . $depDescription . ')">';
            $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';
        }

        return '<span class="pull-right">' . $action . '</span>';

    }
}

if (!function_exists('action_qualification')) {
    function action_qualification($certificateID, $description)
    {
        $description = "'" . $description . "'";
        $action = '<span class="glyphicon glyphicon-pencil editIcon" data-id="' . $certificateID . '" style="color:#3c8dbc"></span>';
        $action .= '&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="delete_Qualification(' . $certificateID . ', ' . $description . ')">';
        $action .= '<span class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';

        return '<span class="pull-right">' . $action . '</span>';

    }
}

if (!function_exists('action_floor')) {
    function action_floor($floorID, $depDescription, $isActive, $latitude, $longitude, $locationRadius, $usageCount)
    {
        $depDescription = "'" . $depDescription . "'";
        $action = '<a onclick="edit_floor(' . $floorID . ', ' . $depDescription . ', \'' . $latitude . '\', \'' . $longitude . '\', \'' . $locationRadius . '\', \'' . $isActive . '\')">';
        $action .= '<span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';

        if ($usageCount == 0) {
            $action .= '&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="delete_floor(' . $floorID . ', ' . $depDescription . ')">';
            $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';
        }

        return '<span class="pull-right">' . $action . '</span>';
    }
}

if (!function_exists('action_empDesignation')) {
    function action_empDesignation($EmpDesignationID, $DesDescription, $isMajor)
    {
        $DesDescription = "'" . $DesDescription . "'";

        $action = '<a onclick="edit_empDesignation(this)"><span class="glyphicon glyphicon-pencil"></span></a>';

        if( $isMajor != 21 ){
            $action .= '&nbsp; | &nbsp;';
            $action .= '<a onclick="delete_empDesignation(' . $EmpDesignationID . ', ' . $DesDescription . ')">';
            $action .= '<span class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';
        }

        return '<span class="pull-right">' . $action . '</span>';

    }
}

if (!function_exists('action_empDepartment')) {
    function action_empDepartment($EmpDepartmentID, $DesDepartment)
    {
        /*$DesDepartment = "'" . $DesDepartment . "'";
        $action = '<a onclick="edit_empDepartments(' . $EmpDepartmentID . ', ' . $DesDepartment . ')"><span class="glyphicon glyphicon-pencil"></span></a>';
        $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="delete_empDepartments(' . $EmpDepartmentID . ', ' . $DesDepartment . ')">';
        $action .= '<span class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';*/

        $DesDepartment = "'" . $DesDepartment . "'";
        $action = '<a onclick="delete_empDepartments(' . $EmpDepartmentID . ', ' . $DesDepartment . ')">';
        $action .= '<span class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';

        return '<span class="pull-right">' . $action . '</span>';

    }
}

if (!function_exists('department_status')) {
    function department_status($autoID, $status)
    {
        $checked = ($status == 1) ? 'checked' : '';
        //return '<input type="checkbox" class="switch-chk" id="status_'.$autoID.'" data-id="'.$autoID.'" data-size="mini" data-on-text="Active" data-handle-width="45" data-off-color="danger" data-on-color="success" data-off-text="Deactive" data-label-width="0" '.$checked.'>';
        return '<input type="checkbox" class="switch-chk" id="status_' . $autoID . '" onchange="changeStatus(' . $autoID . ')" data-size="mini" data-on-text="Active" data-handle-width="45" data-off-color="danger" data-on-color="success" data-off-text="Deactive" data-label-width="0" ' . $checked . '>';
    }
}

if (!function_exists('action_docSetup')) {
    function action_docSetup($DocDesID, $DocDescription)
    {
        $DocDescription = "'" . $DocDescription . "'";
        $action = '<a onclick="edit_docSetup(' . $DocDesID . ', this)"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
        $action .= '&nbsp;&nbsp;|&nbsp;&nbsp; <a onclick="delete_docSetup(' . $DocDesID . ', ' . $DocDescription . ')">';
        $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';

        return '<span class="pull-right">' . $action . '</span>';

    }
}

if (!function_exists('leave_group_change_history_action')) {
    function leave_group_change_history_action($id, $adjustmentDone)
    {
        $action = '';
        switch($adjustmentDone){
            case 0:
                $action = '<a onclick="newLeaveAdjustment(' . $id . ')" class="action-div">Create</a>';
                //$action .= '&nbsp; | &nbsp; <a onclick="skipLeaveAdjustment(' . $id . ')" class="action-div">Skip</a>';
            break;

            case 1:
                $action = '<a onclick="getLeaveAdjustment(' . $id . ')" class="action-div">view</a>';
            break;
        }

        return '<div style="text-align: center; font-weight: bold;">' . $action . '</div>';

    }
}

if (!function_exists('mandatoryStatus')) {
    function mandatoryStatus($isMandatory)
    {
        return ($isMandatory == 1) ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>';
    }
}

if (!function_exists('allDocument_drop')) {
    function allDocument_drop($type = 0)
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
        $CI->db->SELECT("t1.DocDesID,DocDescription");
        $CI->db->FROM('srp_documentdescriptionmaster t1');
        $CI->db->WHERE('t1.Erp_companyID', current_companyID());
        $CI->db->order_by('DocDescription');
        $data = $CI->db->get()->result_array();


        if ($type == 0) {
            $data_arr = array('' => $CI->lang->line('common_select_document')/*'Select Document'*/);
            if (isset($data)) {
                foreach ($data as $row) {
                    $data_arr[trim($row['DocDesID'])] = trim($row['DocDescription']);
                }
                return $data_arr;
            }
        } else {
            return $data;
        }

    }
}

if (!function_exists('emp_document_drop')) {
    function emp_document_drop()
    {
        $CI =& get_instance();
        $CI->db->select("t1.DocDesID,DocDescription,t1.systemTypeID,t3.issuedByType");
        $CI->db->from('srp_documentdescriptionmaster t1');
        $CI->db->join('srp_documentdescriptionsetup t2', 't1.DocDesID=t2.DocDesID');
        $CI->db->join('srp_erp_system_document_types t3', 't3.id=t1.systemTypeID');
        $CI->db->where('t1.Erp_companyID', current_companyID());
        $CI->db->where('t1.isDeleted', 0);
        $CI->db->where('FormType', 'EMP');
        $CI->db->order_by('DocDescription');
        $data = $CI->db->get()->result_array();
        return $data;
        $data_arr = array('' => 'Select Document');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['DocDesID'])] = trim($row['DocDescription']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('segment_drop')) {
    function segment_drop()
    {
        $CI =& get_instance();
        $CI->db->select('segmentCode,description,segmentID');
        $CI->db->from('srp_erp_segment');
        $CI->db->where('srp_erp_segment.companyID', $CI->common_data['company_data']['company_id']);
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Segment');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['segmentID'])] = trim($row['segmentCode']) . ' | ' . trim($row['description']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('floors_drop')) {
    function floors_drop($isFromAttPulling=0,$check_isActive=1)
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
        $CI->db->select('floorID, floorDescription');
        $CI->db->from('srp_erp_pay_floormaster');
        $CI->db->where('companyID', current_companyID());
        if($check_isActive == 1){
            $CI->db->where('isActive', 1);
        }
        $data = $CI->db->get()->result_array();

        $place_holder = (IS_OMAN_OIL == false)? $CI->lang->line('common_select_floor'): $CI->lang->line('common_Location');
        $data_arr = [''=>$place_holder];
        if($isFromAttPulling == 1) {
            $data_arr = array('' => $CI->lang->line('common_select_location')); /*'Select a Location'*/
        }

        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['floorID'])] = trim($row['floorDescription']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('expenseGL_drop')) {
    function expenseGL_drop($asResult = null)
    {

        $CI =& get_instance();
        $CI->db->select("GLAutoID,systemAccountCode,GLSecondaryCode,GLDescription,subCategory");
        $CI->db->from('srp_erp_chartofaccounts');
        $CI->db->where('masterAccountYN', 0);
        $CI->db->where('isBank', 0);
        $CI->db->where('isActive', 1);
        $CI->db->where('subCategory ', 'PLE');
        $CI->db->where('approvedYN', 1);
        $CI->db->order_by('GLSecondaryCode');
        $CI->db->where('companyID', current_companyID());
        $data = $CI->db->get()->result_array();

        if ($asResult != null) {
            $data_arr = array('' => '');
            if (isset($data)) {
                foreach ($data as $row) {
                    $data_arr[trim($row['GLAutoID'])] = trim($row['GLSecondaryCode']) . ' | ' . trim($row['GLDescription']);
                }
            }
            return $data_arr;
        } else {
            return $data;
        }

    }
}

if (!function_exists('monthly_additionDeductionGL_drop')) {
    function monthly_additionDeductionGL_drop($asResult = null)
    {

        $CI =& get_instance();
        $companyID = current_companyID();
        $data = $CI->db->query("SELECT GLAutoID, systemAccountCode, GLSecondaryCode, GLDescription, subCategory
                        FROM srp_erp_chartofaccounts
                        WHERE masterAccountYN = 0 AND isBank = 0 AND isActive = 1 AND accountCategoryTypeID!=4
                        AND approvedYN = 1 AND companyID = {$companyID} ORDER BY GLSecondaryCode")->result_array();

        if ($asResult != null) {
            $data_arr = array('' => '');
            if (isset($data)) {
                foreach ($data as $row) {
                    $data_arr[trim($row['GLAutoID'])] = trim($row['GLSecondaryCode']) . ' | ' . trim($row['GLDescription']);
                }
            }
            return $data_arr;
        } else {
            return $data;
        }

    }
}


if (!function_exists('declaration_drop')) {
    function declaration_drop($AD, $isPayrollCategory)
    {
        $CI =& get_instance();
        $CI->db->SELECT("monthlyDeclarationID, monthlyDeclaration, GLAutoID,GLSecondaryCode, GLDescription, salaryCategoryID");
        $CI->db->FROM('srp_erp_pay_monthlydeclarationstypes AS decType');
        $CI->db->JOIN('srp_erp_chartofaccounts AS chartAcc', 'chartAcc.GLAutoID=decType.expenseGLCode');
        $CI->db->WHERE('monthlyDeclarationType', $AD);
        $CI->db->WHERE('isPayrollCategory', $isPayrollCategory);
        $CI->db->WHERE('decType.companyID', current_companyID());
        return $CI->db->get()->result_array();
    }
}

if (!function_exists('empImage')) {
    function empImage($imgPath)
    {
        $filePath = imagePath() . $imgPath;
        $emp_img = checkIsFileExists($filePath);
        return $emp_img;
    }
}

if (!function_exists('empImage_s3')) {
    function empImage_s3($imgPath, $gender, $male, $female)
    {
        if($imgPath == ''){
            $imgPath = ($gender == 1)? $male: $female;
        }
        elseif ($imgPath == 'images/users/male.png'){
            $imgPath = $male;
        }
        elseif ($imgPath == 'images/users/female.png'){
            $imgPath = $female;
        }
        else{
            $CI =& get_instance();
            $CI->load->library('s3');
            $imgPath = $CI->s3->getMyAuthenticatedURL($imgPath, 3600);
            /*if( $CI->s3->getMyObjectInfo($imgPath) ){
                $imgPath = $CI->s3->getMyAuthenticatedURL($imgPath, 3600);
            }
            else{
                $imgPath = ($gender == 1)? $male : $female;
            }*/
        }
        return $imgPath;
    }
}

if (!function_exists('single_emp_image_s3_with_validation')) {
    function single_emp_image_s3_with_validation($imgPath, $gender)
    {
        $CI =& get_instance();
        $CI->load->library('s3');

        if( $CI->s3->getMyObjectInfo($imgPath) ){
            $imgPath = $CI->s3->getMyAuthenticatedURL($imgPath, 7200);
        }
        else{
            $imgPath = ($gender == 1)? 'male.png' : 'female.png';
            $imgPath = $CI->s3->getMyAuthenticatedURL("images/users/$imgPath", 7200);
        }
        return $imgPath;
    }
}

if (!function_exists('multiple_emp_image_s3_with_validation')) {
    function multiple_emp_image_s3_with_validation($imgPath, $gender, $male, $female)
    {
        if($imgPath == ''){
            $imgPath = ($gender == 1)? $male: $female;
        }
        elseif ($imgPath == 'images/users/male.png'){
            $imgPath = $male;
        }
        elseif ($imgPath == 'images/users/female.png'){
            $imgPath = $female;
        }
        else{
            $CI =& get_instance();
            $CI->load->library('s3');

            if( $CI->s3->getMyObjectInfo($imgPath) ){
                $imgPath = $CI->s3->getMyAuthenticatedURL($imgPath, 3600);
            }
            else{
                $imgPath = ($gender == 1)? $male : $female;
            }
        }
        return $imgPath;
    }
}

if (!function_exists('empImageCheck')) {
    function empImageCheck($imageName, $empType)
    {
        $not_available = 0;

        if(!empty($imageName)){
            $filePath = imagePath() . $imageName;
            $ret = FALSE;
            if (file_exists(UPLOAD_PATH . '' . $filePath)) {
                $ret = TRUE;
            }

            if($ret == TRUE){
                return $filePath;
            }
            else{
                $not_available = 1;
            }
        }else{
            $not_available = 1;
        }

        if($not_available == 1){

            if($empType == 'signature'){
                $img = 'No_Image.png';
            }else{
                $img = ($empType == 2)? 'female.png' :'male.png';
            }

            return imagePath().$img;
        }

    }
}

if (!function_exists('attendanceType_drop')) {
    function attendanceType_drop($drop=true)
    {
        $CI =& get_instance();
        $CI->db->select("PresentTypeID, PresentTypeDes");
        $CI->db->from('srp_sys_attpresenttype t1');
        $CI->db->join('srp_attpresenttype t2', 't1.PresentTypeID = SysPresentTypeID');
        $CI->db->where('Erp_companyID', current_companyID());
        $data = $CI->db->get()->result_array();

        if($drop == false){
            $data_arr = array('' => '');
        }

        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['PresentTypeID'])] = trim($row['PresentTypeDes']);
            }
        }

        return $data_arr; //return $data;
    }
}

if (!function_exists('system_attendanceTypes')) {
    function system_attendanceTypes()
    {
        $CI =& get_instance();
        $company_id = current_companyID();
        $data = $CI->db->query("SELECT * FROM srp_sys_attpresenttype AS t1 WHERE PresentTypeID NOT IN (
                                  SELECT SysPresentTypeID FROM srp_attpresenttype WHERE SysPresentTypeID=t1.PresentTypeID AND Erp_companyID = {$company_id}
                                ) ")->result_array();

        return $data;
    }
}


if (!function_exists('action_attendanceTypes')) {
    function action_attendanceTypes($AttPresentTypeID, $PresentTypeDes)
    {
        $action = '<a onclick="delete_attendanceTypes(' . $AttPresentTypeID . ', \'' . $PresentTypeDes . '\')">';
        $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';

        return '<span class="pull-right">' . $action . '</span>';
    }
}

if (!function_exists('action_attendanceMaster')) {
    function action_attendanceMaster($EmpAttMasterID, $isAttClosed, $attDate)
    {
        $action = '';
        if ($isAttClosed == 1) {
            $action .= '<a onclick="open_attendanceDetailModal(' . $EmpAttMasterID . ', this)"><span title="View" rel="tooltip" class="fa fa-fw fa-eye"></span></a>';
            $action .= '&nbsp;&nbsp; | &nbsp;&nbsp;<a target="_blank" href="' . site_url('Employee/attendance_print/') . '/' . $EmpAttMasterID . '/' . $attDate . '" >';
            $action .= '<span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>';
        } else {
            $action = '<a onclick="open_attendanceDetailModal(' . $EmpAttMasterID . ', this)"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="delete_attendanceMaster(' . $EmpAttMasterID . ')">';
            $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';

        }

        return '<span class="pull-right">' . $action . '</span>';
    }
}

if (!function_exists('action_workShift')) {
    function action_workShift($shiftID, $Description)
    {
        $action = '<a onclick="fetchPage(\'system/hrm/shift_config\',' . $shiftID . ',\'HRMS\', 0, \'' . $Description . '\')">';
        $action .= '<i title="View" rel="tooltip" class="fa fa-fw fa-eye" aria-hidden="true"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        $action .= '<a onclick="edit_shift(' . $shiftID . ', \'' . $Description . '\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
        $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="delete_shift(' . $shiftID . ',  \'' . $Description . '\')">';
        $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';

        return '<span class="pull-right">' . $action . '</span>';
    }
}

if (!function_exists('fetch_employeeShift')) {
    function fetch_employeeShift($shiftID)
    {
        $companyID = current_companyID();

        $CI =& get_instance();
        $query = $CI->db->query("SELECT EIdNo, ECode, IFNULL(Ename1,'') Ename1, IFNULL(Ename2,'') Ename2, IFNULL(Ename3,'') Ename3, IFNULL(Ename4,'') Ename4, EmpImage,shiftEmp.autoID as autoID
                                  FROM srp_employeesdetails AS empMaster
                                  JOIN srp_erp_pay_shiftemployees AS shiftEmp ON shiftEmp.empID=empMaster.EIdNo AND empMaster.Erp_companyID={$companyID}
                                  WHERE shiftID={$shiftID} AND companyID={$companyID}")->result_array();
        return $query;
    }
}

if (!function_exists('fetch_weekDays')) {
    function fetch_weekDays()
    {
        $CI =& get_instance();
        /* $companyID = current_companyID();
         * $query = $CI->db->query("SELECT masterTB.*, shiftDetailID,shiftID,onDutyTime,offDutyTime
                                 FROM srp_weekdays AS masterTB
                                 LEFT JOIN srp_erp_pay_shiftdetails AS detTB ON detTB.dayID = masterTB.DayID AND detTB.companyID = {$companyID}
                                 ORDER BY masterTB.DayID")->result_array();*/
        $query = $CI->db->query("SELECT * FROM srp_weekdays ORDER BY DayID")->result_array();
        return $query;
    }
}

if (!function_exists('fetch_shifts')) {
    function fetch_shifts()
    {
        $CI =& get_instance();
        $companyID = current_companyID();
        $query = $CI->db->query("SELECT shiftID, Description FROM srp_erp_pay_shiftmaster WHERE companyID={$companyID}")->result_array();
        return $query;
    }
}


if (!function_exists('validateDate')) {
    function validateDate($str)
    {


    }
}


if (!function_exists('makeTimeTextBox')) {
    function makeTimeTextBox($name, $h = null, $d = true)
    {
        if (is_array($h)) {
            $hours = str_pad($h['h'], 2, '0', STR_PAD_LEFT);
            $minutes = str_pad($h['m'], 2, '0', STR_PAD_LEFT);
        } /*else {
            $hours = str_pad($h, 2, '0', STR_PAD_LEFT);
            $minutes = str_pad($m, 2, '0', STR_PAD_LEFT);
        }*/
        $disabled = '';
        if ($d) {
            $disabled = 'disabled';
        }


        $txt = '<div class="" style="width: 55px">';
        $txt .= '<div class="input-group">';
        $txt .= '<span class="input-group-btn">';
        $txt .= '<input ' . $disabled . ' onchange="updatebothfields(this,\'' . $name . '\')"  type="text" name="h_' . $name . '" class="trInputs inputdisabled timeBox txtH number h_' . $name . '" style="width: 25px" value="' . $hours . '" ';
        $txt .= 'onkeyup="hoursValidate(this)"  >';
        $txt .= '</span>';
        $txt .= '<span style="font-size: 14px; font-weight: bolder"> : </span>';
        $txt .= '<span class="input-group-btn">';
        $txt .= '<input ' . $disabled . ' onchange="updatebothfields(this,\'' . $name . '\')"  type="text" name="m_' . $name . '" class="trInputs inputdisabled timeBox txtM number m_' . $name . '" style="width: 25px" value="' . $minutes . '" ';
        $txt .= 'onkeyup="minutesValidate(this)" onchange="minutesValidateChange(this)">';
        $txt .= '</span>';
        $txt .= '</div>';
        $txt .= '</div>';
        /*$txt = '<input type="text" name="'.$name.'" class="trInputs txtH" value="'.$h.' '.$m.' 02" style="width:50%"/> : ';
        $txt .= '<input type="text" name="'.$name.'" class="trInputs txtM" value="'.$h.' '.$m.' 20" style="width:50%"/>';*/

        echo $txt;
    }
}

if (!function_exists('action_nationality')) {
    function action_nationality($nid, $Nationality, $usageCount)
    {
        $Nationality = "'" . $Nationality . "'";
        $action = '<a onclick="edit_nationality(' . $nid . ', ' . $Nationality . ')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
        if ($usageCount == 0) {
            $action .= '&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="delete_nationality(' . $nid . ', ' . $Nationality . ')">';
            $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';
        }

        return '<span class="pull-right">' . $action . '</span>';

    }
}

if (!function_exists('bankMasterData')) {
    function bankMasterData($bankID)
    {
        $CI =& get_instance();
        $CI->db->SELECT("bankID, bankCode, bankName");
        $CI->db->FROM('srp_erp_pay_bankmaster');
        $CI->db->WHERE('bankID', $bankID);
        $CI->db->WHERE('companyID', current_companyID());
        return $CI->db->get()->row_array();
    }
}

if (!function_exists('action_social_insurance')) {
    function action_social_insurance($sid, $socialInsurance, $employeeContribution, $employerContribution, $sortCode, $usageCount, $expenseGlAutoID, $liabilityGlAutoID, $isSlabApplicable, $SlabID)
    {
        $socialInsurance = "'" . $socialInsurance . "'";
        $glCodes = "{$expenseGlAutoID}_{$liabilityGlAutoID}";
        $companyID = current_companyID();

        $CI =& get_instance();
        $groupID = $CI->db->query("SELECT payGroupID FROM srp_erp_paygroupmaster WHERE socialInsuranceID='{$sid}' AND companyID={$companyID}")->row('payGroupID');

        $url = site_url('Employee/formulaDecode');
        $action = '<a onclick="formulaModalOpen('.$socialInsurance.', \'' . $groupID . '\', \''.$url.'\', \'\')">';
        $action .= '<span title="Formula" rel="tooltip" class="fa fa-superscript"></span></a></a>&nbsp;&nbsp; | &nbsp;&nbsp;';

        $action .= '<a onclick="edit_social_insurance(' . $sid . ', ' . $socialInsurance . ',\'' . $employeeContribution . '\',\'' . $employerContribution . '\',';
        $action .= '\'' . $sortCode . '\',\'' . $glCodes . '\',\'' . $isSlabApplicable . '\',\'' . $SlabID . '\')">';
        $action .= '<span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';

        if ($usageCount == 0) {
            $action .= '&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="delete_social_insurance(' . $sid . ', ' . $socialInsurance . ')">';
            $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';
        }

        return '<span class="pull-right">' . $action . '</span>';

    }
}

if (!function_exists('common_td_action')) {
    function common_td_action($masterID, $confirmYN, $approvedYN, $createdUserID, $docCode, $template=1)
    {
        $status = '<span class="pull-right">';
        $CI =& get_instance();

        $status .= '<a target="_blank" onclick="view_modal(' . $masterID . ','.$template.')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>';
        $status .= '&nbsp;&nbsp; | &nbsp;&nbsp; <span title="Print" rel="tooltip" class="glyphicon glyphicon-print" onclick="print_SD('.$masterID.', \''.$docCode.'\', '.$template.')" style="color:#3c8dbc"></span>';

        if ($confirmYN != 1) {
            $status .= '&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
            $status .= '<a onclick="load_details(' . $masterID . ','.$template.')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil" ></span>';
            $status .= '&nbsp;&nbsp;| &nbsp;&nbsp;<a onclick="delete_declaration(' . $masterID . ',\'Salary\');"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';
        }
        if ($createdUserID == trim($CI->session->userdata("empID")) and $approvedYN == 0 and $confirmYN == 1) {
            $status .= '&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
            $status .= '<a onclick="referBackDeclaration(' . $masterID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:#d15b47;"></span></a>';
        }
        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('common_approval_action')) {
    function common_approval_action($docID, $masterID, $Level, $approved, $ApprovedID, $type, $docCode=null)
    {
        $status = ($type == 'edit')? '<span class="pull-right">' : '';
        if ($approved == 0) {
            $str = ($type == 'edit')? '<span title="Edit" rel="tooltip" class="glyphicon glyphicon-ok"></span>' : $docCode;
            $status .= '<a onclick=\'fetch_approval("' . $masterID . '","' . $ApprovedID . '","' . $Level . '"); \'>'.$str.'</a>';
        }else{
            $str = ($type == 'edit')? '<span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span>' : $docCode;
            $status .= '<a target="_blank" onclick="documentPageView_modal(\''.$docID.'\',\'' . $masterID . '\')" >'.$str.'</a>';
        }
        $status .= ($type == 'edit')? '</span>' : '';

        return $status;
    }
}

if (!function_exists('load_salary_slab_action')) { /*get po action list*/
    function load_salary_slab_action($masterID)
    {
        $fetch = "fetchPage('system/hrm/create_new_slab','" . $masterID . "','HRMS')";
        $status = '<span class="pull-right">';
        $CI =& get_instance();
        //$status .= '<a target="_blank" onclick="documentPageView_modal(\'SD\',\'' . $masterID . '\')" ><span class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';

        $status .= '<a onclick="' . $fetch . '"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil" ></span>';

        $status .= '</span>';

        return $status;
    }
}

if (!function_exists('salary_categories')) {
    function salary_categories($type = array(), $isPayrollCat = null)
    {
        $CI =& get_instance();
        $com = current_companyID();

        $join = "('" . implode("','", $type) . "')";

        $where = 'companyID=' . $com . ' AND salaryCategoryType IN ' . $join . '';
        $where .= ($isPayrollCat != null)? ' AND isPayrollCategory = '.$isPayrollCat: '';

        $CI->db->select('srp_erp_pay_salarycategories.salaryDescription, srp_erp_pay_salarycategories.salaryCategoryID, salaryCategoryType')
            ->from('srp_erp_pay_salarycategories')
            ->where($where);
        $query = $CI->db->get();

        return $query->result_array();

    }
}


if (!function_exists('systemOT_drop')) {
    function systemOT_drop()
    {
        $CI =& get_instance();
        $data = $CI->db->query("SELECT ID, catDescription FROM srp_erp_pay_sys_overtimecategory ")->result_array();

        $data_arr = array('' => '');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['ID'])] = trim($row['catDescription']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('masterOT_drop')) {
    function masterOT_drop($asResult = null)
    {
        $companyID = current_companyID();
        $CI =& get_instance();
        $data = $CI->db->query("SELECT ID, description FROM srp_erp_pay_overtimecategory WHERE companyID={$companyID}")->result_array();

        if ($asResult == null) {
            $data_arr = array('' => '');
            if (isset($data)) {
                foreach ($data as $row) {
                    $data_arr[trim($row['ID'])] = trim($row['description']);
                }
            }
            return $data_arr;
        } else {
            return $data;
        }

    }
}


if (!function_exists('action_payGroup')) {
    function action_payGroup($payGroupID, $description, $isGroupTotal)
    {
        $usageCount = 0;
        $url = site_url('Employee/formulaDecode');
        $action = '<a onclick="formulaModalOpen(\'' . $description . '\', \'' . $payGroupID . '\', \''.$url.'\', \'\')"><span title="Formula"';
        $action .= ' rel="tooltip" class="fa fa-superscript"></span></a>&nbsp;&nbsp; | &nbsp; ';
        $action .= '<a onclick="edit_paygroup(' . $payGroupID . ',\'' . $description . '\',\'' . $isGroupTotal . '\')"><span title="Edit" rel="tooltip" ';
        $action .= 'class="glyphicon glyphicon-pencil"></span></a>';

        if ($usageCount == 0) {
            $action .= '&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="delete_paygroup(' . $payGroupID . ')">';
            $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';
        }

        return '<span class="pull-right">' . $action . '</span>';
    }
}

if (!function_exists('get_OT_groupMasterDet')) {
    function get_OT_groupMasterDet($groupID)
    {
        $companyID = current_companyID();
        $CI =& get_instance();
        $data = $CI->db->query("SELECT groupDetailID, overTimeID, formula, glCode, description
                                FROM srp_erp_pay_overtimegroupdetails AS groupDet
                                LEFT JOIN srp_erp_pay_overtimecategory AS groupCat ON groupCat.ID = groupDet.overTimeID
                                WHERE groupDet.companyID={$companyID} AND groupDet.groupID={$groupID}")->result_array();

        return $data;
    }
}


if (!function_exists('slabsmaster')) {
    function slabsmaster()
    {
        $CI =& get_instance();
        $com = current_companyID();
        $where = 'companyID=' . $com . '';
        $CI->db->select('Description, slabsMasterID, documentSystemCode')
            ->from('srp_erp_slabsmaster')
            ->where($where);
        $query = $CI->db->get();
        return $query->result();

    }
}

if (!function_exists('action_payee')) {
    function action_payee($sid, $socialInsurance, $sortCode, $liabilityGlAutoID, $SlabID, $isNonPayroll)
    {
        $expenseGlAutoID = '';
        $socialInsurance = "'" . $socialInsurance . "'";
        $glCodes = "{$expenseGlAutoID}_{$liabilityGlAutoID}";
        $isSlabApplicable = '';
        $employeeContribution = '';
        $employerContribution = '';
        $usageCount = false;
        $companyID = current_companyID();
        $CI =& get_instance();

        $groupID = $CI->db->query("SELECT payGroupID FROM srp_erp_paygroupmaster WHERE payeeID='{$sid}' AND companyID={$companyID}")->row('payGroupID');
        $url = site_url('Employee/formulaDecode');
        $action = '<a onclick="formulaModalOpen('.$socialInsurance.', \'' . $groupID . '\', \'' . $url . '\', \'\')"><span title="Formula" rel="tooltip"';
        $action .= 'class="fa fa-superscript"></span></a>&nbsp; | &nbsp;';
        $action .= '<a onclick="edit_social_insurance(' . $sid . ', ' . $socialInsurance . ',\'' . $employeeContribution . '\',\'' . $employerContribution . '\'';
        $action .= ',\'' . $sortCode . '\',\'' . $glCodes . '\',\'' . $isSlabApplicable . '\',\'' . $SlabID . '\', \''.$isNonPayroll.'\')">';
        $action .= '<span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';

        if ($usageCount == 0) {
            $action .= '&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="delete_social_insurance(' . $sid . ', ' . $socialInsurance . ')">';
            $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';
        }

        return '<span class="pull-right">' . $action . '</span>';

    }
}

if (!function_exists('load_employee_contribution')) {
    function load_employee_contribution($employeeContribution, $employerContribution)
    {
        if ($employeeContribution >= 1 && $employerContribution == 0) {
            return $employeeContribution;
        } else if ($employeeContribution == 0 && $employerContribution >= 1) {
            return $employerContribution;
        }

    }
}

if (!function_exists('over_time_group')) {
    function over_time_group()
    {
        $CI =& get_instance();
        $companyID = current_companyID();
        $data = $CI->db->query("SELECT * FROM `srp_erp_pay_overtimegroupmaster` WHERE `companyID` = '{$companyID}'")->result_array();
        $data_arr = array('' => 'Select Over Time Group');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['groupID'])] = trim($row['description']);
            }
        }
        return $data_arr;

    }
}

if (!function_exists('defaultPayrollCategories_drop')) {
    function defaultPayrollCategories_drop($asResult = null)
    {
        $CI =& get_instance();
        $CI->db->select("id,description,isGLCodeRequired");
        $CI->db->from('srp_erp_defaultpayrollcategories');
        $data = $CI->db->get()->result_array();

        if ($asResult != null) {
            $data_arr = array('' => '');
            if (isset($data)) {
                foreach ($data as $row) {
                    $data_arr[trim($row['id'])] = trim($row['description']);
                }
            }
            return $data_arr;
        } else {
            return $data;
        }
    }
}

if (!function_exists('action_employee_type')) {
    function action_employee_type($EmpContractTypeID, $usageCount)
    {
        $action = '<a onclick="editEmployeeDetail(' . $EmpContractTypeID . ', this)"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
        if ($usageCount == 0) {
            $action .= '&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="deleteEmployeeTypeMaster(' . $EmpContractTypeID . ')">';
            $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';
        }
        return '<span class="pull-right">' . $action . '</span>';

    }
}


if (!function_exists('make_dropDown')) {
    function make_dropDown($dropDownData, $selectedID, $isDisabled, $id)
    {
        $h_glCode = ''; $h_salCat = 0;
        $dropDown = '<select name="declarationID[]" id="groupDrop_'.$id.'" class="trInputs select2" onchange="changeGLCode(this)" '.$isDisabled.'>';
        $dropDown .= '<option value="">Select Grouping Type</option>';

        if( !empty($dropDownData) ){
            foreach($dropDownData as $keyDrop => $rowDrop){
                $decID = $rowDrop['monthlyDeclarationID'];
                $selected = ($selectedID == $decID)? 'selected' : '';
                if($selectedID == $decID){
                    $h_glCode = $rowDrop['GLAutoID'];
                    $h_salCat = $rowDrop['salaryCategoryID'];
                }
                $dropDown .= '<option value="'.$decID.'" '.$selected.' data-gl="'.$rowDrop['GLAutoID'].'" data-cat="'.$rowDrop['salaryCategoryID'].'">';
                $dropDown .= $rowDrop['monthlyDeclaration'].' | '.$rowDrop['GLSecondaryCode'].'</option>';
            }
        }

        $dropDown .= '</select>';
        $dropDown .= '<input type="hidden" name="h-glCode[]" class="h-glCode" value="'.$h_glCode.'">';
        $dropDown .= '<input type="hidden" name="h-category[]" class="h-categoryID" value="'.$h_salCat.'">';

        return $dropDown;
    }
}


if (!function_exists('designation_status')) {
    function designation_status($DesignationID, $status)
    {
        $checked = ($status == 1) ? 'checked' : '';
        $isDisable = ($status == 1) ? 'disabled' : '';
        $str = '<input type="checkbox" class="switch-chk" id="designation_status' . $DesignationID . '" onchange="changeDesignationStatus(this, ' . $DesignationID . ')"';
        $str .= 'data-size="mini" data-on-text="Yes" data-handle-width="45" data-off-color="danger" ';
        $str .= 'data-on-color="success" data-off-text="No" data-label-width="0" ' . $checked . ' '.$isDisable.'>';
        return  $str;
    }
}

if (!function_exists('designationActive_status')) {
    function designationActive_status($DesignationID, $status)
    {
        $checked = ($status == 1) ? 'checked' : '';
        $str = '<input type="checkbox" class="switch-chk" id="designationActive_status' . $DesignationID . '" onchange="changeActiveStatus(this, ' . $DesignationID . ')"';
        $str .= 'data-size="mini" data-on-text="Yes" data-handle-width="45" data-off-color="danger" ';
        $str .= 'data-on-color="success" data-off-text="No" data-label-width="0" ' . $checked . '>';
        return  $str;
    }
}

if(!function_exists('require_employeeDataStatus()')){
    function require_employeeDataStatus($empID, $isTibian='N'){
        $CI =& get_instance();
        $companyID = current_companyID();

        $data = $CI->db->query("SELECT EDOJ, DateAssumed, payCurrencyID, segmentID, EmployeeConType, contractStartDate, contractEndDate,
                                  EPassportExpiryDate, EVisaExpiryDate, EmpDesignationId, leaveGroupID, managerID
                                  FROM srp_employeesdetails AS empTB
                                  LEFT JOIN(
                                      SELECT empID, managerID, CONCAT(ECode, '_' ,Ename2) AS managerName FROM  srp_erp_employeemanagers
                                      JOIN srp_employeesdetails ON srp_employeesdetails.EIdNo=srp_erp_employeemanagers.managerID
                                      WHERE empID={$empID} AND companyID={$companyID} AND active=1
                                  )  AS managersTB ON managersTB.empID = empTB.EIdNo
                                  WHERE Erp_companyID={$companyID} AND EIdNo={$empID} ")->row_array();

        $msg = '';

        $msg .= (trim($data['EDOJ']) != '' && $data['EDOJ'] != null)? '' : 'date of join <br/>';
        if($isTibian == 'N') {
            $msg .= (trim($data['DateAssumed']) != '' && $data['DateAssumed'] != null)? '' : 'assume date <br/>';
        }
        $msg .= (trim($data['payCurrencyID']) != '' && $data['payCurrencyID'] != null && $data['payCurrencyID'] != 0)? '' : 'currency <br/>';
        $msg .= (trim($data['segmentID']) != '' && $data['segmentID'] != null && $data['segmentID'] != 0)? '' : 'segment <br/>';
        $msg .= (trim($data['EmployeeConType']) != '' && $data['EmployeeConType'] != null && $data['EmployeeConType'] != 0)? '' : 'Employee type <br/>';
        $msg .= (trim($data['EmpDesignationId']) != '' && $data['EmpDesignationId'] != null && $data['EmpDesignationId'] != 0)? '' : 'designation <br/>';
        $msg .= (trim($data['leaveGroupID']) != '' && $data['leaveGroupID'] != null && $data['leaveGroupID'] != 0)? '' : 'leave group <br/>';
        $msg .= (trim($data['managerID']) != '' && $data['managerID'] != null && $data['managerID'] != 0)? '' : 'Reporting manager<br/>';


        $status = ($msg == '')? 's' : 'e';
        return [$status, $msg, $data];
    }
}

if (!function_exists('action_contractHistory')) {
    function action_contractHistory($contractID, $isContract)
    {

        $action = '';

        if($isContract != 2){
            $action = '<a onclick="delete_contract(' . $contractID . ')"><span class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';
        }

        return '<span class="pull-right">' . $action . '</span>';

    }
}

if (!function_exists('isEmployeeConfirmed')) { //Nasik
    function isEmployeeConfirmed($empID)
    {
        $companyID = current_companyID();
        $CI = &get_instance();
        $CI->db->SELECT("empConfirmedYN");
        $CI->db->FROM('srp_employeesdetails');
        $CI->db->where('Erp_companyID', $companyID);
        $CI->db->where('EIdNo', $empID);
        $data = $CI->db->get()->row('empConfirmedYN');

        return $data;
    }
}

if (!function_exists('getEmployeesDeclaration')) { //Nasik
    function getEmployeesDeclaration($masterID)
    {
        $companyID = current_companyID();
        $CI = &get_instance();
        $convertFormat = convert_date_format_sql();
        $isGroupAccess = getPolicyValues('PAC', 'All');

        $str = '';
        if($isGroupAccess == 1){
            $currentEmp = current_userID();
            $str = "JOIN (
                        SELECT empTB.groupID, employeeID FROM srp_erp_payrollgroupemployees AS empTB
                        JOIN srp_erp_payrollgroupincharge AS incharge ON incharge.groupID=empTB.groupID
                        WHERE empTB.companyID={$companyID} AND incharge.companyID={$companyID} AND empID={$currentEmp}
                    ) AS accTb ON accTb.employeeID=EIdNo";
        }

        $employees = $CI->db->query("SELECT EIdNo, ECode, Ename2, DATE_FORMAT(EDOJ,'{$convertFormat}') AS EDOJ, transactionCurrencyDecimalPlaces AS dPlace
                                     FROM srp_employeesdetails AS empTB
                                     JOIN srp_erp_salarydeclarationmaster AS declarationMaster
                                     ON declarationMaster.transactionCurrencyID = empTB.payCurrencyID AND salarydeclarationMasterID={$masterID}
                                     {$str}
                                     WHERE Erp_companyID={$companyID} AND isPayrollEmployee=1 AND empConfirmedYN=1  AND isDischarged=0")->result_array();
        return $employees;
    }
}

if (!function_exists('salaryCategories_drop')) { //Nasik
    function salaryCategories_drop($masterID)
    {
        $companyID = current_companyID();
        $CI = &get_instance();
        $categories = $CI->db->query("SELECT salaryCategoryID, salaryDescription, salaryCategoryType FROM srp_erp_pay_salarycategories AS catMaster
                                      JOIN srp_erp_salarydeclarationmaster AS declarationMaster
                                      ON declarationMaster.isPayrollCategory = catMaster.isPayrollCategory AND salarydeclarationMasterID={$masterID}
                                      WHERE catMaster.companyID ={$companyID}")->result_array();
        return $categories;
    }
}

if (!function_exists('OT_monthlyAction')) {
    function OT_monthlyAction($id, $confirmedYN = 0,  $isProcess = 0, $code)
    {
        $edit = '';
        $delete = '';
        $view = '';
        $referBack = '';

        $fetch = "fetchPage('system/hrm/OverTimeManagementSalamAir/over_time_monthly_addition_detail',".$id." ,'HRMS','')";


        /*$print = '&nbsp;&nbsp; | &nbsp;&nbsp;<a target="_blank" href="' . site_url('Employee/monthlyAD_print/') . '/' . $id . '/' . $code . '" >';
        $print .= '<span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>';*/

        $print = '';

        if ($confirmedYN != 1) {
            $code = "'" . $code . "'";
            $edit = '<a onclick="' . $fetch . '"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
            $delete = '&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="delete_details(' . $id . ' , ' . $code . ')">';
            $delete .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';
        } elseif ($confirmedYN == 1) {
            $view = '<a onclick="' . $fetch . '"><span title="View" rel="tooltip" class="fa fa-fw fa-eye"></span></a>';
        }

        if ($isProcess == 0 && $confirmedYN == 1) {
            $referBack = '<a onclick="referBackConformation(' . $id . ')"><span style="color:#d15b47;" title="Refer Back" rel="tooltip"';
            $referBack .= ' class="glyphicon glyphicon-repeat"></span></a>&nbsp;&nbsp;|&nbsp;';
        }

        return '<span class="pull-right">' . $referBack . '' . $view . '' . $edit . '' . $delete . '' . $print . ' </span>';
    }
}

if (!function_exists('makeTimeTextBox_OT')) {
    function makeTimeTextBox_OT($i, $name, $rate_or_slab, $dPlaces, $isTotalBlock, $empID, $hours = null, $disabled = false)
    {
        $h = 0; $m = '00';
        if( $hours != null ){
            $h = floor($hours /60);
            $m = str_pad(($hours%60), 2, '0', STR_PAD_LEFT);
        }

        $nameConcat = $name . '_'.$i;
        $hourFn = 'onkeyup="calculateAmount(\''.$nameConcat.'\', \''.$rate_or_slab.'\', \''.$dPlaces.'\')"';
        $minutesFn = 'onkeyup="minutesValidate_OT(this, \''.$nameConcat.'\', \''.$rate_or_slab.'\', \''.$dPlaces.'\', \''.$isTotalBlock.'\')"';
        $minutesFn .= ' onchange="minutesValidateChange(this)"';

        if($isTotalBlock == 1){
            $hourFn = 'onchange="calculateBlockAmount(\'h\', \''.$nameConcat.'\', \''.$empID.'\', \''.$dPlaces.'\')"';
            $minutesFn = 'onkeyup="minutesValidateChange(this)" onchange="calculateBlockAmount(\'m\', \''.$nameConcat.'\', \''.$empID.'\', \''.$dPlaces.'\')"';
        }
        $disabled = ($rate_or_slab == 0)? 'disabled' : $disabled;


        $txt = '<div class="time-box-div">';
        $txt .= '<input type="text" name="h_' . $name . '[]" id="h_' . $nameConcat . '" class="trInputs number " value="' . $h . '" ' . $disabled;
        $txt .= ' style="width: 25px" '.$hourFn.'> : ';
        $txt .= '<input type="text" name="m_' . $name . '[]" id="m_' . $nameConcat.'" class="trInputs number" value="' . $m . '" ' . $disabled;
        $txt .= ' style="width: 25px" '.$minutesFn.' >';
        $txt .= '</div>';

        return $txt;
    }
}

if (!function_exists('fetch_fixed_element_master')) {
    function fetch_fixed_element_master()
    {
        $CI =& get_instance();
        $CI->db->SELECT("fixedElementID,fixedElementDescription");
        $CI->db->FROM('srp_erp_ot_fixedelements');
        $CI->db->WHERE('companyID', current_companyID());
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['fixedElementID'])] = trim($row['fixedElementDescription']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('get_pendingEmpApprovalData')) {
    function get_pendingEmpApprovalData($empID)
    {
        $companyID = current_companyID();
        $CI =& get_instance();
        $pendingData = $CI->db->query("SELECT * FROM srp_erp_employeedatachanges WHERE companyID={$companyID}
                                       AND empID={$empID}  AND approvedYN!=1")->result_array();

        return $pendingData;
    }
}

if (!function_exists('getEmployeesFixedElementDeclaration')) { //Addedby Nazir
    function getEmployeesFixedElementDeclaration($masterID)
    {
        $companyID = current_companyID();
        $CI = &get_instance();
        $convertFormat = convert_date_format_sql();
        $employees = $CI->db->query("SELECT EIdNo, ECode, Ename2, DATE_FORMAT(EDOJ,'{$convertFormat}') AS EDOJ, transactionCurrencyDecimalPlaces AS dPlace
                                     FROM srp_employeesdetails AS empTB
                                     JOIN srp_erp_ot_fixedelementdeclarationmaster AS declarationMaster
                                     ON declarationMaster.transactionCurrencyID = empTB.payCurrencyID AND fedeclarationMasterID={$masterID}
                                     WHERE Erp_companyID={$companyID} AND isPayrollEmployee=1 AND empConfirmedYN=1 AND isDischarged=0")->result_array();
        return $employees;
    }
}

if (!function_exists('load_fixedElementDeclaration_action')) {
    function load_fixedElementDeclaration_action($masterID, $confirmYN, $approvedYN, $createdUserID)
    {
        $fetch = "fetchPage('system/hrm/OverTimeManagementSalamAir/fixed_element_declaration_new','" . $masterID . "','HRMS')";
        $status = '<span class="pull-right">';
        $CI =& get_instance();

        $status .= '<a target="_blank" onclick="documentPageView_modal(\'FED\',\'' . $masterID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>';
        if ($confirmYN != 1) {
            $status .= '&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
            $status .= '<a onclick="' . $fetch . '"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil" ></span>';
            $status .= '&nbsp;&nbsp;| &nbsp;&nbsp;<a onclick="delete_declaration(' . $masterID . ',\'Invoices\');"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';
            //$status .= '&nbsp;&nbsp;|&nbsp;&nbsp;';
        }
        if ($createdUserID == trim($CI->session->userdata("empID")) and $approvedYN == 0 and $confirmYN == 1) {
            $status .= '&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
            $status .= '<a onclick="referbackDeclaration(' . $masterID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:#d15b47;"></span></a>';
        }
        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('load_fixed_element_declaration_action_approval')) { /*get po action list*/
    function load_fixed_element_declaration_action_approval($masterID, $Level, $approved, $ApprovedID)
    {
        $fetch = "fetchPage('system/hrm/OverTimeManagementSalamAir/fixed_element_declaration_new','" . $masterID . "','HRMS')";
        $status = '<span class="pull-right">';
        $CI =& get_instance();
        if ($approved == 0) {
            $status .= '<a onclick=\'fetch_approval("' . $masterID . '","' . $ApprovedID . '","' . $Level . '"); \'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>';
        }else{
            $status .= '<a target="_blank" onclick="documentPageView_modal(\'FED\',\'' . $masterID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>';
        }
        $status .= '</span>';

        return $status;
    }
}

if (!function_exists('OT_group_dropDown')) {
    function OT_group_dropDown()
    {
        $CI =& get_instance();
        $CI->db->SELECT("otGroupID,otGroupDescription");
        $CI->db->FROM('srp_erp_ot_groups');
        $CI->db->WHERE('companyID', current_companyID());
        $data = $CI->db->get()->result_array();
        //$data_arr = array('' => 'Select');
        $data_arr = array();
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['otGroupID'])] = trim($row['otGroupDescription']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('sso_slab_action')) {
    function sso_slab_action($masterID, $description)
    {
        $fetch = "fetchPage('system/hrm/create_new_sso_slab','" . $masterID . "','HRMS', '', '".$description."')";
        $status = '<span class="pull-right">';
        $status .= '<a onclick="' . $fetch . '"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil" ></span>';
        $status .= '</span>';

        return $status;
    }
}

if (!function_exists('get_sso_slabDetails')) {
    function get_sso_slabDetails($masterID)
    {
        $CI =& get_instance();
        $companyID = current_companyID();
        $data = $CI->db->query("SELECT * FROM srp_erp_ssoslabdetails WHERE companyID={$companyID}
                                AND ssoSlabMasterID={$masterID}")->result_array();
        return $data;
    }
}

if (!function_exists('makeCopyBlock')) {
    function makeCopyBlock($class, $function)
    {
        return  '<span class="applyToAll '.$class.'">
                     <button class="btn btn-xs btn-default" type="button" onclick="'.$function.'(this)">
                            <i class="fa fa-arrow-circle-down arrowDown"></i>
                     </button>
                 </span>';
    }
}

if (!function_exists('ssoSlabsMaster')) {
    function ssoSlabsMaster()
    {
        $CI =& get_instance();
        $CI->db->select('ssoSlabMasterID, description')
            ->from('srp_erp_ssoslabmaster')
            ->where('companyID', current_companyID());
        $query = $CI->db->get();
        return $query->result();

    }
}

if (!function_exists('search_otElement')) {
    function search_otElement($arr, $searchingKey)
    {
        $keys = array_keys(array_column($arr, 'templateDetailID'), $searchingKey);
        $new_array = array_map(function ($k) use ($arr) {
            return $arr[$k];
        }, $keys);

        return (!empty($new_array[0])) ? trim($new_array[0]['hourorDays']) : 0;
    }
}

if (!function_exists('search_otAmount')) {
    function search_otAmount($arr, $searchingKey)
    {
        $keys = array_keys(array_column($arr, 'templateDetailID'), $searchingKey);
        $new_array = array_map(function ($k) use ($arr) {
            return $arr[$k];
        }, $keys);

        return (!empty($new_array[0])) ? trim($new_array[0]['transactionAmount']) : 0;
    }
}

if (!function_exists('system_salary_cat_drop')) {
    function system_salary_cat_drop($sysType, $isData=null)
    {
        $companyID=current_companyID();
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);

        $data = $CI->db->query("SELECT salaryCategoryID, salaryDescription FROM srp_erp_pay_salarycategories catTB
                                JOIN srp_erp_defaultpayrollcategories defTB ON defTB.id = catTB.payrollCatID
                                WHERE defTB.code = '{$sysType}' AND companyID= $companyID")->result_array();

        $data_arr = [];
        if($isData == null){
            $data_arr = array('' => $CI->lang->line('common_select_salary_category')/*'Select Salary Category'*/);
        }

        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['salaryCategoryID'])] = trim($row['salaryDescription']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('system_salary_cat_drop_nopay')) {
    function system_salary_cat_drop_nopay($type)
    {
        $companyID=current_companyID();
        $CI =& get_instance();
        $data = $CI->db->query("SELECT salaryCategoryID, salaryDescription FROM srp_erp_pay_salarycategories WHERE payrollCatID=3
                                AND companyID={$companyID} AND isPayrollCategory={$type}")->result_array();

        return $data;
    }
}

if (!function_exists('payGroupSalaryCategories_decode')) {
    function payGroupSalaryCategories_decode($SSO_data = array())
    {
        $formula = (is_array($SSO_data))? trim($SSO_data['formulaString']) : $SSO_data;
        $payGroupCategories = (is_array($SSO_data))? trim($SSO_data['payGroupCategories']) :'';
        $formulaDecode_arr = array();
        $operand_arr = operand_arr();

        if(!empty($payGroupCategories)){
            global $globalFormula;
            $globalFormula = $formula;
            $formula = decode_payGroup($SSO_data);
        }


        $formula_arr = explode('|', $formula); // break the formula

        foreach ($formula_arr as $formula_row) {

            if (trim($formula_row) != '') {
                if (in_array($formula_row, $operand_arr)) {  } //validate is a operand
                else {

                    $elementType = $formula_row[0];

                    if ($elementType == '#') {
                        /*** Salary category ***/
                        $catArr = explode('#', $formula_row);
                        $formulaDecode_arr[] = $catArr[1];
                    }
                }
            }

        }
        return $formulaDecode_arr;
    }
}

if (!function_exists('fetch_employeeStatusWise')) {
    function fetch_employeeStatusWise()
    {
        $companyID = current_companyID();
        $CI =& get_instance();
        $statusCount = $CI->db->query("SELECT * FROM (
                                          SELECT ( SELECT COUNT(EIdNo) FROM srp_employeesdetails WHERE Erp_companyID={$companyID} AND isSystemAdmin!=1
                                          AND isDischarged=1 ) AS discharged, ( SELECT COUNT(EIdNo) FROM srp_employeesdetails WHERE Erp_companyID={$companyID}
                                          AND isSystemAdmin!=1 AND isDischarged = 0 AND empConfirmedYN IS NULL ) AS notConfirmed, ( SELECT COUNT(EIdNo) AS empCount FROM
                                          srp_employeesdetails WHERE Erp_companyID={$companyID} AND isSystemAdmin!=1 AND empConfirmedYN=1 AND isDischarged=0 ) AS activeEmp
                                       ) AS t1")->row_array();
        return $statusCount;
    }
}

if (!function_exists('employeePagination')) {
    function employeePagination()
    {
        $CI =& get_instance();
        $CI->load->library("pagination");
        //$CI->load->library("s3");

        $data_pagination = $CI->input->post('data_pagination');
        $per_page = 10;
        $companyID = current_companyID();

        $count = $CI->db->query("SELECT COUNT(EIdNo) AS empCount FROM srp_employeesdetails WHERE Erp_companyID={$companyID}
                                 AND isSystemAdmin != 1")->row('empCount');

        $isFiltered = 0;
        $searchKey_filter = '';
        $alpha_filter = '';
        $segment_filter = '';
        $designation_filter = '';
        $discharged_filter = '';
        $empStatus_filter = '';

        $searchKey = $CI->input->post('searchKey');
        $letter = $CI->input->post('letter');
        $designation = $CI->input->post('designation');
        $segment = $CI->input->post('segment');
        $empStatus = $CI->input->post('empStatus');


        if ($empStatus != '' && $empStatus != 'null') {
            if($empStatus == 2){
                $empStatus_filter = " AND isDischarged = 0 AND empConfirmedYN IS NULL ";
            }
            else if($empStatus == 1 || $empStatus == 0){
                $empStatus_filter = " AND isDischarged = " . $empStatus;
                if($empStatus == 0){
                    $empStatus_filter .= " AND empConfirmedYN = 1";
                }
            }
            $isFiltered = 1;
        }

        if (!empty($designation) && $designation != 'null') {
            $designation = array($CI->input->post('designation'));
            $whereIN = "( " . join("' , '", $designation) . " )";
            $designation_filter = " AND EmpDesignationId IN " . $whereIN;
            $isFiltered = 1;
        }

        if (!empty($segment) && $segment != 'null') {
            $segment = array($CI->input->post('segment'));
            $whereIN = "( " . join("' , '", $segment) . " )";
            $segment_filter = " AND segTB.segmentID IN " . $whereIN;
            $isFiltered = 1;
        }

        if($letter != null){
            $alpha_filter = ' AND ( Ename3 LIKE \''.$letter.'%\') ';
            $isFiltered = 1;
        }

        if($searchKey != ''){
            $searchKey_filter = " WHERE (empShtrCode LIKE '%$searchKey%' OR Ename3 LIKE '%$searchKey%' OR DesDescription LIKE '%$searchKey%' OR ";
            $searchKey_filter .= " description LIKE '%$searchKey%' OR doj LIKE '%$searchKey%' OR EEmail LIKE '%$searchKey%' OR genderStr LIKE '%$searchKey%'";
            $searchKey_filter .= " OR EcMobile LIKE '%$searchKey%' OR managerReporting LIKE '%$searchKey%' )";
            $isFiltered = 1;
        }

        $countFilter = 0;

        if($isFiltered == 1){
            $countFilterWhere = $designation_filter . $segment_filter . $discharged_filter . $alpha_filter. $empStatus_filter;
            $convertFormat = convert_date_format_sql();
            $countFilter = $CI->db->query("SELECT COUNT(EIdNo) AS empCount FROM(
                                               SELECT EIdNo, EmpSecondaryCode AS empShtrCode, ECode, Ename1, Ename2, Ename3, EmpShortCode, EEmail,
                                               CONCAT( EpAddress1, ' ', EpAddress2, ' ', EpAddress3 ) AS address, EpTelephone, EcPOBox, EcMobile, TitleDescription,
                                               segTB.description AS segment, DesDescription, NIC, managerReporting, employeeType,
                                               DATE_FORMAT(EDOJ, '{$convertFormat}') AS doj,  IF(isDischarged=1, 'Discharged', 'Active') AS empStatus,
                                               IF(Gender=1, 'Male', 'Female') AS genderStr, segmentCode, description
                                               FROM srp_employeesdetails AS t1
                                               JOIN srp_titlemaster ON TitleID=EmpTitleId
                                               LEFT JOIN srp_designation ON DesignationID=t1.EmpDesignationId
                                               LEFT JOIN srp_erp_segment AS segTB ON segTB.segmentID=t1.segmentID
                                               LEFT JOIN srp_nationality ON srp_nationality.NId=t1.Nid
                                               LEFT JOIN srp_erp_systememployeetype AS employeeType ON employeeType.employeeTypeID=t1.EmployeeConType
                                               LEFT JOIN (
                                                   SELECT empID, CONCAT( EmpSecondaryCode,' - ', Ename2 )AS managerReporting FROM srp_erp_employeemanagers
                                                   JOIN srp_employeesdetails ON srp_employeesdetails.EIdNo=srp_erp_employeemanagers.managerID
                                                   WHERE companyID={$companyID} AND active=1
                                               ) AS repotingManagerTB ON repotingManagerTB.empID=t1.EIdNo
                                               WHERE t1.Erp_companyID={$companyID} AND isSystemAdmin != 1 {$countFilterWhere}
                                           ) AS t1 $searchKey_filter ")->row('empCount');

        }



        $config = array();
        $config["base_url"] = "#employee-list";
        $config["total_rows"] =  ($isFiltered == 1) ? $countFilter : $count;
        $config["per_page"] = $per_page;
        $config["data_page_attr"] = 'data-emp-pagination';
        $config["uri_segment"] = 3;

        $CI->pagination->initialize($config);

        $page = (!empty($data_pagination)) ? (($data_pagination -1) * $per_page): 0;
        $employeeData = load_employee_data($page, $per_page);
        $dataCount = $employeeData['dataCount'];

        $data["empCount"] = $count;
        $data["employee_list"] = $employeeData['employee_list'];
        $data["pagination"] = $CI->pagination->create_links_employee_master();
        $data["per_page"] = $per_page;
        $thisPageStartNumber = ($page+1);
        $thisPageEndNumber = $page+$dataCount;

        if($isFiltered == 1){
            $data["filterDisplay"] = "Showing {$thisPageStartNumber} to {$thisPageEndNumber} of {$countFilter} entries (filtered from {$count} total entries)";
        }else{
            $data["filterDisplay"] = "Showing {$thisPageStartNumber} to {$thisPageEndNumber} of {$count} entries";
        }


        return $data;

    }
}

if (!function_exists('load_employee_data')) {
    function load_employee_data($page, $per_page)
    {
        $searchKey_filter = '';
        $alpha_filter = '';
        $segment_filter = '';
        $designation_filter = '';
        $empStatus_filter = '';

        $CI =& get_instance();
        $letter = $CI->input->post('letter');
        $searchKey = $CI->input->post('searchKey');
        $designation = $CI->input->post('designation');
        $segment = $CI->input->post('segment');
        $isDischarged = $CI->input->post('isDischarged');
        $empStatus = $CI->input->post('empStatus');

        if ($empStatus != '' && $empStatus != 'null') {
            if($empStatus == 2){
                $empStatus_filter = " AND isDischarged = 0 AND empConfirmedYN IS NULL ";
            }
            else if($empStatus == 1 || $empStatus == 0){
                $empStatus_filter = " AND isDischarged = " . $empStatus;
                if($empStatus == 0){
                    $empStatus_filter .= " AND empConfirmedYN = 1";
                }
            }
        }

        if (!empty($designation) && $designation != 'null') {
            $designation = array($CI->input->post('designation'));
            $whereIN = "( " . join("' , '", $designation) . " )";
            $designation_filter = " AND EmpDesignationId IN " . $whereIN;
        }

        if (!empty($segment) && $segment != 'null') {
            $segment = array($CI->input->post('segment'));
            $whereIN = "( " . join("' , '", $segment) . " )";
            $segment_filter = " AND t1.segmentID IN " . $whereIN;
        }

        if($letter != null){
            $alpha_filter = ' AND ( Ename3 LIKE \''.$letter.'%\') ';
        }

        if($searchKey != ''){
            $searchKey_filter = " WHERE (empShtrCode LIKE '%$searchKey%' OR Ename2 LIKE '%$searchKey%' OR DesDescription LIKE '%$searchKey%' OR ";
            $searchKey_filter .= " description LIKE '%$searchKey%' OR doj LIKE '%$searchKey%' OR EEmail LIKE '%$searchKey%' OR genderStr LIKE '%$searchKey%'";
            $searchKey_filter .= " OR EcMobile LIKE '%$searchKey%' OR managerReporting LIKE '%$searchKey%' )";
        }

        switch ($isDischarged) {
            case 'N':
                $discharged_filter = ' AND isDischarged != 1';
                break;

            case 'Y':
                $discharged_filter = ' AND isDischarged = 1';
                break;

            default:
                $discharged_filter = '';
        }

        $companyID = current_companyID();
        $convertFormat = convert_date_format_sql();
        $where = "isSystemAdmin != 1 AND t1.Erp_companyID = " . $companyID . $designation_filter . $segment_filter . $discharged_filter . $alpha_filter. $empStatus_filter;

        $data = $CI->db->query("SELECT * FROM(
                                    SELECT EIdNo, EmpSecondaryCode AS empShtrCode, ECode, Ename1, Ename2, Ename3, EmpShortCode, EEmail,
                                    CONCAT( EpAddress1, ' ', EpAddress2, ' ', EpAddress3 ) AS address, EpTelephone, EcPOBox, EcMobile, TitleDescription,
                                    segTB.description AS segment, DesDescription, NIC, managerReporting, employeeType, IFNULL(pendingDataTB.empID,0) AS pendingData,
                                    DATE_FORMAT(EDOJ, '{$convertFormat}') AS doj,  IF(isDischarged=1, 'Discharged', 'Active') AS empStatus,
                                    IF(Gender=1, 'Male', 'Female') AS genderStr, segmentCode, description, Gender, EmpImage, empConfirmedYN
                                    FROM srp_employeesdetails AS t1
                                    LEFT JOIN srp_designation ON DesignationID=t1.EmpDesignationId
                                    JOIN srp_titlemaster ON TitleID=EmpTitleId
                                    LEFT JOIN srp_erp_segment AS segTB ON segTB.segmentID=t1.segmentID
                                    LEFT JOIN srp_nationality ON srp_nationality.NId=t1.Nid
                                    LEFT JOIN srp_erp_systememployeetype AS employeeType ON employeeType.employeeTypeID=t1.EmployeeConType
                                    LEFT JOIN (
                                            SELECT empID, CONCAT( EmpSecondaryCode,' - ', Ename2 )AS managerReporting FROM srp_erp_employeemanagers
                                            JOIN srp_employeesdetails ON srp_employeesdetails.EIdNo=srp_erp_employeemanagers.managerID
                                            WHERE companyID={$companyID} AND active=1
                                    ) AS repotingManagerTB ON repotingManagerTB.empID=t1.EIdNo
                                    LEFT JOIN (
                                        SELECT empID FROM srp_erp_employeedatachanges WHERE companyID={$companyID} AND approvedYN=0
                                        UNION 
                                        SELECT empID  FROM srp_erp_employeefamilydatachanges WHERE companyID={$companyID} AND approvedYN=0
                                        UNION 
                                        SELECT empID FROM srp_erp_family_details WHERE approvedYN=0
                                    ) AS pendingDataTB ON pendingDataTB.empID=t1.EIdNo
                                    WHERE {$where}
                                ) t1 {$searchKey_filter} ORDER BY Ename2 LIMIT {$page}, {$per_page}")->result_array();
        //echo $CI->db->last_query();
        $employee_list = $data;
        $returnData = '';
        $color = "#FF0";
        $isAuthenticate = emp_master_authenticate(); /** Check company policy on 'Employee Master Edit Approval' **/
        if(!empty($employee_list)){
            $CI->load->library('s3');
            $male_img = $CI->s3->getMyAuthenticatedURL('images/users/male.png', 3600);
            $female_img = $CI->s3->getMyAuthenticatedURL('images/users/female.png', 3600);

            foreach($employee_list as $key=>$empData){
                $empID = $empData['EIdNo'];

                $pendingDataDis = 'hidden';
                if($isAuthenticate == 0){
                    $pendingDataDis = ($empData['pendingData'] == 0)? 'hidden': '';
                }


                $empStatus = $empData['empStatus'];
                if($empStatus == 'Discharged'){
                    $label = 'danger';
                }else{
                    $empStatus = ($empData['empConfirmedYN'] != 1)? 'Not confirmed' : $empStatus;
                    $label = ($empData['empConfirmedYN'] != 1)? 'warning' : 'success';
                }

                //$empImage = empImageCheck($empData['EmpImage'], $empData['Gender']);
                $empImage = trim($empData['EmpImage']);
                if($empImage == ''){
                    $empImage = ($empData['Gender'] == 1)? $male_img: $female_img;
                }
                elseif ($empImage == 'images/users/male.png'){
                    $empImage = $male_img;
                }
                elseif ($empImage == 'images/users/female.png'){
                    $empImage = $female_img;
                }
                else{
                    $empImage = $CI->s3->getMyAuthenticatedURL($empImage, 3600);
                    /*if( $CI->s3->getMyObjectInfo($empImage) ){
                        $empImage = $CI->s3->getMyAuthenticatedURL($empImage, 3600);
                    }
                    else{
                        $empImage = ($empData['Gender'] == 1)? $male_img: $female_img;
                    }*/
                }


                $firstDivStyle = ($key==0)? ' style="margin-top: 1px;"' : '';
                $firstDivInput = ($key==0)? '<input id="first-in-emp-list" />' : '';

                if($searchKey != ''){
                    $mailID = toolTip_empMaster($empData['EEmail'], 23, 20, $searchKey);
                    $empName = highlight_word( $empData['Ename2'], $searchKey, $color );
                    $empCode = highlight_word( $empData['empShtrCode'], $searchKey, $color );
                    $designationDes = highlight_word( $empData['DesDescription'], $searchKey, $color );
                    $DOJ = highlight_word( $empData['doj'], $searchKey, $color );
                    $managerReporting = highlight_word( $empData['managerReporting'], $searchKey, $color );
                    $segment = highlight_word( $empData['segment'], $searchKey, $color );
                    $genderStr = highlight_word( $empData['genderStr'], $searchKey, $color );
                    $mobileNo = highlight_word( $empData['EcMobile'], $searchKey, $color );
                }
                else{
                    $mailID = toolTip_empMaster($empData['EEmail'], 23, 20);
                    $empName = $empData['Ename2'];
                    $empCode = $empData['empShtrCode'];
                    $designationDes = $empData['DesDescription'];
                    $DOJ = $empData['doj'];
                    $managerReporting = $empData['managerReporting'];
                    $segment = $empData['segment'];
                    $genderStr = $empData['genderStr'];
                    $mobileNo = $empData['EcMobile'];
                }

                /*<ul class="list-inline">
                    <li><a href="#" onclick="edit_empDet('.$empID.')"><i class="fa fa-eye"></i></a></li>
                    <li><a href="#"><i class="fa fa-bookmark"></i></a></li>
                    <li><a href="#"><i class="fa fa-trash"></i></a></li>
                </ul>*/
                //'<span class="pull-right label label-'.$label.' emp-status-label">'.$empStatus.'</span>';
                //<span class="pull-right emp-status-label" style="font-weight:bold ;color: '.$label.'">'.$empStatus.'</span>
                $returnData .= $firstDivInput;
                $returnData .= '<div class="candidate-description client-description applicants-content" '.$firstDivStyle.'>
                                    <div class="language-print client-des clearfix">
                                        <div class="aplicants-pic pull-left">
                                            <img src="'.$empImage.'" alt="">
                                            <ul class="list-inline">

                                            </ul>
                                        </div>

                                        <div class="clearfix">
                                            <div class="pull-left">
                                                <h5 class="empNameLink" onclick="edit_empDet('.$empID.')">'.$empName.'</h5>
                                                <a href="#" onclick="edit_empDet('.$empID.')">'.$empCode.'</a>
                                            </div>
                                            <span class="pull-right label label-'.$label.' emp-status-label">'.$empStatus.'</span>
                                            <span class="pull-right label notfi-label '.$pendingDataDis.'" onclick="edit_empDet('.$empID.', 1)"> <i class="fa fa-bell" aria-hidden="true"></i> </span>
                                        </div>

                                        <div class="aplicant-details-show clearfix">
                                            <ul class="list-unstyled pull-left">
                                                <li><span>Designation: <b class="aplicant-detail">'.$designationDes.'</b></span></li>
                                                <li><span>Date Joined: <b class="aplicant-detail">'.$DOJ.'</b></span></li>
                                                <li><span>Primary E-Mail: '.$mailID.'</span></li>
                                                <li><span>Reporting Manager: <b class="aplicant-detail">'.$managerReporting.'</b></span></li>
                                            </ul>

                                            <ul class="list-unstyled pull-left">
                                                <li><span>Segment: <b class="aplicant-detail">'.$segment.'</b></span></li>
                                                <li><span>Gender: <b class="aplicant-detail">'.$genderStr.'</b></span></li>
                                                <li><span>Mobile: <b class="aplicant-detail">'.$mobileNo.'</b></span></li>
                                                <li class="dropdown">
                                                   <a href="#" class="dropdown-toggle classDropToggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                                     aria-expanded="false">Action <i class="caret"></i>
                                                   </a>
                                                   <ul class="dropdown-menu classDropMenu">
                                                        <li><a href="#" onclick="loadData(\'LA\','.$empID.')">Leave History</a></li>
                                                   </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>'; //<li><a href="#" onclick="loadData(\'EC\','.$empID.')">Expanse Claim</a></li>
            }
        }
        else{
            $returnData .= '<div class="candidate-description client-description applicants-content">No records</div>';
        }
        return [
            'dataCount' => count($employee_list),
            'employee_list' => $returnData
         ];
    }
}


if (!function_exists('toolTip_filter')) {
    function toolTip_filter($str = '', $maxLength, $outPutContentLength=20)
    {
        $str = trim($str);
        $outPut = $str;
        if(strlen($str) > $maxLength){
            $subStr = substr($str, 0, $outPutContentLength);
            //$outPut = '<b class="aplicant-detail mail-tool-tip" data-title="'.$outPut.'">'.$subStr.'<b class="more-tip">...</b></b>';
            $outPut = '<b class="aplicant-detail mail-tool-tip" title="'.$outPut.'">'.$subStr.'<b class="more-tip">...</b></b>';
        }else{
            $outPut = '<b class="aplicant-detail mail-tool" >'.$str.' </b>';
        }

        return $outPut;
    }
}

if (!function_exists('toolTip_empMaster')) {
    function toolTip_empMaster($str = '', $maxLength, $outPutContentLength, $searchKey='')
    {
        $str = trim($str);
        $outPut = $str;
        $color = '#FF0';

        if(strlen($str) > $maxLength){
            $subStr = substr($str, 0, $outPutContentLength);
            if($searchKey != '' ){
                if (strpos($str, $searchKey) !== false) {
                    $subStr = '<mark class="searchHighlight">' . $subStr . '</mark>';
                }
            }

            $outPut = '<b class="aplicant-detail mail-tool-tip" data-title="'.$outPut.'">'.$subStr.'<b class="more-tip">...</b></b>';

        }else{
            $str = ($searchKey != '' )? highlight_word( $str, $searchKey, $color ): $str;
            $outPut = '<b class="aplicant-detail mail-tool" >'.$str.' </b>';
        }

        return $outPut;
    }
}


if (!function_exists('fetch_employeeWiseSegment')) {
    function fetch_employeeWiseSegment()
    {
        $CI =& get_instance();
        $companyID = current_companyID();

        $data = $CI->db->query("SELECT srp_erp_segment.segmentID, segmentCode, description, count(EIdNo) AS empCount
                                FROM srp_erp_segment
                                JOIN srp_employeesdetails AS empTB ON empTB.segmentID = srp_erp_segment.segmentID
                                AND Erp_companyID={$companyID} AND isSystemAdmin != 1
                                WHERE status=1 AND companyID={$companyID} GROUP BY empTB.segmentID")->result_array();

        return $data;
    }
}

if (!function_exists('highlight_word')) {
    function highlight_word($haystack, $needle, $color = '#FF0'){
        return preg_replace("/($needle)/i", sprintf('<mark style="background-color: %s" class="searchHighlight">$1</mark>', $color), $haystack );
    }
}

if (!function_exists('fetch_employeeWiseDesignation')) {
    function fetch_employeeWiseDesignation()
    {
        $CI =& get_instance();
        $companyID = current_companyID();

        $data = $CI->db->query("SELECT DesignationID, DesDescription, COUNT(EIdNo) AS empCount
                                FROM srp_employeesdetails AS t1
                                JOIN srp_designation ON DesignationID=t1.EmpDesignationId AND srp_designation.Erp_companyID={$companyID}
                                WHERE t1.Erp_companyID={$companyID} AND isSystemAdmin != 1 GROUP BY DesignationID")->result_array();

        return $data;
    }
}

if (!function_exists('employee_details')) {
    function employee_details($empID=null)
    {
        $CI =& get_instance();

        if($empID){
            $data = $CI->Employee_model->employee_details($empID);
        }
        else{
            $data = $CI->Employee_model->employee_details();
        }

        $CI->load->library('s3');
        if(!empty($data['thisEmpID'])){
            //$empImage = empImageCheck($data['EmpImage'], $data['Gender']);
            $empImage = (!empty($data['EmpImage']))? $data['EmpImage']: '-';
            if( $CI->s3->getMyObjectInfo($empImage) ){
                $empImage = $CI->s3->getMyAuthenticatedURL($empImage, 3600);
            }
            else{
                $img = ($data['Gender'] == 1)? 'images/users/male.png': 'images/users/female.png';
                $empImage = $CI->s3->getMyAuthenticatedURL($img, 3600);
            }
            $data['EmpImage'] = $empImage;

            //$empSignature = empImageCheck($data['empSignature'], 'signature');
            $empSignature = $data['empSignature'];
            if( $CI->s3->getMyObjectInfo($empSignature) &&  !empty($empSignature)){
                $empSignature = $CI->s3->getMyAuthenticatedURL($empSignature, 3600);
            }
            else{
                $empSignature = $CI->s3->getMyAuthenticatedURL('images/users/No_Image.png', 3600);
            }
            $data['empSignature'] = $empSignature;


            $managerID = $data['managerId'];

            if(!empty($managerID)){
                $companyID = current_companyID();
                $managerData = $CI->db->query("SELECT Ename2, EmpImage, Gender, DesDescription FROM srp_employeesdetails AS t1
                                           JOIN srp_designation AS t2 ON t2.DesignationID = t1.EmpDesignationId
                                           WHERE t1.Erp_CompanyID={$companyID} AND t2.Erp_companyID={$companyID}
                                           AND EIdNo={$managerID}")->row_array();

                //$managerImg = empImageCheck($managerData['EmpImage'], $managerData['Gender']);
                $managerImg = (!empty($managerData['EmpImage']))? $managerData['EmpImage']: '-';
                if( $CI->s3->getMyObjectInfo($managerImg) ){
                    $managerImg = $CI->s3->getMyAuthenticatedURL($managerImg, 3600);
                }
                else{
                    $img = ($data['Gender'] == 1)? 'images/users/male.png': 'images/users/female.png';
                    $managerImg = $CI->s3->getMyAuthenticatedURL($img, 3600);
                }

                $data['managerName'] =  $managerData['Ename2'];
                $data['managerImg'] =  $managerImg;
                $data['managerDesignation'] =  $managerData['DesDescription'];

            }

            $employeeConType = $data['EmployeeConType'];

            if(!empty($employeeConType)){
                $companyID = current_companyID();
                $employmentType= $CI->db->query("SELECT Description FROM srp_empcontracttypes WHERE Erp_CompanyID={$companyID}
                                               AND EmpContractTypeID={$employeeConType}")->row('Description');

                $data['employmentTypeDisplay'] =  $employmentType;

            }

            $join = $data['EDOJ_ORG'];
            $data['joinDate-display'] = (!empty($join))? date('M d, Y', strtotime($join)) : '';

            if((!empty($join))){
                $toDay = date('Y-m-d');

                if($toDay >= $join){
                    $toDay = new DateTime(date('Y-m-d'));
                    $join = new DateTime($join);

                    $interval = $toDay->diff($join);
                    $y = ($interval->y) ? $interval->y.'y' : '';
                    $m = ($interval->m) ? $interval->m.'m' : '';
                    $d = ($interval->d) ? $interval->d.'d' : '';

                    $periodDisplay = $y;
                    $periodDisplay .= ($periodDisplay != '' && $m != '') ? ' - '.$m : $m;
                    $periodDisplay .= ($periodDisplay != '' && $d != '') ? ' - '.$d : $d;


                    $data['period-display'] = $periodDisplay;
                }

            }

        }

        return $data;
    }


}

if (!function_exists('get_pendingFamilyApprovalData')) {
    function get_pendingFamilyApprovalData($empFamilyDetailsID, $isFromEmpMaster='')
    {
        $companyID = current_companyID();
        $CI =& get_instance();

        if($isFromEmpMaster == 'Y'){
            $pendingData = $CI->db->query("SELECT SUM(pendingCount) AS pendingCount FROM (
                                               SELECT COUNT(id) AS pendingCount FROM srp_erp_employeefamilydatachanges WHERE companyID={$companyID}
                                               AND empID={$empFamilyDetailsID} AND approvedYN!=1
                                               UNION ALL
                                               SELECT COUNT(empfamilydetailsID) AS pendingCount FROM srp_erp_family_details WHERE approvedYN!=1 AND
                                               empID={$empFamilyDetailsID}
                                           )AS t1 ")->row('pendingCount');

            return $pendingData;
        }

        $pendingData = $CI->db->query("SELECT * FROM srp_erp_employeefamilydatachanges WHERE companyID={$companyID}
                                       AND empfamilydetailsID={$empFamilyDetailsID}  AND approvedYN!=1")->result_array();

        return $pendingData;
    }
}

if (!function_exists('fetch_leavePlan')) {
    function fetch_leavePlan($empID=null)
    {
        $CI =& get_instance();
        $year = date('Y');
        $companyID = current_companyID();

        if( $empID != null ){
            $result =  $CI->db->query("SELECT * FROM (
                                            SELECT leaveMasterID AS id, Ename2 AS text, DATE_FORMAT(startDate,'%d-%m-%Y') AS start_date, startDate, `comments` AS levComment,
                                            (DATEDIFF(endDate, startDate)+1) AS duration, 0 AS progress, documentCode, empID, '' AS parent, applicationType,
                                            IF(applicationType=1, 'Leave', 'Plan') AS typeText, DATE_FORMAT(endDate, '%Y-%m-%d') endDate2, approvedYN, confirmedYN,
                                            CASE
                                               WHEN (applicationType=2) THEN '#fda70a'
                                               WHEN (approvedYN=1) THEN '#166123'
                                               WHEN (confirmedYN=1) THEN '#13f358'
                                               ELSE '#61cde2'
                                            END AS color
                                            FROM srp_erp_leavemaster AS lMastre
                                            JOIN srp_employeesdetails AS empTB ON empTB.EIdNo = lMastre.empID AND Erp_companyID={$companyID}
                                            JOIN (
                                              SELECT empID AS rptEmpID FROM srp_erp_employeemanagers WHERE active=1 AND  managerID={$empID}
                                              AND companyID={$companyID}
                                            ) AS rptTB ON rptTB.rptEmpID = lMastre.empID
                                            WHERE companyID={$companyID}  AND ( YEAR(startDate) >= YEAR(NOW()) AND YEAR(endDate) <= (YEAR(NOW())+1) )
                                            UNION
                                            SELECT leaveMasterID AS id, Ename2 AS text, DATE_FORMAT(startDate,'%d-%m-%Y') AS start_date, startDate, `comments` AS levComment,
                                            (DATEDIFF(endDate, startDate)+1) AS duration, 0 AS progress, documentCode, empID, '' AS parent, applicationType,
                                            IF(applicationType=1, 'Leave', 'Plan') typeText, DATE_FORMAT(endDate, '%Y-%m-%d') endDate2, approvedYN, confirmedYN,
                                            CASE
                                               WHEN (applicationType=2) THEN '#fda70a'
                                               WHEN (approvedYN=1) THEN '#166123'
                                               WHEN (confirmedYN=1) THEN '#13f358'
                                               ELSE '#61cde2'
                                            END AS color
                                            FROM srp_erp_leavemaster AS lMastre
                                            JOIN srp_employeesdetails AS empTB ON empTB.EIdNo = lMastre.empID AND Erp_companyID={$companyID}
                                            WHERE companyID={$companyID} AND lMastre.empID={$empID}  AND ( YEAR(startDate) >= YEAR(NOW()) AND YEAR(endDate) <= (YEAR(NOW())+1) )
                                       )AS t1 ORDER BY t1.startDate")->result_array();

        }
        else{
            $result =  $CI->db->query("SELECT leaveMasterID AS id, Ename2 AS text, DATE_FORMAT(startDate,'%d-%m-%Y') AS start_date, `comments` AS levComment,
                                   (DATEDIFF(endDate, startDate)+1) AS duration, 0 AS progress, documentCode, IF(applicationType=1, 'Leave', 'Plan') AS typeText,
                                   empID, '' AS parent, applicationType, DATE_FORMAT(endDate, '%Y-%m-%d') endDate2, approvedYN, confirmedYN,
                                   CASE
                                      WHEN (applicationType=2) THEN '#fda70a'
                                      WHEN (approvedYN=1) THEN '#166123'
                                      WHEN (confirmedYN=1) THEN '#13f358'
                                      ELSE '#61cde2'
                                   END AS color
                                   FROM srp_erp_leavemaster AS lMastre
                                   JOIN srp_employeesdetails AS empTB ON empTB.EIdNo = lMastre.empID AND Erp_companyID={$companyID}
                                   WHERE companyID={$companyID} AND ( YEAR(startDate) >= YEAR(NOW()) AND YEAR(endDate) <= (YEAR(NOW())+1) )
                                   ORDER BY startDate")->result_array(); //AND empID=8 IF(applicationType=1, '#61cde2', '#6b6b6b')
        }

        return $result;
    }
}

if (!function_exists('getLeaveApprovalSetup')) {
    function getLeaveApprovalSetup($isSetting = 'N',$input_companyId=null)
    {
        if($input_companyId==null){
            $companyID = current_companyID();
        }else {
            $companyID = $input_companyId;
        }
        $CI =& get_instance();

        $appSystemValues = $CI->db->query("SELECT * FROM srp_erp_leavesetupsystemapprovaltypes")->result_array();

        if($isSetting == 'Y'){
            $arr = [ 0 => '' ];
            foreach($appSystemValues as $key=>$val){
                $arr[$val['id']] = $val['description'];
            }
            $appSystemValues = $arr;
        }

        $approvalLevel = $CI->db->query("SELECT approvalLevel FROM srp_erp_documentcodemaster WHERE documentID = 'LA' AND
                                         companyID={$companyID} ")->row('approvalLevel');

        $approvalSetup = $CI->db->query("SELECT approvalLevel, approvalType, empID, systemTB.*
                                         FROM srp_erp_leaveapprovalsetup AS setupTB
                                         JOIN srp_erp_leavesetupsystemapprovaltypes AS systemTB ON systemTB.id = setupTB.approvalType
                                         WHERE companyID={$companyID} ORDER BY approvalLevel")->result_array();

        $approvalEmp = $CI->db->query("SELECT approvalLevel, empTB.empID
                                       FROM srp_erp_leaveapprovalsetup AS setupTB
                                       JOIN srp_erp_leaveapprovalsetuphremployees AS empTB ON empTB.approvalSetupID = setupTB.approvalSetupID
                                       WHERE setupTB.companyID={$companyID} AND empTB.companyID={$companyID}")->result_array();

        if(!empty($approvalEmp)){
            $approvalEmp = array_group_by($approvalEmp, 'approvalLevel');
        }

        return [
            'appSystemValues' => $appSystemValues,
            'approvalLevel' => $approvalLevel,
            'approvalSetup' => $approvalSetup,
            'approvalEmp' => $approvalEmp
        ];
    }
}

if (!function_exists('get_hrDocuments')) {
    function get_hrDocuments()
    {
        $companyID = current_companyID();
        $CI =& get_instance();

        $hrDocuments = $CI->db->query("SELECT id, documentDescription, documentFile FROM srp_erp_hrdocuments
                                       WHERE companyID={$companyID} ")->result_array();

        return $hrDocuments;
    }
}

if (!function_exists('grade_drop')) {
    function grade_drop($isDrop=true)
    {
        $CI =& get_instance();
        $CI->db->select('gradeID,gradeDescription');
        $CI->db->from('srp_erp_employeegrade');
        $CI->db->where('srp_erp_employeegrade.companyID', $CI->common_data['company_data']['company_id']);
        $data = $CI->db->get()->result_array();

        $data_arr = [];
        if($isDrop == true){
            $data_arr = array('' => 'Select Grade');
        }

        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['gradeID'])] = trim($row['gradeDescription']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('fetch_degree')) {
    function fetch_degree($asResult = null)
    {
        $CI =& get_instance();
        $data = $CI->db->query("SELECT * FROM srp_erp_degreetype")->result_array();

        if ($asResult == null) {
            $data_arr = array('' => '');
            if (isset($data)) {
                foreach ($data as $row) {
                    $data_arr[trim($row['degreeTypeID'])] = trim($row['degreeDescription']);
                }
            }
            return $data_arr;
        } else {
            return $data;
        }

    }
}

if (!function_exists('sickLeave_setupData')) {
    function sickLeave_setupData()
    {
        $CI =& get_instance();
        $companyID = current_companyID();
        $isNonSalaryProcess = getPolicyValues('NSP', 'All');

        $result = $CI->db->query("SELECT leaveTypeID, description, typeConfirmed, formulaPayTB.*
                                  FROM srp_erp_leavetype AS typeMaster
                                  LEFT JOIN (
                                      SELECT id, formulaString, t1.salaryCategoryID, leaveTypeID AS payID, isNonPayroll, salaryDescription
                                      FROM srp_erp_sickleavesetup AS t1
                                      LEFT JOIN (
                                        SELECT salaryCategoryID, salaryDescription FROM srp_erp_pay_salarycategories WHERE companyID='{$companyID}'
                                      ) AS t2 ON t2.salaryCategoryID = t1.salaryCategoryID
                                      WHERE companyID='{$companyID}'
                                  ) AS formulaPayTB ON formulaPayTB.payID=typeMaster.leaveTypeID
                                  WHERE companyID = '{$companyID}' AND isSickLeave =1")->result_array();
        if(!empty($result)){
            $tempArr = array_group_by($result, 'leaveTypeID');
            $result = [];  $i = 0;
            foreach($tempArr as $leaveTypeID=>$row){

                $count = count($row);
                foreach($row as $key=>$data){
                    $isNonPayroll = $data['isNonPayroll'];
                    if((empty($isNonPayroll))){
                        $isNonPayroll = ($key == 0)? 'N' : 'Y';
                    }

                    $result[$i] = $data;
                    $result[$i]['setupID'] = $leaveTypeID.'|'.$isNonPayroll;
                    $result[$i]['isNonPayroll'] = $isNonPayroll;

                    if($count == 1 && $isNonSalaryProcess==1){
                        $i++;
                        $isNonPayroll = ($isNonPayroll == 'Y')? 'N' : 'Y';
                        $result[$i] = $data;
                        $result[$i]['setupID'] = $leaveTypeID.'|'.$isNonPayroll;
                        $result[$i]['salaryCategoryID'] = '';
                        $result[$i]['salaryDescription'] = '';
                        $result[$i]['formulaString'] = '';
                        $result[$i]['isNonPayroll'] = $isNonPayroll;
                    }

                    $i++;
                }
            }
        }

        return $result;
    }
}

if (!function_exists('formulaBuilder_to_sql_simple_conversion')) {
    function formulaBuilder_to_sql_simple_conversion($formula)
    {
        $salary_categories_arr = salary_categories(array('A', 'D'));
        $formulaText = '';
        $salaryCatID = array();
        $formulaDecode_arr = array();
        $operand_arr = operand_arr();

        $formula_arr = explode('|', $formula); // break the formula

        $n = 0;
        foreach ($formula_arr as $formula_row) {

            if (trim($formula_row) != '') {
                if (in_array($formula_row, $operand_arr)) { //validate is a operand
                    $formulaText .= $formula_row;
                    $formulaDecode_arr[] = $formula_row;
                } else {

                    $elementType = $formula_row[0];

                    if ($elementType == '_') {
                        /*** Number ***/
                        $numArr = explode('_', $formula_row);
                        $formulaText .= (is_numeric($numArr[1])) ? $numArr[1] : $numArr[0];
                        $formulaDecode_arr[] = (is_numeric($numArr[1])) ? $numArr[1] : $numArr[0];

                    } else if ($elementType == '#') {
                        /*** Salary category ***/
                        $catArr = explode('#', $formula_row);
                        $salaryCatID[$n]['ID'] = $catArr[1];

                        $keys = array_keys(array_column($salary_categories_arr, 'salaryCategoryID'), $catArr[1]);
                        $new_array = array_map(function ($k) use ($salary_categories_arr) {
                            return $salary_categories_arr[$k];
                        }, $keys);

                        $salaryDescription = (!empty($new_array[0])) ? trim($new_array[0]['salaryDescription']) : '';

                        $formulaText .= $salaryDescription;

                        $salaryDescription_arr = explode(' ', $salaryDescription);
                        $salaryDescription_arr = preg_replace("/[^a-zA-Z 0-9]+/", "", $salaryDescription_arr);
                        $salaryCatID[$n]['cat'] = implode('_', $salaryDescription_arr) . '_' . $n;
                        $formulaDecode_arr[] = 'SUM(' . $salaryCatID[$n]['cat'] . ')';

                        $n++;

                    }
                    else if ($elementType == '!') {
                        $subDetails = explode('!', $formula_row);
                        if ($subDetails[1] == 'FG') {
                            $formulaText .= 'Basic Pay';
                            $formulaDecode_arr[] = 'totFixPayment';

                        }
                        else if ($subDetails[1] == 'TW') {
                            $formulaText .= 'Total working days';
                            $formulaDecode_arr[] = 'totalWorkingDays';
                        }
                    }
                }
            }

        }

        $formulaDecode = implode(' ', $formulaDecode_arr);

        $select_str = '';
        $select_str2 = '';
        $whereInClause = '';
        $separator = '';

        foreach ($salaryCatID as $key1 => $row) {
            $select_str .= $separator . 'IF(salDec.salaryCategoryID=' . $row['ID'] . ', SUM(transactionAmount) , 0 ) AS ' . $row['cat'];
            $select_str2 .= $separator . 'SUM('.$row['cat'] .') AS ' . $row['cat'];
            $whereInClause .= $separator . ' ' . $row['ID'];
            $separator = ',';
        }

        return array(
            'formulaDecode' => $formulaDecode,
            'select_str' => $select_str,
            'select_str2' => $select_str2,
            'whereInClause' => $whereInClause,
        );
    }
}

if (!function_exists('payroll_group_master_action')) {
    function payroll_group_master_action($masterID, $description)
    {
        $fetch = "load_groupSetup(" . $masterID . ", '".$description."')";
        $status = '<span class="pull-right">';
        $status .= '<a onclick="' . $fetch . '"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil" ></span>';
        $status .= '&nbsp; | &nbsp; <a onclick="delete_groupSetup(' . $masterID . ')">';
        $status .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;" ></span>';
        $status .= '</span>';

        return $status;
    }
}

if (!function_exists('fetch_payroll_access_group')) {
    function fetch_payroll_access_group()
    {
        $CI =& get_instance();

        $CI->db->select('groupID,groupName');
        $CI->db->from('srp_erp_payrollgroups');
        $CI->db->where('companyID', current_companyID());
        $data = $CI->db->get()->result_array();

        $data_arr = [];
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['groupID'])] = trim($row['groupName']);

            }
        }

        return $data_arr;
    }
}

if (!function_exists('employee_list_by_segment')) {
    function employee_list_by_segment($dropDown=null, $status='', $isInitial=1)
    {
        $CI =& get_instance();
        $segmentID = $CI->input->post('segmentID');
        $isContractRpt = $CI->input->post('isContractRpt');

        $CI->db->select('EIdNo, ECode, Ename2');
        $CI->db->from('srp_employeesdetails empTB');
        $CI->db->join('srp_erp_segment', 'srp_erp_segment.segmentID=empTB.segmentID');
        $CI->db->where('Erp_companyID', current_companyID());



        if($isContractRpt == 1){
            $CI->db->join('srp_erp_empcontracthistory con', 'con.empID = empTB.EIdNo')
                  ->where('isCurrent', 1)->where('con.companyID', current_companyID());
        }

        if($status !== ''){
            $CI->db->where('isDischarged ', $status);
        }

        if(!empty($segmentID)){
            $CI->db->where_in('empTB.segmentID', $segmentID);
        }


        if($isInitial != 1 && empty($segmentID)){
            $data = [];
        }else{
            $data = $CI->db->get()->result_array();
        }


        if($dropDown == null){
            return $data;
        }

        $data_arr = [];
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['EIdNo'])] = trim($row['ECode']) .' - '.trim($row['Ename2']);

            }
        }

        return $data_arr;
    }
}

$checkList = [];
$count_payGroup_validation = 0;
if (!function_exists('payGroup_validation')) {
    function payGroup_validation($searchID,  $payGroupCategories)
    {
        $CI =& get_instance();
        $companyID = current_companyID();
        $returnData = null;

        global $count_payGroup_validation;
        global $checkList;
        $count_payGroup_validation++;

        if($count_payGroup_validation > 100) {
            //If the recursive worked more than 100 times than terminate the function
            return ['w', 'Group validation function get terminated.<br/>Please call for software support.'];
        }

        $result = $CI->db->query("SELECT payGroupID, payGroupCategories FROM srp_erp_paygroupformula
                        WHERE companyID = {$companyID}
                        AND payGroupID IN ({$payGroupCategories})
                        AND (
                             payGroupCategories LIKE '%,{$searchID},%' OR payGroupCategories='{$searchID}' OR payGroupCategories
                             LIKE '{$searchID},%' OR payGroupCategories LIKE '%,{$searchID}'
                        )")->result_array();

        if(!empty($result)){
            return ['e', 'exist'];
        }
        else{
            $result = $CI->db->query("SELECT payGroupID, payGroupCategories FROM srp_erp_paygroupformula
                        WHERE companyID = {$companyID}
                        AND payGroupID IN ({$payGroupCategories}) AND payGroupCategories IS NOT NULL")->result_array();

            if(!empty($result)){
                foreach ($result as $row){

                    if( !is_array($checkList) ){
                        $checkList = [];
                    }

                    if(!in_array( $row['payGroupID'], $checkList)){
                        $checkList[] = $row['payGroupID'];

                        $return = payGroup_validation($searchID, $row['payGroupCategories']);

                        if($return[0] == 'e'){
                            $returnData = $return;
                            break;
                        }
                    }

                }
            }
        }

        if(!empty($returnData)){
            return $returnData;
        }

        return ['s', ''];

    }
}

if (!function_exists('floors_fetch')) {
    function floors_fetch()
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
        $CI->db->select('floorID, floorDescription');
        $CI->db->from('srp_erp_pay_floormaster');
        $CI->db->where('companyID', current_companyID());
        $CI->db->where('isActive', 1);
        $data = $CI->db->get()->result_array();

        return $data;
    }
}

if (!function_exists('group_structure_type')) {
    function group_structure_type()
    {
        $CI =& get_instance();
        $CI->db->select('groupStructureTypeID,description');
        $CI->db->from('srp_erp_groupstructuretype');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Please Select');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['groupStructureTypeID'])] = trim($row['description']);

            }
        }
        return $data_arr;
    }
}

if (!function_exists('company_groupmaster_dropdown')) {
    function company_groupmaster_dropdown($parent)
    {
        $CI =& get_instance();
        $CI->db->select('companyGroupID,description');
        $CI->db->from('srp_erp_companygroupmaster');
        $CI->db->where('masterID', $parent);
        $data = $CI->db->get()->result_array();
        $data_arr = [];
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['companyGroupID'])] = trim($row['description']);

            }
        }
        return $data_arr;
    }
}

if (!function_exists('group_company_dropdown')) {
    function group_company_dropdown()
    {
        $CI =& get_instance();

        $data = $CI->db->query("SELECT company_id,CONCAT(company_code,' | ',company_name) as company FROM srp_erp_company LEFT JOIN srp_erp_companygroupdetails On company_id=srp_erp_companygroupdetails.companyID WHERE companyID IS NULL AND confirmedYN=1")->result_array();
        $data_arr = [];
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['company_id'])] = trim($row['company']);

            }
        }
        return $data_arr;
    }
}

if (!function_exists('final_settlement_data')) {
    function final_settlement_data($id)
    {
        $CI =& get_instance();

        $masterData = $CI->db->query("SELECT Ename2, ECode, ma.* FROM srp_erp_pay_finalsettlementmaster ma
                                     JOIN srp_employeesdetails ed ON ma.empID = ed.EIdNo WHERE masterID={$id}")->row_array();

        $data['masterData'] = $masterData;
        $empID = $masterData['empID'];

        $data['payroll'] = $CI->db->query("SELECT salaryDescription, SUM(amount) amount
                                FROM srp_erp_pay_salarydeclartion decl
                                JOIN srp_erp_pay_salarycategories cat ON cat.salaryCategoryID = decl.salaryCategoryID
                                WHERE employeeNo = '{$empID}' GROUP BY cat.salaryCategoryID")->result_array();

        $data['non_payroll'] = $CI->db->query("SELECT salaryDescription, SUM(amount) amount
                                FROM srp_erp_non_pay_salarydeclartion decl
                                JOIN srp_erp_pay_salarycategories cat ON cat.salaryCategoryID = decl.salaryCategoryID
                                WHERE employeeNo = '{$empID}' GROUP BY cat.salaryCategoryID")->result_array();
        return $data;
    }
}

if (!function_exists('fetch_final_settlement_items')) {
    function fetch_final_settlement_items()
    {
        $CI =& get_instance();
        $data = $CI->db->query("SELECT typeID, description, IF(isDedction = 1, 'D', 'A') iType
                                FROM srp_erp_pay_finalsettlementitems t ")->result_array();

        $data = array_group_by($data, 'iType');

        return $data;
    }
}
if (!function_exists('showLevelno')) {
    function showLevelno($levelNo)
    {
       if($levelNo>0){
           $data="<center>Level No - $levelNo </center>";
       }else{
           $data="<center>No Approval</center>";
       }

        return $data;
    }
}

if (!function_exists('final_settlement_action')) {
    function final_settlement_action($masterID, $confirmYN, $approvedYN, $createdUserID, $cnEmpID, $documentCode, $pvID)
    {

        $status = ' <span class="pull-right">';

        if ($approvedYN == 1 && $pvID > 0) {
            $status .= '<a onclick="documentPageView_modal(\'PV\', ' . $pvID . ')" title="Payment Voucher" rel="tooltip" ><i class="fa fa-file"></i></a>&nbsp; | &nbsp;';
        }

        if ($confirmYN != 1) {
            $status .= '<a onclick="load_details(' . $masterID . ')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil" ></span>';
        }else{
            $status .= '<a target="_blank" onclick="load_details(' . $masterID . ')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>';
        }

        $status .= '&nbsp; | &nbsp; <span title="Print" rel="tooltip" class="glyphicon glyphicon-print" onclick="print_document('.$masterID.', \''.$documentCode.'\')" style="color:#3c8dbc"></span>';
        if (($createdUserID == current_userID() or $cnEmpID == current_userID())  and $approvedYN == 0 and $confirmYN == 1) {
            $status .= ' &nbsp; | &nbsp; ';
            $status .= '<a onclick="referBackDeclaration(' . $masterID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:#d15b47;"></span></a>';
        }

        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('final_settlement_approval_action')) {
    function final_settlement_approval_action($masterID, $approvalLevelID, $docCode, $appYN, $type)
    {
        $status = ($type=='edit')?'<span class="pull-right">':'';
        if ($appYN == 1) {
            $str = ($type=='edit')?'<span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span>':$docCode;
            $status .= '<a onclick="load_approvalView(' . $masterID . ',' . $approvalLevelID . ',' . $appYN . ')">';
            $status .= $str.'</a> &nbsp; &nbsp;';
        }else{
            $str = ($type=='edit')?'<span title="Approve" rel="tooltip" class="glyphicon glyphicon-ok"></span>':$docCode;
            $status .= '<a onclick="load_approvalView(' . $masterID . ',' . $approvalLevelID . ',' . $appYN . ')">';
            $status .= $str.'</a> &nbsp; &nbsp;';
        }
        $status .= ($type=='code')?'</span>':'';

        return $status;
    }
}

if (!function_exists('finalSettlement_gl_config')) {
    function finalSettlement_gl_config()
    {
        $CI =& get_instance();
        $companyID = current_companyID();

        $data = $CI->db->query("SELECT typeTB.typeID, typeTB.description, isDedction, 
                                GLID, GLSecondaryCode, GLDescription 
                                FROM srp_erp_pay_finalsettlementitems typeTB
                                LEFT JOIN (
                                    SELECT typeID, conf.GLID, chAcc.GLSecondaryCode, chAcc.GLDescription 
                                    FROM srp_erp_pay_finalsettlement_gl_config conf
                                    JOIN srp_erp_chartofaccounts chAcc ON chAcc.GLAutoID = conf.GLID
                                    WHERE conf.companyID = {$companyID}                                     
                                ) glConf ON glConf.typeID = typeTB.typeID 
                                WHERE isGLAssignable = 1 AND typeTB.typeID NOT IN ( 15 ) ORDER BY typeTB.typeID")->result_array();

        return $data;
    }
}

if (!function_exists('action_gratuity')) {
    function action_gratuity($id, $des, $provisionGL,$expenseGL)
    {

        $url = site_url('Employee/formulaDecode/GRATUITY');
        $action = '<a onclick="formulaModalOpen(\'' . $des . '\', \'' . $id . '\', \''.$url.'\', \'gratuity-formula-'.$id.'\')"><span title="Formula"';
        $action .= ' rel="tooltip" class="fa fa-superscript"></span></a> &nbsp;  | &nbsp; ';
        $action .= '<a onclick="edit_gratuity_master(' . $id . ',\'' . $des . '\',\'' . $expenseGL . '\',\'' . $provisionGL . '\')">';
        $action .= '<span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
        $action .= ' &nbsp;  | &nbsp; <a onclick="load_gratuity_details(' . $id . ')">';
        $action .= '<span title="View" rel="tooltip" class="fa fa-fw fa-eye"></span></a>';
        $action .= ' &nbsp;  | &nbsp; <a onclick="delete_gratuity_master(' . $id . ')">';
        $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';

        return '<span class="pull-right">' . $action . '</span>';

    }
}

if (!function_exists('get_gratuity_slabDetails')) {
    function get_gratuity_slabDetails($masterID)
    {
        $CI =& get_instance();
        $companyID = current_companyID();
        $data = $CI->db->query("SELECT slb.id, slabTitle, startYear, endYear, form.formulaString 
                                FROM srp_erp_pay_gratuityslab slb
                                LEFT JOIN srp_erp_pay_gratuityformula form ON form.autoID = slb.id
                                AND form.masterType = 'GRATUITY-SLAB'
                                WHERE slb.companyID={$companyID} AND gratuityMasterID={$masterID}")->result_array();
        return $data;
    }
}

if (!function_exists('gratuity_drop')) {
    function gratuity_drop($isDrop=true)
    {
        $CI =& get_instance();
        $CI->db->select('gratuityID,gratuityDescription');
        $CI->db->from('srp_erp_pay_gratuitymaster');
        $CI->db->where('companyID', current_companyID());
        $data = $CI->db->get()->result_array();

        $data_arr = [];
        if($isDrop == true){
            $data_arr = array('' => 'Select Gratuity');
        }
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['gratuityID'])] = trim($row['gratuityDescription']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('employee_bank_drop')) {
    function employee_bank_drop($empID)
    {
        $CI =& get_instance();
        $data =  $CI->db->query("SELECT bnk.bankID, bankName, accountNo, accountHolderName, acc.id,
                                 acc.isActive, bnk.bankSwiftCode, branchName, brn.branchID
                                 FROM srp_erp_pay_salaryaccounts AS acc
                                 JOIN srp_erp_pay_bankmaster AS bnk ON bnk.bankID=acc.bankID
                                 JOIN srp_erp_pay_bankbranches AS brn ON brn.branchID=acc.branchID
                                 WHERE employeeNo = {$empID}")->result_array();

        return $data;

        $data_arr = array('' => 'Select Employee Bank');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['id'])] = trim($row['bankName']).' | '.trim($row['branchName']).' | '.trim($row['accountNo']).' | '.trim($row['bankSwiftCode']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('emergency_contact_status')) {
    function emergency_contact_status($is, $status)
    {
        $checked = ($status == 1) ? 'checked' : '';
        $isDisable = ($status == 1) ? 'disabled' : '';
        $str = '<input type="checkbox" class="switch-chk" id="emergency_contact_status' . $is . '" onchange="change_emergency_contact_status(this, ' . $is . ')"';
        $str .= 'data-size="mini" data-on-text="Yes" data-handle-width="45" data-off-color="danger" ';
        $str .= 'data-on-color="success" data-off-text="No" data-label-width="0" ' . $checked . ' '.$isDisable.'>';
        return  $str;
    }
}

if (!function_exists('travel_frequency_drop')) {
    function travel_frequency_drop()
    {
        $companyID = current_companyID();
        $CI =& get_instance();
        $data =  $CI->db->query("SELECT travelFrequencyID, frequencyDescription
                                 FROM srp_erp_travelfrequency                               
                                 WHERE companyID = {$companyID}")->result_array();


        $data_arr = ['' => 'Select Frequency'];
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['travelFrequencyID'])] = trim($row['frequencyDescription']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('familyStatus_drop')) {
    function familyStatus_drop()
    {
        $CI =& get_instance();
        $CI->db->select('familyStatusID,description');
        $CI->db->from('srp_erp_familystatus');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Employment Status');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['familyStatusID'])] = trim($row['description']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('emp_document_sys_type_drop')) {
    function emp_document_sys_type_drop()
    {
        $CI =& get_instance();
        $CI->db->select('id,description');
        $CI->db->from('srp_erp_system_document_types');
        $CI->db->order_by('description', 'ASC');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Type');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['id'])] = trim($row['description']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('action_emp_docs')) {
    function action_emp_docs($id, $file, $incCount, $docDesID, $docDescription)
    {
        $CI =& get_instance();
        $action = '';
        if($incCount > 0){
            $action .= '<button type="button" style="padding: 0px 2px;" onclick="load_inactiveDocs(' . $docDesID . ', \''.$docDescription.'\')">';
            $action .= '<span title="History" rel="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
            $action .= '</button>';
        }

        if(!empty($id)) {

            $action .= ($action == '')? '': '&nbsp; | &nbsp; ';
            $action .= '<a onclick="editDocument(' . $id . ', this)"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
            $action .= '&nbsp; | &nbsp; <span title="Upload" rel="tooltip" onclick="documentUpload(' . $id . ', \''.$docDescription.'\')"><i class="fa fa-upload"></i></span>';

            if (!empty($file)) {
                /*$file = base_url() . 'documents/users/' . $file;
                $downLink = generate_encrypt_link_only($file);*/
                $downLink = $CI->s3->getMyAuthenticatedURL($file, 3600);
                $action .= '&nbsp; | &nbsp; <span title="Download" rel="tooltip" onclick="downloadDoc(\'' . $downLink . '\')"><i class="fa fa-download"></i></span>';
            }
            $action .= '&nbsp; | &nbsp; <a onclick="removeDocument(' . $id . ', \'act\')">';
            $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';
        }
        return '<span class="pull-right">' . $action . '</span>';
    }
}

if (!function_exists('action_emp_docs_history')) {
    function action_emp_docs_history($id, $file)
    {
        /*$file = base_url() . 'documents/users/' . $file;
        $downLink = generate_encrypt_link_only($file);*/
        $CI =& get_instance();
        $downLink = $CI->s3->getMyAuthenticatedURL($file, 3600);
        $action = '<span title="Download" rel="tooltip" onclick="downloadDoc(\'' . $downLink . '\')"><i class="fa fa-download"></i></span>';
        $action .= '&nbsp; | &nbsp; <a onclick="removeDocument(' . $id . ', \'his\')">';
        $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:#d15b47;"></span></a>';

        return '<span class="pull-right">' . $action . '</span>';
    }
}

if (!function_exists('generate_s3_link')) {
    function generate_s3_link($file, $desc)
    {
        $CI =& get_instance();
        $downLink = $CI->s3->getMyAuthenticatedURL($file, 3600);
        $action = '<a rel="tooltip" href="' . $downLink . '" target="_blank">'.$desc.'</span>';
        return '<span class="">' . $action . '</span>';
    }
}

if (!function_exists('emp_docs_status')) {
    function emp_docs_status($id, $incCount)
    {

        if(!empty($id)) {
             $str = '<span class="label label-success">&nbsp;</span>';
        }
        else{
            $color = ($incCount > 0)? 'warning': 'danger';
            $str = '<span class="label label-'.$color.'">&nbsp;</span>';
        }
        return '<div style="text-align: center">'.$str.'</div>';
    }
}

if (!function_exists('drop_down_sso_and_payee')) {
    function drop_down_sso_and_payee($returnType=0)
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
        $companyID = current_companyID();

        $data = $CI->db->query("SELECT ssoTB.socialInsuranceID AS autoID, Description AS des, 'S' AS type
                                FROM srp_erp_socialinsurancemaster ssoTB WHERE companyID = '{$companyID}'
                                UNION 
                                SELECT payeeMasterID AS autoID, description AS des, 'P' AS type
                                FROM srp_erp_payeemaster WHERE srp_erp_payeemaster.companyID ='{$companyID}'")->result_array();


        if($returnType == 1){
            return $data;
        }

        $data_arr = ['' => $CI->lang->line('common_select_type')];
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['type']).'-'.trim($row['autoID'])] = trim($row['des']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('emp_document_sys_sub_type_data')) {
    function emp_document_sys_sub_type_data()
    {
        $CI =& get_instance();
        $CI->db->select('sub_id,system_type_id,description');
        $CI->db->from('srp_erp_system_document_sub_types');
        $CI->db->where('companyID', current_companyID());
        $CI->db->order_by('system_type_id', 'ASC');
        $CI->db->order_by('description', 'ASC');
        return $CI->db->get()->result_array();
    }
}

if (!function_exists('emp_docs_full_description')) {
    function emp_docs_full_description($description, $sub_typesDes)
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
        $lang_document = $CI->lang->line('common_document');
        $lang_type = $CI->lang->line('common_type');

        $str = "<b>{$lang_document}</b> : $description";

        if($sub_typesDes != ''){
            $str .= "<br/><b>{$lang_type}</b> : $sub_typesDes";
        }

        return $str;
    }
}

if (!function_exists('commission_scheme_drop')) {
    function commission_scheme_drop()
    {
        $CI =& get_instance();
        $CI->db->select('id,description');
        $CI->db->from('srp_erp_pay_commissionscheme');
        $CI->db->where('companyID', current_companyID());
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select scheme');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['id'])] = trim($row['description']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('salary_advance_action')) {
    function salary_advance_action($masterID, $confirmYN, $approvedYN, $createdUserID, $cnEmpID, $documentCode)
    {

        $status = ' <span class="pull-right">';


        if ($confirmYN != 1) {
            $status .= '<a onclick="load_details(' . $masterID . ')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil" ></span>';
        }else{
            $status .= '<a target="_blank" onclick="view_modal(' . $masterID . ')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>';
        }

        $status .= '&nbsp; | &nbsp; <span title="Print" rel="tooltip" class="glyphicon glyphicon-print" onclick="print_document('.$masterID.', \''.$documentCode.'\')" style="color:#3c8dbc"></span>';
        if (($createdUserID == current_userID() or $cnEmpID == current_userID())  and $approvedYN == 0 and $confirmYN == 1) {
            $status .= ' &nbsp; | &nbsp; ';
            $status .= '<a onclick="referBack_document(' . $masterID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:#d15b47;"></span></a>';
        }

        if ($confirmYN != 1) {
            $status .= '&nbsp; | &nbsp; <a onclick="delete_document(' . $masterID . ')"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash delete-icon"></span>';
        }

        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('salary_advance_approval_action')) {
    function salary_advance_approval_action($masterID, $approvalLevelID, $docCode, $appYN, $type)
    {
        $status = ($type=='edit')?'<span class="pull-right">':'';
        if ($appYN == 1) {
            $str = ($type=='edit')?'<span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span>':$docCode;
            $status .= '<a onclick="load_approvalView(' . $masterID . ',' . $approvalLevelID . ',' . $appYN . ')">';
            $status .= $str.'</a> &nbsp; &nbsp;';
        }else{
            $str = ($type=='edit')?'<span title="Approve" rel="tooltip" class="glyphicon glyphicon-ok"></span>':$docCode;
            $status .= '<a onclick="load_approvalView(' . $masterID . ',' . $approvalLevelID . ',' . $appYN . ')">';
            $status .= $str.'</a> &nbsp; &nbsp;';
        }
        $status .= ($type=='code')?'</span>':'';

        return $status;
    }
}

if (!function_exists('leave_encashment_action')) {
    function leave_encashment_action($masterID, $document_type, $confirmYN, $approvedYN, $createdUserID, $cnEmpID, $documentCode, $pvID)
    {

        $status = ' <span class="pull-right">';

        if($approvedYN == 1 && $pvID > 0){
            $status .= '<a onclick="documentPageView_modal(\'PV\', ' . $pvID . ')"><span title="Payment Voucher" rel="tooltip"><i class="fa fa-file"></i></span> &nbsp; | &nbsp; ';
        }

        if ($confirmYN != 1) {
            $status .= '<a onclick="load_details(' . $masterID . ', '.$document_type.')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil" ></span>';
        }else{
            $status .= '<a target="_blank" onclick="view_modal(' . $masterID . ', '.$document_type.')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>';
        }

        $status .= '&nbsp; | &nbsp; <span title="Print" rel="tooltip" class="glyphicon glyphicon-print" onclick="print_document('.$masterID.', \''.$documentCode.'\')" style="color:#3c8dbc"></span>';
        if (($createdUserID == current_userID() or $cnEmpID == current_userID())  and $approvedYN == 0 and $confirmYN == 1) {
            $status .= ' &nbsp; | &nbsp; ';
            $status .= '<a onclick="referBack_document(' . $masterID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:#d15b47;"></span></a>';
        }

        if ($confirmYN != 1) {
            $status .= '&nbsp; | &nbsp; <a onclick="delete_document(' . $masterID . ')"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash delete-icon"></span>';
        }

        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('fetch_emp_asset_category_drop')) {
    function fetch_emp_asset_category_drop()
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);

        $CI->db->select('id,assetType')->from('srp_erp_pay_assettype')->where('companyID', current_companyID());
        $data = $CI->db->order_by('assetType')->get()->result_array();

        $data_arr = ['' => $CI->lang->line('common_select_a_option')];
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['id'])] = trim($row['assetType']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('fetch_emp_asset_condition_drop')) {
    function fetch_emp_asset_condition_drop()
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);

        $CI->db->select('id,description')->from('srp_erp_pay_assetcondition');
        $data = $CI->db->order_by('description')->get()->result_array();

        $data_arr = ['' => $CI->lang->line('common_select_a_option')];
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['id'])] = trim($row['description']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('my_employee_drop')) {
    function my_employee_drop()
    {
        $CI =& get_instance();
        $company_id = current_companyID();
        $emp_id = current_userID();

        $arr = $CI->db->query("SELECT EIdNo AS emp_id, ECode, empTB.Ename2 AS emp_name
                        FROM srp_employeesdetails AS empTB
                        JOIN (
                           SELECT managerID, empID FROM srp_erp_employeemanagers
                           WHERE active = 1 AND managerID = {$emp_id} AND companyID = {$company_id}
                        ) AS man_tb ON man_tb.empID = empTB.EIdNo
                        WHERE empTB.Erp_companyID = {$company_id} AND isDischarged = 0")->result_array();
        return $arr;
    }
}

if (!function_exists('tibian_employeeType')) {
    function tibian_employeeType()
    {
        $CI =& get_instance();
        $CI->db->select("id,CONCAT(prefix, ' - ', description) AS description");
        $data = $CI->db->from('srp_erp_tibian_employeetype')->get()->result_array();

        $data_arr = array('' => 'Select type');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['id'])] = trim($row['description']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('hr_letter_types')) {
    function hr_letter_types()
    {
        $CI =& get_instance();
        $CI->db->select("id, letter_type");
        $data = $CI->db->from('srp_erp_hr_letters')->get()->result_array();

        $data_arr = array('' => 'Select type');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['id'])] = trim($row['letter_type']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('hr_letter_request_action')) {
    function hr_letter_request_action($masterID, $confirmYN, $approvedYN, $createdUserID, $cnEmpID, $documentCode)
    {

        $status = '<span class="pull-right">';
        $status .= '<a onclick=\'attachment_modal(' . $masterID . ',"Document Request","HDR",'.$confirmYN.');\'><span title="Attachment" rel="tooltip" ';
        $status .= 'class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';
        $status .= '<a target="_blank" onclick="view_modal(' . $masterID . ')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>';

        if ($confirmYN != 1) {
            $status .= ' &nbsp; | &nbsp; ';
            $status .= '<a onclick="load_details(' . $masterID . ')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil" ></span>';
        }

        //$status .= '&nbsp; | &nbsp; <span title="Print" rel="tooltip" class="glyphicon glyphicon-print" onclick="print_document('.$masterID.', \''.$documentCode.'\')" style="color:#3c8dbc"></span>';
        if (($createdUserID == current_userID() or $cnEmpID == current_userID())  and $approvedYN == 0 and $confirmYN == 1) {
            $status .= ' &nbsp; | &nbsp; ';
            $status .= '<a onclick="referBack_document(' . $masterID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:#d15b47;"></span></a>';
        }

        if ($confirmYN != 1) {
            $status .= '&nbsp; | &nbsp; <a onclick="delete_document(' . $masterID . ')"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash delete-icon"></span>';
        }

        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('payee_emp_type_drop')) {
    function payee_emp_type_drop()
    {
        $data = get_instance()->db->query("SELECT * FROM srp_erp_payee_emptype")->result_array();
        $data_arr = array('' => 'Select type');
        if (!empty($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['id'])] = trim($row['description']);
            }
        }
        return $data_arr;
    }
}
if (!function_exists('sponser_drop')) {
    function sponser_drop()
    {
        $companyID = current_companyID();
        $CI =& get_instance();
        $data =  $CI->db->query("SELECT sponsorID, sponsorName
                                 FROM srp_erp_sponsormaster                               
                                 WHERE companyID = {$companyID}")->result_array();


        $data_arr = ['' => 'Select Sponsor'];
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['sponsorID'])] = trim($row['sponsorName']);
            }
        }
        return $data_arr;
    }
}

/**
 * Created by PhpStorm.
 * User: NSK
 * Date: 5/16/2016
 * Time: 12:51 PM
 */

