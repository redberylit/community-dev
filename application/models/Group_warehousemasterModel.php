<?php
class Group_warehousemasterModel extends ERP_Model{

    function save_warehousemaster()
    {
        if(empty($this->input->post('warehouseredit'))){

            $companyID=$this->common_data['company_data']['company_id'];
            //$companyGroup = $this->db->query("SELECT companyGroupID FROM srp_erp_companygroupdetails WHERE srp_erp_companygroupdetails.companyID = {$companyID}")->row_array();
            $warehousecode = $this->input->post('warehousecode');
            $Warehouse = $this->db->query("SELECT wareHouseAutoID FROM srp_erp_groupwarehousemaster where groupID = {$companyID} AND wareHouseCode = '{$warehousecode}'")->row_array();
            if ($Warehouse) {
                $this->session->set_flashdata('e', 'Warehouse already created !');
                return false;
            } else {
                //$this->db->set('companyCode', (($this->input->post('companyid') != "")) ? $this->input->post('companyid') : NULL);
                $this->db->set('wareHouseCode', (($this->input->post('warehousecode') != "")) ? $this->input->post('warehousecode') : NULL);
                $this->db->set('wareHouseDescription', (($this->input->post('warehousedescription') != "")) ? $this->input->post('warehousedescription') : NULL);
                $this->db->set('wareHouseLocation', (($this->input->post('warehouselocation') != "")) ? $this->input->post('warehouselocation') : NULL);
                $this->db->set('warehouseAddress', (($this->input->post('warehouseAddress') != "")) ? $this->input->post('warehouseAddress') : NULL);
                $this->db->set('warehouseTel', (($this->input->post('warehouseTel') != "")) ? $this->input->post('warehouseTel') : NULL);
                //   $this->db->set('isPosLocation', (($this->input->post('isPosLocation') != "")) ? $this->input->post('isPosLocation') : NULL);
                $this->db->set('createdUserGroup', ($this->common_data['user_group']));
                $this->db->set('createdPCID', ($this->common_data['current_pc']));
                $this->db->set('createdUserID', ($this->common_data['current_userID']));
                $this->db->set('createdDateTime', ($this->common_data['current_date']));
                $this->db->set('createdUserName', ($this->common_data['current_user']));
                $this->db->set('groupID', ($companyID));

                $result = $this->db->insert('srp_erp_groupwarehousemaster');
                if ($result) {
                    $this->session->set_flashdata('s', 'Warehouse Added Successfully');
                    return true;
                }
            }
        }
        else{

            $companyID=$this->common_data['company_data']['company_id'];
            //$companyGroup = $this->db->query("SELECT companyGroupID FROM srp_erp_companygroupdetails WHERE srp_erp_companygroupdetails.companyID = {$companyID}")->row_array();
            $warehousecode = $this->input->post('warehousecode');
            $warehouseredit = $this->input->post('warehouseredit');
            $Warehouse = $this->db->query("SELECT wareHouseAutoID FROM srp_erp_groupwarehousemaster where groupID = {$companyID} AND wareHouseCode = '{$warehousecode}' AND wareHouseAutoID !=  $warehouseredit ")->row_array();
           // echo $this->db->last_query();
            if ($Warehouse) {
                $this->session->set_flashdata('e', 'Warehouse already created !');
                return false;
            } else {
                $data['wareHouseCode'] = ((($this->input->post('warehousecode') != "")) ? $this->input->post('warehousecode') : NULL);
                $data['wareHouseDescription'] = ((($this->input->post('warehousedescription') != "")) ? $this->input->post('warehousedescription') : NULL);
                $data['wareHouseLocation'] = ((($this->input->post('warehouselocation') != "")) ? $this->input->post('warehouselocation') : NULL);
                $data['warehouseAddress']= ((($this->input->post('warehouseAddress') != "")) ? $this->input->post('warehouseAddress') : NULL);
                $data['warehouseTel'] = ((($this->input->post('warehouseTel') != "")) ? $this->input->post('warehouseTel') : NULL);
                // $data['isPosLocation'] = ((($this->input->post('isPosLocation') != "")) ? $this->input->post('isPosLocation') : NULL);
                $data['modifiedPCID'] = ($this->common_data['current_pc']);
                $data['modifiedUserID'] = ($this->common_data['current_userID']);
                $data['modifiedDateTime'] = ($this->common_data['current_date']);
                $data['modifiedUserName'] = ($this->common_data['current_user']);


                $this->db->where('wareHouseAutoID', $this->input->post('warehouseredit'));
                $result = $this->db->update('srp_erp_groupwarehousemaster', $data);
                if ($result) {
                    $this->session->set_flashdata('s', 'Records Updated Successfully');
                    return true;
                }
            }
        }
    }

    function get_warehouse()
    {
        $this->db->select('*');
        $this->db->where('wareHouseAutoID', $this->input->post('id'));
        return $this->db->get('srp_erp_groupwarehousemaster')->row_array();
    }

    function delete_warehouse_link()
    {
        $this->db->where('groupWarehouseDetailID', $this->input->post('groupWarehouseDetailID'));
        $result = $this->db->delete('srp_erp_groupwarehousedetails');
        return array('s', 'Record Deleted Successfully');
    }

    function save_warehouse_link()
    {
        $companyid = $this->input->post('companyIDgrp');
        $warehosueMasterID = $this->input->post('warehosueMasterID');
        $com = current_companyID();
        /*$this->db->select('companyGroupID');
        $this->db->where('companyID', $com);
        $grp = $this->db->get('srp_erp_companygroupdetails')->row_array();*/
        $grpid = $com;

        $results =$this->db->delete('srp_erp_groupwarehousedetails', array('companyGroupID' => $grpid, 'groupWarehouseMasterID' => $this->input->post('groupwareHouseAutoID')));

        foreach($companyid as $key => $val){
            if(!empty($warehosueMasterID[$key])){
                $data['groupWarehouseMasterID'] = trim($this->input->post('groupwareHouseAutoID'));
                $data['warehosueMasterID'] = trim($warehosueMasterID[$key]);
                $data['companyID'] = trim($val);
                $data['companyGroupID'] = $grpid;

                $data['createdPCID'] = $this->common_data['current_pc'];
                $data['createdUserID'] = $this->common_data['current_userID'];
                $data['createdUserName'] = $this->common_data['current_user'];
                $data['createdDateTime'] = $this->common_data['current_date'];

                $results = $this->db->insert('srp_erp_groupwarehousedetails', $data);
            }
            //$last_id = $this->db->insert_id();
        }
        if ($results) {
            return array('s', 'Warehouse Link Saved Successfully');
        } else {
            return array('e', 'Warehouse Link Save Failed');
        }
    }

    function load_warehouse_header()
    {
        $this->db->select('wareHouseLocation');
        //$this->db->join('srp_erp_groupcustomerdetails', 'srp_erp_groupcustomermaster.groupCustomerAutoID = srp_erp_groupcustomerdetails.groupCustomerMasterID');
        $this->db->where('wareHouseAutoID', $this->input->post('groupwareHouseAutoID'));
        return $this->db->get('srp_erp_groupwarehousemaster')->row_array();
    }




}
