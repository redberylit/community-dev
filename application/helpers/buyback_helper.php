<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*Load all countries for select2*/
if (!function_exists('load_all_countries')) {
    function load_all_countries($status = true)/*Load all Supplier*/
    {
        $CI =& get_instance();
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

/*Load all buyback location for select2*/
if (!function_exists('load_all_locations')) {
    function load_all_locations($status = true)/*Load all Supplier*/
    {
        $CI =& get_instance();
        $CI->db->SELECT("locationID,description");
        $CI->db->FROM('srp_erp_buyback_locations');
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $CI->db->where('masterID', 0);
        $location = $CI->db->get()->result_array();
        if($status == true)
        {
            $location_arr = array('' => 'Select Area');
        }
        if (isset($location)) {
            foreach ($location as $row) {
                $location_arr[trim($row['locationID'])] = trim($row['description']);
            }
        }
        return $location_arr;
    }
}

/*Load all Customer*/
if (!function_exists('all_customer_drop')) {
    function all_customer_drop()
    {
        $CI =& get_instance();
        $CI->db->select("customerAutoID,customerName,customerSystemCode,customerCountry");
        $CI->db->from('srp_erp_customermaster');
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $customer = $CI->db->get()->result_array();
        $customer_arr = array('' => 'Select Customer');
        if (isset($customer)) {
            foreach ($customer as $row) {
                $customer_arr[trim($row['customerAutoID'])] = (trim($row['customerSystemCode']) ? trim($row['customerSystemCode']) . ' | ' : '') . trim($row['customerName']) . (trim($row['customerCountry']) ? ' | ' . trim($row['customerCountry']) : '');
            }
        }

        return $customer_arr;
    }
}

if (!function_exists('all_delivery_location_drop')) {
    function all_delivery_location_drop($status = TRUE)
    {
        $CI =& get_instance();
        $CI->db->select('wareHouseAutoID,wareHouseLocation,wareHouseDescription,wareHouseCode');
        $CI->db->from('srp_erp_warehousemaster');
        $CI->db->where('companyCode', $CI->common_data['company_data']['company_code']);
        $data = $CI->db->get()->result_array();
        if ($status) {
            $data_arr = array('' => 'Select Warehouse');
        } else {
            $data_arr = '';
        }
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['wareHouseAutoID'])] = trim($row['wareHouseCode']) . ' | ' . trim($row['wareHouseLocation']) . ' | ' . trim($row['wareHouseDescription']);
            }
        }

        return $data_arr;
    }
}

/*Load all buyback farms for select2*/
if (!function_exists('load_all_farms')) {
    function load_all_farms($status = true)
    {
        $CI =& get_instance();
        $CI->db->SELECT("farmID,description,farmSystemCode");
        $CI->db->FROM('srp_erp_buyback_farmmaster');
        $CI->db->where('companyID', current_companyID());
        $CI->db->where('isActive', 1);
        $farm = $CI->db->get()->result_array();

        if($status==false)
        {
            if (isset($farm)) {
                foreach ($farm as $row) {
                    $farm_arr[trim($row['farmID'])] = trim($row['farmSystemCode']) . " | " . trim($row['description']);
                }
            }
        }else
        {
            $farm_arr = array('' => 'Select Farm');
            if (isset($farm)) {
                foreach ($farm as $row) {
                    $farm_arr[trim($row['farmID'])] = trim($row['farmSystemCode']) . " | " . trim($row['description']);
                }
            }
        }

        return $farm_arr;
    }
}

/*Load all buyback farms for select2*/
if (!function_exists('load_all_farms_view')) {
    function load_all_farms_view()
    {
        $CI =& get_instance();
        $CI->db->SELECT("farmID,description,farmSystemCode");
        $CI->db->FROM('srp_erp_buyback_farmmaster');
        $CI->db->where('companyID', current_companyID());
        $CI->db->where('isActive', 1);
        return $CI->db->get()->result_array();
    }
}

if (!function_exists('buyback_addon_catagory')) {
    function buyback_addon_catagory()
    {
        $CI =& get_instance();
        $CI->db->select('*');
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $data = $CI->db->get('srp_erp_buyback_addon_category')->result_array();
        $data_arr = array('' => 'Select Addon Category');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['category_id'])] = trim($row['description']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('buyback_all_gl_codes')) {
    function buyback_all_gl_codes($code = NULL)
    {
        $CI =& get_instance();
        $CI->db->SELECT("GLAutoID,systemAccountCode,GLSecondaryCode,GLDescription,systemAccountCode,subCategory,accountCategoryTypeID");
        $CI->db->from('srp_erp_chartofaccounts');
        if ($code) {
            $CI->db->where('subCategory', $code);
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

if (!function_exists('buyback_workin_progress_gl_codes')) {
    function buyback_workin_progress_gl_codes($code = NULL)
    {
        $CI =& get_instance();
        $CI->db->SELECT("coa.GLAutoID,coa.systemAccountCode,coa.GLSecondaryCode,coa.GLDescription,coa.systemAccountCode,coa.subCategory,coa.accountCategoryTypeID");
        $CI->db->from('srp_erp_chartofaccounts coa');
        $CI->db->join('srp_erp_companycontrolaccounts controlaccounts', 'coa.GLAutoID = controlaccounts.GLAutoID');
        $CI->db->where('controlaccounts.controlAccountType', 'WIP');
        $CI->db->where('coa.companyID', $CI->common_data['company_data']['company_id']);
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

if (!function_exists('edit_addonCategoryMaster')) {
    function edit_addonCategoryMaster($category_id)
    {
        $status = '<div style="text-align: center">';
        $status .= '<a onclick="edit_addonCategoryMaster(' . $category_id . ')"><span class="glyphicon glyphicon-pencil"></span></a>';
        $status .= '</div>';

        return $status;
    }
}

if (!function_exists('edit_buyback_item')) {
    function edit_buyback_item($buybackItemID,$itemMasterCode)
    {
        $CI =& get_instance();
        $companyID = current_companyID();
        $result = $CI->db->query("SELECT dpd.dispatchDetailsID FROM srp_erp_buyback_dispatchnotedetails dpd LEFT JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID = dpd.dispatchAutoID WHERE dpd.companyID={$companyID} AND dpd.itemAutoID = {$itemMasterCode} AND dpm.confirmedYN = 1 ")->row_array();

        $status = '<span class="pull-right">';
        if(empty($result)){
            $status .= '<a onclick="edit_buyback_itemMaster(' . $buybackItemID . ')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;';

            $status .= '| &nbsp;&nbsp;<a onclick="delete_item_master(' . $buybackItemID . ');"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';
        }else{
            $status .= '<i class="fa fa-check" aria-hidden="true" style="color: green;font-size: 15px;"></i>';
        }
        $status .= '</span>';
        return $status;
    }
}

/*Load all buyback item types for select2*/
if (!function_exists('load_buyBack_itemTypes')) {
    function load_buyBack_itemTypes($status = true)
    {
        $CI =& get_instance();
        $CI->db->SELECT("buybackItemtypeID,description");
        $CI->db->FROM('srp_erp_buyback_itemtypes');
        //$CI->db->where('companyID', current_companyID());
        $itemType = $CI->db->get()->result_array();
        $itemType_arr = array('' => 'Select Item Type');
        if (isset($itemType)) {
            foreach ($itemType as $row) {
                $itemType_arr[trim($row['buybackItemtypeID'])] = trim($row['description']);
            }
        }
        return $itemType_arr;
    }
}

/*Load all buyback item types for select2*/
if (!function_exists('load_buyBack_batches_report')) {
    function load_buyBack_batches_report($status = true)
    {
        $CI =& get_instance();
        $CI->db->SELECT("batchMasterID,batchCode");
        $CI->db->FROM('srp_erp_buyback_batch');
        $CI->db->where('companyID', current_companyID());
        $batch = $CI->db->get()->result_array();
        $batch_arr = array('' => 'Select Batch');
        if (isset($batch)) {
            foreach ($batch as $row) {
                $batch_arr[trim($row['batchMasterID'])] = trim($row['batchCode']);
            }
        }
        return $batch_arr;
    }
}

/*Load all buyback item types for select2*/
if (!function_exists('load_buyBack_batches')) {
    function load_buyBack_batches_notClosed($status = true)
    {
        $CI =& get_instance();
        $CI->db->SELECT("batchMasterID,batchCode");
        $CI->db->FROM('srp_erp_buyback_batch');
        $CI->db->where('companyID', current_companyID());
        $CI->db->where('isclosed', 1);
        $batch = $CI->db->get()->result_array();
        $batch_arr = array('' => 'Select Batch');
        if (isset($batch)) {
            foreach ($batch as $row) {
                $batch_arr[trim($row['batchMasterID'])] = trim($row['batchCode']);
            }
        }
        return $batch_arr;
    }
}

/*Load all buyback Mortality Causes*/
if (!function_exists('load_buyBack_mortality_Causes')) {
    function load_buyBack_mortality_Causes($status = true)
    {
        $CI =& get_instance();
        $CI->db->SELECT("causeID,Description");
        $CI->db->FROM('srp_erp_buyback_mortalitycauses');
        $CI->db->where('companyID', current_companyID());
        $cause = $CI->db->get()->result_array();
        $cause_arr = array('' => 'Select Cause');
        if (isset($cause)) {
            foreach ($cause as $row) {
                $cause_arr[trim($row['causeID'])] = trim($row['Description']);
            }
        }
        return $cause_arr;
    }
}

if (!function_exists('fetch_buyback_item_data')) {
    function fetch_buyback_item_data($itemAutoID)
    {
        $CI =& get_instance();
        $CI->db->SELECT("*");
        $CI->db->FROM('srp_erp_buyback_itemmaster');
        $CI->db->WHERE('itemAutoID', $itemAutoID);
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);

        return $CI->db->get()->row_array();
    }
}

if (!function_exists('farm_master_gl_drop')) {
    function farm_master_gl_drop()
    {
        $CI =& get_instance();
        $CI->db->SELECT("GLAutoID,systemAccountCode,GLSecondaryCode,GLDescription,subCategory");
        $CI->db->FROM('srp_erp_chartofaccounts');
        $CI->db->where_in('subCategory', array("BSL", "BSA"));
        $CI->db->WHERE('controllAccountYN', 1);
        $CI->db->WHERE('masterAccountYN', 0);
        $CI->db->where('isBank', 0);
        $CI->db->where('isActive', 1);
        $CI->db->where('approvedYN', 1);
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Farmer GL Account');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['GLAutoID'])] = trim($row['systemAccountCode']) . ' | ' . trim($row['GLSecondaryCode']) . ' | ' . trim($row['GLDescription']) . ' | ' . trim($row['subCategory']);
            }
        }

        return $data_arr;
    }
}

if (!function_exists('buyback_dispatchNote_approval_action')) {
    function buyback_dispatchNote_approval_action($dispatchAutoID, $approvalLevelID, $approvedYN, $documentApprovedID,$batchid)
    {
        $status = '<span class="pull-right">';
        if ($approvedYN == 0) {
            $status .= '<a onclick=\'fetch_approval("' . $dispatchAutoID . '","' . $documentApprovedID . '","' . $approvalLevelID . '","' . $batchid . '"); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>&nbsp;&nbsp;';
        } else {
            $status .= '<a target="_blank" onclick="documentPageView_modal(\'BBDPN\',\'' . $dispatchAutoID . '\',\'' . $batchid . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;';
        }

        $status .= '</span>';

        return $status;
    }
}

if (!function_exists('buyback_paymentVoucher_approval_action')) {
    function buyback_paymentVoucher_approval_action($pvMasterAutoID, $approvalLevelID, $approvedYN, $documentApprovedID)
    {
        $status = '<span class="pull-right">';
        if ($approvedYN == 0) {
            $status .= '<a onclick=\'fetch_approval("' . $pvMasterAutoID . '","' . $documentApprovedID . '","' . $approvalLevelID . '"); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>&nbsp;&nbsp;';
        } else {
            $status .= '<a target="_blank" onclick="documentPageView_modal(\'BBPV\',\'' . $pvMasterAutoID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;';
        }

        $status .= '</span>';

        return $status;
    }
}

if (!function_exists('buyback_goodReceiptNote_approval_action')) {
    function buyback_goodReceiptNote_approval_action($grnAutoID, $approvalLevelID, $approvedYN, $documentApprovedID)
    {
        $status = '<span class="pull-right">';
        if ($approvedYN == 0) {
            $status .= '<a onclick=\'fetch_approval("' . $grnAutoID . '","' . $documentApprovedID . '","' . $approvalLevelID . '"); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>&nbsp;&nbsp;';
        } else {
            $status .= '<a target="_blank" onclick="documentPageView_modal(\'BBGRN\',\'' . $grnAutoID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;';
        }

        $status .= '</span>';

        return $status;
    }
}

if (!function_exists('buyback_batchClosing_approval_action')) {
    function buyback_batchClosing_approval_action($batchMasterID, $approvalLevelID, $approvedYN, $documentApprovedID)
    {
        $status = '<span class="pull-right">';
        if ($approvedYN == 0) {
            $status .= '<a onclick=\'fetch_approval("' . $batchMasterID . '","' . $documentApprovedID . '","' . $approvalLevelID . '"); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>&nbsp;&nbsp;';
        } else {
            $status .= '<a target="_blank" onclick="documentPageView_modal(\'BBBC\',\'' . $batchMasterID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;';
        }

        $status .= '</span>';

        return $status;
    }
}

if (!function_exists('fetch_buyback_feedTypes')) {
    function fetch_buyback_feedTypes()
    {
        $CI =& get_instance();
        $CI->db->SELECT("buybackFeedtypeID,description");
        $CI->db->FROM('srp_erp_buyback_feedtypes');
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select a Type');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['buybackFeedtypeID'])] = trim($row['description']);
            }
        }

        return $data_arr;
    }
}

if (!function_exists('fetch_buyback_umo_drop')) {
    function fetch_buyback_umo_drop()
    {
        $CI =& get_instance();
        $CI->db->select('UnitID,UnitShortCode,UnitDes');
        $CI->db->from('srp_erp_unit_of_measure');
        $CI->db->WHERE('companyID', $CI->common_data['company_data']['company_id']);
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select UOM');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['UnitID'])] = trim($row['UnitShortCode']) . ' - ' . trim($row['UnitDes']);
            }
        }

        return $data_arr;
    }
}

if (!function_exists('buyback_farm_fieldOfficers_drop')) {
    function buyback_farm_fieldOfficers_drop()
    {
        $CI =& get_instance();
        $CI->db->SELECT("ffo.empID,emp.Ename2");
        $CI->db->from('srp_erp_buyback_farmfieldofficers ffo');
        $CI->db->join('srp_employeesdetails emp', 'emp.EIdNo = ffo.empID');
        $CI->db->where('ffo.companyID', $CI->common_data['company_data']['company_id']);
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Field Officer');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['empID'])] = trim($row['Ename2']);
            }
        }

        return $data_arr;
    }
}
if (!function_exists('isBuyBack_company')) {
    function isBuyBack_company()
    {
        $CI =& get_instance();
        $CI->db->SELECT("isBuyBackEnabled");
        $CI->db->FROM('srp_erp_company');
        $CI->db->where('company_id', $CI->common_data['company_data']['company_id']);
        $row = $CI->db->get()->row_array();
        $data = 0;
        if (!empty($row)) {
            if ($row['isBuyBackEnabled'] == 1) {
                $data = 1;
            } else {
                $data = 0;
            }
        }
        return $data;
    }
}

if (!function_exists('tax_formulaBuilder_to_sql')) {
    function tax_formulaBuilder_to_sql($ssoRow = array(), $salary_categories_arr = array(),$amount)
    {
       // print_r($ssoRow);
        $formula = (is_array($ssoRow))? trim($ssoRow['formulaString']) : $ssoRow;
        $payGroupCategories = (is_array($ssoRow))? trim($ssoRow['payGroupCategories']) :'';
        $formulaText = '';
        $salaryCatID = array();
        $formulaDecode_arr = array();
        $operand_arr = operand_arr();

        if(!empty($payGroupCategories)){
            global $globalFormula;
            $globalFormula = $formula;
            $decode_data = decode_taxGroup($ssoRow);
           // print_r($decode_data);
            if(is_array($decode_data)){
                if($decode_data[0] == 'e'){
                    //If maximum recursive exceeded than return will be a array else string
                    return $decode_data;
                }
            }
            $formula = $decode_data;

        }

       //echo $formula;
       // die();
        $formula_arr = explode('|', $formula); // break the formula
        $n = 0;

        foreach ($formula_arr as $formula_row) {

            if (trim($formula_row) != '') {
                if (in_array($formula_row, $operand_arr)) { //validate is a operand
                    $formulaText .= ' '.$formula_row.' ';

                    $formulaDecode_arr[] = $formula_row;
                }
                else {

                    $elementType = $formula_row[0];

                    if ($elementType == '_') {
                        /*** Number ***/
                        $numArr = explode('_', $formula_row);
                        $formulaText .= ( is_numeric($numArr[1]) ) ? $numArr[1] : $numArr[0];
                        $formulaDecode_arr[] = ( is_numeric($numArr[1]) ) ? $numArr[1] : $numArr[0];

                    }
                    else if ($elementType == '#') {
                        /*** Salary category ***/
                        $catArr = explode('#', $formula_row);
                        $salaryCatID[$n]['ID'] = $catArr[1];
                        $salaryCatID[$n]['columnType'] = 'TAX_CAT';

                        $keys = array_keys(array_column($salary_categories_arr, 'taxMasterAutoID'), $catArr[1]);
                        $new_array = array_map(function ($k) use ($salary_categories_arr) {
                            return $salary_categories_arr[$k];
                        }, $keys);

                        $salaryDescription = (!empty($new_array[0])) ? trim($new_array[0]['taxDescription']) : '';

                        $formulaText .= $salaryDescription;

                        $salaryDescription_arr = explode(' ', $salaryDescription);
                        $salaryDescription_arr = preg_replace("/[^a-zA-Z 0-9]+/", "", $salaryDescription_arr);
                        $salaryCatID[$n]['cat'] = implode('_', $salaryDescription_arr) . '_' . $n;
                        $formulaDecode_arr[] = 'SUM(' . $salaryCatID[$n]['cat'] . ')';

                    }
                    else if ($elementType == '!') {
                        $monthlyADArr = explode('!', $formula_row);

                        /*** Monthly Addition or Monthly Deduction ***/
                        $formulaText .= 'Amount';
                        $MD_MD_Description = $monthlyADArr[1] . '_' . $n;

                        $formulaDecode_arr[] = $amount;
                        $salaryCatID[$n]['cat'] = $monthlyADArr[1];
                        $salaryCatID[$n]['description'] = $MD_MD_Description;
                        $salaryCatID[$n]['columnType'] = 'AMT';

                    }

                    $n++;
                }
            }

        }

        $formulaDecode = implode(' ', $formulaDecode_arr);

        $select_salaryCat_str = '';
        $select_group_str = '';
        $select_monthlyAD_str = '';
        $whereInClause = '';
        $where_MA_MD_Clause = array();
        $whereInClause_group = '';
        $separator_salCat_count = 0;
        $separator_group_count = 0;
        $separator_monthlyAD_count = 0;


        foreach ($salaryCatID as $key1 => $row) {
            $separator_salCat = ($separator_salCat_count > 0) ? ',' : '';
            $separator_group = ($separator_group_count > 0) ? ',' : '';
            $separator_monthlyAD = ($separator_monthlyAD_count > 0) ? ',' : '';

            if ($row['columnType'] == 'TAX_CAT') {
                $select_salaryCat_str .= $separator_salCat . 'IF(salCatID=' . $row['ID'] . ', SUM(transactionAmount) , 0 ) AS ' . $row['cat'] . '';
                $whereInClause .= $separator_salCat . ' ' . $row['ID'];
                $separator_salCat_count++;
            }
            if ($row['columnType'] == 'AMT') {
                $select_monthlyAD_str .= $separator_monthlyAD . ' IF(calculationTB=\'' . $row['cat'] . '\', SUM(transactionAmount) , 0 ) AS ' . $row['description'] . '';

                //array_push($where_MA_MD_Clause, array($row['cat']=>$row['cat']));
                $where_MA_MD_Clause[] = $row['cat'];
                $separator_monthlyAD_count++;
            }


        }

        $returnData = array(
            'formulaDecode' => $formulaDecode,
            'select_salaryCat_str' => $select_salaryCat_str,
            'select_group_str' => $select_group_str,
            'select_monthlyAD_str' => $select_monthlyAD_str,
            'whereInClause' => $whereInClause,
            'where_MA_MD_Clause' => $where_MA_MD_Clause,
            'whereInClause_group' => $whereInClause_group,
        );

        return $returnData;
    }
}

$globalFormula = '';
if (!function_exists('decode_taxGroup')) {
    function decode_taxGroup($formulaData, $decode_taxGroup_count=0)
    {
        $CI =& get_instance();
        $companyID = current_companyID();
        $payGroupCategories = $formulaData['payGroupCategories'];
        $taxCalculationformulaID = $formulaData['taxCalculationformulaID'];


        global $globalFormula;
        $decode_taxGroup_count++;

        if($decode_taxGroup_count > 1000){
            //If the recursive worked more than 200 times than terminate the function
            return ['e', 'Decode tax group function got terminated.<br/>'];
        }

        $result = $CI->db->query("SELECT masterTB.taxMasterAutoID, formula AS formulaString, taxMasters AS payGroupCategories,masterTB.taxPercentage as taxPercentage FROM srp_erp_taxmaster AS masterTB
                                  JOIN srp_erp_taxcalculationformuladetails AS formula ON formula.taxMasterAutoID=masterTB.taxMasterAutoID AND taxCalculationformulaID=$taxCalculationformulaID
                                  WHERE masterTB.companyID = {$companyID} AND masterTB.taxMasterAutoID IN ($payGroupCategories)")->result_array();
       // echo '<pre>';print_r($result); echo '</pre>';

        foreach($result as $row){
            $searchVal = '#'.$row['taxMasterAutoID'];
            $replaceVal = '|(|'.$row['formulaString'].'|)|';


            if(!empty( $row['payGroupCategories'] )){
                $globalFormula = str_replace($searchVal, $replaceVal, $globalFormula);
                $return = decode_taxGroup($row, $decode_taxGroup_count);
                if(is_array($return)){
                    if($return[0] == 'e'){
                        return $return;
                        break;
                    }
                }
            }
            else{
                $globalFormula = str_replace($searchVal, $replaceVal, $globalFormula);
                $payGroupCategories = null;
            }
        }

        return $globalFormula;
    }
}
if (!function_exists('get_segment_code')) {
    function get_segment_code($segmentID)
    {
        $CI =& get_instance();
        $CI->db->select("segmentCode");
        $CI->db->from('srp_erp_segment');
        $CI->db->where('segmentID', $segmentID);
        $result = $CI->db->get()->row_array();
        if (!empty($result)) {
            return $result['segmentCode'];
        } else {
            return null;
        }
    }
}

if (!function_exists('get_customer_details')) {
    function get_customer_details($customerMasterID,$companyID)/*get all Customers*/
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->from('srp_erp_customermaster');
        $CI->db->where('customerAutoID', $customerMasterID);
        $CI->db->where('companyID', $companyID);
        $customer = $CI->db->get()->row_array();

        return $customer;
    }
}

if (!function_exists('get_company_details')) {
    function get_company_details($companyID)/*get all Customers*/
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->from('srp_erp_company');
        $CI->db->where('company_id', $companyID);
        $company = $CI->db->get()->row_array();

        return $company;
    }
}

if (!function_exists('get_warehouse_details')) {
    function get_warehouse_details($warehosueMasterID,$companyID)/*get all Customers*/
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->from('srp_erp_warehousemaster');
        $CI->db->where('companyID', $companyID);
        $CI->db->where('wareHouseAutoID', $warehosueMasterID);
        $warehouse = $CI->db->get()->row_array();

        return $warehouse;
    }
}

if (!function_exists('get_item_details')) {
    function get_item_details($itemAutoID,$companyID)/*get all Customers*/
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->from('srp_erp_itemmaster');
        $CI->db->where('companyID', $companyID);
        $CI->db->where('itemAutoID', $itemAutoID);
        $item = $CI->db->get()->row_array();

        return $item;
    }
}

if (!function_exists('get_coa_details')) {
    function get_coa_details($GLAutoID,$companyID)/*get all Customers*/
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->from('srp_erp_chartofaccounts');
        $CI->db->where('companyID', $companyID);
        $CI->db->where('GLAutoID', $GLAutoID);
        $item = $CI->db->get()->row_array();

        return $item;
    }
}

if (!function_exists('eliminate_check_box')) {
    function eliminate_check_box($invoiceAutoID,$eliminateYN)
    {
        $status = '';
        if ($eliminateYN == 1) {
            $status .= '<div style="text-align: center;"><div class="skin skin-square invoice-iCheck"> <div class="skin-section extraColumns"><input id="eliminateYN_'.$invoiceAutoID.'" onclick="InvoiceEliminate(this)" name="eliminateYN[]" type="checkbox" class="columnSelectedEliminate"  value="'.$invoiceAutoID.'" checked><label for="checkbox">&nbsp;</label> </div></div></div>';
        } else {
            $status .= '<div style="text-align: center;"><div class="skin skin-square invoice-iCheck"> <div class="skin-section extraColumns"><input id="eliminateYN_'.$invoiceAutoID.'" onclick="InvoiceEliminate(this)" name="eliminateYN[]" type="checkbox" class="columnSelectedEliminate"  value="'.$invoiceAutoID.'" ><label for="checkbox">&nbsp;</label> </div></div></div>';
        }

        $status .= '';

        return $status;
    }
}

if (!function_exists('load_invoice_action_buyback_dayclose')) {
    function load_invoice_action_buyback_dayclose($poID, $POConfirmedYN, $approved, $createdUserID, $confirmedYN, $isDeleted)
    {
        $CI =& get_instance();
        $CI->load->library('session');
        $status = '<span class="pull-right">';
        //$status .= '<a onclick=\'attachment_modal(' . $poID . ',"Invoice","HCINV",' . $confirmedYN . ');\'><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';

        if ($isDeleted == 1) {
            //$status .= '<a onclick="reOpen_contract(' . $poID . ');"><span title="Re Open" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }

        if ($POConfirmedYN != 1 && $isDeleted == 0) {
            //$status .= '<a onclick=\'fetchPage("system/invoices/erp_invoices_buyback",' . $poID . ',"Edit Customer Invoice","PO"); \'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }

        if ($createdUserID == trim($CI->session->userdata("empID")) and $approved == 0 and $POConfirmedYN == 1 && $isDeleted == 0) {
            //$status .= '<a onclick="referback_customer_invoice(' . $poID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }

        $status .= '<a target="_blank" onclick="documentPageView_modal(\'HCINV\',\'' . $poID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>';

        $status .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a target="_blank" href="' . site_url('InvoicesPercentage/load_invoices_conformation_buyback/') . '/' . $poID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a> ';

        if ($POConfirmedYN != 1 && $isDeleted == 0) {
            //$status .= '&nbsp;&nbsp;| &nbsp;&nbsp;<a onclick="delete_item(' . $poID . ',\'Invoices\');"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';
        }

        $status .= '</span>';

        return $status;
    }
}

if (!function_exists('fetch_item_data_pvt')) {
    function fetch_item_data_pvt($itemAutoID,$companyID)
    {
        $CI =& get_instance();
        $CI->db->SELECT("*");
        $CI->db->FROM('srp_erp_itemmaster');
        $CI->db->WHERE('itemAutoID', $itemAutoID);
        $CI->db->where('companyID', $companyID);

        return $CI->db->get()->row_array();
    }
}
if (!function_exists('load_buyback_return_action')) {
    function load_buyback_return_action($returnAutoID, $confirmedYN, $approvedYN, $createdUserID,$isDeleted)
    {

        $CI =& get_instance();
        $CI->load->library('session');
        $status = '<span class="pull-right">';
        $status .= '<a onclick=\'attachment_modal(' . $returnAutoID . ',"Return","BBDR",' . $confirmedYN . ');\'><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';

        if($isDeleted==1){
            $status .= '<a onclick="reOpen_contract(' . $returnAutoID .');"><span title="Re Open" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }

        if ($confirmedYN != 1 && $isDeleted==0) {
            $status .= '<a onclick=\'fetchPage("system/buyback/create_buyback_return",' . $returnAutoID . ',"Edit Return","BBDR"); \'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';
        }

        if ($createdUserID == trim($CI->session->userdata("empID")) and $approvedYN == 0 and $confirmedYN == 1 && $isDeleted==0) {
            $status .= '<a onclick="referback_buyback_return(' . $returnAutoID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
        }

        $status .= '<a target="_blank" onclick="documentPageView_modal(\'BBDR\',\'' . $returnAutoID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>';

        $status .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a target="_blank" href="' . site_url('Buyback/load_buyback_return_conformation') . '/' . $returnAutoID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>';

        if ($confirmedYN != 1 && $isDeleted==0) {
            $status .= '&nbsp;&nbsp;| &nbsp;&nbsp;<a onclick="delete_item(' . $returnAutoID . ',\'Return\');"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';
        }
        $status .= '</span>';
        return $status;
    }
    if (!function_exists('buyback_return_action_approval')) {
        function buyback_return_action_approval($returnautoid, $Level, $approved, $ApprovedID, $documentID, $approval = 1)
        {
            $status = '<span class="pull-right">';
            $status .= '<a onclick=\'attachment_modal(' . $returnautoid . ',"Return","BBDR");\'><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
            if ($approved == 0) {
                $status .= '<a onclick=\'fetch_approval("' . $returnautoid . '","' . $ApprovedID . '","' . $Level . '"); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>&nbsp;&nbsp;';

            } else {
                $status .= '<a onclick=\'documentPageView_modal("' . $documentID . '","' . $returnautoid . '","","' . $approval . '"  ); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;';
            }



            $status .= '</span>';

            return $status;
        }
    }

}

/* Buyback Dashboard Details Fetch */
if (!function_exists('chicks_balance_dashboard')) {
    function chicks_balance_dashboard($batchMasterID, $chicksTotal)
    {

        $CI =& get_instance();
        $CI->db->SELECT("COALESCE(sum(srp_erp_buyback_grndetails.noOfBirds), 0) AS balanceChicksTotal");
        $CI->db->FROM('srp_erp_buyback_grn');
        $CI->db->join('srp_erp_buyback_grndetails','srp_erp_buyback_grndetails.grnAutoID = srp_erp_buyback_grn.grnAutoID','INNER');
        $CI->db->WHERE('batchMasterID', $batchMasterID);

        $balanceChicksTotal = $CI->db->get()->row_array();

        $CI =& get_instance();
        $CI->db->SELECT("COALESCE(sum(noOfBirds), 0) AS deadChicksTotal");
        $CI->db->FROM('srp_erp_buyback_mortalitymaster');
        $CI->db->join('srp_erp_buyback_mortalitydetails','srp_erp_buyback_mortalitydetails.mortalityAutoID = srp_erp_buyback_mortalitymaster.mortalityAutoID','INNER');
        $CI->db->WHERE('batchMasterID', $batchMasterID);

        $deadChicksTotal = $CI->db->get()->row_array();

        if (!empty($balanceChicksTotal)) {
            $totalChicks = ($chicksTotal - ($balanceChicksTotal['balanceChicksTotal'] + $deadChicksTotal['deadChicksTotal']));
            return $totalChicks;
        }
    }
}

if (!function_exists('chicks_age_dashboard')) {
    function chicks_age_dashboard($batch, $ageFrom, $ageTo)
    {
        $CI =& get_instance();

        $CI->db->SELECT("srp_erp_buyback_dispatchnote.dispatchedDate,srp_erp_buyback_batch.closedDate");
        $CI->db->FROM('srp_erp_buyback_dispatchnote');
        $CI->db->join('srp_erp_buyback_dispatchnotedetails','srp_erp_buyback_dispatchnotedetails.dispatchAutoID = srp_erp_buyback_dispatchnote.dispatchAutoID AND buybackItemType = 1','INNER');
        $CI->db->join('srp_erp_buyback_batch','srp_erp_buyback_batch.batchMasterID = srp_erp_buyback_dispatchnote.batchMasterID');
        $CI->db->WHERE('srp_erp_buyback_dispatchnote.batchMasterID', $batch);
        $chicksAge = $CI->db->get()->row_array();
        if (!empty($chicksAge)) {
            $dStart = new DateTime($chicksAge['dispatchedDate']);
            if($chicksAge['closedDate'] != ''){
                $dEnd  = new DateTime($chicksAge['closedDate']);
            }else{
                $dEnd  = new DateTime(current_date());
            }
            $dDiff = $dStart->diff($dEnd);
            $newFormattedDate = $dDiff->days + 1;

            if(!empty($ageFrom) && !empty($ageTo)){
                if($newFormattedDate >= $ageFrom && $newFormattedDate <= $ageTo){
                    return $newFormattedDate;
                }
            }else{
                return $newFormattedDate;
            }

        }
    }
}

if (!function_exists('load_yearfilter_dashboard')) {
    function load_yearfilter_dashboard()
    {
        $CI =& get_instance();
        $convertFormat = convert_date_format_sql();

        $CI->db->SELECT("*, YEAR(beginingDate) as year");
        $CI->db->FROM('srp_erp_companyfinanceyear');
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $CI->db->where('isActive', 1);
        $CI->db->order_by('year', 'DESC');
        $data = $CI->db->get()->result_array();

        $data_arr = array();
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['companyFinanceYearID'])] = trim($row['year']) . ' yr';
            }
        }

        return $data_arr;
    }
}

if (!function_exists('load_dashboard_monthTitle')) {
    function load_dashboard_monthTitle($periodId)
    {
        $CI =& get_instance();
        $CI->db->select(" MONTH(dateFrom) AS month");
        $CI->db->from('srp_erp_companyfinanceperiod');
        $CI->db->where('companyFinancePeriodID', $periodId);
        $item = $CI->db->get()->row_array();

      //  var_dump($item['month']);
         switch ($item['month']) {
             case 1:
                 $result = 'Jan';
                 Break;
             case 2:
                 $result = 'Feb';
                 Break;
             case 3:
                 $result = 'Mar';
                 Break;
             case 4:
                 $result = 'Apr';
                 Break;
             case 5:
                 $result = 'May';
                 Break;
             case 6:
                 $result = 'Jun';
                 Break;
             case 7:
                 $result = 'July';
                 Break;
             case 8:
                 $result = 'Aug';
                 Break;
             case 9:
                 $result = 'Sep';
                 Break;
             case 10:
                 $result = 'Oct';
                 Break;
             case 11:
                 $result = 'Nov';
                 Break;
             case 12:
                 $result = 'Dec';
                 Break;

             default:
                 $result = 'Annual';
         }
         return $result;
    }
}

if (!function_exists('getColor')) {
    function getColor()
    {
        $ColorArr = array('#4572A7', '#AA4643', '#89A54E', '#80699B', '#3D96AE', '#DB843D', '#92A8CD', '#A47D7C', '#B5CA92');
        // "#A27BA7","#C72A3B","#DA6784","#0495C2","#0F3353","#6872FF","#488957","#FF59AC","#999999","#996855","#3C3636"
        $k = array_rand($ColorArr);
        return $ColorArr[$k];
    }
}

if (!function_exists('buybackReturnApproval')) {
    function buybackReturnApproval($confirmedYN,$approvedYN,$code,$returnAutoID)
    {
$status = '<center>';

        if($code == 1){
            if ($confirmedYN == 1) {
                $status .= '<a style="cursor: pointer"><span
                        class="label"
                        style="background-color:#8bc34a; color: #FFFFFF; font-size: 11px;">Confirmed</span></a>';
            } else {
                $status .= ' <span class="label"
                         style="background-color: rgba(255, 72, 49, 0.96); color: #FFFFFF; font-size: 11px;">Not Confirmed</span>';

            }
        } else{
            if ($approvedYN == 1) {
                $status .= '<a style="cursor: pointer"
                        onclick="fetch_approval_user_modal(\'BBDR\',' . $returnAutoID . ')"><span
                        class="label"
                        style="background-color:#8bc34a; color: #FFFFFF; font-size: 11px;">Approved <i
                        class="fa fa-external-link" aria-hidden="true"></i></span></a>';
            } else {
                $status .= ' <span class="label"
                         style="background-color: rgba(255, 72, 49, 0.96); color: #FFFFFF; font-size: 11px;">Not Approved</span>';

            }
        }
        return $status;
    }
}

if (!function_exists('wagesPayableAmount')) {
    function wagesPayableAmount($id)
    {
        $CI =& get_instance();
        $companyID =  $CI->common_data['company_data']['company_id'];

        $CI->db->select('sum( pvd.transactionAmount ) AS transactionAmount,sum( pvd.companyLocalAmount ) AS LocalAmount,sum( pvd.companyReportingAmount ) AS ReportingAmount');
        $CI->db->from("srp_erp_buyback_paymentvoucherdetail pvd");
        $CI->db->join('srp_erp_buyback_paymentvouchermaster pvm', 'pvd.pvMasterAutoID = pvm.pvMasterAutoID', 'LEFT');
        $CI->db->where("pvd.BatchID", $id);
        $CI->db->where("pvd.companyID", $companyID);
        $CI->db->where("pvd.type", 'Expense');
        $CI->db->where("pvm.approvedYN", 1);
        $CI->db->order_by("pvDetailID DESC");
        $expense = $CI->db->get()->row_array();

        $CI->db->select('sum( totalTransferAmountTransaction ) AS totalTransferAmountTransaction,sum( totalTransferAmountLocal ) AS LocalAmount,sum( totalTranferAmountReporting ) AS ReportingAmount');
        $CI->db->from("srp_erp_buyback_itemledger");
        $CI->db->where("batchID", $id);
        $CI->db->where("companyID", $companyID);
        $CI->db->where("documentCode", 'BBDPN');
        $CI->db->order_by("buybackItemType ASC");
        $dispatch = $CI->db->get()->row_array();

        $CI->db->select('sum( totalTransferAmountTransaction ) AS totalTransferAmountTransaction,sum( totalTransferAmountLocal ) AS totalTransferAmountLocal,sum( totalTranferAmountReporting ) AS totalTranferAmountReporting');
        $CI->db->from("srp_erp_buyback_itemledger");
        $CI->db->where("batchID", $id);
        $CI->db->where("companyID", $companyID);
        $CI->db->where("documentCode", 'BBGRN');
        $CI->db->order_by("itemLedgerAutoID ASC");
        $buyback = $CI->db->get()->row_array();

        $CI->db->select('sum( disreturn.totalTransferCost ) AS totalTransferCost, sum( disreturn.totalTransferCostReporting ) AS totalTransferCostReporting,sum( disreturn.totalTransferCostLocal ) AS totalTransferCostLocal');
        $CI->db->from("srp_erp_buyback_dispatchreturn returnmaster ");
        $CI->db->join('srp_erp_buyback_dispatchreturndetails disreturn', 'disreturn.returnAutoID = returnmaster.returnAutoID', 'left');
        $CI->db->join('srp_erp_buyback_dispatchnote dismaster', 'dismaster.dispatchAutoID = disreturn.dispatchAutoID', 'left');
        $CI->db->where("returnmaster.batchMasterID", $id);
        $CI->db->where("returnmaster.companyID", $companyID);
        $CI->db->where("returnmaster.approvedYN", 1);
        $CI->db->where("returnmaster.confirmedYN", 1);
        $return = $CI->db->get()->row_array();

        $grandTotalrptAmount = $dispatch['totalTransferAmountTransaction'] + $expense['transactionAmount'];
        $grandTotalBuybackAmount = $buyback['totalTransferAmountTransaction'] + $return['totalTransferCost'];
        $data['transactionAmount'] = ($grandTotalBuybackAmount - $grandTotalrptAmount);

        $grandTotalrptAmount = $dispatch['LocalAmount'] + $expense['LocalAmount'];
        $grandTotalBuybackAmount = $buyback['totalTransferAmountLocal'] + $return['totalTransferCostLocal'];
        $data['companyLocalAmount'] = ($grandTotalBuybackAmount - $grandTotalrptAmount);

        $grandTotalrptAmount = $dispatch['ReportingAmount'] + $expense['ReportingAmount'];
        $grandTotalBuybackAmount = $buyback['totalTranferAmountReporting'] + $return['totalTransferCostReporting'];
        $data['companyReportingAmount'] = ($grandTotalBuybackAmount - $grandTotalrptAmount);
        return $data;
    }
}

if (!function_exists('load_all_fleet_vehicles')) {
    function load_all_fleet_vehicles()
    {
        $CI =& get_instance();
        $CI->db->SELECT("vehicleMasterID,vehicleCode,VehicleNo");
        $CI->db->FROM('fleet_vehiclemaster');
        $CI->db->where('companyID', current_companyID());
        $CI->db->where('isActive', 1);
        $farm = $CI->db->get()->result_array();

            $farm_arr = array('' => 'Select Vehicle');
            if (isset($farm)) {
                foreach ($farm as $row) {
                    $farm_arr[trim($row['vehicleMasterID'])] = trim($row['vehicleCode']) . " | " . trim($row['VehicleNo']);
                }
            }

        return $farm_arr;
    }
}
