<?php

class CrmLead extends ERP_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Crm_lead_model');
        $this->load->helper('crm_helper');
    }

    function load_leadManagement_view()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $currentuserID = current_userID();
        $convertFormat = convert_date_format_sql();
        $text = trim($this->input->post('searchTask'));
        $sorting = trim($this->input->post('filtervalue'));
        $status = trim($this->input->post('status'));
        $issuperadmin = crm_isSuperAdmin();
        $search_string = '';
        if (isset($text) && !empty($text)) {
            $search_string = " AND (firstName Like '%" . $text . "%') OR (lastName Like '%" . $text . "%') OR (srp_erp_crm_leadmaster.email Like '%" . $text . "%')";
        }
        $filterStatus = '';
        if (isset($status) && !empty($status)) {
            $filterStatus = " AND srp_erp_crm_leadmaster.statusID = " . $status . "";
        }
        $search_sorting = '';
        if (isset($sorting) && $sorting != '#') {
            $search_sorting = " AND firstName Like '" . $sorting . "%'";
        }
        if ($issuperadmin['isSuperAdmin'] == 1) {

            $where_admin = "srp_erp_crm_leadmaster.companyID = " . $companyid . $search_string . $search_sorting . $filterStatus;

            $data['header'] = $this->db->query("SELECT srp_erp_crm_leadmaster.leadID,firstName,lastName,phoneMobile,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization,srp_erp_crm_leadmaster.createdUserID as createduserlead,srp_erp_crm_leadmaster.responsiblePersonEmpID,srp_erp_crm_leadmaster.createdUserName as createdUserNamelead,DATE_FORMAT(srp_erp_crm_leadmaster.createdDateTime,'" . $convertFormat . "') AS createdDateTimelead FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID WHERE $where_admin ORDER BY leadID DESC ")->result_array();

        } else {
            $where1 = "srp_erp_crm_documentpermission.documentID = 5 AND srp_erp_crm_documentpermission.permissionID = 1 AND srp_erp_crm_leadmaster.companyID = " . $companyid . $search_string . $search_sorting . $filterStatus;

            $where2 = "srp_erp_crm_documentpermission.documentID = 5 AND srp_erp_crm_documentpermission.permissionID = 2 AND (srp_erp_crm_documentpermission.permissionValue = " . $currentuserID . " or srp_erp_crm_leadmaster.createdUserID = " . $currentuserID . ") AND  srp_erp_crm_leadmaster.companyID = " . $companyid . $search_string . $search_sorting . $filterStatus;

            $where3 = "srp_erp_crm_documentpermission.documentID = 5 AND srp_erp_crm_documentpermission.permissionID = 3 AND (srp_erp_crm_usergroupdetails.empID = " . $currentuserID . " or srp_erp_crm_leadmaster.createdUserID = " . $currentuserID . ") AND  srp_erp_crm_leadmaster.companyID = " . $companyid . $search_string . $search_sorting . $filterStatus;

            $where4 = "srp_erp_crm_documentpermission.documentID = 5 AND srp_erp_crm_documentpermission.permissionID = 4 AND (srp_erp_crm_documentpermissiondetails.empID = " . $currentuserID . " or srp_erp_crm_leadmaster.createdUserID = " . $currentuserID . ") AND  srp_erp_crm_leadmaster.companyID = " . $companyid . $search_string . $search_sorting . $filterStatus;

            $convertFormat = convert_date_format_sql();

            $data['header'] = $this->db->query("SELECT srp_erp_crm_leadmaster.leadID,firstName,lastName,phoneMobile,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization,srp_erp_crm_leadmaster.createdUserID as createduserlead,srp_erp_crm_leadmaster.responsiblePersonEmpID,srp_erp_crm_leadmaster.createdUserName as createdUserNamelead,DATE_FORMAT(srp_erp_crm_leadmaster.createdDateTime,'" . $convertFormat . "') AS createdDateTimelead FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_leadmaster.leadID WHERE $where1 UNION SELECT srp_erp_crm_leadmaster.leadID,firstName,lastName,phoneMobile,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization,srp_erp_crm_leadmaster.createdUserID as createduserlead,srp_erp_crm_leadmaster.responsiblePersonEmpID,srp_erp_crm_leadmaster.createdUserName as createdUserNamelead,DATE_FORMAT(srp_erp_crm_leadmaster.createdDateTime,'" . $convertFormat . "') AS createdDateTimelead FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_leadmaster.leadID WHERE $where2 UNION SELECT srp_erp_crm_leadmaster.leadID,firstName,lastName,phoneMobile,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization,srp_erp_crm_leadmaster.createdUserID as createduserlead,srp_erp_crm_leadmaster.responsiblePersonEmpID,srp_erp_crm_leadmaster.createdUserName as createdUserNamelead,DATE_FORMAT(srp_erp_crm_leadmaster.createdDateTime,'" . $convertFormat . "') AS createdDateTimelead FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_leadmaster.leadID LEFT JOIN srp_erp_crm_usergroupdetails ON srp_erp_crm_usergroupdetails.groupMasterID = srp_erp_crm_documentpermission.permissionValue WHERE $where3 UNION SELECT srp_erp_crm_leadmaster.leadID,firstName,lastName,phoneMobile,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization,srp_erp_crm_leadmaster.createdUserID as createduserlead,srp_erp_crm_leadmaster.responsiblePersonEmpID,srp_erp_crm_leadmaster.createdUserName as createdUserNamelead,DATE_FORMAT(srp_erp_crm_leadmaster.createdDateTime,'" . $convertFormat . "') AS createdDateTimelead FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_leadmaster.leadID LEFT JOIN srp_erp_crm_documentpermissiondetails ON srp_erp_crm_documentpermissiondetails.documentPermissionID = srp_erp_crm_documentpermission.documentPermissionID WHERE $where4 ORDER BY leadID DESC ")->result_array();
        }

        $this->load->view('system/crm/ajax/load_lead_master', $data);
    }

    function save_lead_header()
    {

        $organization = trim($this->input->post('organization'));
        $linkorganization = trim($this->input->post('linkorganization'));

        $userPermission = $this->input->post('userPermission');
        $groupid = trim($this->input->post('groupID'));
        $assignemployees[] = $this->input->post('responsiblePersonEmpID');
        $permissionemp = $this->input->post('employees');
        $companyID = $this->common_data['company_data']['company_id'];
        $currentuser[] = current_userID();

        $this->form_validation->set_rules('prefix', 'Prefix', 'trim|required');
        $this->form_validation->set_rules('firstName', 'First Name', 'trim|required');
        $this->form_validation->set_rules('lastName', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('phoneMobile', 'Phone Mobile', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('sourceID', 'Lead Source', 'trim|required');
        $this->form_validation->set_rules('statusID', 'Status', 'trim|required');
        if ($linkorganization == '') {
            $this->form_validation->set_rules('organization', 'Organization', 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            if ($userPermission == 3) {
                if ($groupid == ' ' || empty($groupid)) {
                    echo json_encode(array('w', 'Please select a user group.'));
                }
                if (empty($assignemployees) || $assignemployees == ' ') {
                    echo json_encode(array('w', 'Please select a Assignee.'));
                } else {
                    $grouppermission = $this->db->query("select empID from srp_erp_crm_usergroupdetails where companyID = '{$companyID }' And groupMasterID = '{$groupid}'  AND empID IN (" . join(',', $assignemployees) . ") ")->result_array();
                    $grouppermissionresult = array_column($grouppermission, 'empID');
                    $resultdiff = array_diff($assignemployees, $grouppermissionresult);
                    if (!empty($resultdiff)) {
                        echo json_encode(array('w', 'Selected assignee not in the current user group'));
                    } else {
                        echo json_encode($this->Crm_lead_model->save_lead_header());
                    }
                }
            } else if ($userPermission == 4) {
                if (empty($assignemployees) || $assignemployees == ' ') {
                    echo json_encode(array('w', 'Please select a Assignee.'));
                } else {
                    $resultdifference = array_diff($assignemployees, $permissionemp);
                    if (!empty($resultdifference)) {
                        echo json_encode(array('w', 'Visibility permission not granted for some assignees'));
                    } else {
                        echo json_encode($this->Crm_lead_model->save_lead_header());
                    }
                }
            }else if ($userPermission == 2) {
                $resultdifferencerecord = array_diff($assignemployees, $currentuser);
                if (!empty($resultdifferencerecord)) {
                    echo json_encode(array('w', 'Please select current user as assignee'));
                } else {
                    echo json_encode($this->Crm_lead_model->save_lead_header());
                }

            }else {
                echo json_encode($this->Crm_lead_model->save_lead_header());
            }

        }
    }

    function load_lead_header()
    {
        echo json_encode($this->Crm_lead_model->load_lead_header());
    }

    function delete_lead_master()
    {
        echo json_encode($this->Crm_lead_model->delete_lead_master());
    }

    function delete_opportunity_master()
    {
        echo json_encode($this->Crm_lead_model->delete_opportunity_master());
    }

    function reopen_opportunity_master()
    {
        echo json_encode($this->Crm_lead_model->reopen_opportunity_master());
    }

    function load_leadManagement_editView()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $leadID = trim($this->input->post('leadID'));
        $this->db->select('*,srp_erp_crm_leadmaster.leadID,CountryDes,DATE_FORMAT(srp_erp_crm_leadmaster.createdDateTime,\'' . $convertFormat . '\') AS createdDate,DATE_FORMAT(srp_erp_crm_leadmaster.modifiedDateTime,\'' . $convertFormat . '\') AS modifydate, srp_erp_crm_leadmaster.description as leadDescription,srp_erp_crm_leadstatus.description as statusdescription,srp_erp_crm_users.employeeName as responsiblePerson, srp_erp_crm_source.description as sourceDescription,srp_erp_crm_leadmaster.createdUserName as leadCreatedUser,srp_erp_crm_organizations.Name as linkedOrganizationName,srp_erp_crm_leadmaster.website as leadWebsite,srp_erp_crm_leadmaster.createdUserID as crtduser');
        $this->db->where('srp_erp_crm_leadmaster.leadID', $leadID);
        $this->db->from('srp_erp_crm_leadmaster');
        $this->db->join('srp_erp_crm_leadstatus', 'srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID', 'LEFT');
        $this->db->join('srp_erp_countrymaster', 'srp_erp_countrymaster.countryID = srp_erp_crm_leadmaster.countryID', 'LEFT');
        $this->db->join('srp_erp_crm_users', 'srp_erp_crm_users.employeeID = srp_erp_crm_leadmaster.responsiblePersonEmpID', 'LEFT');
        $this->db->join('srp_erp_crm_source', 'srp_erp_crm_source.sourceID = srp_erp_crm_leadmaster.sourceID', 'LEFT');
        $this->db->join('srp_erp_crm_organizations', 'srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID', 'LEFT');
        $data['header'] = $this->db->get()->row_array();

        $this->db->select('employeeID as isadmin');
        $this->db->from('srp_erp_crm_users');
        $this->db->where('companyID', $companyID);
        $this->db->where('isSuperAdmin', 1);
        $data['superadmn'] = $this->db->get()->row_array();

        $this->load->view('system/crm/ajax/load_lead_edit_view', $data);
    }

    function lead_image_upload()
    {
        $this->form_validation->set_rules('leadID', 'Lead ID is missing', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Crm_lead_model->lead_image_upload());
        }
    }

    function load_lead_all_notes()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $leadID = trim($this->input->post('leadID'));

        $where = "companyID = " . $companyid . " AND documentID = 5 AND contactID = " . $leadID . "";
        $convertFormat = convert_date_format_sql();
        $this->db->select('*');
        $this->db->from('srp_erp_crm_contactnotes');
        $this->db->where($where);
        $this->db->order_by('notesID', 'desc');
        $data['notes'] = $this->db->get()->result_array();
        $this->load->view('system/crm/ajax/load_lead_notes', $data);
    }

    function add_lead_notes()
    {
        $this->form_validation->set_rules('leadID', 'lead ID', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Crm_lead_model->add_lead_notes());
        }
    }

    function add_lead_product()
    {
        $this->form_validation->set_rules('leadID', 'lead ID', 'trim|required');
        $this->form_validation->set_rules('productID', 'Product Name', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('transactionCurrencyID', 'Transaction Currency', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Crm_lead_model->add_lead_product());
        }
    }

    function load_lead_all_attachments()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $leadID = trim($this->input->post('leadID'));

        $where = "companyID = " . $companyid . " AND documentID = 5  AND documentAutoID = " . $leadID . "";
        $convertFormat = convert_date_format_sql();
        $this->db->select('*');
        $this->db->from('srp_erp_crm_attachments');
        $this->db->where($where);
        $this->db->order_by('attachmentID', 'desc');
        $data['attachment'] = $this->db->get()->result_array();
        $this->load->view('system/crm/ajax/load_all_lead_attachements', $data);
    }

    function load_lead_all_tasks()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $leadID = trim($this->input->post('leadID'));

        $where = "srp_erp_crm_link.companyID = " . $companyID . " AND srp_erp_crm_link.relatedDocumentID = 5 AND srp_erp_crm_link.relatedDocumentMasterID = " . $leadID . " AND srp_erp_crm_link.documentID=2 ";

        $this->db->select('srp_erp_crm_task.taskID,srp_erp_crm_task.subject,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,visibility,DATE_FORMAT(starDate,\'%D-%b-%y %h:%i %p\') AS starDate,DATE_FORMAT(DueDate,\'%D-%b-%y %h:%i %p\') AS DueDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_assignees.empID,srp_erp_crm_task.progress,srp_erp_crm_task.status,srp_erp_crm_task.Priority');
        $this->db->from('srp_erp_crm_link');
        $this->db->join('srp_erp_crm_task', 'srp_erp_crm_task.taskID = srp_erp_crm_link.MasterAutoID', 'LEFT');
        $this->db->join('srp_erp_crm_categories', 'srp_erp_crm_categories.categoryID = srp_erp_crm_task.categoryID', 'LEFT');
        $this->db->join('srp_erp_crm_status', 'srp_erp_crm_status.statusID = srp_erp_crm_task.status', 'LEFT');
        $this->db->join('srp_erp_crm_assignees', 'srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_task.taskID', 'LEFT');
        $this->db->where($where);
        $this->db->group_by('taskID');
        $this->db->order_by('taskID', 'desc');
        $data['tasks'] = $this->db->get()->result_array();
        $data['masterID'] = $leadID;
        $this->load->view('system/crm/ajax/load_lead_tasks', $data);
    }

    function load_leads_all_product()
    {
        $companyid = $this->common_data['company_data']['company_id'];

        $leadID = trim($this->input->post('leadID'));

        $where = "srp_erp_crm_leadproducts.companyID = " . $companyid . " AND leadID = " . $leadID . "";
        $this->db->select('leadProductID,srp_erp_crm_products.productName,srp_erp_crm_leadproducts.productDescription,price,srp_erp_currencymaster.CurrencyCode as reportingcurrency,companyReportingCurrencyExchangeRate,companyReportingCurrencyDecimalPlaces,transactioncurrency.CurrencyCode as transactioncurrency,transactionExchangeRate,transactionCurrencyDecimalPlaces,companylocalcurrency.CurrencyCode as currencycodelocal,companyLocalCurrencyExchangeRate,companyLocalCurrencyDecimalPlaces');
        $this->db->from('srp_erp_crm_leadproducts');
        $this->db->join('srp_erp_crm_products', 'srp_erp_crm_leadproducts.productID = srp_erp_crm_products.productID');
        $this->db->join('srp_erp_currencymaster', 'srp_erp_crm_leadproducts.companyReportingCurrencyID = srp_erp_currencymaster.currencyID');
        $this->db->join('srp_erp_currencymaster transactioncurrency', 'srp_erp_crm_leadproducts.transactionCurrencyID = transactioncurrency.currencyID');
        $this->db->join('srp_erp_currencymaster companylocalcurrency', 'srp_erp_crm_leadproducts.companyLocalCurrencyID = companylocalcurrency.currencyID');
        $this->db->where($where);
        $this->db->order_by('leadProductID', 'desc');
        $data['header'] = $this->db->get()->result_array();
        $data['masterID'] = $leadID;
        $this->load->view('system/crm/ajax/load_lead_products', $data);
    }


    function load_opportunityManagement_view()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $text = trim($this->input->post('searchTask'));
        $sorting = trim($this->input->post('filtervalue'));
        $status = trim($this->input->post('status'));
        $responsible = trim($this->input->post('responsible'));
        $issuperadmin = crm_isSuperAdmin();
        $currentuserID = current_userID();
        $isGroupAdmin = crm_isGroupAdmin();
        $search_string = '';
        if (isset($text) && !empty($text)) {
            $search_string = " AND (opportunityName Like '%" . $text . "%')";
        }
        $search_sorting = '';
        if (isset($sorting) && !empty($sorting)) {
            $search_sorting = " AND opportunityName Like '" . $sorting . "%'";
        }
        $filterStatus = '';
        if (isset($status) && !empty($status)) {
            $filterStatus = " AND srp_erp_crm_opportunity.statusID = " . $status . "";
        }
        $filter_responsible = '';
        if (isset($responsible) && !empty($responsible)) {
            $filter_responsible = " AND srp_erp_crm_opportunity.responsibleEmpID = " . $responsible . "";
        }
        $convertFormat = convert_date_format_sql();

        if ($issuperadmin['isSuperAdmin'] == 1 || $isGroupAdmin['adminYN'] == 1) {

            $where_admin = "srp_erp_crm_opportunity.companyID = " . $companyid . $search_string . $search_sorting . $filterStatus . $filter_responsible;

            $data['header'] = $this->db->query("SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate, '" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_opportunity.createdUserName as campaigncreateduser,DATE_FORMAT(srp_erp_crm_opportunity.createdDatetime, '" . $convertFormat . "') AS createdDatetimeopportunity FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID WHERE $where_admin GROUP BY srp_erp_crm_opportunity.opportunityID ORDER BY opportunityID DESC ")->result_array();

        } else {

            $where1 = "srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 1  AND srp_erp_crm_opportunity.companyID = " . $companyid . $search_string . $search_sorting . $filterStatus;

            $where2 = "srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 2  AND (srp_erp_crm_documentpermission.permissionValue = " . $currentuserID . " or srp_erp_crm_opportunity.createdUserID = " . $currentuserID . ") AND  srp_erp_crm_opportunity.companyID = " . $companyid . $search_string . $search_sorting . $filterStatus;

            $where3 = "srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 3  AND (srp_erp_crm_usergroupdetails.empID = " . $currentuserID . " or srp_erp_crm_opportunity.createdUserID = " . $currentuserID . ") AND  srp_erp_crm_opportunity.companyID = " . $companyid . $search_string . $search_sorting . $filterStatus;

            $where4 = "srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 4  AND (srp_erp_crm_documentpermissiondetails.empID = " . $currentuserID . " or srp_erp_crm_opportunity.createdUserID = " . $currentuserID . ") AND  srp_erp_crm_opportunity.companyID = " . $companyid . $search_string . $search_sorting . $filterStatus;

            $data['header'] = $this->db->query("SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate, '" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_opportunity.createdUserName as campaigncreateduser,DATE_FORMAT(srp_erp_crm_opportunity.createdDatetime, '" . $convertFormat . "') AS createdDatetimeopportunity FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID WHERE $where1 GROUP BY srp_erp_crm_opportunity.opportunityID UNION SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate, '" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_opportunity.createdUserName as campaigncreateduser,DATE_FORMAT(srp_erp_crm_opportunity.createdDatetime, '" . $convertFormat . "') AS createdDatetimeopportunity FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID WHERE $where2 GROUP BY srp_erp_crm_opportunity.opportunityID UNION SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate,'" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_opportunity.createdUserName as campaigncreateduser,DATE_FORMAT(srp_erp_crm_opportunity.createdDatetime, '" . $convertFormat . "') AS createdDatetimeopportunity FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID LEFT JOIN srp_erp_crm_usergroupdetails ON srp_erp_crm_usergroupdetails.groupMasterID = srp_erp_crm_documentpermission.permissionValue WHERE $where3 GROUP BY srp_erp_crm_opportunity.opportunityID UNION SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate,'" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_opportunity.createdUserName as campaigncreateduser,DATE_FORMAT(srp_erp_crm_opportunity.createdDatetime, '" . $convertFormat . "') AS createdDatetimeopportunity FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID LEFT JOIN srp_erp_crm_documentpermissiondetails ON srp_erp_crm_documentpermissiondetails.documentPermissionID = srp_erp_crm_documentpermission.documentPermissionID WHERE $where4 GROUP BY srp_erp_crm_opportunity.opportunityID")->result_array();
        }
        $this->load->view('system/crm/ajax/load_opportunity_master', $data);
    }

    function save_opportunity_header()
    {
        $searches = $this->input->post('related_search');
        $userPermission = $this->input->post('userPermission');
        $groupid = trim($this->input->post('groupID'));
        $assignemployees[] = $this->input->post('responsiblePersonEmpID');
        $permissionemp = $this->input->post('employees');
        $companyID = $this->common_data['company_data']['company_id'];
        $currentuser[] = current_userID();

        $this->form_validation->set_rules('opportunityname', 'Opportunity Name', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('reason', 'Reason', 'trim|required');
        $this->form_validation->set_rules('statusID', 'Status', 'trim|required');
        $this->form_validation->set_rules('categoryID', 'Category', 'trim|required');
        $this->form_validation->set_rules('responsiblePersonEmpID', 'User Responsible', 'trim|required');

        foreach ($searches as $key => $search) {
            $this->form_validation->set_rules("relatedTo[{$key}]", 'Related To', 'trim|required');
            $this->form_validation->set_rules("relatedAutoID[{$key}]", 'Search', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            if ($userPermission == 3) {
                if ($groupid == ' ' || empty($groupid)) {
                    echo json_encode(array('w', 'Please select a user group.'));
                }
                if (empty($assignemployees) || $assignemployees == ' ') {
                    echo json_encode(array('w', 'Please select a Assignee.'));
                } else {
                    $grouppermission = $this->db->query("select empID from srp_erp_crm_usergroupdetails where companyID = '{$companyID }' And groupMasterID = '{$groupid}'  AND empID IN (" . join(',', $assignemployees) . ") ")->result_array();
                    $grouppermissionresult = array_column($grouppermission, 'empID');
                    $resultdiff = array_diff($assignemployees, $grouppermissionresult);
                    if (!empty($resultdiff)) {
                        echo json_encode(array('w', 'Selected assignee not in the current user group'));
                    } else {
                        echo json_encode($this->Crm_lead_model->save_opportunity_header());
                    }
                }
            } else if ($userPermission == 4) {
                if (empty($assignemployees) || $assignemployees == ' ') {
                    echo json_encode(array('w', 'Please select a Assignee.'));
                } else {
                    $resultdifference = array_diff($assignemployees, $permissionemp);
                    if (!empty($resultdifference)) {
                        echo json_encode(array('w', 'Visibility permission not granted for some assignees'));
                    } else {
                        echo json_encode($this->Crm_lead_model->save_opportunity_header());
                    }
                }
            } else if ($userPermission == 2) {
                $resultdifferencerecord = array_diff($assignemployees, $currentuser);
                if (!empty($resultdifferencerecord)) {
                    echo json_encode(array('w', 'Please select current user as assignee'));
                } else {
                    echo json_encode($this->Crm_lead_model->save_opportunity_header());
                }

            }else {
                echo json_encode($this->Crm_lead_model->save_opportunity_header());
            }

        }
    }

    function load_opportunity_header()
    {
        echo json_encode($this->Crm_lead_model->load_opportunity_header());
    }

    function load_project_header()
    {
        echo json_encode($this->Crm_lead_model->load_project_header());
    }

    function convert_leadToOpportunity()
    {
        $crmleadid = $this->input->post('leadID');
        $companyid = current_companyID();
        $isgroupadmin = crm_isGroupAdmin();
        $admin = crm_isSuperAdmin();
        $cuurentuser = current_userID();
        $leaddata = $this->db->query("SELECT srp_erp_crm_leadmaster.createdUserID as createduserlead,srp_erp_crm_leadmaster.responsiblePersonEmpID FROM	srp_erp_crm_leadmaster WHERE srp_erp_crm_leadmaster.companyID = $companyid AND leadID = $crmleadid")->row_array();

        if(($leaddata['createduserlead'] != $cuurentuser) || ( $admin['isSuperAdmin'] != 1) || ($isgroupadmin['adminYN'] != 1) || ($leaddata['responsiblePersonEmpID'] !=  $cuurentuser))
        {
            echo json_encode(array('w', 'You do not have permission to convert this lead'));
        }else
        {
            echo json_encode($this->Crm_lead_model->convert_leadToOpportunity());
        }


    }


    function load_opportunityManagement_editView()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $opportunityID = trim($this->input->post('opportunityID'));
        $this->db->select('*,DATE_FORMAT(srp_erp_crm_opportunity.createdDateTime,\'' . $convertFormat . '\') AS createdDate,DATE_FORMAT(srp_erp_crm_opportunity.modifiedDateTime,\'' . $convertFormat . '\') AS modifydate, srp_erp_crm_opportunity.description as opportunityDescription,srp_erp_crm_users.employeeName as responsiblePerson,srp_erp_crm_categories.description as categoryDescription,probabilityofwinning,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate,\'' . $convertFormat . '\') AS forcastCloseDate,pipelineID,pipelineStageID,srp_erp_crm_status.description as statusDescription,srp_erp_crm_opportunity.reason,CurrencyCode,transactionAmount, CONCAT(srp_erp_crm_leadmaster.firstName, " ", srp_erp_crm_leadmaster.lastName) as fullname,srp_erp_crm_opportunity.closeStatus,srp_erp_crm_opportunity.createdUserName as createdUserName,srp_erp_crm_opportunity.createdUserID as crtduser');
        $this->db->where('opportunityID', $opportunityID);
        $this->db->from('srp_erp_crm_opportunity');
        $this->db->join('srp_erp_crm_users', 'srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID', 'LEFT');
        $this->db->join('srp_erp_currencymaster', 'srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID', 'LEFT');
        $this->db->join('srp_erp_crm_status', 'srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID', 'LEFT');
        $this->db->join('srp_erp_crm_categories', 'srp_erp_crm_categories.categoryID = srp_erp_crm_opportunity.categoryID', 'LEFT');
        $this->db->join('srp_erp_crm_leadmaster', 'srp_erp_crm_leadmaster.leadID = srp_erp_crm_opportunity.leadID', 'LEFT');
        $data['header'] = $this->db->get()->row_array();

        $this->db->select('employeeID as isadmin');
        $this->db->from('srp_erp_crm_users');
        $this->db->where('companyID', $companyid);
        $this->db->where('isSuperAdmin', 1);
        $data['superadmn'] = $this->db->get()->row_array();

        $this->load->view('system/crm/ajax/load_opportunity_edit_view', $data);
    }

    function load_pipelineSubStage()
    {
        echo json_encode($this->Crm_lead_model->load_pipelineSubStage());
    }


    function load_opportunity_all_notes()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $opportunityID = trim($this->input->post('opportunityID'));

        $where = "companyID = " . $companyid . " AND documentID = 4 AND contactID = " . $opportunityID . "";
        $convertFormat = convert_date_format_sql();
        $this->db->select('*');
        $this->db->from('srp_erp_crm_contactnotes');
        $this->db->where($where);
        $this->db->order_by('notesID', 'desc');
        $data['notes'] = $this->db->get()->result_array();
        $this->load->view('system/crm/ajax/load_opportunity_notes', $data);
    }

    function add_opportunity_notes()
    {
        $this->form_validation->set_rules('opportunityID', 'Opportunity ID', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Crm_lead_model->add_opportunity_notes());
        }
    }

    function load_opportunity_all_attachments()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $opportunityID = trim($this->input->post('opportunityID'));

        $where = "companyID = " . $companyid . " AND documentID = 4  AND documentAutoID = " . $opportunityID . "";
        $convertFormat = convert_date_format_sql();
        $this->db->select('*');
        $this->db->from('srp_erp_crm_attachments');
        $this->db->where($where);
        $this->db->order_by('attachmentID', 'desc');
        $data['attachment'] = $this->db->get()->result_array();
        $this->load->view('system/crm/ajax/load_all_opportunity_attachements', $data);
    }


    function load_opportunity_all_tasks()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $opportunityID = trim($this->input->post('opportunityID'));

        $where = "srp_erp_crm_link.companyID = " . $companyid . " AND srp_erp_crm_link.relatedDocumentID = 4 AND srp_erp_crm_link.relatedDocumentMasterID = " . $opportunityID . " AND srp_erp_crm_link.documentID=2";
        $convertFormat = convert_date_format_sql();
        $this->db->select('srp_erp_crm_task.taskID,srp_erp_crm_task.subject,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,visibility,DATE_FORMAT(starDate,\'%D-%b-%y %h:%i %p\') AS starDate,DATE_FORMAT(DueDate,\'%D-%b-%y %h:%i %p\') AS DueDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_assignees.empID,srp_erp_crm_task.progress,srp_erp_crm_task.status,srp_erp_crm_task.Priority');
        $this->db->from('srp_erp_crm_link');
        $this->db->join('srp_erp_crm_task', 'srp_erp_crm_task.taskID = srp_erp_crm_link.MasterAutoID');
        $this->db->join('srp_erp_crm_categories', 'srp_erp_crm_categories.categoryID = srp_erp_crm_task.categoryID');
        $this->db->join('srp_erp_crm_status', 'srp_erp_crm_status.statusID = srp_erp_crm_task.status');
        $this->db->join('srp_erp_crm_assignees', 'srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_task.taskID', 'LEFT');
        $this->db->where($where);
        $this->db->group_by('taskID');
        $this->db->order_by('taskID', 'desc');
        $data['tasks'] = $this->db->get()->result_array();
        $data['masterID'] = $opportunityID;
        $this->load->view('system/crm/ajax/load_opportunity_tasks', $data);
    }

    function opportunity_update_status()
    {
        $this->form_validation->set_rules('reason', 'Reason', 'trim|required');
        $this->form_validation->set_rules('statusID', 'Status', 'trim|required');
        $this->form_validation->set_rules('opportunityID', 'Opportunity ID', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Crm_lead_model->opportunity_update_status());
        }
    }

    function dashboardTotalDocuments_Count()
    {
        echo json_encode($this->Crm_lead_model->dashboardTotalDocuments_Count());
    }

    function load_dashboard_bestleads()
    {
        $this->load->view('system/crm/ajax/dashboard_top_leads');
    }

    function load_dashboard_bestOpportunities()
    {
        $this->load->view('system/crm/ajax/dashboard_top_opportunity');
    }

    function load_dashboard_leadSource()
    {
        $this->load->view('system/crm/ajax/dashboard_lead_source');
    }

    function load_dashboard_reportsOfYear()
    {
        $this->load->view('system/crm/ajax/dashboard_total_reports');
    }

    function load_dashboard_leadGenerationRate()
    {
        $this->load->view('system/crm/ajax/dashboard_lead_generation_rate');
    }


    function load_dashboard_crmTeam()
    {
        $companyId = $this->common_data['company_data']['company_id'];
        $masterID = $this->input->post('masterID');
        $where = "";

        if(!empty($masterID)){
            $filterEmployee = join($masterID, ",");
            $where = "AND ugd.groupMasterID IN ($filterEmployee)";
        }
        //$data['detail'] = $this->db->query("SELECT user.employeeName From srp_erp_crm_usergroupdetails ugd LEFT JOIN srp_erp_crm_users user ON ugd.userID = user.userID WHERE ugd.companyID = '$companyId' AND activeYN = 1 $where ORDER BY groupDetailID DESC")->result_array();
        $data['detail'] = $this->db->query("SELECT users.employeeName,EmpImage FROM srp_erp_crm_users users LEFT JOIN srp_employeesdetails ON users.employeeID = srp_employeesdetails.EIdNo WHERE users.companyID = '$companyId' AND activeYN = 1 ORDER BY userID DESC")->result_array();
        $this->load->view('system/crm/ajax/dashboard_our_crm_team', $data);
    }

    function dashboard_new_opprtunityConvertedTotal()
    {

        $this->load->view('system/crm/ajax/dashboard_new_opprtunityConvertedtotal_reports');
    }

    function dashboard_new_projectConvertedTotal()
    {
        $this->load->view('system/crm/ajax/dashboard_new_projectConvertedTotal');
    }

    function load_dashboard_groupEmployees()
    {
        $data_arr = array();
        $masterID = $this->input->post('masterID');
        $this->db->select('empID,employeeName');
        $this->db->where_in('groupMasterID', $masterID);
        $this->db->where('companyID', $this->common_data['company_data']['company_id']);
        $this->db->from('srp_erp_crm_usergroupdetails');
        $groupEmployees = $this->db->get()->result_array();

        if (isset($groupEmployees)) {
            foreach ($groupEmployees as $row) {
                $data_arr[trim($row['empID'])] = trim($row['employeeName']);
            }
        }
        echo form_dropdown('groupEmployeeID[]', $data_arr, '', 'class="form-control select2" id="groupEmployeeID"  onchange="employeeDashboard()" multiple="" ');
    }
    function load_dashboard_Employees_CRM()
    {
        $data_arr = array();
        $this->db->select('employeeID,employeeName');
        $this->db->where('companyID', $this->common_data['company_data']['company_id']);
        $this->db->from('srp_erp_crm_users');
        $crmEmployees = $this->db->get()->result_array();

        if (isset($crmEmployees)) {
            foreach ($crmEmployees as $row) {
                $data_arr[trim($row['employeeID'])] = trim($row['employeeName']);
            }
        }
        echo form_dropdown('groupEmployeeID[]', $data_arr, '', 'class="form-control select2" id="groupEmployeeID"  onchange="employeeDashboard()" multiple="" ');
    }

    /*function reports_management()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $currentuserID = current_userID();
        $issuperadmin = crm_isSuperAdmin();
        $convertFormat = convert_date_format_sql();
        $sys = $this->input->post('sys');
        $page = $this->input->post('page');
        $url = '';
        $employeeID = join($this->input->post('employeeID'), ",");
        switch (trim($sys)) {
            case 'contact':
                if (isset($employeeID) && !empty($employeeID)) {
                    $where_count1 = "WHERE srp_erp_crm_documentpermission.documentID = 6 AND srp_erp_crm_documentpermission.permissionID = 1 AND srp_erp_crm_contactmaster.companyID = '{$companyID}'";

                    $where_count2 = "WHERE srp_erp_crm_documentpermission.documentID = 6 AND srp_erp_crm_documentpermission.permissionID = 2 AND srp_erp_crm_documentpermission.permissionValue IN ($employeeID) AND srp_erp_crm_contactmaster.companyID = '{$companyID}'";

                    $where_count3 = "WHERE srp_erp_crm_documentpermission.documentID = 6 AND srp_erp_crm_documentpermission.permissionID = 3 AND srp_erp_crm_usergroupdetails.empID IN ($employeeID) AND srp_erp_crm_contactmaster.companyID = '{$companyID}'";

                    $where_count4 = "WHERE srp_erp_crm_documentpermission.documentID = 6 AND srp_erp_crm_documentpermission.permissionID = 4 AND srp_erp_crm_documentpermissiondetails.empID IN ($employeeID) AND srp_erp_crm_contactmaster.companyID = '{$companyID}'";

                    $data['contact'] = $this->db->query("SELECT CONCAT(firstName, ' ', lastName) as fullname,email,organization,occupation,phoneMobile FROM srp_erp_crm_contactmaster LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_contactmaster.contactID $where_count1 GROUP BY contactID UNION SELECT CONCAT(firstName, ' ', lastName) as fullname,email,organization,occupation,phoneMobile FROM srp_erp_crm_contactmaster LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_contactmaster.contactID $where_count2 GROUP BY contactID UNION SELECT CONCAT(firstName, ' ', lastName) as fullname,email,organization,occupation,phoneMobile FROM srp_erp_crm_contactmaster LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_contactmaster.contactID LEFT JOIN srp_erp_crm_usergroupdetails ON srp_erp_crm_usergroupdetails.groupMasterID = srp_erp_crm_documentpermission.permissionValue $where_count3 GROUP BY contactID UNION SELECT CONCAT(firstName, ' ', lastName) as fullname,email,organization,occupation,phoneMobile FROM srp_erp_crm_contactmaster LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_contactmaster.contactID LEFT JOIN srp_erp_crm_documentpermissiondetails ON srp_erp_crm_documentpermissiondetails.documentPermissionID = srp_erp_crm_documentpermission.documentPermissionID $where_count4 GROUP BY contactID ")->result_array();
                    //echo $this->db->last_query();
                } else {

                    $data['contact'] = $this->db->query("SELECT CONCAT(firstName, ' ', lastName) as fullname,email,organization,occupation,phoneMobile FROM srp_erp_crm_contactmaster WHERE companyID = $companyID")->result_array();
                }

                $url = 'system/crm/ajax/report_contact_management';
                break;
            case
            'organization':

                if (isset($employeeID) && !empty($employeeID)) {

                    $where_organization1 = "WHERE srp_erp_crm_documentpermission.documentID = 8 AND srp_erp_crm_documentpermission.permissionID = 1 AND srp_erp_crm_organizations.companyID = '{$companyID}'";

                    $where_organization2 = "WHERE srp_erp_crm_documentpermission.documentID = 8 AND srp_erp_crm_documentpermission.permissionID = 2 AND srp_erp_crm_documentpermission.permissionValue IN ($employeeID) AND srp_erp_crm_organizations.companyID = '{$companyID}'";

                    $where_organization3 = "WHERE srp_erp_crm_documentpermission.documentID = 8 AND srp_erp_crm_documentpermission.permissionID = 3 AND srp_erp_crm_usergroupdetails.empID IN ($employeeID) AND srp_erp_crm_organizations.companyID = '{$companyID}'";

                    $where_organization4 = "WHERE srp_erp_crm_documentpermission.documentID = 8 AND srp_erp_crm_documentpermission.permissionID = 4 AND srp_erp_crm_documentpermissiondetails.empID IN ($employeeID) AND srp_erp_crm_organizations.companyID = '{$companyID}'";

                    $data['organization'] = $this->db->query("SELECT Name,email,shippingAddress FROM srp_erp_crm_organizations LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_organizations.organizationID $where_organization1 GROUP BY organizationID UNION SELECT Name,email,shippingAddress FROM srp_erp_crm_organizations LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_organizations.organizationID $where_organization2 GROUP BY organizationID UNION SELECT Name,email,shippingAddress FROM srp_erp_crm_organizations LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_organizations.organizationID LEFT JOIN srp_erp_crm_usergroupdetails ON srp_erp_crm_usergroupdetails.groupMasterID = srp_erp_crm_documentpermission.permissionValue $where_organization3 GROUP BY organizationID UNION SELECT Name,email,shippingAddress FROM srp_erp_crm_organizations LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_organizations.organizationID LEFT JOIN srp_erp_crm_documentpermissiondetails ON srp_erp_crm_documentpermissiondetails.documentPermissionID = srp_erp_crm_documentpermission.documentPermissionID $where_organization4 GROUP BY organizationID")->result_array();

                } else {
                    $data['organization'] = $this->db->query("SELECT Name,email,shippingAddress FROM srp_erp_crm_organizations WHERE companyID = $companyID ")->result_array();
                }

                $url = 'system/crm/ajax/report_organization_management';
                break;
            case 'task':
                if (isset($employeeID) && !empty($employeeID)) {

                    $where_task1 = "srp_erp_crm_documentpermission.documentID = 2 AND srp_erp_crm_documentpermission.permissionID = 1 AND srp_erp_crm_task.companyID = '{$companyID}'";

                    $where_task2 = "srp_erp_crm_documentpermission.documentID = 2 AND srp_erp_crm_documentpermission.permissionID = 2 AND srp_erp_crm_documentpermission.permissionValue IN ($employeeID) AND srp_erp_crm_task.companyID = '{$companyID}'";

                    $where_task3 = "srp_erp_crm_documentpermission.documentID = 2 AND srp_erp_crm_documentpermission.permissionID = 3 AND srp_erp_crm_usergroupdetails.empID IN ($employeeID) AND srp_erp_crm_task.companyID = '{$companyID}'";

                    $where_task4 = "srp_erp_crm_documentpermission.documentID = 2 AND srp_erp_crm_documentpermission.permissionID = 4 AND srp_erp_crm_documentpermissiondetails.empID IN ($employeeID) AND srp_erp_crm_task.companyID = '{$companyID}'";

                    $data['task'] = $this->db->query("SELECT srp_erp_crm_task.taskID,srp_erp_crm_task.subject,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,visibility,DATE_FORMAT(starDate,'%D-%b-%y %h:%i %p') AS starDate,DATE_FORMAT(DueDate,'%D-%b-%y %h:%i %p') AS DueDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_assignees.empID,srp_erp_crm_task.progress,srp_erp_crm_task.status,srp_erp_crm_task.Priority,isClosed FROM srp_erp_crm_task LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_categories.categoryID = srp_erp_crm_task.categoryID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_task.status LEFT JOIN srp_erp_crm_assignees ON srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_task.taskID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_task.taskID WHERE $where_task1 UNION SELECT srp_erp_crm_task.taskID,srp_erp_crm_task.subject,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,visibility,DATE_FORMAT(starDate,'%D-%b-%y %h:%i %p') AS starDate,DATE_FORMAT(DueDate,'%D-%b-%y %h:%i %p') AS DueDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_assignees.empID,srp_erp_crm_task.progress,srp_erp_crm_task.status,srp_erp_crm_task.Priority,isClosed FROM srp_erp_crm_task LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_categories.categoryID = srp_erp_crm_task.categoryID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_task.status LEFT JOIN srp_erp_crm_assignees ON srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_task.taskID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_task.taskID WHERE $where_task2 UNION SELECT srp_erp_crm_task.taskID,srp_erp_crm_task.subject,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,visibility,DATE_FORMAT(starDate,'%D-%b-%y %h:%i %p') AS starDate,DATE_FORMAT(DueDate,'%D-%b-%y %h:%i %p') AS DueDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_assignees.empID,srp_erp_crm_task.progress,srp_erp_crm_task.status,srp_erp_crm_task.Priority,isClosed FROM srp_erp_crm_task LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_categories.categoryID = srp_erp_crm_task.categoryID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_task.status LEFT JOIN srp_erp_crm_assignees ON srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_task.taskID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_task.taskID LEFT JOIN srp_erp_crm_usergroupdetails ON srp_erp_crm_usergroupdetails.groupMasterID = srp_erp_crm_documentpermission.permissionValue WHERE $where_task3 UNION SELECT srp_erp_crm_task.taskID,srp_erp_crm_task.subject,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,visibility,DATE_FORMAT(starDate,'%D-%b-%y %h:%i %p') AS starDate,DATE_FORMAT(DueDate,'%D-%b-%y %h:%i %p') AS DueDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_assignees.empID,srp_erp_crm_task.progress,srp_erp_crm_task.status,srp_erp_crm_task.Priority,isClosed FROM srp_erp_crm_task LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_categories.categoryID = srp_erp_crm_task.categoryID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_task.status LEFT JOIN srp_erp_crm_assignees ON srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_task.taskID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_task.taskID LEFT JOIN srp_erp_crm_documentpermissiondetails ON srp_erp_crm_documentpermissiondetails.documentPermissionID = srp_erp_crm_documentpermission.documentPermissionID WHERE $where_task4 GROUP BY srp_erp_crm_task.taskID ORDER BY taskID DESC")->result_array();
                } else {
                    $data['task'] = $this->db->query("SELECT srp_erp_crm_task.taskID,srp_erp_crm_task.subject,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,visibility,DATE_FORMAT(srp_erp_crm_task.starDate,'%D-%b-%y %h:%i %p') AS starDate,DATE_FORMAT(DueDate,'%D-%b-%y %h:%i %p') AS DueDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_task.progress,srp_erp_crm_task.status,srp_erp_crm_task.Priority,isClosed FROM srp_erp_crm_task LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_categories.categoryID = srp_erp_crm_task.categoryID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_task.status WHERE srp_erp_crm_task.companyID = $companyID ")->result_array();
                }

                $url = 'system/crm/ajax/report_task_management';
                break;
            case
            'campaign':

                if (isset($employeeID) && !empty($employeeID)) {

                    $where1 = "srp_erp_crm_documentpermission.documentID = 1 AND srp_erp_crm_documentpermission.permissionID = 1 AND srp_erp_crm_campaignmaster.companyID = " . $companyID;

                    $where2 = "srp_erp_crm_documentpermission.documentID = 1 AND srp_erp_crm_documentpermission.permissionID = 2 AND srp_erp_crm_documentpermission.permissionValue IN ($employeeID) AND  srp_erp_crm_campaignmaster.companyID = " . $companyID;

                    $where3 = "srp_erp_crm_documentpermission.documentID = 1 AND srp_erp_crm_documentpermission.permissionID = 3 AND srp_erp_crm_usergroupdetails.empID IN ($employeeID) AND srp_erp_crm_campaignmaster.companyID = " . $companyID;

                    $where4 = "srp_erp_crm_documentpermission.documentID = 1 AND srp_erp_crm_documentpermission.permissionID = 4 AND srp_erp_crm_documentpermissiondetails.empID IN ($employeeID) AND srp_erp_crm_campaignmaster.companyID = " . $companyID;

                    $data['campaign'] = $this->db->query("SELECT srp_erp_crm_campaignmaster.campaignID,srp_erp_crm_campaignmaster.name,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,srp_erp_crm_status.description as statusDescription,DATE_FORMAT(startDate,'" . $convertFormat . "') AS startDate,DATE_FORMAT(endDate,'" . $convertFormat . "') AS endDate,srp_erp_crm_campaignmaster.status,srp_erp_crm_assignees.empID,isClosed FROM srp_erp_crm_campaignmaster LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_categories.categoryID = srp_erp_crm_campaignmaster.type LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_campaignmaster.status LEFT JOIN srp_erp_crm_assignees ON srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_campaignmaster.campaignID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_campaignmaster.campaignID WHERE $where1 UNION SELECT srp_erp_crm_campaignmaster.campaignID,srp_erp_crm_campaignmaster.name,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,srp_erp_crm_status.description as statusDescription,DATE_FORMAT(startDate,'" . $convertFormat . "') AS startDate,DATE_FORMAT(endDate,'" . $convertFormat . "') AS endDate,srp_erp_crm_campaignmaster.status,srp_erp_crm_assignees.empID,isClosed FROM srp_erp_crm_campaignmaster LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_categories.categoryID = srp_erp_crm_campaignmaster.type LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_campaignmaster.status LEFT JOIN srp_erp_crm_assignees ON srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_campaignmaster.campaignID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_campaignmaster.campaignID WHERE $where2 UNION SELECT srp_erp_crm_campaignmaster.campaignID,srp_erp_crm_campaignmaster.name,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,srp_erp_crm_status.description as statusDescription,DATE_FORMAT(startDate,'" . $convertFormat . "') AS startDate,DATE_FORMAT(endDate,'" . $convertFormat . "') AS endDate,srp_erp_crm_campaignmaster.status,srp_erp_crm_assignees.empID,isClosed FROM srp_erp_crm_campaignmaster LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_categories.categoryID = srp_erp_crm_campaignmaster.type LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_campaignmaster.status LEFT JOIN srp_erp_crm_assignees ON srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_campaignmaster.campaignID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_campaignmaster.campaignID LEFT JOIN srp_erp_crm_usergroupdetails ON srp_erp_crm_usergroupdetails.groupMasterID = srp_erp_crm_documentpermission.permissionValue WHERE $where3 UNION SELECT srp_erp_crm_campaignmaster.campaignID,srp_erp_crm_campaignmaster.name,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,srp_erp_crm_status.description as statusDescription,DATE_FORMAT(startDate,'" . $convertFormat . "') AS startDate,DATE_FORMAT(endDate,'" . $convertFormat . "') AS endDate,srp_erp_crm_campaignmaster.status,srp_erp_crm_assignees.empID,isClosed FROM srp_erp_crm_campaignmaster LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_categories.categoryID = srp_erp_crm_campaignmaster.type LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_campaignmaster.status LEFT JOIN srp_erp_crm_assignees ON srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_campaignmaster.campaignID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_campaignmaster.campaignID LEFT JOIN srp_erp_crm_documentpermissiondetails ON srp_erp_crm_documentpermissiondetails.documentPermissionID = srp_erp_crm_documentpermission.documentPermissionID WHERE $where4 ORDER BY campaignID DESC")->result_array();
                    //echo $this->db->last_query();

                } else {
                    $data['campaign'] = $this->db->query("SELECT srp_erp_crm_campaignmaster.campaignID,srp_erp_crm_campaignmaster.name,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,srp_erp_crm_status.description as statusDescription,DATE_FORMAT(startDate,'%D-%b-%y') AS startDate,DATE_FORMAT(endDate,'%D-%b-%y') AS endDate,srp_erp_crm_campaignmaster.status,isClosed FROM srp_erp_crm_campaignmaster LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_categories.categoryID = srp_erp_crm_campaignmaster.type LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_campaignmaster.status WHERE srp_erp_crm_campaignmaster.companyID = $companyID ")->result_array();
                }

                $url = 'system/crm/ajax/report_campaign_management';
                break;
            case 'leadnew':

                if (isset($employeeID) && !empty($employeeID)) {
                    $where1 = "srp_erp_crm_documentpermission.documentID = 5 AND srp_erp_crm_documentpermission.permissionID = 1 AND srp_erp_crm_leadmaster.companyID = " . $companyID ;

                    $where2 = "srp_erp_crm_documentpermission.documentID = 5 AND srp_erp_crm_documentpermission.permissionID = 2 AND srp_erp_crm_documentpermission.permissionValue IN ($employeeID) AND  srp_erp_crm_leadmaster.companyID = " . $companyID;

                    $where3 = "srp_erp_crm_documentpermission.documentID = 5 AND srp_erp_crm_documentpermission.permissionID = 3 AND srp_erp_crm_usergroupdetails.empID IN ($employeeID) AND  srp_erp_crm_leadmaster.companyID = " . $companyID ;

                    $where4 = "srp_erp_crm_documentpermission.documentID = 5 AND srp_erp_crm_documentpermission.permissionID = 4 AND srp_erp_crm_documentpermissiondetails.empID IN ($employeeID) AND  srp_erp_crm_leadmaster.companyID = " . $companyID ;

                    $data['lead'] = $this->db->query("SELECT srp_erp_crm_leadmaster.leadID,CONCAT(firstName,' ',lastName) as fullname,phoneMobile,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_leadmaster.leadID WHERE $where1 UNION SELECT srp_erp_crm_leadmaster.leadID,CONCAT(firstName,' ',lastName) as fullname,phoneMobile,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_leadmaster.leadID WHERE $where2 UNION SELECT srp_erp_crm_leadmaster.leadID,CONCAT(firstName,' ',lastName) as fullname,phoneMobile,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_leadmaster.leadID LEFT JOIN srp_erp_crm_usergroupdetails ON srp_erp_crm_usergroupdetails.groupMasterID = srp_erp_crm_documentpermission.permissionValue WHERE $where3 UNION SELECT srp_erp_crm_leadmaster.leadID,CONCAT(firstName,' ',lastName) as fullname,phoneMobile,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_leadmaster.leadID LEFT JOIN srp_erp_crm_documentpermissiondetails ON srp_erp_crm_documentpermissiondetails.documentPermissionID = srp_erp_crm_documentpermission.documentPermissionID WHERE $where4 ORDER BY leadID DESC ")->result_array();

                } else {
                    $data['lead'] = $this->db->query("SELECT srp_erp_crm_leadmaster.leadID,CONCAT(firstName,' ',lastName) as fullname,phoneMobile,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID WHERE srp_erp_crm_leadmaster.companyID = $companyID GROUP BY srp_erp_crm_leadmaster.leadID")->result_array();
                }

                $url = 'system/crm/ajax/report_lead_management';
                break;
            case 'opportunity':

                if (isset($employeeID) && !empty($employeeID)) {
                    $where_opportunity1 = "srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 1 AND srp_erp_crm_opportunity.companyID = '{$companyID}' AND srp_erp_crm_opportunity.closeStatus != 2";

                    $where_opportunity2 = "srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 2 AND srp_erp_crm_documentpermission.permissionValue IN ($employeeID) AND srp_erp_crm_opportunity.companyID = '{$companyID}' AND srp_erp_crm_opportunity.closeStatus != 2";

                    $where_opportunity3 = "srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 3 AND srp_erp_crm_usergroupdetails.empID IN ($employeeID) AND srp_erp_crm_opportunity.companyID = '{$companyID}' AND srp_erp_crm_opportunity.closeStatus != 2";

                    $where_opportunity4 = "srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 4 AND srp_erp_crm_documentpermissiondetails.empID IN ($employeeID) AND srp_erp_crm_opportunity.companyID = '{$companyID}' AND srp_erp_crm_opportunity.closeStatus != 2";

                    $data['opportunity'] = $this->db->query("SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate, '" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID WHERE $where_opportunity1 UNION SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate, '" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID WHERE $where_opportunity2 UNION SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate,'" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID LEFT JOIN srp_erp_crm_usergroupdetails ON srp_erp_crm_usergroupdetails.groupMasterID = srp_erp_crm_documentpermission.permissionValue WHERE $where_opportunity3 UNION SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate,'" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID LEFT JOIN srp_erp_crm_documentpermissiondetails ON srp_erp_crm_documentpermissiondetails.documentPermissionID = srp_erp_crm_documentpermission.documentPermissionID WHERE $where_opportunity4 ")->result_array();

                   //echo $this->db->last_query();

                } else {
                    $data['opportunity'] = $this->db->query("SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate, '" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID WHERE srp_erp_crm_opportunity.companyID = $companyID ORDER BY opportunityID DESC ")->result_array();
                }
                $url = 'system/crm/ajax/report_opportunity_management';
                break;
            case 'project':

                if (isset($employeeID) && !empty($employeeID)) {
                    $where_project1 = "WHERE srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 1 AND srp_erp_crm_opportunity.companyID = '{$companyID}' AND srp_erp_crm_opportunity.closeStatus = 2";

                    $where_project2 = "WHERE srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 2 AND srp_erp_crm_documentpermission.permissionValue IN ($employeeID) AND srp_erp_crm_opportunity.companyID = '{$companyID}' AND srp_erp_crm_opportunity.closeStatus = 2";

                    $where_project3 = "WHERE srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 3 AND srp_erp_crm_usergroupdetails.empID IN ($employeeID) AND srp_erp_crm_opportunity.companyID = '{$companyID}' AND srp_erp_crm_opportunity.closeStatus = 2";

                    $where_project4 = "WHERE srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 4 AND srp_erp_crm_documentpermissiondetails.empID IN ($employeeID) AND srp_erp_crm_opportunity.companyID = '{$companyID}' AND srp_erp_crm_opportunity.closeStatus = 2";

                    $data['project'] = $this->db->query("SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,srp_erp_crm_opportunity.description as oppoDescription,srp_erp_crm_status.description as statusDescription FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID $where_project1 GROUP BY opportunityID UNION SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,srp_erp_crm_opportunity.description as oppoDescription,srp_erp_crm_status.description as statusDescription FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID $where_project2 GROUP BY opportunityID UNION SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,srp_erp_crm_opportunity.description as oppoDescription,srp_erp_crm_status.description as statusDescription FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID LEFT JOIN srp_erp_crm_usergroupdetails ON srp_erp_crm_usergroupdetails.groupMasterID = srp_erp_crm_documentpermission.permissionValue LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID $where_project3 GROUP BY opportunityID UNION SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,srp_erp_crm_opportunity.description as oppoDescription,srp_erp_crm_status.description as statusDescription FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID LEFT JOIN srp_erp_crm_documentpermissiondetails ON srp_erp_crm_documentpermissiondetails.documentPermissionID = srp_erp_crm_documentpermission.documentPermissionID LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID $where_project4 GROUP BY opportunityID")->result_array();
                } else {
                    $data['project'] = $this->db->query("SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate, '" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_opportunity.description as oppoDescription,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID WHERE srp_erp_crm_opportunity.companyID = $companyID AND closeStatus = 2 GROUP BY opportunityID ORDER BY opportunityID DESC ")->result_array();
                }

                $url = 'system/crm/ajax/report_project_management';
                break;
            default:
                $url = '';

        }
        if($page == 'html'){
            $this->load->view($url, $data);
        }
    }*/

    function reports_management()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $currentuserID = current_userID();
        $issuperadmin = crm_isSuperAdmin();
        $convertFormat = convert_date_format_sql();
        $sys = $this->input->post('sys');
        $page = $this->input->post('page');
        $groupID = $this->input->post('groupID');
        $groupEmployeeID = $this->input->post('groupEmployeeID');
        $assignee = $this->input->post('assigneeid');
        $taskstatus = $this->input->post('statusid');
        $categorytaskassignee = $this->input->post('categorytaskassignee');
        $assigneescamp = $this->input->post('assigneescamp');
        $campaigncat = $this->input->post('typeID');
        $campstatus = $this->input->post('statusidcamp');
        $userresponsleads = $this->input->post('leadsrptuser');
        $leadsstatus = $this->input->post('leadsstatus');
        $opporstatus = $this->input->post('opporstatusid');
        $responsiblePersonEmpIDopprpt = $this->input->post('responsiblePersonEmpIDopprpt');
        $responsiblePersonEmpIDpro = $this->input->post('responsiblePersonEmpIDpro');
        $catergorypro = $this->input->post('catergorypro');
        $prostatusid = $this->input->post('prostatusid');
        $date_format_policy = date_format_policy();

        $dateto = $this->input->post('datetotask');
        $datefrom = $this->input->post('datefromtask');

        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $datetoconvert = input_format_date($dateto, $date_format_policy);

        $url = '';
        if (isset($groupEmployeeID) && !empty($groupEmployeeID)) {
            $employeeID = implode(",", $this->input->post('groupEmployeeID'));
        }
        switch (trim($sys)) {
            case 'contact':
                if ((isset($employeeID) && !empty($employeeID)) || (isset($groupID) && !empty($groupID))) {

                    if (isset($employeeID) && !empty($employeeID)) {
                        $where_contact = "WHERE srp_erp_crm_contactmaster.companyID = '{$companyID}' AND srp_erp_crm_contactmaster.createdUserID IN ($employeeID)";
                    } else {
                        $where_contact = "WHERE srp_erp_crm_contactmaster.companyID = '{$companyID}' AND srp_erp_crm_contactmaster.createdUserID = ''";
                    }
                    $data['contact'] = $this->db->query("SELECT CONCAT(firstName, ' ', lastName) as fullname,srp_erp_crm_contactmaster.email,organization,occupation,phoneMobile,phoneHome,srp_erp_crm_organizations.Name as linkedorganization FROM srp_erp_crm_contactmaster LEFT JOIN srp_erp_crm_link ON srp_erp_crm_link.MasterAutoID = srp_erp_crm_contactmaster.contactID AND srp_erp_crm_link.documentID = 6 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID AND srp_erp_crm_link.documentID = 6 $where_contact GROUP BY contactID ")->result_array();

                } else {
                    $data['contact'] = $this->db->query("SELECT CONCAT(firstName, ' ', lastName) as fullname,srp_erp_crm_contactmaster.email,organization,occupation,phoneMobile,phoneHome,srp_erp_crm_organizations.Name as linkedorganization FROM srp_erp_crm_contactmaster LEFT JOIN srp_erp_crm_link ON srp_erp_crm_link.MasterAutoID = srp_erp_crm_contactmaster.contactID AND srp_erp_crm_link.documentID = 6 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID AND srp_erp_crm_link.documentID = 6 WHERE srp_erp_crm_contactmaster.companyID = $companyID GROUP BY contactID")->result_array();
                }
                if ($page == 'html') {
                    $url = 'system/crm/ajax/report_contact_management';
                    $this->load->view($url, $data);
                } else {
                    $html = $this->load->view('system/CRM/print/report_contact_print', $data, true);
                    $this->load->library('pdf');
                    $pdf = $this->pdf->printed($html, 'A4-L');
                }
                break;
            case
            'organization':

                if ((isset($employeeID) && !empty($employeeID)) || (isset($groupID) && !empty($groupID))) {

                    if (isset($employeeID) && !empty($employeeID)) {
                        $where_organization = "WHERE srp_erp_crm_organizations.companyID = '{$companyID}' AND srp_erp_crm_organizations.createdUserID IN ($employeeID)";
                    } else {
                        $where_organization = "WHERE srp_erp_crm_organizations.companyID = '{$companyID}' AND srp_erp_crm_organizations.createdUserID = ''";
                    }

                    $data['organization'] = $this->db->query("SELECT Name,email,shippingAddress,telephoneNo FROM srp_erp_crm_organizations $where_organization GROUP BY organizationID ")->result_array();

                } else {
                    $data['organization'] = $this->db->query("SELECT Name,email,shippingAddress,telephoneNo FROM srp_erp_crm_organizations WHERE companyID = $companyID ")->result_array();
                }
                if ($page == 'html') {
                    $url = 'system/crm/ajax/report_organization_management';
                    $this->load->view($url, $data);
                } else {
                    $html = $this->load->view('system/CRM/print/report_organization_print', $data, true);
                    $this->load->library('pdf');
                    $pdf = $this->pdf->printed($html, 'A4-L');
                }
                break;
            case 'task':
                if ((isset($assignee) && !empty($assignee)) ||(isset($taskstatus) && !empty($taskstatus))|| (isset($categorytaskassignee) && !empty($categorytaskassignee)) || (isset($dateto) && !empty($dateto))|| (isset($datefrom) && !empty($datefrom))) {
                    $where_task = '';
                    if (isset($assignee) && !empty($assignee)) {
                        $where_task = " AND srp_erp_crm_assignees.empID IN ($assignee)";
                    }
                    $where_task_status = '';
                    if ((isset($taskstatus) && !empty($taskstatus))) {
                            $where_task_status = " AND srp_erp_crm_task.status = $taskstatus";
                    }
                    $where_task_cat = '';
                    if((isset($categorytaskassignee) && !empty($categorytaskassignee)))
                    {
                        $where_task_cat = " AND srp_erp_crm_task.categoryID = $categorytaskassignee";
                    }
                    $date = "";
                    if (!empty($datefrom) && !empty($dateto)) {
                        $date .= " AND ( DATE_FORMAT( srp_erp_crm_task.starDate, '%Y-%m-%d') >= '" . $datefromconvert . "' AND DATE_FORMAT( srp_erp_crm_task.DueDate, '%Y-%m-%d' ) <= '" . $datetoconvert . "')";
                    }
                    $where_taskfilter = '';
                    $where_taskfilter = "Where srp_erp_crm_task.companyID = " . $companyID . $where_task . $where_task_status . $where_task_cat .$date;
                    $data['task'] = $this->db->query("SELECT srp_erp_crm_task.taskID,srp_erp_crm_task.categoryID,srp_erp_crm_task.subject,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,visibility,DATE_FORMAT(starDate,'%Y-%m-%d') AS starDate,DATE_FORMAT(DueDate,'%Y-%m-%d') AS DueDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_assignees.empID,srp_erp_crm_task.progress,srp_erp_crm_task.status,srp_erp_crm_task.Priority,isClosed,CASE WHEN srp_erp_crm_task.Priority = \"1\" THEN \"Low\" WHEN srp_erp_crm_task.Priority = \"2\" THEN \"Medium\" WHEN srp_erp_crm_task.Priority = \"3\" THEN \"High\" END PriorityTask,DATEDIFF(DATE_FORMAT( srp_erp_crm_task.DueDate, '%Y-%m-%d' ),CURDATE()) as datedifferencetask FROM srp_erp_crm_task LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_categories.categoryID = srp_erp_crm_task.categoryID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_task.status LEFT JOIN srp_erp_crm_assignees ON srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_task.taskID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_task.taskID $where_taskfilter GROUP BY srp_erp_crm_task.taskID ORDER BY taskID DESC")->result_array();
                } else {
                    $data['task'] = $this->db->query("SELECT srp_erp_crm_task.taskID,srp_erp_crm_task.categoryID,srp_erp_crm_task.subject,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,visibility,DATE_FORMAT(srp_erp_crm_task.starDate,'%Y-%m-%d') AS starDate,DATE_FORMAT(DueDate,'%Y-%m-%d') AS DueDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_task.progress,srp_erp_crm_task.status,srp_erp_crm_task.Priority,isClosed,CASE WHEN srp_erp_crm_task.Priority = \"1\" THEN \"Low\" WHEN srp_erp_crm_task.Priority = \"2\" THEN \"Medium\" WHEN srp_erp_crm_task.Priority = \"3\" THEN \"High\" END PriorityTask,DATEDIFF(DATE_FORMAT( srp_erp_crm_task.DueDate, '%Y-%m-%d' ),CURDATE()) as datedifferencetask FROM srp_erp_crm_task LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_categories.categoryID = srp_erp_crm_task.categoryID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_task.status WHERE srp_erp_crm_task.companyID = $companyID GROUP BY srp_erp_crm_task.taskID ORDER BY taskID DESC")->result_array();
                }
                if ($page == 'html') {
                    $url = 'system/crm/ajax/report_task_management';
                    $this->load->view($url, $data);
                } else {
                    $html = $this->load->view('system/crm/print/report_task_print', $data, true);
                    $this->load->library('pdf');
                    $pdf = $this->pdf->printed($html, 'A4-L');
                }
                break;
            case
            'campaign':


                if ((isset($campaigncat) && !empty($campaigncat)) || (isset($campstatus) && !empty($campstatus)) || (isset($assigneescamp) && !empty($assigneescamp))) {
                    $where_campaign_cat ='';
                    if (isset($campaigncat) && !empty($campaigncat)) {
                        $where_campaign_cat = " AND srp_erp_crm_campaignmaster.type = $campaigncat ";
                    }
                    $where_campaign_status = '';
                    if(isset($campstatus) && !empty($campstatus))
                    {
                        $where_campaign_status =" AND srp_erp_crm_campaignmaster.status = $campstatus";
                    }
                    $where_camp_assignees = '';
                    if(isset($assigneescamp) && !empty($assigneescamp))
                    {
                        $where_camp_assignees = " AND srp_erp_crm_assignees.empID = $assigneescamp";
                    }
                    $where_campaing_filter = '';
                    $where_campaing_filter = "WHERE srp_erp_crm_campaignmaster.companyID = " . $companyID . $where_campaign_cat . $where_campaign_status .$where_camp_assignees;
                    $data['campaign'] = $this->db->query("SELECT srp_erp_crm_campaignmaster.campaignID,srp_erp_crm_assignees.empID,empdet.Ename2 as empnameassignee,srp_erp_crm_campaignmaster.name,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,srp_erp_crm_status.description as statusDescription,DATE_FORMAT(startDate,'" . $convertFormat . "') AS startDate,DATE_FORMAT(endDate,'" . $convertFormat . "') AS endDate,srp_erp_crm_campaignmaster.status,srp_erp_crm_assignees.empID,isClosed FROM srp_erp_crm_campaignmaster  LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_categories.categoryID = srp_erp_crm_campaignmaster.type LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_campaignmaster.status LEFT JOIN srp_erp_crm_assignees ON srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_campaignmaster.campaignID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_campaignmaster.campaignID Left join srp_employeesdetails empdet on empdet.EIdNo = srp_erp_crm_assignees.empID $where_campaing_filter GROUP BY srp_erp_crm_campaignmaster.campaignID ORDER BY campaignID DESC")->result_array();
                } else {
                    $data['campaign'] = $this->db->query("SELECT srp_erp_crm_campaignmaster.campaignID,srp_erp_crm_assignees.empID,empdet.Ename2 as empnameassignee,srp_erp_crm_campaignmaster.name,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,srp_erp_crm_status.description as statusDescription,DATE_FORMAT(startDate,'%D-%b-%y') AS startDate,DATE_FORMAT(endDate,'%D-%b-%y') AS endDate,srp_erp_crm_campaignmaster.status,isClosed FROM srp_erp_crm_campaignmaster LEFT JOIN srp_erp_crm_assignees ON srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_campaignmaster.campaignID  LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_categories.categoryID = srp_erp_crm_campaignmaster.type LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_campaignmaster.status LEFT JOIN srp_employeesdetails empdet on empdet.EIdNo = srp_erp_crm_assignees.empID WHERE srp_erp_crm_campaignmaster.companyID = $companyID GROUP BY srp_erp_crm_campaignmaster.campaignID ORDER BY campaignID DESC")->result_array();
                }
                if ($page == 'html') {
                    $url = 'system/crm/ajax/report_campaign_management';
                    $this->load->view($url, $data);
                } else {
                    $html = $this->load->view('system/crm/print/report_campaign_print', $data, true);
                    $this->load->library('pdf');
                    $pdf = $this->pdf->printed($html, 'A4-L');
                }
                break;
            case 'leadnew':

                if ((isset($userresponsleads) && !empty($userresponsleads)) || (isset($leadsstatus) && !empty($leadsstatus))) {
                    $where_user_res = '';
                    if (isset($userresponsleads) && !empty($userresponsleads)) {
                        $where_user_res = " AND srp_erp_crm_leadmaster.responsiblePersonEmpID = $userresponsleads";
                    }
                    $where_leadnewstatus = '';
                    if (isset($leadsstatus) && !empty($leadsstatus)) {
                        $where_leadnewstatus = " AND srp_erp_crm_leadmaster.statusID = $leadsstatus";
                    }
                    $where_leadnew = "WHERE srp_erp_crm_leadmaster.companyID = " . $companyID .$where_user_res .$where_leadnewstatus;
                    $data['lead'] = $this->db->query("SELECT srp_erp_crm_leadmaster.leadID,CONCAT(firstName,' ',lastName) as fullname,phoneMobile,phoneHome,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization,empdet.Ename2 as responsibleempname,srp_erp_crm_leadmaster.responsiblePersonEmpID,srp_erp_crm_leadmaster.statusID FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_leadmaster.leadID LEFT JOIN srp_employeesdetails empdet on empdet.EIdNo = srp_erp_crm_leadmaster.responsiblePersonEmpID $where_leadnew GROUP BY srp_erp_crm_leadmaster.leadID ORDER BY leadID DESC ")->result_array();

                } else {
                    $data['lead'] = $this->db->query("SELECT srp_erp_crm_leadmaster.leadID,CONCAT(firstName,' ',lastName) as fullname,phoneMobile,phoneHome,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization,empdet.Ename2 as responsibleempname,srp_erp_crm_leadmaster.responsiblePersonEmpID,srp_erp_crm_leadmaster.statusID FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID LEFT JOIN srp_employeesdetails empdet on empdet.EIdNo = srp_erp_crm_leadmaster.responsiblePersonEmpID WHERE srp_erp_crm_leadmaster.companyID = $companyID GROUP BY srp_erp_crm_leadmaster.leadID ORDER BY leadID DESC")->result_array();
                }
                if ($page == 'html') {
                    $url = 'system/crm/ajax/report_lead_management';
                    $this->load->view($url, $data);
                } else {
                    $html = $this->load->view('system/crm/print/report_lead_print', $data, true);
                    $this->load->library('pdf');
                    $pdf = $this->pdf->printed($html, 'A4-L');
                }
                break;
            case 'opportunity':

                if ((isset($opporstatus) && !empty($opporstatus)) || (isset($responsiblePersonEmpIDopprpt) && !empty($responsiblePersonEmpIDopprpt))) {
                        $where_opportunity_status = '';
                    if (isset($opporstatus) && !empty($opporstatus)) {
                        $where_opportunity_status = " AND srp_erp_crm_opportunity.statusID = $opporstatus";
                    }
                    $where_responsible_id = '';
                    if((isset($responsiblePersonEmpIDopprpt) && !empty($responsiblePersonEmpIDopprpt)))
                    {
                        $where_responsible_id = " AND srp_erp_crm_opportunity.responsibleEmpID = $responsiblePersonEmpIDopprpt";
                    }
                    $where_opportunity = "WHERE srp_erp_crm_opportunity.companyID = " .$companyID .$where_opportunity_status .$where_responsible_id;
                    $data['opportunity'] = $this->db->query("SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate, '" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_opportunity.responsibleEmpID,srp_erp_crm_opportunity.statusID FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID $where_opportunity GROUP BY opportunityID ORDER BY opportunityID DESC ")->result_array();
                } else {
                    $data['opportunity'] = $this->db->query("SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate, '" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_opportunity.responsibleEmpID,srp_erp_crm_opportunity.statusID FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID WHERE srp_erp_crm_opportunity.companyID = $companyID GROUP BY opportunityID ORDER BY opportunityID DESC ")->result_array();
                }
                if ($page == 'html') {
                    $url = 'system/crm/ajax/report_opportunity_management';
                    $this->load->view($url, $data);
                } else {
                    $html = $this->load->view('system/crm/print/report_opportunity_print', $data, true);
                    $this->load->library('pdf');
                    $pdf = $this->pdf->printed($html, 'A4-L');
                }
                break;
            case 'project':

                if ((isset($responsiblePersonEmpIDpro) && !empty($responsiblePersonEmpIDpro)) || (isset($catergorypro) && !empty($catergorypro))|| (isset($prostatusid) && !empty($prostatusid))) {
                    $where_project_responsible = '';
                    if (isset($responsiblePersonEmpIDpro) && !empty($responsiblePersonEmpIDpro)) {
                        $where_project_responsible = " AND srp_erp_crm_project.responsibleEmpID = $responsiblePersonEmpIDpro";
                    }
                    $where_project_catergory ='';
                    if (isset($catergorypro) && !empty($catergorypro)) {
                        $where_project_catergory = " AND srp_erp_crm_project.categoryID = $catergorypro";
                    }
                    $where_project_status ='';
                    if (isset($prostatusid) && !empty($prostatusid)) {
                        $where_project_status = " AND srp_erp_crm_project.statusID = $prostatusid";
                    }

                    $where_project = "WHERE srp_erp_crm_project.companyID = " .$companyID .$where_project_responsible .$where_project_catergory .$where_project_status;


                    $data['project'] = $this->db->query("SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectStartDate, '" . $convertFormat . "') AS projectStartDate,DATE_FORMAT(srp_erp_crm_project.projectEndDate, '" . $convertFormat . "') AS projectEndDate,srp_erp_crm_project.description as oppoDescription,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_project.categoryID AS categoryID,srp_erp_crm_project.responsibleEmpID AS responsibleEmpID,srp_erp_crm_status.statusID,srp_erp_crm_categories.description as categorydes FROM srp_erp_crm_project LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.statusID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_project.projectID LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_categories on srp_erp_crm_categories.categoryID = srp_erp_crm_project.categoryID  $where_project GROUP BY projectID ")->result_array();
                } else {
                    $data['project'] = $this->db->query("SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectStartDate, '" . $convertFormat . "') AS projectStartDate,DATE_FORMAT(srp_erp_crm_project.projectEndDate, '" . $convertFormat . "') AS projectEndDate,srp_erp_crm_project.description as oppoDescription,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_project.categoryID AS categoryID,srp_erp_crm_project.responsibleEmpID AS responsibleEmpID,srp_erp_crm_status.statusID,srp_erp_crm_categories.description as categorydes FROM srp_erp_crm_project LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_categories on srp_erp_crm_categories.categoryID = srp_erp_crm_project.categoryID WHERE srp_erp_crm_project.companyID = $companyID GROUP BY projectID ORDER BY projectID DESC ")->result_array();
                }
                if ($page == 'html') {
                    $url = 'system/crm/ajax/report_project_management';
                    $this->load->view($url, $data);
                } else {
                    $html = $this->load->view('system/crm/print/report_project_print', $data, true);
                    $this->load->library('pdf');
                    $pdf = $this->pdf->printed($html, 'A4-L');
                }
                break;
            case 'projectmoni':
                //Project Owner start
                $datefrom=$this->input->post('datefrom');
                $dateyear=$this->input->post('dateyear');
                $dateto=$this->input->post('dateto');
                $category=$this->input->post('category');
                $date_format_policy = date_format_policy();
                $frmDate = input_format_date($datefrom, $date_format_policy);
                $toDate = input_format_date($dateto, $date_format_policy);
                if(!empty($frmDate) && !empty($toDate)){
                    $whwre="AND projectStartDate BETWEEN '$frmDate' AND '$toDate'";
                }else{
                    $dts=date("Y-01-01");
                    $dt=date("Y-m-d");
                    $whwre="AND projectStartDate BETWEEN '$dts' AND '$dt'";
                }

                if(!empty($category)){
                    $whcate="AND srp_erp_crm_project.categoryID IN (" . join(',', $category) . ")";
                }else{
                    $cat=$this->db->query("SELECT `categoryID` FROM `srp_erp_crm_categories` WHERE `documentID` = 9 AND `companyID` = '$companyID'")->result_array();
                    $cata=array();
                    foreach($cat as $cal){
                        array_push($cata,$cal['categoryID']);
                    }
                    $category=$cata;
                    $whcate="AND srp_erp_crm_project.categoryID IN (" . join(',', $cata) . ")";
                }

                $companyID=current_companyID();
                $data['piplines'] = $this->db->query("SELECT pipeLineID,pipeLineName FROM srp_erp_crm_pipeline WHERE companyID = $companyID AND projectYN=1")->result_array();
                //$data['satushead'] = $this->db->query("SELECT * FROM srp_erp_crm_status WHERE companyID=$companyID AND documentID=9")->result_array();
                $data['satusbody'] = $this->db->query("SELECT projectID,projectStatus,responsibleEmpID,srp_employeesdetails.Ename2 as ename FROM srp_erp_crm_project LEFT JOIN srp_employeesdetails ON srp_erp_crm_project.responsibleEmpID = srp_employeesdetails.EIdNo WHERE companyID=$companyID $whwre $whcate GROUP BY responsibleEmpID")->result_array();
                $data['frmDate']=$frmDate;
                $data['toDate']=$toDate;
                if(!empty($datefrom)){
                    $data['datefrm']=$datefrom;
                }else{
                    $dts=date("Y-01-01");
                    $dtsf = convert_date_format($dts);

                    $data['datefrm']=$dtsf;
                }
                $data['datetu']=$dateto;
                //Project Owner end
                //Unclosed Tasks start
                if(!empty($frmDate) && !empty($toDate)){
                    $whwre="AND srp_erp_crm_task.starDate BETWEEN '$frmDate' AND '$toDate'";
                }else{
                    $dts=date("Y-01-01");
                    $dt=date("Y-m-d");
                    $whwre="AND srp_erp_crm_task.starDate BETWEEN '$dts' AND '$dt'";
                }
                $data['uncloshead'] = $this->db->query("SELECT srp_erp_crm_task.categoryID,srp_erp_crm_categories.description,srp_erp_crm_categories.textColor,srp_erp_crm_categories.backGroundColor FROM srp_erp_crm_assignees LEFT JOIN srp_erp_crm_task ON srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_task.taskID LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_task.categoryID = srp_erp_crm_categories.categoryID WHERE srp_erp_crm_assignees.companyID = $companyID AND srp_erp_crm_task.isClosed = 0 AND srp_erp_crm_assignees.documentID = 2 $whwre GROUP BY srp_erp_crm_task.categoryID")->result_array();
                $data['unclosbody'] = $this->db->query("SELECT empID,srp_employeesdetails.Ename2 FROM srp_erp_crm_assignees LEFT JOIN srp_erp_crm_task ON srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_task.taskID LEFT JOIN srp_employeesdetails ON srp_erp_crm_assignees.empID=srp_employeesdetails.EIdNo WHERE srp_erp_crm_assignees.companyID=$companyID AND srp_erp_crm_task.isClosed=0 AND srp_erp_crm_assignees.documentID = 2 $whwre GROUP BY empID")->result_array();

                //Unclosed Tasks end

                //Project Closing start
                $data['year']=date("Y");
                if(!empty($dateyear)){
                    $data['year']=$dateyear;
                }
                $data['Closingbody'] = $this->db->query("SELECT projectID,projectStatus,responsibleEmpID,srp_employeesdetails.Ename2 as ename FROM srp_erp_crm_project LEFT JOIN srp_employeesdetails ON srp_erp_crm_project.responsibleEmpID = srp_employeesdetails.EIdNo WHERE companyID=$companyID  GROUP BY responsibleEmpID")->result_array();
                //Project Closing end

                //Project Closing chart start
                $clsbdychart=$this->db->query("SELECT projectID,projectStatus,responsibleEmpID,srp_employeesdetails.Ename2 as ename FROM srp_erp_crm_project LEFT JOIN srp_employeesdetails ON srp_erp_crm_project.responsibleEmpID = srp_employeesdetails.EIdNo WHERE companyID=$companyID  GROUP BY responsibleEmpID")->result_array();;
                $piData = '';
                if(!empty($clsbdychart)) {
                    $cnt=count($clsbdychart);
                    $i=1;
                    $piData .= '[';
                    foreach ($clsbdychart as $nam) {
                        $piData .= '{';
                        $piData .= '"name" : "' . $nam['ename'] . '",';
                        $totempclos = 0;
                        for ($x = 1; $x <= 12; $x++) {
                            $companyID = current_companyID();

                            $empid = $nam{'responsibleEmpID'};
                            $yearfltr = '';
                            $monthfltr = '';
                            if (!empty($data['year'])) {
                                $yearfltr = 'AND YEAR(closedDate)=' . $data['year'];
                                $monthfltr = 'AND MONTH(closedDate)=' . "$x";
                            }
                            $datas = $this->db->query("SELECT COUNT(projectStatus) as projectStatus FROM srp_erp_crm_project LEFT JOIN srp_employeesdetails ON srp_erp_crm_project.responsibleEmpID = srp_employeesdetails.EIdNo WHERE companyID=$companyID $yearfltr  $monthfltr AND isClosed=1  AND responsibleEmpID=$empid $whcate  ")->row_array();
                            $totempclos += $datas['projectStatus'];
                        }
                        if($totempclos>0){
                            $piData .= '"y" : ' . $totempclos . '';
                        }else{
                            $piData .= '"y" : 0';
                        }


                        if($i==$cnt){
                            $piData .= '}';
                        }else{
                            $piData .= '},';
                        }
                        $i++;
                    }

                    $piData .= ']';
                }
                $data['piData']=$piData;
                $data['category']=$category;
                $data['categorypdf']=$category;
                $data['category2']= implode(',', $category);
                $data['frmdtfilter']= $datefrom;
                $data['todtfilter']= $dateto;
                $data['yearfilter']= $dateyear;
                //Project Closing chart end

                if ($page == 'html') {
                    $url = 'system/crm/ajax/report_project_monitoring';
                    $this->load->view($url, $data);
                } else {

                    /*echo 'gum sdhsgdj<pre>';
                    print_r($_POST);
                    echo ($this->input->post('dateto')) ;exit;*/

                    $html = $this->load->view('system/crm/print/report_project_monitoring_print', $data, true);
                    $this->load->library('pdf');
                    $pdf = $this->pdf->printed($html, 'A4');
                }
                break;
            default:
                $url = '';

        }

    }

    function printcontactreport()
    {

        $data['contact'] = $this->db->query("SELECT CONCAT(firstName, ' ', lastName) as fullname,srp_erp_crm_contactmaster.email,organization,occupation,phoneMobile,srp_erp_crm_organizations.Name as linkedorganization FROM srp_erp_crm_contactmaster LEFT JOIN srp_erp_crm_link ON srp_erp_crm_link.MasterAutoID = srp_erp_crm_contactmaster.contactID AND srp_erp_crm_link.documentID = 6 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID AND srp_erp_crm_link.documentID = 6 GROUP BY contactID ")->result_array();

        $html = $this->load->view('system/CRM/ajax/report_contact_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4-L');
        }

    }

    function load_projectManagement_view()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $text = trim($this->input->post('searchTask'));
        $sorting = trim($this->input->post('filtervalue'));
        $status = trim($this->input->post('status'));
        $activeclose = trim($this->input->post('isactivestatus'));
        $issuperadmin = crm_isSuperAdmin();
        $currentuserID = current_userID();
        $catergory = trim($this->input->post('catergory'));
        $search_string = '';
        $status_catergory = '';
        if (isset($text) && !empty($text)) {
            $search_string = " AND (projectName Like '%" . $text . "%')";
        }
        $search_sorting = '';
        if (isset($sorting) && !empty($sorting)) {
            $search_sorting = " AND projectName Like '" . $sorting . "%'";
        }
        $filterStatus = '';
        if (isset($status) && !empty($status)) {
            $filterStatus = " AND srp_erp_crm_project.projectStatus = " . $status . "";
        }
        $filteractiveclose = '';
        if (isset($activeclose) && !empty($activeclose)) {
            if($activeclose == 1)
            {
                $filteractiveclose = " AND (srp_erp_crm_project.isClosed IS NULL OR srp_erp_crm_project.isClosed = 0)
";
            }else
            {
                $filteractiveclose = " AND srp_erp_crm_project.isClosed = '1'";
            }

        }
        if (isset($catergory) && !empty($catergory)) {
            $status_catergory = " AND srp_erp_crm_project.categoryID = " . $catergory . "";
        }
        $convertFormat = convert_date_format_sql();

        if ($issuperadmin['isSuperAdmin'] == 1) {

            $where_admin = "srp_erp_crm_project.companyID = " . $companyid . " AND closeStatus = 2 " . $search_string . $search_sorting . $filterStatus . $status_catergory . $filteractiveclose;

            $data['header'] = $this->db->query("SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectEndDate, '" . $convertFormat . "') AS projectEndDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_organizations.Name as organizationName,srp_erp_crm_project.isClosed as isClosed,srp_erp_crm_project.createdUserName as createduserproject,DATE_FORMAT(srp_erp_crm_project.createdDateTime, '" . $convertFormat . "') AS createddateproject FROM srp_erp_crm_project LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_link ON srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8 AND srp_erp_crm_link.documentID = 4 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID WHERE $where_admin GROUP BY srp_erp_crm_project.projectID ORDER BY projectID DESC ")->result_array();
            // echo $this->db->last_query();

        } else {

            $where1 = "srp_erp_crm_documentpermission.documentID = 9 AND srp_erp_crm_documentpermission.permissionID = 1  AND closeStatus = 2 AND srp_erp_crm_project.companyID = " . $companyid . $search_string . $search_sorting . $filterStatus . $status_catergory . $filteractiveclose;

            $where2 = "srp_erp_crm_documentpermission.documentID = 9 AND srp_erp_crm_documentpermission.permissionID = 2  AND closeStatus = 2 AND (srp_erp_crm_documentpermission.permissionValue = " . $currentuserID . " or srp_erp_crm_project.createdUserID = " . $currentuserID . ") AND  srp_erp_crm_project.companyID = " . $companyid . $search_string . $search_sorting . $filterStatus. $status_catergory . $filteractiveclose;

            $where3 = "srp_erp_crm_documentpermission.documentID = 9 AND srp_erp_crm_documentpermission.permissionID = 3  AND closeStatus = 2 AND (srp_erp_crm_usergroupdetails.empID = " . $currentuserID . " or srp_erp_crm_project.createdUserID = " . $currentuserID . ") AND  srp_erp_crm_project.companyID = " . $companyid . $search_string . $search_sorting . $filterStatus. $status_catergory . $filteractiveclose;

            $where4 = "srp_erp_crm_documentpermission.documentID = 9 AND srp_erp_crm_documentpermission.permissionID = 4  AND closeStatus = 2 AND (srp_erp_crm_documentpermissiondetails.empID = " . $currentuserID . " or srp_erp_crm_project.createdUserID = " . $currentuserID . ") AND  srp_erp_crm_project.companyID = " . $companyid . $search_string . $search_sorting . $filterStatus. $status_catergory . $filteractiveclose;

            $data['header'] = $this->db->query("SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectEndDate, '" . $convertFormat . "') AS projectEndDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_organizations.Name as organizationName,srp_erp_crm_project.isClosed as isClosed,srp_erp_crm_project.createdUserName as createduserproject,DATE_FORMAT(srp_erp_crm_project.createdDateTime, '" . $convertFormat . "') AS createddateproject FROM srp_erp_crm_project LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.projectStatus LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_project.projectID LEFT JOIN srp_erp_crm_link ON srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8 AND srp_erp_crm_link.documentID = 4 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID WHERE $where1 GROUP BY srp_erp_crm_project.projectID UNION SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectEndDate, '" . $convertFormat . "') AS projectEndDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_organizations.Name as organizationName,srp_erp_crm_project.isClosed as isClosed,srp_erp_crm_project.createdUserName as createduserproject,DATE_FORMAT(srp_erp_crm_project.createdDateTime, '" . $convertFormat . "') AS createddateproject FROM srp_erp_crm_project LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.projectStatus LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_project.projectID LEFT JOIN srp_erp_crm_link ON srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8 AND srp_erp_crm_link.documentID = 4 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID WHERE $where2 GROUP BY srp_erp_crm_project.projectID UNION SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectEndDate,'" . $convertFormat . "') AS projectEndDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_organizations.Name as organizationName,srp_erp_crm_project.isClosed as isClosed,srp_erp_crm_project.createdUserName as createduserproject,DATE_FORMAT(srp_erp_crm_project.createdDateTime, '" . $convertFormat . "') AS createddateproject FROM srp_erp_crm_project LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_project.projectID LEFT JOIN srp_erp_crm_usergroupdetails ON srp_erp_crm_usergroupdetails.groupMasterID = srp_erp_crm_documentpermission.permissionValue LEFT JOIN srp_erp_crm_link ON srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8 AND srp_erp_crm_link.documentID = 4 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID WHERE $where3 GROUP BY srp_erp_crm_project.projectID UNION SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectEndDate,'" . $convertFormat . "') AS projectEndDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_organizations.Name as organizationName,srp_erp_crm_project.isClosed as isClosed,srp_erp_crm_project.createdUserName as createduserproject,DATE_FORMAT(srp_erp_crm_project.createdDateTime, '" . $convertFormat . "') AS createddateproject FROM srp_erp_crm_project LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_project.projectID LEFT JOIN srp_erp_crm_documentpermissiondetails ON srp_erp_crm_documentpermissiondetails.documentPermissionID = srp_erp_crm_documentpermission.documentPermissionID LEFT JOIN srp_erp_crm_link ON srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8 AND srp_erp_crm_link.documentID = 4 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID WHERE $where4 GROUP BY srp_erp_crm_project.projectID")->result_array();
            //echo $this->db->last_query();
        }
        $this->load->view('system/crm/ajax/load_project_master', $data);
    }

    function load_projectManagement_editView()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $projectID = trim($this->input->post('projectID'));
        $this->db->select('*,DATE_FORMAT(srp_erp_crm_project.createdDateTime,\'' . $convertFormat . '\') AS createdDate,DATE_FORMAT(srp_erp_crm_project.modifiedDateTime,\'' . $convertFormat . '\') AS modifydate, srp_erp_crm_project.description as opportunityDescription,srp_erp_crm_users.employeeName as responsiblePerson,srp_erp_crm_categories.description as categoryDescription,probabilityofwinning,DATE_FORMAT(srp_erp_crm_project.forcastCloseDate,\'' . $convertFormat . '\') AS forcastCloseDate,pipelineID,pipelineStageID,srp_erp_crm_status.description as statusDescription,srp_erp_crm_project.reason,CurrencyCode,transactionAmount, CONCAT(srp_erp_crm_leadmaster.firstName, " ", srp_erp_crm_leadmaster.lastName) as fullname,srp_erp_crm_project.closeStatus,srp_erp_crm_project.createdUserName as createdUserName,DATE_FORMAT(srp_erp_crm_project.projectStartDate,\'' . $convertFormat . '\') AS projectStartDate,DATE_FORMAT(srp_erp_crm_project.projectEndDate,\'' . $convertFormat . '\') AS projectEndDate,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackgroundColor,srp_erp_crm_link.searchValue,srp_erp_crm_link.relatedDocumentMasterID,srp_erp_crm_organizations.Name as organizationName,srp_erp_crm_project.isClosed as clsd,DATE_FORMAT(srp_erp_crm_project.closedDate,\'' . $convertFormat . '\') AS clsdDate,srp_erp_crm_project.createdUserID as crtduser');
        $this->db->where('projectID', $projectID);
        //$this->db->where('srp_erp_crm_link.documentID', 9);
        $this->db->from('srp_erp_crm_project');
        $this->db->join('srp_erp_crm_users', 'srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID', 'LEFT');
        $this->db->join('srp_erp_currencymaster', 'srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID', 'LEFT');
        $this->db->join('srp_erp_crm_status', 'srp_erp_crm_status.statusID = srp_erp_crm_project.projectStatus', 'LEFT');
        $this->db->join('srp_erp_crm_categories', 'srp_erp_crm_categories.categoryID = srp_erp_crm_project.categoryID', 'LEFT');
        $this->db->join('srp_erp_crm_leadmaster', 'srp_erp_crm_leadmaster.leadID = srp_erp_crm_project.leadID', 'LEFT');
        $this->db->join('srp_erp_crm_link', 'srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8', 'LEFT');
        $this->db->join('srp_erp_crm_organizations', 'srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID  AND srp_erp_crm_link.documentID = 4 ', 'LEFT');
        $data['header'] = $this->db->get()->row_array();

        $this->db->select('employeeID as isadmin');
        $this->db->from('srp_erp_crm_users');
        $this->db->where('companyID', $companyid);
        $this->db->where('employeeID', $this->common_data['current_userID']);
        $this->db->where('isSuperAdmin', 1);
        $data['superadmn'] = $this->db->get()->row_array();

        $this->db->select('empID as isadmin');
        $this->db->from('srp_erp_crm_usergroupdetails');
        $this->db->where('companyID', $companyid);
        $this->db->where('empID', $this->common_data['current_userID']);
        $this->db->where('adminYN', 1);
        $data['isAdmin'] = $this->db->get()->row_array();

        $this->load->view('system/crm/ajax/load_project_edit_view', $data);
    }

    function load_project_all_notes()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $projectID = trim($this->input->post('projectID'));

        $where = "companyID = " . $companyid . " AND documentID = 9 AND contactID = " . $projectID . "";
        $convertFormat = convert_date_format_sql();
        $this->db->select('*');
        $this->db->from('srp_erp_crm_contactnotes');
        $this->db->where($where);
        $this->db->order_by('notesID', 'desc');
        $data['notes'] = $this->db->get()->result_array();
        $this->load->view('system/crm/ajax/load_project_notes', $data);
    }

    function update_project_header()
    {
        $searches = $this->input->post('related_search');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('projectStatus', 'Status', 'trim|required');
        $this->form_validation->set_rules('responsiblePersonEmpID', 'User Responsible', 'trim|required');

        foreach ($searches as $key => $search) {
            $this->form_validation->set_rules("relatedTo[{$key}]", 'Related To', 'trim|required');
            $this->form_validation->set_rules("relatedAutoID[{$key}]", 'Search', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Crm_lead_model->update_project_header());
        }
    }

    function save_project_header()
    {
        $searches = $this->input->post('related_search');
        $projectStartDate = $this->input->post('projectStartDate');
        $projectEndDate = $this->input->post('projectEndDate');
        $this->form_validation->set_rules('opportunityname', 'Project Name', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('projectStatus', 'Status', 'trim|required');
        $this->form_validation->set_rules('responsiblePersonEmpID', 'User Responsible', 'trim|required');
        $this->form_validation->set_rules('categoryID', 'Category', 'trim|required');
        if ($this->input->post('isClosed')==1) {
            $this->form_validation->set_rules('closedDate', 'Closed Date', 'trim|required');
        }

        foreach ($searches as $key => $search) {
            $this->form_validation->set_rules("relatedTo[{$key}]", 'Related To', 'trim|required');
            $this->form_validation->set_rules("relatedAutoID[{$key}]", 'Contact, Organization...', 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            if ((isset($projectStartDate) && !empty($projectStartDate)) && (isset($projectEndDate) && !empty($projectEndDate))) {

                $date_format_policy = date_format_policy();
                $format_projectStartDate = input_format_date($projectStartDate, $date_format_policy);
                $format_projectEndDate = input_format_date($projectEndDate, $date_format_policy);
                $startdate = $format_projectStartDate;
                $end_date = $format_projectEndDate;
                if ($end_date <= $startdate) {
                    echo json_encode(array('e', 'End Date should be greater than Start Date'));
                    exit();
                }
            }
            echo json_encode($this->Crm_lead_model->save_project_header());
        }
    }

    function load_project_all_tasks()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $projectID = trim($this->input->post('projectID'));

        $where = "srp_erp_crm_link.companyID = " . $companyid . " AND srp_erp_crm_link.relatedDocumentID = 9 AND srp_erp_crm_link.relatedDocumentMasterID = " . $projectID . " and srp_erp_crm_link.documentID=2 ";
        $convertFormat = convert_date_format_sql();
        $this->db->select('srp_erp_crm_task.taskID,srp_erp_crm_task.subject,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,visibility,DATE_FORMAT(starDate,\'%D-%b-%y %h:%i %p\') AS starDate,DATE_FORMAT(DueDate,\'%D-%b-%y %h:%i %p\') AS DueDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_assignees.empID,srp_erp_crm_task.progress,srp_erp_crm_task.status,srp_erp_crm_task.Priority');
        $this->db->from('srp_erp_crm_link');
        $this->db->join('srp_erp_crm_task', 'srp_erp_crm_task.taskID = srp_erp_crm_link.MasterAutoID');
        $this->db->join('srp_erp_crm_categories', 'srp_erp_crm_categories.categoryID = srp_erp_crm_task.categoryID');
        $this->db->join('srp_erp_crm_status', 'srp_erp_crm_status.statusID = srp_erp_crm_task.status');
        $this->db->join('srp_erp_crm_assignees', 'srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_task.taskID', 'LEFT');
        $this->db->where($where);
        $this->db->group_by('taskID');
        $this->db->order_by('taskID', 'desc');
        $data['tasks'] = $this->db->get()->result_array();
        $data['masterID'] = $projectID;
        $this->load->view('system/crm/ajax/load_project_tasks', $data);
    }

    function add_project_notes()
    {
        $this->form_validation->set_rules('projectID', 'Project ID', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Crm_lead_model->add_project_notes());
        }
    }

    function load_project_all_attachments()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $projectID = trim($this->input->post('projectID'));

        $where = "companyID = " . $companyid . " AND documentID = 9  AND documentAutoID = " . $projectID . "";
        $convertFormat = convert_date_format_sql();
        $this->db->select('*');
        $this->db->from('srp_erp_crm_attachments');
        $this->db->where($where);
        $this->db->order_by('attachmentID', 'desc');
        $data['attachment'] = $this->db->get()->result_array();
        $this->load->view('system/crm/ajax/load_all_project_attachements', $data);
    }

    function load_SalesTargetAchievedManagement_view()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $currentuserID = current_userID();
        $text = trim($this->input->post('searchTask'));
        $sorting = trim($this->input->post('filtervalue'));
        $employee = trim($this->input->post('employee'));
        $issuperadmin = crm_isSuperAdmin();
        $convertFormat = convert_date_format_sql();

        $filter_employee = '';
        if (isset($employee) && !empty($employee)) {
            $filter_employee = " AND sta.userID = " . $employee . "";
        }

        if ($issuperadmin['isSuperAdmin'] == 1) {

            $where_admin = "sta.companyID = " . $companyid . $filter_employee;

            /*       $data['header'] = $this->db->query('SELECT salesTargetAcheivedID,acheivedValue,collectionAmount,srp_erp_crm_project.projectName,srp_erp_crm_salestargetacheived.salesTargetID,targetValue,CONCAT(DATE_FORMAT(srp_erp_crm_salestarget.dateFrom,\'' . $convertFormat . '\')," | ", DATE_FORMAT(srp_erp_crm_salestarget.dateTo,\'' . $convertFormat . '\')) AS formattedDate,CurrencyCode FROM srp_erp_crm_salestargetacheived LEFT JOIN srp_erp_crm_project ON srp_erp_crm_salestargetacheived.projectID = srp_erp_crm_project.projectID LEFT JOIN srp_erp_crm_salestarget ON srp_erp_crm_salestargetacheived.salesTargetID = srp_erp_crm_salestarget.salesTargetID LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_salestargetacheived.transactionCurrencyID = srp_erp_currencymaster.currencyID WHERE '.$where_admin.' ORDER BY srp_erp_crm_salestargetacheived.salesTargetAcheivedID DESC')->result_array();*/

            $data['header'] = $this->db->query('SELECT salesTargetID,targetValue,DATE_FORMAT(sta.dateFrom,\'' . $convertFormat . '\') as salesDateFrom,DATE_FORMAT(sta.dateTo,\'' . $convertFormat . '\') AS salesDateTo,CurrencyCode,user.employeeName as employee,sta.userID FROM srp_erp_crm_salestarget sta LEFT JOIN srp_erp_currencymaster cm ON sta.transactionCurrencyID = cm.currencyID LEFT JOIN srp_erp_crm_users user ON user.employeeID = sta.userID WHERE ' . $where_admin . ' ORDER BY sta.salesTargetID DESC')->result_array();

        } else {

            $where_user = "sta.companyID = " . $companyid . $filter_employee . " AND sta.userID = " . $currentuserID;

            $data['header'] = $this->db->query('SELECT salesTargetID,targetValue,DATE_FORMAT(sta.dateFrom,\'' . $convertFormat . '\') as salesDateFrom,DATE_FORMAT(sta.dateTo,\'' . $convertFormat . '\') AS salesDateTo,CurrencyCode,user.employeeName as employee,sta.userID FROM srp_erp_crm_salestarget sta LEFT JOIN srp_erp_currencymaster cm ON sta.transactionCurrencyID = cm.currencyID LEFT JOIN srp_erp_crm_users user ON user.employeeID = sta.userID WHERE ' . $where_user . ' ORDER BY sta.salesTargetID DESC')->result_array();

        }

        $this->load->view('system/crm/ajax/load_sales_target_achieved_master', $data);
    }

    function load_projectBase_period()
    {
        echo json_encode($this->Crm_lead_model->load_projectBase_period());
    }

    function save_sales_targetAchieved_header()
    {
        $this->form_validation->set_rules('userID', 'Employee is required', 'trim|required');
        $this->form_validation->set_rules('dateFrom', 'Date From is required', 'trim|required|validate_date');
        $this->form_validation->set_rules('dateTo', 'Date To is required', 'trim|required|validate_date');
        $this->form_validation->set_rules('transactionCurrencyID', 'Currency is required', 'trim|required');
        $this->form_validation->set_rules('targetValue', 'Amount is required', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            $date_format_policy = date_format_policy();
            $format_dateFrom = input_format_date($this->input->post('dateFrom'), $date_format_policy);
            $format_dateTo = input_format_date($this->input->post('dateTo'), $date_format_policy);
            if ($format_dateTo >= $format_dateFrom) {
                echo json_encode($this->Crm_lead_model->save_sales_targetAchieved_header());
            } else {
                echo json_encode(array('e', 'Date To should be greater than Date From'));
                exit();
            }


        }
    }

    function delete_salesTarget_Acheived()
    {
        echo json_encode($this->Crm_lead_model->delete_salesTarget_Acheived());
    }

    function load_edit_salesTarget_achieved()
    {
        echo json_encode($this->Crm_lead_model->load_edit_salesTarget_achieved());
    }


    function load_opportunity_all_quotation()
    {
        $convertFormat = convert_date_format_sql();
        $companyid = $this->common_data['company_data']['company_id'];
        $opportunityID = trim($this->input->post('opportunityID'));

        $where_admin = "srp_erp_crm_quotation.companyID = " . $companyid . " AND srp_erp_crm_quotation.opportunityID =  $opportunityID";

        $data['header'] = $this->db->query("SELECT quotationAutoID,DATE_FORMAT(quotationDate,'" . $convertFormat . "') AS quotationDate,DATE_FORMAT(quotationExpDate,'" . $convertFormat . "') AS quotationExpDate,quotationCode,srp_erp_crm_organizations.Name as fullname,CurrencyCode,srp_erp_crm_quotation.confirmedYN,opportunityName,srp_erp_crm_quotation.opportunityID FROM srp_erp_crm_quotation LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_quotation.customerID LEFT JOIN srp_erp_currencymaster ON srp_erp_currencymaster.currencyID = srp_erp_crm_quotation.transactionCurrencyID LEFT JOIN srp_erp_crm_opportunity ON srp_erp_crm_opportunity.opportunityID = srp_erp_crm_quotation.opportunityID WHERE $where_admin ORDER BY srp_erp_crm_quotation.quotationAutoID DESC")->result_array();

        $data['page'] = "opportunity";

        $this->load->view('system/crm/ajax/load_quotation_management', $data);

    }

    function load_lead_productsEdit()
    {
        echo json_encode($this->Crm_lead_model->load_lead_productsEdit());
    }

    function load_lead_productsDelete()
    {
        echo json_encode($this->Crm_lead_model->load_lead_productsDelete());
    }


    function save_sales_target_multiple()
    {
        $employeeS = $this->input->post('userID');
        $dateFrom = $this->input->post('dateFrom');
        $dateTo = $this->input->post('dateTo');
        $date_format_policy = date_format_policy();
        foreach ($employeeS as $key => $value) {
            $this->form_validation->set_rules("userID[{$key}]", 'Employee', 'trim|required');
            $this->form_validation->set_rules("dateFrom[{$key}]", 'Date From', 'trim|required|validate_date');
            $this->form_validation->set_rules("dateTo[{$key}]", 'Date To', 'trim|required|validate_date');
            $this->form_validation->set_rules("transactionCurrencyID[{$key}]", 'Currency', 'trim|required');
            $this->form_validation->set_rules("targetValue[{$key}]", 'Amount', 'trim|required');

            $format_dateFrom = input_format_date($dateFrom[$key], $date_format_policy);
            $format_dateTo = input_format_date($dateTo[$key], $date_format_policy);
            if ($format_dateFrom >= $format_dateTo) {
                echo json_encode(array('e', 'Date To should be greater than Date From'));
                exit();
            }
        }

        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $uniqMesg = array_unique($msg);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));
            echo json_encode(array('e', join('', $validateMsg)));
        } else {
            echo json_encode($this->Crm_lead_model->save_sales_target_multiple());
        }
    }


    function load_myprofile_SalesTargetAchievedManagement_view()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $currentuserID = current_userID();
        $text = trim($this->input->post('searchTask'));
        $sorting = trim($this->input->post('filtervalue'));
        $employee = trim($this->input->post('employee'));
        $issuperadmin = crm_isSuperAdmin();
        $convertFormat = convert_date_format_sql();


        $where_admin = "sta.companyID = " . $companyid . " AND userID = $currentuserID";

        /*$data['header'] = $this->db->query('SELECT salesTargetAcheivedID,acheivedValue,collectionAmount,srp_erp_crm_project.projectName,srp_erp_crm_salestargetacheived.salesTargetID,targetValue,CONCAT(DATE_FORMAT(srp_erp_crm_salestarget.dateFrom,\'' . $convertFormat . '\')," | ", DATE_FORMAT(srp_erp_crm_salestarget.dateTo,\'' . $convertFormat . '\')) AS formattedDate,CurrencyCode FROM srp_erp_crm_salestargetacheived LEFT JOIN srp_erp_crm_project ON srp_erp_crm_salestargetacheived.projectID = srp_erp_crm_project.projectID LEFT JOIN srp_erp_crm_salestarget ON srp_erp_crm_salestargetacheived.salesTargetID = srp_erp_crm_salestarget.salesTargetID LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_salestargetacheived.transactionCurrencyID = srp_erp_currencymaster.currencyID WHERE ' . $where_admin . ' ORDER BY srp_erp_crm_salestargetacheived.salesTargetAcheivedID DESC')->result_array();*/

        $data['header'] = $this->db->query('SELECT sta.salesTargetID,targetValue,CONCAT(DATE_FORMAT(sta.dateFrom,\'' . $convertFormat . '\')," | ", DATE_FORMAT(sta.dateTo,\'' . $convertFormat . '\')) AS formattedDate,CurrencyCode FROM srp_erp_crm_salestarget sta LEFT JOIN srp_erp_currencymaster cm ON sta.transactionCurrencyID = cm.currencyID  WHERE ' . $where_admin . ' ORDER BY sta.salesTargetID DESC')->result_array();

        $this->load->view('system/crm/ajax/load_sales_target_achieved_myProfile', $data);
    }


    function save_salesTarget_achived_multiple()
    {
        $salesTargetID = trim($this->input->post('salesTargetID'));
        $dateFromS = $this->input->post('dateFrom');
        foreach ($dateFromS as $key => $value) {
            $this->form_validation->set_rules("dateFrom[{$key}]", 'Date From', 'trim|required|validate_date');
            $this->form_validation->set_rules("acheivedValue[{$key}]", 'Amount', 'trim|required');

            $masterDate = $this->db->query("SELECT dateFrom,dateTo FROM srp_erp_crm_salestarget WHERE salesTargetID = $salesTargetID ")->row_array();

            $dateFrom = date('Y-m-d', strtotime($value));;
            $contractDateBegin = date('Y-m-d', strtotime($masterDate['dateFrom']));
            $contractDateEnd = date('Y-m-d', strtotime($masterDate['dateTo']));

            if (($dateFrom >= $contractDateBegin) && ($dateFrom <= $contractDateEnd)) {
            } else {
                echo json_encode(array('e', 'Date not between period date'));
                exit();
            }
        }

        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $uniqMesg = array_unique($msg);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));
            echo json_encode(array('e', join('', $validateMsg)));
        } else {
            echo json_encode($this->Crm_lead_model->save_salesTarget_achived_multiple());
        }
    }


    function delete_salesTarget_Acheived_profile()
    {
        echo json_encode($this->Crm_lead_model->delete_salesTarget_Acheived_profile());
    }

    function load_edit_salesTarget_achieved_profile()
    {
        echo json_encode($this->Crm_lead_model->load_edit_salesTarget_achieved_profile());
    }

    function save_sales_targetAchieved_header_profile()
    {
        $salesTargetID = trim($this->input->post('salesTargetID'));
        $dateFromS = $this->input->post('dateFrom');

        $masterDate = $this->db->query("SELECT dateFrom,dateTo FROM srp_erp_crm_salestarget WHERE salesTargetID = $salesTargetID ")->row_array();

        $this->form_validation->set_rules('dateFrom', 'Date is required', 'trim|required|validate_date');
        $this->form_validation->set_rules('acheivedValue', 'Amount is required', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {

            $dateFrom = date('Y-m-d', strtotime($dateFromS));;
            $contractDateBegin = date('Y-m-d', strtotime($masterDate['dateFrom']));
            $contractDateEnd = date('Y-m-d', strtotime($masterDate['dateTo']));

            if (($dateFrom >= $contractDateBegin) && ($dateFrom <= $contractDateEnd)) {
            } else {
                echo json_encode(array('e', 'Date not between period date'));
                exit();
            }
            echo json_encode($this->Crm_lead_model->save_sales_targetAchieved_header_profile());
        }
    }


    function load_projectManagement_view_idwise()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $projectIds = trim($this->input->post('projectIds'));
        $category = trim($this->input->post('category'));
        $issuperadmin = crm_isSuperAdmin();
        $currentuserID = current_userID();

        $convertFormat = convert_date_format_sql();

        if ($issuperadmin['isSuperAdmin'] == 1) {

            $where_admin = "srp_erp_crm_project.companyID = " . $companyid . " AND closeStatus = 2 AND srp_erp_crm_project.projectID IN $projectIds AND srp_erp_crm_project.categoryID IN  $category " ;

            $data['header'] = $this->db->query("SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectEndDate, '" . $convertFormat . "') AS projectEndDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_organizations.Name as organizationName FROM srp_erp_crm_project LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_link ON srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8 AND srp_erp_crm_link.documentID = 4 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID WHERE $where_admin GROUP BY srp_erp_crm_project.projectID ORDER BY projectID DESC ")->result_array();
            // echo $this->db->last_query();

        } else {

            $where1 = "srp_erp_crm_documentpermission.documentID = 9 AND srp_erp_crm_documentpermission.permissionID = 1  AND closeStatus = 2 AND srp_erp_crm_project.projectID IN $projectIds AND srp_erp_crm_project.categoryID IN  $category   AND srp_erp_crm_project.companyID = " . $companyid ;

            $where2 = "srp_erp_crm_documentpermission.documentID = 9 AND srp_erp_crm_documentpermission.permissionID = 2  AND closeStatus = 2 AND srp_erp_crm_project.projectID IN $projectIds AND srp_erp_crm_project.categoryID IN $category   AND srp_erp_crm_documentpermission.permissionValue = " . $currentuserID . " AND  srp_erp_crm_project.companyID = " . $companyid ;

            $where3 = "srp_erp_crm_documentpermission.documentID = 9 AND srp_erp_crm_documentpermission.permissionID = 3  AND closeStatus = 2 AND srp_erp_crm_project.projectID IN $projectIds AND srp_erp_crm_project.categoryID IN  $category  AND srp_erp_crm_usergroupdetails.empID = " . $currentuserID . " AND  srp_erp_crm_project.companyID = " . $companyid ;

            $where4 = "srp_erp_crm_documentpermission.documentID = 9 AND srp_erp_crm_documentpermission.permissionID = 4  AND closeStatus = 2 AND srp_erp_crm_project.projectID IN $projectIds AND srp_erp_crm_project.categoryID IN $category   AND srp_erp_crm_documentpermissiondetails.empID = " . $currentuserID . " AND  srp_erp_crm_project.companyID = " . $companyid ;

            $data['header'] = $this->db->query("SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectEndDate, '" . $convertFormat . "') AS projectEndDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_organizations.Name as organizationName,srp_erp_crm_project.isClosed as clsd FROM srp_erp_crm_project LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.projectStatus LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_project.projectID LEFT JOIN srp_erp_crm_link ON srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8 AND srp_erp_crm_link.documentID = 4 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID WHERE $where1 GROUP BY srp_erp_crm_project.projectID UNION SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectEndDate, '" . $convertFormat . "') AS projectEndDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_organizations.Name as organizationName,srp_erp_crm_project.isClosed as clsd FROM srp_erp_crm_project LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.projectStatus LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_project.projectID LEFT JOIN srp_erp_crm_link ON srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8 AND srp_erp_crm_link.documentID = 4 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID WHERE $where2 GROUP BY srp_erp_crm_project.projectID UNION SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectEndDate,'" . $convertFormat . "') AS projectEndDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_organizations.Name as organizationName,srp_erp_crm_project.isClosed as clsd FROM srp_erp_crm_project LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_project.projectID LEFT JOIN srp_erp_crm_usergroupdetails ON srp_erp_crm_usergroupdetails.groupMasterID = srp_erp_crm_documentpermission.permissionValue LEFT JOIN srp_erp_crm_link ON srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8 AND srp_erp_crm_link.documentID = 4 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID WHERE $where3 GROUP BY srp_erp_crm_project.projectID UNION SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectEndDate,'" . $convertFormat . "') AS projectEndDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_organizations.Name as organizationName,srp_erp_crm_project.isClosed as clsd FROM srp_erp_crm_project LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_project.projectID LEFT JOIN srp_erp_crm_documentpermissiondetails ON srp_erp_crm_documentpermissiondetails.documentPermissionID = srp_erp_crm_documentpermission.documentPermissionID LEFT JOIN srp_erp_crm_link ON srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8 AND srp_erp_crm_link.documentID = 4 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID WHERE $where4 GROUP BY srp_erp_crm_project.projectID")->result_array();
            //echo $this->db->last_query();
        }
        $this->load->view('system/crm/ajax/load_project_master_year_wise', $data);
    }



    function load_taskManagement_view_idwise()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $taskIds = trim($this->input->post('taskIds'));
        $issuperadmin = crm_isSuperAdmin();



        if ($issuperadmin['isSuperAdmin'] == 1) {

            $where_admin = "srp_erp_crm_task.companyID = " . $companyid ." AND srp_erp_crm_task.taskID IN ($taskIds)";

            $data['header'] = $this->db->query("SELECT srp_erp_crm_task.taskID,srp_erp_crm_task.subject,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,visibility,DATE_FORMAT(starDate,'%D-%b-%y %h:%i %p') AS starDate,DATE_FORMAT(DueDate,'%D-%b-%y %h:%i %p') AS DueDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_assignees.empID,srp_erp_crm_task.progress,srp_erp_crm_task.status,srp_erp_crm_task.Priority,isClosed FROM srp_erp_crm_task LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_categories.categoryID = srp_erp_crm_task.categoryID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_task.status LEFT JOIN srp_erp_crm_assignees ON srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_task.taskID WHERE $where_admin GROUP BY srp_erp_crm_task.taskID ORDER BY taskID DESC ")->result_array();

        } else {

            $where1 = "srp_erp_crm_documentpermission.documentID = 2 AND srp_erp_crm_documentpermission.permissionID = 1 AND srp_erp_crm_task.taskID IN ($taskIds) AND srp_erp_crm_task.companyID = " . $companyid;

            $where2 = "srp_erp_crm_documentpermission.documentID = 2 AND srp_erp_crm_documentpermission.permissionID = 2 AND srp_erp_crm_task.taskID IN ($taskIds) AND srp_erp_crm_task.companyID = " . $companyid;

            $where3 = "srp_erp_crm_documentpermission.documentID = 2 AND srp_erp_crm_documentpermission.permissionID = 3 AND srp_erp_crm_task.taskID IN ($taskIds) AND srp_erp_crm_task.companyID = " . $companyid;

            $where4 = "srp_erp_crm_documentpermission.documentID = 2 AND srp_erp_crm_documentpermission.permissionID = 4 AND srp_erp_crm_task.taskID IN ($taskIds) AND srp_erp_crm_task.companyID = " . $companyid;

            $data['header'] = $this->db->query("SELECT srp_erp_crm_task.taskID,srp_erp_crm_task.subject,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,visibility,DATE_FORMAT(starDate,'%D-%b-%y %h:%i %p') AS starDate,DATE_FORMAT(DueDate,'%D-%b-%y %h:%i %p') AS DueDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_assignees.empID,srp_erp_crm_task.progress,srp_erp_crm_task.status,srp_erp_crm_task.Priority,isClosed FROM srp_erp_crm_task LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_categories.categoryID = srp_erp_crm_task.categoryID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_task.status LEFT JOIN srp_erp_crm_assignees ON srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_task.taskID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_task.taskID WHERE $where1 UNION SELECT srp_erp_crm_task.taskID,srp_erp_crm_task.subject,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,visibility,DATE_FORMAT(starDate,'%D-%b-%y %h:%i %p') AS starDate,DATE_FORMAT(DueDate,'%D-%b-%y %h:%i %p') AS DueDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_assignees.empID,srp_erp_crm_task.progress,srp_erp_crm_task.status,srp_erp_crm_task.Priority,isClosed FROM srp_erp_crm_task LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_categories.categoryID = srp_erp_crm_task.categoryID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_task.status LEFT JOIN srp_erp_crm_assignees ON srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_task.taskID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_task.taskID WHERE $where2 UNION SELECT srp_erp_crm_task.taskID,srp_erp_crm_task.subject,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,visibility,DATE_FORMAT(starDate,'%D-%b-%y %h:%i %p') AS starDate,DATE_FORMAT(DueDate,'%D-%b-%y %h:%i %p') AS DueDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_assignees.empID,srp_erp_crm_task.progress,srp_erp_crm_task.status,srp_erp_crm_task.Priority,isClosed FROM srp_erp_crm_task LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_categories.categoryID = srp_erp_crm_task.categoryID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_task.status LEFT JOIN srp_erp_crm_assignees ON srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_task.taskID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_task.taskID LEFT JOIN srp_erp_crm_usergroupdetails ON srp_erp_crm_usergroupdetails.groupMasterID = srp_erp_crm_documentpermission.permissionValue WHERE $where3 UNION SELECT srp_erp_crm_task.taskID,srp_erp_crm_task.subject,srp_erp_crm_categories.description as categoryDescription,textColor,backGroundColor,visibility,DATE_FORMAT(starDate,'%D-%b-%y %h:%i %p') AS starDate,DATE_FORMAT(DueDate,'%D-%b-%y %h:%i %p') AS DueDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_assignees.empID,srp_erp_crm_task.progress,srp_erp_crm_task.status,srp_erp_crm_task.Priority,isClosed FROM srp_erp_crm_task LEFT JOIN srp_erp_crm_categories ON srp_erp_crm_categories.categoryID = srp_erp_crm_task.categoryID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_task.status LEFT JOIN srp_erp_crm_assignees ON srp_erp_crm_assignees.MasterAutoID = srp_erp_crm_task.taskID LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_task.taskID LEFT JOIN srp_erp_crm_documentpermissiondetails ON srp_erp_crm_documentpermissiondetails.documentPermissionID = srp_erp_crm_documentpermission.documentPermissionID WHERE $where4 GROUP BY srp_erp_crm_task.taskID ORDER BY taskID DESC")->result_array();

        }
        $this->load->view('system/crm/ajax/load_task_master_id_wise', $data);
    }

    function load_leads_dashboard_dd()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $currentuserID = current_userID();
        $convertFormat = convert_date_format_sql();
        $text = trim($this->input->post('searchTask'));
        $sorting = trim($this->input->post('filtervalue'));
        $status = trim($this->input->post('status'));
        $issuperadmin = crm_isSuperAdmin();
        $datefrom = trim($this->input->post('datefrom'));
        $dateto = trim($this->input->post('dateto'));
        $isGroupAdmin = crm_isGroupAdmin();
        $userresponsible = $this->input->post('employeeID');
        $permissiontype = $this->input->post('permissiontype');
        $where_lead = '';
        $where_lead1= '';
        $where_lead2= '';
        $where_lead3= '';
        $where_lead4= '';
        if (isset($userresponsible) && !empty($userresponsible)) {
            $employeeID = join($userresponsible, ",");
        }
        if (isset($employeeID) && !empty($employeeID)) {
            $where_lead = " AND srp_erp_crm_leadmaster.responsiblePersonEmpID IN ($employeeID)";
        }
        if ($issuperadmin['isSuperAdmin'] == 1 || $isGroupAdmin['adminYN'] == 1) {
            $where_admin = "srp_erp_crm_leadmaster.companyID = " . $companyid .$where_lead;
            $data['header'] = $this->db->query("SELECT srp_erp_crm_leadmaster.leadID,firstName,lastName,phoneMobile,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization,srp_erp_crm_leadmaster.createdUserID as createduserlead,srp_erp_crm_leadmaster.responsiblePersonEmpID,srp_erp_crm_leadmaster.createdUserName as createdUserNamelead,DATE_FORMAT(srp_erp_crm_leadmaster.createdDateTime,'" . $convertFormat . "') AS createdDateTimelead FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID WHERE $where_admin  AND (DATE( srp_erp_crm_leadmaster.createdDateTime ) BETWEEN '$datefrom' AND '$dateto') ORDER BY leadID DESC ")->result_array();

        }else if ($permissiontype == 1)
        {
            $where_lead1 = "WHERE srp_erp_crm_documentpermission.documentID = 5 AND srp_erp_crm_documentpermission.permissionID = 1 AND srp_erp_crm_leadmaster.companyID = '{$companyid}' AND (DATE( srp_erp_crm_leadmaster.createdDateTime ) BETWEEN '$datefrom' AND '$dateto')";
            $where_lead2 = "WHERE srp_erp_crm_documentpermission.documentID = 5 AND srp_erp_crm_documentpermission.permissionID = 2 AND srp_erp_crm_leadmaster.companyID = '{$companyid}' AND (srp_erp_crm_documentpermission.permissionValue = " . $currentuserID . " or srp_erp_crm_leadmaster.createdUserID = " . $currentuserID . ") AND (DATE( srp_erp_crm_leadmaster.createdDateTime ) BETWEEN '$datefrom' AND '$dateto')";
            $where_lead3 = "WHERE srp_erp_crm_documentpermission.documentID = 5 AND srp_erp_crm_documentpermission.permissionID = 3 AND srp_erp_crm_leadmaster.companyID = '{$companyid}' AND (srp_erp_crm_usergroupdetails.empID = " . $currentuserID . " or srp_erp_crm_leadmaster.createdUserID = " . $currentuserID . ") AND (DATE( srp_erp_crm_leadmaster.createdDateTime ) BETWEEN '$datefrom' AND '$dateto')";
            $where_lead4 = "WHERE srp_erp_crm_documentpermission.documentID = 5 AND srp_erp_crm_documentpermission.permissionID = 4  AND srp_erp_crm_leadmaster.companyID = '{$companyid}' AND (srp_erp_crm_documentpermissiondetails.empID = " . $currentuserID . " or srp_erp_crm_leadmaster.createdUserID = " . $currentuserID . ") AND (DATE( srp_erp_crm_leadmaster.createdDateTime ) BETWEEN '$datefrom' AND '$dateto')";
            $data['header'] = $this->db->query("SELECT srp_erp_crm_leadmaster.leadID,firstName,lastName,phoneMobile,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization,srp_erp_crm_leadmaster.createdUserID as createduserlead,srp_erp_crm_leadmaster.responsiblePersonEmpID,srp_erp_crm_leadmaster.createdUserName as createdUserNamelead,DATE_FORMAT(srp_erp_crm_leadmaster.createdDateTime,'" . $convertFormat . "') AS createdDateTimelead FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_leadmaster.leadID LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID $where_lead1 GROUP BY leadID UNION SELECT srp_erp_crm_leadmaster.leadID,firstName,lastName,phoneMobile,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization,srp_erp_crm_leadmaster.createdUserID as createduserlead,srp_erp_crm_leadmaster.responsiblePersonEmpID,srp_erp_crm_leadmaster.createdUserName as createdUserNamelead,DATE_FORMAT(srp_erp_crm_leadmaster.createdDateTime,'" . $convertFormat . "') AS createdDateTimelead FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_leadmaster.leadID LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID $where_lead2 GROUP BY leadID UNION SELECT srp_erp_crm_leadmaster.leadID,firstName,lastName,phoneMobile,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization,srp_erp_crm_leadmaster.createdUserID as createduserlead,srp_erp_crm_leadmaster.responsiblePersonEmpID,srp_erp_crm_leadmaster.createdUserName as createdUserNamelead,DATE_FORMAT(srp_erp_crm_leadmaster.createdDateTime,'" . $convertFormat . "') AS createdDateTimelead FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_leadmaster.leadID LEFT JOIN srp_erp_crm_usergroupdetails ON srp_erp_crm_usergroupdetails.groupMasterID = srp_erp_crm_documentpermission.permissionValue LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID $where_lead3 GROUP BY leadID UNION SELECT srp_erp_crm_leadmaster.leadID,firstName,lastName,phoneMobile,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization,srp_erp_crm_leadmaster.createdUserID as createduserlead,srp_erp_crm_leadmaster.responsiblePersonEmpID,srp_erp_crm_leadmaster.createdUserName as createdUserNamelead,DATE_FORMAT(srp_erp_crm_leadmaster.createdDateTime,'" . $convertFormat . "') AS createdDateTimelead FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_leadmaster.leadID LEFT JOIN srp_erp_crm_documentpermissiondetails ON srp_erp_crm_documentpermissiondetails.documentPermissionID = srp_erp_crm_documentpermission.documentPermissionID LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID $where_lead4 GROUP BY leadID")->result_array();

        }else if ($permissiontype == 2)
        {
            $where_lead1 = "WHERE srp_erp_crm_documentpermission.documentID = 5 AND srp_erp_crm_documentpermission.permissionID = 1 AND srp_erp_crm_leadmaster.companyID = '{$companyid}' AND (DATE( srp_erp_crm_leadmaster.createdDateTime ) BETWEEN '$datefrom' AND '$dateto') AND srp_erp_crm_leadmaster.responsiblePersonEmpID = " . $currentuserID . " ";

            $where_lead2 = "WHERE srp_erp_crm_documentpermission.documentID = 5 AND srp_erp_crm_documentpermission.permissionID = 2 AND srp_erp_crm_documentpermission.permissionValue = " . $currentuserID . " AND srp_erp_crm_leadmaster.companyID = '{$companyid}' AND (DATE( srp_erp_crm_leadmaster.createdDateTime ) BETWEEN '$datefrom' AND '$dateto') AND srp_erp_crm_leadmaster.responsiblePersonEmpID = " . $currentuserID . "";

            $where_lead3 = "WHERE srp_erp_crm_documentpermission.documentID = 5 AND srp_erp_crm_documentpermission.permissionID = 3 AND srp_erp_crm_usergroupdetails.empID = " . $currentuserID . " AND srp_erp_crm_leadmaster.companyID = '{$companyid}' AND (DATE( srp_erp_crm_leadmaster.createdDateTime ) BETWEEN '$datefrom' AND '$dateto') AND srp_erp_crm_leadmaster.responsiblePersonEmpID = " . $currentuserID . "";

            $where_lead4 = "WHERE srp_erp_crm_documentpermission.documentID = 5 AND srp_erp_crm_documentpermission.permissionID = 4 AND srp_erp_crm_documentpermissiondetails.empID = " . $currentuserID . " AND srp_erp_crm_leadmaster.companyID = '{$companyid}' AND (DATE( srp_erp_crm_leadmaster.createdDateTime ) BETWEEN '$datefrom' AND '$dateto') AND srp_erp_crm_leadmaster.responsiblePersonEmpID = " . $currentuserID . "";
            $data['header'] = $this->db->query("SELECT srp_erp_crm_leadmaster.leadID,firstName,lastName,phoneMobile,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization,srp_erp_crm_leadmaster.createdUserID as createduserlead,srp_erp_crm_leadmaster.responsiblePersonEmpID,srp_erp_crm_leadmaster.createdUserName as createdUserNamelead,DATE_FORMAT(srp_erp_crm_leadmaster.createdDateTime,'" . $convertFormat . "') AS createdDateTimelead FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_leadmaster.leadID LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID $where_lead1 GROUP BY leadID UNION SELECT srp_erp_crm_leadmaster.leadID,firstName,lastName,phoneMobile,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization,srp_erp_crm_leadmaster.createdUserID as createduserlead,srp_erp_crm_leadmaster.responsiblePersonEmpID,srp_erp_crm_leadmaster.createdUserName as createdUserNamelead,DATE_FORMAT(srp_erp_crm_leadmaster.createdDateTime,'" . $convertFormat . "') AS createdDateTimelead FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_leadmaster.leadID LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID $where_lead2 GROUP BY leadID UNION SELECT srp_erp_crm_leadmaster.leadID,firstName,lastName,phoneMobile,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization,srp_erp_crm_leadmaster.createdUserID as createduserlead,srp_erp_crm_leadmaster.responsiblePersonEmpID,srp_erp_crm_leadmaster.createdUserName as createdUserNamelead,DATE_FORMAT(srp_erp_crm_leadmaster.createdDateTime,'" . $convertFormat . "') AS createdDateTimelead FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_leadmaster.leadID LEFT JOIN srp_erp_crm_usergroupdetails ON srp_erp_crm_usergroupdetails.groupMasterID = srp_erp_crm_documentpermission.permissionValue LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID $where_lead3 GROUP BY leadID UNION SELECT srp_erp_crm_leadmaster.leadID,firstName,lastName,phoneMobile,srp_erp_crm_leadmaster.email,organization,leadImage,srp_erp_crm_leadstatus.description as statusDescription,isClosed,srp_erp_crm_organizations.Name as linkedorganization,srp_erp_crm_leadmaster.createdUserID as createduserlead,srp_erp_crm_leadmaster.responsiblePersonEmpID,srp_erp_crm_leadmaster.createdUserName as createdUserNamelead,DATE_FORMAT(srp_erp_crm_leadmaster.createdDateTime,'" . $convertFormat . "') AS createdDateTimelead FROM srp_erp_crm_leadmaster LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_leadmaster.leadID LEFT JOIN srp_erp_crm_documentpermissiondetails ON srp_erp_crm_documentpermissiondetails.documentPermissionID = srp_erp_crm_documentpermission.documentPermissionID LEFT JOIN srp_erp_crm_leadstatus ON srp_erp_crm_leadstatus.statusID = srp_erp_crm_leadmaster.statusID LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_leadmaster.linkedorganizationID $where_lead4 GROUP BY leadID")->result_array();
        }

        $this->load->view('system/crm/ajax/load_lead_master_dashboard_dd', $data);





    }

    function load_opportunityManagement_view_dashboard()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $text = trim($this->input->post('searchTask'));
        $sorting = trim($this->input->post('filtervalue'));
        $status = trim($this->input->post('status'));
        $responsible = trim($this->input->post('responsible'));
        $convertFormat = convert_date_format_sql();
        $datefrom = trim($this->input->post('datefrom'));
        $dateto = trim($this->input->post('dateto'));
        $userresponsible = $this->input->post('employeeID');
        $issuperadmin = crm_isSuperAdmin();
        $isGroupAdmin = crm_isGroupAdmin();
        $currentuserID = current_userID();
        $permissiontype = $this->input->post('permissiontype');
        $where_oppo = '';
        $where_opportunity1 ='';
        $where_opportunity2='';
        $where_opportunity3='';
        $where_opportunity4='';
        if (isset($userresponsible) && !empty($userresponsible)) {
            $employeeID = join($userresponsible, ",");
        }
        if (isset($employeeID) && !empty($employeeID)) {
            $where_oppo = " AND srp_erp_crm_opportunity.responsibleEmpID IN ($employeeID)";
        }
        if ($issuperadmin['isSuperAdmin'] == 1 || $isGroupAdmin['adminYN'] == 1) {
            $where_admin = "srp_erp_crm_opportunity.companyID = " . $companyid . $where_oppo;
            $data['header'] = $this->db->query("SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate, '" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_opportunity.createdUserName as campaigncreateduser,DATE_FORMAT(srp_erp_crm_opportunity.createdDatetime, '" . $convertFormat . "') AS createdDatetimeopportunity FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID WHERE $where_admin AND (DATE( srp_erp_crm_opportunity.createdDateTime ) BETWEEN '$datefrom' AND '$dateto') GROUP BY srp_erp_crm_opportunity.opportunityID ORDER BY opportunityID DESC ")->result_array();
        }else if ($permissiontype == 1)
        {
            $where_opportunity1 = "WHERE srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 1 AND srp_erp_crm_opportunity.companyID = '{$companyid}'  AND (DATE(srp_erp_crm_opportunity.createdDateTime) BETWEEN '$datefrom' AND '$dateto')";

            $where_opportunity2 = "WHERE srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 2 AND srp_erp_crm_documentpermission.permissionValue = " . $currentuserID . " AND srp_erp_crm_opportunity.companyID = '{$companyid}'  AND (DATE(srp_erp_crm_opportunity.createdDateTime) BETWEEN '$datefrom' AND '$dateto')";

            $where_opportunity3 = "WHERE srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 3 AND srp_erp_crm_usergroupdetails.empID = " . $currentuserID . " AND srp_erp_crm_opportunity.companyID = '{$companyid}'  AND (DATE(srp_erp_crm_opportunity.createdDateTime) BETWEEN '$datefrom' AND '$dateto')";

            $where_opportunity4 = "WHERE srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 4 AND srp_erp_crm_documentpermissiondetails.empID = " . $currentuserID . " AND srp_erp_crm_opportunity.companyID = '{$companyid}'  AND (DATE(srp_erp_crm_opportunity.createdDateTime) BETWEEN '$datefrom' AND '$dateto')";
            $data['header'] = $this->db->query("SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate, '" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_opportunity.createdUserName as campaigncreateduser,DATE_FORMAT(srp_erp_crm_opportunity.createdDatetime, '" . $convertFormat . "') AS createdDatetimeopportunity FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID 	LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID $where_opportunity1 GROUP BY opportunityID UNION SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate, '" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_opportunity.createdUserName as campaigncreateduser,DATE_FORMAT(srp_erp_crm_opportunity.createdDatetime, '" . $convertFormat . "') AS createdDatetimeopportunity FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID $where_opportunity2 GROUP BY opportunityID UNION SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate, '" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_opportunity.createdUserName as campaigncreateduser,DATE_FORMAT(srp_erp_crm_opportunity.createdDatetime, '" . $convertFormat . "') AS createdDatetimeopportunity FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID LEFT JOIN srp_erp_crm_usergroupdetails ON srp_erp_crm_usergroupdetails.groupMasterID = srp_erp_crm_documentpermission.permissionValue LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID $where_opportunity3 GROUP BY opportunityID UNION SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate, '" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_opportunity.createdUserName as campaigncreateduser,DATE_FORMAT(srp_erp_crm_opportunity.createdDatetime, '" . $convertFormat . "') AS createdDatetimeopportunity FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID LEFT JOIN srp_erp_crm_documentpermissiondetails ON srp_erp_crm_documentpermissiondetails.documentPermissionID = srp_erp_crm_documentpermission.documentPermissionID LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID $where_opportunity4 GROUP BY opportunityID")->result_array();
        }else if ($permissiontype == 2)
        {
            $where_opportunity1 = "WHERE srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 1 AND srp_erp_crm_opportunity.companyID = '{$companyid}'  AND (DATE(srp_erp_crm_opportunity.createdDateTime) BETWEEN '$datefrom' AND '$dateto') AND srp_erp_crm_opportunity.responsibleEmpID = " . $currentuserID . " ";

            $where_opportunity2 = "WHERE srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 2 AND srp_erp_crm_documentpermission.permissionValue = " . $currentuserID . " AND srp_erp_crm_opportunity.companyID = '{$companyid}'  AND (DATE(srp_erp_crm_opportunity.createdDateTime) BETWEEN '$datefrom' AND '$dateto') AND srp_erp_crm_opportunity.responsibleEmpID = " . $currentuserID . "";

            $where_opportunity3 = "WHERE srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 3 AND srp_erp_crm_usergroupdetails.empID = " . $currentuserID . " AND srp_erp_crm_opportunity.companyID = '{$companyid}'  AND (DATE(srp_erp_crm_opportunity.createdDateTime) BETWEEN '$datefrom' AND '$dateto') AND srp_erp_crm_opportunity.responsibleEmpID = " . $currentuserID . "";

            $where_opportunity4 = "WHERE srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 4 AND srp_erp_crm_documentpermissiondetails.empID = " . $currentuserID . " AND srp_erp_crm_opportunity.companyID = '{$companyid}' AND (DATE(srp_erp_crm_opportunity.createdDateTime) BETWEEN '$datefrom' AND '$dateto') AND srp_erp_crm_opportunity.responsibleEmpID = " . $currentuserID . "";
            $data['header'] = $this->db->query("SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate, '" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_opportunity.createdUserName as campaigncreateduser,DATE_FORMAT(srp_erp_crm_opportunity.createdDatetime, '" . $convertFormat . "') AS createdDatetimeopportunity FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID 	LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID $where_opportunity1 GROUP BY opportunityID UNION SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate, '" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_opportunity.createdUserName as campaigncreateduser,DATE_FORMAT(srp_erp_crm_opportunity.createdDatetime, '" . $convertFormat . "') AS createdDatetimeopportunity FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID $where_opportunity2 GROUP BY opportunityID UNION SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate, '" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_opportunity.createdUserName as campaigncreateduser,DATE_FORMAT(srp_erp_crm_opportunity.createdDatetime, '" . $convertFormat . "') AS createdDatetimeopportunity FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID LEFT JOIN srp_erp_crm_usergroupdetails ON srp_erp_crm_usergroupdetails.groupMasterID = srp_erp_crm_documentpermission.permissionValue LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID $where_opportunity3 GROUP BY opportunityID UNION SELECT opportunityID,opportunityName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_opportunity.forcastCloseDate, '" . $convertFormat . "') AS forcastCloseDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_opportunity.createdUserName as campaigncreateduser,DATE_FORMAT(srp_erp_crm_opportunity.createdDatetime, '" . $convertFormat . "') AS createdDatetimeopportunity FROM srp_erp_crm_opportunity LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_opportunity.opportunityID LEFT JOIN srp_erp_crm_documentpermissiondetails ON srp_erp_crm_documentpermissiondetails.documentPermissionID = srp_erp_crm_documentpermission.documentPermissionID LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_opportunity.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_opportunity.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_opportunity.responsibleEmpID $where_opportunity4 GROUP BY opportunityID")->result_array();
        }



            $this->load->view('system/crm/ajax/load_opportunity_master_dd',$data);

}
    function load_projectManagement_view_dashboard_dd()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $text = trim($this->input->post('searchTask'));
        $sorting = trim($this->input->post('filtervalue'));
        $status = trim($this->input->post('status'));
        $activeclose = trim($this->input->post('isactivestatus'));
        $issuperadmin = crm_isSuperAdmin();
        $currentuserID = current_userID();
        $catergory = trim($this->input->post('catergory'));
        $search_string = '';
        $issuperadmin = crm_isSuperAdmin();
        $isGroupAdmin = crm_isGroupAdmin();
        $datefrom = trim($this->input->post('datefrom'));
        $dateto = trim($this->input->post('dateto'));
        $userresponsible = $this->input->post('employeeID');
        $convertFormat = convert_date_format_sql();
        $permissiontype = $this->input->post('permissiontype');
        $where_project1 = '';
        $where_project2 = '';
        $where_project3 = '';
        $where_project4 = '';

        if (isset($userresponsible) && !empty($userresponsible)) {
            $employeeID = join($userresponsible, ",");
        }
        $where_pro = '';
        if (isset($employeeID) && !empty($employeeID)) {
            $where_pro = " AND srp_erp_crm_project.responsibleEmpID IN ($employeeID)";
        }
        if ($issuperadmin['isSuperAdmin'] == 1 || $isGroupAdmin['adminYN'] == 1) {
            $where_admin = "srp_erp_crm_project.companyID = " . $companyid .$where_pro;
            $data['header'] = $this->db->query("SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectEndDate, '" . $convertFormat . "') AS projectEndDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_organizations.Name as organizationName,srp_erp_crm_project.isClosed as isClosed,srp_erp_crm_project.createdUserName as createduserproject,DATE_FORMAT(srp_erp_crm_project.createdDateTime, '" . $convertFormat . "') AS createddateproject FROM srp_erp_crm_project LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_link ON srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8 AND srp_erp_crm_link.documentID = 4 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID WHERE $where_admin AND (DATE( srp_erp_crm_project.createdDateTime ) BETWEEN '$datefrom' AND '$dateto') GROUP BY srp_erp_crm_project.projectID ORDER BY projectID DESC ")->result_array();
        }else if ($permissiontype == 1)
        {
            $where_project1 = "WHERE srp_erp_crm_documentpermission.documentID = 9 AND srp_erp_crm_documentpermission.permissionID = 1 AND srp_erp_crm_project.companyID = '{$companyid}' AND (DATE(srp_erp_crm_project.createdDateTime) BETWEEN '$datefrom' AND '$dateto')";

            $where_project2 = "WHERE srp_erp_crm_documentpermission.documentID = 9 AND srp_erp_crm_documentpermission.permissionID = 2 AND srp_erp_crm_documentpermission.permissionValue = " . $currentuserID . " AND srp_erp_crm_project.companyID = '{$companyid}' AND (DATE(srp_erp_crm_project.createdDateTime) BETWEEN '$datefrom' AND '$dateto')";

            $where_project3 = "WHERE srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 3 AND srp_erp_crm_usergroupdetails.empID = " . $currentuserID . " AND srp_erp_crm_project.companyID = '{$companyid}' AND (DATE(srp_erp_crm_project.createdDateTime) BETWEEN '$datefrom' AND '$dateto')";

            $where_project4 = "WHERE srp_erp_crm_documentpermission.documentID = 9 AND srp_erp_crm_documentpermission.permissionID = 4 AND srp_erp_crm_documentpermissiondetails.empID = " . $currentuserID . " AND srp_erp_crm_project.companyID = '{$companyid}' AND (DATE(srp_erp_crm_project.createdDateTime) BETWEEN '$datefrom' AND '$dateto')";
            $data['header'] = $this->db->query("SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectEndDate, '" . $convertFormat . "') AS projectEndDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_organizations.Name as organizationName,srp_erp_crm_project.isClosed as isClosed,srp_erp_crm_project.createdUserName as createduserproject,DATE_FORMAT(srp_erp_crm_project.createdDateTime, '" . $convertFormat . "') AS createddateproject FROM srp_erp_crm_project LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_project.projectID 	LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_link ON srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8 AND srp_erp_crm_link.documentID = 4 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID  $where_project1 GROUP BY projectID UNION SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectEndDate, '" . $convertFormat . "') AS projectEndDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_organizations.Name as organizationName,srp_erp_crm_project.isClosed as isClosed,srp_erp_crm_project.createdUserName as createduserproject,DATE_FORMAT(srp_erp_crm_project.createdDateTime, '" . $convertFormat . "') AS createddateproject FROM srp_erp_crm_project LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_project.projectID LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_link ON srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8 AND srp_erp_crm_link.documentID = 4 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID $where_project2 GROUP BY projectID UNION SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectEndDate, '" . $convertFormat . "') AS projectEndDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_organizations.Name as organizationName,srp_erp_crm_project.isClosed as isClosed,srp_erp_crm_project.createdUserName as createduserproject,DATE_FORMAT(srp_erp_crm_project.createdDateTime, '" . $convertFormat . "') AS createddateproject FROM srp_erp_crm_project LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_project.projectID LEFT JOIN srp_erp_crm_usergroupdetails ON srp_erp_crm_usergroupdetails.groupMasterID = srp_erp_crm_documentpermission.permissionValue LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_link ON srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8 AND srp_erp_crm_link.documentID = 4 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID $where_project3 GROUP BY projectID UNION SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectEndDate, '" . $convertFormat . "') AS projectEndDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_organizations.Name as organizationName,srp_erp_crm_project.isClosed as isClosed,srp_erp_crm_project.createdUserName as createduserproject,DATE_FORMAT(srp_erp_crm_project.createdDateTime, '" . $convertFormat . "') AS createddateproject FROM srp_erp_crm_project LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_project.projectID LEFT JOIN srp_erp_crm_documentpermissiondetails ON srp_erp_crm_documentpermissiondetails.documentPermissionID = srp_erp_crm_documentpermission.documentPermissionID LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_link ON srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8 AND srp_erp_crm_link.documentID = 4 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID $where_project4 GROUP BY projectID")->result_array();
        }else if ($permissiontype == 2)
        {
            $where_project1 = "WHERE srp_erp_crm_documentpermission.documentID = 9 AND srp_erp_crm_documentpermission.permissionID = 1 AND srp_erp_crm_project.companyID = '{$companyid}' AND (DATE(srp_erp_crm_project.createdDateTime) BETWEEN '$datefrom' AND '$dateto') AND srp_erp_crm_project.responsibleEmpID = " . $currentuserID . "";

            $where_project2 = "WHERE srp_erp_crm_documentpermission.documentID = 9 AND srp_erp_crm_documentpermission.permissionID = 2 AND srp_erp_crm_documentpermission.permissionValue = " . $currentuserID . " AND srp_erp_crm_project.companyID = '{$companyid}' AND (DATE(srp_erp_crm_project.createdDateTime) BETWEEN '$datefrom' AND '$dateto') AND srp_erp_crm_project.responsibleEmpID = " . $currentuserID . "";

            $where_project3 = "WHERE srp_erp_crm_documentpermission.documentID = 4 AND srp_erp_crm_documentpermission.permissionID = 3 AND srp_erp_crm_usergroupdetails.empID = " . $currentuserID . " AND srp_erp_crm_project.companyID = '{$companyid}' AND (DATE(srp_erp_crm_project.createdDateTime) BETWEEN '$datefrom' AND '$dateto') AND srp_erp_crm_project.responsibleEmpID = " . $currentuserID . "";

            $where_project4 = "WHERE srp_erp_crm_documentpermission.documentID = 9 AND srp_erp_crm_documentpermission.permissionID = 4 AND srp_erp_crm_documentpermissiondetails.empID = " . $currentuserID . " AND srp_erp_crm_project.companyID = '{$companyid}' AND (DATE(srp_erp_crm_project.createdDateTime) BETWEEN '$datefrom' AND '$dateto') AND srp_erp_crm_project.responsibleEmpID = " . $currentuserID . "";
            $data['header'] = $this->db->query("SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectEndDate, '" . $convertFormat . "') AS projectEndDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_organizations.Name as organizationName,srp_erp_crm_project.isClosed as isClosed,srp_erp_crm_project.createdUserName as createduserproject,DATE_FORMAT(srp_erp_crm_project.createdDateTime, '" . $convertFormat . "') AS createddateproject FROM srp_erp_crm_project LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_project.projectID 	LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_link ON srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8 AND srp_erp_crm_link.documentID = 4 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID  $where_project1 GROUP BY projectID UNION SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectEndDate, '" . $convertFormat . "') AS projectEndDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_organizations.Name as organizationName,srp_erp_crm_project.isClosed as isClosed,srp_erp_crm_project.createdUserName as createduserproject,DATE_FORMAT(srp_erp_crm_project.createdDateTime, '" . $convertFormat . "') AS createddateproject FROM srp_erp_crm_project LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_project.projectID LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_link ON srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8 AND srp_erp_crm_link.documentID = 4 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID $where_project2 GROUP BY projectID UNION SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectEndDate, '" . $convertFormat . "') AS projectEndDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_organizations.Name as organizationName,srp_erp_crm_project.isClosed as isClosed,srp_erp_crm_project.createdUserName as createduserproject,DATE_FORMAT(srp_erp_crm_project.createdDateTime, '" . $convertFormat . "') AS createddateproject FROM srp_erp_crm_project LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_project.projectID LEFT JOIN srp_erp_crm_usergroupdetails ON srp_erp_crm_usergroupdetails.groupMasterID = srp_erp_crm_documentpermission.permissionValue LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_link ON srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8 AND srp_erp_crm_link.documentID = 4 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID $where_project3 GROUP BY projectID UNION SELECT projectID,projectName,CurrencyCode,transactionAmount,DATE_FORMAT(srp_erp_crm_project.projectEndDate, '" . $convertFormat . "') AS projectEndDate,srp_erp_crm_status.description as statusDescription,srp_erp_crm_users.employeeName as responsiblePerson,pipelineID,pipelineStageID,closeStatus,srp_erp_crm_status.statusColor as statusTextColor,srp_erp_crm_status.statusBackgroundColor as statusBackGroundColor,srp_erp_crm_organizations.Name as organizationName,srp_erp_crm_project.isClosed as isClosed,srp_erp_crm_project.createdUserName as createduserproject,DATE_FORMAT(srp_erp_crm_project.createdDateTime, '" . $convertFormat . "') AS createddateproject FROM srp_erp_crm_project LEFT JOIN srp_erp_crm_documentpermission ON srp_erp_crm_documentpermission.documentAutoID = srp_erp_crm_project.projectID LEFT JOIN srp_erp_crm_documentpermissiondetails ON srp_erp_crm_documentpermissiondetails.documentPermissionID = srp_erp_crm_documentpermission.documentPermissionID LEFT JOIN srp_erp_currencymaster ON srp_erp_crm_project.transactionCurrencyID = srp_erp_currencymaster.currencyID LEFT JOIN srp_erp_crm_status ON srp_erp_crm_status.statusID = srp_erp_crm_project.statusID LEFT JOIN srp_erp_crm_users ON srp_erp_crm_users.employeeID = srp_erp_crm_project.responsibleEmpID LEFT JOIN srp_erp_crm_link ON srp_erp_crm_project.opportunityID = srp_erp_crm_link.MasterAutoID AND relatedDocumentID = 8 AND srp_erp_crm_link.documentID = 4 LEFT JOIN srp_erp_crm_organizations ON srp_erp_crm_organizations.organizationID = srp_erp_crm_link.relatedDocumentMasterID $where_project4 GROUP BY projectID")->result_array();
        }

        $this->load->view('system/crm/ajax/load_project_master_dashboard_dd', $data);
    }
}

