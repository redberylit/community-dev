<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('fetch_group_segment')) {
    function fetch_group_segment($id = FALSE, $state = TRUE) /*$id parameter is used to display only ID as value in select option*/
    {
        $CI =& get_instance();
        $CI->db->select('segmentCode,description,segmentID');
        $CI->db->from('srp_erp_groupsegment');
        $CI->db->where('status', 1);
        $CI->db->where('groupID', current_companyID());
        $CI->db->group_by('segmentID');
        $data = $CI->db->get()->result_array();
        if ($state == TRUE) {
            $data_arr = array('' => 'Select Segment');
        } else {
            $data_arr = '';
        }
        if (isset($data)) {
            foreach ($data as $row) {
                if ($id) {
                    $data_arr[trim($row['segmentID'])] = trim($row['segmentCode']) . ' | ' . trim($row['description']);
                } else {
                    $data_arr[trim($row['segmentID']) . '|' . trim($row['segmentCode'])] = trim($row['segmentCode']) . ' | ' . trim($row['description']);
                }

            }
        }

        return $data_arr;
    }
}

if (!function_exists('itemLedgerDocumentID')) {
    function itemLedgerDocumentID($id = FALSE, $state = TRUE)
    {
        $CI =& get_instance();
        $CI->db->select('srp_erp_itemledger.documentID as documentID,srp_erp_documentcodes.document as document');
        $CI->db->from('srp_erp_itemledger');
        $CI->db->join('srp_erp_documentcodes ', 'srp_erp_documentcodes.documentID = srp_erp_itemledger.documentID');
        $CI->db->where('srp_erp_itemledger.companyID', current_companyID());
        $CI->db->group_by('srp_erp_itemledger.documentID');
        $data = $CI->db->get()->result_array();
        if ($state == TRUE) {
           // $data_arr = array('' => 'Select Document ID');
            $data_arr = '';
        } else {
            $data_arr = '';
        }
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr['"'.$row['documentID'].'"'] = trim($row['documentID']) . ' | ' . trim($row['document']);
            }
        }

        return $data_arr;
    }
}

if (!function_exists('generalLedgerDocumentID')) {
    function generalLedgerDocumentID($id = FALSE, $state = TRUE)
    {
        $CI =& get_instance();
        $CI->db->select('srp_erp_generalledger.documentCode as documentID,srp_erp_documentcodes.document as document');
        $CI->db->from('srp_erp_generalledger');
        $CI->db->join('srp_erp_documentcodes ', 'srp_erp_documentcodes.documentID = srp_erp_generalledger.documentCode');
        $CI->db->where('srp_erp_generalledger.companyID', current_companyID());
        $CI->db->group_by('srp_erp_generalledger.documentCode');
        $data = $CI->db->get()->result_array();
        if ($state == TRUE) {
            // $data_arr = array('' => 'Select Document ID');
            $data_arr = '';
        } else {
            $data_arr = '';
        }
        if (isset($data)) {
            foreach ($data as $row) {
                $data_arr['"'.$row['documentID'].'"'] = trim($row['documentID']) . ' | ' . trim($row['document']);
            }
        }

        return $data_arr;
    }
}