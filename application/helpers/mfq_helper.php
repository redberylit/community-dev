<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Mubashir
 * Date: 3/7/2017
 * Time: 5:41 PM
 */


if (!function_exists('confirm_mfq')) {
    function confirm_mfq($con)
    {
        $status = '<div style="text-align: center">';
        if ($con == 0) {
            $status .= '<div class="actioniconWarning"><span class="glyphicon glyphicon-ok" style="color:rgb(255, 255, 255);" title="Not Confirmed"></span></div>';
        } elseif ($con == 1) {
            $status .= '<div class="actionicon"><span class="glyphicon glyphicon-ok" style="color:rgb(255, 255, 255);" title="Confirmed"></span></div>';
        } elseif ($con == 2) {
            $status .= '<span class="label label-warning">&nbsp;</span>';
        } elseif ($con == 3) {
            $status .= '<span class="label label-warning">&nbsp;</span>';
        } else {
            $status .= '-';
        }
        $status .= '</div>';
        return $status;
    }
}

if (!function_exists('col_category')) {
    function col_category($id, $description, $functionName, $tmpCriteria = null)
    {
        // $criteria = preg_replace("/[^A-Za-z0-9\-\']/", "", $tmpCriteria);
        $xCriteria = str_replace("\n", "", $tmpCriteria);
        $criteria = str_replace("\r", "", $xCriteria);
        $desc = !empty($description) ? $description : '<span style="color:#9da1a1">un-categorised</span>';
        $string = '<button class="btn-link" onclick="' . $functionName . '(' . $id . ',\'' . $criteria . '\')">' . $desc . '</button>';
        return $string;
    }
}
if (!function_exists('gender_ico')) {
    function gender_ico($gender)
    {
        if ($gender == 1) {
            $output = '<div style="font-size:14px;" class="text-center"><i class="fa fa-male" style="color:blueviolet;" aria-hidden="true"></i></div>';
        } else {
            $output = '<div style="font-size:14px;" class="text-center"><i class="fa fa-female" style="color:deeppink;" aria-hidden="true"></i></div>';
        }
        return $output;
    }
}

if (!function_exists('countryDiv')) {
    function countryDiv($country)
    {
        $countryImg = base_url() . '/images/flags/' . trim($country) . '.png';
        $output = '<div><img src="' . $countryImg . '" /> ' . $country . '</div>';
        return $output;
    }
}


if (!function_exists('get_mfq_category_drop')) {
    function get_mfq_category_drop($parentID = 0, $categoryType = 1)
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->from('srp_erp_mfq_category');
        $CI->db->where('masterID', $parentID);
        $CI->db->where('companyID', current_companyID());
        $CI->db->where('categoryType', $categoryType);
        $CI->db->order_by('description');
        $output = $CI->db->get()->result_array();
        $result = array('-1' => 'Select');
        if (!empty($output)) {
            foreach ($output as $row) {
                $result[$row['itemCategoryID']] = $row['description'];
            }
        }
        return $result;
    }
}


if (!function_exists('edit_mfq_crew')) {
    function edit_mfq_crew($id, $isFromERP)
    {
        $status = '<span class="pull-right">';
        if ($isFromERP) {
            $status .= '<span style="color:#079f1e; font-size:13px;"><span title="Linked to ERP" rel="tooltip" class="fa fa-link"></span></span>&nbsp;&nbsp;';
            $status .= ' &nbsp; | &nbsp; ';
            $status .= '<a onclick="fetchPage(\'system/mfq/crew/manage-crew\',' . $id . ',\'Edit Crew\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;';
        } else {
            $status .= '<span style="color:#8B0000; font-size:13px;" onclick="link_crew_master(' . $id . ')"><span title="Not Linked" rel="tooltip" class="fa fa-external-link"></span></span>&nbsp;&nbsp;';
            $status .= ' &nbsp; | &nbsp; ';
            $status .= '<a onclick="fetchPage(\'system/mfq/crew/manage-crew\',' . $id . ',\'Edit Crew\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;';
        }
        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('edit_mfq_customer')) {
    function edit_mfq_customer($id, $isFromERP)
    {
        $status = '<span class="pull-right">';
        if ($isFromERP) {
            $status .= '<span style="color:#079f1e; font-size:13px;"><span title="Linked to ERP" rel="tooltip" class="fa fa-link"></span></span>&nbsp;&nbsp;';
            $status .= ' &nbsp; | &nbsp; ';
            $status .= '<a onclick="fetchPage(\'system/mfq/crew/manage-customer\',' . $id . ',\'Edit Customer\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;';
        } else {
            $status .= '<span style="color:#8B0000; font-size:13px;"  onclick="link_customer_master(' . $id . ')"><span title="Not Linked" rel="tooltip" class="fa fa-external-link"></span></span>&nbsp;&nbsp;';
            $status .= ' &nbsp; | &nbsp; ';
            $status .= '<a onclick="fetchPage(\'system/mfq/crew/manage-customer\',' . $id . ',\'Edit Customer\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;';
        }
        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('edit_mfq_segment')) {
    function edit_mfq_segment($id, $isFromERP)
    {
        $status = '<span class="pull-right">';
        if ($isFromERP) {
            $status .= '<span style="color:#079f1e; font-size:13px;"><span title="Linked to ERP" rel="tooltip" class="fa fa-link"></span></span>&nbsp;&nbsp;';
            $status .= ' &nbsp; | &nbsp; ';
            $status .= '<a onclick="fetchPage(\'system/mfq/master/manage-segment\',' . $id . ',\'Edit Segment\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;';
        } else {
            $status .= '<span style="color:#8B0000; font-size:13px;" onclick="link_segment_master(' . $id . ')" ><span title="Not Linked" rel="tooltip" class="fa fa-external-link"></span></span>&nbsp;&nbsp;';
            $status .= ' &nbsp; | &nbsp; ';
            $status .= '<a onclick="fetchPage(\'system/mfq/master/manage-segment\',' . $id . ',\'Edit Segment\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;';
        }
        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('edit_mfq_warehouse')) {
    function edit_mfq_warehouse($id, $isFromERP, $warehouseAutoID)
    {
        $status = '<span class="pull-right">';
        if ($isFromERP || $warehouseAutoID) {
            $status .= '<span style="color:#079f1e; font-size:13px;"><span title="Linked to ERP" rel="tooltip" class="fa fa-link"></span></span>&nbsp;&nbsp;';
            $status .= ' &nbsp; | &nbsp; ';
            $status .= '<a onclick="fetchPage(\'system/mfq/manage_warehouse\',' . $id . ',\'Edit Warehouse\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;';
        } else {
            $status .= '<span style="color:#8B0000; font-size:13px;" onclick="link_warehouse_master(' . $id . ')"><span title="Not Linked" rel="tooltip" class="fa fa-external-link"></span></span>&nbsp;&nbsp;';
            $status .= ' &nbsp; | &nbsp; ';
            $status .= '<a onclick="fetchPage(\'system/mfq/manage_warehouse\',' . $id . ',\'Edit Warehouse\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;';
        }
        $status .= '</span>';
        return $status;
    }
}

if (!function_exists('edit_mfq_uom')) {
    function edit_mfq_uom($id, $isFromERP)
    {
        $status = '<span class="pull-right">';
        if ($isFromERP) {
            $status .= '<span style="color:#079f1e; font-size:13px;"><span title="Linked to ERP" rel="tooltip" class="fa fa-link"></span></span>&nbsp;&nbsp;';
            $status .= '<a onclick="fetchPage(\'system/mfq/master/manage-segment\',' . $id . ',\'Edit Segment\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;';
        } else {
            $status .= '<span style="color:#8B0000; font-size:13px;" ><span title="Not Linked" rel="tooltip" class="fa fa-external-link"></span></span>&nbsp;&nbsp;';
            $status .= ' &nbsp; | &nbsp; ';
            $status .= '<a onclick="fetchPage(\'system/mfq/master/manage-segment\',' . $id . ',\'Edit Segment\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;';
        }
        $status .= '</span>';
        return $status;
    }
}


if (!function_exists('edit_mfq_item')) {
    function edit_mfq_item($id, $isFromERP, $itemAutoID)
    {
        $status = '<span class="pull-right">';
        if ($isFromERP || $itemAutoID) {
            $status .= '<span style="color:#079f1e; font-size:13px;"><span title="Linked to ERP" rel="tooltip" class="fa fa-link"></span></span>&nbsp;&nbsp;';
            $status .= ' &nbsp; | &nbsp; ';
            $status .= '<a onclick="fetchPage(\'system/mfq/item-master/manage-item\',' . $id . ',\'Edit Crew\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>';
        } else {
            $status .= '<span style="color:#8B0000; font-size:13px;" onclick="link_item_master(' . $id . ')"><span title="Not Linked" rel="tooltip" class="fa fa-external-link"></span></span>&nbsp;&nbsp;';
            $status .= ' &nbsp; | &nbsp; ';
            $status .= '<a onclick="fetchPage(\'system/mfq/item-master/manage-item\',' . $id . ',\'Edit Crew\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;';
        }
        $status .= '</span>';
        return $status;
    }
}


if (!function_exists('edit_mfq_asset')) {
    function edit_mfq_asset($id, $isFromERP)
    {
        $status = '<span class="pull-right">';
        if ($isFromERP) {
            $status .= '<span style="color:#079f1e; font-size:13px;"><span title="Linked to ERP" rel="tooltip" class="fa fa-link"></span></span>&nbsp;&nbsp;';
            $status .= ' &nbsp; | &nbsp; ';
            $status .= '<a onclick="fetchPage(\'system/mfq/master/manage-machine\',' . $id . ',\'Edit Crew\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;';
        } else {
            $status .= '<span style="color:#8B0000; font-size:13px;"onclick="link_asset_master(' . $id . ')" ><span title="Not Linked" rel="tooltip" class="fa fa-external-link"></span></span>&nbsp;&nbsp;';
            $status .= ' &nbsp; | &nbsp; ';
            $status .= '<a onclick="fetchPage(\'system/mfq/master/manage-machine\',' . $id . ',\'Edit Crew\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;';
        }
        $status .= '</span>';
        return $status;
    }
}


if (!function_exists('generateMFQ_SystemCode')) {
    function generateMFQ_SystemCode($tableName, $primaryKey, $companyIDCol = 'companyID', $documentID = null, $segmentID = null)
    {
        $CI =& get_instance();
        $CI->db->select_max('serialNo');
        $CI->db->from($tableName);
        $CI->db->where($companyIDCol, current_companyID());
        //$CI->db->order_by($primaryKey, 'desc');
        $result = $CI->db->get()->row_array();

        if (!empty($result)) {
            $serialNo = $result['serialNo'] + 1;
            $systemCode = current_companyCode() . '/' . date('Y') . '/' . str_pad($serialNo, 5, '0', STR_PAD_LEFT);

        } else {
            $serialNo = 1;
            $systemCode = current_companyCode() . '/' . date('Y') . '/' . str_pad(1, 5, '0', STR_PAD_LEFT);

        }

        $output['serialNo'] = $serialNo;
        $output['systemCode'] = $systemCode;

        return $output;

    }
}


if (!function_exists('get_overhead_categoryDrop')) {
    function get_overhead_categoryDrop()
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->from('srp_erp_mfq_overheadcategory');
        $CI->db->order_by('description');
        $output = $CI->db->get()->result_array();
        $result = array('' => 'Select');
        if (!empty($output)) {
            foreach ($output as $row) {
                $result[$row['overheadCategoryID']] = $row['description'];
            }
        }
        return $result;
    }
}


if (!function_exists('editOverHead')) {
    function editOverHead($overHeadID)
    {
        $status = '<div style="text-align: center;">';
        $status .= '<a onclick=\'editOverHead(' . $overHeadID . ')\'><span class="glyphicon glyphicon-pencil"></span></a>';
        $status .= '</div>';
        return $status;
    }
}

if (!function_exists('editLabour')) {
    function editLabour($overHeadID)
    {
        $status = '<div style="text-align: center;">';
        $status .= '<a onclick=\'editLabour(' . $overHeadID . ')\'><span class="glyphicon glyphicon-pencil"></span></a>';
        /*$status .= '&nbsp; | &nbsp;<a onclick=\'deleteLabour(' . $overHeadID . ')\'><span class="glyphicon glyphicon-trash text-red"></span></a>';*/
        $status .= '</div>';
        return $status;
    }
}

if (!function_exists('editBoM')) {
    function editBoM($bomID)
    {
        $status = '<div style="text-align: center">';
        $status .= '<a onclick="fetchPage(\'system/mfq/mfq_add_new_bill_of_material\',' . $bomID . ',\'Edit Bill of Material\',\'BOM\');"><span class="glyphicon glyphicon-pencil"></span></a> &nbsp; | &nbsp;<a onclick="deleteBOM(' . $bomID . ');"><span class="glyphicon glyphicon-trash text-red"></span></a>';
        $status .= '</div>';

        return $status;
    }
}

if (!function_exists('editCustomerInquiry')) {
    function editCustomerInquiry($ciMasterID, $confirmedYN, $approvedYN)
    {
        $status = '<div style="text-align: center">';
        if ($confirmedYN == 1) {
            if ($approvedYN == 1) {
                $status .= '<a onclick="viewDocument(' . $ciMasterID . ')" title="View" rel="tooltip"><span class="fa fa-eye"></span></a> &nbsp; | &nbsp; <a onclick="createEstimate(' . $ciMasterID . ')" title="Create Estimate" rel="tooltip"><span class="fa fa-file-text"></span></a>';
            } else {
                $status .= ' <a onclick="referbackCustomerInquiry(' . $ciMasterID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp; | &nbsp;<a onclick="viewDocument(' . $ciMasterID . ')" title="View" rel="tooltip"><span class="fa fa-eye"></span></a>';
            }
        } else {
            $status .= '<a onclick="fetchPage(\'system/mfq/mfq_add_new_mfq\',' . $ciMasterID . ',\'Edit Customer Inquiry\',\'CI\');" title="Edit" rel="tooltip"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="viewDocument(' . $ciMasterID . ')" title="View" rel="tooltip"><span class="fa fa-eye"></span></a>';
        }
        $status .= '</div>';

        return $status;
    }
}

if (!function_exists('editEstimate')) {
    function editEstimate($estimateMasterID, $confirmedYN, $estimateDetailID, $approvedYN, $jobID, $docApprovedYN)
    {
        $status = '<div style="text-align: center">';

        if ($confirmedYN == 1) {
            if ($estimateDetailID) {
                if ($approvedYN) {
                    $status .= '<a onclick="viewDocument(' . $estimateMasterID . ')" title="View" rel="tooltip"><span class="fa fa-eye"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="sendemail(' . $estimateMasterID . ')" title="Send Mail" rel="tooltip"><i class="fa fa-envelope" aria-hidden="true"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="load_jobOrder_view(' . $estimateMasterID . ',' . $jobID . ')" title="Job View" rel="tooltip"><i class="fa fa-book" aria-hidden="true"></i></a>';
                } else {
                    if ($docApprovedYN) {
                        $status .= '<a onclick="referbackEstimate(' . $estimateMasterID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp; | &nbsp;<a onclick="sendemail(' . $estimateMasterID . ')" title="Send Mail" rel="tooltip"><i class="fa fa-envelope" aria-hidden="true"></i></a>&nbsp;|&nbsp;<a onclick="viewDocument(' . $estimateMasterID . ')" title="View" rel="tooltip"><span class="fa fa-eye"></span></a>';
                    } else {
                        $status .= '<a onclick="referbackEstimate(' . $estimateMasterID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp; | &nbsp;<a onclick="viewDocument(' . $estimateMasterID . ')" title="View" rel="tooltip"><span class="fa fa-eye"></span></a>';
                    }
                }
            } else {
                if ($approvedYN) {
                    $status .= '<a onclick="viewDocument(' . $estimateMasterID . ')" title="View" rel="tooltip"><span class="fa fa-eye"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="createJob(' . $estimateMasterID . ')" title="Create Job" rel="tooltip"><i class="fa fa-file-text" aria-hidden="true"></i></a> &nbsp;&nbsp;|&nbsp;&nbsp; <a onclick="createEstimateVersion(' . $estimateMasterID . ')" title="Create Revision" rel="tooltip"><i class="fa fa-repeat" aria-hidden="true"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="sendemail(' . $estimateMasterID . ')" title="Send Mail" rel="tooltip"><i class="fa fa-envelope" aria-hidden="true"></i></a>';
                } else {
                    $status .= '<a onclick="referbackEstimate(' . $estimateMasterID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="viewDocument(' . $estimateMasterID . ')" title="View" rel="tooltip"><span class="fa fa-eye"></span></a>';
                }
            }
        } else {
            $status .= '<a onclick="fetchPage(\'system/mfq/mfq_add_new_estimate\',' . $estimateMasterID . ',\'Edit Estimate\',\'EST\');" title="Edit" rel="tooltip"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="viewDocument(' . $estimateMasterID . ')" title="View" rel="tooltip"><span class="fa fa-eye"></span></a>';
        }
        $status .= '</div>';

        return $status;
    }
}

if (!function_exists('editJob')) {
    function editJob($workProcessID, $confirmedYN, $approvedYN, $isFromEstimate, $estimateMasterID = null, $linkedJobCard = null)
    {
        $documentID = "MFQ";
        if ($isFromEstimate == 1) {
            $documentID = "EST";
        }
        $status = '<div style="text-align: center">';
        if (is_null($linkedJobCard)) {
            $status .= '<a href="#" onclick="createJob(' . $estimateMasterID . ',' . $workProcessID . ')"><i class="fa fa-file-text" aria-hidden="true" title="Generate Job" rel="tooltip"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="load_jobOrder_view(' . $estimateMasterID . ',' . $workProcessID . ')" title="Job View" rel="tooltip"><i class="fa fa-book" aria-hidden="true"></i></a>';
        } else {

            if ($confirmedYN == 1) {
                if ($approvedYN == 1) {
                    $status .= '<span class="pull-right"><a href="#" onclick="fetchPage(\'system/mfq/mfq_job_create\',' . $workProcessID . ',\'Edit Job\',\'' . $documentID . '\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp; | &nbsp;<a href="#" onclick="getWorkFlowStatus(' . $workProcessID . ')"><span title="Route Card" rel="tooltip" class="fa fa-cogs"></span></a></span>';
                } else {
                    $status .= ' <a onclick="referbackJob(' . $workProcessID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp; | &nbsp;<span class="pull-right"><a href="#" onclick="fetchPage(\'system/mfq/mfq_job_create\',' . $workProcessID . ',\'Edit Job\',\'' . $documentID . '\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp; | &nbsp;<a href="#" onclick="getWorkFlowStatus(' . $workProcessID . ')"><span title="Route Card" rel="tooltip" class="fa fa-cogs"></span></a></span>';
                }
            } else {
                $status .= '<span class="pull-right"><a href="#" onclick="fetchPage(\'system/mfq/mfq_job_create\',' . $workProcessID . ',\'Edit Job\',\'' . $documentID . '\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp; | &nbsp;<a href="#" onclick="getWorkFlowStatus(' . $workProcessID . ')"><span title="Route Card" rel="tooltip" class="fa fa-cogs"></span></a> &nbsp; | &nbsp;<a href="#" onclick="updateUsageQty(' . $workProcessID . ')"><span title="Usage Qty" rel="tooltip" class="fa fa-arrow-up"></span></a></span>';
            }
        }
        $status .= '</div>';
        return $status;
    }
}


if (!function_exists('get_job_cardID')) {
    function get_job_cardID($workProcessID, $workFlowID, $templateDetailID)
    {
        $CI =& get_instance();
        $CI->db->select("srp_erp_mfq_jobcardmaster.*,ws.status");
        $CI->db->from('srp_erp_mfq_jobcardmaster');
        //$CI->db->join('srp_erp_mfq_workflowstatus', ' srp_erp_mfq_jobcardmaster.templateDetailID=srp_erp_mfq_workflowstatus.templateDetailID', 'LEFT');
        $CI->db->join('(SELECT * FROM srp_erp_mfq_workflowstatus WHERE jobID = ' . $workProcessID . ' AND templateDetailID = ' . $templateDetailID . ') ws', ' srp_erp_mfq_jobcardmaster.templateDetailID=ws.templateDetailID', 'LEFT');
        $CI->db->where('srp_erp_mfq_jobcardmaster.workProcessID', $workProcessID);
        $CI->db->where('srp_erp_mfq_jobcardmaster.workFlowID', $workFlowID);
        $CI->db->where('srp_erp_mfq_jobcardmaster.templateDetailID', $templateDetailID);
        $output = $CI->db->get()->row_array();
        return $output;
    }
}

if (!function_exists('get_job_master')) {
    function get_job_master($workProcessID)
    {
        $CI =& get_instance();
        $CI->db->select("srp_erp_mfq_job.*,UnitDes,srp_erp_mfq_itemmaster.itemDescription,IFNULL(est.estimateCode,'') as estimateCode");
        $CI->db->from('srp_erp_mfq_job');
        $CI->db->join('srp_erp_mfq_itemmaster', ' srp_erp_mfq_itemmaster.mfqItemID = srp_erp_mfq_job.mfqItemID', 'LEFT');
        $CI->db->join('srp_erp_unit_of_measure', ' UnitID = defaultUnitOfMeasureID', 'LEFT');
        $CI->db->join('(SELECT IFNULL(estimateCode,"") as estimateCode,srp_erp_mfq_estimatedetail.estimateDetailID FROM srp_erp_mfq_estimatedetail LEFT JOIN srp_erp_mfq_estimatemaster ON srp_erp_mfq_estimatedetail.estimateMasterID = srp_erp_mfq_estimatemaster.estimateMasterID) est', 'est.estimateDetailID = srp_erp_mfq_job.estimateDetailID', 'LEFT');
        $CI->db->where('workProcessID', $workProcessID);
        $output = $CI->db->get()->row_array();
        return $output;
    }
}

if (!function_exists('all_bill_of_material_drop')) {
    function all_bill_of_material_drop($mfqItemID = null, $status = TRUE)/*Load all Bom*/
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->from('srp_erp_mfq_billofmaterial');
        $CI->db->where('mfqItemID', $mfqItemID);
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $bom = $CI->db->get()->result_array();
        if ($status) {
            $bom_arr = array('' => 'Select BOM');
        } else {
            $bom_arr = '';
        }
        if (isset($bom)) {
            foreach ($bom as $row) {
                $bom_arr[trim($row['bomMasterID'])] = trim($row['description']);
            }
        }

        return $bom_arr;
    }
}

if (!function_exists('all_finish_goods_drop')) {
    function all_finish_goods_drop($status = TRUE)/*Load all Bom*/
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->from('srp_erp_mfq_itemmaster');
        $CI->db->where('itemType', 2);
        $CI->db->or_where('itemType', 3);
        $item = $CI->db->get()->result_array();
        if ($status) {
            $item_arr = array('' => 'Select Item');
        } else {
            $item_arr = '';
        }
        if (isset($item)) {
            foreach ($item as $row) {
                $item_arr[trim($row['mfqItemID'])] = trim($row['itemSystemCode']) . ' - ' . trim($row['itemDescription']);
            }
        }

        return $item_arr;
    }
}

if (!function_exists('get_finishedgoods_drop')) {
    function get_finishedgoods_drop($status = TRUE)
    {
        $CI =& get_instance();
        $CI->db->select("mfqItemID,itemSystemCode,itemDescription");
        $CI->db->from('srp_erp_mfq_itemmaster');
        $CI->db->where('companyID', current_companyID());
        $CI->db->where('itemType', 2);
        $CI->db->or_where('itemType', 3);
        $output = $CI->db->get()->result_array();
        if ($status) {
            $result = array('' => 'Select Product');
        } else {
            $result = '';
        }
        if (!empty($output)) {
            foreach ($output as $row) {
                $result[$row['mfqItemID']] = $row['itemSystemCode'] . ' - ' . $row['itemDescription'];
            }
        }
        return $result;
    }
}


if (!function_exists('link_job_card_drop')) {
    function link_job_card_drop($templateMasterID = null, $templateDetailID = null, $status = TRUE)/*Load all Bom*/
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->from('srp_erp_mfq_templatedetail');
        $CI->db->where('workFlowID', 1);
        $CI->db->where('templateMasterID', $templateMasterID);
        $CI->db->where('templateDetailID <', $templateDetailID);
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $job = $CI->db->get()->result_array();
        if ($status) {
            $job_arr = array('' => 'Select Job card');
        } else {
            $job_arr = '';
        }
        if (isset($job)) {
            foreach ($job as $row) {
                $job_arr[trim($row['templateDetailID'])] = trim($row['description']);
            }
        }

        return $job_arr;
    }
}


if (!function_exists('check_link_job_card')) {
    function check_link_job_card($templateMasterID = null, $templateDetailID = null)/*Load all Bom*/
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->from('srp_erp_mfq_templatedetail');
        $CI->db->where('workFlowID', 1);
        $CI->db->where('templateMasterID', $templateMasterID);
        $CI->db->where('templateDetailID <', $templateDetailID);
        $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
        $job = $CI->db->get()->result_array();
        return $job;
    }
}


if (!function_exists('get_prev_job_card')) {
    function get_prev_job_card($workProcessID, $workFlowID, $linkworkFlowID, $templateDetailID, $templateMasterID)
    {
        $CI =& get_instance();
        $CI->db->select("*");
        $CI->db->from("srp_erp_mfq_customtemplatedetail");
        $CI->db->where("jobID", $workProcessID);
        $job = $CI->db->get()->result_array();
        $output = "";
        $output2 = "";
        if ($job) {

            $CI->db->select("jobcardID,srp_erp_mfq_customtemplatedetail.description,jobNo,quotationRef,srp_erp_mfq_jobcardmaster.description as jobDescription");
            $CI->db->from('srp_erp_mfq_jobcardmaster');
            $CI->db->join('srp_erp_mfq_customtemplatedetail', 'srp_erp_mfq_jobcardmaster.templateDetailID = srp_erp_mfq_customtemplatedetail.templateDetailID', 'inner');
            $CI->db->where('workProcessID', $workProcessID);
            $CI->db->where('srp_erp_mfq_jobcardmaster.workFlowID', $workFlowID);
            $CI->db->where('srp_erp_mfq_jobcardmaster.templateDetailID', $linkworkFlowID);
            $output = $CI->db->get()->row_array();

            $CI->db->select("jobcardID");
            $CI->db->from('srp_erp_mfq_customtemplatedetail');
            $CI->db->join('srp_erp_mfq_jobcardmaster', 'srp_erp_mfq_jobcardmaster.templateDetailID = srp_erp_mfq_customtemplatedetail.linkWorkFlow', 'inner');
            $CI->db->where('workProcessID', $workProcessID);
            $CI->db->where('srp_erp_mfq_jobcardmaster.workFlowID', $workFlowID);
            $CI->db->where('srp_erp_mfq_customtemplatedetail.templateDetailID <=', $templateDetailID);
            $CI->db->where('srp_erp_mfq_customtemplatedetail.templateMasterID', $templateMasterID);
            $output2 = $CI->db->get()->result_array();
            $output2 = array_column($output2, 'jobcardID');
        } else {
            $CI =& get_instance();
            $CI->db->select("jobcardID,srp_erp_mfq_templatedetail.description,jobNo,quotationRef,srp_erp_mfq_jobcardmaster.description as jobDescription");
            $CI->db->from('srp_erp_mfq_jobcardmaster');
            $CI->db->join('srp_erp_mfq_templatedetail', 'srp_erp_mfq_jobcardmaster.templateDetailID = srp_erp_mfq_templatedetail.templateDetailID', 'inner');
            $CI->db->where('workProcessID', $workProcessID);
            $CI->db->where('srp_erp_mfq_jobcardmaster.workFlowID', $workFlowID);
            $CI->db->where('srp_erp_mfq_jobcardmaster.templateDetailID', $linkworkFlowID);
            $output = $CI->db->get()->row_array();

            $CI->db->select("jobcardID");
            $CI->db->from('srp_erp_mfq_templatedetail');
            $CI->db->join('srp_erp_mfq_jobcardmaster', 'srp_erp_mfq_jobcardmaster.templateDetailID = srp_erp_mfq_templatedetail.linkWorkFlow', 'inner');
            $CI->db->where('workProcessID', $workProcessID);
            $CI->db->where('srp_erp_mfq_jobcardmaster.workFlowID', $workFlowID);
            $CI->db->where('srp_erp_mfq_templatedetail.templateDetailID <=', $templateDetailID);
            $CI->db->where('srp_erp_mfq_templatedetail.templateMasterID', $templateMasterID);
            $output2 = $CI->db->get()->result_array();
            $output2 = array_column($output2, 'jobcardID');
        }

        if ($output2) {
            $CI->db->select("SUM(materialCost) as materialCost,SUM(materialCharge) as materialCharge");
            $CI->db->from('srp_erp_mfq_jc_materialconsumption');
            $CI->db->join('srp_erp_mfq_itemmaster', 'srp_erp_mfq_jc_materialconsumption.mfqItemID = srp_erp_mfq_itemmaster.mfqItemID', 'inner');
            $CI->db->join('srp_erp_unit_of_measure', 'srp_erp_unit_of_measure.UnitID = srp_erp_mfq_itemmaster.defaultUnitOfMeasureID', 'inner');
            $CI->db->where_in('jobCardID', $output2);
            $result = $CI->db->get()->row_array();
            $data["materialConsumption"] = $result;
        } else {
            $data["materialConsumption"] = 0;
        }

        if ($output2) {
            $CI->db->select("SUM(totalValue) as totalValue");
            $CI->db->from('srp_erp_mfq_jc_labourtask');
            $CI->db->join('srp_erp_mfq_overhead', 'srp_erp_mfq_overhead.overHeadID = srp_erp_mfq_jc_labourtask.labourTask', 'inner');
            $CI->db->where_in('jobCardID', $output2);
            $result = $CI->db->get()->row_array();
            $data["labourTask"] = $result;
        } else {
            $data["labourTask"] = 0;
        }

        if ($output2) {
            $CI->db->select("SUM(totalValue) as totalValue");
            $CI->db->from('srp_erp_mfq_jc_overhead');
            $CI->db->join('srp_erp_mfq_overhead', 'srp_erp_mfq_overhead.overHeadID = srp_erp_mfq_jc_overhead.overheadID', 'inner');
            $CI->db->where_in('jobCardID', $output2);
            $result = $CI->db->get()->row_array();
            $data["overheadCost"] = $result;
        } else {
            $data["overheadCost"] = 0;
        }

        if ($output2) {
            $CI->db->select("SUM(totalValue) as totalValue");
            $CI->db->from('srp_erp_mfq_jc_machine');
            $CI->db->join('srp_erp_mfq_fa_asset_master', 'srp_erp_mfq_jc_machine.mfq_faID = srp_erp_mfq_fa_asset_master.mfq_faID', 'inner');
            $CI->db->where_in('jobCardID', $output2);
            $result = $CI->db->get()->row_array();
            $data["machineCost"] = $result;
        } else {
            $data["machineCost"] = 0;
        }

        $data["jobcard"] = $output;

        return $data;

    }

    if (!function_exists('job_status')) {
        function job_status($status)
        {

            if ($status >= 0 && $status <= 25) {
                return '<div class="progress"><div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="' . round($status) . '" aria-valuemin="0" aria-valuemax="100" style="width:' . round($status) . '%;color:black;font-weight:bold">' . round($status) . '%</div></div>';
            } else if ($status >= 25 && $status <= 50) {
                return '<div class="progress"><div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="' . round($status) . '" aria-valuemin="0" aria-valuemax="100" style="width:' . round($status) . '%;font-weight:bold">' . round($status) . '%</div></div>';
            } else if ($status >= 50 && $status <= 75) {
                return '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="' . round($status) . '" aria-valuemin="0" aria-valuemax="100" style="width:' . round($status) . '%;font-weight:bold">' . round($status) . '%</div></div>';
            } else if ($status >= 75 && $status <= 100) {
                return '<div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="' . round($status) . '" aria-valuemin="0" aria-valuemax="100" style="width:' . round($status) . '%;font-weight:bold">' . round($status) . '%</div></div>';
            }
        }
    }

    if (!function_exists('fetch_ongoing_jobs')) {
        function fetch_ongoing_jobs()
        {
            $CI =& get_instance();
            $year = date('Y');
            $sql = "SELECT jobID as id,documentCode as text,
	DATE_FORMAT(startDate,'%d-%m-%Y') as start_date,
	DATEDIFF(endDate, startDate) AS duration,
	ws.progress as progress,
	description,'#61cde2' as color
	 FROM srp_erp_mfq_job LEFT JOIN (SELECT jobID,COUNT(*) as totCount,SUM(if(status = 1,1,0)) as completedCount,(SUM(if(status = 1,1,0))/COUNT(*)) * 100 as percentage,(SUM(if(status = 1,1,0))/COUNT(*)) * 1 as progress FROM srp_erp_mfq_workflowstatus GROUP BY jobID) ws ON ws.jobID = srp_erp_mfq_job.workProcessID WHERE ws.percentage < 100";
            $result = $CI->db->query($sql)->result_array();
            echo json_encode($result);
        }
    }

    if (!function_exists('fetch_mfq_segment')) {
        function fetch_mfq_segment($id = TRUE, $state = TRUE) /*$id parameter is used to display only ID as value in select option*/
        {
            $CI =& get_instance();
            $CI->db->select('segmentCode,description,mfqSegmentID');
            $CI->db->from('srp_erp_mfq_segment');
            $CI->db->where('status', 1);
            $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
            $data = $CI->db->get()->result_array();
            if ($state == TRUE) {
                $data_arr = array('' => 'Select Segment');
            } else {
                $data_arr = '';
            }
            if (isset($data)) {
                foreach ($data as $row) {
                    if ($id) {
                        $data_arr[trim($row['mfqSegmentID'])] = trim($row['segmentCode']) . ' | ' . trim($row['description']);
                    } else {
                        $data_arr[trim($row['mfqSegmentID']) . '|' . trim($row['segmentCode'])] = trim($row['segmentCode']) . ' | ' . trim($row['description']);
                    }

                }
            }

            return $data_arr;
        }
    }

    if (!function_exists('customerInquiryStatus')) {
        function customerInquiryStatus($confirmedYN, $approvedYN)
        {
            $status = '<div style="text-align: center">';

            if ($approvedYN == 1) {
                $status .= '<span class="label" style="background-color:#75C181; color:#ffffff; font-size: 11px;">Submitted</span>';
            } else {
                if ($confirmedYN) {
                    $status .= '<span class="label" style="background-color:#EE6363; color:#ffffff; font-size: 11px;">Pending</span>';
                } else {
                    $status .= '<span class="label" style="background-color:#58BBEE; color:#ffffff; font-size: 11px;">Open</span>';
                }
            }
            $status .= '</div>';

            return $status;
        }
    }

    if (!function_exists('get_customerinquiry_status')) {
        function get_customerinquiry_status($confirmedYN, $plannedDate, $isMailSent)
        {
            $status = '<div style="text-align: center">';

            if ($isMailSent == 1) {
                $status .= '<span class="label" style="background-color:#75C181; color:#ffffff; font-size: 11px;">Submitted</span>';
            } else {
                if ($plannedDate < date('Y-m-d')) {
                    $status .= '<span class="label" style="background-color:#EE6363; color:#ffffff; font-size: 11px;">Overdue</span>';
                } else {
                    $status .= '<span class="label" style="background-color:#58BBEE; color:#ffffff; font-size: 11px;">Open</span>';
                }
            }
            $status .= '</div>';

            return $status;
        }
    }

    if (!function_exists('get_job_status')) {
        function get_job_status($confirmedYN)
        {
            $status = '<div style="text-align: center">';
            if ($confirmedYN == 1) {
                $status .= '<span class="label" style="background-color:#75C181; color:#ffffff; font-size: 11px;">Closed</span>';
            } else {
                $status .= '<span class="label" style="background-color:#58BBEE; color:#ffffff; font-size: 11px;">Open</span>';
            }
            $status .= '</div>';

            return $status;
        }
    }


    if (!function_exists('get_customerinquiry_submission_status')) {
        function get_customerinquiry_submission_status($description, $statusColor, $statusBackgroundColor)
        {
            return '<span class="label" style="background-color:' . $statusBackgroundColor . '; color:' . $statusColor . '; font-size: 11px;">' . $description . '</span>';
        }
    }

    if (!function_exists('format_number')) {
        function format_number($amount = 0, $decimal_place = 2)
        {
            if (is_null($amount)) {
                $amount = 0;
            }
            if (is_null($decimal_place)) {
                $decimal_place = 2;
            }

            return number_format($amount, $decimal_place);
        }
    }


    if (!function_exists('round_percentage')) {
        function round_percentage($status)
        {
            return round($status);
        }
    }

    if (!function_exists('all_mfq_documents')) {
        function all_mfq_documents($status = true)
        {
            $CI =& get_instance();
            $CI->db->SELECT("documentID,description");
            $CI->db->FROM('srp_erp_mfq_documents');
            $CI->db->where('isActive', 1);
            $data = $CI->db->get()->result_array();
            if ($status) {
                $data_arr = array('' => 'Select a Document');
            } else {
                $data_arr = '';
            }
            if (isset($data)) {
                foreach ($data as $row) {
                    $data_arr[trim($row['documentID'])] = trim($row['description']);
                }
            }
            return $data_arr;
        }
    }

    if (!function_exists('statuscolor')) {
        function statuscolor($statuscolor)
        {
            return '<span class="label" style="background-color: ' . $statuscolor . '">&nbsp;</span>';
        }
    }

    if (!function_exists('all_mfq_status')) {
        function all_mfq_status($documentID, $status = true)
        {
            $CI =& get_instance();
            $CI->db->SELECT("statusID,description");
            $CI->db->FROM('srp_erp_mfq_status');
            $CI->db->where('documentID', $documentID);
            //$CI->db->where('companyID', current_companyID());
            $data = $CI->db->get()->result_array();
            if ($status) {
                $data_arr = array('' => 'Select a Status');
            } else {
                $data_arr = '';
            }
            if (isset($data)) {
                foreach ($data as $row) {
                    $data_arr[trim($row['statusID'])] = trim($row['description']);
                }
            }
            return $data_arr;
        }
    }


    if (!function_exists('mfq_status')) {
        function mfq_status($description, $statusColor, $statusBackgroundColor)
        {
            return '<span class="label" style="background-color:' . $statusBackgroundColor . '; color:' . $statusColor . '; font-size: 11px;">' . $description . '</span>';
        }
    }

    if (!function_exists('all_mfq_warehouse_drop')) {
        function all_mfq_warehouse_drop($status = true)
        {
            $CI =& get_instance();
            $CI->db->SELECT("mfqWarehouseAutoID,srp_erp_mfq_warehousemaster.warehouseDescription");
            $CI->db->FROM('srp_erp_mfq_warehousemaster');
            $CI->db->join('srp_erp_warehousemaster', "srp_erp_mfq_warehousemaster.warehouseAutoID = srp_erp_warehousemaster.wareHouseAutoID", "left");
            $CI->db->where('srp_erp_mfq_warehousemaster.companyID', current_companyID());
            $CI->db->where('srp_erp_mfq_warehousemaster.warehouseAutoID IS NOT NULL');
            $CI->db->where('warehouseType', 2);
            $data = $CI->db->get()->result_array();
            if ($status) {
                $data_arr = array('' => 'Select a Warehouse');
            } else {
                $data_arr = '';
            }
            if (isset($data)) {
                foreach ($data as $row) {
                    $data_arr[trim($row['mfqWarehouseAutoID'])] = trim($row['warehouseDescription']);
                }
            }
            return $data_arr;
        }
    }

    if (!function_exists('approval_action')) {
        function approval_action($autoID, $approvalLevelID, $approvedYN, $documentApprovedID, $documentID,$jobID = null,$finalApproval = null,$postingFinanceDate = null)
        {
            $status = '<span class="pull-right">';
            if ($approvedYN == 0) {
                $status .= '<a onclick=\'fetch_approval("' . $autoID . '","' . $documentApprovedID . '","' . $approvalLevelID . '","' . $jobID . '","' . $finalApproval . '","' . $postingFinanceDate . '"); \'><span title="View" rel="tooltip" class="glyphicon glyphicon-ok"></span></a>&nbsp;&nbsp;';
            } else {
                $status .= '<a target="_blank" onclick="documentPageView_modal(\'' . $documentID . '\',\'' . $autoID . '\',\'' . $jobID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>&nbsp;&nbsp;';
            }
            $status .= '</span>';

            return $status;
        }
    }


    /*    if (!function_exists('customer_inquiry_approval_status')) {
            function customer_inquiry_approval_status($approved_status, $confirmed_status, $statusID, $autoID, $code)
            {
                $status = '<center>';
                if ($statusID == 3) {
                    $status .= '<span class="label" style="background-color:#ff851b; color:#ffffff; font-size: 11px;">Declined</span>';
                } else {
                    if ($approved_status == 0) {
                        if ($confirmed_status == 0 || $confirmed_status == 3) {
                            $status .= '<span class="label label-danger">Pending</span>';
                        } else if ($confirmed_status == 2) {
                            $status .= '<a onclick="fetch_approval_reject_user_modal(\'' . $code . '\',' . $autoID . ')" class="label label-danger"> Pending ';
                            $status .= '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>';
                        } else {
                            $status .= '<a onclick="fetch_all_approval_users_modal(\'' . $code . '\',' . $autoID . ')" class="label label-danger"> Pending ';
                            $status .= '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>';
                        }
                    } elseif ($approved_status == 1) {
                        if ($confirmed_status == 1) {
                            $status .= '<a onclick="fetch_approval_user_modal(\'' . $code . '\',' . $autoID . ')" class="label label-success"> Approved ';
                            $status .= '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>';
                        } else {
                            $status .= '<span class="label label-success">&nbsp;</span>';
                        }
                    } elseif ($approved_status == 2) {
                        $status .= '<span class="label label-warning">&nbsp;</span>';
                    } elseif ($approved_status == 6) {
                        $fn = 'onclick="fetch_approval_reject_user_modal(\'' . $code . '\',' . $autoID . ')"';
                        $status .= '<span class="label label-info cancel-pop-up" ' . $fn . '><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></span>';
                    } else {
                        $status .= '-';
                    }
                }
                $status .= '</center>';

                return $status;
            }
        }*/

    if (!function_exists('customer_inquiry_approval_status')) {
        function customer_inquiry_approval_status($approved_status, $confirmed_status, $statusID, $autoID, $code)
        {
            $status = '<center>';
            if ($statusID == 3) {
                $status .= '<span class="label" style="background-color:#ff851b; color:#ffffff; font-size: 11px;">Declined</span>';
            } else {
                if ($approved_status == 0) {
                    if ($confirmed_status == 0 || $confirmed_status == 3) {
                        $status .= '<span class="label label-danger">Pending</span>';
                    } else if ($confirmed_status == 2) {
                        $status .= '<a href="#" class="label label-danger"> Pending ';
                    } else {
                        $status .= '<a href="#" class="label label-danger"> Pending ';
                    }
                } elseif ($approved_status == 1) {
                    if ($confirmed_status == 1) {
                        $status .= '<a href="#" class="label label-success"> Approved ';
                    } else {
                        $status .= '<span class="label label-success">&nbsp;</span>';
                    }
                } elseif ($approved_status == 2) {
                    $status .= '<span class="label label-warning">&nbsp;</span>';
                } elseif ($approved_status == 6) {
                    //$fn = 'onclick="fetch_approval_reject_user_modal(\'' . $code . '\',' . $autoID . ')"';
                    $status .= '<span class="label label-info cancel-pop-up"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></span>';
                } else {
                    $status .= '-';
                }
            }
            $status .= '</center>';

            return $status;
        }
    }

    if (!function_exists('estimate_approval_status')) {
        function estimate_approval_status($approved_status, $confirmed_status, $submissionStatus, $autoID, $code)
        {
            $status = '<center>';

            if ($approved_status == 0) {
                if ($confirmed_status == 0 && $submissionStatus == 6) {
                    $status .= '<span class="label label-warning">Revised</span>';
                } else if ($confirmed_status == 0 || $confirmed_status == 3) {
                    $status .= '<span class="label label-danger">Pending</span>';
                } else if ($confirmed_status == 2) {
                    $status .= '<a onclick="fetch_approval_reject_user_modal(\'' . $code . '\',' . $autoID . ')" class="label label-warning"> Rejected ';
                    $status .= '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>';
                } else {
                    if ($submissionStatus == 6) {
                        $status .= '<a onclick="fetch_all_approval_users_modal(\'' . $code . '\',' . $autoID . ')" class="label label-warning"> Revised ';
                        $status .= '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>';
                    } else {
                        $status .= '<a onclick="fetch_all_approval_users_modal(\'' . $code . '\',' . $autoID . ')" class="label label-danger"> Pending ';
                        $status .= '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>';
                    }
                }
            } elseif ($approved_status == 1) {
                if ($confirmed_status == 1) {
                    if ($submissionStatus == 6) {
                        $status .= '<a onclick="fetch_approval_user_modal(\'' . $code . '\',' . $autoID . ')" class="label label-warning"> Revised ';
                        $status .= '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>';
                    } else {
                        $status .= '<a onclick="fetch_approval_user_modal(\'' . $code . '\',' . $autoID . ')" class="label label-success"> Approved ';
                        $status .= '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>';
                    }
                } else {
                    $status .= '<span class="label label-success">&nbsp;</span>';
                }
            } elseif ($approved_status == 2) {
                $status .= '<span class="label label-warning">&nbsp;</span>';
            } elseif ($approved_status == 6) {
                $fn = 'onclick="fetch_approval_reject_user_modal(\'' . $code . '\',' . $autoID . ')"';
                $status .= '<span class="label label-info cancel-pop-up" ' . $fn . '><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></span>';
            } else {
                $status .= '-';
            }

            $status .= '</center>';

            return $status;
        }
    }

    if (!function_exists('approval_status')) {
        function approval_status($approvedYN, $confirmedYN = null)
        {
            $status = '<div style="text-align: center">';

            if ($approvedYN == 1) {
                $status .= '<span class="label" style="background-color:#75C181; color:#ffffff; font-size: 11px;">Approved</span>';
            } else {
                $status .= '<span class="label" style="background-color:#EE6363; color:#ffffff; font-size: 11px;">Not Approved</span>';
            }
            $status .= '</div>';

            return $status;
        }
    }

    if (!function_exists('confirmation_status')) {
        function confirmation_status($confirmedYN)
        {
            $status = '<div style="text-align: center">';

            if ($confirmedYN == 1) {
                $status .= '<span class="label" style="background-color:#75C181; color:#ffffff; font-size: 11px;">Confirmed</span>';
            } else {
                $status .= '<span class="label" style="background-color:#EE6363; color:#ffffff; font-size: 11px;">Not Confirmed</span>';
            }
            $status .= '</div>';

            return $status;
        }
    }

    if (!function_exists('get_all_mfq_template')) {
        function get_all_mfq_template()
        {
            $CI =& get_instance();
            $CI->db->select("*");
            $CI->db->from('srp_erp_mfq_templatemaster');
            $template = $CI->db->get()->result_array();
            return $template;
        }
    }

    if (!function_exists('all_mfq_month_drop')) {
        function all_mfq_month_drop($status = true)
        {
            $CI =& get_instance();
            $CI->db->SELECT("*");
            $CI->db->FROM('srp_months');
            $data = $CI->db->get()->result_array();
            if ($status) {
                $data_arr = array('' => 'Select a Month');
            } else {
                $data_arr = '';
            }
            if (isset($data)) {
                foreach ($data as $row) {
                    if ($row['MonthId'] > 1) {
                        $data_arr[trim($row['MonthId'])] = trim($row['MonthId']) . " Months";
                    } else {
                        $data_arr[trim($row['MonthId'])] = trim($row['MonthId']) . " Month";
                    }
                }
            }
            return $data_arr;
        }
    }

    if (!function_exists('all_mfq_jobs_drop')) {
        function all_mfq_jobs_drop($status = TRUE)/*Load all Jobs*/
        {
            $CI =& get_instance();
            $CI->db->select("workProcessID,documentCode");
            $CI->db->from('srp_erp_mfq_job job');
            $CI->db->join('srp_erp_mfq_estimatedetail estd', 'estd.estimateDetailID = job.estimateDetailID');
            $CI->db->join('srp_erp_mfq_estimatemaster estm', 'estd.estimateMasterID = estm.estimateMasterID');
            $CI->db->where('job.companyID', $CI->common_data['company_data']['company_id']);
            $CI->db->where('estm.orderStatus', 1);
            $CI->db->where('job.approvedYN', 1);
            $jobs = $CI->db->get()->result_array();
            if ($status) {
                $jobs_arr = array('' => 'Select Job');
            } else {
                $jobs_arr = '';
            }
            if (isset($jobs)) {
                foreach ($jobs as $row) {
                    $jobs_arr[trim($row['workProcessID'])] = (trim($row['documentCode']));
                }
            }

            return $jobs_arr;
        }
    }

    if (!function_exists('load_delivery_note_action')) {
        function load_delivery_note_action($poID, $confirmedYN, $approved, $createdUserID)
        {
            $CI =& get_instance();
            $CI->load->library('session');
            $status = '<span class="pull-right">';

            if ($confirmedYN != 1) {
                $status .= '<a onclick=\'fetchPage("system/mfq/mfq_delivery_note_create",' . $poID . ',"Edit Delivery Note","MFQ"); \'><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
            }

            if ($createdUserID == trim($CI->session->userdata("empID")) and $approved == 0 and $confirmedYN == 1) {
                //$status .= '<a onclick="referBack_delivery_note(' . $poID . ');"><span title="Refer Back" rel="tooltip" class="glyphicon glyphicon-repeat" style="color:rgb(209, 91, 71);"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
            }

            $status .= '<a target="_blank" onclick="view_delivery_note(\'' . $poID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>';

            $status .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a target="_blank" href="' . site_url('MFQ_DeliveryNote/load_deliveryNote_confirmation/') . $poID . '" ><span title="Print" rel="tooltip" class="glyphicon glyphicon-print"></span></a> ';

            if ($confirmedYN != 1) {
                $status .= '&nbsp;&nbsp;| &nbsp;&nbsp;<a onclick="delete_delivery_note(' . $poID . ',\'Invoices\');"><span title="Delete" rel="tooltip" class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span></a>';
            }

            $status .= '</span>';

            return $status;
        }
    }

    if (!function_exists('fetch_all_mfq_gl_codes')) {
        function fetch_all_mfq_gl_codes($code = NULL)
        {
            $CI =& get_instance();
            $CI->db->SELECT("GLAutoID,systemAccountCode,GLSecondaryCode,GLDescription,subCategory,accountCategoryTypeID");
            $CI->db->from('srp_erp_chartofaccounts');
            if ($code) {
                $CI->db->where('subCategory', $code);
            }
            $CI->db->where('controllAccountYN', 0);
            $CI->db->WHERE('masterAccountYN', 0);
            // $CI->db->WHERE('accountCategoryTypeID !=', 4);
            //$CI->db->where('approvedYN', 1);
            $CI->db->where('isActive', 1);
            $CI->db->where('isBank', 0);
            $CI->db->where('companyID', $CI->common_data['company_data']['company_id']);
            $CI->db->limit(425);
            $data = $CI->db->get()->result_array();
            $data_arr = array('' => 'Select GL Code');
            if (isset($data)) {
                foreach ($data as $row) {
                    $data_arr[trim($row['GLAutoID'])] = trim($row['GLSecondaryCode']) . ' | ' . htmlspecialchars(trim($row['GLDescription']), ENT_QUOTES) . ' | ' . trim($row['subCategory']);
                }
            }

            return $data_arr;
        }
    }

    if (!function_exists('editCustomerInvoice')) {
        function editCustomerInvoice($invoiceAutoID, $confirmedYN, $approvedYN)
        {
            $status = '<span class="pull-right">';
            $status .= '<a target="_blank" onclick="viewDocument(\'' . $invoiceAutoID . '\')" ><span title="View" rel="tooltip" class="glyphicon glyphicon-eye-open"></span></a>';
            if ($confirmedYN != 1) {
                $status .= '&nbsp;&nbsp;|&nbsp;&nbsp;<span class="pull-right"><a href="#" onclick="fetchPage(\'system/mfq/mfq_add_customer_invoice\',' . $invoiceAutoID . ',\'Edit Customer Invoice\',\'MCINV\')"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a></span>';
            }
            $status .= '</span>';
            return $status;
        }
    }

    if (!function_exists('usergroupstatus')) {
        function usergroupstatus($isActive)
        {
            return ($isActive == 1) ? '<span class="label" style="background-color:#75C181; color:#ffffff; font-size: 11px;">Active</span>' : '<span class="label" style="background-color:#EE6363; color:#ffffff; font-size: 11px;">In Active</span>';
        }
    }

    if (!function_exists('all_mfq_usergroup_drop')) {
        function all_mfq_usergroup_drop($status = true)
        {
            $CI =& get_instance();
            $CI->db->SELECT("*");
            $CI->db->FROM('srp_erp_mfq_usergroups');
            $CI->db->where('companyID', current_companyID());
            $CI->db->where('isActive', 1);
            $data = $CI->db->get()->result_array();
            if ($status) {
                $data_arr = array('' => 'Select a Usergroup');
            } else {
                $data_arr = '';
            }
            if (isset($data)) {
                foreach ($data as $row) {
                    $data_arr[trim($row['userGroupID'])] = trim($row['description']);
                }
            }
            return $data_arr;
        }
    }
    if (!function_exists('isdefaultstatus')) {
        function isdefaultstatus($isDefault)
        {
            return ($isDefault == 1) ? '<span class="label" style="background-color:#75C181; color:#ffffff; font-size: 11px;">Default</span>' : '<span class="label" style="background-color:#EE6363; color:#ffffff; font-size: 11px;">Is Default</span>';
        }
    }

    if (!function_exists('getStandardDetail')) {
        function getStandardDetail()
        {
            $CI =& get_instance();
            $CI->db->SELECT("*");
            $CI->db->FROM('srp_erp_mfq_standarddetailsmaster');
            $CI->db->where('companyID', current_companyID());
            $data = $CI->db->get()->result_array();
            return $data;
        }
    }

    if (!function_exists('generateSubJobCode')) {
        function generateSubJobCode()
        {
            $CI =& get_instance();
            $CI->db->SELECT("*");
            $CI->db->FROM('srp_erp_mfq_standarddetailsmaster');
            $CI->db->where('companyID', current_companyID());
            $data = $CI->db->get()->result_array();
            return $data;
        }
    }

    if (!function_exists('get_specific_mfq_item')) {
        function get_specific_mfq_item($itemID)
        {
            $CI =& get_instance();
            $CI->db->SELECT("*");
            $CI->db->FROM('srp_erp_mfq_itemmaster');
            $CI->db->where('mfqItemID', $itemID);
            $data = $CI->db->get()->row_array();
            return $data;
        }
    }

    if (!function_exists('fetch_materialCertificate')) {
        function fetch_materialCertificate()
        {
            $CI =& get_instance();
            $CI->db->select("*");
            $CI->db->from('srp_erp_mfq_materialcertificatemaster');
            $CI->db->where('companyID',current_companyID());
            $certificate = $CI->db->get()->result_array();
            $certificateArr = [];
            if (isset($certificate)) {
                foreach ($certificate as $row) {
                    $certificateArr[trim($row['materialCertificateID'])] = (trim($row['Description']));
                }
            }
            return $certificateArr;
        }
    }
}