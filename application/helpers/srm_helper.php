<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*DO NOT USE THIS THIS IS AN EXAMPLE  */
if (!function_exists('load_all_custer_drop')) {
    function load_all_custer_drop()
    {
        $CI =& get_instance();
        $CI->db->select("CustomerName");
        $CI->db->from('srp_erp_srm_customermaster');
        $result = $CI->db->get()->result_array();
        $customer_err = array('' => 'Select Status');
        if (!empty($result)) {
            foreach ($result as $row) {
                $customer_err[trim($row['customerID'])] = (trim($row['customerName']));
            }
        }
        return $customer_err;
    }
}

if (!function_exists('all_srm_customer_drop')) {
    function all_srm_customer_drop($status = true) /*Load all Supplier*/
    {
        $CI =& get_instance();
        $CI->db->select("CustomerAutoID,CustomerName");
        $CI->db->from('srp_erp_srm_customermaster');
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $customer = $CI->db->get()->result_array();
        if ($status) {
            $customer_arr = array('' => 'Select Customer');
        } else {
            $customer_arr = '';
        }
        if (isset($customer)) {
            foreach ($customer as $row) {
                $customer_arr[trim($row['CustomerAutoID'])] = (trim($row['CustomerName']));
            }
        }
        return $customer_arr;
    }
}

if (!function_exists('all_srm_Currency_drop')) {
    function all_srm_Currency_drop($status = true) /*Load all Supplier*/
    {
        $CI =& get_instance();
        $CI->db->select("CustomerAutoID,customerCurrency");
        $CI->db->from('srp_erp_srm_customermaster');
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $customer = $CI->db->get()->result_array();
        if ($status) {
            $currncy_arr = array('' => 'Select Customer');
        } else {
            $currncy_arr = '';
        }
        if (isset($customer)) {
            foreach ($customer as $row) {
                $currncy_arr[trim($row['CustomerAutoID'])] = (trim($row['customerCurrency']));
            }
        }
        return $currncy_arr;
    }
}


if (!function_exists('all_srm_supplier_drop')) {
    function all_srm_supplier_drop($status = true) /*Load all Supplier*/
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->from('srp_erp_srm_suppliermaster');
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $supplier = $CI->db->get()->result_array();

        if ($status) {
            $supplier_arr = array('' => 'Select supplier');
        } else {
            $supplier_arr = '';
        }
        if (isset($supplier)) {
            foreach ($supplier as $row) {
                $supplier_arr[trim($row['supplierAutoID'])] = (trim($row['supplierName']));
            }
        }

        return $supplier_arr;
    }
}

if (!function_exists('all_srm_supplie_Currency_drop')) {
    function all_srm_supplie_Currency_drop($status = true) /*Load all Supplier*/
    {
        $CI =& get_instance();
        $CI->db->select("supplierAutoID,supplierCurrency");
        $CI->db->from('srp_erp_srm_suppliermaster');
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $customer = $CI->db->get()->result_array();
        if ($status) {
            $currncy_arr = array('' => 'Select currency');
        } else {
            $currncy_arr = '';
        }
        if (isset($customer)) {
            foreach ($customer as $row) {
                $currncy_arr[trim($row['supplierAutoID'])] = (trim($row['supplierCurrency']));
            }
        }
        return $currncy_arr;
    }
}

/*Load all countries for select2 - Added by Nazir*/
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

/*Load all SRM Customers for select2*/
if (!function_exists('all_srm_customers')) {
    function all_srm_customers($status = true)
    {
        $CI =& get_instance();
        $CI->db->SELECT("CustomerAutoID,CustomerName");
        $CI->db->FROM('srp_erp_srm_customermaster');
        $CI->db->where('isActive', 1);
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $data = $CI->db->get()->result_array();
        if ($status) {
            $data_arr = array('' => 'Select Customer');
        } else {
            $data_arr = '';
        }
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['CustomerAutoID'])] = trim($row['CustomerName']);
            }
        }
        return $data_arr;
    }
}

/*Load all campaign status*/
if (!function_exists('all_customer_order_status')) {
    function all_customer_order_status($custom = true)
    {
        $CI =& get_instance();
        $CI->db->select("statusID,description,documentID");
        $CI->db->from('srp_erp_srm_status');
        $CI->db->where('documentID', 3);
        $CI->db->where('isActive', 1);
        //$CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $status = $CI->db->get()->result_array();
        if ($custom) {
            $status_arr = array('' => 'Select Status');
        } else {
            $status_arr = array('' => 'Status');
        }
        if (isset($status)) {
            foreach ($status as $row) {
                $status_arr[trim($row['statusID'])] = (trim($row['description']));
            }
        }
        return $status_arr;
    }
}
/*Load all order inquiry reviews*/
if (!function_exists('all_order_inquiries')) {
    function all_order_inquiries($custom = true)
    {
        $CI =& get_instance();
        $CI->db->select("inquiryID,documentCode");
        $CI->db->from('srp_erp_srm_orderinquirydetails');
        $CI->db->join('srp_erp_srm_orderinquirymaster', 'srp_erp_srm_orderinquirymaster.inquiryID = srp_erp_srm_orderinquirydetails.inquiryMasterID');
        $CI->db->where('isSupplierSubmited', 1);
        $CI->db->where('srp_erp_srm_orderinquirydetails.companyID', $CI->common_data['company_data']['company_id']);
        $inquiry = $CI->db->get()->result_array();
        $inquiry_arr = array('' => 'Select Inquiry');
        if (isset($inquiry)) {
            foreach ($inquiry as $row) {
                $inquiry_arr[trim($row['inquiryID'])] = (trim($row['documentCode']));
            }
        }
        return $inquiry_arr;
    }
}







