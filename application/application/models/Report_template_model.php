<?php

class Report_template_model extends ERP_Model
{

    function save_reportTemplateMaster()
    {
        $this->db->trans_start();
        $companyID = current_companyID();

        $data['description'] = trim($this->input->post('description'));
        $data['reportID'] = trim($this->input->post('reportID'));
        $data['companyID'] = $companyID;
        $data['createdUserGroup'] = current_user_group();
        $data['createdPCID'] = current_pc();
        $data['createdUserID'] = current_userID();
        $data['createdUserName'] = current_employee();
        $data['createdDateTime'] = current_date();


        $this->db->insert('srp_erp_companyreporttemplate', $data);
        $last_id = $this->db->insert_id();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return array('e', 'Template Save Failed');
        } else {
            return array('s', 'Template Saved Successfully');
        }
    }

    function save_reportTemplateDetail()
    {
        $this->db->trans_start();
        $companyID = current_companyID();
        $dateTime = current_date();
        $subMaster = trim($this->input->post('subMaster'));
        $subMaster = (empty($subMaster))? null: $subMaster;
        $itemType = $this->input->post('itemType');

        $data['companyReportTemplateID'] = trim($this->input->post('masterID'));
        $data['description'] = trim($this->input->post('description'));
        $data['sortOrder'] = trim($this->input->post('sortOrder'));
        $data['masterID'] = $subMaster;
        $data['itemType'] = $itemType;

        if($itemType == 2){
            $data['accountType'] = trim($this->input->post('accountType'));;
        }
        $data['companyID'] = trim($companyID);
        $data['createdUserGroup'] = current_user_group();
        $data['createdPCID'] = current_pc();
        $data['createdUserID'] = current_userID();
        $data['createdUserName'] = current_employee();
        $data['createdDateTime'] = $dateTime;
        $data['timestamp'] = $dateTime;

        $this->db->insert('srp_erp_companyreporttemplatedetails', $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return array('e', 'Error in process');
        } else {
            return array('s', 'Template Saved Successfully');
        }
    }

    function save_reportTemplateLink()
    {
        $this->db->trans_start();

        $companyID = current_companyID();
        $masterID = trim($this->input->post('masterID'));
        $column = ($this->input->post('linkType') == 'S') ? 'glAutoID': 'subCategory';
        $detID = trim($this->input->post('detID'));
        $glAutoID_arr = $this->input->post('glAutoID');
        $sortOrder = $this->db->query("SELECT MAX(sortOrder) AS sortOrder FROM srp_erp_companyreporttemplatelinks WHERE templateDetailID={$detID}")->row('sortOrder');
        $data = [];

        foreach ($glAutoID_arr as $key=>$gl){
            $sortOrder++;
            $data[$key]['templateMasterID'] = $masterID;
            $data[$key]['templateDetailID'] = $detID;
            $data[$key][$column] = $gl;
            $data[$key]['sortOrder'] = $sortOrder;
            $data[$key]['companyID'] = $companyID;
            $data[$key]['createdUserGroup'] = current_user_group();
            $data[$key]['createdPCID'] = current_pc();
            $data[$key]['createdUserID'] = current_userID();
            $data[$key]['createdUserName'] = current_employee();
            $data[$key]['createdDateTime'] = current_date();
        }

        $this->db->insert_batch('srp_erp_companyreporttemplatelinks', $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return array('e', 'Error in sub item adding process');
        } else {
            return array('s', 'Sub items added successfully');
        }
    }


}