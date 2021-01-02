<?php

class MFQ_ItemMaster extends ERP_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('MFQ_Item_model');
        $this->load->helper('mfq');

    }

    function fetch_item()
    {
        $search = $_REQUEST["sSearch"];
        $this->datatables->select('mfqItemID,itemSystemCode,itemName, secondaryItemCode,itemImage,srp_erp_mfq_itemmaster.itemDescription as mfq_itemmasterDescription ,itm.itemDescription as itemmasterDescription,mainCategoryID,"" as mainCategory,defaultUnitOfMeasure,itm.currentStock as currentStock,companyLocalSellingPrice,companyLocalCurrency,companyLocalCurrencyDecimalPlaces,revenueDescription,costDescription,assetDescription,isActive,companyLocalWacAmount, ""  as SubCategoryDescription,CONCAT(itm.currentStock,\'  \',defaultUnitOfMeasure) as CurrentStock,CONCAT(companyLocalWacAmount,\'  \',companyLocalCurrency) as TotalWacAmount, mfqMasterCategory.description AS mainCatDescription , mfqMasterSubCategory.description as subCatDescription, mfqMasterSubSubCategory.description as subSubCatDescription, isFromERP as isFromERP,srp_erp_mfq_itemmaster.itemAutoID as itemAutoID', false)
            ->from('srp_erp_mfq_itemmaster')
            ->join('(SELECT itemAutoID,currentStock,itemDescription FROM srp_erp_itemmaster WHERE companyID ='.$this->common_data['company_data']['company_id'].') itm', 'srp_erp_mfq_itemmaster.itemAutoID = itm.itemAutoID', 'LEFT')
            ->join('srp_erp_itemcategory', 'srp_erp_mfq_itemmaster.subcategoryID = srp_erp_itemcategory.itemCategoryID', 'LEFT')
            ->join('srp_erp_mfq_category mfqMasterCategory', 'mfqMasterCategory.itemCategoryID = srp_erp_mfq_itemmaster.mfqCategoryID', 'LEFT')
            ->join('srp_erp_mfq_category mfqMasterSubCategory', 'mfqMasterSubCategory.itemCategoryID = srp_erp_mfq_itemmaster.mfqSubCategoryID', 'LEFT')
            ->join('srp_erp_mfq_category mfqMasterSubSubCategory', 'mfqMasterSubSubCategory.itemCategoryID = srp_erp_mfq_itemmaster.mfqSubSubCategoryID', 'LEFT');
        if (!empty(trim($this->input->post('mainCategory')))) {
            $this->datatables->where('mainCategoryID', $this->input->post('mainCategory'));
        }
        if (!empty(trim($this->input->post('subcategory')))) {
            $this->datatables->where('subcategoryID', $this->input->post('subcategory'));
        }
        if (!empty(trim($this->input->post('itemType')))) {
            $this->datatables->where('itemType', $this->input->post('itemType'));
        }
        $this->datatables->where('srp_erp_mfq_itemmaster.companyID', $this->common_data['company_data']['company_id']);
        if($search){
            $this->datatables->like('srp_erp_mfq_itemmaster.itemSystemCode', $search);
            $this->datatables->or_like('srp_erp_mfq_itemmaster.itemDescription', $search);
            $this->datatables->or_like('srp_erp_mfq_itemmaster.defaultUnitOfMeasure', $search);
            $this->datatables->or_like('mfqMasterCategory.description', $search);
            $this->datatables->or_like('mfqMasterSubCategory.description', $search);
            $this->datatables->or_like('mfqMasterSubSubCategory.description', $search);
            $this->datatables->or_like('srp_erp_mfq_itemmaster.secondaryItemCode', $search);
        }
        $this->datatables->add_column('item_inventryCode', '$1 - $2 <b></b>', 'itemSystemCode,mfq_itemmasterDescription');
        $this->datatables->add_column('confirmed', '$1', 'confirm_mfq(isActive)');

        $this->datatables->add_column('mfq_category', '$1', 'col_category(mfqItemID, mainCatDescription,add_mainCategory,itemName)');
        $this->datatables->add_column('mfq_subCategory', '$1', 'col_category(mfqItemID, subCatDescription,add_mainCategory,itemName)');
        $this->datatables->add_column('mfq_subSubCategory', '$1', 'col_category(mfqItemID, subSubCatDescription,add_mainCategory,itemName)');
        //$this->datatables->add_column('confirmed', '$1', 'confirm_mfq(isActive)');
        //$this->datatables->add_column('confirmed', '$1', 'confirm_mfq(isActive)');
        $this->datatables->add_column('edit', '$1', 'edit_mfq_item(mfqItemID,isFromERP,itemAutoID)');

        $r = $this->datatables->generate();
        //echo $this->db->last_query();
        //exit;
        echo $r;
    }

    function save_itemmaster()
    {
        $maincategory = $this->db->query("SELECT itemCategoryID,categoryTypeID FROM srp_erp_itemcategory WHERE itemCategoryID ={$this->input->post('mainCategoryID')}")->row_array();

        if (!$this->input->post('itemAutoID')) {
            $this->form_validation->set_rules('mainCategoryID', 'Main category', 'trim|required');
            $this->form_validation->set_rules('defaultUnitOfMeasureID', 'Unit of messure', 'trim|required');
        }
        if ($maincategory['categoryTypeID'] == 3) {
            $this->form_validation->set_rules('COSTGLCODEdes', 'Cost Account', 'trim|required');
            $this->form_validation->set_rules('ACCDEPGLCODEdes', 'Acc Dep GL Code', 'trim|required');
            $this->form_validation->set_rules('DEPGLCODEdes', 'Dep GL Code', 'trim|required');
            $this->form_validation->set_rules('DISPOGLCODEdes', 'Disposal GL Code', 'trim|required');
        }
        if ($maincategory['categoryTypeID'] == 1) {
            $this->form_validation->set_rules('assteGLAutoID', 'Asste GL Code', 'trim|required');
            $this->form_validation->set_rules('revanueGLAutoID', 'Revanue GL Code', 'trim|required');
            $this->form_validation->set_rules('costGLAutoID', 'Cost GL Code', 'trim|required');
        }
        if ($maincategory['categoryTypeID'] == 2) {
            //$this->form_validation->set_rules('revanueGLAutoID', 'Revanue GL Code', 'trim|required');
            $this->form_validation->set_rules('costGLAutoID', 'Cost GL Code', 'trim|required');
        }
        $this->form_validation->set_rules('seconeryItemCode', 'Seconery Item Code', 'trim|required');
        $this->form_validation->set_rules('itemName', 'Item Name', 'trim|required');
        $this->form_validation->set_rules('itemDescription', 'Item Full Name', 'trim|required');
        $this->form_validation->set_rules('subcategoryID', 'Sub category', 'trim|required');
        /*        $this->form_validation->set_rules('maximunQty', 'Maximun Qty', 'trim|required');
                $this->form_validation->set_rules('minimumQty', 'Minimum Qty', 'trim|required');
                $this->form_validation->set_rules('reorderPoint', 'Reorder Point', 'trim|required');*/
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->MFQ_Item_model->save_item_master());
        }
    }

    function img_uplode()
    {
        $this->form_validation->set_rules('item_id', 'Item ID', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->MFQ_Item_model->img_uplode());
        }
    }

    function load_item_header()
    {
        echo json_encode($this->MFQ_Item_model->load_item_header());
    }

    function load_subcat()
    {
        echo json_encode($this->MFQ_Item_model->load_subcat());
    }

    function load_subsubcat()
    {
        echo json_encode($this->MFQ_Item_model->load_subsubcat());
    }

    function edit_item()
    {
        if ($this->input->post('id') != "") {
            echo json_encode($this->MFQ_Item_model->edit_item());
        } else {
            echo json_encode(FALSE);
        }
    }

    function item_master_img_uplode()
    {
        echo json_encode($this->MFQ_Item_model->item_master_img_uplode());
    }

    function delete_item()
    {
        echo json_encode($this->MFQ_Item_model->delete_item());
    }

    function load_gl_codes()
    {
        echo json_encode($this->MFQ_Item_model->load_gl_codes());
    }

    function changeitemactive()
    {
        echo json_encode($this->MFQ_Item_model->changeitemactive());
    }

    function load_category_type_id()
    {
        echo json_encode($this->MFQ_Item_model->load_category_type_id());
    }

    function load_unitprice_exchangerate()
    {
        echo json_encode($this->MFQ_Item_model->load_unitprice_exchangerate());
    }


    function item_image_upload()
    {
        $this->form_validation->set_rules('faID', 'Document Id is missing', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->MFQ_Item_model->item_image_upload());
        }
    }

    function fetch_sync_item()
    {

        $this->datatables->select('itemAutoID,itemSystemCode,itemName,seconeryItemCode,itemImage,itemDescription,mainCategoryID,mainCategory,defaultUnitOfMeasure,currentStock,companyLocalSellingPrice,companyLocalCurrency,companyLocalCurrencyDecimalPlaces,revanueDescription,costDescription,assteDescription,isActive,companyLocalWacAmount,srp_erp_itemcategory.description as SubCategoryDescription,CONCAT(currentStock,\'  \',defaultUnitOfMeasure) as CurrentStock,CONCAT(companyLocalWacAmount,\'  \',companyLocalCurrency) as TotalWacAmount', false)
            ->from('srp_erp_itemmaster')
            ->join('srp_erp_itemcategory', 'srp_erp_itemmaster.subcategoryID = srp_erp_itemcategory.itemCategoryID');
        $this->datatables->where('NOT EXISTS(SELECT * FROM srp_erp_mfq_itemmaster WHERE srp_erp_mfq_itemmaster.itemAutoID = srp_erp_itemmaster.itemAutoID AND companyID =' . $this->common_data['company_data']['company_id'] . ' )');
        $this->datatables->where('srp_erp_itemmaster.companyID', $this->common_data['company_data']['company_id']);
        if (!empty($this->input->post('mainCategory'))) {
            $this->datatables->where('mainCategoryID', $this->input->post('mainCategory'));
        } else {
            $this->datatables->where('srp_erp_itemmaster.mainCategory IN ("Inventory","Non Inventory","Service","Services")');
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

    /*function fetch_sync_item()
    {
        $search = $_REQUEST["sSearch"];
        $this->datatables->select('master.itemCodeSystem as itemAutoID,master.primaryCode as itemSystemCode,master.itemDescription as itemName,master.secondaryItemCode as seconeryItemCode,unit.UnitDes as defaultUnitOfMeasure,itmass.isActive,itmass.wacValueLocal as companyLocalWacAmount,CONCAT(itmled.currentStock,\'  \',unit.UnitDes) as CurrentStock,CONCAT(itmass.wacValueLocal,\'  \',itmass.wacValueLocalCurrencyID) as TotalWacAmount', false)
            ->from('gearserp.itemassigned itmass')
            ->join('gearserp.itemmaster master', 'master.itemCodeSystem = itmass.itemCodeSystem','left')
            ->join('gearserp.units unit', 'itmass.itemUnitOfMeasure = unit.UnitID','left')
            ->join('(SELECT SUM(inOutQty) as currentStock,itemSystemCode FROM gearserp.erp_itemledger WHERE companyID = "HEMT") itmled', 'itmled.itemSystemCode = itmass.itemCodeSystem','left');
        $this->datatables->where('NOT EXISTS(SELECT * FROM srp_erp_mfq_itemmaster WHERE srp_erp_mfq_itemmaster.itemAutoID = itmass.itemCodeSystem AND companyID ="HEMT")');
        $this->datatables->where('itmass.companyID',  "HEMT");
        if($search){
            $this->datatables->like('master.primaryCode', $search);
            $this->datatables->or_like('master.itemDescription', $search);
            $this->datatables->or_like('unit.UnitDes', $search);
            $this->datatables->or_like('master.secondaryItemCode', $search);
        }
        $this->datatables->add_column('item_inventryCode', '$1 - $2 <b></b>', 'itemSystemCode,itemDescription');
        $this->datatables->add_column('confirmed', '$1', 'confirm(isActive)');
        //$this->datatables->add_column('edit', '<input id="selectItem_$1" value="$1" type="checkbox" onclick="ItemsSelectedSync(this)">', 'itemAutoID');
        $this->datatables->add_column('edit', '<div style="text-align: center;"><div class="skin skin-square item-iCheck"> <div class="skin-section extraColumns"><input id="selectItem_$1" onclick="ItemsSelectedSync(this)" type="checkbox" class="columnSelected"  value="$1" ><label for="checkbox">&nbsp;</label> </div></div></div>', 'itemAutoID');
        echo $this->datatables->generate();
    }*/

    function add_item()
    {
        $this->form_validation->set_rules('selectedItemsSync[]', 'Item', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->MFQ_Item_model->add_item());
        }
    }


    function fetch_itemrecord()
    {
        echo json_encode($this->MFQ_Item_model->fetch_itemrecord());
    }

    function assign_itemCategory_children()
    {
        $validation = $this->mfq_validate_category();
        if ($validation['error'] == 1) {
            echo json_encode(array('error' => 1, 'message' => $validation['message']));
        } else {
            $itemAutoID = $this->input->post('itemAutoID');
            $data['mfqCategoryID'] = $this->input->post('categoryID');
            $data['mfqSubCategoryID'] = $this->input->post('subCategory');
            $data['mfqSubSubCategoryID'] = $this->input->post('subSubCategory');

            $result = $this->MFQ_Item_model->update_srp_erp_mfq_itemmaster($itemAutoID, $data);
            if ($result) {
                echo json_encode(array('error' => 0, 'message' => 'Record updated successfully!'));
            } else {
                echo json_encode(array('error' => 1, 'message' => 'Error, while updating.'));
            }
        }
    }

    function mfq_validate_category()
    {
        $category = $this->input->post('categoryID');
        $subCategory = $this->input->post('subCategory');
        $subSubCategory = $this->input->post('subSubCategory');
        if ($category == -1 || $category == '') {
            return array('error' => 1, 'message' => 'Category is required.');
        } else if ($subCategory == -1 || $subCategory == '') {
            return array('error' => 1, 'message' => 'Sub Category is required.');
        } else if ($subSubCategory == -1 || $subSubCategory == '') {
            return array('error' => 0);
            //return array('error' => 1, 'message' => 'Sub Sub Category is required.');
        } else {
            return array('error' => 0);
        }
    }

    function get_mfq_subCategory()
    {
        $parentID = $this->input->post('parentID');
        $result = $this->MFQ_Item_model->get_mfq_childCategory($parentID);
        echo json_encode($result);
    }

    function add_edit_mfq_item()
    {
        $mfqItemID = $this->input->post('mfqItemID');
        $companyid = $this->common_data['company_data']['company_id'];
        $maincatid = $this->input->post('mainCategoryID');

        if(!empty($maincatid))
        {
            $result=$this->db->query("SELECT categoryTypeID FROM `srp_erp_itemcategory` 
         WHERE
	    `masterID` IS NULL 
	    AND `companyID` = $companyid
	    AND itemCategoryID = $maincatid")->row_array();

            if(!empty($result['categoryTypeID']==2))
            {
                $this->form_validation->set_rules('unbilledServicesGLAutoID', 'Unbilled Services Gl Code', 'trim|required');
            }

        }





        $this->form_validation->set_rules('itemName', 'Item Name', 'trim|required');
        $this->form_validation->set_rules('secondaryItemCode', 'Item Secondary Code', 'trim|required');
        $this->form_validation->set_rules('mfqCategoryID', 'Category', 'trim|required');
        $this->form_validation->set_rules('mfqSubCategoryID', 'Sub Category', 'trim|required');
        //$this->form_validation->set_rules('mfqSubSubCategoryID', 'Sub Sub Cateogry', 'trim|required');
        $this->form_validation->set_rules('itemType', 'Category', 'trim|required');
        $this->form_validation->set_rules('mainCategoryID', 'Main Category', 'trim|required');
        $this->form_validation->set_rules('subcategoryID', 'Sub Category', 'trim|required');


        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('error' => 1, 'message' => validation_errors()));
        } else {
            if ($mfqItemID) {
                /** Update */
                echo json_encode($this->MFQ_Item_model->update_item());
            } else {
                /** Insert */
                echo json_encode($this->MFQ_Item_model->insert_item());
            }
        }
    }

    function load_mfq_itemMaster()
    {
        $mfqItemID = $this->input->post('mfqItemID');
        $mfqItemMaster = $this->MFQ_Item_model->get_srp_erp_mfq_itemmaster($mfqItemID);
        if (!empty($mfqItemMaster)) {
            echo json_encode(array_merge(array('error' => 0, 'message' => 'done'), $mfqItemMaster));
        } else {
            echo json_encode(array('error' => 1, 'message' => 'no record found!'));
        }
    }

    function fetch_link_item()
    {
        $search = $_REQUEST["sSearch"];
        $this->datatables->select('itemAutoID,itemSystemCode,itemName,seconeryItemCode,itemImage,itemDescription,mainCategoryID,mainCategory,defaultUnitOfMeasure,currentStock,companyLocalSellingPrice,companyLocalCurrency,companyLocalCurrencyDecimalPlaces,revanueDescription,costDescription,assteDescription,isActive,companyLocalWacAmount,srp_erp_itemcategory.description as SubCategoryDescription,CONCAT(currentStock,\'  \',defaultUnitOfMeasure) as CurrentStock,CONCAT(companyLocalWacAmount,\'  \',companyLocalCurrency) as TotalWacAmount', false)
            ->from('srp_erp_itemmaster')
            ->join('srp_erp_itemcategory', 'srp_erp_itemmaster.subcategoryID = srp_erp_itemcategory.itemCategoryID');
        $this->datatables->where('NOT EXISTS(SELECT * FROM srp_erp_mfq_itemmaster WHERE srp_erp_mfq_itemmaster.itemAutoID = srp_erp_itemmaster.itemAutoID AND companyID =' . $this->common_data['company_data']['company_id'] . ' )');
        $this->datatables->where('srp_erp_itemmaster.companyID', $this->common_data['company_data']['company_id']);
        if (!empty($this->input->post('mainCategory'))) {
            $this->datatables->where('mainCategoryID', $this->input->post('mainCategory'));
        } else {
            $this->datatables->where('srp_erp_itemmaster.mainCategory IN ("Inventory","Non Inventory","Service")');
        }
        if (!empty($this->input->post('subcategory'))) {
            $this->datatables->where('subcategoryID', $this->input->post('subcategory'));
        }
        if($search){
            $this->datatables->like('srp_erp_itemmaster.itemSystemCode', $search);
            $this->datatables->or_like('srp_erp_itemmaster.itemDescription', $search);
            $this->datatables->or_like('srp_erp_itemmaster.defaultUnitOfMeasure', $search);
            $this->datatables->or_like('srp_erp_itemmaster.seconeryItemCode', $search);
        }
        $this->datatables->add_column('item_inventryCode', '$1 - $2 <b></b>', 'itemSystemCode,itemDescription');
        $this->datatables->add_column('confirmed', '$1', 'confirm(isActive)');
        //$this->datatables->add_column('edit', '<input id="selectItem_$1" value="$1" type="checkbox" onclick="ItemsSelectedSync(this)">', 'itemAutoID');
        $this->datatables->add_column('edit', '<div style="text-align: center;"><div class="skin skin-square item-iCheck"> <div class="skin-section extraColumns"><input id="linkItem_$1" name="linkItem" type="radio"  value="$1" class="radioChk" data-itemAutoID="$1" ><label for="checkbox">&nbsp;</label> </div></div></div>', 'itemAutoID');
        echo $this->datatables->generate();
    }


    function link_item()
    {
        $this->form_validation->set_rules('selectedItemsSync[]', 'Item', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('e', validation_errors());
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->MFQ_Item_model->link_item());
        }
    }
    function hideshownoninventory()
    {
        $mainCategoryID = trim($this->input->post('mainCategoryID'));
        $companyid = $this->common_data['company_data']['company_id'];
        $result=$this->db->query("SELECT itemCategoryID, description,`codePrefix`,categoryTypeID FROM `srp_erp_itemcategory` WHERE
	    `masterID` IS NULL 
	    AND `companyID` = $companyid
	    AND itemCategoryID = $mainCategoryID ")->row_array(); echo json_encode($result);


    }
}
