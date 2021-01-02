<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('load_iou_action')) {
    function load_iou_action($voucherAutoID, $Level, $approved, $ApprovedID, $documentID, $approval = 1)
    {
        $status = '<span class="pull-right">';
        $status .= '<a onclick=\'attachment_modal(' . $voucherAutoID . ',"Voucher","IOU");\'><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        if ($approved == 0) {
            $status .= '<a onclick=\'fetch_approval("' . $voucherAutoID . '","' . $ApprovedID . '","' . $Level . '"); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>&nbsp;&nbsp;';

        } else {
            $status .= '<a onclick=\'documentPageView_modal("' . $documentID . '","' . $voucherAutoID . '","","' . $approval . '"  ); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;';
        }

        $status .= '</span>';

        return $status;
    }
}
if (!function_exists('fetch_claim_category_iou')) {
    function fetch_claim_category_iou($id = FALSE, $state = TRUE) /*$id parameter is used to display only ID as value in select option*/
    {
        $CI =& get_instance();

        $CI->db->select('expenseClaimCategoriesAutoID,glCode,claimcategoriesDescription');
        $CI->db->from('srp_erp_expenseclaimcategories');
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $CI->db->where('type', 2);
        $data = $CI->db->get()->result_array();
        if ($state == TRUE) {
            $data_arr = array('' => 'Select Claim Category');
        } else {
            $data_arr = '';
        }
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['expenseClaimCategoriesAutoID'])] = trim($row['glCode']) . ' | ' . trim($row['claimcategoriesDescription']);
            }

            return $data_arr;
        }
    }


}


if (!function_exists('fetch_users_iou')) {
    function fetch_users_iou($status = TRUE, $isDischarged = 0)
    {
        {
            $CI =& get_instance();
            $companyID = current_companyID();
            if ($status == TRUE) {
                $customer = $CI->db->query("SELECT CONCAT(EIdNo,'|','1') as emp,`ECode`,`Ename2` FROM `srp_employeesdetails` WHERE `Erp_companyID` = {$companyID} AND `isDischarged` != 1 UNION  select 	CONCAT(userID,'|','2') as emp,userCode as ECode,userName as Ename2 from  srp_erp_iouusers  where  companyID  = {$companyID} AND isActive = 1")->result_array();
            } else {
                $customer = $CI->db->query("SELECT CONCAT (EIdNo,'|','1') as emp,`ECode`,`Ename2` FROM `srp_employeesdetails` WHERE `Erp_companyID` = {$companyID} AND `isDischarged` != 1 ")->result_array();
            }

            $customer_arr = array('' => 'Select Employee');
            if (isset($customer)) {
                foreach ($customer as $row) {
                    $customer_arr[trim($row['emp'])] = trim($row['ECode']) . ' | ' . trim($row['Ename2']);
                }
            }
        }
        return $customer_arr;
    }


}