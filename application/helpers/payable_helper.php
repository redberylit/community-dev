<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('supplier_invoice_total_value')) {
    function supplier_invoice_total_value($id, $DecimalPlaces = 2)
    {
        $tax = 0;
        $CI =& get_instance();
        $CI->db->select_sum('transactionAmount');
        $CI->db->where('InvoiceAutoID', $id);
        $totalAmount = $CI->db->get('srp_erp_paysupplierinvoicedetail')->row('transactionAmount');
        $CI->db->select('taxPercentage');
        $CI->db->where('InvoiceAutoID', $id);
        $data_arr = $CI->db->get('srp_erp_paysupplierinvoicetaxdetails')->result_array();
        for ($i = 0; $i < count($data_arr); $i++) {
            $tax += (($data_arr[$i]['taxPercentage'] / 100) * $totalAmount);
        }
        $totalAmount += $tax;
        return number_format($totalAmount, $DecimalPlaces);
    }
}

if (!function_exists('db_invoice_total_value')) {
    function db_invoice_total_value($id, $DecimalPlaces = 2)
    {
        $CI =& get_instance();
        $CI->db->select_sum('transactionAmount');
        $CI->db->where('debitNoteMasterAutoID', $id);
        $totalAmount = $CI->db->get('srp_erp_debitnotedetail')->row('transactionAmount');
        return number_format($totalAmount, $DecimalPlaces);
    }
}

if (!function_exists('payment_voucher_total_value')) {
    function payment_voucher_total_value($id, $DecimalPlaces = 2, $status = 1)
    {
        $tax = 0;
        $CI =& get_instance();
        /*$CI->db->select_sum('transactionAmount');
        $CI->db->where('payVoucherAutoId', $id);
        $totalAmount = $CI->db->get('srp_erp_paymentvoucherdetail')->row('transactionAmount');
        $CI->db->select('taxPercentage');
        $CI->db->where('payVoucherAutoId', $id);
        $data_arr = $CI->db->get('srp_erp_paymentvouchertaxdetails')->result_array();
        for ($i = 0; $i < count($data_arr); $i++) {
            $tax += (($data_arr[$i]['taxPercentage'] / 100) * $totalAmount);
        }
        $totalAmount += $tax;*/

        $totalAmount = $CI->db->query("SELECT
(((IFNULL(addondet.taxPercentage, 0) / 100) * IFNULL(tyepdet.transactionAmount,0)) + IFNULL(det.transactionAmount, 0) - IFNULL(debitnote.transactionAmount,0)) AS transactionAmount
FROM
	`srp_erp_paymentVouchermaster`
LEFT JOIN (
	SELECT
		SUM(transactionAmount) AS transactionAmount,
		payVoucherAutoId
	FROM
		srp_erp_paymentvoucherdetail
	WHERE
		srp_erp_paymentvoucherdetail.type != 'debitnote'
	GROUP BY
		payVoucherAutoId
) det ON (
	`det`.`payVoucherAutoId` = srp_erp_paymentVouchermaster.payVoucherAutoId
)
LEFT JOIN (
	SELECT
		SUM(transactionAmount) AS transactionAmount,
		payVoucherAutoId
	FROM
		srp_erp_paymentvoucherdetail
	WHERE
		srp_erp_paymentvoucherdetail.type = 'GL'
	OR srp_erp_paymentvoucherdetail.type = 'Item'
	GROUP BY
		payVoucherAutoId
) tyepdet ON (
	`tyepdet`.`payVoucherAutoId` = srp_erp_paymentVouchermaster.payVoucherAutoId
)
LEFT JOIN (
	SELECT
		SUM(transactionAmount) AS transactionAmount,
		payVoucherAutoId
	FROM
		srp_erp_paymentvoucherdetail
	WHERE
		srp_erp_paymentvoucherdetail.type = 'debitnote'
	GROUP BY
		payVoucherAutoId
) debitnote ON (
	`debitnote`.`payVoucherAutoId` = srp_erp_paymentVouchermaster.payVoucherAutoId
)
LEFT JOIN (
	SELECT
		SUM(taxPercentage) AS taxPercentage,
		payVoucherAutoId
	FROM
		srp_erp_paymentvouchertaxdetails
	GROUP BY
		payVoucherAutoId
) addondet ON (
	`addondet`.`payVoucherAutoId` = srp_erp_paymentVouchermaster.payVoucherAutoId
)
WHERE
	`srp_erp_paymentVouchermaster`.`payVoucherAutoId` = $id
")->row('transactionAmount');/*AND `pvType` <> 'SC'*/



        if ($status) {
            return number_format($totalAmount, $DecimalPlaces);
        } else {
            return $totalAmount;
        }
    }
}

if (!function_exists('payment_match_total_value')) {
    function payment_match_total_value($id, $DecimalPlaces = 2, $status = 1)
    {
        $CI =& get_instance();
        $CI->db->select_sum('transactionAmount');
        $CI->db->where('matchID', $id);
        $totalAmount = $CI->db->get('srp_erp_pvadvancematchdetails')->row('transactionAmount');
        if ($status) {
            return number_format($totalAmount, $DecimalPlaces);
        } else {
            return $totalAmount;
        }
    }
}

if (!function_exists('receipt_match_total_value')) {
    function receipt_match_total_value($id, $DecimalPlaces = 2, $status = 1)
    {
        $CI =& get_instance();
        $CI->db->select_sum('transactionAmount');
        $CI->db->where('matchID', $id);
        $totalAmount = $CI->db->get('srp_erp_rvadvancematchdetails')->row('transactionAmount');
        if ($status) {
            return number_format($totalAmount, $DecimalPlaces);
        } else {
            return $totalAmount;
        }
    }
}

if (!function_exists('payable_confirm')) {
    function payable_confirm($con,$code, $autoID)
    {
        $status = '<center>';
        if ($con == 0) {
            $status .= '<span class="label label-danger">&nbsp;</span>';
        } elseif ($con == 1) {
            $status .= '<span class="label label-success">&nbsp;</span>';
        } elseif ($con == 2) {
            $status .= '<span class="label label-warning">&nbsp;</span>';
        } else {
            $status .= '-';
        }
        $status .= '</center>';
        return $status;
    }
}

if (!function_exists('load_supplier_invoice_action')) { /*get po action list*/
    function load_supplier_invoice_action($masterID, $ConfirmedYN, $approved, $createdUserID,$isDeleted,$confirmedByEmp)
    {
        $CI =& get_instance();
        $CI->load->library('session');
        $status = '<span class="pull-right">';
        $status .= '<a onclick=\'attachment_modal(' . $masterID . ',"Supplier Invoice","BSI",' . $ConfirmedYN . ');\'><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';

        if($isDeleted==1){
            $status .= '<a onclick="reOpen_contract(' . $masterID .');"><span title="Re Open" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }

        if ($ConfirmedYN != 1 && $isDeleted==0) {
            $status .= '<a onclick=\'fetchPage("system/accounts_payable/erp_supplier_invoices",' . $masterID . ',"Edit Supplier Invoice","BSI"); \'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }

        if (($createdUserID == trim($CI->session->userdata("empID")) || $confirmedByEmp == trim($CI->session->userdata("empID")))  and $approved == 0 and $ConfirmedYN == 1 && $isDeleted==0) {
            $status .= '<a onclick="referbacksupplierinvoice(' . $masterID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }

        $status .= '<a target="_blank" onclick="documentPageView_modal(\'BSI\',\'' . $masterID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';

        $status .= '<a target="_blank" href="' . site_url('Payable/load_supplier_invoice_conformation/') . '/' . $masterID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>';

        if ($ConfirmedYN != 1 && $isDeleted==0) {
            $status .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="confirmSupplierInvoicefront(' . $masterID . ') "><span title="Confirm" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>';
            $status .= '&nbsp;&nbsp;| &nbsp;&nbsp;<a onclick="delete_supplier_invoice(' . $masterID . ',\'Supplier Invoice\');"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';
        }

        if($approved==1){
            $status .= '&nbsp; | &nbsp;<a onclick="traceDocument(' . $masterID . ',\'BSI\')" title="Trace Document" rel="tooltip"><i class="fa fa-search" aria-hidden="true"></i></a>';
        }

        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('si_confirm')) {
    function si_confirm($con, $m_id = null)
    {
        $status = '<center>';
        if ($m_id) {
            if ($con == 0) {
                $status .= '<a href="#" onclick="procu(' . $m_id . ')"><span class="label label-danger">&nbsp;</span></a>';
            } elseif ($con == 1) {
                $status .= '<a href="#" onclick="procu(' . $m_id . ')"><span class="label label-success">&nbsp;</span></a>';
            } elseif ($con == 2) {
                $status .= '<a href="#" onclick="procu(' . $m_id . ')"><span class="label label-warning">&nbsp;</span></a>';
            } else {
                $status .= '-';
            }
        } else {
            if ($con == 0) {
                $status .= '<span class="label label-danger">&nbsp;</span>';
            } elseif ($con == 1) {
                $status .= '<span class="label label-success">&nbsp;</span>';
            } elseif ($con == 2) {
                $status .= '<span class="label label-warning">&nbsp;</span>';
            } else {
                $status .= '-';
            }
        }

        $status .= '</center>';
        return $status;
    }
}

if (!function_exists('supplier_invoice_action_approval')) {
    function supplier_invoice_action_approval($InvoiceAutoID, $Level, $approved, $ApprovedID, $isRejected,$approval=1)
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
        $SupplierInvoiceAttachments = $CI->lang->line('common_supplier_invoice_attachments');
        $status = '<span class="pull-right">';
        $status .= '<a onclick=\'attachment_modal(' . $InvoiceAutoID . ',"'.$SupplierInvoiceAttachments.'","BSI");\'><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp; ';
        if ($approved == 0) {
            $status .= '| &nbsp;&nbsp;<a onclick=\'fetch_approval("' . $InvoiceAutoID . '","' . $ApprovedID . '","' . $Level . '"); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>&nbsp;&nbsp;';

        }else{
            $status .= '| &nbsp;&nbsp;<a target="_blank" onclick="documentPageView_modal(\'BSI\',\'' . $InvoiceAutoID . '\',\'\',\'' . $approval . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;';
        }


           // $status .= '| &nbsp;&nbsp;<a target="_blank" href="' . site_url('Payable/load_supplier_invoice_conformation/') . '/' . $InvoiceAutoID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>';

        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('dn_confirm')) {
    function dn_confirm($con,$code, $autoID)
    {
        $status = '<center>';
        if ($con == 0) {
            $status .= '<span class="label label-danger">&nbsp;</span>';
        } elseif ($con == 1) {
            $status .= '<span class="label label-success">&nbsp;</span>';
        } elseif ($con == 2) {
            $status .= '<span class="label label-warning">&nbsp;</span>';
        } else {
            $status .= '-';
        }
        $status .= '</center>';
        return $status;
    }
}

if (!function_exists('pv_confirm')) {
    function pv_confirm($con,$code, $autoID)
    {
        $status = '<center>';
        if ($con == 0) {
            $status .= '<span class="label label-danger">&nbsp;</span>';
        } elseif ($con == 1) {
            $status .= '<span class="label label-success">&nbsp;</span>';
        } elseif ($con == 2) {
            $status .= '<span class="label label-warning">&nbsp;</span>';
        } else {
            $status .= '-';
        }
        $status .= '</center>';
        return $status;
    }
}

if (!function_exists('load_Debit_note_action')) {
    function load_Debit_note_action($dnID, $dnConfirmedYN, $approved, $createdUserID,$isDeleted,$confirmedByEmp)
    {
        $CI =& get_instance();
        $CI->load->library('session');
        $status = '<span class="pull-right">';
        $status .= '<a onclick=\'attachment_modal(' . $dnID . ',"Debit Note","DN",' . $dnConfirmedYN . ');\'><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp; | &nbsp;&nbsp;';

        if($isDeleted==1){
            $status .= '<a onclick="reOpen_contract(' . $dnID .');"><span title="Re Open" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }

        if ($dnConfirmedYN != 1 && $isDeleted==0) {
            $status .= '<a onclick=\'fetchPage("system/accounts_payable/erp_debit_note",' . $dnID . ',"Edit Debit Note","DN"); \'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }

        if (($createdUserID == trim($CI->session->userdata("empID")) || $confirmedByEmp == trim($CI->session->userdata("empID"))) and $approved == 0 and $dnConfirmedYN == 1 && $isDeleted==0) {
            $status .= '<a onclick="referbackdn(' . $dnID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }

        $status .= '<a target="_blank" onclick="documentPageView_modal(\'DN\',\'' . $dnID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>';

        $status .= '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a target="_blank" href="' . site_url('Payable/load_dn_conformation/') . '/' . $dnID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a> ';

        if ($dnConfirmedYN != 1 && $isDeleted==0) {
            $status .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="confirmDebitNotefront(' . $dnID . ') "><span title="Confirm" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>';
            $status .= '&nbsp;&nbsp;| &nbsp;&nbsp;<a onclick="delete_item(' . $dnID . ',\'Debit Note\');"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';
        }

        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('load_pvm_action')) {
    function load_pvm_action($pvID, $pvConfirmedYN,$isDeleted,$confirmedByEmp,$createdUserID)
    {
        $CI =& get_instance();
        $status = '<span class="pull-right">';
        $status .= '<a onclick=\'attachment_modal(' . $pvID . ',"Payment Match","PVM",' . $pvConfirmedYN . ');\'><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp; | &nbsp;&nbsp;';
        if($isDeleted==1){
            $status .= '<a onclick="reOpen_contract(' . $pvID .');"><span title="Re Open" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
            $status .= '<a target="_blank" onclick="documentPageView_modal(\'PVM\',\'' . $pvID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>';
        }
        if ($pvConfirmedYN == 0 || $pvConfirmedYN == 3 && $isDeleted==0) {
            $status .= '<a onclick=\'fetchPage("system/payment_voucher/erp_payment_match",' . $pvID . ',"Edit Payment Matching","PVM"); \'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a> &nbsp;&nbsp;| &nbsp;&nbsp;';
            //$status .= '&nbsp;&nbsp;| &nbsp;&nbsp;<a target="_blank" onclick="documentPageView_modal(\'PVM\',\'' . $pvID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>';
            //$status .= '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a target="_blank" href="' . site_url('Payment_voucher/load_pv_match_conformation/') . '/' . $pvID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a> &nbsp;&nbsp;| &nbsp;&nbsp;';
            $status .= '<a onclick="delete_pvm_item(' . $pvID . ',\'Payment Voucher\');"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';
        }

        if (($createdUserID == trim($CI->session->userdata("empID")) || $confirmedByEmp == trim($CI->session->userdata("empID"))) and $pvConfirmedYN == 1 && $isDeleted==0){
            $status .= '<a onclick="referbackPaymentMatch(' . $pvID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>';
        }
            $status .= '&nbsp;&nbsp;| &nbsp;&nbsp; <a target="_blank" onclick="documentPageView_modal(\'PVM\',\'' . $pvID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>';
            $status .= '&nbsp;&nbsp;| &nbsp;&nbsp;<a target="_blank" href="' . site_url('Payment_voucher/load_pv_match_conformation') . '/' . $pvID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>';

        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('load_rvm_action')) {
    function load_rvm_action($pvID, $pvConfirmedYN,$isDeleted,$confirmedByEmp,$createdUserID)
    {
        $CI =& get_instance();
        $status = '<span class="pull-right">';
        $status .= '<a onclick=\'attachment_modal(' . $pvID . ',"Receipt Matching","RVM",' . $pvConfirmedYN . ');\'><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp; | &nbsp;&nbsp;';
        if($isDeleted==1){
            $status .= '<a onclick="reOpen_contract(' . $pvID .');"><span title="Re Open" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }
        if ($pvConfirmedYN != 1 && $isDeleted==0) {
            $status .= '<a onclick=\'fetchPage("system/Receipt_voucher/erp_receipt_match",' . $pvID . ',"Edit Receipt Matching","RVM"); \'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
            $status .= '&nbsp;&nbsp;| &nbsp;&nbsp;<a target="_blank" onclick="documentPageView_modal(\'RVM\',\'' . $pvID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>';
            $status .= '&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a target="_blank" href="' . site_url('Receipt_voucher/load_rv_match_conformation/') . '/' . $pvID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a> &nbsp;&nbsp;| &nbsp;&nbsp;';
            $status .= '<a onclick="delete_rvm_item(' . $pvID . ',\'Receipt Voucher\');"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';
        } else {
            if(($createdUserID == trim($CI->session->userdata("empID")) || $confirmedByEmp == trim($CI->session->userdata("empID"))) and $pvConfirmedYN == 1 && $isDeleted == 0){
                $status .='<a onclick="referbackReceiptMatch('.$pvID.');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
            }


            $status .= '<a target="_blank" onclick="documentPageView_modal(\'RVM\',\'' . $pvID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>';
            $status .= '&nbsp;&nbsp;| &nbsp;&nbsp;<a target="_blank" href="' . site_url('Receipt_voucher/load_rv_match_conformation') . '/' . $pvID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>';
        }
        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('load_pv_action')) {
    function load_pv_action($pvID, $pvConfirmedYN, $approved, $createdUserID,$documentID = "PV",$isDeleted,$bankGLAutoID,$paymentType,$pvtype,$confirmedByEmp)
    {
        $CI =& get_instance();
        $CI->db->select('isCash');
        $CI->db->where('GLAutoID', $bankGLAutoID);
        $isCash = $CI->db->get('srp_erp_chartofaccounts')->row_array();

        $CI->db->select('coaChequeTemplateID');
        $CI->db->where('GLAutoID', $bankGLAutoID);
        $CI->db->where('companyID', current_companyID());
        $templateexist = $CI->db->get('srp_erp_chartofaccountchequetemplates')->row_array();

        $CI->db->select('COUNT(`srp_erp_chartofaccountchequetemplates`.`coaChequeTemplateID`) as templateCount');
        $CI->db->where('companyID', current_companyID());
        $CI->db->where('GLAutoID', $bankGLAutoID);
        $CI->db->join('srp_erp_systemchequetemplates', 'srp_erp_chartofaccountchequetemplates.systemChequeTemplateID = srp_erp_systemchequetemplates.chequeTemplateID', 'left');
        $CI->db->from('srp_erp_chartofaccountchequetemplates');
        $count = $CI->db->get()->row_array();

        $CI->load->library('session');
        $status = '<span class="pull-right">';
        $status .= '<a onclick=\'attachment_modal(' . $pvID . ',"Payment Voucher","PV",' . $pvConfirmedYN . ');\'><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';

        if($isCash['isCash'] ==0 && $approved==1 && !empty($templateexist) && $paymentType==1){
                $status .= '<a onclick=cheque_print_modal(' . $pvID . ','.$count['templateCount'].','.$templateexist['coaChequeTemplateID'].'); ><i title="Cheque Print" rel="tooltip" class="fa fa-cc" aria-hidden="true"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }
        if($isCash['isCash'] !=1 && $approved==1 && $paymentType==2){
            $status .= '<a target="_blank" href="' . site_url('Payment_voucher/load_pv_bank_transfer/') . '/' . $pvID . '" ><span title="Bank Transfer Letter" rel="tooltip" class="glyphicon glyphicon-file"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }
        if($isDeleted==1){
            $status .= '<a onclick="reOpen_contract(' . $pvID .');"><span title="Re Open" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }

        if ($pvConfirmedYN != 1 && $isDeleted==0) {
            if($documentID == "PV") {
                $status .= '<a onclick=\'fetchPage("system/payment_voucher/erp_payment_voucher",' . $pvID . ',"Edit Payment Voucher","PV"); \'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
            }else{
                $status .= '<a onclick=\'fetchPage("system/sales/commision_payment_new",' . $pvID . ',"Edit Commission Payment","PV"); \'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
            }
        }

        if (($createdUserID == trim($CI->session->userdata("empID")) || $confirmedByEmp == trim($CI->session->userdata("empID"))) and $approved == 0 and $pvConfirmedYN == 1 && $isDeleted==0) {
            $status .= '<a onclick="referbackgrv(' . $pvID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }

        $status .= '<a target="_blank" onclick="documentPageView_modal(\'PV\',\'' . $pvID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';

        $status .= '<a target="_blank" href="' . site_url('Payment_voucher/load_pv_conformation/') . '/' . $pvID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>';

        if ($pvConfirmedYN != 1 && $isDeleted==0) {
            $status .= '&nbsp;&nbsp;| &nbsp;&nbsp;<a onclick="delete_pv_item(' . $pvID . ',\'Payment Voucher\');"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';
        }
        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('dn_action_approval')) { /*get po action list*/
    function dn_action_approval($debitNoteMasterAutoID, $Level, $approved, $ApprovedID, $isRejected,$approval=1)
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
       $DebitNoteAttachments  =$CI->lang->line('common_debit_note_attachments'); /*Debit Note Attachments*/
        $status = '<span class="pull-right">';
        $status .= '<a onclick=\'attachment_modal(' . $debitNoteMasterAutoID . ',"'.$DebitNoteAttachments.'","DN");\'><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp;';
        if ($approved == 0) {
            $status .= '| &nbsp;&nbsp;<a onclick=\'fetch_approval("' . $debitNoteMasterAutoID . '","' . $ApprovedID . '","' . $Level . '"); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>&nbsp;&nbsp;';
        }else{
            $status .= '| &nbsp;&nbsp;<a target="_blank" onclick="documentPageView_modal(\'DN\',\'' . $debitNoteMasterAutoID . '\',\'\',\'' . $approval . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;&nbsp;';
        }



            //$status .= '|&nbsp;&nbsp;&nbsp;<a target="_blank" href="' . site_url('Payable/load_dn_conformation/') . '/' . $debitNoteMasterAutoID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>';


        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('pv_confirm')) {
    function pv_confirm($con)
    {
        $status = '<center>';
        if ($con == 0) {
            $status .= '<span class="label label-danger">&nbsp;</span>';
        } elseif ($con == 1) {
            $status .= '<span class="label label-success">&nbsp;</span>';
        } elseif ($con == 2) {
            $status .= '<span class="label label-warning">&nbsp;</span>';
        } else {
            $status .= '-';
        }
        $status .= '</center>';
        return $status;
    }
}

if (!function_exists('pv_action_approval')) { /*get po action list*/
    function pv_action_approval($PayVoucherAutoId, $Level, $approved, $ApprovedID, $isRejected,$approval=1)
    {
        $status = '<span class="pull-right">';
        $status .= '<a onclick=\'attachment_modal(' . $PayVoucherAutoId . ',"Payment Voucher","PV");\'><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>';
        if ($approved == 0) {
            $status .= '&nbsp;&nbsp;| &nbsp;&nbsp;<a onclick=\'fetch_approval("' . $PayVoucherAutoId . '","' . $ApprovedID . '","' . $Level . '"); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>';
        }else{
            $status .= '&nbsp;&nbsp;| &nbsp;&nbsp;<a target="_blank" onclick="documentPageView_modal(\'PV\',\'' . $PayVoucherAutoId . '\',\' \',\'' . $approval . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;';
        }


        //$status .= '| &nbsp;&nbsp;<a target="_blank" href="' . site_url('Payment_voucher/load_pv_conformation/') . '/' . $PayVoucherAutoId . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>';

        $status .= '</span>';
        return $status;
    }
}