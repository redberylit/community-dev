<?php
class Group_segemnt_model extends ERP_Model{

    function saveSegment()
    {
        $this->db->trans_start();
        $companyid = $this->common_data['company_data']['company_id'];
        /*$this->db->select('companyGroupID');
        $this->db->where('companyID', $companyid);
        $grp= $this->db->get('srp_erp_companygroupdetails')->row_array();*/
        $grpid=$companyid;

        $data['description'] = trim($this->input->post('description'));
        $data['segmentCode'] = trim($this->input->post('segmentCode'));
        if (trim($this->input->post('segmentID')) !='') {
            $this->db->where('segmentID', trim($this->input->post('segmentID')));
            $this->db->update('srp_erp_groupsegment', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                return array('e' ,'Segment Update Failed');
            } else {
                //$this->session->set_flashdata('s', 'Segment Updated Successfully.');
                return array('s','Segment Updated Successfully');
            }
        } else {
            $checkExist = $this->db->query("select * from srp_erp_groupsegment where segmentCode = '" . $this->input->post('segmentCode') . "' AND groupID = $grpid")->row_array();
            if (!empty($checkExist)) {
                return array('e','Segment Code already exists');
            } else {
                $data['groupID'] = trim($grpid);
                $this->db->insert('srp_erp_groupsegment', $data);
                $last_id = $this->db->insert_id();
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    return array('e','Segment Save Failed');
                } else {
                    return array('s' ,'Segment Saved Successfully');
                }
            }
        }
    }

    function edit_group_segment()
    {
        $this->db->select('*');
        $this->db->where('segmentID', $this->input->post('segmentID'));
        return $this->db->get('srp_erp_groupsegment')->row_array();
    }


    function save_segment_link()
    {
        $companyid = $this->input->post('companyIDgrp');
        $segmentID = $this->input->post('segmentID');
        $com = current_companyID();
        /*$this->db->select('companyGroupID');
        $this->db->where('companyID', $com);
        $grp = $this->db->get('srp_erp_companygroupdetails')->row_array();*/
        $grpid = $com;
        $results=$this->db->delete('srp_erp_groupsegmentdetails', array('companyGroupID' => $grpid, 'groupSegmentID' => $this->input->post('groupSegmentID')));
        foreach($companyid as $key => $val){
            if(!empty($segmentID[$key])){
                $data['groupSegmentID'] = trim($this->input->post('groupSegmentID'));
                $data['segmentID'] = trim($segmentID[$key]);
                $data['companyID'] = trim($val);
                $data['companyGroupID'] = $grpid;

                $data['createdPCID'] = $this->common_data['current_pc'];
                $data['createdUserID'] = $this->common_data['current_userID'];
                $data['createdUserName'] = $this->common_data['current_user'];
                $data['createdDateTime'] = $this->common_data['current_date'];

                $results = $this->db->insert('srp_erp_groupsegmentdetails', $data);
            }
            //$last_id = $this->db->insert_id();
        }

        if ($results) {
            return array('s', 'Segment Link Saved Successfully');

        } else {
            return array('e', 'Segment Link Save Failed');
        }
    }

    function delete_segment_link()
    {
        $this->db->where('groupSegmentDetailID', $this->input->post('groupSegmentDetailID'));
        $result = $this->db->delete('srp_erp_groupsegmentdetails');
        return array('s', 'Record Deleted Successfully');
    }

    function load_segment_header()
    {
        $this->db->select('description');
        $this->db->where('segmentID', $this->input->post('groupSegmentID'));
        return $this->db->get('srp_erp_groupsegment')->row_array();
    }


}