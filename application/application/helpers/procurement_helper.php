<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('load_po_action')) { /*get po action list*/
    function load_po_action($poID, $POConfirmedYN, $approved, $createdUserID,$isDeleted,$confirmedByEmpID)
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
       $PurchaseOrder = $CI->lang->line('common_purchase_order');/*"Purchase Order"*/
        $CI->load->library('session');
        $status = '<span class="pull-right">';
        $status .= '<a onclick=\'attachment_modal(' . $poID . ',"'.$PurchaseOrder.'","PO",' . $POConfirmedYN . ');\'><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';
        if($isDeleted==1){
            $status .= '<a onclick="reOpen_contract(' . $poID .');"><span title="Re Open" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }

        if (($createdUserID == trim($CI->session->userdata("empID")) || $confirmedByEmpID == trim($CI->session->userdata("empID"))) and $approved == 0 and $POConfirmedYN == 1 && $isDeleted==0) {
            $status .= '<a onclick="referbackprocument(' . $poID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';
        }

        if ($POConfirmedYN != 1 && $isDeleted==0) {
            $status .= '<a onclick=\'fetchPage("system/procurement/erp_purchase_order_new",' . $poID . ',"Edit Purchase Order","PO"); \'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';
            $status .= '<a target="_blank" onclick="documentPageView_modal(\'PO\',\'' . $poID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';
            $status .= '<a target="_blank" href="' . site_url('Procurement/load_purchase_order_conformation/') . '/' . $poID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a> &nbsp;&nbsp;| &nbsp;&nbsp;';

            $status .= '<a onclick="delete_item(' . $poID . ',\'Purchase Order\');"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';
        }
        if ($POConfirmedYN == 1 && $isDeleted==0) {
            $status .= '<a target="_blank" onclick="documentPageView_modal(\'PO\',\'' . $poID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';
            if ($approved != 5 && $isDeleted==0) {
                $status .= '<a onclick=\'po_close("' . $poID . '"); \'><i title="Close" rel="tooltip" class="fa fa-times" aria-hidden="true"></i></a>&nbsp;&nbsp;| &nbsp;&nbsp;';
            }

            $status .= '<a target="_blank" href="' . site_url('Procurement/load_purchase_order_conformation/') . '/' . $poID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>&nbsp;&nbsp;';


        }
        if($approved == 1)
        {
            $status .= '| &nbsp;<a onclick="sendemail(' . $poID . ')" title="Send Mail" rel="tooltip"><i class="fa fa-envelope" aria-hidden="true"></i></a>&nbsp; | &nbsp;<a onclick="traceDocument(' . $poID . ',\'PO\')" title="Trace Document" rel="tooltip"><i class="fa fa-search" aria-hidden="true"></i></a>';
        }

        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('load_uom_action')) { /*get po action list*/
    function load_uom_action($UnitID, $desc, $code)
    {
        $status = '<span class="pull-right">';
        $status .= '<a onclick=\'fetch_umo_detail_con(' . $UnitID . ',"' . $desc . '","' . $code . '");\'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('po_action_approval')) { /*get po action list*/
    function po_action_approval($poID, $Level, $approved, $ApprovedID, $isRejected,$approval=1)
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
        $PurchaseOrder1 = $CI->lang->line('common_purchase_order');/*"Purchase Order"*/
        $status = '<span class="pull-right">';

        $status .= '<a onclick=\'attachment_modal(' . $poID . ',"'.$PurchaseOrder1.'","PO");\'><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp;| &nbsp;&nbsp;';


        if ($approved == 0) {
            $status .= '<a onclick=\'fetch_approval("' . $poID . '","' . $ApprovedID . '","' . $Level . '"); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>&nbsp;&nbsp; ';
        }else{
            $status .= '<a target="_blank" onclick="documentPageView_modal(\'PO\',\'' . $poID . '\',\' \',\'' . $approval . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;';
        }
        //$status .= '<a target="_blank" onclick="documentPageView_modal(\'PO\',\'' . $poID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;';
        //$status .= '| &nbsp;&nbsp;<a target="_blank" href="' . site_url('Procurement/load_purchase_order_conformation/') . '/' . $poID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>';
        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('po_confirm')) { /*get po action list*/
    function po_confirm($con)
    {
        $status = '<center>';
/*        if ($m_id) {
            if ($con == 0) {
                $status .= '<a href="#" onclick="procu(' . $m_id . ')"><span class="label label-danger">&nbsp;</span></a>';
            } elseif ($con == 1) {
                $status .= '<a href="#" onclick="procu(' . $m_id . ')"><span class="label label-success">&nbsp;</span></a>';
            } elseif ($con == 2) {
                $status .= '<a href="#" onclick="procu(' . $m_id . ')"><span class="label label-warning">&nbsp;</span></a>';
            } else {
                $status .= '-';
            }
        } else {*/
            if ($con == 0) {
                $status .= '<span class="label label-danger">&nbsp;</span>';
            } else if ($con == 1) {
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

if (!function_exists('sold_to')) {
    function sold_to()
    {
        $CI =& get_instance();
        $CI->db->select('contactPerson,addressID,isDefault,addressType');
        $CI->db->from('srp_erp_address');
        $CI->db->join('srp_erp_addresstype', 'srp_erp_addresstype.addressTypeID = srp_erp_address.addressTypeID');
        $CI->db->where('srp_erp_address.companyID', $CI->common_data['company_data']['company_id']);
        $CI->db->where('srp_erp_addresstype.addressTypeDescription', 'Sold To');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Sold');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['addressID'])] = trim($row['addressType']) . ' - ' . trim($row['contactPerson']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('ship_to')) {
    function ship_to()
    {
        $CI =& get_instance();
        $primaryLanguage = getPrimaryLanguage();
        $CI->lang->load('common', $primaryLanguage);
        $CI->db->select('contactPerson,addressID,isDefault,addressType,addressDescription');
        $CI->db->from('srp_erp_address');
        $CI->db->join('srp_erp_addresstype', 'srp_erp_addresstype.addressTypeID = srp_erp_address.addressTypeID');
        $CI->db->where('srp_erp_address.companyID', $CI->common_data['company_data']['company_id']);
        $CI->db->where('srp_erp_addresstype.addressTypeDescription', 'Ship To');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => $CI->lang->line('common_select_ship')/*'Select Ship'*/);
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['addressID'])] = trim($row['addressType']) . ' | ' . trim($row['contactPerson']). ' | ' . trim($row['addressDescription']);
            }
        }
        return $data_arr;
    }
}


if (!function_exists('invoice_to')) {
    function invoice_to()
    {
        $CI =& get_instance();
        $CI->db->select('contactPerson,addressID,isDefault,addressType');
        $CI->db->from('srp_erp_address');
        $CI->db->join('srp_erp_addresstype', 'srp_erp_addresstype.addressTypeID = srp_erp_address.addressTypeID');
        $CI->db->where('srp_erp_address.companyID', $CI->common_data['company_data']['company_id']);
        $CI->db->where('srp_erp_addresstype.addressTypeDescription', 'Send Invoice To');
        $data = $CI->db->get()->result_array();
        $data_arr = array('' => 'Select Invoice');
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr[trim($row['addressID'])] = trim($row['addressType']) . ' - ' . trim($row['contactPerson']);
            }
        }
        return $data_arr;
    }
}

if (!function_exists('fetch_address_po')) {
    function fetch_address_po($id)
    {
        $CI =& get_instance();
        $CI->db->select('*');
        $CI->db->from('srp_erp_address');
        $CI->db->where('addressID', $id);
        return $CI->db->get()->row_array();
    }
}

if (!function_exists('po_total_value')) {
    function po_total_value($id, $decimal = 2)
    {
        $CI =& get_instance();
        $CI->db->select_sum('totalAmount');
        $CI->db->where('purchaseOrderID', $id);
        $totalAmount = $CI->db->get('srp_erp_purchaseorderdetails')->row('totalAmount');
        return number_format($totalAmount, $decimal);
    }
}

if (!function_exists('po_Recived')) { /*get po action list*/
    function po_Recived($isReceived,$closedYN)
    {
        $status = '<center>';
        /*        if ($m_id) {
                    if ($con == 0) {
                        $status .= '<a href="#" onclick="procu(' . $m_id . ')"><span class="label label-danger">&nbsp;</span></a>';
                    } elseif ($con == 1) {
                        $status .= '<a href="#" onclick="procu(' . $m_id . ')"><span class="label label-success">&nbsp;</span></a>';
                    } elseif ($con == 2) {
                        $status .= '<a href="#" onclick="procu(' . $m_id . ')"><span class="label label-warning">&nbsp;</span></a>';
                    } else {
                        $status .= '-';
                    }
                } else {*/
        if ($isReceived == 0) {
            $status .= '<span class="label label-danger" style="font-size: 9px;" title="Not Received" rel="tooltip">Not Received</span>';
        } else if ($isReceived == 1) {
            $status .= '<span class="label label-warning" style="font-size: 9px;" title="Partially Received" rel="tooltip">Partially Received</span>';
        } elseif ($isReceived == 2) {
            $status .= '<span class="label label-success" style="font-size: 9px;" title="Fully Received" rel="tooltip">Fully Received</span>';
        }

        $status .= '</center>';
        return $status;
    }
}

