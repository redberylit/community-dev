<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('receipt_voucher_total_value')) {
    function receipt_voucher_total_value($id, $DecimalPlaces = 2, $status = 1)
    {
        $tax = 0;
        $CI =& get_instance();
        /*$CI->db->select_sum('transactionAmount');
        $CI->db->where('receiptVoucherAutoId', $id);
        $totalAmount = $CI->db->get('srp_erp_customerreceiptdetail')->row('transactionAmount');*/

        $totalAmount = $CI->db->query("SELECT
(((IFNULL(addondet.taxPercentage, 0) / 100) * IFNULL(tyepdet.transactionAmount,0)) + IFNULL(det.transactionAmount, 0) - IFNULL(Creditnots.transactionAmount,0)) AS transactionAmount
FROM
	`srp_erp_customerreceiptmaster`
LEFT JOIN (
	SELECT
		SUM(transactionAmount) AS transactionAmount,
		receiptVoucherAutoId
	FROM
		srp_erp_customerreceiptdetail
	WHERE
		srp_erp_customerreceiptdetail.type != 'creditnote'
	GROUP BY
		receiptVoucherAutoId
) det ON (
	`det`.`receiptVoucherAutoId` = srp_erp_customerreceiptmaster.receiptVoucherAutoId
)
LEFT JOIN (
	SELECT
		SUM(taxPercentage) AS taxPercentage,
		receiptVoucherAutoId
	FROM
		srp_erp_customerreceipttaxdetails
	GROUP BY
		receiptVoucherAutoId
) addondet ON (
	`addondet`.`receiptVoucherAutoId` = srp_erp_customerreceiptmaster.receiptVoucherAutoId
)
LEFT JOIN (
	SELECT
		SUM(transactionAmount) AS transactionAmount,
		receiptVoucherAutoId
	FROM
		srp_erp_customerreceiptdetail
	WHERE
		srp_erp_customerreceiptdetail.type = 'GL'
	OR srp_erp_customerreceiptdetail.type = 'Item'
	GROUP BY
		receiptVoucherAutoId
) tyepdet ON (
	`tyepdet`.`receiptVoucherAutoId` = srp_erp_customerreceiptmaster.receiptVoucherAutoId
)
LEFT JOIN (
	SELECT
		SUM(transactionAmount) AS transactionAmount,
		receiptVoucherAutoId
	FROM
		srp_erp_customerreceiptdetail
	WHERE
		srp_erp_customerreceiptdetail.type = 'creditnote'
	GROUP BY
		receiptVoucherAutoId
) Creditnots ON (
	`Creditnots`.`receiptVoucherAutoId` = srp_erp_customerreceiptmaster.receiptVoucherAutoId
)
LEFT JOIN `srp_erp_customermaster` ON `srp_erp_customermaster`.`customerAutoID` = `srp_erp_customerreceiptmaster`.`customerID`
WHERE
	`srp_erp_customerreceiptmaster`.`receiptVoucherAutoId` = $id")->row('transactionAmount');


       /* $CI->db->select('taxPercentage');
        $CI->db->where('receiptVoucherAutoId', $id);
        $data_arr = $CI->db->get('srp_erp_customerreceipttaxdetails')->result_array();
        for ($i = 0; $i < count($data_arr); $i++) {
            $tax += (($data_arr[$i]['taxPercentage'] / 100) * $totalAmount);
        }
        $totalAmount += $tax;*/
        if ($status) {
            return number_format($totalAmount, $DecimalPlaces);
        } else {
            return $totalAmount;
        }
    }
}

if (!function_exists('cr_invoice_total_value')) {
    function cr_invoice_total_value($id, $DecimalPlaces = 2)
    {
        $CI =& get_instance();
        $CI->db->select_sum('transactionAmount');
        $CI->db->where('creditNoteMasterAutoID', $id);
        $totalAmount = $CI->db->get('srp_erp_creditnotedetail')->row('transactionAmount');
        return number_format($totalAmount, $DecimalPlaces);
    }
}

// if (!function_exists('supplier_invoice_action_approval')) {
//     function supplier_invoice_action_approval($InvoiceAutoID,$Level,$approved,$ApprovedID){
//         $status ='<span class="pull-right">';
//         if ($approved==0){
//             $status .='<a onclick=\'fetch_approval("'.$InvoiceAutoID.'","'.$ApprovedID.'","'.$Level.'"); \'><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';
//         }
//         $status .='<a target="_blank" href="'.site_url('Procurement/load_purchase_order_conformation/').'/'.$InvoiceAutoID.'" ><span class="glyphicon glyphicon-print"></span></a>';
//         $status .='</span>';
//         return $status;
//     }
// }

// if (!function_exists('dn_confirm')) {
//     function dn_confirm($con){
//         $status ='<center>';
//         if ($con==0) {
//             $status .='<span class="label label-danger">&nbsp;</span>';
//         }elseif($con==1) {
//             $status .='<span class="label label-success">&nbsp;</span>';
//         }elseif($con==2) {
//             $status .='<span class="label label-warning">&nbsp;</span>';
//         }else{
//             $status .='-';
//         }
//         $status .='</center>';
//         return $status;
//     }
// }

// if (!function_exists('pv_confirm')) {
//     function pv_confirm($con){
//         $status ='<center>';
//         if ($con==0) {
//             $status .='<span class="label label-danger">&nbsp;</span>';
//         }elseif($con==1) {
//             $status .='<span class="label label-success">&nbsp;</span>';
//         }elseif($con==2) {
//             $status .='<span class="label label-warning">&nbsp;</span>';
//         }else{
//             $status .='-';
//         }
//         $status .='</center>';
//         return $status;
//     }
// }

if (!function_exists('load_credit_note_action')) {
    function load_credit_note_action($dnID, $dnConfirmedYN, $approved, $createdUserID,$isDeleted,$confirmedByEmp)
    {
        $CI =& get_instance();
        $CI->load->library('session');
        $status = '<span class="pull-right">';
        $status .= '<a onclick=\'attachment_modal(' . $dnID . ',"Credit Note","CN",' . $dnConfirmedYN . ');\'><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';

        if($isDeleted==1){
            $status .= '<a onclick="reOpen_contract(' . $dnID .');"><span title="Re Open" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }

        if ($dnConfirmedYN != 1 && $isDeleted==0) {
            $status .= '<a onclick=\'fetchPage("system/accounts_receivable/erp_credit_note",' . $dnID . ',"Edit Credit Note","CN"); \'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }

        if (($createdUserID == trim($CI->session->userdata("empID")) || $confirmedByEmp == trim($CI->session->userdata("empID"))) and $approved == 0 and $dnConfirmedYN == 1 && $isDeleted==0) {
            $status .= '<a onclick="referbackdn(' . $dnID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }

        $status .= '<a target="_blank" onclick="documentPageView_modal(\'CN\',\'' . $dnID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';

        $status .= '<a target="_blank" href="' . site_url('Receivable/load_cn_conformation/') . '/' . $dnID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>';

        if ($dnConfirmedYN != 1 && $isDeleted==0) {
            $status .= '&nbsp;&nbsp;| &nbsp;&nbsp;<a onclick="delete_item(' . $dnID . ',\'Credit Note\');"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';
        }
        $status .= '</span>';
        return $status;
    }
}


// if (!function_exists('load_pv_action')) {
//     function load_pv_action($pvID,$pvConfirmedYN,$approved,$createdUserID){
//         $CI =& get_instance();
//         $CI->load->library('session');
//         $status ='<span class="pull-right">';
//         if ($createdUserID==trim($CI->session->userdata("empID")) and $approved == 0 and $pvConfirmedYN ==1) {
//             $status .='<a onclick="referbackgrv('.$pvID.');"><span class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
//         }

//         if ($pvConfirmedYN!=1) {
//             $status .='<a onclick=\'fetchPage("system/payment_voucher/erp_payment_voucher",'.$pvID.',"Add Payment Voucher","PV"); \'><span class="glyphicon glyphicon-pencil"></span></a>';
//             $status .='&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a target="_blank" href="'.site_url('Payment_voucher/load_pv_conformation/').'/'.$pvID.'" ><span class="glyphicon glyphicon-print"></span></a> &nbsp;&nbsp;| &nbsp;&nbsp;';
//             $status .='<a onclick="delete_pv_item('.$pvID.',\'Payment Voucher\');"><span class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';
//         }
//         if ($pvConfirmedYN!=0){
//             $status .='<a target="_blank" href="'.site_url('Payment_voucher/load_pv_conformation').'/'.$pvID.'" ><span class="glyphicon glyphicon-print"></span></a>';
//         }
//         $status .='</span>';
//         return $status;
//     }
// }

if (!function_exists('cn_action_approval')) {
    function cn_action_approval($debitNoteMasterAutoID=null, $Level=null, $approved=null, $ApprovedID=null, $isRejected=null,$approval=1)
    {
        $status = '<span class="pull-right">';
        $status .= '<a onclick=\'attachment_modal(' . $debitNoteMasterAutoID . ',"Credit Note","CN");\'><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp; ';
        if ($approved == 0) {
            $status .= '| &nbsp;&nbsp;<a onclick=\'fetch_approval("' . $debitNoteMasterAutoID . '","' . $ApprovedID . '","' . $Level . '"); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>&nbsp;&nbsp;';
        }else{
            $status .= '| &nbsp;&nbsp;<a target="_blank" onclick="documentPageView_modal(\'CN\',\'' . $debitNoteMasterAutoID . '\',\' \',\'' . $approval . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;';
        }

           // $status .= '| &nbsp;&nbsp;<a target="_blank" href="' . site_url('Receivable/load_cn_conformation/') . '/' . $debitNoteMasterAutoID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>';
        $status .= '</span>';
        return $status;
    }
}

// if (!function_exists('pv_confirm')) {
//     function pv_confirm($con){
//         $status ='<center>';
//         if ($con==0) {
//             $status .='<span class="label label-danger">&nbsp;</span>';
//         }elseif($con==1) {
//             $status .='<span class="label label-success">&nbsp;</span>';
//         }elseif($con==2) {
//             $status .='<span class="label label-warning">&nbsp;</span>';
//         }else{
//             $status .='-';
//         }
//         $status .='</center>';
//         return $status;
//     }
// }

// if (!function_exists('pv_action_approval')) { /*get po action list*/
//     function pv_action_approval($PayVoucherAutoId,$Level,$approved,$ApprovedID){
//         $status ='<span class="pull-right">';
//         if ($approved==0){
//             $status .='<a onclick=\'fetch_approval("'.$PayVoucherAutoId.'","'.$ApprovedID.'","'.$Level.'"); \'><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';
//         }
//         $status .='<a target="_blank" href="'.site_url('Payment_voucher/load_pv_conformation/').'/'.$PayVoucherAutoId.'" ><span class="glyphicon glyphicon-print"></span></a>';
//         $status .='</span>';
//         return $status;
//     }
// }