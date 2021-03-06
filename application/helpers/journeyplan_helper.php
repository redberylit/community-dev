<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('fetch_drivers')) {
    function fetch_drivers()
    {
        $CI =& get_instance();

        $CI->db->select('driverMasID,driverCode,driverName');
        $CI->db->from('fleet_drivermaster');
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Driver');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['driverMasID'])] = trim($row['driverCode']) . ' | ' . trim($row['driverName']);
            }

            return $data_arr;
        }
    }


}
if (!function_exists('fetch_vehiclenumber')) {
    function fetch_vehiclenumber()
    {
        $CI =& get_instance();

        $CI->db->select('vehicleMasterID,VehicleNo');
        $CI->db->from('fleet_vehiclemaster');
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Vehicale');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['vehicleMasterID'])] = trim($row['VehicleNo']);
            }

            return $data_arr;
        }
    }
    if (!function_exists('load_jp_action')) {
        function load_jp_action($voucherAutoID, $Level, $approved, $ApprovedID, $documentID, $approval = 1)
        {
            $status = '<span class="pull-right">';
            $status .= '<a onclick=\'attachment_modal(' . $voucherAutoID . ',"Journey Plan","JP");\'><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
            if ($approved == 0) {
                $status .= '<a onclick=\'fetch_approval("' . $voucherAutoID . '","' . $ApprovedID . '","' . $Level . '"); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>&nbsp;&nbsp;';



            } else {
                $status .= '<a onclick=\'documentPageView_modal("' . $documentID . '","' . $voucherAutoID . '","","' . $approval . '"  ); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;';
            }

            $status .= '</span>';

            return $status;
        }
    }

}
