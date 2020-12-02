<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('fetch_cost_account')) {
    function fetch_cost_account()
    {

        $CI =& get_instance();
        $companyID = $CI->common_data['company_data']['company_id'];
        $data = $CI->db->query("SELECT * FROM srp_erp_chartofaccounts WHERE masterAccountYN = 0 AND companyID = '{$companyID}' AND masterAutoID IN ( SELECT GLAutoID FROM srp_erp_chartofaccounts WHERE accountCategoryTypeID = 4 AND masterAccountYN = 1 AND companyID = '{$companyID}')")->result_array();
        $data_arr = array('' => 'Select GL Code');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['GLAutoID'])] = trim($row['systemAccountCode']) . ' | ' . trim($row['GLSecondaryCode']) . ' | ' . trim($row['GLDescription']) . ' | ' . trim($row['subCategory']);
            }
        }
        return $data_arr;
    }
}
if (!function_exists('fetch_post_to_gl')) {
    function fetch_post_to_gl()
    {
        $CI =& get_instance();
        $company_id = $CI->common_data['company_data']['company_id'];
        $data = $CI->db->query("SELECT * FROM srp_erp_chartofaccounts WHERE  masterAccountYN = 0 AND companyID='{$company_id}'")->result_array();

        $data_arr = array('' => 'Select Cost Account');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['GLAutoID'])] = trim($row['systemAccountCode']) . ' | ' . trim($row['GLSecondaryCode']) . ' | ' . trim($row['GLDescription']) . ' | ' . trim($row['subCategory']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('fetch_gl_code')) {
    function fetch_gl_code($codes = null)
    {

        $CI =& get_instance();
        $company_code = $CI->common_data['company_data']['company_code'];
        $CI->db->SELECT("GLAutoID,systemAccountCode,GLSecondaryCode,GLDescription,systemAccountCode,subCategory");
        $CI->db->from('srp_erp_chartofaccounts');
        if ($codes) {
            foreach ($codes as $key => $code) {
                $CI->db->where($key, $code);
            }
        }
        $CI->db->where('masterAccountYN', 0);
        $CI->db->where('companyCode', $company_code);
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


if (!function_exists('ast_action_approval')) { /*get po action list*/
    function ast_action_approval($poID, $Level, $approved, $ApprovedID, $isRejected)
    {
        $status = '<span class="pull-right">';
        if ($approved == 0) {
            $status .= '<a onclick=\'fetch_approval("' . $poID . '","' . $ApprovedID . '","' . $Level . '","' . $approved . '"); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>&nbsp;&nbsp;';
        } else {
            $status .= '<a onclick=\'fetch_approval("' . $poID . '","' . $ApprovedID . '","' . $Level . '","' . $approved . '"); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;';
        }
        /*$status .= '<a target="_blank" href="' . site_url('AssetManagement/load_asset_conformation/') . '/' . $poID . '" ><span class="glyphicon glyphicon-print"></span></a>';*/
        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('dep_action_approval')) { /*get po action list*/
    function dep_action_approval($poID, $Level, $approved, $ApprovedID, $isRejected)
    {
        $status = '<span class="pull-right">';
        if ($approved == 0) {
            $status .= '<a onclick=\'fetch_approval("' . $poID . '","' . $ApprovedID . '","' . $Level . '","' . $approved . '"); \'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>&nbsp;&nbsp;';
        } else {
            $status .= '<a onclick=\'fetch_approval("' . $poID . '","' . $ApprovedID . '","' . $Level . '","' . $approved . '"); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;';
        }
        /*$status .= '<a target="_blank" href="' . site_url('AssetManagement/load_asset_conformation/') . '/' . $poID . '" ><span class="glyphicon glyphicon-print"></span></a>';*/
        $status .= '</span>';
        return $status;
    }
}


if (!function_exists('dep_calculate')) {
    function dep_calculate($amount, $per)
    {
        $depAmount = (($amount * $per) / 100) / 12;
        return $depAmount;
    }
}

if (!function_exists('fa_asset_category')) {
    function fa_asset_category($id = null, $empty = true)
    {
        $CI =& get_instance();
        $company_code = $CI->common_data['company_data']['company_code'];
        $companyId = $CI->common_data['company_data']['company_id'];

        $itemCategoryID = $CI->db->query("SELECT itemCategoryID FROM `srp_erp_itemcategory` WHERE `categoryTypeID` = '3' AND `companyCode` = '{$company_code}'")->row_array();


        $CI->db->SELECT("itemCategoryID,description");
        $CI->db->from('srp_erp_itemcategory');
        $CI->db->where('companyID', $companyId);
        $CI->db->where('masterID', $itemCategoryID['itemCategoryID']);

        $data = $CI->db->get()->result_array();
        if ($empty) {
            $data_arr = array('' => '');
        } else {
            $data_arr = array();
        }


        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['itemCategoryID'])] = trim($row['description']);
            }
        }

        return $data_arr;
    }
}


if (!function_exists('fa_asset_category_sub')) { /*get po action list*/
    function fa_asset_category_sub($id = null)
    {
        $CI =& get_instance();
        $companyId = $CI->common_data['company_data']['company_id'];


        $CI->db->SELECT("itemCategoryID,description");
        $CI->db->from('srp_erp_itemcategory');
        $CI->db->where('companyID', $companyId);
        $CI->db->where('masterID', $id);

        $data = $CI->db->get()->result_array();
        $data_arr = array();
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['itemCategoryID'])] = trim($row['description']);
            }
        }

        return $data_arr;
    }
}

if (!function_exists('edit_asset')) { /*get po action list*/
    function edit_asset($faId, $confirmed, $approved, $isFromGRV,$createdUser,$confirmedByEmp)
    {
        $status = '<span class="pull-right">';
        if (($createdUser == current_userID() || $confirmedByEmp == current_userID()) and $approved == 0 and $confirmed == 1) {
            $status .= '<a onclick="referbackAsset(' . $faId . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
        }


        $status .= '<a onclick="getAssetDetails(' . $faId . ')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;';
        if ((is_null($isFromGRV) || empty($isFromGRV) || $isFromGRV == 0) && ($confirmed == 0 || is_null($confirmed) || empty($confirmed))) {
            $status .= '<a target="" style="color: red;" onclick="deleteAsset(' . $faId . ')"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash"></span></a>';
            $status .= "</span>";
        }
        return $status;
    }
}

if (!function_exists('view_depreciation')) { /*get po action list*/
    function view_depreciation($depMasterAutoID, $confirmedYN, $approvedYN, $createdUserID,$confirmedByEmp)
    {
        $status = '<span class="pull-right">';
        $CI =& get_instance();
        $currentUser = current_userID();

        if (($createdUserID == trim($CI->session->userdata("empID")) || $confirmedByEmp == trim($CI->session->userdata("empID"))) and $approvedYN == 0 and $confirmedYN == 1 ) {
            $status .= '<a onclick="referback_bankrec(' . $depMasterAutoID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>';
        }
        $status .= '&nbsp;&nbsp;&nbsp;<a target="_blank" onclick="documentPageView_modal(\'FAD\',\'' . $depMasterAutoID . '\',\'month\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></a>';

        if (($confirmedYN == 0 && $approvedYN == 0) || ($confirmedYN == 2 && $approvedYN == 0) || ($confirmedYN == 3 && $approvedYN == 0)) {
            $status .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick=\'getAssetDepDetail_editView("' . $depMasterAutoID . '","' . $confirmedYN . '");\'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
        }

        if (is_null($confirmedYN) || empty($confirmedYN) || $confirmedYN == 0 || $confirmedYN == 2 || $confirmedYN == 3 ) {
            $status .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a style="color:red;" onclick=\'deleteAssetDep("' . $depMasterAutoID . '","' . $confirmedYN . '");\'><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash"></span></a>';
        }

        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('view_depreciation_adhoc')) { /*get po action list*/
    function view_depreciation_adhoc($depMasterAutoID, $confirmedYN, $approvedYN, $createdUserID,$confirmedByEmp)
    {
        $status = '<span class="pull-right">';
        $CI =& get_instance();
        $currentUser = current_userID();
        if (($createdUserID == trim($CI->session->userdata("empID")) || $confirmedByEmp == trim($CI->session->userdata("empID"))) and $approvedYN == 0 and $confirmedYN == 1 ) {
            $status .= '<a onclick="referback_adhoc(' . $depMasterAutoID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>';
        }
        $status .= '&nbsp;&nbsp;&nbsp;<a target="_blank" onclick="documentPageView_modal(\'FAD\',\'' . $depMasterAutoID . '\',\'adhoc\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></a>';

        if (($confirmedYN == 0 && $approvedYN == 0) || ($confirmedYN == 2 && $approvedYN == 0)) {
            $status .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick=\'getAssetDepDetail_editView_adhoc("' . $depMasterAutoID . '","' . $confirmedYN . '");\'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
        }

        if (is_null($confirmedYN) || empty($confirmedYN) || $confirmedYN == 0) {
            $status .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a style="color:red;" onclick=\'deleteAssetDep("' . $depMasterAutoID . '","' . $confirmedYN . '");\'><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash"></span></a>';
        }

        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('add_asset_to_diposal')) {
    function add_asset_to_diposal($faID)
    {
        $status = '<span class="text-center">';
        //$status .= '<a onclick=\'getAssetDepDetail("' . $faID . '");\'><span class="glyphicon glyphicon-plus"></span></a>&nbsp;&nbsp;';
        $status .= '<a onclick=\'add_to_disposal("' . $faID . '");\'><span class="glyphicon glyphicon-plus">';
        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('remove_asset_to_diposal')) {
    function remove_asset_to_diposal($assetDisposalDetailAutoID, $faId)
    {
        $status = '<span class="pull-right">';
        //$status .= '<a onclick=\'getAssetDepDetail("' . $faID . '");\'><span class="glyphicon glyphicon-plus"></span></a>&nbsp;&nbsp;';
        $status .= '<a style="color:red;" class="remove_from_disposal" onclick=\'remove_from_disposal("' . $assetDisposalDetailAutoID . '","' . $faId . '");\'><span class="glyphicon glyphicon-trash">';
        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('delete_attachment')) { /*get po action list*/
    function delete_attachment($attachmentID)
    {
        $status = '<span class="pull-right">';
        $status .= '<a style="color:red;" href="#" onclick=\'delete_attachment("' . $attachmentID . '");\'><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash"></span></a>&nbsp;&nbsp|&nbsp;&nbsp;<a onclick=\'edit_attachment("' . $attachmentID . '");\'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>;';
        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('disposal_selected_asset')) {
    function disposal_selected_asset($detailId)
    {
        $status = '<span class="pull-right">';
        $status .= '<a style="color:red;" href="#" onclick=\'delete_selected_asset("' . $detailId . '");\'><span class="glyphicon glyphicon-trash"></span></a>&nbsp;&nbsp;';
        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('get_finance_category')) { /*get po action list*/
    function get_finance_category($params = array())
    {
        $CI =& get_instance();
        $company_code = $CI->common_data['company_data']['company_code'];
        $CI->db->select("srp_erp_fa_financecategory.faFinanceCatID, srp_erp_fa_financecategory.financeCatDescription");
        $CI->db->from('srp_erp_fa_financecategory');

        foreach ($params as $key => $value) {
            $CI->db->where($key, $value);
        }

        $data = $CI->db->get()->result_array();
        $data_arr = array();
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['faFinanceCatID'])] = trim($row['financeCatDescription']);
            }
        }

        return $data_arr;
    }
}

if (!function_exists('fa_categories')) { /*get po action list*/
    function fa_categories($params = array())
    {
        $CI =& get_instance();
        $companyID = current_companyID();
        $CI->db->select("srp_erp_fa_category.faCatID, srp_erp_fa_category.catDescription, srp_erp_fa_category.companyID");
        $CI->db->from('srp_erp_fa_category');

        foreach ($params as $key => $value) {
            $CI->db->where($key, $value);
        }

        $CI->db->where('companyID', $companyID);

        $data = $CI->db->get()->result_array();
        $data_arr = array();
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['faCatID'])] = trim($row['catDescription']);
            }
        }

        return $data_arr;
    }
}

if (!function_exists('group_to')) { /*get po action list*/
    function group_to($faCatID)
    {
        $CI =& get_instance();
        $companyID = current_companyID();
        $companyCode = current_companyCode();

        $data = $CI->db->query("SELECT srp_erp_fa_asset_master.faID, srp_erp_fa_asset_master.faCode, srp_erp_fa_asset_master.assetDescription FROM srp_erp_fa_asset_master WHERE srp_erp_fa_asset_master.faCatID = '{$faCatID}' AND srp_erp_fa_asset_master.companyCode = '{$companyCode}' ")->result_array();

        $data_arr = array('' => '');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['faID'])] = trim($row['faCode']) . '-' . $row['assetDescription'];
            }
        }

        return $data_arr;
    }
}

if (!function_exists('postoGL')) { /*get po action list*/
    function postoGL()
    {
        $CI =& get_instance();
        $companyID = current_companyID();
        $companyCode = current_companyCode();

        $data = $CI->db->query("SELECT GLAutoID, systemAccountCode, GLDescription FROM `srp_erp_companycontrolaccounts` WHERE `companyCode` = '{$companyCode}' AND `controlAccountType` = 'ACA'")->result_array();

        $data_arr = array('' => '');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['GLAutoID'])] = trim($row['systemAccountCode']) . '-' . $row['GLDescription'];
            }
        }

        return $data_arr;
    }
}

if (!function_exists('financeYearPeriod')) {
    function financeYearPeriod($companyFinanceYearID)
    {
        $CI =& get_instance();
        $CI->db->select('companyFinanceYearID,dateFrom,dateTo');
        $CI->db->from('srp_erp_companyfinanceperiod');
        $CI->db->where('companyFinanceYearID', $companyFinanceYearID);
        $CI->db->where('isActive', 1);
        $CI->db->where('isClosed', 0);

        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Financial Period');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['dateFrom']) . '|' . $row['dateTo']] = trim($row['dateFrom']) . ' - ' . trim($row['dateTo']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('disposal_action')) {
    function disposal_action($assetdisposalMasterAutoID, $confirmedYN, $approvedYN, $createdUserID,$confirmedByEmpID)
    {
        $status = '<span class="pull-right">';
        $CI =& get_instance();
        $status .= '<a onclick=\'attachment_modal(' . $assetdisposalMasterAutoID . ',"Asset Disposal","ADSP",' . $confirmedYN . ');\'><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';


        if (($createdUserID == trim($CI->session->userdata("empID")) || $confirmedByEmpID == trim($CI->session->userdata("empID"))) and $approvedYN == 0 and $confirmedYN == 1) {
        /*if ($approvedYN == 0 && $confirmedYN == 1) {*/
            $status .= '<a onclick="referbackDisposal(' . $assetdisposalMasterAutoID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;|&nbsp;';
        }

        $status .= '&nbsp;<a target="_blank" onclick="documentPageView_modal(\'ADSP\',\'' . $assetdisposalMasterAutoID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        if (($confirmedYN != 1)) {
            $status .= '<a class="" href="#" onclick="getAssetDisposalDetails(' . $assetdisposalMasterAutoID . ')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
        }
        if (($confirmedYN != 1)) {
            $status .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a style="color:red;" onclick=\'deleteDisposal("' . $assetdisposalMasterAutoID . '","' . $confirmedYN . '");\'><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash"></span></a>&nbsp;&nbsp;';
        }

        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('depreicationType')) {
    function depreicationType($type)
    {
        if ($type == 0) {
            return "<span>Monthly Depreciation</span>";
        } else {
            return "<span>Adhoc Depreciation</span>";
        }
    }
}

if (!function_exists('load_location_master')) { /*get po action list*/
    function load_location_master($masterID)
    {

        $status = '<span class="pull-right">';
        $status .= '<a onclick="editLocation()"><span class="glyphicon glyphicon-pencil" ></span>';

        $status .= '</span>';

        return $status;
    }
}

if (!function_exists('action_asset_location')) {
    function action_asset_location($locationID, $locationName)
    {
        $action = '<a onclick="edit_location(' . $locationID . ',\'' . $locationName . '\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
        $action .= '&nbsp;&nbsp; | &nbsp;&nbsp;<a onclick="delete_location(' . $locationID . ')">';
        $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';

        return '<span class="pull-right">' . $action . '</span>';

    }
}
if (!function_exists('action_asset_location_code')) {
    function action_asset_location_code($locationID, $locationName,$locationCode,$loccodename)
    {
        $action = '<a onclick="edit_location(' . $locationID . ',\'' . $locationName . '\',\'' . $locationCode . '\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
        $action .= '&nbsp;|&nbsp;&nbsp;<a onclick="add_location_emp(' . $locationID . ',\'' . $locationName . '\',\'' . $locationCode . '\',\'' . $loccodename . '\')"><span title="Add User" rel="tooltip" class="glyphicon glyphicon-user"></span></a>  &nbsp; | &nbsp;<a onclick="delete_location(' . $locationID . ')">';

        $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';

        return '<span class="pull-right">' . $action . '</span>';

    }
}
