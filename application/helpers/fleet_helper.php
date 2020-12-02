<?php

if (!function_exists('load_vehicles')) {
    function load_vehicles()
    {
        $CI =& get_instance();
        $CI->db->SELECT("fuelBodyID,description");
        $CI->db->FROM('fleet_fuel_body');
        $CI->db->order_by('description');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Vehicle');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['fuelBodyID'])] = trim($row['description']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('load_vehicle_color')) {
    function load_vehicle_color()
    {
        $CI =& get_instance();
        $CI->db->SELECT("colourID,description");
        $CI->db->FROM('fleet_colour');
        $CI->db->order_by('description');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Vehicle Color');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['colourID'])] = trim($row['description']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('load_vehicle_brand')) {
    function load_vehicle_brand()
    {
        $CI =& get_instance();
        $CI->db->SELECT("brandID,description");
        $CI->db->FROM('fleet_brand_master');
        $CI->db->WHERE('companyID',current_companyID());
        $CI->db->order_by('description');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Brand');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['brandID'])] = trim($row['description']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('load_vehicle_model')) {
    function load_vehicle_model()
    {
        $CI =& get_instance();
       // $brand = trim($CI->input->post('vehicleMasterID'));

        $CI->db->SELECT("modelID,description");
        $CI->db->FROM('fleet_brand_model');
        $CI->db->order_by('description');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Model');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['modelID'])] = trim($row['description']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('load_fuel_type')) {
    function load_fuel_type()
    {
        $CI =& get_instance();
        $CI->db->SELECT("fuelTypeID,description");
        $CI->db->FROM('fleet_fuel_type');
        $CI->db->order_by('description');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Fuel');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['fuelTypeID'])] = trim($row['description']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('vehicle_active_status')) {
    function vehicle_active_status($active)
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

if (!function_exists('action_vehicleMaster')) {
    function action_vehicleMaster($vehicleMasterID, $VehicleNo)
    {
        $CI =& get_instance();
        $CI->load->library('session');
        $VehicleNo = "'" . $VehicleNo . "'";

        $CI->db->select('*');
        $CI->db->from('fleet_fuelusagedetails');
        $CI->db->where('vehicleMasterID', $vehicleMasterID);
        $datas = $CI->db->get()->row_array();

        if($datas) {
            $action = '<a href="#"
                               onclick="fetchPage(\'system/Fleet_Management/load_Vehicle_edit_view\',' . $vehicleMasterID . ',\'Edit Asset  \',' . $VehicleNo . ')"><span
                                        title="Edit" rel="tooltip"
                                        class="glyphicon glyphicon-pencil"></span> &nbsp;&nbsp;|&nbsp;&nbsp';

            $action .= '<a href="#"
                                   onclick="fetchPage(\'system/Fleet_Management/fleet_saf_vehicleView\',' . $vehicleMasterID . ',\'View Details\')"><span
                                        title="" rel="tooltip" class="glyphicon glyphicon-eye-open"
                                        data-original-title="View"></span></a>';


            return '<span class="pull-right">' . $action . '</span>';
        }
        else{
            $action = '<a href="#"
                               onclick="fetchPage(\'system/Fleet_Management/load_Vehicle_edit_view\',' . $vehicleMasterID . ',\'Edit Asset \',' . $VehicleNo . ')"><span
                                        title="Edit" rel="tooltip"
                                        class="glyphicon glyphicon-pencil"></span> &nbsp;&nbsp;|&nbsp;&nbsp';

            $action .= '<a href="#"
                                   onclick="fetchPage(\'system/Fleet_Management/fleet_saf_vehicleView\',' . $vehicleMasterID . ',\'View Details\')"><span
                                        title="" rel="tooltip" class="glyphicon glyphicon-eye-open"
                                        data-original-title="View"></span></a>';

            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp; <a onclick="delete_vehicle(' . $vehicleMasterID . ', ' . $VehicleNo . ')">';
            $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';

            return '<span class="pull-right">' . $action . '</span>';
        }

    }
}

if (!function_exists('action_fuelMaster')) {
    function action_fuelMaster($fuelTypeID)
    {
        $CI =& get_instance();
        $CI->load->library('session');

      //  $data = $CI->db->query("SELECT * FROM srp_erp_bloodgrouptype")->result_array();
        $CI->db->select('*');
        $CI->db->from('fleet_vehiclemaster');
        $CI->db->where('fuelTypeID', $fuelTypeID);
        $datas = $CI->db->get()->row_array();
        if ($datas) {
            $action = '<a href="#"
                               onclick="fetchPage(\'system/Fleet_Management/fleet_saf_fuelMaster\',' . $fuelTypeID . ',\'\')">';

            $action .= '<a onclick="edit_fuel(' . $fuelTypeID . ')">';
            $action .= '<span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';

            return '<span class="pull-right">' . $action . '</span>';
        } else {
            $action = '<a href="#"
                               onclick="fetchPage(\'system/Fleet_Management/fleet_saf_fuelMaster\',' . $fuelTypeID . ',\'\')">';

            $action .= '<a onclick="edit_fuel(' . $fuelTypeID . ')">';
            $action .= '<span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span>';
            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp; <a onclick="delete_fuel(' . $fuelTypeID . ')">';
            $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';

            return '<span class="pull-right">' . $action . '</span>';

        }
    }
}


if (!function_exists('driver_active_status')) {
    function driver_active_status($active)
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

if (!function_exists('action_driverMaster')) {
    function action_driverMaster($driverMasID, $driverName)
    {
        $driverName = "'" . $driverName . "'";
       // $action = '<a onclick="edit_driver(' . $driverMasID . ', this)"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';

        $action = '<a href="#"
                               onclick="fetchPage(\'system/Fleet_Management/load_Driver_edit_view\',' .$driverMasID. ',\'Edit Driver - \')"><span
                                        title="Edit" rel="tooltip"
                                        class="glyphicon glyphicon-pencil"></span> &nbsp;&nbsp;|&nbsp;&nbsp';

        $action .= '<a href="#"
                                   onclick="fetchPage(\'system/Fleet_Management/fleet_saf_driverView\',' .$driverMasID. ',\'View Details\')"><span
                                        title="" rel="tooltip" class="glyphicon glyphicon-eye-open"
                                        data-original-title="View"></span></a>';


        $action .= '&nbsp;&nbsp;|&nbsp;&nbsp; <a onclick="delete_driver(' . $driverMasID . ', ' . $driverName . ')">';
        $action .= '<span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';

        return '<span class="pull-right">' . $action . '</span>';

    }
}

if (!function_exists('load_bloodGroup')) {
    function load_bloodGroup()
    {
        $CI =& get_instance();
        $data = $CI->db->query("SELECT * FROM srp_erp_bloodgrouptype")->result_array();
        return $data;
    }
}

/* ----------------------  Transaction ------------------------*/

if (!function_exists('fetch_all_segment')) {
    function fetch_all_segment()
    {
        $CI =& get_instance();
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

if (!function_exists('fuel_supplier_drop')) {
    function fuel_supplier_drop()
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
        $CI->db->select("supplierAutoID,supplierSystemCode,supplierName");
        $CI->db->from('srp_erp_suppliermaster');
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);

        $customer = $CI->db->get()->result_array();
            $customer_arr = array('' => $CI->lang->line('common_select_supplier'));
            if (isset($customer)) {
                foreach ($customer as $row) {
                    $customer_arr[trim($row['supplierAutoID'])] =  trim($row['supplierSystemCode']) . ' | ' . trim($row['supplierName']);
                }
            }
        return $customer_arr;
    }
}

if (!function_exists('fetch_all_vehicle')){
    function fetch_all_vehicle()
    {
        $CI =& get_instance();
        $CI->db->SELECT("vehicleMasterID,vehicleCode,VehicleNo,fuel_type_description");
        $CI->db->FROM('fleet_vehiclemaster');
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $CI->db->where('isActive', 1);
        $CI->db->order_by('vehicleCode');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Vehicle');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['vehicleMasterID'])] = trim($row['vehicleCode']) . '|' . trim($row['VehicleNo']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('fetch_all_drivers')){
    function fetch_all_drivers()
    {
        $CI =& get_instance();
        $CI->db->SELECT("driverMasID,driverCode,driverName");
        $CI->db->FROM('fleet_drivermaster');
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $CI->db->where('isActive', 1);
        $CI->db->order_by('driverCode');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Driver');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['driverMasID'])] = trim($row['driverCode']) . '|' . trim($row['driverName']);
            }
        }
        return $data_arr;
    }
}


if (!function_exists('employee_drop')) {
    function employee_drop()
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
        $CI->db->select("EIdNo,Ename2");
        $CI->db->from('srp_employeesdetails');
        $CI->db->where('Erp_companyID', $CI->common_data['company_data']['company_id']);

        $customer = $CI->db->get()->result_array();
        $customer_arr = array('' => $CI->lang->line('common_select_employee'));
        if (isset($customer)) {
            foreach ($customer as $row) {
                $customer_arr[trim($row['EIdNo'])] = trim($row['Ename2']);
            }
        }
        return $customer_arr;
    }
}

if (!function_exists('fetch_gl_categories')) {
    function fetch_gl_categories()
    {
        $CI =& get_instance();
        $CI->db->SELECT("glConfigAutoID,glConfigDescription");
        $CI->db->from('fleet_glconfiguration');

        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Category');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['glConfigAutoID'])] = trim($row['glConfigDescription']);
            }
        }

        return $data_arr;
    }
}

if (!function_exists('action_fuel_usage')) {
    function action_fuel_usage($fuelusageID,$confirmedYN,$isDeleted, $approvedYN,$createdUserID)
    {
        $CI =& get_instance();
        $CI->load->library('session');
        $status = '<span class="pull-right">';

        if($isDeleted==1){
            $status .= '<a onclick="reOpen_fuel_usage(' . $fuelusageID .');"><span title="Re Open" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }

        if ($confirmedYN != 1 && $isDeleted==0) {
            $status .= '<a onclick=\'fetchPage("system/Fleet_Management/fleet_saf_newFuelUsage",' . $fuelusageID . ',"Edit Fuel Usage","FU"); \'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';
        }

        if ($createdUserID == trim($CI->session->userdata("empID")) and $approvedYN == 0 and $confirmedYN == 1 && $isDeleted==0) {
            $status .= '<a onclick="referbackFuelUsage(' . $fuelusageID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }

        $status .= '<a target="_blank" onclick="documentPageView_modal(\'FU\',\'' . $fuelusageID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>';

        $status .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a target="_blank" href="' . site_url('fleet/load_fleet_fuel_comfirmation') . '/' . $fuelusageID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>';
        if ($confirmedYN != 1 && $isDeleted==0) {
            $status .= '&nbsp;|&nbsp;<a onclick="delete_document(' . $fuelusageID . ',\'Return\');"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';
        }
        $status .= '</span>';
        return $status;



    }

    if (!function_exists('fuel_usage_approval_action')) {
        function fuel_usage_approval_action($fuelusageID, $approvalLevelID, $approvedYN, $documentApprovedID, $documentID)
        {
            $status = '<span class="pull-right">';
            if ($approvedYN == 0) {
                $status .= '<a onclick=\'fetch_approval("' . $fuelusageID . '","' . $documentApprovedID . '","' . $approvalLevelID . '"); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>&nbsp;&nbsp;';
            } else {
                $status .= '<a target="_blank" onclick="PageView_modal(\'FU\',\'' . $fuelusageID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;';
            }

            // $status .= '<a target="_blank" href="' . site_url('Bank_rec/bank_transfer_view/') . '/' . $fuelusageID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>';

            $status .= '</span>';

            return $status;
        }
    }
}


/* ===================================  */

if (!function_exists('fetch_supplier_drop')) {
    function fetch_supplier_drop($status = true)
    {
        $CI =& get_instance();
        $CI->db->SELECT("supplierAutoID,supplierName");
        $CI->db->FROM('srp_erp_suppliermaster');
        $CI->db->WHERE('companyID', current_companyID());
        $CI->db->order_by('supplierAutoID', 'ASC');
        $donor = $CI->db->get()->result_array();
        if ($status) {
            $supp_arr = array('' => 'Select Supplier');
        } else {
            $supp_arr = '';
        }
        if (isset($donor)) {
            foreach ($donor as $row) {
                $supp_arr[trim($row['supplierAutoID'])] = trim($row['supplierName']);
            }
        }
        return $supp_arr;
    }
}
 /* GL Configuration */

if (!function_exists('load_gl_config_table_action')) { /*get po action list*/
    function load_gl_config_table_action($glConfigAutoID)
    {
        $CI =& get_instance();
        $CI->load->library('session');

        $CI->db->select('*');
        $CI->db->from('fleet_fuelusagedetails');
        $CI->db->where('glConfigAutoID', $glConfigAutoID);
        $datas = $CI->db->get()->row_array();
        if(empty($datas)) {
            $status = '<span class="pull-right">';
            $status .= '<a onclick="editGLconfig(' . $glConfigAutoID . ')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a> &nbsp &nbsp|&nbsp &nbsp <a onclick="deleteGLconfig(' . $glConfigAutoID . ')"><span style="color:rgb(209, 91, 71);" title="Edit" rel="tooltip" class="glyphicon glyphicon-trash"></span></a>';
            $status .= '</span>';
            return $status;
        } else {
            $status = '<span class="pull-right">';
            $status .= '<a onclick="editGLconfig(' . $glConfigAutoID . ')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
            $status .= '</span>';
            return $status;
        }
    }
}
if (!function_exists('load_all_assets')) {
    function load_all_assets()
    {
        $CI =& get_instance();
        $CI->db->SELECT("faID,faCode,assetDescription");
        $CI->db->FROM('srp_erp_fa_asset_master');
        $CI->db->WHERE('companyID',current_companyID());
        $output = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Asset');
        if (isset($output)) {
            foreach ($output as $row) {
                $data_arr[trim($row['faID'])] = trim($row['faCode']) . ' | ' . trim($row['assetDescription']);
            }
        }

        return $data_arr;
    }
}
if (!function_exists('load_all_maintenacecompany')) {
    function load_all_maintenacecompany()
    {
        $CI =& get_instance();
        $CI->db->SELECT("maintenance_id,company_name,status");
        $CI->db->FROM('fleet_maintenance_company');
        $CI->db->WHERE('status',1);
        $output = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Maintenace Company');
        if (isset($output)) {
            foreach ($output as $row) {
                $data_arr[trim($row['maintenance_id'])] = trim($row['company_name']);
            }
        }

        return $data_arr;
    }
}

if (!function_exists('load_all_maintenacetype')) {
    function load_all_maintenacetype()
    {
        $CI =& get_instance();
        $CI->db->SELECT("maintenanceTypeID,type");
        $CI->db->FROM('fleet_maintenancetype');
        $output = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Maintenace Type');
        if (isset($output)) {
            foreach ($output as $row) {
                $data_arr[trim($row['maintenanceTypeID'])] = trim($row['type']);
            }
        }

        return $data_arr;
    }
}
if (!function_exists('load_all_maintenacecriteria')) {
    function load_all_maintenacecriteria()
    {
        $CI =& get_instance();
        $CI->db->SELECT("maintenanceCriteriaID,maintenanceCriteria,status");
        $CI->db->WHERE('status',1);
        $CI->db->WHERE('companyID',$CI->common_data['company_data']['company_id']);
        $CI->db->FROM('fleet_maintenance_criteria');
        $output = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Maintenace Criteria');
        if (isset($output)) {
            foreach ($output as $row) {
                $data_arr[trim($row['maintenanceCriteriaID'])] = trim($row['maintenanceCriteria']);
            }
        }

        return $data_arr;
    }
}
if (!function_exists('all_maintenancecompany_drop')) {
    function all_maintenancecompany_drop($status = TRUE)/*Load all Supplier*/
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);

        $CI->db->select("supplierAutoID,supplierName,supplierSystemCode,supplierCountry");
        $CI->db->from('srp_erp_suppliermaster');
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $supplier = $CI->db->get()->result_array();
        if ($status) {
            $supplier_arr = array('' => 'Select Maintenace Company');
        } else {
            $supplier_arr = '';
        }
        if (isset($supplier)) {
            foreach ($supplier as $row) {
                $supplier_arr[trim($row['supplierAutoID'])] = (trim($row['supplierSystemCode']) ? trim($row['supplierSystemCode']) . ' | ' : '') . trim($row['supplierName']) . (trim($row['supplierCountry']) ? ' | ' . trim($row['supplierCountry']) : '');
            }
        }

        return $supplier_arr;
    }
}
if (!function_exists('load_all_crew')) {
    function load_all_crew()
    {
        $CI =& get_instance();
        $CI->db->SELECT("crewTypeID,Description,");
        $CI->db->FROM('fleet_maintenancecrewtype');
        $output = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Maintenace Crew');
        if (isset($output)) {
            foreach ($output as $row) {
                $data_arr[trim($row['crewTypeID'])] = trim($row['Description']);
            }
        }

        return $data_arr;
    }
}


/**
 * Created by PhpStorm.
 * User: Safeena
 * Date: 7/11/2018
 * Time: 9:50 AM
 */