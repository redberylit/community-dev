<?php
class Group_category_model extends ERP_Model{

    function saveCategory()
    {
        $companyID=$this->common_data['company_data']['company_id'];
        //$companyGroup = $this->db->query("SELECT companyGroupID FROM srp_erp_companygroupdetails WHERE srp_erp_companygroupdetails.companyID = {$companyID}")->row_array();

        if (empty($this->input->post('partyCategoryID'))) {
            $this->db->select('partyCategoryID');
            $this->db->where('categoryDescription', $this->input->post('categoryDescription'));
            $this->db->where('partyType', 1);
            $this->db->where('groupID', $companyID);
            $category = $this->db->get('srp_erp_grouppartycategories')->row_array();
            if (empty($category)) {
                $this->db->set('categoryDescription', $this->input->post('categoryDescription'));
                $this->db->set('partyType', 1);
                $this->db->set('groupID', $companyID);
                $this->db->set('createdPCID', current_pc());
                $this->db->set('createdUserID', current_userID());
                $this->db->set('createdUserID', current_userID());
                $this->db->set('createdUserName', current_user());
                $this->db->set('createdDateTime', $this->common_data['current_date']);
                $result = $this->db->insert('srp_erp_grouppartycategories');

                if ($result) {
                    return array('s', 'Record added successfully');
                } else {
                    return array('e', 'Error in adding Record');
                }
            } else {
                return array('e', 'Category Already Exist');
            }
        } else {
            $this->db->select('partyCategoryID');
            $this->db->where('categoryDescription', $this->input->post('categoryDescription'));
            $this->db->where('partyType', 1);
            $this->db->where('groupID', $companyID);
            $category = $this->db->get('srp_erp_grouppartycategories')->row_array();
            if (empty($category)) {
                $data['categoryDescription'] = $this->input->post('categoryDescription');
                $data['modifiedPCID'] = current_pc();
                $data['modifiedUserID'] = current_userID();
                $data['modifiedDateTime'] = $this->common_data['current_date'];
                $data['modifiedUserName'] = current_user();

                $this->db->where('partyCategoryID', $this->input->post('partyCategoryID'));
                $result = $this->db->update('srp_erp_grouppartycategories', $data);


                if ($result) {
                    return array('s', 'Record Updated successfully');
                } else {
                    return array('e', 'Error in Updating Record');
                }
            } else {
                return array('e', 'Category Already Exist');
            }
        }
    }

    function getCategory()
    {
        $this->db->select('*');
        $this->db->where('partyCategoryID', $this->input->post('partyCategoryID'));
        return $this->db->get('srp_erp_grouppartycategories')->row_array();
    }

    function delete_category()
    {
        $this->db->where('partyCategoryID', $this->input->post('partyCategoryID'));
        $result = $this->db->delete('srp_erp_grouppartycategories');
        if ($result) {
            return array('s', 'Record Deleted successfully');
        }
    }


    function saveSupplierCategory()
    {
        $companyID=$this->common_data['company_data']['company_id'];
        //$companyGroup = $this->db->query("SELECT companyGroupID FROM srp_erp_companygroupdetails WHERE srp_erp_companygroupdetails.companyID = {$companyID}")->row_array();
        if (empty($this->input->post('partyCategoryID'))) {

            $this->db->select('partyCategoryID');
            $this->db->where('categoryDescription', $this->input->post('categoryDescription'));
            $this->db->where('partyType', 2);
            $this->db->where('groupID', $companyID);
            $category = $this->db->get('srp_erp_grouppartycategories')->row_array();
            if (empty($category)) {
                $this->db->set('categoryDescription', $this->input->post('categoryDescription'));
                $this->db->set('partyType', 2);
                $this->db->set('groupID', $companyID);
                $this->db->set('createdPCID', current_pc());
                $this->db->set('createdUserID', current_userID());
                $this->db->set('createdUserID', current_userID());
                $this->db->set('createdUserName', current_user());
                $this->db->set('createdDateTime', $this->common_data['current_date']);
                $result = $this->db->insert('srp_erp_grouppartycategories');

                if ($result) {
                    return array('s', 'Record added successfully');
                } else {
                    return array('e', 'Error in adding Record');
                }
            } else {
                return array('e', 'Category Already Exist');
            }
        } else {
            $this->db->select('partyCategoryID');
            $this->db->where('categoryDescription', $this->input->post('categoryDescription'));
            $this->db->where('partyType', 2);
            $this->db->where('groupID', $companyID);
            $category = $this->db->get('srp_erp_grouppartycategories')->row_array();
            if (empty($category)) {
                $data['categoryDescription'] = $this->input->post('categoryDescription');
                $data['modifiedPCID'] = current_pc();
                $data['modifiedUserID'] = current_userID();
                $data['modifiedDateTime'] = $this->common_data['current_date'];
                $data['modifiedUserName'] = current_user();

                $this->db->where('partyCategoryID', $this->input->post('partyCategoryID'));
                $result = $this->db->update('srp_erp_grouppartycategories', $data);


                if ($result) {
                    return array('s', 'Record Updated successfully');
                } else {
                    return array('e', 'Error in Updating Record');
                }
            } else {
                return array('e', 'Category Already Exist');
            }
        }

    }

    function getSupplierCategory()
    {
        $this->db->select('*');
        $this->db->where('partyCategoryID', $this->input->post('partyCategoryID'));
        return $this->db->get('srp_erp_grouppartycategories')->row_array();
    }

    function load_category_header()
    {
        $this->db->select('*');
        $this->db->where('partyCategoryID', $this->input->post('groupCustomerCategoryID'));
        return $this->db->get('srp_erp_grouppartycategories')->row_array();
    }

    function save_customer_category_link()
    {
        $companyid = $this->input->post('companyIDgrp');
        $partyCategoryID = $this->input->post('partyCategoryID');
        $com = current_companyID();
        $grpid = $com;
        $masterGroupID=getParentgroupMasterID();
        $this->db->delete('srp_erp_grouppartycategorydetails', array('companyGroupID' => $grpid, 'groupPartyCategoryID' => $this->input->post('partyCategoryIDhn')));

            foreach($companyid as $key => $val){
                if(!empty($partyCategoryID[$key])) {
                    $data['groupPartyCategoryID'] = trim($this->input->post('partyCategoryIDhn'));
                    $data['partyCategoryID'] = trim($partyCategoryID[$key]);
                    $data['companyID'] = trim($val);
                    $data['companyGroupID'] = $masterGroupID;

                    $data['createdPCID'] = $this->common_data['current_pc'];
                    $data['createdUserID'] = $this->common_data['current_userID'];
                    $data['createdUserName'] = $this->common_data['current_user'];
                    $data['createdDateTime'] = $this->common_data['current_date'];

                    $results = $this->db->insert('srp_erp_grouppartycategorydetails', $data);
                }else{
                    $results =true;
                }
            }



        if ($results) {
            return array('s', 'Customer Category Link Saved Successfully');
        } else {
            return array('e', 'Customer Category Link Save Failed');
        }
    }

    function save_supplier_category_link()
    {
        $companyid = $this->input->post('companyIDgrp');
        $partyCategoryID = $this->input->post('partyCategoryID');
        $com = current_companyID();
        $grpid = $com;
        $masterGroupID=getParentgroupMasterID();
        $this->db->delete('srp_erp_grouppartycategorydetails', array('companyGroupID' => $grpid, 'groupPartyCategoryID' => $this->input->post('partyCategoryIDhn')));

        foreach($companyid as $key => $val){
            if(!empty($partyCategoryID[$key])) {
                $data['groupPartyCategoryID'] = trim($this->input->post('partyCategoryIDhn'));
                $data['partyCategoryID'] = trim($partyCategoryID[$key]);
                $data['companyID'] = trim($val);
                $data['companyGroupID'] = $masterGroupID;

                $data['createdPCID'] = $this->common_data['current_pc'];
                $data['createdUserID'] = $this->common_data['current_userID'];
                $data['createdUserName'] = $this->common_data['current_user'];
                $data['createdDateTime'] = $this->common_data['current_date'];

                $results = $this->db->insert('srp_erp_grouppartycategorydetails', $data);
            }else{
                $results =true;
            }
        }



        if ($results) {
            return array('s', 'Supplier Category Link Saved Successfully');
        } else {
            return array('e', 'Supplier Category Link Save Failed');
        }
    }


}