<?php
define("empMasterTables", serialize(
    array(
        'EmpTitleId' => ['srp_titlemaster', 'TitleID', 'TitleDescription'],
        'Nid' => ['srp_nationality', 'NId', 'Nationality'],
        'MaritialStatus' => ['srp_erp_maritialstatus', 'maritialstatusID', 'description'],
        'Rid' => ['srp_religion', 'RId', 'Religion'],
        'Gender' => ['srp_erp_gender', 'genderID', 'name'],
        'BloodGroup' => ['srp_erp_bloodgrouptype', 'BloodTypeID', 'BloodDescription'],
        'relationship' => ['srp_erp_family_relationship', 'relationshipID', 'relationship'],
        'nationality' => ['srp_nationality', 'NId', 'Nationality'],
        'gender' => ['srp_erp_gender', 'genderID', 'name'],
        'insuranceCategory' => ['srp_erp_family_insurancecategory', 'insurancecategoryID', 'description'],
    )
));

if (!function_exists('get_employeetitle')) {
    function get_employeetitle()/*Load all company*/
    {
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->select('*');
        $CI->db->from('srp_titlemaster');
        $CI->db->where('Erp_companyID', current_companyID());
        return $CI->db->get()->result_array();
    }
}

if (!function_exists('get_gender')) {
    function get_gender()
    {
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->select('*');
        $CI->db->from('srp_erp_gender');
        return $CI->db->get()->result_array();
    }
}

if (!function_exists('search_pendingData')) {
    function search_pendingData($data_arr, $searchVal, $isDrop=0)
    {
        $keys = array_keys(array_column($data_arr, 'columnName'), $searchVal);
        $new_array = array_map(function ($k) use ($data_arr) {
            return $data_arr[$k];
        }, $keys);



        if($isDrop == 1 && !empty($new_array[0])){
            $empMasterTables = unserialize(empMasterTables);
            $tableName = $empMasterTables[$searchVal][0];
            $masterIDColumn = $empMasterTables[$searchVal][1];
            $desColumn = $empMasterTables[$searchVal][2];

            $masterID = $new_array[0]['columnVal'];

            $CI =& get_instance();
            $description = $CI->db->query("SELECT {$desColumn} AS searchVal FROM {$tableName}
                                           WHERE {$masterIDColumn} = {$masterID}")->row('searchVal');
            return [$masterID, $description];
        }
        else{
            //return (!empty($new_array[0])) ? $new_array[0]['columnVal'] : null;
            return (array_key_exists(0, $new_array)) ? $new_array[0]['columnVal'] : null;
        }
    }
}

if (!function_exists('search_pendingDataApproval')) {
    function search_pendingDataApproval($data_arr, $searchVal, $isDrop=0)
    {
        /*echo '<pre>helper -' .$searchVal.'- <br/>'; print_r($data_arr); echo '</pre>';
        $m = array_column($data_arr, 'columnName');
        echo '<pre>columnName <br/>'; print_r($m); echo '</pre>';
        $keys = array_keys(array_column($data_arr, 'columnName'), $searchVal);
        $new_array = array_map(function ($k) use ($data_arr) {
            return $data_arr[$k];
        }, $keys);*/


        $keys = array_keys(array_column($data_arr, 'columnName'), $searchVal);
        $new_array = array_map(function ($k) use ($data_arr) {
            return $data_arr[$k];
        }, $keys);

        if($isDrop == 1 && !empty($new_array[0])){
            $empMasterTables = unserialize(empMasterTables);
            $tableName = $empMasterTables[$searchVal][0];
            $masterIDColumn = $empMasterTables[$searchVal][1];
            $desColumn = $empMasterTables[$searchVal][2];

            $masterID = $new_array[0]['columnVal'];

            $CI =& get_instance();
            $description = $CI->db->query("SELECT {$desColumn} AS searchVal FROM {$tableName}
                                             WHERE {$masterIDColumn} = {$masterID}")->row('searchVal');

            $returnData = '<td> '.$description.'<input type="hidden" name="columnVal[' . $searchVal . ']" value="'.$masterID.'" /></td>';
            $returnData .= '<td><input type="checkbox" name="upDateColumn[]" value="' . $searchVal . '" class="approveChk"/></td>';
            return $returnData;
        }
        else if($isDrop == 2 && !empty($new_array[0])){
            $date = format_date_dob($new_array[0]['columnVal']);
            $date2 = $new_array[0]['columnVal'];

            $returnData = '<td> ' . $date . '<input type="hidden" name="columnVal[' . $searchVal . ']" value="' . $date2 . '" /></td>';
            $returnData .= '<td><input type="checkbox" name="upDateColumn[]" value="' . $searchVal . '" class="approveChk"/></td>';
            return $returnData;
        }
        else{
            $returnData = null;
            if (!empty($new_array[0])) {
                $description = $new_array[0]['columnVal'];

                $returnData = '<td> ' . $description .'<input type="hidden" name="columnVal['.$searchVal.']" value="' . $description . '" /></td>';
                $returnData .= '<td><input type="checkbox" name="upDateColumn[]" value="' . $searchVal . '" class="approveChk"/></td>';
            }

            return $returnData;
        }
    }
}

if (!function_exists('search_pendingFamilyDataApproval')) {
    function search_pendingFamilyDataApproval($id, $data_arr, $searchVal, $isDrop=0)
    {

        $keys = array_keys(array_column($data_arr, 'columnName'), $searchVal);
        $new_array = array_map(function ($k) use ($data_arr) {
            return $data_arr[$k];
        }, $keys);

        if($isDrop == 1 && !empty($new_array[0])){
            $empMasterTables = unserialize(empMasterTables);
            $tableName = $empMasterTables[$searchVal][0];
            $masterIDColumn = $empMasterTables[$searchVal][1];
            $desColumn = $empMasterTables[$searchVal][2];

            $masterID = $new_array[0]['columnVal'];

            $CI =& get_instance();
            $description = $CI->db->query("SELECT {$desColumn} AS searchVal FROM {$tableName}
                                             WHERE {$masterIDColumn} = {$masterID}")->row('searchVal');

            $dataApp = ' value="' . $searchVal . '"  data-value="' . $masterID . '" data-id="' . $id . '"';
            $returnData = '<td> '.$description.'</td>';
            $returnData .= '<td><input type="checkbox" name="upDateColumnFamily[]" ' . $dataApp . ' class="approveChk-family"/></td>';
            return $returnData;
        }
        else if($isDrop == 2 && !empty($new_array[0])){
            $date = format_date_dob($new_array[0]['columnVal']);
            $date2 = $new_array[0]['columnVal'];

            $dataApp = ' value="' . $searchVal . '"  data-value="' . $date2 . '"  data-id="' . $id . '"';
            $returnData = '<td> ' . $date . '</td>';
            $returnData .= '<td><input type="checkbox" name="upDateColumnFamily[]" ' . $dataApp . ' class="approveChk-family"/></td>';
            return $returnData;
        }
        else{
            $returnData = null;
            if (!empty($new_array[0])) {
                $description = $new_array[0]['columnVal'];

                $dataApp = ' value="' . $searchVal . '"  data-value="' . $description . '"  data-id="' . $id . '"';
                $returnData = '<td> ' . $description .'</td>';
                $returnData .= '<td><input type="checkbox" name="upDateColumnFamily[]" ' . $dataApp . ' class="approveChk-family"/></td>';
            }

            return $returnData;
        }
    }
}

if (!function_exists('get_DataEmployee')) {
    function get_DataEmployee($empID, $searchCol)
    {
        $CI =& get_instance();
        $responseData = $CI->db->query("SELECT {$searchCol} AS searchVal FROM srp_employeesdetails
                                        WHERE EIdNo = {$empID}")->row('searchVal');
        return $responseData;
    }
}

if (!function_exists('get_nationality')) {
    function get_nationality()
    {
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->select('*');
        $CI->db->from('countrymaster');
        $CI->db->order_by('countryName', 'ASC');
        return $CI->db->get()->result_array();
    }
}