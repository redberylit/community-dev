<?php

class Buyback extends ERP_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Buyback_model');
        $this->load->helper('buyback_helper');
        $this->load->helpers('exceedmatch');
    }

    function load_farmManagement_view()
    {
        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $text = trim($this->input->post('searchTask'));
        $sorting = trim($this->input->post('filtervalue'));
        $location = trim($this->input->post('locationID'));
        $fieldofficer = trim($this->input->post('fieldofficer'));
        $farmtype = trim($this->input->post('farm_type'));
        $status = trim($this->input->post('status'));
        $subarea = trim($this->input->post('subLocationID'));


        $search_string = '';
        if (isset($text) && !empty($text)) {
            $search_string = " AND fm.description Like '%" . $text . "%'";
        }
        $search_sorting = '';
        if (isset($sorting) && $sorting != '#') {
            $search_sorting = " AND fm.description Like '" . $sorting . "%'";
        }

        $location_filter = '';
        if (isset($location) && !empty($location)) {
            $location_filter = " AND fm.locationID = {$location}";
        }

        $subarealocation_filter = '';
        if (isset($subarea) && !empty($subarea)) {
            $subarealocation_filter = " AND fm.subLocationID = {$subarea}";
        }

        $fieldofficer_filter = '';
        if (isset($fieldofficer) && !empty($fieldofficer)) {
            $fieldofficer_filter = " AND fi.empID = {$fieldofficer}";
        }


        $filter_farmtype = '';
        if (isset($farmtype) && !empty($farmtype)) {
            if ($farmtype == 1) {
                $filter_farmtype = " AND fm.farmType = 1 ";
            } else if ($farmtype == 2) {
                $filter_farmtype = " AND fm.farmType = 2 ";
            }
        }

        $filter_status = '';
        if (isset($status) && !empty($status)) {
            if ($status == 1) {
                $filter_status = " AND fm.isActive = 1 ";
            } else if ($status == 2) {
                $filter_status = " AND fm.isActive = 0 ";
            }
        }

        $where_admin = "Where fm.companyID = " . $companyID . $search_string . $search_sorting . $location_filter . $fieldofficer_filter . $filter_farmtype . $filter_status . $subarealocation_filter;

        $data['header'] = $this->db->query("SELECT fm.farmID,fm.farmImage as farmImage,fm.description as farmerName, loc.description as farmerLocation,sublocation.description as farmersubarea,fm.farmType,fm.phoneMobile,fm.farmSystemCode,fm.subLocationID,fm.isActive,cur.CurrencyCode FROM srp_erp_buyback_farmmaster fm LEFT JOIN srp_erp_buyback_locations loc ON loc.locationID = fm.locationID LEFT JOIN srp_erp_currencymaster cur ON cur.currencyID = fm.farmerCurrencyID LEFT JOIN srp_erp_buyback_farmfieldofficers fi ON fi.farmID = fm.farmID LEFT JOIN srp_erp_buyback_locations sublocation ON sublocation.locationID = fm.subLocationID  $where_admin GROUP BY farmID DESC ")->result_array();
        //echo $this->db->last_query();

        $this->load->view('system/buyback/ajax/load_farm_master', $data);
    }

    public function new_location()
    {
        $this->form_validation->set_rules('location', 'Area', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->new_location());
        }
    }

    function save_buyback_sub_location()
    {
        $this->form_validation->set_rules('location', 'Area', 'trim|required');
        $this->form_validation->set_rules('subLocation', 'Sub Area', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->save_buyback_sub_location());
        }
    }

    function save_farm_header()
    {
        $this->form_validation->set_rules('description', 'Farm Name', 'trim|required');
        $this->form_validation->set_rules('farmType', 'Farm Type', 'trim|required');
        $this->form_validation->set_rules('phoneMobile', 'Phone Mobile', 'trim|required');
        $this->form_validation->set_rules('locationID', 'Area', 'trim|required');
        $this->form_validation->set_rules('subLocationID', 'Sub Area', 'trim|required');
        $this->form_validation->set_rules('contactPerson', 'Contact Person', 'trim|required');
        $this->form_validation->set_rules('transactionCurrencyID', 'Currency', 'trim|required');
        $this->form_validation->set_rules('farmersLiabilityGLautoID', 'Farmer Liability Account', 'trim|required');
        $this->form_validation->set_rules('depositAmount', 'Deposit Amount', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            $farmID = trim($this->input->post('farmID'));
            $email = $this->input->post('email');
            $this->db->select('*');
            $this->db->where('email', $email);
            $this->db->from('srp_erp_buyback_farmmaster');
            $detail = $this->db->get()->result_array();
            if (empty($farmID)){
                if(!empty( count($detail))){
                    echo json_encode(array('w','Email Address already exist'));
                }else{
                    echo json_encode($this->Buyback_model->save_farm_header());
                }
            } else{
                if (count($detail) > 1){
                    echo json_encode(array('w','Email Address already exist'));
                }else{
                    echo json_encode($this->Buyback_model->save_farm_header());
                }
            }
        }
    }

    function load_farm_header()
    {
        echo json_encode($this->Buyback_model->load_farm_header());
    }

    function load_farmManagement_editView()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $farmID = trim($this->input->post('farmID'));

        $data['wip'] =  $this->Buyback_model->farmManagement_editView_wip($farmID);
        $data['header'] = $this->db->query("SELECT *,CountryDes,DATE_FORMAT(srp_erp_buyback_farmmaster.createdDateTime,'" . $convertFormat . "') AS createdDate,DATE_FORMAT(srp_erp_buyback_farmmaster.modifiedDateTime,'" . $convertFormat . "') AS modifydate,srp_erp_buyback_farmmaster.createdUserName as createdUserName,srp_erp_buyback_locations.description as locationDes,sublocation.description as subdescription,srp_erp_buyback_farmmaster.description as farmName,DATE_FORMAT(srp_erp_buyback_farmmaster.registeredDate,'" . $convertFormat . "') AS registeredDate,srp_erp_chartofaccounts.systemAccountCode,srp_erp_chartofaccounts.GLDescription,CurrencyCode,CurrencyName,srp_erp_buyback_farmmaster.isActive FROM srp_erp_buyback_farmmaster LEFT JOIN srp_erp_countrymaster ON srp_erp_countrymaster.countryID = srp_erp_buyback_farmmaster.countryID LEFT JOIN srp_erp_buyback_locations ON srp_erp_buyback_locations.locationID = srp_erp_buyback_farmmaster.locationID LEFT JOIN srp_erp_chartofaccounts ON srp_erp_chartofaccounts.GLAutoID = srp_erp_buyback_farmmaster.farmersLiabilityGLautoID LEFT JOIN srp_erp_currencymaster ON srp_erp_currencymaster.currencyID = srp_erp_buyback_farmmaster.farmerCurrencyID LEFT JOIN srp_erp_buyback_locations sublocation ON sublocation.locationID = srp_erp_buyback_farmmaster.subLocationID WHERE farmID = " . $farmID . "")->row_array();

        $this->load->view('system/buyback/ajax/load_farm_edit_view', $data);
    }

    function load_farm_all_batches()
    {

        $companyID = $this->common_data['company_data']['company_id'];
        $farmID = trim($this->input->post('farmID'));

        $convertFormat = convert_date_format_sql();
        $this->db->select('batch.batchMasterID,batch.grade,batch.confirmedYN as batchconfirm,batch.approvedYN as batchapprovedYN,dispatch.batchMasterID as dispatchbatch,batchCode,DATE_FORMAT(batchStartDate,"' . $convertFormat . '") AS batchStartDate,DATE_FORMAT(batchClosingDate,"' . $convertFormat . '") AS batchClosingDate,isclosed,description,c1.systemAccountCode AS wip_SystemCode,c1.GLDescription AS wip_description,c2.systemAccountCode AS dw_SystemCode,c2.GLDescription AS dw_description');
        $this->db->from('srp_erp_buyback_batch batch');
        $this->db->join('(select * from srp_erp_buyback_dispatchnote dn where dn.farmID =' . $farmID . ' GROUP BY dn.batchMasterID)dispatch', 'dispatch.batchMasterID = batch.batchMasterID', 'left');
        $this->db->join('srp_erp_chartofaccounts c1', 'c1.GLAutoID = batch.WIPGLAutoID', 'LEFT');
        $this->db->join('srp_erp_chartofaccounts c2', 'c2.GLAutoID = batch.DirectWagesGLAutoID', 'LEFT');
        $this->db->where('batch.companyID', $companyID);
        $this->db->where('batch.farmID', $farmID);
        $this->db->order_by('batchMasterID', 'desc');
        $data['batch'] = $this->db->get()->result_array();
        $this->load->view('system/buyback/ajax/load_farm_all_batches', $data);
    }

    function add_farm_batch()
    {
        $this->form_validation->set_rules('batchStartDate', 'Start Date', 'trim|required');
        $this->form_validation->set_rules('batchClosingDate', 'Closing Date', 'trim|required');
        $this->form_validation->set_rules('farmID', 'Farm ID', 'trim|required');
        $this->form_validation->set_rules('WIPGLAutoID', 'Work in Progress', 'trim|required');
        $this->form_validation->set_rules('DirectWagesGLAutoID', 'Direct Wages', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            $date_format_policy = date_format_policy();
            $format_closingDate = input_format_date($this->input->post('batchClosingDate'), $date_format_policy);
            $format_startDate = input_format_date($this->input->post('batchStartDate'), $date_format_policy);

            if ($format_closingDate >= $format_startDate) {

                echo json_encode($this->Buyback_model->add_farm_batch());
            } else {
                echo json_encode(array('e', 'Closing Date should be greater than Start Date'));
            }

        }
    }

    function load_farm_batch_header()
    {
        echo json_encode($this->Buyback_model->load_farm_batch_header());
    }

    function load_farm_all_party()
    {

        $companyID = $this->common_data['company_data']['company_id'];
        $farmID = trim($this->input->post('farmID'));

        $convertFormat = convert_date_format_sql();
        $this->db->select('farmPartyAutoID,partyType,partyAutoID,farmID,srp_erp_buyback_farmcustomersupplier.isActive,srp_erp_suppliermaster.supplierName,srp_erp_suppliermaster.supplierSystemCode,srp_erp_customermaster.customerName,srp_erp_customermaster.customerSystemCode');
        $this->db->from('srp_erp_buyback_farmcustomersupplier');
        $this->db->join('srp_erp_suppliermaster', 'srp_erp_suppliermaster.supplierAutoID = srp_erp_buyback_farmcustomersupplier.partyAutoID', 'LEFT');
        $this->db->join('srp_erp_customermaster', 'srp_erp_customermaster.customerAutoID = srp_erp_buyback_farmcustomersupplier.partyAutoID', 'LEFT');
        $this->db->where('srp_erp_buyback_farmcustomersupplier.companyID', $companyID);
        $this->db->where('srp_erp_buyback_farmcustomersupplier.farmID', $farmID);
        $data['party'] = $this->db->get()->result_array();
        $this->load->view('system/buyback/ajax/load_farm_all_party', $data);
    }

    function add_farm_party()
    {
        $partyType = trim($this->input->post('partyType'));
        $this->form_validation->set_rules('partyType', 'Party Type', 'trim|required');
        $this->form_validation->set_rules('farmID', 'Farm ID', 'trim|required');

        if ($partyType == 1) {
            $this->form_validation->set_rules('supplierPrimaryCode', 'Supplier', 'trim|required');
        } else {
            $this->form_validation->set_rules('customerID', 'Customer', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->add_farm_party());
        }

    }

    function load_farm_party_header()
    {
        echo json_encode($this->Buyback_model->load_farm_party_header());
    }

    function load_farm_all_dealers()
    {

        $companyID = $this->common_data['company_data']['company_id'];
        $farmID = trim($this->input->post('farmID'));

        $convertFormat = convert_date_format_sql();
        $this->db->select('farmDealerID,farmID,srp_erp_buyback_farmdealers.isActive,srp_erp_customermaster.customerName,srp_erp_customermaster.customerSystemCode');
        $this->db->from('srp_erp_buyback_farmdealers');
        $this->db->join('srp_erp_customermaster', 'srp_erp_customermaster.customerAutoID = srp_erp_buyback_farmdealers.customerAutoID', 'LEFT');
        $this->db->where('srp_erp_buyback_farmdealers.companyID', $companyID);
        $this->db->where('srp_erp_buyback_farmdealers.farmID', $farmID);
        $data['dealers'] = $this->db->get()->result_array();
        $this->load->view('system/buyback/ajax/load_farm_all_dealers', $data);
    }

    function add_farm_dealers()
    {
        $this->form_validation->set_rules('customerID', 'Customer', 'trim|required');
        $this->form_validation->set_rules('farmID', 'Farm ID', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->add_farm_dealers());
        }
    }

    function add_farm_fieldOfficer()
    {
        $this->form_validation->set_rules('employeeID', 'Employee', 'trim|required');
        $this->form_validation->set_rules('farmID', 'Farm ID', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->add_farm_fieldOfficer());
        }
    }

    function load_farm_dealers_header()
    {
        echo json_encode($this->Buyback_model->load_farm_dealers_header());
    }

    function load_farm_all_warehouse()
    {

        $companyID = $this->common_data['company_data']['company_id'];
        $farmID = trim($this->input->post('farmID'));

        $convertFormat = convert_date_format_sql();
        $this->db->select('farmWarehouseID,farmID,srp_erp_buyback_farmwarehouses.isActive,srp_erp_warehousemaster.wareHouseDescription,srp_erp_warehousemaster.wareHouseCode');
        $this->db->from('srp_erp_buyback_farmwarehouses');
        $this->db->join('srp_erp_warehousemaster', 'srp_erp_warehousemaster.wareHouseAutoID = srp_erp_buyback_farmwarehouses.warehouseMasterID', 'LEFT');
        $this->db->where('srp_erp_buyback_farmwarehouses.companyID', $companyID);
        $this->db->where('srp_erp_buyback_farmwarehouses.farmID', $farmID);
        $data['warehouse'] = $this->db->get()->result_array();
        $this->load->view('system/buyback/ajax/load_farm_all_warehouse', $data);
    }

    function add_farm_warehouse()
    {
        $this->form_validation->set_rules('warehouseMasterID', 'Warehouse', 'trim|required');
        $this->form_validation->set_rules('farmID', 'Farm ID', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->add_farm_warehouse());
        }
    }

    function load_farm_warehouse_header()
    {
        echo json_encode($this->Buyback_model->load_farm_warehouse_header());
    }

    function load_farm_all_notes()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $farmID = trim($this->input->post('farmID'));

        $where = "companyID = " . $companyID . " AND documentID = 1 AND contactID = " . $farmID . "";
        $convertFormat = convert_date_format_sql();
        $this->db->select('*');
        $this->db->from('srp_erp_buyback_notes');
        $this->db->where($where);
        $this->db->order_by('notesID', 'desc');
        $data['notes'] = $this->db->get()->result_array();
        $this->load->view('system/buyback/ajax/load_farm_all_notes', $data);
    }

    function add_farm_notes()
    {
        $this->form_validation->set_rules('farmID', 'Farm ID', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->add_farm_notes());
        }
    }

    function loadfarmnotes()
    {
        $this->form_validation->set_rules('notesID', 'Note ID', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->load_all_notes());
        }
    }


    function load_farm_all_attachments()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $farmID = trim($this->input->post('farmID'));

        $where = "companyID = " . $companyID . " AND documentID = 1  AND documentAutoID = " . $farmID . "";
        $convertFormat = convert_date_format_sql();
        $this->db->select('*');
        $this->db->from('srp_erp_buyback_attachments');
        $this->db->where($where);
        $this->db->order_by('attachmentID', 'desc');
        $data['attachment'] = $this->db->get()->result_array();
        $this->load->view('system/buyback/ajax/load_farm_all_attachments', $data);
    }

    function attachement_upload()
    {
        $this->form_validation->set_rules('attachmentDescription', 'Attachment Description', 'trim|required');
        $this->form_validation->set_rules('documentID', 'documentID', 'trim|required');
        $this->form_validation->set_rules('documentAutoID', 'Document Auto ID', 'trim|required');

        //$this->form_validation->set_rules('document_file', 'File', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 0, 'type' => 'e', 'message' => validation_errors()));
        } else {

            $this->db->trans_start();
            $this->db->select('companyID');
            $this->db->where('documentID', trim($this->input->post('documentID')));
            $num = $this->db->get('srp_erp_buyback_attachments')->result_array();
            $file_name = $this->input->post('document_name') . '_' . $this->input->post('documentID') . '_' . (count($num) + 1);
            $config['upload_path'] = realpath(APPPATH . '../attachments/buyback');
            $config['allowed_types'] = 'gif|jpg|jpeg|png|doc|docx|ppt|pptx|ppsx|pdf|xls|xlsx|xlsxm|rtf|msg|txt|7zip|zip|rar';
            $config['max_size'] = '5120'; // 5 MB
            $config['file_name'] = $file_name;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload("document_file")) {
                echo json_encode(array('status' => 0, 'type' => 'w', 'message' => 'Upload failed ' . $this->upload->display_errors()));
            } else {
                $upload_data = $this->upload->data();
                //$fileName                       = $file_name.'_'.$upload_data["file_ext"];
                $data['documentID'] = trim($this->input->post('documentID'));
                $data['documentAutoID'] = trim($this->input->post('documentAutoID'));
                $data['attachmentDescription'] = trim($this->input->post('attachmentDescription'));
                $data['myFileName'] = $file_name . $upload_data["file_ext"];
                $data['fileType'] = trim($upload_data["file_ext"]);
                $data['fileSize'] = trim($upload_data["file_size"]);
                $data['timestamp'] = date('Y-m-d H:i:s');
                $data['companyID'] = $this->common_data['company_data']['company_id'];
                $data['createdUserGroup'] = $this->common_data['user_group'];
                $data['modifiedPCID'] = $this->common_data['current_pc'];
                $data['modifiedUserID'] = $this->common_data['current_userID'];
                $data['modifiedUserName'] = $this->common_data['current_user'];
                $data['modifiedDateTime'] = $this->common_data['current_date'];
                $data['createdPCID'] = $this->common_data['current_pc'];
                $data['createdUserID'] = $this->common_data['current_userID'];
                $data['createdUserName'] = $this->common_data['current_user'];
                $data['createdDateTime'] = $this->common_data['current_date'];
                $this->db->insert('srp_erp_buyback_attachments', $data);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    echo json_encode(array('status' => 0, 'type' => 'e', 'message' => 'Upload failed ' . $this->db->_error_message()));
                } else {
                    $this->db->trans_commit();
                    echo json_encode(array('status' => 1, 'type' => 's', 'message' => 'Successfully ' . $file_name . ' uploaded.'));
                }
            }
        }
    }

    function delete_farmAttachment()
    {
        $attachmentID = $this->input->post('attachmentID');
        $myFileName = $this->input->post('myFileName');
        $url = base_url("attachments/buyback");
        $link = "$url/$myFileName";
        if (!unlink(UPLOAD_PATH . $link)) {
            echo json_encode(false);
        } else {
            $this->db->delete('srp_erp_buyback_attachments', array('attachmentID' => trim($attachmentID)));
            echo json_encode(true);
        }
    }


    function load_dispatchNoteManagement_view()
    {
        $date_format_policy = date_format_policy();
        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $text = trim($this->input->post('searchTask'));
        $dispatchType = trim($this->input->post('dispatchType'));
        $farmType = trim($this->input->post('farmType'));
        $status = trim($this->input->post('status'));
        $dateto = $this->input->post('dispatchedDateto');
        $datefrom = $this->input->post('dispatchedDatefrom');
        $famrername = $this->input->post('farmername');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $datetoconvert = input_format_date($dateto, $date_format_policy);

        $search_string = '';
        if (isset($text) && !empty($text)) {
            $search_string = " AND ((dpn.documentSystemCode Like '%" . $text . "%') OR (fm.description Like '%" . $text . "%') OR (batch.batchCode Like '%" . $text . "%'))";
        }
        $filter_dispatchType = '';
        if (isset($dispatchType) && !empty($dispatchType)) {
            $filter_dispatchType = " AND dispatchType = {$dispatchType}";
        }
        $filter_famrername = '';
        if (isset($famrername) && !empty($famrername)) {
            $filter_famrername = " AND fm.farmID = {$famrername}";
        }
        $filter_farmerType = '';
        if (isset($farmType) && !empty($farmType)) {
            $filter_farmerType = " AND fm.farmType = {$farmType}";
        }
        $filter_status = '';
        if (isset($status) && !empty($status)) {
            if ($status == 1) {
                $filter_status = " AND dpn.confirmedYN = 0 AND dpn.approvedYN = 0";
            } else if ($status == 2) {
                $filter_status = " AND dpn.confirmedYN = 1 AND dpn.approvedYN = 0";
            } elseif ($status == 3) {
                $filter_status = " AND dpn.confirmedYN = 1 AND dpn.approvedYN = 1";
            }

        }

        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( dispatchedDate >= '" . $datefromconvert . " 00:00:00' AND dispatchedDate <= '" . $datetoconvert . " 23:59:00')";
        }

        $where_admin = "WHERE dpn.companyID = " . $companyID . $search_string . $filter_dispatchType . $filter_farmerType . $filter_status . $date . $filter_famrername;

        $data['header'] = $this->db->query("SELECT dispatchAutoID,dpn.batchMasterID,dpn.documentSystemCode,fm.description as farmName,dpn.confirmedYN,dpn.approvedYN,DATE_FORMAT(dispatchedDate,'" . $convertFormat . "') AS dispatchedDate,dpn.Narration,dpn.transactionCurrency as detailCurrency,dpn.createdUserID,batch.batchCode,fm.farmType as farmerType FROM srp_erp_buyback_dispatchnote dpn LEFT JOIN srp_erp_buyback_farmmaster fm ON fm.farmID = dpn.farmID LEFT JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = dpn.batchMasterID $where_admin ORDER BY dispatchAutoID DESC ")->result_array();
        //echo $this->db->last_query();

        $this->load->view('system/buyback/ajax/load_dispatch_note_master', $data);
    }

    function load_dispatch_detail_items_view()
    {
        $companyID = $this->common_data['company_data']['company_id'];

        $dispatchAutoID = trim($this->input->post('dispatchAutoID'));

        $this->db->select('*');
        $this->db->from('srp_erp_buyback_dispatchnotedetails');
        $this->db->where('srp_erp_buyback_dispatchnotedetails.companyID', $companyID);
        $this->db->where('srp_erp_buyback_dispatchnotedetails.dispatchAutoID', $dispatchAutoID);
        $this->db->order_by('dispatchDetailsID', 'ASC');
        $data['header'] = $this->db->get()->result_array();

        $this->load->view('system/buyback/ajax/load_dispatch_note_item_view', $data);
    }

    function load_dispatch_detail_addonCost_view()
    {
        $companyID = $this->common_data['company_data']['company_id'];

        $dispatchAutoID = trim($this->input->post('dispatchAutoID'));

        $this->db->select('dispatchAddonAutoID,srp_erp_buyback_addon_category.description as addonCategoryName,transactionCurrency,srp_erp_buyback_dispatchnote_addon.GLAutoID,srp_erp_buyback_dispatchnote_addon.GLCode,srp_erp_buyback_dispatchnote_addon.GLDescription,total_amount');
        $this->db->from('srp_erp_buyback_dispatchnote_addon');
        $this->db->join('srp_erp_buyback_addon_category', 'srp_erp_buyback_addon_category.category_id = srp_erp_buyback_dispatchnote_addon.addonCatagory', 'LEFT');
        $this->db->where('srp_erp_buyback_dispatchnote_addon.companyID', $companyID);
        $this->db->where('dispatchAutoID', $dispatchAutoID);
        $this->db->order_by('dispatchAddonAutoID', 'desc');
        $data['header'] = $this->db->get()->result_array();

        $this->load->view('system/buyback/ajax/load_dispatch_note_addonCost_view', $data);
    }

    function save_dispatch_note_header()
    {
        $date_format_policy = date_format_policy();
        $dispatchedDate = $this->input->post('dispatchedDate');
        $deleiverddate = $this->input->post('deliveredDate');
        $batchMasterID = trim($this->input->post('batchMasterID'));
        $formatted_dispatchedDate = input_format_date($dispatchedDate, $date_format_policy);
        $formatted_deliverydate = input_format_date($deleiverddate, $date_format_policy);
        $this->form_validation->set_rules('segment', 'Segment', 'trim|required');
        //$this->form_validation->set_rules('supplierID', 'Supplier', 'trim|required');
        $this->form_validation->set_rules('transactionCurrencyID', 'Currency', 'trim|required');
        $this->form_validation->set_rules('deliveredDate', 'Delivered Date', 'trim|required|validate_date');
        $this->form_validation->set_rules('location', 'Delivery Location', 'trim|required');
        $this->form_validation->set_rules('financeyear', 'Financial Year', 'trim|required');
        $this->form_validation->set_rules('financeyear_period', 'Financial Period', 'trim|required');
        $this->form_validation->set_rules('dispatchedDate', 'Dispatched Date', 'trim|required|validate_date');
        $this->form_validation->set_rules('farmID', 'Farm', 'trim|required');
        $this->form_validation->set_rules('batchMasterID', 'Batch', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            $batchMaster = $this->db->query("SELECT batchStartDate FROM srp_erp_buyback_batch WHERE  batchMasterID = {$batchMasterID}")->row_array();
            $formatted_batchStartDate = input_format_date($batchMaster['batchStartDate'], $date_format_policy);

            if ($formatted_dispatchedDate < $formatted_batchStartDate) {
                echo json_encode(array('e', 'Dispatched Date Should be Greater than Batch Start Date ! (' . $formatted_batchStartDate . ')'));
                exit();
            }

            if ($formatted_deliverydate < $formatted_batchStartDate) {
                echo json_encode(array('e', 'Delivered Date Should be Greater than Batch Start Date ! (' . $formatted_batchStartDate . ')'));
                exit();
            }
            $financearray = $this->input->post('financeyear_period');
            $financePeriod = fetchFinancePeriod($financearray);

            if ($formatted_dispatchedDate >= $financePeriod['dateFrom'] && $formatted_dispatchedDate <= $financePeriod['dateTo']) {
                echo json_encode($this->Buyback_model->save_dispatch_note_header());
            } else {
                echo json_encode(array('e', 'Dispatched Date not between Financial period !'));
                exit();
            }
        }
    }

    function load_dispatchNote_header()
    {
        echo json_encode($this->Buyback_model->load_dispatchNote_header());
    }

    function delete_dispatchNote_master()
    {
        echo json_encode($this->Buyback_model->delete_dispatchNote_master());
    }

    function load_dispatchNote_confirmation()
    {
        /*  echo '<pre>';
          print_r($this->uri);
          die();*/
        $dispatchAutoID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('dispatchAutoID'));
        $batchid = ($this->uri->segment(4)) ? $this->uri->segment(4) : trim($this->input->post('batchid'));
        $batchMasterID = trim($this->input->post('batchid'));
        $data['batchid'] = $batchMasterID;
        $companyID = $this->common_data['company_data']['company_id'];

        $this->db->select("sum(qty) AS chicksTotal");
        $this->db->from("srp_erp_buyback_dispatchnote dpm");
        $this->db->join('srp_erp_buyback_dispatchnotedetails dpd', 'dpd.dispatchAutoID = dpm.dispatchAutoID AND buybackItemType = 1', 'LEFT');
        $this->db->where("batchMasterID", $batchMasterID);
        $this->db->where("dpm.companyID", $companyID);
        $data["chicks"] = $this->db->get()->row_array();


        $data['extra'] = $this->Buyback_model->fetch_dispatchNote_data($dispatchAutoID);
        $data['approval'] = $this->input->post('approval');
        $data['size'] = $this->input->post('size');
        $data['size2'] = $this->input->post('size2');


        $data['feed_header'] = $this->Buyback_model->fetch_feed_details($batchid);
        if ($this->input->post('html')) {
            $html = $this->load->view('system/buyback/dispatch_note_print', $data, true);
            echo $html;
        } else {
            $this->load->library('pdf');
            $html = $this->load->view('system/buyback/dispatch_note_print_pdf', $data, true);
            $pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN']);
        }
    }

    function fetch_farm_BatchesDropdown_all()
    {
        $data_arr = array();
        $farmID = $this->input->post('farmID');
        $companyID = $this->common_data['company_data']['company_id'];
        $batchQry = "SELECT batchMasterID,batchCode FROM srp_erp_buyback_batch WHERE companyID = {$companyID} AND farmID = {$farmID}";
        $batchMaster = $this->db->query($batchQry)->result_array();
        $data_arr = array('' => 'Select Batch');
        if (!empty($batchMaster)) {
            foreach ($batchMaster as $row) {
                $data_arr[trim($row['batchMasterID'])] = trim($row['batchCode']);
            }
        }
        echo form_dropdown('batchMasterID', $data_arr, '', 'class="form-control select2" id="advance_batchMasterID"');
    }

    function fetch_farm_BatchesDropdown()
    {
        $data_arr = array();
        $farmID = $this->input->post('farmID');
        $companyID = $this->common_data['company_data']['company_id'];

        if (!empty($farmID)) {
            $batchQry = "SELECT batchMasterID,batchCode FROM srp_erp_buyback_batch WHERE companyID = {$companyID} AND farmID = {$farmID} AND isclosed = 0";
            $batchMaster = $this->db->query($batchQry)->result_array();
            $data_arr = array('' => 'Select Batch');
            if (!empty($batchMaster)) {
                foreach ($batchMaster as $row) {
                    $data_arr[trim($row['batchMasterID'])] = trim($row['batchCode']);
                }
            }
            echo form_dropdown('batchMasterID', $data_arr, '', 'class="form-control select2 batchMasterClass" id="batchMasterID"');
        }

    }

    function fetch_farm_BatchesDropdown_closed()
    {
        $data_arr = array();
        $farmID = $this->input->post('farmID');
        $companyID = $this->common_data['company_data']['company_id'];
        $batchQry = "SELECT batchMasterID,batchCode FROM srp_erp_buyback_batch WHERE companyID = {$companyID} AND farmID = {$farmID} AND isclosed = 1";
        $batchMaster = $this->db->query($batchQry)->result_array();
        $data_arr = array('' => 'Select Batch');
        if (!empty($batchMaster)) {
            foreach ($batchMaster as $row) {
                $data_arr[trim($row['batchMasterID'])] = trim($row['batchCode']);
            }
        }
        echo form_dropdown('batchMasterID', $data_arr, '', 'class="form-control select2 settleMentClosed" id="advance_batchMasterID" onchange="fetch_batchOutstandingPayable(this.value)"');
    }

    function fetch_farm_BatchesDropdown_closed_all()
    {
        $data_arr = array();
        $farmID = $this->input->post('farmID');
        $companyID = $this->common_data['company_data']['company_id'];
        //$batchQry = "SELECT batchMasterID,batchCode FROM srp_erp_buyback_batch WHERE companyID = {$companyID} AND farmID = {$farmID} AND isclosed = 1";
        $batchQry = "SELECT
	*
FROM
	(
		SELECT
			batchMasterID,
			batchCode,
			batchPayableAmount,
			IFNULL(
				voucher.transactionAmountvoucher,
				0
			) AS transactionAmountvoucher,
			IFNULL(
				srp_erp_buyback_batch.batchPayableAmount - voucher.transactionAmountvoucher,
				0
			) AS balanceAmaount
		FROM
			srp_erp_buyback_batch
		LEFT JOIN (
			SELECT
				BatchID,
				SUM(transactionAmount) AS transactionAmountvoucher
			FROM
				srp_erp_buyback_paymentvoucherdetail
			GROUP BY
				BatchID
		) voucher ON voucher.BatchID = srp_erp_buyback_batch.batchMasterID
		WHERE
			farmID = $farmID
		AND companyID = $companyID
		AND isclosed = 1
	) amount
HAVING
	amount.balanceAmaount > 0";
        $batchMaster = $this->db->query($batchQry)->result_array();
        $data_arr = array('' => 'Select Batch');
        if (!empty($batchMaster)) {
            foreach ($batchMaster as $row) {
                $data_arr[trim($row['batchMasterID'])] = trim($row['batchCode']);
            }
        }
        echo form_dropdown('batchMasterID', $data_arr, '', 'class="form-control select2 settleMentClosed" id="advance_batchMasterID" onchange="fetch_batchOutstandingPayableAll(this.value)"');
    }

    function fetch_buyback_batch_balance_amount()
    {
        $data_arr = array();
        $batchMasterID = $this->input->post('batchMasterID');
        $companyID = $this->common_data['company_data']['company_id'];
        //$batchQry = "SELECT batchMasterID,batchCode FROM srp_erp_buyback_batch WHERE companyID = {$companyID} AND farmID = {$farmID} AND isclosed = 1";
        $batchQry = "SELECT
	*
FROM
	(
		SELECT
			batchMasterID,
			batchCode,
			batchPayableAmount,
			IFNULL(
				voucher.transactionAmountvoucher,
				0
			) AS transactionAmountvoucher,
			IFNULL(
				srp_erp_buyback_batch.batchPayableAmount - voucher.transactionAmountvoucher,
				0
			) AS balanceAmaount
		FROM
			srp_erp_buyback_batch
		LEFT JOIN (
			SELECT
				BatchID,
				SUM(transactionAmount) AS transactionAmountvoucher
			FROM
				srp_erp_buyback_paymentvoucherdetail
			GROUP BY
				BatchID
		) voucher ON voucher.BatchID = srp_erp_buyback_batch.batchMasterID
		WHERE
			batchMasterID = $batchMasterID
		AND companyID = $companyID
		AND isclosed = 1
	) amount
HAVING
	amount.balanceAmaount > 0";
        $batchMaster = $this->db->query($batchQry)->row_array();
        echo json_encode($batchMaster);
    }

    function fetch_farm_BatchesDropdown_array()
    {
        $data_arr = array();
        $farmID = $this->input->post('farmID');
        $companyID = $this->common_data['company_data']['company_id'];
        $batchQry = "SELECT batchMasterID,batchCode FROM srp_erp_buyback_batch WHERE companyID = {$companyID} AND farmID = {$farmID} AND isclosed = 0";
        $batchMaster = $this->db->query($batchQry)->result_array();
        $data_arr = array('' => 'Select Batch');
        if (!empty($batchMaster)) {
            foreach ($batchMaster as $row) {
                $data_arr[trim($row['batchMasterID'])] = trim($row['batchCode']);
            }
        }
        echo form_dropdown('batchMasterID[]', $data_arr, '', 'class="form-control select2"');
    }

    function fetch_farm_BatchesDropdown_visitReport()
    {
        $data_arr = array();
        $farmID = $this->input->post('farmID');
        $companyID = $this->common_data['company_data']['company_id'];

        if (!empty($farmID)) {
            $batchQry = "SELECT batchMasterID,batchCode FROM srp_erp_buyback_batch WHERE companyID = {$companyID} AND farmID = {$farmID} AND isclosed = 0";
            $batchMaster = $this->db->query($batchQry)->result_array();
            $data_arr = array('' => 'Select Batch');
            if (!empty($batchMaster)) {
                foreach ($batchMaster as $row) {
                    $data_arr[trim($row['batchMasterID'])] = trim($row['batchCode']);
                }
            }
            echo form_dropdown('batchMasterID', $data_arr, '', 'class="form-control select2" id="batchMasterID" onchange="batchWiseMasterChange(this.value), fetch_farmVisitNo(this.value)"');
        }

    }

    function fetch_farmVisitNo_visitReport()
    {
        $batchID = $this->input->post('batchID');
        $totalCount = $this->db->query("select COUNT(numberOfVisit) as visitNo FROM srp_erp_buyback_farmervisitreport WHERE batchMasterID = $batchID")->row_array();
        $data =  $totalCount['visitNo'] + 1;

        switch ($data) {
            case 1:
                $result = '1st Visit';
                Break;
            case 2:
                $result = '2nd Visit';
                Break;
            case 3:
                $result = '3rd Visit';
                Break;
            case 4:
                $result = '4th Visit';
                Break;
            case 5:
                $result = '5th Visit';
                Break;
            case 6:
                $result = '6th Visit';
                Break;
            default:
                $result = '';
        }
        echo '<input type="text" name="noofvisit" id="noofvisit" class="form-control" value=" ' . $data . ' " required>';

    }

    function fetch_farm_BatchesDropdown_settlement()
    {
        $data_arr = array();
        $farmID = $this->input->post('farmID');
        $companyID = $this->common_data['company_data']['company_id'];
        $batchQry = "SELECT batchMasterID,batchCode FROM srp_erp_buyback_batch WHERE companyID = {$companyID} AND farmID = {$farmID} AND isclosed = 1";
        $batchMaster = $this->db->query($batchQry)->result_array();
        $data_arr = array('' => 'Select Batch');
        if (!empty($batchMaster)) {
            foreach ($batchMaster as $row) {
                $data_arr[trim($row['batchMasterID'])] = trim($row['batchCode']);
            }
        }
        echo form_dropdown('batchMasterID', $data_arr, '', 'class="form-control select2 settleMentClosed" id="settlement_batchMasterID" onchange="fetch_batchOutstandingPayable(this.value)"');
    }

    function save_dispatchNote_item_detail()
    {
        $searches = $this->input->post('search');
        foreach ($searches as $key => $search) {
            //$this->form_validation->set_rules("search[{$key}]", 'Item', 'trim|required');
            $this->form_validation->set_rules("itemAutoID[{$key}]", 'Item', 'trim|required');
            $this->form_validation->set_rules("UnitOfMeasureID[{$key}]", 'Unit Of Measure', 'trim|required');
            $this->form_validation->set_rules("quantityRequested[{$key}]", 'Quantity', 'trim|required');
            $this->form_validation->set_rules("estimatedAmount[{$key}]", 'Amount', 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $uniqMesg = array_unique($msg);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));
            echo json_encode(array('e', join('', $validateMsg)));
        } else {
            echo json_encode($this->Buyback_model->save_dispatchNote_item_detail());
        }
    }

    function fetch_dispatchNote_item_detail()
    {
        echo json_encode($this->Buyback_model->fetch_dispatchNote_item_detail());
    }

    function fetch_dispatchNote_addonCost_detail()
    {
        echo json_encode($this->Buyback_model->fetch_dispatchNote_addonCost_detail());
    }

    function update_dispatchNote_item_detail()
    {
        $this->form_validation->set_rules('search', 'Item', 'trim|required');
        $this->form_validation->set_rules('itemAutoID', 'Item', 'trim|required');
        $this->form_validation->set_rules('UnitOfMeasureID', 'Unit Of Measure', 'trim|required');
        $this->form_validation->set_rules('quantityRequested', 'Quantity Requested', 'trim|required');
        $this->form_validation->set_rules('estimatedAmount', 'Estimated Amount', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->update_dispatchNote_item_detail());
        }
    }

    function delete_dispatchNote_detail_item()
    {
        echo json_encode($this->Buyback_model->delete_dispatchNote_detail_item());
    }

    function delete_dispatchNote_detail_addon()
    {
        echo json_encode($this->Buyback_model->delete_dispatchNote_detail_addon());
    }

    function save_dispatchNote_addon()
    {
        //$this->form_validation->set_rules('bookingCurrencyID', 'Booking Currency', 'trim|required');
        $this->form_validation->set_rules('total_amount', 'Total Amount', 'trim|required');
        $this->form_validation->set_rules('addonCatagory', 'Addon Catagory', 'trim|required');
        $this->form_validation->set_rules('GLAutoID', 'GL Code', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->save_dispatchNote_addon());
        }
    }

    function fetch_addonCategory_table()
    {
        $this->datatables->select('category_id,description,GLAutoID', false)
            ->from('srp_erp_buyback_addon_category');
        $this->datatables->where('companyID', $this->common_data['company_data']['company_id']);
        $this->datatables->add_column('edit', '$1', 'edit_addonCategoryMaster(category_id)');
        echo $this->datatables->generate();
    }

    function save_addon_categoryMaster()
    {
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('GLAutoID', 'GL Code', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->save_addon_categoryMaster());
        }
    }

    function fetch_addonCategory_detail()
    {
        echo json_encode($this->Buyback_model->fetch_addonCategory_detail());
    }

    function fetch_farmer_currencyID()
    {
        echo json_encode($this->Buyback_model->fetch_farmer_currencyID());
    }


    function load_goodReceiptNoteManagement_view()
    {
        $date_format_policy = date_format_policy();
        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $text = trim($this->input->post('searchTask'));
        $dateto = $this->input->post('grnDateto');
        $datefrom = $this->input->post('grnDatefrom');
        $farmname = $this->input->post('farmername');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $datetoconvert = input_format_date($dateto, $date_format_policy);

        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( grn.documentDate >= '" . $datefromconvert . " 00:00:00' AND grn.documentDate <= '" . $datetoconvert . " 23:59:00')";
        }
        $farm_filter = '';

        if (isset($farmname) && !empty($farmname)) {
            $farm_filter = " AND fm.farmID = {$farmname}";
        }

        $search_string = '';
        if (isset($text) && !empty($text)) {
            $search_string = " AND ((grn.documentSystemCode Like '%" . $text . "%') OR (fm.description Like '%" . $text . "%') OR (batch.batchCode Like '%" . $text . "%'))";
        }

        $where_admin = "Where grn.companyID = " . $companyID . $search_string . $date . $farm_filter;

        $data['header'] = $this->db->query("SELECT grnAutoID,grn.documentSystemCode,grn.confirmedYN,grn.approvedYN,DATE_FORMAT(documentDate,'" . $convertFormat . "') AS documentDate,grn.Narration,grn.transactionCurrency as detailCurrency,fm.description as farmName,grn.createdUserID,batch.batchCode FROM srp_erp_buyback_grn grn LEFT JOIN srp_erp_buyback_farmmaster fm ON fm.farmID = grn.farmID LEFT JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = grn.batchMasterID $where_admin ORDER BY grnAutoID DESC ")->result_array();
        //echo $this->db->last_query();

        $this->load->view('system/buyback/ajax/load_good_ReceiptNote_note_master', $data);
    }

    function save_good_receipt_note_header()
    {
        $date_format_policy = date_format_policy();
        $documentDate = $this->input->post('documentDate');
        $formatted_documentDate = input_format_date($documentDate, $date_format_policy);
        $this->form_validation->set_rules('segment', 'Segment', 'trim|required');
        $this->form_validation->set_rules('transactionCurrencyID', 'Currency', 'trim|required');
        $this->form_validation->set_rules('deliveredDate', 'Delivered Date', 'trim|required|validate_date');
        $this->form_validation->set_rules('location', 'Delivery Location', 'trim|required');
        $this->form_validation->set_rules('financeyear', 'Financial Year', 'trim|required');
        $this->form_validation->set_rules('financeyear_period', 'Financial Period', 'trim|required');
        $this->form_validation->set_rules('documentDate', 'Document Date', 'trim|required|validate_date');
        $this->form_validation->set_rules('farmID', 'Farm', 'trim|required');
        $this->form_validation->set_rules('batchMasterID', 'Batch', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            $financearray = $this->input->post('financeyear_period');
            $financePeriod = fetchFinancePeriod($financearray);
            if ($formatted_documentDate >= $financePeriod['dateFrom'] && $formatted_documentDate <= $financePeriod['dateTo']) {
                echo json_encode($this->Buyback_model->save_good_receipt_note_header());
            } else {
                echo json_encode(array('e', 'Document Date not between Financial period !'));
            }
        }
    }

    function load_good_receiptNote_header()
    {
        echo json_encode($this->Buyback_model->load_good_receiptNote_header());
    }

    function load_GoodReceiptNote_detail_items_view()
    {
        $companyID = $this->common_data['company_data']['company_id'];

        $grnAutoID = trim($this->input->post('grnAutoID'));

        $this->db->select('*');
        $this->db->from('srp_erp_buyback_grndetails');
        $this->db->where('srp_erp_buyback_grndetails.companyID', $companyID);
        $this->db->where('srp_erp_buyback_grndetails.grnAutoID', $grnAutoID);
        $this->db->order_by('grnDetailsID', 'desc');
        $data['header'] = $this->db->get()->result_array();

        $this->load->view('system/buyback/ajax/load_GoodReceiptNote_item_view', $data);
    }

    function save_goodReceiptNote_item_detail()
    {
        $searches = $this->input->post('search');
        foreach ($searches as $key => $search) {
            //$this->form_validation->set_rules("search[{$key}]", 'Item', 'trim|required');
            $this->form_validation->set_rules("itemAutoID[{$key}]", 'Item', 'trim|required');
            $this->form_validation->set_rules("noofbirds[{$key}]", 'No of Birds', 'trim|required');
            $this->form_validation->set_rules("kgweight[{$key}]", 'Weight', 'trim|required');
            $this->form_validation->set_rules("Amount[{$key}]", 'Amount', 'trim|required');
            $this->form_validation->set_rules("UnitOfMeasureID[{$key}]", 'Unit Of Measure', 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $uniqMesg = array_unique($msg);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));
            echo json_encode(array('e', join('', $validateMsg)));
        } else {
            echo json_encode($this->Buyback_model->save_goodReceiptNote_item_detail());
        }
    }

    function fetch_goodReceiptNote_item_detail()
    {
        echo json_encode($this->Buyback_model->fetch_goodReceiptNote_item_detail());
    }

    function update_goodReceiptNote_item_detail()
    {
        $this->form_validation->set_rules('search', 'Item', 'trim|required');
        $this->form_validation->set_rules('itemAutoID', 'Item', 'trim|required');
        $this->form_validation->set_rules('noofbirds', 'No Of Birds', 'trim|required');
        $this->form_validation->set_rules('UnitOfMeasureID', 'Unit Of Measure', 'trim|required');
        $this->form_validation->set_rules('kgweight', 'Quantity Requested', 'trim|required');
        $this->form_validation->set_rules('Amount', 'Estimated Amount', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->update_goodReceiptNote_item_detail());
        }
    }

    function delete_goodReceiptNote_detail_item()
    {
        echo json_encode($this->Buyback_model->delete_goodReceiptNote_detail_item());
    }

    function load_goodReceiptNote_confirmation()
    {
        $data['type'] = $this->input->post('html');
        $data['size'] = $this->input->post('size');
        $grnAutoID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('grnAutoID'));
        $data['extra'] = $this->Buyback_model->fetch_goodReceiptNote_data($grnAutoID);
        $data['approval'] = $this->input->post('approval');
        $html = $this->load->view('system/buyback/good_receipt_note_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN']);
        }
    }

    function fetch_buyback_item()
    {
        $this->datatables->select('buybackItemID,IM.itemSystemCode,CONCAT(IM.itemSystemCode,\'-\',IM.itemName) as itemName, BIM.secondaryItemCode as secondaryItemCode,IM.itemImage,IM.itemDescription,IM.mainCategoryID,IM.mainCategory,IM.defaultUnitOfMeasure,IM.currentStock,IM.companyLocalSellingPrice,IM.companyLocalCurrency,IM.companyLocalCurrencyDecimalPlaces,revenueDescription,IM.costDescription,assetDescription,IM.isActive,IM.companyLocalWacAmount, IC.description as SubCategoryDescription,CONCAT(IM.currentStock,\'  \',IM.defaultUnitOfMeasure) as CurrentStock,CONCAT(IM.companyLocalWacAmount,\'  \',IM.companyLocalCurrency) as TotalWacAmount,IT.description as BuybackItemType,FT.description as feedName,IM.itemAutoID as itemMasterCode', false)
            ->from('srp_erp_buyback_itemmaster BIM')
            ->join('srp_erp_buyback_itemtypes IT', 'IT.buybackItemtypeID = BIM.buybackItemType', 'LEFT')
            ->join('srp_erp_buyback_feedtypes FT', 'BIM.feedType = FT.buybackFeedtypeID', 'LEFT')
            ->join('srp_erp_itemmaster IM', 'IM.itemAutoID = BIM.itemAutoID', 'LEFT')
            ->join('srp_erp_itemcategory IC', 'BIM.subcategoryID = IC.itemCategoryID');
        if (!empty(trim($this->input->post('buybackItemType')))) {
            $this->datatables->where('BIM.buybackItemType', $this->input->post('buybackItemType'));
        }
        if (!empty(trim($this->input->post('mainCategory')))) {
            $this->datatables->where('BIM.mainCategoryID', $this->input->post('mainCategory'));
        }
        $this->datatables->where('BIM.companyID', $this->common_data['company_data']['company_id']);
        //$this->datatables->add_column('confirmed', '$1', 'confirm_mfq(isActive)');
        $this->datatables->add_column('edit', '$1', 'edit_buyback_item(buybackItemID,itemMasterCode)');
        echo $this->datatables->generate();
    }

    function fetch_sync_item()
    {
        $this->datatables->select('itemAutoID,itemSystemCode,itemName,seconeryItemCode,itemImage,itemDescription,mainCategoryID,mainCategory,defaultUnitOfMeasure,currentStock,companyLocalSellingPrice,companyLocalCurrency,companyLocalCurrencyDecimalPlaces,revanueDescription,costDescription,assteDescription,isActive,companyLocalWacAmount,srp_erp_itemcategory.description as SubCategoryDescription,CONCAT(currentStock,\'  \',defaultUnitOfMeasure) as CurrentStock,CONCAT(companyLocalWacAmount,\'  \',companyLocalCurrency) as TotalWacAmount', false)
            ->from('srp_erp_itemmaster')
            ->join('srp_erp_itemcategory', 'srp_erp_itemmaster.subcategoryID = srp_erp_itemcategory.itemCategoryID');
        $this->datatables->where('NOT EXISTS(SELECT * FROM srp_erp_buyback_itemmaster WHERE srp_erp_buyback_itemmaster.itemAutoID = srp_erp_itemmaster.itemAutoID AND companyID =' . $this->common_data['company_data']['company_id'] . ' )');
        $this->datatables->where('srp_erp_itemmaster.companyID', $this->common_data['company_data']['company_id']);
        if (!empty($this->input->post('mainCategory'))) {
            $this->datatables->where('mainCategoryID', $this->input->post('mainCategory'));
        } else {
            $this->datatables->where('srp_erp_itemmaster.mainCategory IN ("Inventory","Non Inventory","Service")');
        }
        if (!empty($this->input->post('subcategory'))) {
            $this->datatables->where('subcategoryID', $this->input->post('subcategory'));
        }
        $this->datatables->add_column('item_inventryCode', '$1 - $2 <b></b>', 'itemSystemCode,itemDescription');
        $this->datatables->add_column('confirmed', '$1', 'confirm(isActive)');
        //$this->datatables->add_column('edit', '<input id="selectItem_$1" value="$1" type="checkbox" onclick="ItemsSelectedSync(this)">', 'itemAutoID');
        $this->datatables->add_column('edit', '<div style="text-align: center;"><div class="skin skin-square item-iCheck"> <div class="skin-section extraColumns"><input id="selectItem_$1" onclick="ItemsSelectedSync(this)" type="checkbox" class="columnSelected"  value="$1" ><label for="checkbox">&nbsp;</label> </div></div></div>', 'itemAutoID');
        echo $this->datatables->generate();
    }

    function add_item()
    {
        $this->form_validation->set_rules('selectedItemsSync[]', 'Item', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Buyback_model->add_item());
        }
    }

    function buyback_itemType_update()
    {
        $buybackItemType = trim($this->input->post('buybackItemType'));
        $this->form_validation->set_rules('buybackItemType', 'Item Type', 'trim|required');
        $this->form_validation->set_rules('buybackItemID', 'Buyback Auto ID', 'trim|required');
        if ($buybackItemType == 2) {
            $this->form_validation->set_rules('buybackFeedType', 'Feed Type', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->buyback_itemType_update());
        }
    }

    function fetch_buyback_item_recode()
    {
        echo json_encode($this->Buyback_model->fetch_buyback_item_recode());
    }

    function dispatch_note_confirmation()
    {
        echo json_encode($this->Buyback_model->dispatch_note_confirmation());
    }

    function buyback_production_report()
    {
        $this->form_validation->set_rules('batchMasterID', 'Batch ID', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error_message = validation_errors();
            echo warning_message($error_message);
        } else {
            $data = array();
            $batchMasterID = trim($this->input->post('batchMasterID'));
            $convertFormat = convert_date_format_sql();
            $companyID = current_companyID();
            $farmID = trim($this->input->post('farmID'));
            $totalFarmerpay = 0;

            if(empty($farmID)){
                $farmDetail = $this->db->query("SELECT farmID FROM `srp_erp_buyback_batch` WHERE batchMasterID = $batchMasterID")->row_array();
                $farmID = $farmDetail['farmID'];
            }

            $data["batchDetail"] = $this->Buyback_model->getBatch_detail($this->input->post('batchMasterID'));

            $this->db->select('*, DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate');
            $this->db->from("srp_erp_buyback_itemledger");
            $this->db->where("batchID", $batchMasterID);
            $this->db->where("companyID", $companyID);
            $this->db->where("documentCode", 'BBDPN');
            $this->db->order_by("buybackItemType ASC");
            //$this->db->group_by("buybackItemType");
            $data["dispatch"] = $this->db->get()->result_array();

            $this->db->select('pvd.GLCode,pvd.GLDescription,DATE_FORMAT(pvm.documentDate,\'' . $convertFormat . '\') AS documentDate,pvd.transactionAmount,pvd.comment as expenseDescription');
            $this->db->from("srp_erp_buyback_paymentvoucherdetail pvd");
            $this->db->join('srp_erp_buyback_paymentvouchermaster pvm', 'pvd.pvMasterAutoID = pvm.pvMasterAutoID', 'LEFT');
            $this->db->where("pvd.BatchID", $batchMasterID);
            $this->db->where("pvd.companyID", $companyID);
            $this->db->where("pvd.type", 'Expense');
            $this->db->where("pvm.approvedYN", 1);
            $this->db->order_by("pvDetailID DESC");
            $data["expense"] = $this->db->get()->result_array();

            $this->db->select('sum(noOfBirds) AS totalBirds');
            $this->db->from("srp_erp_buyback_mortalitydetails md");
            $this->db->join('srp_erp_buyback_mortalitymaster mm', 'md.mortalityAutoID = mm.mortalityAutoID', 'LEFT');
            $this->db->where("mm.batchMasterID", $batchMasterID);
            $this->db->where("md.companyID", $companyID);
            $this->db->where("mm.confirmedYN", 1);
            $data["mortality"] = $this->db->get()->row_array();

            $this->db->select('*,DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate');
            $this->db->from("srp_erp_buyback_itemledger");
            $this->db->where("batchID", $batchMasterID);
            $this->db->where("companyID", $companyID);
            $this->db->where("documentCode", 'BBGRN');
            $this->db->order_by("itemLedgerAutoID ASC");
            $data["buyback"] = $this->db->get()->result_array();


            $this->db->select('returnmaster.returnAutoID,returnmaster.documentSystemCode,CONCAT(dismaster.documentSystemCode,\' - \',disreturn.itemDescription) as descriptiton,disreturn.qty as returnedqty,disreturn.unitTransferCost as rate,disreturn.totalTransferCost,DATE_FORMAT(returnmaster.returnedDate,\'' . $convertFormat . '\') AS returneddate');
            $this->db->from("srp_erp_buyback_dispatchreturn returnmaster ");
            $this->db->join('srp_erp_buyback_dispatchreturndetails disreturn','disreturn.returnAutoID = returnmaster.returnAutoID','left');
            $this->db->join('srp_erp_buyback_dispatchnote dismaster','dismaster.dispatchAutoID = disreturn.dispatchAutoID','left');
            $this->db->where("returnmaster.batchMasterID", $batchMasterID);
            $this->db->where("returnmaster.companyID", $companyID);
            $this->db->where("returnmaster.approvedYN", 1);
            $this->db->where("returnmaster.confirmedYN", 1);
            $data["return"] = $this->db->get()->result_array();

            $this->db->select('returnmaster.returnAutoID,returnmaster.documentSystemCode,CONCAT(dismaster.documentSystemCode,\' - \',disreturn.itemDescription) as descriptiton,disreturn.qty as returnedqty,disreturn.unitTransferCost as rate,disreturn.totalTransferCost,DATE_FORMAT(returnmaster.returnedDate,\'' . $convertFormat . '\') AS returneddate');
            $this->db->from("srp_erp_buyback_dispatchreturn returnmaster ");
            $this->db->join('srp_erp_buyback_dispatchreturndetails disreturn','disreturn.returnAutoID = returnmaster.returnAutoID','left');
            $this->db->join('srp_erp_buyback_dispatchnote dismaster','dismaster.dispatchAutoID = disreturn.dispatchAutoID','left');
            $this->db->where("returnmaster.batchMasterID", $batchMasterID);
            $this->db->where("returnmaster.companyID", $companyID);
            $this->db->where("returnmaster.approvedYN", 1);
            $this->db->where("returnmaster.confirmedYN", 1);
            $data["returns"] = $this->db->get()->result_array();


            $this->db->select("sum(transactionQTY) AS chicksTotal");
            $this->db->from("srp_erp_buyback_itemledger");
            $this->db->where("batchID", $batchMasterID);
            $this->db->where("companyID", $companyID);
            $this->db->where("documentCode", 'BBDPN');
            $this->db->where("buybackItemType", 1);
            $data["chicks"] = $this->db->get()->row_array();

            $this->db->select("sum(transactionQTY) AS feedTotal");
            $this->db->from("srp_erp_buyback_itemledger");
            $this->db->where("batchID", $batchMasterID);
            $this->db->where("companyID", $companyID);
            $this->db->where("documentCode", 'BBDPN');
            $this->db->where("buybackItemType", 2);
            $data["feed"] = $this->db->get()->row_array();

            $this->db->select('farmDealerID,farmID,srp_erp_buyback_farmdealers.isActive,srp_erp_customermaster.customerName,srp_erp_customermaster.customerSystemCode');
            $this->db->from('srp_erp_buyback_farmdealers');
            $this->db->join('srp_erp_customermaster', 'srp_erp_customermaster.customerAutoID = srp_erp_buyback_farmdealers.customerAutoID', 'LEFT');
            $this->db->where('srp_erp_buyback_farmdealers.companyID', $companyID);
            $this->db->where('srp_erp_buyback_farmdealers.farmID', $farmID);
            $data['dealers'] = $this->db->get()->row_array();

            $data['batchOutstanding'] = $this->db->query("SELECT COALESCE(SUM(batchPayableAmount),0) as oustanding FROM `srp_erp_buyback_batch` WHERE farmID = $farmID")->row_array();

            $data['batchTotalPaid'] = $this->db->query("SELECT COALESCE (SUM(pvd.wagesAmount), 0) AS wagesAmount FROM
	srp_erp_buyback_paymentvouchermaster pvm LEFT JOIN (SELECT pvMasterAutoID, type, SUM(transactionAmount) AS wagesAmount FROM
		srp_erp_buyback_paymentvoucherdetail GROUP BY pvMasterAutoID) pvd ON pvm.pvMasterAutoID = pvd.pvMasterAutoID WHERE farmID = $farmID AND PVtype = 3 AND approvedYN = 1")->row_array();

            $birdsKGWeight = 0;
            $birdsTotalCount = 0;
            $feedTot = 0;
            $weightPercentage = 0;
            $fcr = 0;
            foreach ($data["buyback"] as $buy) {
                $birdsKGWeight += $buy["transactionQTY"];
                $birdsTotalCount += $buy["noOfBirds"];
            }

            if (!empty($data['feed']) && !empty($data['chicks']) && !empty($data['birdsTotalCount'])) {
                $feedTot = ($data['chicks']['chicksTotal'] + $birdsTotalCount) / 2;
                $feedPercentage = ($data['feed']['feedTotal'] * 50) / $feedTot;
            }

            if (!empty($birdsKGWeight) && !empty($birdsTotalCount)) {
                $weightPercentage = ($birdsKGWeight / $birdsTotalCount);
            }

            if (!empty($weightPercentage) && !empty($feedPercentage)) {

                $fcr = ($feedPercentage / $weightPercentage);
            }


            $data['fcr'] = $fcr;
            $data["type"] = "html";
            $data["typecostYN"] = $this->input->post('typecostYN');
            $this->load->view('system/buyback/report/production_report_view', $data);

        }
    }


    function buyback_production_report_pdf()
    {
        $this->form_validation->set_rules('batchMasterID', 'Batch ID', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error_message = validation_errors();
            echo warning_message($error_message);
        } else {
            $data = array();
            $batchMasterID = trim($this->input->post('batchMasterID'));
            $convertFormat = convert_date_format_sql();
            $companyID = current_companyID();

            $data["batchDetail"] = $this->Buyback_model->getBatch_detail($this->input->post('batchMasterID'));

            $this->db->select('*, DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate');
            $this->db->from("srp_erp_buyback_itemledger");
            $this->db->where("batchID", $batchMasterID);
            $this->db->where("companyID", $companyID);
            $this->db->where("documentCode", 'BBDPN');
            $this->db->order_by("buybackItemType ASC");
            //$this->db->group_by("buybackItemType");
            $data["dispatch"] = $this->db->get()->result_array();

            $this->db->select('pvd.GLCode,pvd.GLDescription,DATE_FORMAT(pvm.documentDate,\'' . $convertFormat . '\') AS documentDate,pvd.transactionAmount,pvd.comment as expenseDescription');
            $this->db->from("srp_erp_buyback_paymentvoucherdetail pvd");
            $this->db->join('srp_erp_buyback_paymentvouchermaster pvm', 'pvd.pvMasterAutoID = pvm.pvMasterAutoID', 'LEFT');
            $this->db->where("pvd.BatchID", $batchMasterID);
            $this->db->where("pvd.companyID", $companyID);
            $this->db->where("pvd.type", 'Expense');
            $this->db->where("pvm.approvedYN", 1);
            $this->db->order_by("pvDetailID DESC");
            $data["expense"] = $this->db->get()->result_array();

            $this->db->select('sum(noOfBirds) AS totalBirds');
            $this->db->from("srp_erp_buyback_mortalitydetails md");
            $this->db->join('srp_erp_buyback_mortalitymaster mm', 'md.mortalityAutoID = mm.mortalityAutoID', 'LEFT');
            $this->db->where("mm.batchMasterID", $batchMasterID);
            $this->db->where("md.companyID", $companyID);
            $this->db->where("mm.confirmedYN", 1);
            $data["mortality"] = $this->db->get()->row_array();

            $this->db->select('*,DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate');
            $this->db->from("srp_erp_buyback_itemledger");
            $this->db->where("batchID", $batchMasterID);
            $this->db->where("companyID", $companyID);
            $this->db->where("documentCode", 'BBGRN');
            $this->db->order_by("itemLedgerAutoID ASC");
            $data["buyback"] = $this->db->get()->result_array();

            $this->db->select("sum(transactionQTY) AS chicksTotal");
            $this->db->from("srp_erp_buyback_itemledger");
            $this->db->where("batchID", $batchMasterID);
            $this->db->where("companyID", $companyID);
            $this->db->where("documentCode", 'BBDPN');
            $this->db->where("buybackItemType", 1);
            $data["chicks"] = $this->db->get()->row_array();

            $this->db->select("sum(transactionQTY) AS feedTotal");
            $this->db->from("srp_erp_buyback_itemledger");
            $this->db->where("batchID", $batchMasterID);
            $this->db->where("companyID", $companyID);
            $this->db->where("documentCode", 'BBDPN');
            $this->db->where("buybackItemType", 2);
            $data["feed"] = $this->db->get()->row_array();

            $data["type"] = "html";
            $html = $this->load->view('system/buyback/report/production_report_view_pdf', $data, true);

            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4');
        }
    }

    function buyback_Batch_ClosingLock()
    {
        $this->form_validation->set_rules('batchMasterID', 'Batch ID', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error_message = validation_errors();
            echo warning_message($error_message);
        } else {
            $data = array();
            $data["batchDetail"] = $this->Buyback_model->getBatch_detail($this->input->post('batchMasterID'));
            $data["type"] = "html";
            $data["output"] = $this->Buyback_model->getProductionReport_detail($this->input->post('batchMasterID'));
            $this->load->view('system/buyback/report/production_report_BatchLock', $data);
        }
    }

    function load_batch_Master_view()
    {
        $date_format_policy = date_format_policy();
        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $farmer = trim($this->input->post('farmer'));
        $location = trim($this->input->post('locationID'));
        $fieldofficer = trim($this->input->post('fieldofficer'));
        $status = trim($this->input->post('batches_status'));
        $subarea = trim($this->input->post('subLocationID'));
        $dateto = $this->input->post('batchmasterDateto');
        $datefrom = $this->input->post('batchmasterDatefrom');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $datetoconvert = input_format_date($dateto, $date_format_policy);


        $text = trim($this->input->post('searchTask'));

        $search_string = '';
        if (isset($text) && !empty($text)) {
            $search_string = " AND (fm.description Like '%" . $text . "%' OR batchCode Like '%" . $text . "%')";
        }

        $f_farmer = '';
        if (isset($farmer) && !empty($farmer)) {
            $f_farmer = " AND fm.farmID = {$farmer}";
        }


        $location_filter = '';
        if (isset($location) && !empty($location)) {
            $location_filter = " AND fm.locationID = {$location}";
        }

        $fieldofficer_filter = '';
        if (isset($fieldofficer) && !empty($fieldofficer)) {
            $fieldofficer_filter = " AND fi.empID = {$fieldofficer}";
        }

        $filter_status = '';
        if (isset($status) && !empty($status)) {
            if ($status == 1) {
                $filter_status = " AND batch.confirmedYN = 0 AND batch.approvedYN = 0";
            } else if ($status == 2) {
                $filter_status = " AND batch.confirmedYN = 1 AND batch.approvedYN = 0";
            } elseif ($status == 3) {
                $filter_status = " AND batch.confirmedYN = 1 AND batch.approvedYN = 1";
            }

        }
        $subarealocation_filter = '';
        if (isset($subarea) && !empty($subarea)) {
            $subarealocation_filter = " AND fm.subLocationID = {$subarea}";
        }

        $date = '';
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( batchStartDate >= '" . $datefromconvert . " 00:00:00' AND batchClosingDate <= '" . $datetoconvert . " 23:59:00')";
        }


        $where_admin = "Where batch.companyID = " . $companyID . $search_string . $f_farmer . $location_filter . $fieldofficer_filter . $filter_status . $subarealocation_filter . $date;

        /*   $data['batch'] = $this->db->query("SELECT batchMasterID,batchCode,DATE_FORMAT(batchStartDate,' . $convertFormat . ') AS batchStartDate,DATE_FORMAT(batchClosingDate,' . $convertFormat . ') AS batchClosingDate,isclosed,c1.systemAccountCode AS wip_SystemCode,c1.GLDescription AS wip_description,c2.systemAccountCode AS dw_SystemCode,c2.GLDescription AS dw_description,fm.description as farmerName,fm.locationID,fm.subLocationID,fi.empID,isclosed,batch.confirmedYN,batch.approvedYN FROM srp_erp_buyback_batch batch LEFT JOIN srp_erp_chartofaccounts c1 ON c1.GLAutoID = batch.WIPGLAutoID LEFT JOIN srp_erp_chartofaccounts c2 ON c2.GLAutoID = batch.DirectWagesGLAutoID LEFT JOIN srp_erp_buyback_farmmaster fm ON fm.farmID = batch.farmID LEFT JOIN srp_erp_buyback_farmfieldofficers fi ON fi.farmID = fm.farmID $where_admin GROUP BY batchMasterID ORDER BY batchClosingDate ASC ")->result_array();*/


        $data['batch'] = $this->db->query("SELECT batch.batchMasterID,receivedtotaltbl.receivedtotal as receivedtotal,batchCode,DATE_FORMAT(batchStartDate,' . $convertFormat . ') AS batchStartDate,DATE_FORMAT(batchClosingDate,' . $convertFormat . ') AS batchClosingDate,isclosed,c1.systemAccountCode AS wip_SystemCode,c1.GLDescription AS wip_description,c2.systemAccountCode AS dw_SystemCode,c2.GLDescription AS dw_description,fm.description as farmerName,fm.locationID,fm.subLocationID,fi.empID,isclosed,batch.confirmedYN,batch.approvedYN FROM srp_erp_buyback_batch batch LEFT JOIN srp_erp_chartofaccounts c1 ON c1.GLAutoID = batch.WIPGLAutoID LEFT JOIN srp_erp_chartofaccounts c2 ON c2.GLAutoID = batch.DirectWagesGLAutoID LEFT JOIN srp_erp_buyback_farmmaster fm ON fm.farmID = batch.farmID LEFT JOIN srp_erp_buyback_farmfieldofficers fi ON fi.farmID = fm.farmID LEFT JOIN (
SELECT COALESCE
	( sum( qty ), 0 ) AS chicksTotal,
	batchMasterID,
	confirmedYN,
	approvedYN
FROM
	srp_erp_buyback_dispatchnotedetails dpd
	INNER JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID = dpd.dispatchAutoID 
	AND buybackItemType = 1 
WHERE
	dpm.confirmedYN = 1 
	AND dpm.approvedYN = 1 
GROUP BY
	batchMasterID 
	) chicksTotaltbl ON chicksTotaltbl.batchMasterID = batch.batchMasterID 

LEFT JOIN (SELECT COALESCE ( sum( grnd.noOfBirds ), 0 ) AS receivedtotal,batchMasterID,confirmedYN,approvedYN FROM 	srp_erp_buyback_grndetails grnd INNER JOIN srp_erp_buyback_grn grn ON  grn.grnAutoID = grnd.grnAutoID WHERE  confirmedYN = 1 ANd approvedYN =1 
GROUP BY
batchMasterID
)receivedtotaltbl ON receivedtotaltbl.batchMasterID = batch.batchMasterID 

LEFT JOIN(SELECT COALESCE(sum(noOfBirds), 0) AS deadChicksTotal,batchMasterID,confirmedYN FROM srp_erp_buyback_mortalitymaster mm INNER JOIN srp_erp_buyback_mortalitydetails md ON mm.mortalityAutoID = md.mortalityAutoID WHERE confirmedYN = 1

GROUP BY
 batchMasterID

)deadChicksTotal on deadChicksTotal.batchMasterID = batch.batchMasterID  $where_admin AND chicksTotaltbl.confirmedYN = 1 
	AND chicksTotaltbl.approvedYN = 1 
 GROUP BY batch.batchMasterID  ORDER BY batchCode DESC ")->result_array();


        $this->load->view('system/buyback/ajax/load_batch_master', $data);
    }

    function buyback_batchLock_confirmation()
    {
        $this->form_validation->set_rules('Grading', 'Grading', 'trim|required');
        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->buyback_batchLock_confirmation());
        }
    }

    function save_mortality_header()
    {
        $this->form_validation->set_rules('documentDate', 'Document Date', 'trim|required|validate_date');
        $this->form_validation->set_rules('farmID', 'Farm', 'trim|required');
        $this->form_validation->set_rules('batchMasterID', 'Batch', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->save_mortality_header());
        }
    }

    function load_mortality_Master_view()
    {

        $date_format_policy = date_format_policy();
        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $text = trim($this->input->post('searchTask'));

        $dateto = $this->input->post('mortalityDateto');
        $datefrom = $this->input->post('mortalityDatefrom');
        $farm = $this->input->post('farmername');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $datetoconvert = input_format_date($dateto, $date_format_policy);

        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( mm.documentDate >= '" . $datefromconvert . " 00:00:00' AND mm.documentDate <= '" . $datetoconvert . " 23:59:00')";
        }

        $farm_filter = '';
        if (isset($farm) && !empty($farm)) {
            $farm_filter = " AND fm.farmID = {$farm}";
        }

        $search_string = '';
        if (isset($text) && !empty($text)) {
            $search_string = " AND fm.description Like '%" . $text . "%' OR batch.batchCode Like '%" . $text . "%'";
        }

        $where_admin = "Where batch.companyID = " . $companyID . $search_string . $date  . $farm_filter;
     //   $where_admin = "Where mm.companyID = " . $companyID . $search_string;

        //$data['batch'] = $this->db->query("SELECT * FROM srp_erp_buyback_batch $where_admin ORDER BY batchMasterID DESC ")->result_array();
        $data['batch'] = $this->db->query("SELECT mm.mortalityAutoID,batch.isclosed,DATE_FORMAT(documentDate,' . $convertFormat . ') AS documentDate,fm.description as farmerName,batch.batchCode,mm.confirmedYN,mm.createdUserID FROM srp_erp_buyback_mortalitymaster mm LEFT JOIN srp_erp_buyback_farmmaster fm ON fm.farmID = mm.farmID LEFT JOIN srp_erp_buyback_batch batch ON mm.batchMasterID = batch.batchMasterID $where_admin ORDER BY mortalityAutoID DESC ")->result_array();
        //echo $this->db->last_query();

        $this->load->view('system/buyback/ajax/load_mortality_master', $data);
    }

    function load_mortality_header()
    {
        echo json_encode($this->Buyback_model->load_mortality_header());
    }

    function save_mortality_birds_detail()
    {
        $causeIDs = $this->input->post('causeID');
        foreach ($causeIDs as $key => $causeID) {
            $this->form_validation->set_rules("causeID[{$key}]", 'Mortality Cause', 'trim|required');
            $this->form_validation->set_rules("noOfBirds[{$key}]", 'No of Birds', 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $uniqMesg = array_unique($msg);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));
            echo json_encode(array('e', join('', $validateMsg)));
        } else {
            echo json_encode($this->Buyback_model->save_mortality_birds_detail());
        }
    }

    function load_mortalityBird_detail_view()
    {
        $companyID = $this->common_data['company_data']['company_id'];

        $mortalityAutoID = trim($this->input->post('mortalityAutoID'));

        $this->db->select('*,srp_erp_buyback_mortalitycauses.Description as mortalityNaturalCause');
        $this->db->from('srp_erp_buyback_mortalitydetails');
        $this->db->join('srp_erp_buyback_mortalitycauses', 'srp_erp_buyback_mortalitycauses.causeID = srp_erp_buyback_mortalitydetails.causeID', 'LEFT');
        $this->db->where('srp_erp_buyback_mortalitydetails.companyID', $companyID);
        $this->db->where('srp_erp_buyback_mortalitydetails.mortalityAutoID', $mortalityAutoID);

        $this->db->order_by('mortalityDetailID', 'desc');
        $data['header'] = $this->db->get()->result_array();

        $this->load->view('system/buyback/ajax/load_mortalityBirds_detail_view', $data);
    }

    function fetch_mortality_bird_detail()
    {
        echo json_encode($this->Buyback_model->fetch_mortality_bird_detail());
    }

    function update_mortality_birds_detail()
    {
        $this->form_validation->set_rules('causeID', 'Mortality Cause', 'trim|required');
        $this->form_validation->set_rules('noOfBirds', 'No Of Birds', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->update_mortality_birds_detail());
        }
    }

    function delete_mortality_birds_detail()
    {
        echo json_encode($this->Buyback_model->delete_mortality_birds_detail());
    }

    function delete_mortality_master()
    {
        echo json_encode($this->Buyback_model->delete_mortality_master());
    }

    function load_mortality_confirmation()
    {
        $data['type'] = $this->input->post('html');
        $mortalityAutoID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('mortalityAutoID'));
        $data['extra'] = $this->Buyback_model->load_mortality_confirmation($mortalityAutoID);
        $html = $this->load->view('system/buyback/mortality_birds_print', $data, TRUE);

        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4');
        }
    }

    function load_production_report_confirmation()
    {
        $convertFormat = convert_date_format_sql();
        $companyID = current_companyID();
        $batchMasterID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('batchMasterID'));

        $data["batchDetail"] = $this->Buyback_model->getBatch_detail($this->input->post('batchMasterID'));

        $this->db->select('*, DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate');
        $this->db->from("srp_erp_buyback_itemledger");
        $this->db->where("batchID", $batchMasterID);
        $this->db->where("companyID", $companyID);
        $this->db->where("documentCode", 'BBDPN');
        $this->db->order_by("itemLedgerAutoID ASC");
        $data["dispatch"] = $this->db->get()->result_array();

        $this->db->select('pvd.GLCode,pvd.GLDescription,DATE_FORMAT(pvm.documentDate,\'' . $convertFormat . '\') AS documentDate,pvd.transactionAmount');
        $this->db->from("srp_erp_buyback_paymentvoucherdetail pvd");
        $this->db->join('srp_erp_buyback_paymentvouchermaster pvm', 'pvd.pvMasterAutoID = pvm.pvMasterAutoID', 'LEFT');
        $this->db->where("pvd.BatchID", $batchMasterID);
        $this->db->where("pvd.companyID", $companyID);
        $this->db->where("pvd.type", 'Expense');
        $this->db->where("pvm.approvedYN", 1);
        $this->db->order_by("pvDetailID DESC");
        $data["expense"] = $this->db->get()->result_array();

        $this->db->select('sum(noOfBirds) AS totalBirds');
        $this->db->from("srp_erp_buyback_mortalitydetails md");
        $this->db->join('srp_erp_buyback_mortalitymaster mm', 'md.mortalityAutoID = mm.mortalityAutoID', 'LEFT');
        $this->db->where("mm.batchMasterID", $batchMasterID);
        $this->db->where("md.companyID", $companyID);
        $this->db->where("mm.confirmedYN", 1);
        $data["mortality"] = $this->db->get()->row_array();

        $this->db->select('*,DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate');
        $this->db->from("srp_erp_buyback_itemledger");
        $this->db->where("batchID", $batchMasterID);
        $this->db->where("companyID", $companyID);
        $this->db->where("documentCode", 'BBGRN');
        $this->db->order_by("itemLedgerAutoID ASC");
        $data["buyback"] = $this->db->get()->result_array();

        $this->db->select("sum(transactionQTY) AS chicksTotal");
        $this->db->from("srp_erp_buyback_itemledger");
        $this->db->where("batchID", $batchMasterID);
        $this->db->where("companyID", $companyID);
        $this->db->where("documentCode", 'BBDPN');
        $this->db->where("buybackItemType", 1);
        $data["chicks"] = $this->db->get()->row_array();

        $this->db->select("sum(transactionQTY) AS feedTotal");
        $this->db->from("srp_erp_buyback_itemledger");
        $this->db->where("batchID", $batchMasterID);
        $this->db->where("companyID", $companyID);
        $this->db->where("documentCode", 'BBDPN');
        $this->db->where("buybackItemType", 2);
        $data["feed"] = $this->db->get()->row_array();

        $data['approval'] = $this->input->post('approval');
        $html = $this->load->view('system/buyback/report/production_report_print', $data, TRUE);

        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4-L', $data['extra']['master']['approvedYN']);
        }
    }

    function mortality_confirmation()
    {
        echo json_encode($this->Buyback_model->mortality_confirmation());
    }

    function referback_mortality()
    {

        $mortalityAutoID = trim($this->input->post('mortalityAutoID'));

        $dataUpdate = array(
            'confirmedYN' => 0,
            'confirmedByEmpID' => '',
            'confirmedByName' => '',
            'confirmedDate' => '',
        );

        $this->db->where('mortalityAutoID', $mortalityAutoID);
        $this->db->update('srp_erp_buyback_mortalitymaster', $dataUpdate);

        echo json_encode(array('s', ' Referred Back Successfully.'));
    }

    function fetch_farm_BatchesDropdown_mortality()
    {
        $data_arr = array();
        $farmID = $this->input->post('farmID');
        $companyID = $this->common_data['company_data']['company_id'];
        $batchQry = "SELECT bb.batchMasterID,bb.batchCode FROM srp_erp_buyback_batch bb LEFT JOIN srp_erp_buyback_mortalitymaster mm ON bb.batchMasterID = mm.batchMasterID WHERE bb.companyID = {$companyID} AND bb.farmID = {$farmID} AND bb.isclosed = 0 ";
        //AND (mm.confirmedYN = 0 OR mm.confirmedYN IS NULL)
        $batchMaster = $this->db->query($batchQry)->result_array();
        $data_arr = array('' => 'Select Batch');
        if (!empty($batchMaster)) {
            foreach ($batchMaster as $row) {
                $data_arr[trim($row['batchMasterID'])] = trim($row['batchCode']);
            }
        }
        echo form_dropdown('batchMasterID', $data_arr, '', 'class="form-control select2" id="batchMasterID"');
    }

    function referback_dispatchnote()
    {
        $dispatchAutoID = trim($this->input->post('dispatchAutoID'));

        $this->load->library('approvals');
        $status = $this->approvals->approve_delete($dispatchAutoID, 'BBDPN');
        if ($status == 1) {
            echo json_encode(array('s', ' Referred Back Successfully.', $status));
        } else {
            echo json_encode(array('e', ' Error in refer back.', $status));
        }
    }

    function referback_GoodReceiptnote()
    {
        $grnAutoID = trim($this->input->post('grnAutoID'));

        $this->load->library('approvals');
        $status = $this->approvals->approve_delete($grnAutoID, 'BBGRN');
        if ($status == 1) {
            echo json_encode(array('s', ' Referred Back Successfully.', $status));
        } else {
            echo json_encode(array('e', ' Error in refer back.', $status));
        }
    }

    function load_mortalityCourses_Master_view()
    {
        $companyID = $this->common_data['company_data']['company_id'];

        $data['cause'] = $this->db->query("SELECT * FROM srp_erp_buyback_mortalitycauses WHERE companyID = {$companyID} ORDER BY causeID DESC ")->result_array();

        $this->load->view('system/buyback/ajax/load_mortalityCourses_master', $data);
    }


    function load_mortalityCourses_header()
    {
        echo json_encode($this->Buyback_model->load_mortalityCourses_header());
    }

    function save_mortalityCourses_header()
    {
        $this->form_validation->set_rules('description', 'Description', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->save_mortalityCourses_header());
        }
    }


    function save_grn_detail()
    {
        $qtyChicks = trim($this->input->post('detail_qtyChicks'));
        $mortality = trim($this->input->post('detail_qtyMortality'));
        $noOfBirds = trim($this->input->post('noOfBirds'));

        $this->form_validation->set_rules('itemAutoID', 'Item', 'trim|required');
        $this->form_validation->set_rules('noOfBirds', 'Qty Received Bird', 'trim|required');
        $this->form_validation->set_rules('quantityRequested', 'Qty Received Weight', 'trim|required');
        $this->form_validation->set_rules('estimatedAmount', 'Amount', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            if ((($qtyChicks - $mortality) >= $noOfBirds) && ($noOfBirds != 0)) {
                echo json_encode($this->Buyback_model->save_grn_detail());
            } else {
                Die(json_encode(['e', 'Number of birds cannot be greater than chicks']));
            }

        }
    }

    function delete_mortalityCourse()
    {
        echo json_encode($this->Buyback_model->delete_mortalityCourse());
    }

    function good_receipt_note_confirmation()
    {
        echo json_encode($this->Buyback_model->good_receipt_note_confirmation());
    }

    function fetch_farmerDetails_For_dispatchNote()
    {
        echo json_encode($this->Buyback_model->fetch_farmerDetails_For_dispatchNote());
    }


    function load_paymentVoucherManagement_view()
    {
        $date_format_policy = date_format_policy();
        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $text = trim($this->input->post('searchTask'));
        $farmer = trim($this->input->post('farmer'));
        $vouchertype = trim($this->input->post('vouchertype'));
        $status = trim($this->input->post('status'));
        $dateto = $this->input->post('voucherDateto');
        $datefrom = $this->input->post('voucherDatefrom');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $datetoconvert = input_format_date($dateto, $date_format_policy);
        $date = "";

        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( pvm.documentDate >= '" . $datefromconvert . " 00:00:00' AND pvm.documentDate <= '" . $datetoconvert . " 23:59:00')";
        }

        $search_string = '';
        if (isset($text) && !empty($text)) {
            $search_string = " AND ((pvm.documentSystemCode Like '%" . $text . "%') OR (fm.description Like '%" . $text . "%'))";
        }

        $f_farmer = '';
        if (isset($farmer) && !empty($farmer)) {
            $f_farmer = " AND fm.farmID = {$farmer}";
        }

        $vouchertypefilter = '';
        if (isset($vouchertype) && !empty($vouchertype)) {
            if ($vouchertype == 1) {
                $vouchertypefilter = " AND PVtype = 1 ";
            } else if ($vouchertype == 2) {
                $vouchertypefilter = " AND PVtype = 2 ";
            } else if ($vouchertype == 3) {
                $vouchertypefilter = " AND PVtype = 3";
            }
        }

        $statusfilter = '';
        if (isset($status) && !empty($status)) {
            if ($status == 1) {
                $statusfilter = " AND pvm.confirmedYN = 1 AND pvm.approvedYN = 0";
            } else if ($status == 2) {
                $statusfilter = " AND pvm.confirmedYN = 0 AND pvm.approvedYN = 0";
            } else if ($status == 3) {
                $statusfilter = " AND pvm.confirmedYN = 1 AND pvm.approvedYN = 1";
            }

        }


        $where_admin = "WHERE pvm.companyID = " . $companyID . $search_string . $f_farmer . $vouchertypefilter . $statusfilter . $date;

        $data['header'] = $this->db->query("SELECT pvMasterAutoID,documentID,documentSystemCode,DATE_FORMAT(documentDate,' . $convertFormat . ') AS documentDate,pvm.PVNarration,pvm.confirmedYN,pvm.approvedYN,fm.description as farmName,pvm.createdUserID,PVtype FROM srp_erp_buyback_paymentvouchermaster pvm LEFT JOIN srp_erp_buyback_farmmaster fm ON fm.farmID = pvm.farmID $where_admin ORDER BY pvMasterAutoID DESC ")->result_array();
        //echo $this->db->last_query();

        $this->load->view('system/buyback/ajax/load_payment_voucher_master', $data);
    }

    function save_payment_voucher_header()
    {
        $date_format_policy = date_format_policy();
        $PVtype = trim($this->input->post('PVtype'));
        $documentDate = $this->input->post('documentDate');
        $formatted_documentDate = input_format_date($documentDate, $date_format_policy);
        $this->form_validation->set_rules('PVtype', 'Voucher Type', 'trim|required');
        $this->form_validation->set_rules('documentDate', 'Document Date', 'trim|required|validate_date');
        $this->form_validation->set_rules('transactionCurrencyID', 'Currency', 'trim|required');
        $this->form_validation->set_rules('financeyear', 'Financial Year', 'trim|required');
        $this->form_validation->set_rules('financeyear_period', 'Financial Period', 'trim|required');
        $this->form_validation->set_rules('farmID', 'Farm', 'trim|required');
        $this->form_validation->set_rules('segment', 'Segment', 'trim|required');
        if ($PVtype != 3) {
            $this->form_validation->set_rules('PVbankCode', 'Bank or Cash', 'trim|required');
        }
        if ($PVtype == 3) {
            $this->form_validation->set_rules('batchMasterID', 'Batch ID', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            $financearray = $this->input->post('financeyear_period');
            $financePeriod = fetchFinancePeriod($financearray);
            if ($formatted_documentDate >= $financePeriod['dateFrom'] && $formatted_documentDate <= $financePeriod['dateTo']) {
                echo json_encode($this->Buyback_model->save_payment_voucher_header());
            } else {
                echo json_encode(array('e', 'Document Date not between Financial period !'));
            }

        }
    }

    function load_paymentVoucher_header()
    {
        echo json_encode($this->Buyback_model->load_paymentVoucher_header());
    }

    function load_paymentVoucher_expense_view()
    {
        $companyID = $this->common_data['company_data']['company_id'];

        $pvMasterAutoID = trim($this->input->post('pvMasterAutoID'));

        $where = "WHERE pvd.companyID = " . $companyID . " AND pvd.type = 'Expense' AND pvd.pvMasterAutoID = " . $pvMasterAutoID . "";

        $data['expense'] = $this->db->query("SELECT pvDetailID,comment,transactionAmount,batch.batchCode,transactionCurrency,GLCode,GLDescription,segmentCode FROM srp_erp_buyback_paymentvoucherdetail pvd LEFT JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = pvd.BatchID $where ORDER BY pvDetailID DESC ")->result_array();

        $this->load->view('system/buyback/ajax/load_paymentVoucher_expense_view', $data);
    }

    function load_paymentVoucher_advance_view()
    {

        $companyID = $this->common_data['company_data']['company_id'];

        $pvMasterAutoID = trim($this->input->post('pvMasterAutoID'));

        $where = "WHERE pvd.companyID = " . $companyID . " AND pvd.type = 'Advance' AND pvd.pvMasterAutoID = " . $pvMasterAutoID . "";

        $data['advance'] = $this->db->query("SELECT pvDetailID,comment,transactionAmount,batch.batchCode,transactionCurrency FROM srp_erp_buyback_paymentvoucherdetail pvd LEFT JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = pvd.BatchID $where ORDER BY pvDetailID DESC ")->result_array();

        $this->load->view('system/buyback/ajax/load_paymentVoucher_advance_view', $data);
    }

    function load_paymentVoucher_batch_view()
    {

        $companyID = $this->common_data['company_data']['company_id'];

        $pvMasterAutoID = trim($this->input->post('pvMasterAutoID'));

        $where = "WHERE pvd.companyID = " . $companyID . " AND pvd.type = 'Batch' AND pvd.pvMasterAutoID = " . $pvMasterAutoID . "";

        $data['advance'] = $this->db->query("SELECT pvDetailID,comment,transactionAmount,batch.batchCode,transactionCurrency FROM srp_erp_buyback_paymentvoucherdetail pvd LEFT JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = pvd.BatchID $where ORDER BY pvDetailID DESC ")->result_array();

        $this->load->view('system/buyback/ajax/load_paymentVoucher_batch_view', $data);
    }

    function save_paymentVoucher_advance()
    {
        //$this->form_validation->set_rules('batchMasterID', 'Batch', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->save_paymentVoucher_advance());
        }
    }

    function load_receiptVoucher_deposit_view()
    {
        $companyID = $this->common_data['company_data']['company_id'];

        $pvMasterAutoID = trim($this->input->post('pvMasterAutoID'));

        $where = "WHERE pvd.companyID = " . $companyID . " AND pvd.type = 'Deposit' AND pvd.pvMasterAutoID = " . $pvMasterAutoID . "";

        $data['income'] = $this->db->query("SELECT pvDetailID,comment,transactionAmount,transactionCurrency,GLCode,GLDescription,segmentCode FROM srp_erp_buyback_paymentvoucherdetail pvd $where ORDER BY pvDetailID DESC ")->result_array();

        $this->load->view('system/buyback/ajax/load_receiptVoucher_deposit_view', $data);
    }

    function load_paymentVoucher_loan_view()
    {
        $companyID = $this->common_data['company_data']['company_id'];

        $pvMasterAutoID = trim($this->input->post('pvMasterAutoID'));

        $where = "WHERE pvd.companyID = " . $companyID . " AND pvd.type = 'Loan' AND pvd.pvMasterAutoID = " . $pvMasterAutoID . "";

        $data['loan'] = $this->db->query("SELECT pvDetailID,comment,transactionAmount,transactionCurrency,isMatching FROM srp_erp_buyback_paymentvoucherdetail pvd $where ORDER BY pvDetailID DESC ")->result_array();

        $this->load->view('system/buyback/ajax/load_paymentVoucher_loan_view', $data);
    }

    function load_paymentVoucher_confirmation()
    {
        $pvMasterAutoID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('pvMasterAutoID'));
        $data['typehtml'] = $this->input->post('html');
        $data['size'] = $this->input->post('size');
        $data['extra'] = $this->Buyback_model->fetch_paymentVoucher_data($pvMasterAutoID);
        $data['approval'] = $this->input->post('approval');
        $html = $this->load->view('system/buyback/payment_voucher_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN']);
        }
    }

    function save_paymentVoucher_expense_multiple()
    {
        $gl_codes = $this->input->post('gl_code');
        $segment_gls = $this->input->post('segment_gl');
        $descriptions = $this->input->post('description');
        $amount = $this->input->post('amount');

        foreach ($gl_codes as $key => $gl_code) {
            $this->form_validation->set_rules("batchMasterID[{$key}]", 'Batch', 'required|trim');
            $this->form_validation->set_rules("gl_code[{$key}]", 'GL Code', 'required|trim');
            $this->form_validation->set_rules("segment_gl[{$key}]", 'Segment', 'required|trim');
            $this->form_validation->set_rules("amount[{$key}]", 'Amount', 'trim|required');
            $this->form_validation->set_rules("description[{$key}]", 'Description', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $trimmed_array = array_map('trim', $msg);
            $uniqMesg = array_unique($trimmed_array);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));
            echo json_encode(array('e', join('', $validateMsg)));
        } else {
            echo json_encode($this->Buyback_model->save_paymentVoucher_expense_multiple());
        }
    }

    function save_receiptVoucher_income_multiple()
    {
        $gl_codes = $this->input->post('gl_code_income');
        $segment_gls = $this->input->post('segment_gl');
        $descriptions = $this->input->post('description');
        $amount = $this->input->post('amount');

        foreach ($gl_codes as $key => $gl_code) {
            $this->form_validation->set_rules("gl_code_income[{$key}]", 'GL Code', 'required|trim');
            $this->form_validation->set_rules("segment_gl[{$key}]", 'Segment', 'required|trim');
            $this->form_validation->set_rules("amount[{$key}]", 'Amount', 'trim|required');
            $this->form_validation->set_rules("description[{$key}]", 'Description', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $trimmed_array = array_map('trim', $msg);
            $uniqMesg = array_unique($trimmed_array);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));
            echo json_encode(array('e', join('', $validateMsg)));
        } else {
            echo json_encode($this->Buyback_model->save_receiptVoucher_income_multiple());
        }
    }

    function delete_paymentVoucher_master()
    {
        echo json_encode($this->Buyback_model->delete_paymentVoucher_master());
    }

    function paymentVoucher_confirmation()
    {
        echo json_encode($this->Buyback_model->paymentVoucher_confirmation());
    }

    function referback_paymentVoucher()
    {
        $pvMasterAutoID = trim($this->input->post('pvMasterAutoID'));

        $this->db->select("documentID");
        $this->db->from("srp_erp_buyback_paymentvouchermaster");
        $this->db->where("pvMasterAutoID", $pvMasterAutoID);
        $masterVoucher = $this->db->get()->row_array();

        $this->load->library('approvals');
        $status = $this->approvals->approve_delete($pvMasterAutoID, $masterVoucher['documentID']);
        if ($status == 1) {
            echo json_encode(array('s', ' Referred Back Successfully.', $status));
        } else {
            echo json_encode(array('e', ' Error in refer back.', $status));
        }
    }

    function fetch_paymentVoucher_expense_detail()
    {
        echo json_encode($this->Buyback_model->fetch_paymentVoucher_expense_detail());
    }

    function update_paymentVoucher_expense_detail()
    {
        $this->form_validation->set_rules('batchMasterID', 'Batch', 'trim|required');
        $this->form_validation->set_rules('gl_code', 'GL Code', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('segment_gl', 'Segment', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->update_paymentVoucher_expense_detail());
        }
    }

    function update_receiptVoucher_income_detail()
    {
        $this->form_validation->set_rules('gl_code', 'GL Code', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('segment_gl', 'Segment', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->update_receiptVoucher_income_detail());
        }
    }

    function delete_paymentVoucher_expense_detail()
    {
        echo json_encode($this->Buyback_model->delete_paymentVoucher_expense_detail());
    }

    function delete_paymentVoucher_advance_detail()
    {
        echo json_encode($this->Buyback_model->delete_paymentVoucher_advance_detail());
    }

    function update_paymentVoucher_advance_detail()
    {
        $this->form_validation->set_rules('batchMasterID', 'Batch', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->update_paymentVoucher_advance_detail());
        }
    }

    function delete_goodReceipt_note_master()
    {
        echo json_encode($this->Buyback_model->delete_goodReceipt_note_master());
    }

    function dispatch_note_table_approval()
    {
        /*
         * rejected = 1
         * not rejected = 0
         * */
        $approvedYN = trim($this->input->post('approvedYN'));
        $companyID = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $this->datatables->select('dpm.dispatchAutoID AS dispatchAutoID ,dpm.documentSystemCode,dpm.confirmedYN,srp_erp_documentapproved.approvedYN as approvedYN,documentApprovedID,approvalLevelID,DATE_FORMAT(dpm.documentDate,\'' . $convertFormat . '\') AS documentDate,dpm.transactionCurrency AS dpm_transactionCurrency,dpm.transactionCurrencyDecimalPlaces AS dpm_transactionCurrencyDecimalPlaces,det.transactionAmount as total_value,det.transactionAmount as detTransactionAmount,fm.description AS farmerName, batch.batchCode AS batchMasterCode,batch.batchMasterID AS batchid', false);
        $this->datatables->join('(SELECT SUM(totalTransferCost) as transactionAmount,dispatchAutoID FROM srp_erp_buyback_dispatchnotedetails GROUP BY dispatchAutoID) det', '(det.dispatchAutoID = dpm.dispatchAutoID)', 'left');
        $this->datatables->from('srp_erp_buyback_dispatchnote dpm');
        $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = dpm.dispatchAutoID AND srp_erp_documentapproved.approvalLevelID = dpm.currentLevelNo');
        $this->datatables->join('srp_erp_approvalusers', 'srp_erp_approvalusers.levelNo = dpm.currentLevelNo');
        $this->datatables->join('srp_erp_buyback_farmmaster fm', 'fm.farmID = dpm.farmID');
        $this->datatables->join('srp_erp_buyback_batch batch', 'batch.batchMasterID = dpm.batchMasterID');
        $this->datatables->where('srp_erp_documentapproved.documentID', 'BBDPN');
        $this->datatables->where('srp_erp_approvalusers.documentID', 'BBDPN');
        $this->datatables->where('srp_erp_approvalusers.companyID', $companyID);
        $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
        $this->datatables->where('dpm.companyID', $companyID);
        $this->datatables->where('srp_erp_documentapproved.approvedYN', $approvedYN);
        $this->datatables->add_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,dpm_transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('detail', '<b>Farm Name : </b> $1 <b> <br>Dispatched Date : </b> $2  <br>Batch ID : </b> $3 <b>',
            'farmerName,documentDate,batchMasterCode,transactionCurrency,transactionAmount');
        $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
        $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN,"BBDPN",dispatchAutoID)');
        $this->datatables->add_column('level', 'Level   $1', 'approvalLevelID');
        $this->datatables->add_column('edit', '$1', 'buyback_dispatchNote_approval_action(dispatchAutoID,approvalLevelID,approvedYN,documentApprovedID,batchid)');
        echo $this->datatables->generate();
    }

    function save_dispatchNote_approval()
    {
        $system_code = trim($this->input->post('dispatchAutoID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('po_status'));
        if ($status == 1) {
            $approvedYN = checkApproved($system_code, 'BBDPN', $level_id);
            if ($approvedYN) {
                $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                echo json_encode(FALSE);
            } else {
                $this->db->select('dispatchAutoID');
                $this->db->where('dispatchAutoID', trim($system_code));
                $this->db->where('confirmedYN', 2);
                $this->db->from('srp_erp_buyback_dispatchnote');
                $po_approved = $this->db->get()->row_array();
                if (!empty($po_approved)) {
                    $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                    echo json_encode(FALSE);
                } else {
                    $this->form_validation->set_rules('po_status', 'Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('dispatchAutoID', 'Dispatch Note ID', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->Buyback_model->save_dispatchNote_approval());
                    }
                }
            }
        } else if ($status == 2) {
            $this->db->select('dispatchAutoID');
            $this->db->where('dispatchAutoID', trim($system_code));
            $this->db->where('confirmedYN', 2);
            $this->db->from('srp_erp_buyback_dispatchnote');
            $po_approved = $this->db->get()->row_array();
            if (!empty($po_approved)) {
                $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                echo json_encode(FALSE);
            } else {
                $rejectYN = checkApproved($system_code, 'BBDPN', $level_id);
                if (!empty($rejectYN)) {
                    $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                    echo json_encode(FALSE);
                } else {
                    $this->form_validation->set_rules('po_status', 'Dispatch Note Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('dispatchAutoID', 'Dispatch Note ID ', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->Buyback_model->save_dispatchNote_approval());
                    }
                }
            }
        }
    }

    function save_paymentVoucher_approval()
    {
        $system_code = trim($this->input->post('pvMasterAutoID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('po_status'));

        $this->db->select("documentID");
        $this->db->from("srp_erp_buyback_paymentvouchermaster");
        $this->db->where("pvMasterAutoID", $system_code);
        $masterVoucher = $this->db->get()->row_array();

        if ($status == 1) {
            $approvedYN = checkApproved($system_code, $masterVoucher['documentID'], $level_id);
            if ($approvedYN) {
                $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                echo json_encode(FALSE);
            } else {
                $this->db->select('pvMasterAutoID');
                $this->db->where('pvMasterAutoID', trim($system_code));
                $this->db->where('confirmedYN', 2);
                $this->db->from('srp_erp_buyback_paymentvouchermaster');
                $po_approved = $this->db->get()->row_array();
                if (!empty($po_approved)) {
                    $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                    echo json_encode(FALSE);
                } else {
                    $this->form_validation->set_rules('po_status', 'Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('pvMasterAutoID', 'Payment Voucher ID', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->Buyback_model->save_paymentVoucher_approval());
                    }
                }
            }
        } else if ($status == 2) {
            $this->db->select('pvMasterAutoID');
            $this->db->where('pvMasterAutoID', trim($system_code));
            $this->db->where('confirmedYN', 2);
            $this->db->from('srp_erp_buyback_paymentvouchermaster');
            $po_approved = $this->db->get()->row_array();
            if (!empty($po_approved)) {
                $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                echo json_encode(FALSE);
            } else {
                $rejectYN = checkApproved($system_code, 'BBPV', $level_id);
                if (!empty($rejectYN)) {
                    $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                    echo json_encode(FALSE);
                } else {
                    $this->form_validation->set_rules('po_status', 'Payment Voucher Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('pvMasterAutoID', 'Payment Voucher ID ', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->Buyback_model->save_paymentVoucher_approval());
                    }
                }
            }
        }
    }

    function save_batchClosing_approval()
    {
        $system_code = trim($this->input->post('batchMasterID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('po_status'));
        if ($status == 1) {
            $approvedYN = checkApproved($system_code, 'BBBC', $level_id);
            if ($approvedYN) {
                $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                echo json_encode(array('w', 'Document already approved'));
            } else {
                $this->db->select('batchMasterID');
                $this->db->where('batchMasterID', trim($system_code));
                $this->db->where('confirmedYN', 2);
                $this->db->from('srp_erp_buyback_batch');
                $po_approved = $this->db->get()->row_array();
                if (!empty($po_approved)) {
                    $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                    echo json_encode(array('w', 'Document already rejected'));
                } else {
                    $this->form_validation->set_rules('po_status', 'Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('batchMasterID', 'Batch ID', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode('e', validation_errors());
                    } else {
                        echo json_encode($this->Buyback_model->save_batchClosing_approval());
                    }
                }
            }
        } else if ($status == 2) {
            $this->db->select('batchMasterID');
            $this->db->where('batchMasterID', trim($system_code));
            $this->db->where('confirmedYN', 2);
            $this->db->from('srp_erp_buyback_batch');
            $po_approved = $this->db->get()->row_array();
            if (!empty($po_approved)) {
                $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                echo json_encode(array('w', 'Document already rejected'));
            } else {
                $rejectYN = checkApproved($system_code, 'BBBC', $level_id);
                if (!empty($rejectYN)) {
                    $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                    echo json_encode('w', 'Document already approved');
                    echo json_encode(array('w', 'Document already approved'));
                } else {
                    $this->form_validation->set_rules('po_status', 'Batch Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('batchMasterID', 'Batch ID ', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode('e', validation_errors());
                    } else {
                        echo json_encode($this->Buyback_model->save_batchClosing_approval());
                    }
                }
            }
        }
    }

    function fetch_double_entry_buyback_dispatchNote()
    {
        $masterID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('masterID'));
        $code = ($this->uri->segment(4)) ? $this->uri->segment(4) : trim($this->input->post('code'));
        $data['extra'] = $this->Buyback_model->fetch_double_entry_buyback_dispatchNote($masterID, $code);
        $html = $this->load->view('system/buyback/dispatchNote_double_entry_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', 0);
        }
    }

    function fetch_double_entry_buyback_paymentVoucher()
    {
        $masterID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('masterID'));
        $code = ($this->uri->segment(4)) ? $this->uri->segment(4) : trim($this->input->post('code'));
        $data['extra'] = $this->Buyback_model->fetch_double_entry_buyback_paymentVoucher($masterID, $code);
        $html = $this->load->view('system/buyback/paymentVoucher_double_entry_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', 0);
        }
    }

    function fetch_double_entry_buyback_goodReceiptNote()
    {
        $masterID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('masterID'));
        $code = ($this->uri->segment(4)) ? $this->uri->segment(4) : trim($this->input->post('code'));
        $data['extra'] = $this->Buyback_model->fetch_double_entry_buyback_goodReceiptNote($masterID, $code);
        $html = $this->load->view('system/buyback/goodReceiptNote_double_entry_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', 0);
        }
    }

    function fetch_double_entry_buyback_batchClosing()
    {
        $masterID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('masterID'));
        $code = ($this->uri->segment(4)) ? $this->uri->segment(4) : trim($this->input->post('code'));
        $data['extra'] = $this->Buyback_model->fetch_double_entry_buyback_batchClosing($masterID, $code);
        $html = $this->load->view('system/buyback/goodReceiptNote_double_entry_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', 0);
        }
    }


    function goodReceiptNote_table_approval()
    {
        /*
         * rejected = 1
         * not rejected = 0
         * */
        $approvedYN = trim($this->input->post('approvedYN'));
        $companyID = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $this->datatables->select('grn.grnAutoID AS grnAutoID ,grn.documentSystemCode,grn.confirmedYN,srp_erp_documentapproved.approvedYN as approvedYN,documentApprovedID,approvalLevelID,DATE_FORMAT(grn.documentDate,\'' . $convertFormat . '\') AS documentDate,grn.transactionCurrency as grn_transactionCurrency,grn.transactionCurrencyDecimalPlaces as grn_transactionCurrencyDecimalPlaces,det.transactionAmount as total_value,fm.description AS farmerName, batch.batchCode AS batchMasterCode', false);
        $this->datatables->join('(SELECT SUM(totalCost) as transactionAmount, SUM(noOfBirds) as totalBirds,grnAutoID FROM srp_erp_buyback_grndetails GROUP BY grnAutoID) det', '(det.grnAutoID = grn.grnAutoID)', 'left');
        $this->datatables->from('srp_erp_buyback_grn grn');
        $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = grn.grnAutoID AND srp_erp_documentapproved.approvalLevelID = grn.currentLevelNo');
        $this->datatables->join('srp_erp_approvalusers', 'srp_erp_approvalusers.levelNo = grn.currentLevelNo');
        $this->datatables->join('srp_erp_buyback_farmmaster fm', 'fm.farmID = grn.farmID');
        $this->datatables->join('srp_erp_buyback_batch batch', 'batch.batchMasterID = grn.batchMasterID');
        $this->datatables->where('srp_erp_documentapproved.documentID', 'BBGRN');
        $this->datatables->where('srp_erp_approvalusers.documentID', 'BBGRN');
        $this->datatables->where('srp_erp_approvalusers.companyID', $companyID);
        $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
        $this->datatables->where('grn.companyID', $companyID);
        $this->datatables->where('srp_erp_documentapproved.approvedYN', $approvedYN);
        $this->datatables->add_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,grn_transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('detail', '<b>Farm Name : </b> $1 <b> <br>Document Date : </b> $2  <br>Batch ID : </b> $3 <b>',
            'farmerName,documentDate,batchMasterCode,transactionCurrency,transactionAmount');
        $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
        $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN,"BBGRN",grnAutoID)');
        $this->datatables->add_column('level', 'Level   $1', 'approvalLevelID');
        $this->datatables->add_column('edit', '$1', 'buyback_goodReceiptNote_approval_action(grnAutoID,approvalLevelID,approvedYN,documentApprovedID)');
        echo $this->datatables->generate();
    }

    function paymentVoucher_table_approval()
    {
        $approvedYN = trim($this->input->post('approvedYN'));
        $companyID = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $this->datatables->select('pvm.pvMasterAutoID AS pvMasterAutoID,pvm.documentSystemCode,pvm.confirmedYN,srp_erp_documentapproved.approvedYN as approvedYN,documentApprovedID,approvalLevelID,DATE_FORMAT(pvm.documentDate,\'' . $convertFormat . '\') AS documentDate,transactionCurrency,transactionCurrencyDecimalPlaces,fm.description AS farmerName', false);
        $this->datatables->from('srp_erp_buyback_paymentvouchermaster pvm');
        $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = pvm.pvMasterAutoID AND srp_erp_documentapproved.approvalLevelID = pvm.currentLevelNo AND pvm.documentID = srp_erp_documentapproved.documentID');
        $this->datatables->join('srp_erp_approvalusers', 'srp_erp_approvalusers.levelNo = pvm.currentLevelNo AND srp_erp_approvalusers.documentID = pvm.documentID');
        $this->datatables->join('srp_erp_buyback_farmmaster fm', 'fm.farmID = pvm.farmID');
        //$this->datatables->where('srp_erp_documentapproved.documentID', 'BBPV');
        //$this->datatables->where('srp_erp_approvalusers.documentID', 'BBPV');
        $this->datatables->where('srp_erp_approvalusers.companyID', $companyID);
        $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
        $this->datatables->where('pvm.companyID', $companyID);
        $this->datatables->where('srp_erp_documentapproved.approvedYN', $approvedYN);
        //$this->datatables->add_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('detail', '<b>Farm Name : </b> $1 <b> <br>Document Date : </b> $2 ',
            'farmerName,documentDate,grn_transactionCurrency,transactionAmount');
        $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
        $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN,"BBPV",pvMasterAutoID)');
        $this->datatables->add_column('level', 'Level   $1', 'approvalLevelID');
        $this->datatables->add_column('edit', '$1', 'buyback_paymentVoucher_approval_action(pvMasterAutoID,approvalLevelID,approvedYN,documentApprovedID)');
        echo $this->datatables->generate();
    }

    function fetch_goodReciptNote_liveBirds_item()
    {
        echo json_encode($this->Buyback_model->fetch_goodReciptNote_liveBirds_item());
    }

    function fetch_goodReciptNote_batch_chicks()
    {
        echo json_encode($this->Buyback_model->fetch_goodReciptNote_batch_chicks());
    }

    function fetch_goodReciptNote_batch_chicks_edit()
    {
        echo json_encode($this->Buyback_model->fetch_goodReciptNote_batch_chicks_edit());
    }

    function fetch_goodReciptNote_batch_mortality()
    {

        echo json_encode($this->Buyback_model->fetch_goodReciptNote_batch_mortality());
    }

    function save_goodReceiptNote_approval()
    {
        $system_code = trim($this->input->post('grnAutoID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('po_status'));
        if ($status == 1) {
            $approvedYN = checkApproved($system_code, 'BBGRN', $level_id);
            if ($approvedYN) {
                $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                echo json_encode(FALSE);
            } else {
                $this->db->select('grnAutoID');
                $this->db->where('grnAutoID', trim($system_code));
                $this->db->where('confirmedYN', 2);
                $this->db->from('srp_erp_buyback_grn');
                $po_approved = $this->db->get()->row_array();
                if (!empty($po_approved)) {
                    $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                    echo json_encode(FALSE);
                } else {
                    $this->form_validation->set_rules('po_status', 'Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('grnAutoID', 'Goods Received Note', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->Buyback_model->save_goodReceiptNote_approval());
                    }
                }
            }
        } else if ($status == 2) {
            $this->db->select('grnAutoID');
            $this->db->where('grnAutoID', trim($system_code));
            $this->db->where('confirmedYN', 2);
            $this->db->from('srp_erp_buyback_grn');
            $po_approved = $this->db->get()->row_array();
            if (!empty($po_approved)) {
                $this->session->set_flashdata($msgtype = 'w', 'Document already rejected');
                echo json_encode(FALSE);
            } else {
                $rejectYN = checkApproved($system_code, 'BBGRN', $level_id);
                if (!empty($rejectYN)) {
                    $this->session->set_flashdata($msgtype = 'w', 'Document already approved');
                    echo json_encode(FALSE);
                } else {
                    $this->form_validation->set_rules('po_status', 'Payment Voucher Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('grnAutoID', 'Goods Received Note ID ', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        $this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(FALSE);
                    } else {
                        echo json_encode($this->Buyback_model->save_goodReceiptNote_approval());
                    }
                }
            }
        }
    }

    function fetch_batchClosing_table_data()
    {

        $approvedYN = trim($this->input->post('approvedYN'));
        $companyID = $this->common_data['company_data']['company_id'];
        $convertFormat = convert_date_format_sql();
        $this->datatables->select('batch.batchMasterID AS batchMasterID,batch.batchCode,batch.confirmedYN,srp_erp_documentapproved.approvedYN as approvedYN,documentApprovedID,approvalLevelID,DATE_FORMAT(batch.batchStartDate,\'' . $convertFormat . '\') AS batchStartDate,fm.description AS farmerName', false);
        $this->datatables->from('srp_erp_buyback_batch batch');
        $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = batch.batchMasterID AND srp_erp_documentapproved.approvalLevelID = batch.currentLevelNo');
        $this->datatables->join('srp_erp_approvalusers', 'srp_erp_approvalusers.levelNo = batch.currentLevelNo');
        $this->datatables->join('srp_erp_buyback_farmmaster fm', 'fm.farmID = batch.farmID');
        $this->datatables->where('srp_erp_documentapproved.documentID', 'BBBC');
        $this->datatables->where('srp_erp_approvalusers.documentID', 'BBBC');
        $this->datatables->where('srp_erp_approvalusers.companyID', $companyID);
        $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
        $this->datatables->where('batch.companyID', $companyID);
        $this->datatables->where('srp_erp_documentapproved.approvedYN', $approvedYN);
        //$this->datatables->add_column('total_value', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_value,transactionCurrencyDecimalPlaces),transactionCurrency');
        $this->datatables->add_column('detail', '<b>Farm Name : </b> $1 <b> <br>Start Date : </b> $2 ',
            'farmerName,batchStartDate');
        $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
        $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN,"BBBC",batchMasterID)');
        $this->datatables->add_column('level', 'Level   $1', 'approvalLevelID');
        $this->datatables->add_column('edit', '$1', 'buyback_batchClosing_approval_action(batchMasterID,approvalLevelID,approvedYN,documentApprovedID)');
        echo $this->datatables->generate();
    }

    function save_fieldChart_detail_multiple()
    {

        $feedPer_days = $this->input->post('feedPer_day');
        foreach ($feedPer_days as $key => $feedPer_day) {
            $this->form_validation->set_rules("age_day[{$key}]", 'Age Day', 'required|trim');
            $this->form_validation->set_rules("feedPer_day[{$key}]", 'Feed Per Day', 'required|trim');
            $this->form_validation->set_rules("uomID[{$key}]", 'UOM', 'required|trim');
            $this->form_validation->set_rules("bodyWeight_min[{$key}]", 'Body Weight Min', 'required|trim');
            $this->form_validation->set_rules("bodyWeight_max[{$key}]", 'Body Weight Max', 'required|trim');
            $this->form_validation->set_rules("fcr_min[{$key}]", 'FCR Min', 'trim|required');
            $this->form_validation->set_rules("fcr_max[{$key}]", 'FCR Max', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $trimmed_array = array_map('trim', $msg);
            $uniqMesg = array_unique($trimmed_array);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));
            echo json_encode(array('e', join('', $validateMsg)));
        } else {
            echo json_encode($this->Buyback_model->save_fieldChart_detail_multiple());
        }
    }

    function update_fieldChart_detail()
    {
        $this->form_validation->set_rules('age_day', 'Age Day', 'trim|required');
        $this->form_validation->set_rules('feedPer_day', 'Feed Per Day', 'trim|required');
        $this->form_validation->set_rules('uomID', 'UOM', 'trim|required');
        $this->form_validation->set_rules('bodyWeight_min', 'Body Weight Min', 'trim|required');
        $this->form_validation->set_rules('bodyWeight_max', 'Body Weight Max', 'trim|required');
        $this->form_validation->set_rules('fcr_min', 'FCR Min', 'trim|required');
        $this->form_validation->set_rules('fcr_max', 'FCR Max', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->update_fieldChart_detail());
        }
    }

    function save_fieldChart_header()
    {
        $this->form_validation->set_rules('startDay', 'Farm Name', 'trim|required');
        //$this->form_validation->set_rules('endDay', 'End Day', 'trim|required');
        $this->form_validation->set_rules('uomID', 'UOM', 'trim|required');
        $this->form_validation->set_rules('feedTypeID', 'Feed Type', 'trim|required');
        $this->form_validation->set_rules('feedAmount', 'Feed', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->save_fieldChart_header());
        }
    }

    function load_feedChart_header_view()
    {
        $companyID = $this->common_data['company_data']['company_id'];

        $data['header'] = $this->db->query("SELECT feedScheduleID,feedAmount,ft.description as feedType,CONCAT(startDay, ' - ', endDay) as changedDate FROM srp_erp_buyback_feedschedulemaster fsm LEFT JOIN srp_erp_buyback_feedtypes ft ON ft.buybackFeedtypeID = fsm.feedTypeID WHERE fsm.companyID = " . $companyID . " ORDER BY feedScheduleID ASC")->result_array();

        $this->load->view('system/buyback/ajax/load_feedChart_header_tableView', $data);
    }

    function load_feedChart_detail_view()
    {
        $companyID = $this->common_data['company_data']['company_id'];

        $data['detail'] = $this->db->query("SELECT * FROM srp_erp_buyback_feedscheduledetail WHERE companyID = " . $companyID . " ORDER BY feedscheduledetailID ASC")->result_array();

        $this->load->view('system/buyback/ajax/load_feedChart_detail_tableView', $data);
    }

    function delete_feedChart_detail()
    {
        echo json_encode($this->Buyback_model->delete_feedChart_detail());
    }

    function delete_feedChart_header()
    {
        echo json_encode($this->Buyback_model->delete_feedChart_header());
    }

    function edit_feedChart_header()
    {
        echo json_encode($this->Buyback_model->edit_feedChart_header());
    }

    function edit_feedChart_detail()
    {
        echo json_encode($this->Buyback_model->edit_feedChart_detail());
    }

    function load_feedSchedule_report()
    {
        $this->form_validation->set_rules('batchMasterID', 'Batch ID', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error_message = validation_errors();
            echo warning_message($error_message);
        } else {
            $data = array();
            $batchMasterID = trim($this->input->post('batchMasterID'));
            $convertFormat = convert_date_format_sql();
            $companyID = current_companyID();

            $data["batchDetail"] = $this->Buyback_model->getBatch_detail($this->input->post('batchMasterID'));

            $this->db->select('feedScheduleID,feedAmount,ft.description as feedName,CONCAT(startDay, \' - \', endDay) as changedDate,buybackFeedtypeID');
            $this->db->from("srp_erp_buyback_feedschedulemaster fsm");
            $this->db->join('srp_erp_buyback_feedtypes ft', 'fsm.feedTypeID = ft.buybackFeedtypeID', 'LEFT');
            $this->db->where("fsm.companyID", $companyID);
            $this->db->order_by("feedScheduleID ASC");
            $data["feedHeader"] = $this->db->get()->result_array();

            $this->db->select('*, DATE_FORMAT(dpm.documentDate,\'' . $convertFormat . '\') AS documentDate');
            $this->db->from("srp_erp_buyback_dispatchnote dpm");
            $this->db->join('srp_erp_buyback_dispatchnotedetails dpd', 'dpd.dispatchAutoID = dpm.dispatchAutoID AND buybackItemType = 2');
            $this->db->where("batchMasterID", $batchMasterID);
            $this->db->where("dpm.companyID", $companyID);
            $this->db->order_by("dpm.documentDate ASC");
            $data["dispatch"] = $this->db->get()->result_array();
            //echo $this->db->last_query();

            $this->db->select('DATE_FORMAT(dpm.documentDate,\'' . $convertFormat . '\') AS documentDate');
            $this->db->from("srp_erp_buyback_dispatchnote dpm");
            $this->db->join('srp_erp_buyback_dispatchnotedetails dpd', 'dpd.dispatchAutoID = dpm.dispatchAutoID AND buybackItemType = 2');
            $this->db->where("batchMasterID", $batchMasterID);
            $this->db->where("dpm.companyID", $companyID);
            $this->db->order_by("dpm.documentDate ASC");
            $data["dispatchFirstDate"] = $this->db->get()->row_array();

            $this->db->select("sum(qty) AS chicksTotal");
            $this->db->from("srp_erp_buyback_dispatchnote dpm");
            $this->db->join('srp_erp_buyback_dispatchnotedetails dpd', 'dpd.dispatchAutoID = dpm.dispatchAutoID AND buybackItemType = 1', 'LEFT');
            $this->db->where("batchMasterID", $batchMasterID);
            $this->db->where("dpm.companyID", $companyID);
            $data["chicks"] = $this->db->get()->row_array();

            $this->db->select("sum(qty) AS booster");
            $this->db->from("srp_erp_buyback_dispatchnote dpm");
            $this->db->join('srp_erp_buyback_dispatchnotedetails dpd', 'dpd.dispatchAutoID = dpm.dispatchAutoID AND buybackItemType = 2', 'LEFT');
            $this->db->where("batchMasterID", $batchMasterID);
            $this->db->where("dpm.companyID", $companyID);
            $data["feedBooster"] = $this->db->get()->row_array();


            $this->db->select("sum(qty) AS starter");
            $this->db->from("srp_erp_buyback_dispatchnote dpm");
            $this->db->join('srp_erp_buyback_dispatchnotedetails dpd', 'dpd.dispatchAutoID = dpm.dispatchAutoID AND buybackItemType = 2 AND feedType = 2', 'LEFT');
            $this->db->where("batchMasterID", $batchMasterID);
            $this->db->where("dpm.companyID", $companyID);
            $data["feedStarter"] = $this->db->get()->row_array();

            $data["feedTypes "] = $this->db->query("SELECT feedScheduleID,feedAmount,ft.description as feedName,CONCAT(startDay, ' - ', endDay) as changedDate,buybackFeedtypeID FROM srp_erp_buyback_feedschedulemaster fsm LEFT JOIN srp_erp_buyback_feedtypes ft ON fsm.feedTypeID = ft.buybackFeedtypeID WHERE fsm.companyID = {$companyID} ORDER BY feedScheduleID ASC")->result_array();

            $this->db->select('pvd.GLCode,pvd.GLDescription,DATE_FORMAT(pvm.documentDate,\'' . $convertFormat . '\') AS documentDate,pvd.transactionAmount');
            $this->db->from("srp_erp_buyback_paymentvoucherdetail pvd");
            $this->db->join('srp_erp_buyback_paymentvouchermaster pvm', 'pvd.pvMasterAutoID = pvm.pvMasterAutoID', 'LEFT');
            $this->db->where("pvd.BatchID", $batchMasterID);
            $this->db->where("pvd.companyID", $companyID);
            $this->db->where("pvd.type", 'Expense');
            $this->db->where("pvm.approvedYN", 1);
            $this->db->order_by("pvDetailID DESC");
            $data["expense"] = $this->db->get()->result_array();

            $this->db->select('sum(noOfBirds) AS totalBirds');
            $this->db->from("srp_erp_buyback_mortalitydetails md");
            $this->db->join('srp_erp_buyback_mortalitymaster mm', 'md.mortalityAutoID = mm.mortalityAutoID', 'LEFT');
            $this->db->where("mm.batchMasterID", $batchMasterID);
            $this->db->where("md.companyID", $companyID);
            $this->db->where("mm.confirmedYN", 1);
            $data["mortality"] = $this->db->get()->row_array();

            $this->db->select('*,DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate');
            $this->db->from("srp_erp_buyback_itemledger");
            $this->db->where("batchID", $batchMasterID);
            $this->db->where("companyID", $companyID);
            $this->db->where("documentCode", 'BBGRN');
            $this->db->order_by("itemLedgerAutoID ASC");
            $data["buyback"] = $this->db->get()->result_array();

            $this->db->select("sum(transactionQTY) AS feedTotal");
            $this->db->from("srp_erp_buyback_itemledger");
            $this->db->where("batchID", $batchMasterID);
            $this->db->where("companyID", $companyID);
            $this->db->where("documentCode", 'BBDPN');
            $this->db->where("buybackItemType", 2);
            $data["feed"] = $this->db->get()->row_array();

            $this->db->select('DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS farmerVisitDate');
            $this->db->from("srp_erp_buyback_farmervisitreport");
            $this->db->where("batchMasterID", $batchMasterID);
            $this->db->where("companyID", $companyID);
            $this->db->where("confirmedYN", 1);
            $this->db->order_by("farmerVisitID ASC");
            $data["farmVisit"] = $this->db->get()->result_array();

            $data["type"] = "html";
            $this->load->view('system/buyback/report/feedSchedule_report_view', $data);
        }
    }

    function fetch_buyback_item_masterEdit()
    {
        echo json_encode($this->Buyback_model->fetch_buyback_item_masterEdit());
    }

    function load_feedType_Master_view()
    {
        $companyID = $this->common_data['company_data']['company_id'];

        $data['feedTypes'] = $this->db->query("SELECT * FROM srp_erp_buyback_feedtypes WHERE companyID = {$companyID} ORDER BY buybackFeedtypeID ASC ")->result_array();

        $this->load->view('system/buyback/ajax/load_feedType_master', $data);
    }

    function save_feedType_header()
    {
        $this->form_validation->set_rules('description', 'Description', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->save_feedType_header());
        }
    }

    function load_feedType_header()
    {
        echo json_encode($this->Buyback_model->load_feedType_header());
    }

    function delete_feedType()
    {
        echo json_encode($this->Buyback_model->delete_feedType());
    }

    function load_feed_schedule_report()
    {
        $date_format_policy = date_format_policy();
        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $text = trim($this->input->post('searchTask'));
        $farmer = trim($this->input->post('farmer'));
        $location = trim($this->input->post('locationID'));
        $fieldofficer = trim($this->input->post('fieldofficer'));
        $subarea = trim($this->input->post('subLocationID'));
        $dateto = $this->input->post('feedscheduleDateto');
        $datefrom = $this->input->post('feedscheduleDatefrom');
        $feedstatus = $this->input->post('feed_status');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $datetoconvert = input_format_date($dateto, $date_format_policy);

        $search_string = '';
        if (isset($text) && !empty($text)) {
            $search_string = " AND (fm.description Like '%" . $text . "%' OR batchCode Like '%" . $text . "%')";
        }
        $location_filter = '';
        if (isset($location) && !empty($location)) {
            $location_filter = " AND fm.locationID = {$location}";
        }

        $status_feed = '';

        if ($feedstatus == '0') {
            $status_feed = "batch.isclosed = $feedstatus";
        } else if ($feedstatus == 1) {
            $status_feed = "batch.isclosed = $feedstatus";
        } else {
            $status_feed = " batch.isclosed = 0 OR batch.isclosed = 1";
        }

        $f_farmer = '';
        if (isset($farmer) && !empty($farmer)) {
            $f_farmer = " AND fm.farmID = {$farmer}";
        }
        $fieldofficer_filter = '';
        if (isset($fieldofficer) && !empty($fieldofficer)) {
            $fieldofficer_filter = " AND fi.empID = {$fieldofficer}";
        }
        $subarealocation_filter = '';
        if (isset($subarea) && !empty($subarea)) {
            $subarealocation_filter = " AND fm.subLocationID = {$subarea}";
        }

        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( batchStartDate >= '" . $datefromconvert . " 00:00:00' AND batchClosingDate <= '" . $datetoconvert . " 23:59:00')";
        }


        $where_admin = "Where $status_feed AND batch.companyID = " . $companyID . $search_string . $f_farmer . $location_filter . $fieldofficer_filter . $subarealocation_filter . $date;

        $data['batch'] = $this->db->query("SELECT batchMasterID,batchCode,DATE_FORMAT(batchStartDate,' . $convertFormat . ') AS batchStartDate,DATE_FORMAT(batchClosingDate,' . $convertFormat . ') AS batchClosingDate,isclosed,fm.description as farmerName,isclosed,batch.confirmedYN,batch.approvedYN,fm.locationID,fm.subLocationID FROM srp_erp_buyback_batch batch LEFT JOIN srp_erp_buyback_farmmaster fm ON fm.farmID = batch.farmID LEFT JOIN srp_erp_buyback_farmfieldofficers fi ON fi.farmID = fm.farmID  $where_admin GROUP BY batchMasterID DESC ")->result_array();

        //echo $this->db->last_query();

        $this->load->view('system/buyback/ajax/load_feed_schedule_report', $data);
    }

    function save_receiptVoucher_single_income()
    {
        $amount = trim($this->input->post('amount'));
        $wages_amount = trim($this->input->post('balance_amount'));
        $this->form_validation->set_rules('pvMasterAutoID', 'Master ID', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($amount > $wages_amount) {
            echo json_encode(array('e', 'Amount cannot be greater than Pending Deposit Amount!.'));
            exit();
        }
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->save_receiptVoucher_single_income());
        }
    }

    // added by aflal
    function load_farm_visit_view()
    {

        $date_format_policy = date_format_policy();
        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $text = trim($this->input->post('searchfarm'));

        $dateto = $this->input->post('fieldVisitDateto');
        $datefrom = $this->input->post('fieldVisitDatefrom');
        $fieldOfficer = $this->input->post('FieldVisitID');
        $farm = $this->input->post('farmername');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $datetoconvert = input_format_date($dateto, $date_format_policy);

        $date = "";
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( fvr.documentDate >= '" . $datefromconvert . " 00:00:00' AND fvr.documentDate <= '" . $datetoconvert . " 23:59:00')";
        }

        $fieldOfficer_filter = '';
        if (isset($fieldOfficer) && !empty($fieldOfficer)) {
            $fieldOfficer_filter = " AND ffo.empID = {$fieldOfficer}";
        }

        $farm_filter = '';
        if (isset($farm) && !empty($farm)) {
            $farm_filter = " AND fvr.farmID = {$farm}";
        }

        $search_string = '';
        if (isset($text) && !empty($text)) {
            $search_string = " AND (fm.description Like '%" . $text . "%' OR batchCode Like '%" . $text . "%' OR fvr.documentSystemCode Like '%" . $text . "%')";
        }

        $where_admin = "Where batch.companyID = " . $companyID . $search_string . $date . $fieldOfficer_filter . $farm_filter;

        $data['batch'] = $this->db->query("SELECT fvr.farmerVisitID,batch.isclosed, ffo.fieldOfficerName ,batch.batchCode,DATE_FORMAT(documentDate,'$convertFormat') AS documentDate,DATE_FORMAT(hatchDate,' . $convertFormat . ') AS hatchDate,fm.description as farmerName,fvr.documentSystemCode as fvrSystemCode,fvr.confirmedYN as fvrConfirmedYN,fvr.createdUserID as fvrCreatedUserID FROM srp_erp_buyback_farmervisitreport fvr INNER JOIN srp_erp_buyback_farmmaster fm ON fm.farmID = fvr.farmID INNER JOIN srp_erp_buyback_farmfieldofficers ffo ON ffo.farmID = fvr.farmID INNER JOIN srp_erp_buyback_batch batch ON fvr.batchMasterID = batch.batchMasterID $where_admin  AND ffo.isActive = 1 ORDER BY farmerVisitID DESC ")->result_array();

        $this->load->view('system/buyback/ajax/load_farm_field_visit', $data);
    }

    function save_farmVisit_report_header()
    {
        $this->form_validation->set_rules('farmID', 'Farm Name', 'trim|required');
        $this->form_validation->set_rules('batchMasterID', 'Batch', 'trim|required');
        $this->form_validation->set_rules('documentDate', 'Document Date', 'trim|required');
        $this->form_validation->set_rules('farmType', 'Farm Type', 'trim|required');
        $this->form_validation->set_rules('breed', 'Breed', 'trim|required');
        $this->form_validation->set_rules('feed', 'Feed', 'trim|required');
        $this->form_validation->set_rules('noofvisit', 'Visit Number', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->save_farmVisit_report_header());
        }
    }

    function load_farmVisitReport_detail_view()
    {
        $companyID = $this->common_data['company_data']['company_id'];

        $farmerVisitID = trim($this->input->post('farmerVisitID'));

        $this->db->select('*');
        $this->db->from('srp_erp_buyback_farmervisitreportdetails fvrd');
        $this->db->where('fvrd.companyID', $companyID);
        $this->db->where('fvrd.farmerVisitMasterID', $farmerVisitID);
        $this->db->order_by('farmerVisitDetailID', 'desc');
        $data['header'] = $this->db->get()->result_array();

        $this->load->view('system/buyback/ajax/load_farmVisitReport_detail_view', $data);
    }

    function load_farmVisitReport_header()
    {
        echo json_encode($this->Buyback_model->load_farmVisitReport_header());
    }

    function load_farmVisitReport_confirmation()
    {
        $data['type'] = $this->input->post('html');
        $farmerVisitID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('farmerVisitID'));
        $data['extra'] = $this->Buyback_model->load_farmVisitReport_confirmation($farmerVisitID);
        $html = $this->load->view('system/buyback/farmVisit_report_print', $data, TRUE);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', 1);
        }
    }

    function save_farmVisitReport_detail()
    {
        $ages = $this->input->post('age');
        foreach ($ages as $key => $age) {
            $this->form_validation->set_rules("age[{$key}]", 'Age', 'trim|required');
            $this->form_validation->set_rules("numberOfBirds[{$key}]", 'No of Birds', 'trim|required');
            $this->form_validation->set_rules("remarks[{$key}]", 'Remarks', 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            $msg = explode('</p>', validation_errors());
            $uniqMesg = array_unique($msg);
            $validateMsg = array_map(function ($uniqMesg) {
                return $a = $uniqMesg . '</p>';
            }, array_filter($uniqMesg));
            echo json_encode(array('e', join('', $validateMsg)));
        } else {
            echo json_encode($this->Buyback_model->save_farmVisitReport_detail());
        }
    }

    function delete_farmVisitReport_detail()
    {
        echo json_encode($this->Buyback_model->delete_farmVisitReport_detail());
    }

    function delete_farmVisitReport_master()
    {
        echo json_encode($this->Buyback_model->delete_farmVisitReport_master());
    }

    function farmVisitReport_confirmation()
    {
        echo json_encode($this->Buyback_model->farmVisitReport_confirmation());
    }

    function referback_farmVisit_Report()
    {
        $farmerVisitID = trim($this->input->post('farmerVisitID'));
        $dataUpdate = array(
            'confirmedYN' => 0,
            'confirmedByEmpID' => '',
            'confirmedByName' => '',
            'confirmedDate' => '',
        );

        $this->db->where('farmerVisitID', $farmerVisitID);
        $this->db->update('srp_erp_buyback_farmervisitreport', $dataUpdate);

        echo json_encode(array('s', ' Referred Back Successfully.'));
    }

    function load_buyback_first_dispatchNote_for_fvr()
    {
        $batchMasterID = trim($this->input->post('batchMasterID'));
        echo json_encode($this->Buyback_model->fetch_buyback_first_dispatchDate($batchMasterID));
    }

    function save_paymentVoucher_loan()
    {
        $loanType = trim($this->input->post('loanType'));
        $amount = trim($this->input->post('amount'));
        $balance_amount = trim($this->input->post('balance_amount'));
        $this->form_validation->set_rules('loanType', 'Type', 'trim|required');
        if ($loanType == 1) {
            $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
        } else if ($loanType == 2) {
            $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
            $this->form_validation->set_rules('balance_amount', 'Balance', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            if ($amount > $balance_amount) {
                echo json_encode(array('e', 'Amount cannot be greater than Balance Amount!.'));
                exit();
            }
        }
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->save_paymentVoucher_loan());
        }
    }

    function load_buyback_farmer_pendingLoanAmount_pv()
    {
        $farmID = trim($this->input->post('farmID'));
        echo json_encode($this->Buyback_model->fetch_buyback_farmer_pendingLoanAmount($farmID));
    }

    function load_buyback_farmer_paidDepositAmount_rv()
    {
        $farmID = trim($this->input->post('farmID'));
        echo json_encode($this->Buyback_model->fetch_buyback_farmer_paidDepositAmount($farmID));
    }

    function fetch_buyback_batch_outStandingPayableAmount_voucher()
    {
        $batchMasterID = trim($this->input->post('batchMasterID'));
        echo json_encode($this->Buyback_model->fetch_buyback_batch_outStandingPayableAmount($batchMasterID));
    }

    function load_farm_all_fieldOfficer()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $farmID = trim($this->input->post('farmID'));
        $convertFormat = convert_date_format_sql();
        $this->db->select('fieldOfficerID,ed.ECode,ed.Ename2,ffo.isActive as ffoActive');
        $this->db->from('srp_erp_buyback_farmfieldofficers ffo');
        $this->db->join('srp_employeesdetails ed', 'ed.EIdNo = ffo.empID', 'LEFT');
        $this->db->where('ffo.companyID', $companyID);
        $this->db->where('ffo.farmID', $farmID);
        $data['fieldOfficer'] = $this->db->get()->result_array();
        $this->load->view('system/buyback/ajax/load_farm_all_fieldOfficer', $data);
    }

    function load_farm_fieldOfficer_header()
    {
        echo json_encode($this->Buyback_model->load_farm_fieldOfficer_header());
    }

    function delete_farm_Dealers()
    {
        echo json_encode($this->Buyback_model->delete_farm_Dealers());
    }

    function delete_farm_fieldOfficer()
    {
        echo json_encode($this->Buyback_model->delete_farm_fieldOfficer());
    }

    function farm_image_upload()
    {
        $this->form_validation->set_rules('farmID', 'Farm ID is missing', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->farm_image_upload());
        }
    }


    function load_batch_settlement_detail_view()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $batchMasterID = trim($this->input->post('batchMasterID'));
        $farmID = trim($this->input->post('farmID'));
        $pvMasterAutoID = trim($this->input->post('pvMasterAutoID'));

        $data['batchAmount'] = $this->Buyback_model->fetch_buyback_batch_outStandingPayableAmount($batchMasterID);

        $data['farmerDepositAmount'] = $this->Buyback_model->fetch_farmer_depositAmount($farmID);

        $data['farmerDepositPaidAmount'] = $this->db->query("SELECT COALESCE(SUM(pvd.depositAmount),0) as depositAmount FROM
	srp_erp_buyback_paymentvouchermaster pvm LEFT JOIN ( SELECT pvMasterAutoID, type, SUM(transactionAmount) AS depositAmount FROM srp_erp_buyback_paymentvoucherdetail WHERE type = 'Deposit' GROUP BY pvMasterAutoID) pvd ON pvm.pvMasterAutoID = pvd.pvMasterAutoID WHERE farmID = {$farmID} AND (PVtype = 3 OR PVtype = 2)  AND approvedYN = 1")->row_array();

        $data['lostAmount'] = $this->db->query("SELECT
	batchCode,
	batchMasterID,
	batchPayableAmount,
	IFNULL(pvd.batchSettledAmt,0) as batchSettledAmt,
	IFNULL(pvdtls.transamt, 0) AS transamt,
	(batchPayableAmount+IFNULL(batchSettledAmt,0)) as batchBalanceAmt
FROM
	srp_erp_buyback_batch
LEFT JOIN (
	SELECT
BatchID,
		IFNULL(SUM(transactionAmount),0) AS batchSettledAmt
	FROM
		srp_erp_buyback_paymentvoucherdetail
		WHERE
pvMasterAutoID!=$pvMasterAutoID
	GROUP BY
		BatchID
) pvd ON srp_erp_buyback_batch.batchMasterID = pvd.BatchID
LEFT JOIN (
	SELECT
		lossedbatchID,
    batchID,
		IFNULL(transactionAmount, 0) AS transamt
	FROM
		srp_erp_buyback_paymentvoucherdetail
	WHERE
		pvMasterAutoID = $pvMasterAutoID AND
		type = 'Loss'
) pvdtls ON srp_erp_buyback_batch.batchMasterID = pvdtls.lossedbatchID
WHERE
	farmID=$farmID
AND isclosed=1
AND batchPayableAmount<0
HAVING batchBalanceAmt!=0
")->result_array();

        $data['loanPayableAmount'] = $this->db->query("SELECT COALESCE(SUM(pvd.loanAmount),0) as loanAmount FROM
	srp_erp_buyback_paymentvouchermaster pvm LEFT JOIN ( SELECT pvMasterAutoID, type, SUM(transactionAmount) AS loanAmount FROM srp_erp_buyback_paymentvoucherdetail WHERE type = 'Loan' GROUP BY pvMasterAutoID) pvd ON pvm.pvMasterAutoID = pvd.pvMasterAutoID WHERE farmID = {$farmID} AND PVtype = 1 AND approvedYN = 1")->row_array();

        $data['loanPaidAmount'] = $this->db->query("SELECT COALESCE(SUM(pvd.loanAmount),0) as loanPaidAmount FROM
	srp_erp_buyback_paymentvouchermaster pvm LEFT JOIN ( SELECT pvMasterAutoID, type, SUM(transactionAmount) AS loanAmount FROM srp_erp_buyback_paymentvoucherdetail WHERE type = 'Loan' GROUP BY pvMasterAutoID) pvd ON pvm.pvMasterAutoID = pvd.pvMasterAutoID WHERE farmID = {$farmID} AND PVtype = 3")->row_array();

        $data['advancePayableAmount'] = $this->db->query("SELECT COALESCE(SUM(pvd.advanceAmount),0) as advanceAmount FROM
	srp_erp_buyback_paymentvouchermaster pvm LEFT JOIN ( SELECT pvMasterAutoID, type, SUM(transactionAmount) AS advanceAmount FROM srp_erp_buyback_paymentvoucherdetail WHERE type = 'Advance' GROUP BY pvMasterAutoID) pvd ON pvm.pvMasterAutoID = pvd.pvMasterAutoID WHERE farmID = {$farmID} AND PVtype = 1 AND approvedYN = 1")->row_array();

        $data['advancePaidAmount'] = $this->db->query("SELECT COALESCE(SUM(pvd.advanceAmount),0) as advanceAmount FROM
	srp_erp_buyback_paymentvouchermaster pvm LEFT JOIN ( SELECT pvMasterAutoID, type, SUM(transactionAmount) AS advanceAmount FROM srp_erp_buyback_paymentvoucherdetail WHERE type = 'Advance' GROUP BY pvMasterAutoID) pvd ON pvm.pvMasterAutoID = pvd.pvMasterAutoID WHERE farmID = {$farmID} AND PVtype = 3")->row_array();

        $data['wagesPaidAmount'] = $this->db->query("SELECT COALESCE (SUM(pvd.wagesAmount), 0) AS wagesAmount FROM
	srp_erp_buyback_paymentvouchermaster pvm LEFT JOIN (SELECT pvMasterAutoID, type, SUM(transactionAmount) AS wagesAmount FROM
		srp_erp_buyback_paymentvoucherdetail GROUP BY BatchID) pvd ON pvm.pvMasterAutoID = pvd.pvMasterAutoID WHERE farmID = {$farmID} AND PVtype = 3 AND approvedYN = 1")->row_array();

        $this->load->view('system/buyback/ajax/load_settlementVoucher_detail_view', $data);
    }

    function save_paymentVoucher_batch_settlement()
    {
        $this->form_validation->set_rules('pvMasterAutoID', 'Farm ID is missing', 'trim|required');
        $this->form_validation->set_rules('BatchID', 'Batch ID is missing', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->save_paymentVoucher_batch_settlement());
        }

    }

    function farmer_pending_loanAmount()
    {
        echo json_encode($this->Buyback_model->farmer_pending_loanAmount());
    }

    function fetch_buyback_subArea()
    {
        echo json_encode($this->Buyback_model->fetch_buyback_subArea());
    }

    function delete_buyback_item_master()
    {
        echo json_encode($this->Buyback_model->delete_buyback_item_master());
    }

    function get_farm_ledger_filter() /*Customer Ledger,Customer Statement,Customer Aging Summary,Customer Aging Detail*/
    {
        $data = array();
        $data["columns"] = $this->Buyback_model->getColumnsByReport('BB_FL');
        $data["formName"] = $this->input->post('formName');
        $data["reportID"] = $this->input->post('reportID');
        $data["type"] = $this->input->post('type');
        $this->load->view('system/buyback/report/farm_ledger_filter', $data);
    }

    function get_farm_ledger_report()
    {
        $fieldNameChk = array("transactionAmount");
        $this->form_validation->set_rules('from', 'As of', 'trim|required');
        $this->form_validation->set_rules('farmerTo[]', 'Farmer', 'trim|required');
        $this->form_validation->set_rules('fieldNameChk[]', 'fieldNameChk', 'callback_check_valid_extra_column');
        $this->form_validation->set_rules('documentCode[]', 'documentCode', 'callback_check_valid_documentCode_column');
        if ($this->form_validation->run() == FALSE) {
            $error_message = validation_errors();
            echo warning_message($error_message);
        } else {
            $data = array();
            $data["output"] = $this->Buyback_model->get_farm_ledger_report();
            $data["caption"] = $this->input->post('captionChk');
            $data["fieldName"] = $this->input->post('fieldNameChk');
            $data["from"] = $this->input->post('from');
            $data["to"] = $this->input->post('to');
            $data["type"] = "html";
            $this->load->view('system/buyback/ajax/farm_ledger_report', $data);
        }
    }

    function check_valid_extra_column($fieldNameChk)
    {
        if (empty($fieldNameChk)) {
            $this->form_validation->set_message('check_valid_extra_column', 'Please select one currency');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_valid_documentCode_column($fieldNameChk)
    {
        if (empty($fieldNameChk)) {
            $this->form_validation->set_message('check_valid_documentCode_column', 'Please select one Document Type');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function delete_batch_master()
    {
        echo json_encode($this->Buyback_model->delete_batch_master());
    }

    function delete_buyback_notes()
    {
        echo json_encode($this->Buyback_model->delete_buyback_notes());
    }

    function save_paymentVoucher_batch()
    {
        //$this->form_validation->set_rules('batchMasterID', 'Batch', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->save_paymentVoucher_batch());
        }
    }

    function fetch_buyback_preformance_sublocationDropdown()
    {
        $data_arr = array();
        $locationID = $this->input->post('locationid');
        $comapnyid = $this->common_data['company_data']['company_id'];
        $where = " ";
        if (!empty($locationID)) {
            $filtersublocation = join($locationID, ",");
            $where = " AND (masterID IN ($filtersublocation) OR masterID  IS NULL OR masterID  = '')";
        }

        $location = $this->db->query("SELECT locationID,description FROM srp_erp_buyback_locations WHERE companyID = $comapnyid $where AND masterID!=0")->result_array();
        if (!empty($location)) {
            foreach ($location as $row) {
                $data_arr[trim($row['locationID'])] = trim($row['description']);
            }
        }
        echo form_dropdown('subLocationID[]', $data_arr, '', 'class="form-control" id="filter_sublocation" onchange="fetch_farm()" multiple="" ');
    }

    function fetch_farm_by_sub_location()
    {
        $farm_arr = array();
        $comapnyid = $this->common_data['company_data']['company_id'];
        $sublocationid = $this->input->post('sublocationid');
        $where = "";


        if (!empty($sublocationid)) {
            $filterfarm = join($sublocationid, ",");
            $where = " AND (subLocationID IN ($filterfarm) OR subLocationID  IS NULL OR subLocationID  = '')";
        }

        $farm = $this->db->query("SELECT farmID,description,farmSystemCode FROM srp_erp_buyback_farmmaster WHERE companyID = $comapnyid  $where")->result_array();
        if (isset($farm)) {
            foreach ($farm as $row) {
                $farm_arr[trim($row['farmID'])] = trim($row['farmSystemCode']) . " | " . trim($row['description']);
            }
        }
        echo form_dropdown('farmer[]', $farm_arr, '', 'class="form-control" id="filter_farm" multiple=""');
    }

    function buy_back_preformance_rpt()
    {
        $locationid = $this->input->post('locationID');
        $sublocationid = $this->input->post('subLocationID');
        $farmer = $this->input->post('farmer');
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');

        if (empty($datefrom)) {
            echo ' <div class="alert alert-warning" role="alert">
              Date From is required
            </div>';
        } else if (empty($dateto)) {
            echo ' <div class="alert alert-warning" role="alert">
              Date To is  required
            </div>';
        } else {

            $data["details"] = $this->Buyback_model->get_buyback_preformance_rpt($datefrom, $dateto, $locationid, $sublocationid, $farmer);
            $data["type"] = "html";
            echo $html = $this->load->view('system/buyback/report/load_buyback_preformance_rpt', $data, true);

        }
    }

    function get_buy_back_preformance_rpt_pdf()
    {
        $locationid = $this->input->post('locationID');
        $sublocationid = $this->input->post('subLocationID');
        $farmer = $this->input->post('farmer');
        $datefrom = $this->input->post('datefrom');
        $dateto = $this->input->post('dateto');
        $data["details"] = $this->Buyback_model->get_buyback_preformance_rpt($datefrom, $dateto, $locationid, $sublocationid, $farmer);
        $data["type"] = "pdf";
        $html = $this->load->view('system/buyback/report/load_buyback_preformance_rpt', $data, true);
        $this->load->library('pdf');
        $pdf = $this->pdf->printed($html, 'A4');
    }

    function fetchbirdscount()
    {
        $this->form_validation->set_rules('batchMasterID', 'Batch Master ID is missing', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->fetchbuybackbirdstot());
        }
    }

    function load_batch_overview()
    {

        $farmid = trim($this->input->post('farmID'));


        $birdsweight = $this->Buyback_model->birds_weight($farmid);
        $birdsfcr = $this->Buyback_model->birds_fcr($farmid);


        $data['weight'] = $birdsweight;
        $data['fcr'] = $birdsfcr;

        $this->load->view('system/buyback/batch_overview_chart', $data);
    }

    function save_gradings()
    {
        $this->form_validation->set_rules('id', 'Batch Master ID is missing', 'trim|required');
        $this->form_validation->set_rules('Grading', 'Grading', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->save_gradings());
        }
    }

    function load_buyback_collection()
    {
        $convertFormat = convert_date_format_sql();
        $comapnyid = $this->common_data['company_data']['company_id'];
        $data['collection'] = $this->db->query("SELECT
	colrep.*,
	collection.collection  as collection,
	CONCAT_WS(' | ',IF(LENGTH(driverName),driverName,NULL),IF(LENGTH(helperName),helperName,NULL))as driverhelper,DATE_FORMAT(createdDateTime,'$convertFormat') as createdDate
FROM
	srp_erp_buyback_collectionreport colrep
	LEFT JOIN (SELECT coldet.collectionID, IFNULL( SUM( coldet.collectionQty ), 0 ) AS collection FROM srp_erp_buyback_collectionreportdetails coldet GROUP BY coldet.collectionID ) collection on collection.collectionID = colrep.collectionID 
WHERE
	companyID = $comapnyid  ORDER BY collectionCode DESC ")->result_array();
        $this->load->view('system/buyback/ajax/buyback_collection_create', $data);
    }

    function load_buyback_collection_header()
    {
        $date_format_policy = date_format_policy();
        $data['collectionautoid'] = trim($this->input->post('collectionautoid'));
        $collautoid = trim($this->input->post('collectionautoid'));
        $companyID = $this->common_data['company_data']['company_id'];
        $date_format_policy = date_format_policy();
        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $company_code = $this->common_data['company_data']['company_code'];
        $farmer = $this->input->post('farmer');
        $location = $this->input->post('locationID');
        $subarea = $this->input->post('subLocationID');
        $dateto = $this->input->post('batchmasterDateto');
        $datefrom = $this->input->post('batchmasterDatefrom');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $datetoconvert = input_format_date($dateto, $date_format_policy);
        $collectionid = $this->input->post('collectionid');

        $where_location = '';
        if (!empty($location)) {
            $location_filter = join($location, ",");
            $where_location = " AND farm.locationID IN ($location_filter)";
        }
        $where_sub_location = '';
        if (!empty($subarea)) {
            $sublocationidset = join($subarea, ",");
            $where_sub_location = " AND farm.subLocationID IN ($sublocationidset)";
        }
        $where_farmer = '';
        if (!empty($farmer)) {
            $farmerset = join($farmer, ",");
            $where_farmer = " AND farm.farmID IN ($farmerset)";
        }
        $date = '';
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( batch.batchStartDate >= '" . $datefromconvert . " 00:00:00' AND batch.batchClosingDate <= '" . $datetoconvert . " 23:59:00')";
        }
        $where_admin = "Where batch.companyID = " . $companyID . $where_farmer . $where_location . $where_sub_location . $date;


        if (!empty($collautoid)) {
            $data['batch'] = $this->db->query("select
colldettail.* ,
DATE_FORMAT( batchStartDate, ' . %d-%m-%Y . ' ) AS batchStartDate,
	DATE_FORMAT( batchClosingDate, ' . %d-%m-%Y . ' ) AS batchClosingDate,
	batch.batchCode, 
	farm.description as farmname,
	farmlocation.description as farmlocation,
	farmlocationsub.description as subarea
from
srp_erp_buyback_collectionreportdetails colldettail
LEFT JOIN srp_erp_buyback_batch batch on batch.batchMasterID = colldettail.batchID
LEFT JOIN srp_erp_buyback_farmmaster farm on farm.farmID = colldettail.farmerID
LEFT JOIN srp_erp_buyback_locations farmlocation on farmlocation.locationID = colldettail.locationID
LEFT JOIN srp_erp_buyback_locations farmlocationsub on farmlocationsub.locationID = colldettail.subLocationID 
$where_admin AND collectionID = $collautoid 
AND colldettail.companyID = $companyID ORDER BY farmlocation ASC ")->result_array();
        }
        $this->load->view('system/buyback/ajax/buyback_collection_details', $data);

    }

    function save_buyback_collection_header()
    {

        $this->form_validation->set_rules('farmer[]', 'Farmer', 'trim|required');
        $this->form_validation->set_rules('locationID[]', 'Location', 'trim|required');
        $this->form_validation->set_rules('subLocationID[]', 'Sub area', 'trim|required');
        $this->form_validation->set_rules('batchmasterDateto', 'Date to', 'trim|required');
        $this->form_validation->set_rules('batchmasterDatefrom', 'Date From', 'trim|required');
    //    $this->form_validation->set_rules('employeeName', 'Driver', 'trim|required');
    //    $this->form_validation->set_rules('helpername_n', 'Helper', 'trim|required');
    //    $this->form_validation->set_rules('comment', 'Comment', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->save_collection_header_detail());
        }
    }

    function update_buyback_collection_header()
    {
        $this->form_validation->set_rules('batchmasterDateto', 'Date to', 'trim|required');
        $this->form_validation->set_rules('batchmasterDatefrom', 'Date From', 'trim|required');
        $this->form_validation->set_rules('employeeName', 'Driver', 'trim|required');
        $this->form_validation->set_rules('helpername_n', 'Helper', 'trim|required');
        $this->form_validation->set_rules('comment', 'Comment', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->update_collection_header_detail());
        }
    }

    function add_collection_amt()
    {
        $this->form_validation->set_rules('batchid', 'Batch Id', 'trim|required');
        $this->form_validation->set_rules('collectionautoid', 'Collection Autoid', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Buyback_model->add_collection_amt());
        }
    }

    function load_buyback_collection_confirmation()
    {
        $collectionautoid = trim($this->input->post('collectionautoid'));
        $collectionautoidpost = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('collectionautoid'));

        $data['type'] = $this->input->post('html');
        $data['size'] = $this->input->post('size');
        $data['collectiondetails'] = $this->Buyback_model->get_collection_details($collectionautoidpost);
        $data['extra'] = $this->Buyback_model->getcollectionmaster($collectionautoidpost);
        /* print_r($data['extra']['collectionmaster']['collectionID']);
         exit();*/
        $html = $this->load->view('system/buyback/collection_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', $data['extra']['collectionmaster']['confirmedYN']);
        }
    }

    function load_collection_header()
    {
        echo json_encode($this->Buyback_model->load_collection_header());
    }

    function collection_confirmation()
    {
        echo json_encode($this->Buyback_model->collection_confirmation());
    }

    function fetch_balance_chicks()
    {

        echo json_encode($this->Buyback_model->fetch_balance_chicks());
    }

    function save_buyback_return()
    {
        $date_format_policy = date_format_policy();
        $rtndt = $this->input->post('returnDate');
        $returnDate = input_format_date($rtndt, $date_format_policy);

        $this->form_validation->set_rules('farmID', 'Farmer', 'trim|required');
        $this->form_validation->set_rules('returnDate', 'Return Date', 'trim|required|validate_date');
        $this->form_validation->set_rules('financeyear', 'Financial year', 'trim|required');
        $this->form_validation->set_rules('financeyear_period', 'Financial period', 'trim|required');
        $this->form_validation->set_rules('narration', 'Narration', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            $financearray = $this->input->post('financeyear_period');
            $financePeriod = fetchFinancePeriod($financearray);
            if ($returnDate >= $financePeriod['dateFrom'] && $returnDate <= $financePeriod['dateTo']) {
                echo json_encode($this->Buyback_model->save_buyback_return());
            } else {
                $this->session->set_flashdata('e', ' Return Date not between Financial period !');
                echo json_encode(FALSE);
            }
        }
    }

    function save_return_header()
    {
        $date_format_policy = date_format_policy();
        $rtndt = $this->input->post('returnDate');
        $returnDate = input_format_date($rtndt, $date_format_policy);


        $this->form_validation->set_rules('batchMasterID', 'Batch', 'trim|required');
        $this->form_validation->set_rules('returnDate', 'Return Date', 'trim|required|validate_date');
        $this->form_validation->set_rules('financeyear', 'Financial year', 'trim|required');
        $this->form_validation->set_rules('financeyear_period', 'Financial period', 'trim|required');
        $this->form_validation->set_rules('narration', 'Narration', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            $financearray = $this->input->post('financeyear_period');
            $financePeriod = fetchFinancePeriod($financearray);
            if ($returnDate >= $financePeriod['dateFrom'] && $returnDate <= $financePeriod['dateTo']) {
                echo json_encode($this->Buyback_model->save_return_header());
            } else {
                $this->session->set_flashdata('e', 'Return Date not between Financial period !');
                echo json_encode(FALSE);
            }
        }
    }

    function fetch_dispatch_codes()
    {
        echo json_encode($this->Buyback_model->fetch_dispatch_code());
    }

    function fetch_dispatchdetails()
    {
        echo json_encode($this->Buyback_model->fetch_dispatchdetails());
    }

    function save_return_details()
    {
        echo json_encode($this->Buyback_model->save_return_details());
    }

    function fetch_return_table_detail()
    {
        echo json_encode($this->Buyback_model->fetch_return_table_detail());
    }

    function delete_return_detail()
    {
        echo json_encode($this->Buyback_model->delete_return_detail());
    }

    function fetch_buyback_return_table()
    {
        $convertFormat = convert_date_format_sql();
        $this->datatables->select("returnmaster.returnAutoID as returnAutoID,,returnmaster.documentSystemCode as documentSystemCode,farm.description as description,returnmaster.wareHouseLocation as wareHouseLocation,DATE_FORMAT( returnmaster.documentDate,'$convertFormat') as documentDate,returnmaster.confirmedYN as confirmedYN,
returnmaster.approvedYN as approvedYN,returnmaster.createdUserID as createdUserID,isDeleted");
        $this->datatables->from('srp_erp_buyback_dispatchreturn returnmaster');
        $this->datatables->join('srp_erp_buyback_farmmaster farm', 'farm.farmID = returnmaster.farmID', 'left');
        $this->datatables->add_column('returnwarehouse', '<b>To : </b> $1', 'wareHouseLocation');
        $this->datatables->add_column('confirmed', '$1', 'buybackReturnApproval(confirmedYN,approvedYN,"1",returnAutoID)');
        $this->datatables->add_column('approved', '$1', 'buybackReturnApproval(confirmedYN,approvedYN,"2",returnAutoID)');
        $this->datatables->add_column('edit', '$1', 'load_buyback_return_action(returnAutoID,confirmedYN,approvedYN,createdUserID,isDeleted)');
        $this->datatables->edit_column('DT_RowClass', '$1', 'set_deleted_class(isDeleted)');
        echo $this->datatables->generate();
    }

    function load_bubyack_return_header()
    {
        echo json_encode($this->Buyback_model->load_bubyack_return_header());
    }

    function load_buyback_return_conformation()
    {
        $buybackreturn = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('returnAutoID'));
        $data['extra'] = $this->Buyback_model->fetch_template_buyback_return_data($buybackreturn);
        $data['approval'] = $this->input->post('approval');
        $html = $this->load->view('system/buyback/buyback_return_print', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', $data['extra']['master']['approvedYN']);
        }
    }

    function buyback_return_confirmation()
    {
        echo json_encode($this->Buyback_model->buyback_return_confirmation());
    }

    function referback_buyback_return()
    {
        $returnautoid = $this->input->post('returnAutoID');

        $this->db->select('approvedYN,documentID');
        $this->db->where('returnAutoID', trim($returnautoid));
        $this->db->where('approvedYN', 1);
        $this->db->where('confirmedYN', 1);
        $this->db->from('srp_erp_buyback_dispatchreturn');
        $approved_buyback_return = $this->db->get()->row_array();
        if (!empty($approved_buyback_return)) {
            echo json_encode(array('e', 'The document already approved - ' . $approved_buyback_return['salesReturnCode']));
        } else {
            $this->load->library('approvals');
            $status = $this->approvals->approve_delete($returnautoid, 'BBDR');
            if ($status == 1) {
                echo json_encode(array('s', ' Referred Back Successfully.', $status));
            } else {
                echo json_encode(array('e', ' Error in refer back.', $status));
            }
        }

    }

    function delete_return()
    {
        echo json_encode($this->Buyback_model->delete_return());
    }

    function re_open_buyback()
    {
        echo json_encode($this->Buyback_model->re_open_buyback());
    }

    function delete_farm_master()
    {
        echo json_encode($this->Buyback_model->delete_farm_master());
    }

    function get_wip_report()
    {
        $this->form_validation->set_rules('famerid[]', 'Farm', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo ' <div class="alert alert-warning" role="alert">
                ' . validation_errors() . '
            </div>';
        } else {
            $checkbox = $this->input->post('hideTotalRow');

            $data["Tot"] = '';
            if($checkbox == 'Y'){
                $data["Tot"] = 'show';
            } else if($checkbox == 'N'){
                $data["Tot"] = 'hide';
            }
            $data["details"] = $this->Buyback_model->get_wip_report();

            $data["type"] = "html";
            echo $html = $this->load->view('system/buyback/report/wip_rpt_view', $data, true);
        }
    }

    function dispatchnote_drilldown()
    {
        $data["details"] = $this->Buyback_model->get_dispatchnote_drilldown();
        $data["type"] = "html";
        echo $html = $this->load->view('system/buyback/dispatchdrilldown', $data, true);

    }

    function get_wip_report_pdf()
    {
        $this->form_validation->set_rules('famerid[]', 'Farm', 'required');
        if ($this->form_validation->run() == FALSE) {
            echo ' <div class="alert alert-warning" role="alert">
                ' . validation_errors() . '
            </div>';
        } else {
            $data["details"] = $this->Buyback_model->get_wip_report();
            $data["type"] = "pdf";
            $this->load->library('pdf');
            $html = $this->load->view('system/buyback/report/wip_rpt_view', $data, true);
            $pdf = $this->pdf->printed($html, 'A4-L');

        }
    }
    function fetch_buyback_item_recode_grn()
    {
        echo json_encode($this->Buyback_model->fetch_buyback_item_recode_grn());
    }
    function fetch_double_entry_buyback_dispatch_return()
    {
        $masterID = ($this->uri->segment(3)) ? $this->uri->segment(3) : trim($this->input->post('masterID'));
        $code = ($this->uri->segment(4)) ? $this->uri->segment(4) : trim($this->input->post('code'));
        $data['extra'] = $this->Buyback_model->fetch_double_entry_buyback_return($masterID, $code);
        $html = $this->load->view('system/buyback/buybackreturn_double_entry_print.php', $data, true);
        if ($this->input->post('html')) {
            echo $html;
        } else {
            $this->load->library('pdf');
            $pdf = $this->pdf->printed($html, 'A4', 0);
        }
    }
    function fetch_return_approval()
    {
        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $approvedYN = trim($this->input->post('approvedYN'));
        $this->datatables->select('masterTbl.returnAutoID as returnAutoID,det.totalTransferCost as total_TransferCost,masterTbl.documentSystemCode AS systemcode,masterTbl.Narration AS COMMENT,fmmaster.description AS farmname,confirmedYN, DATE_FORMAT(returnedDate,\'' . $convertFormat . '\') AS returnedDate,masterTbl.transactionCurrencyDecimalPlaces as transactionCurrencyDecimalPlaces,transactionCurrency,masterTbl.approvedYN as approvedYN,approvalLevelID,documentApprovedID', false);
        $this->datatables->join('(SELECT SUM( totalTransferCost ) AS totalTransferCost, returnAutoID FROM srp_erp_buyback_dispatchreturndetails detailTbl GROUP BY returnAutoID) det', '(masterTbl.returnAutoID = det.returnAutoID )', 'left');
        $this->datatables->from('srp_erp_buyback_dispatchreturn masterTbl');
        $this->datatables->join('srp_erp_buyback_farmmaster fmmaster', 'fmmaster.farmID = masterTbl.farmID', 'left');
        $this->datatables->join('srp_erp_documentapproved', 'srp_erp_documentapproved.documentSystemCode = masterTbl.returnAutoID AND srp_erp_documentapproved.approvalLevelID = masterTbl.currentLevelNo');
        $this->datatables->join('srp_erp_approvalusers', 'srp_erp_approvalusers.levelNo = masterTbl.currentLevelNo');
        $this->datatables->where('srp_erp_documentapproved.documentID', 'BBDR');
        $this->datatables->where('srp_erp_approvalusers.documentID', 'BBDR');
        $this->datatables->where('srp_erp_documentapproved.companyID', $companyID);
        $this->datatables->where('srp_erp_approvalusers.companyID', $companyID);
        $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
        $this->datatables->where('srp_erp_documentapproved.approvedYN', trim($this->input->post('approvedYN')));
        $this->datatables->add_column('total_TransferCost', '<div class="pull-right"><b>$2 : </b> $1 </div>', 'number_format(total_TransferCost,transactionCurrencyDecimalPlaces),transactionCurrency');
       $this->datatables->add_column('confirmed', "<div style='text-align: center'>Level $1</div>", 'approvalLevelID');
        $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN,"BBDR",returnAutoID)');
        $this->datatables->add_column('edit', '$1', 'buyback_return_action_approval(returnAutoID,approvalLevelID,approvedYN,documentApprovedID,BBDR)');
        echo $this->datatables->generate();
    }
    function save_return_approval()
    {
        $system_code = trim($this->input->post('ReturnAutoID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('status'));

        if ($status == 1) {
            $approvedYN = checkApproved($system_code, 'BBDR', $level_id);
            if ($approvedYN) {
                echo json_encode(array('error' => 1, 'message' => 'Document already approved'));
            } else {
                $this->db->select('returnAutoID');
                $this->db->where('returnAutoID', trim($system_code));
                $this->db->where('confirmedYN', 2);
                $this->db->from('srp_erp_buyback_dispatchreturn');
                $return_approved = $this->db->get()->row_array();
                if (!empty($return_approved)) {
                    echo json_encode(array('error' => 1, 'message' => 'Document already rejected'));
                } else {
                    $this->form_validation->set_rules('status', 'Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('ReturnAutoID', 'Return Auto ID', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        //$this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(array('error' => 1, 'message' => validation_errors()));

                    } else {
                        echo json_encode($this->Buyback_model->save_return_approval());
                    }
                }
            }
        } else if ($status == 2) {
            $this->db->select('returnAutoID');
            $this->db->where('returnAutoID', trim($system_code));
            $this->db->where('confirmedYN', 2);
            $this->db->from('srp_erp_buyback_dispatchreturn');
            $po_approved = $this->db->get()->row_array();
            if (!empty($po_approved)) {
                echo json_encode(array('error' => 1, 'message' => 'Document already rejected'));
            } else {
                $rejectYN = checkApproved($system_code, 'SLR', $level_id);
                if (!empty($rejectYN)) {
                    echo json_encode(array('error' => 1, 'message' => 'Document already approved'));
                } else {
                    $this->form_validation->set_rules('status', 'Approval Status', 'trim|required');
                    if ($this->input->post('status') == 2) {
                        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
                    }
                    $this->form_validation->set_rules('ReturnAutoID', 'Return Auto ID ', 'trim|required');
                    $this->form_validation->set_rules('documentApprovedID', 'Document Approved ID', 'trim|required');
                    if ($this->form_validation->run() == FALSE) {
                        //$this->session->set_flashdata($msgtype = 'e', validation_errors());
                        echo json_encode(array('error' => 1, 'message' => validation_errors()));

                    } else {
                        echo json_encode($this->Buyback_model->save_return_approval());
                    }
                }
            }
        }
    }
    function returnqtychicks()
    {
        echo json_encode($this->Buyback_model->returnqtychicks());
    }
    function fetch_goodReciptNote_batch_chicks_farmvisit()
    {

        echo json_encode($this->Buyback_model->fetch_goodReciptNote_batch_chicks_farmvisit());
    }


    /* =============================== Production Report ======================================== */
    function get_Production_Report()
    {
        $companyID = current_companyID();
        $yearID = $this->db->query("SELECT YEAR(beginingDate) AS year
                                       FROM srp_erp_companyfinanceyear 
                                       WHERE companyID = $companyID AND isCurrent = 1")->row_array();
        $data['year'] = $yearID['year'];
        $data["details"] = $this->Buyback_model->get_Production_Report_Details();
            $data["type"] = "html";
            echo $html = $this->load->view('system/buyback/report/production_report_output_view', $data, true);
    }

    function get_Production_Report_pdf(){
        $companyID = current_companyID();
        $yearID = $this->db->query("SELECT YEAR(beginingDate) AS year
                                       FROM srp_erp_companyfinanceyear 
                                       WHERE companyID = $companyID AND isCurrent = 1")->row_array();
        $data['year'] = $yearID['year'];
        $data["details"] = $this->Buyback_model->get_Production_Report_Details();
        $data["type"] = "pdf";
        $html = $this->load->view('system/buyback/report/production_report_output_view', $data, true);
        $this->load->library('pdf');
        $pdf = $this->pdf->printed($html, 'A4');

    }

    function get_outstanding_filter()
    {
        $data = array();
        $data["columns"] = $this->Buyback_model->getColumnsByReport('BB_FL');
        $data["formName"] = $this->input->post('formName');
        $data["reportID"] = $this->input->post('reportID');
        $data["type"] = $this->input->post('type');
        $this->load->view('system/buyback/report/outstanding_filter', $data);
    }

    function get_outstanding_report(){
        $fieldNameChk = array("transactionAmount");
        $this->form_validation->set_rules('from', 'As of', 'trim|required');
        $this->form_validation->set_rules('farmerTo[]', 'Farmer', 'trim|required');
        $this->form_validation->set_rules('fieldNameChk[]', 'fieldNameChk', 'callback_check_valid_extra_column');
        if ($this->form_validation->run() == FALSE) {
            $error_message = validation_errors();
            echo warning_message($error_message);
        } else {
            $data = array();
            $data["output"] = $this->Buyback_model->get_outstanding_report();
            $data["caption"] = $this->input->post('captionChk');
            $data["fieldName"] = $this->input->post('fieldNameChk');
            $data["from"] = $this->input->post('from');
            $data["to"] = $this->input->post('to');
            $data["type"] = "html";
            $this->load->view('system/buyback/ajax/outstanding_report', $data);
        }
    }

    function save_grn_transportDetails(){
        $this->form_validation->set_rules('grnAutoID', 'grnAutoID', 'trim|required');
        $this->form_validation->set_rules('VehicleName', 'Vehicle', 'trim|required');
        $this->form_validation->set_rules('driverName', 'Driver', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', 'message' => validation_errors()));

        } else {
            echo json_encode($this->Buyback_model->save_grn_transportDetails());
        }
    }
}