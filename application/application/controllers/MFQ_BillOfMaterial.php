<?php

class MFQ_BillOfMaterial extends ERP_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('MFQ_BillOfMaterial_model');
    }

    function fetch_bom()
    {
        $this->datatables->select('BoM.bomMasterID as bomMasterID, BoM.documentCode as documentCode, MFQI.itemDescription as description, BoM.industryTypeID, IT.industryTypeDescription as industryTypeDescription', false)
            ->from('srp_erp_mfq_billofmaterial BoM')->join('srp_erp_mfq_industrytypes IT', 'IT.industrytypeID = BoM.industryTypeID', 'left')->join('srp_erp_mfq_itemmaster MFQI', 'MFQI.mfqItemID = BoM.mfqItemID', 'left');
        $this->datatables->where('BoM.companyID', $this->common_data['company_data']['company_id']);
        $this->datatables->add_column('edit', '$1', 'editBoM(bomMasterID)');
        echo $this->datatables->generate();
    }

    function fetch_related_uom_id()
    {
        echo json_encode($this->MFQ_BillOfMaterial_model->fetch_related_uom_id());
    }

    function load_unitprice_exchangerate()
    {
        echo json_encode($this->MFQ_BillOfMaterial_model->load_unitprice_exchangerate());

    }

    function add_edit_BoMMaster()
    {

        $fileExist = false;
        if (isset($_FILES['productImage']['name']) && !empty($_FILES['productImage']['name'])) {
            $fileExist = true;


            $path = './uploads/';
            $tmpImagePath = $_FILES['productImage']['name'];
            $ext = pathinfo($tmpImagePath, PATHINFO_EXTENSION);

            $fileName = 'mfq_product_' . time() . '.' . $ext;

            $config['upload_path'] = $path;
            $config['allowed_types'] = 'png|jpg|jpeg';
            $config['max_size'] = '200000';
            $config['file_name'] = $fileName;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $this->upload->do_upload("productImage");
            $tmpData = $this->upload->data();
            $_POST['productImage'] = isset($tmpData['file_name']) ? $tmpData['file_name'] : '';
        }


        $bomMasterID = $this->input->post('bomMasterID');

        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('documentDate', 'Date', 'required');
        $this->form_validation->set_rules('industryTypeID', 'Industry Type', 'required');
        $this->form_validation->set_rules('Qty', 'Qty', 'required');
        $this->form_validation->set_rules('uomID', 'Unit of Measure', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('error' => 1, 'message' => validation_errors()));
        } else {
            if ($bomMasterID) {
                /** Update */
                echo json_encode($this->MFQ_BillOfMaterial_model->update_BoM());
            } else {
                /** Insert */
                echo json_encode($this->MFQ_BillOfMaterial_model->insert_BoM());
            }
        }
    }

    function add_edit_BillOfMaterial()
    {
        $fileExist = false;
        if (isset($_FILES['productImage']['name']) && !empty($_FILES['productImage']['name'])) {
            $fileExist = true;

            $path = './uploads/';
            $tmpImagePath = $_FILES['productImage']['name'];
            $ext = pathinfo($tmpImagePath, PATHINFO_EXTENSION);

            $fileName = 'mfq_product_' . time() . '.' . $ext;

            $config['upload_path'] = $path;
            $config['allowed_types'] = 'png|jpg|jpeg';
            $config['max_size'] = '200000';
            $config['file_name'] = $fileName;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $this->upload->do_upload("productImage");
            $tmpData = $this->upload->data();
            $_POST['productImage'] = isset($tmpData['file_name']) ? $tmpData['file_name'] : '';
        }


        $this->form_validation->set_rules('product', 'Item', 'required|callback_itemCheck[' . $this->input->post('bomMasterID') . ']');
        $this->form_validation->set_rules('documentDate', 'Date', 'required');
        if (!$this->input->post("estimateDetailID")) {
            $this->form_validation->set_rules('industryTypeID', 'Industry Type', 'required');
        }
        $this->form_validation->set_rules('Qty', 'Qty', 'required');
        $this->form_validation->set_rules('uomID', 'Unit of Measure', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('error' => 1, 'message' => validation_errors()));
        } else {
            echo json_encode($this->MFQ_BillOfMaterial_model->add_edit_BillOfMaterial());
        }
    }

    function itemCheck($id, $bomMasterID)
    {
        $result = "";
        if ($bomMasterID) {
            $this->db->select('mfqItemID');
            $this->db->from('srp_erp_mfq_billofmaterial');
            $this->db->where('mfqItemID', $id);
            $this->db->where('bomMasterID <>', $bomMasterID);
            $this->db->where('companyID', current_companyID());
            $result = $this->db->get()->row_array();
        } else {
            $this->db->select('mfqItemID');
            $this->db->from('srp_erp_mfq_billofmaterial');
            $this->db->where('mfqItemID', $id);
            $this->db->where('companyID', current_companyID());
            $result = $this->db->get()->row_array();
        }
        if ($result) {
            $this->form_validation->set_message('itemCheck', 'Already a bom created for selected item');
            return FALSE;
        } else {
            return true;
        }

    }

    function load_mfq_billOfMaterial()
    {
        $bomMasterID = $this->input->post('bomMasterID');
        $mfqItemMaster = $this->MFQ_BillOfMaterial_model->get_srp_erp_mfq_billofmaterial($bomMasterID);
        if (!empty($mfqItemMaster)) {
            echo json_encode(array_merge(array('error' => 0, 'message' => 'done'), $mfqItemMaster));
        } else {
            echo json_encode(array('error' => 1, 'message' => 'no record found!'));
        }
    }

    function load_mfq_billOfMaterial_detail()
    {
        $bomMasterID = $this->input->post('bomMasterID');
        echo json_encode($this->MFQ_BillOfMaterial_model->load_mfq_billOfMaterial_detail($bomMasterID));
    }


    function save_material_consumption()
    {
        $bomMaterialConsumptionID = $this->input->post('bomMaterialConsumptionID');

        $mfqItemID = $this->input->post('mfqItemID');
        if (!empty($mfqItemID)) {
            try {
                foreach ($mfqItemID as $key => $val) {
                    if (!empty($bomMaterialConsumptionID[$key])) {

                        $materialCost = ($this->input->post('markUp')[$key]) * ($this->input->post('qtyUsed')[$key]);
                        $materialCharge = $this->input->post('markUp')[$key] * ($materialCost / 100);

                        $this->db->set('mfqItemID', $this->input->post('mfqItemID')[$key]);
                        $this->db->set('qtyUsed', $this->input->post('qtyUsed')[$key]);
                        $this->db->set('unitCost', $this->input->post('unitCost')[$key]);
                        $this->db->set('materialCost', $materialCost);
                        $this->db->set('markUp', $this->input->post('markUp')[$key]);
                        $this->db->set('materialCharge', $materialCharge);

                        $this->db->set('crewID', $this->input->post('crewID')[$key]);
                        $this->db->set('modifiedPCID', gethostbyaddr($_SERVER['REMOTE_ADDR']));
                        $this->db->set('modifiedUserID', current_userID());
                        $this->db->set('modifiedUserName', current_user());
                        $this->db->set('modifiedDateTime', current_date(true));
                        $this->db->where('bomMaterialConsumptionID', $bomMaterialConsumptionID[$key]);
                        $this->db->update('srp_erp_mfq_bom_materialconsumption');

                    } else {

                        $materialCost = ($this->input->post('markUp')[$key]) * ($this->input->post('qtyUsed')[$key]);
                        $materialCharge = $this->input->post('markUp')[$key] * ($materialCost / 100);

                        $this->db->set('bomMasterID', $this->input->post('bomMasterID'));
                        $this->db->set('mfqItemID', $this->input->post('mfqItemID')[$key]);
                        $this->db->set('qtyUsed', $this->input->post('qtyUsed')[$key]);
                        $this->db->set('unitCost', $this->input->post('unitCost')[$key]);
                        $this->db->set('materialCost', $materialCost);
                        $this->db->set('markUp', $this->input->post('markUp')[$key]);
                        $this->db->set('materialCharge', $materialCharge);
                        $this->db->set('modifiedPCID', current_pc());
                        $this->db->set('modifiedUserID', current_userID());
                        $this->db->set('modifiedDateTime', format_date_mysql_datetime());

                        $this->db->insert('srp_erp_mfq_bom_materialconsumption');
                        echo $this->db->last_query();
                    }
                }
                $this->db->trans_commit();
                return array('error' => 0, 'message' => 'Material Added Successfully.');

            } catch (Exception $e) {
                $this->db->trans_rollback();
                return array('error' => 1, 'message' => 'Error while adding material' . $this->db->_error_message());
            }
        }
    }

    function delete_materialConsumption()
    {
        $id = $this->input->post('bomMaterialConsumptionID');
        echo json_encode($this->MFQ_BillOfMaterial_model->delete_materialConsumption($id));
    }

    function load_bom_material_consumption()
    {
        $bomMasterID = $this->input->post('bomMasterID');
        $output = $this->MFQ_BillOfMaterial_model->load_bom_material_consumption($bomMasterID);
        echo json_encode($output);
    }


    /** Labour Task */
    function fetch_labourTask()
    {
        echo json_encode($this->MFQ_BillOfMaterial_model->fetch_labourTask());
    }

    function fetch_overhead()
    {
        echo json_encode($this->MFQ_BillOfMaterial_model->fetch_overhead());
    }

    function fetch_machine()
    {
        echo json_encode($this->MFQ_BillOfMaterial_model->fetch_machine());
    }

    function fetch_bom_labour_task()
    {
        echo json_encode($this->MFQ_BillOfMaterial_model->fetch_bom_labour_task());
    }

    function delete_labour_task()
    {
        echo json_encode($this->MFQ_BillOfMaterial_model->delete_labour_task());
    }

    function delete_overhead_cost()
    {
        echo json_encode($this->MFQ_BillOfMaterial_model->delete_overhead_cost());
    }

    function fetch_bom_overhead_cost()
    {
        echo json_encode($this->MFQ_BillOfMaterial_model->fetch_bom_overhead_cost());
    }

    function fetch_bom_machine_cost()
    {
        echo json_encode($this->MFQ_BillOfMaterial_model->fetch_bom_machine_cost());
    }

    function checkItemInBom()
    {
        $result = "";
        if ($this->input->post("bomMasterID")) {
            $this->db->select('mfqItemID');
            $this->db->from('srp_erp_mfq_billofmaterial');
            $this->db->where('mfqItemID', $this->input->post("mfqItemID"));
            $this->db->where('bomMasterID <>', $this->input->post("bomMasterID"));
            $this->db->where('companyID', current_companyID());
            $result = $this->db->get()->result_array();
        } else {
            $this->db->select('mfqItemID');
            $this->db->from('srp_erp_mfq_billofmaterial');
            $this->db->where('mfqItemID', $this->input->post("mfqItemID"));
            $this->db->where('companyID', current_companyID());
            $result = $this->db->get()->result_array();
        }
        if ($result) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    }

    function deleteBOM()
    {
        echo json_encode($this->MFQ_BillOfMaterial_model->deleteBOM());
    }

    function load_segment_hours()
    {
        echo json_encode($this->MFQ_BillOfMaterial_model->load_segment_hours());
    }

}
