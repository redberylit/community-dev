<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*recurring journal Entry Action*/

if (!function_exists('recurring_journal_entry_action')) {
    function recurring_journal_entry_action($RJVMasterAutoId, $confirmedYN, $approvedYN, $createdUserID, $isDeleted,$confirmedByEmpID)
    {
        $CI =& get_instance();
        $CI->load->library('session');
        $status = '<span class="pull-right">';
        $status .= '<a onclick=\'attachment_modal(' . $RJVMasterAutoId . ',"Recurring Journal Entry","RJV",' . $confirmedYN . ');\'><span title="Attachment" rel="tooltip" class="glyphicon glyphicon-paperclip"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        if ($isDeleted == 1) {
            $status .= '<a onclick="reOpen_contract(' . $RJVMasterAutoId . ');"><span title="Re Open" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }
        if ($confirmedYN != 1 && $isDeleted == 0) {
            $status .= '<a onclick=\'fetchPage("system/recurringJV/recurring_je_new",' . $RJVMasterAutoId . ',"Edit Recurring Journal Entry","Recurring Journal Entry"); \'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }
        if (($createdUserID == trim($CI->session->userdata("empID")) || $confirmedByEmpID == trim($CI->session->userdata("empID"))) and $approvedYN == 0 and $confirmedYN == 1 && $isDeleted == 0) {
            $status .= '<a onclick="referback_journal_entry(' . $RJVMasterAutoId . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
        }
        $status .= '<a target="_blank" onclick="documentPageView_modal(\'RJV\',\'' . $RJVMasterAutoId . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp';
        $status .= '<a target="_blank" href="' . site_url('Recurring_je/recurring_journal_entry_conformation/') . '/' . $RJVMasterAutoId . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a> ';
        if ($confirmedYN != 1 && $isDeleted == 0) {
            $status .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="delete_recurring_journal_entry(' . $RJVMasterAutoId . ',\'Invoices\');"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';
        }
        $status .= '</span>';

        return $status;
    }
}

if (!function_exists('rjv_approval')) {
    function rjv_approval($poID, $Level, $approved, $ApprovedID, $isRejected, $approval = 1)
    {
        $status = '<span class="pull-right">';
        if ($approved == 0) {
            $status .= '<a onclick=\'fetch_approval("' . $poID . '","' . $ApprovedID . '","' . $Level . '"); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>&nbsp;&nbsp;';
        } else {
            $status .= '&nbsp;&nbsp;<a target="_blank" onclick="documentPageView_modal(\'RJV\', ' . $poID . ',\' \', ' . $approval . ')"> <span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a> &nbsp;&nbsp;';
        }


        //$status .= '| &nbsp;&nbsp;<a target="_blank" href="' . site_url('Journal_entry/journal_entry_conformation/') . '/' . $poID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a>';
        $status .= '</span>';

        return $status;
    }
}




