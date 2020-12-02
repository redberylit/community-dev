<?php

class Group_Item_model extends ERP_Model
{
    function load_subcat()
    {
        $companyID=$this->common_data['company_data']['company_id'];
        //$companyGroup = $this->db->query("SELECT companyGroupID FROM srp_erp_companygroupdetails WHERE srp_erp_companygroupdetails.companyID = {$companyID}")->row_array();
        $this->db->select('itemCategoryID,description,masterID');
        $this->db->where('masterID', $this->input->post('subid'));
        $this->db->where('groupID', $companyID);
        $this->db->from('srp_erp_groupitemcategory');
        return $subcat = $this->db->get()->result_array();
    }

    function load_subsubcat()
    {
        $companyID=$this->common_data['company_data']['company_id'];
        //$companyGroup = $this->db->query("SELECT companyGroupID FROM srp_erp_companygroupdetails WHERE srp_erp_companygroupdetails.companyID = {$companyID}")->row_array();
        $this->db->select('itemCategoryID,description,masterID');
        $this->db->where('masterID', $this->input->post('subsubid'));
        $this->db->where('groupID', $companyID);
        $this->db->from('srp_erp_groupitemcategory');
        return $subsubcat = $this->db->get()->result_array();
    }

    function save_item_master()
    {
        $companyID=$this->common_data['company_data']['company_id'];
        //$companyGroup = $this->db->query("SELECT companyGroupID FROM srp_erp_companygroupdetails WHERE srp_erp_companygroupdetails.companyID = {$companyID}")->row_array();

        $this->db->trans_start();
        $mainCategory = explode('|', trim($this->input->post('mainCategory')));
        $isactive = 0;
        if (!empty($this->input->post('isActive'))) {
            $isactive = 1;
        }

        $data['isActive'] = $isactive;
        $data['secondaryItemCode'] = trim($this->input->post('seconeryItemCode'));
        $data['itemName'] = clear_descriprions(trim($this->input->post('itemName')));
        $data['itemDescription'] = clear_descriprions(trim($this->input->post('itemDescription')));
        $data['subcategoryID'] = trim($this->input->post('subcategoryID'));
        $data['subSubCategoryID'] = trim($this->input->post('subSubCategoryID'));

        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedUserName'] = $this->common_data['current_user'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];
        $data['companyLocalCurrencyID'] = $this->common_data['company_data']['company_default_currencyID'];
        $data['companyLocalCurrency'] = $this->common_data['company_data']['company_default_currency'];
        $data['companyLocalExchangeRate'] = 1;
        //$data['companyLocalSellingPrice'] = trim($this->input->post('companyLocalSellingPrice'));
        $data['companyLocalCurrencyDecimalPlaces'] = $this->common_data['company_data']['company_default_decimal'];
        $data['companyReportingCurrency'] = $this->common_data['company_data']['company_reporting_currency'];
        $data['companyReportingCurrencyID'] = $this->common_data['company_data']['company_reporting_currencyID'];
        $reporting_currency = currency_conversion($data['companyLocalCurrency'], $data['companyReportingCurrency']);
        $data['companyReportingExchangeRate'] = $reporting_currency['conversion'];
        $data['companyReportingCurrencyDecimalPlaces'] = $reporting_currency['DecimalPlaces'];
        //$data['companyReportingSellingPrice'] = ($data['companyLocalSellingPrice'] / $data['companyReportingExchangeRate']);

        if (trim($this->input->post('itemAutoID'))) {
            $itemAutoID=trim($this->input->post('itemAutoID'));
            /*$barcode = trim($this->input->post('barcode'));
            $bar=$this->db->query("SELECT * FROM `srp_erp_itemmaster` WHERE itemAutoID=$itemAutoID")->row_array();
            if ($barcode != '') {
                $data['barcode'] = $barcode;
            } else {
                $data['barcode'] = $bar['itemSystemCode'];
            }*/
            $this->db->where('itemAutoID', trim($this->input->post('itemAutoID')));
            $this->db->update('srp_erp_groupitemmaster', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('e', 'Item : ' . $data['itemSystemCode'] . ' - ' . $data['itemName'] . ' Update Failed ' . $this->db->_error_message());
                $this->db->trans_rollback();
                return array('status' => false);
            } else {
                $this->session->set_flashdata('s', 'Item : ' . $data['itemName'] . ' Updated Successfully.');
                $this->db->trans_commit();
                return array('status' => true, 'last_id' => $this->input->post('itemAutoID'));
            }
        } else {
            $uom = explode('|', trim($this->input->post('uom')));
            $this->load->library('sequence');

            $data['isActive'] = $isactive;
            $data['itemImage'] = 'no-image.png';
            $data['defaultUnitOfMeasureID'] = trim($this->input->post('defaultUnitOfMeasureID'));
            $data['defaultUnitOfMeasure'] = trim($uom[0]);
            $data['mainCategoryID'] = trim($this->input->post('mainCategoryID'));
            $data['mainCategory'] = trim($mainCategory[1]);
            $data['financeCategory'] = $this->finance_category($data['mainCategoryID']);


            $data['companyLocalWacAmount'] = 0.00;
            $data['companyReportingWacAmount'] = 0.00;
            $data['groupID'] = $companyID;
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $number = $this->db->query("SELECT IFNULL(MAX(serialNo),0) as serialNo FROM srp_erp_groupitemmaster")->row_array();
            $code = (current_companyCode() . '/'.trim($mainCategory[0]). str_pad($number["serialNo"]+1, 6, '0', STR_PAD_LEFT));
            $data['itemSystemCode'] = $code;
            $data['serialNo'] = $number["serialNo"]+1;
            //$data['itemSystemCode'] = $this->sequence->sequence_generator();
            /*$barcode = trim($this->input->post('barcode'));
            if ($barcode != '') {
                $data['barcode'] = $barcode;
            } else {
                $data['barcode'] = $data['itemSystemCode'];
            }*/
            $this->db->insert('srp_erp_groupitemmaster', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('e', 'Item : ' . $data['itemSystemCode'] . ' - ' . $data['itemName'] . ' Save Failed ' . $this->db->_error_message());
                $this->db->trans_rollback();
                return array('status' => false);
            } else {
                $this->session->set_flashdata('s', 'Item : ' . $data['itemSystemCode'] . ' - ' . $data['itemSystemCode'] . ' - ' . $data['itemName'] . ' Saved Successfully.');
                $this->db->trans_commit();
                return array('status' => true, 'last_id' => $last_id);
            }
        }
    }

    function finance_category($id)
    {
        $this->db->select('categoryTypeID');
        $this->db->where('itemCategoryID', $id);
        return $this->db->get('srp_erp_groupitemcategory')->row('categoryTypeID');
    }

    function load_item_header()
    {
        $this->db->select('*');
        $this->db->where('itemAutoID', $this->input->post('itemAutoID'));
        return $this->db->get('srp_erp_groupitemmaster')->row_array();
    }

    function save_item_link()
    {
        $companyid = $this->input->post('companyIDgrp');
        $ItemAutoID = $this->input->post('ItemAutoID');
        $com = current_companyID();
        /*$this->db->select('companyGroupID');
        $this->db->where('companyID', $com);
        $grp = $this->db->get('srp_erp_companygroupdetails')->row_array();*/
        $grpid = $com;

        $results=$this->db->delete('srp_erp_groupitemmasterdetails', array('companyGroupID' => $grpid, 'groupItemMasterID' => $this->input->post('groupItemMasterID')));

        foreach($companyid as $key => $val){
            if(!empty($ItemAutoID[$key])){
                $data['groupItemMasterID'] = trim($this->input->post('groupItemMasterID'));
                $data['ItemAutoID'] = trim($ItemAutoID[$key]);
                $data['companyID'] = trim($val);
                $data['companyGroupID'] = $grpid;

                $data['createdPCID'] = $this->common_data['current_pc'];
                $data['createdUserID'] = $this->common_data['current_userID'];
                $data['createdUserName'] = $this->common_data['current_user'];
                $data['createdDateTime'] = $this->common_data['current_date'];

                $results = $this->db->insert('srp_erp_groupitemmasterdetails', $data);
            }
            //$last_id = $this->db->insert_id();
        }

        if ($results) {
            return array('s', 'Item Link Saved Successfully');
        } else {
            return array('e', 'Item Link Save Failed');
        }
    }

    function delete_item_link()
    {
        $this->db->where('groupItemDetailID', $this->input->post('groupItemDetailID'));
        $result = $this->db->delete('srp_erp_groupitemmasterdetails');
        return array('s', 'Record Deleted Successfully');
    }

    function save_item_duplicate(){
        $companyid = $this->input->post('checkedCompanies');
        $com = current_companyID();
        $grpid = $com;
        $masterGroupID=getParentgroupMasterID();
        $results='';
        $comparr=array();
        $categoryACOAexsist=array();
        $categoryFACOAexsist=array();
        $categoryFADCCOAexsist=array();
        $categoryFADICOAexsist=array();
        $categoryFCCOAexsist=array();
        foreach($companyid as $key => $val){
            $i=0;
            $this->db->select('groupItemDetailID');
            $this->db->where('groupItemMasterID', $this->input->post('itemAutoIDDuplicatehn'));
            $this->db->where('companyID', $val);
            $this->db->where('companyGroupID', $masterGroupID);
            $linkexsist = $this->db->get('srp_erp_groupitemmasterdetails')->row_array();

            $this->db->select('*');
            $this->db->where('itemAutoID', $this->input->post('itemAutoIDDuplicatehn'));
            $CurrentCus = $this->db->get('srp_erp_groupitemmaster')->row_array();

            $this->db->select('groupItemCategoryDetailID');
            $this->db->where('groupItemCategoryID', $CurrentCus['mainCategoryID']);
            $this->db->where('companyID', $val);
            $this->db->where('companyGroupID', $masterGroupID);
            $categorylinkexsist = $this->db->get('srp_erp_groupitemcategorydetails')->row_array();

            if(empty($categorylinkexsist)){
                $i++;
                $companyName = get_companyData($val);
                $this->db->select('description');
                $this->db->where('itemCategoryID', $CurrentCus['mainCategoryID']);
                $partyDesc = $this->db->get('srp_erp_groupitemcategory')->row_array();
                array_push($comparr, array("companyname" => $companyName['company_name'], "message" => "Category not linked" ." (".$partyDesc['description'].")" ));
            }

            if(!empty($CurrentCus['revenueGLAutoID'])){
                $this->db->select('chartofAccountID');
                $this->db->where('groupChartofAccountMasterID', $CurrentCus['revenueGLAutoID']);
                $this->db->where('companyID', $val);
                $this->db->where('companyGroupID', $masterGroupID);
                $categoryRCOAexsist = $this->db->get('srp_erp_groupchartofaccountdetails')->row_array();

                if(empty($categoryRCOAexsist)){
                    $i++;
                    $companyName = get_companyData($val);
                    $this->db->select('GLSecondaryCode');
                    $this->db->where('GLAutoID', $CurrentCus['revenueGLAutoID']);
                    $glDesc = $this->db->get('srp_erp_groupchartofaccounts')->row_array();
                    array_push($comparr, array("companyname" => $companyName['company_name'], "message" => "Revenue GL not linked" ." (".$glDesc['GLSecondaryCode'].")" ));
                }
            }

            if(!empty($CurrentCus['costGLAutoID'])) {
                $this->db->select('chartofAccountID');
                $this->db->where('groupChartofAccountMasterID', $CurrentCus['costGLAutoID']);
                $this->db->where('companyID', $val);
                $this->db->where('companyGroupID', $masterGroupID);
                $categoryCCOAexsist = $this->db->get('srp_erp_groupchartofaccountdetails')->row_array();

                if (empty($categoryCCOAexsist)) {
                    $i++;
                    $companyName = get_companyData($val);
                    $this->db->select('GLSecondaryCode');
                    $this->db->where('GLAutoID', $CurrentCus['costGLAutoID']);
                    $glDesc = $this->db->get('srp_erp_groupchartofaccounts')->row_array();
                    array_push($comparr, array("companyname" => $companyName['company_name'], "message" => "Cost GL not linked" . " (" . $glDesc['GLSecondaryCode'] . ")"));
                }
            }

            if(!empty($CurrentCus['assetGLAutoID'])) {
                $this->db->select('chartofAccountID');
                $this->db->where('groupChartofAccountMasterID', $CurrentCus['assetGLAutoID']);
                $this->db->where('companyID', $val);
                $this->db->where('companyGroupID', $masterGroupID);
                $categoryACOAexsist = $this->db->get('srp_erp_groupchartofaccountdetails')->row_array();

                if (empty($categoryACOAexsist)) {
                    $i++;
                    $companyName = get_companyData($val);
                    $this->db->select('GLSecondaryCode');
                    $this->db->where('GLAutoID', $CurrentCus['assetGLAutoID']);
                    $glDesc = $this->db->get('srp_erp_groupchartofaccounts')->row_array();
                    array_push($comparr, array("companyname" => $companyName['company_name'], "message" => "Asset GL not linked" . " (" . $glDesc['GLSecondaryCode'] . ")"));
                }
            }

            if(!empty($CurrentCus['faCostGLAutoID'])) {
                $this->db->select('chartofAccountID');
                $this->db->where('groupChartofAccountMasterID', $CurrentCus['faCostGLAutoID']);
                $this->db->where('companyID', $val);
                $this->db->where('companyGroupID', $masterGroupID);
                $categoryFCCOAexsist = $this->db->get('srp_erp_groupchartofaccountdetails')->row_array();

                if (empty($categoryFCCOAexsist)) {
                    $i++;
                    $companyName = get_companyData($val);
                    $this->db->select('GLSecondaryCode');
                    $this->db->where('GLAutoID', $CurrentCus['faCostGLAutoID']);
                    $glDesc = $this->db->get('srp_erp_groupchartofaccounts')->row_array();
                    array_push($comparr, array("companyname" => $companyName['company_name'], "message" => "Fix Asset Cost GL not linked" . " (" . $glDesc['GLSecondaryCode'] . ")"));
                }
            }

            if(!empty($CurrentCus['faACCDEPGLAutoID'])) {
                $this->db->select('chartofAccountID');
                $this->db->where('groupChartofAccountMasterID', $CurrentCus['faACCDEPGLAutoID']);
                $this->db->where('companyID', $val);
                $this->db->where('companyGroupID', $masterGroupID);
                $categoryFACOAexsist = $this->db->get('srp_erp_groupchartofaccountdetails')->row_array();

                if (empty($categoryFACOAexsist)) {
                    $i++;
                    $companyName = get_companyData($val);
                    $this->db->select('GLSecondaryCode');
                    $this->db->where('GLAutoID', $CurrentCus['faACCDEPGLAutoID']);
                    $glDesc = $this->db->get('srp_erp_groupchartofaccounts')->row_array();
                    array_push($comparr, array("companyname" => $companyName['company_name'], "message" => "Fix Asset Accumulated Depreciation GL not linked" . " (" . $glDesc['GLSecondaryCode'] . ")"));
                }
            }

            if(!empty($CurrentCus['faDEPGLAutoID'])) {
                $this->db->select('chartofAccountID');
                $this->db->where('groupChartofAccountMasterID', $CurrentCus['faDEPGLAutoID']);
                $this->db->where('companyID', $val);
                $this->db->where('companyGroupID', $masterGroupID);
                $categoryFADCCOAexsist = $this->db->get('srp_erp_groupchartofaccountdetails')->row_array();

                if (empty($categoryFADCCOAexsist)) {
                    $i++;
                    $companyName = get_companyData($val);
                    $this->db->select('GLSecondaryCode');
                    $this->db->where('GLAutoID', $CurrentCus['faDEPGLAutoID']);
                    $glDesc = $this->db->get('srp_erp_groupchartofaccounts')->row_array();
                    array_push($comparr, array("companyname" => $companyName['company_name'], "message" => "Fix Asset Depreciation GL not linked" . " (" . $glDesc['GLSecondaryCode'] . ")"));
                }
            }

            if(!empty($CurrentCus['faDISPOGLAutoID'])) {
                $this->db->select('chartofAccountID');
                $this->db->where('groupChartofAccountMasterID', $CurrentCus['faDISPOGLAutoID']);
                $this->db->where('companyID', $val);
                $this->db->where('companyGroupID', $masterGroupID);
                $categoryFADICOAexsist = $this->db->get('srp_erp_groupchartofaccountdetails')->row_array();

                if (empty($categoryFADICOAexsist)) {
                    $i++;
                    $companyName = get_companyData($val);
                    $this->db->select('GLSecondaryCode');
                    $this->db->where('GLAutoID', $CurrentCus['faDISPOGLAutoID']);
                    $glDesc = $this->db->get('srp_erp_groupchartofaccounts')->row_array();
                    array_push($comparr, array("companyname" => $companyName['company_name'], "message" => "Fix Asset Disposal GL not linked" . " (" . $glDesc['GLSecondaryCode'] . ")"));
                }
            }

            if(!empty($CurrentCus['mainCategoryID'])) {
                $this->db->select('itemCategoryID');
                $this->db->where('groupItemCategoryID', $CurrentCus['mainCategoryID']);
                $this->db->where('companyID', $val);
                $this->db->where('companyGroupID', $masterGroupID);
                $categoryMainexsist = $this->db->get('srp_erp_groupitemcategorydetails')->row_array();

                if (empty($categoryMainexsist)) {
                    $i++;
                    $companyName = get_companyData($val);
                    $this->db->select('description');
                    $this->db->where('itemCategoryID', $CurrentCus['mainCategoryID']);
                    $glDesc = $this->db->get('srp_erp_groupitemcategory')->row_array();
                    array_push($comparr, array("companyname" => $companyName['company_name'], "message" => "Category not linked" . " (" . $glDesc['description'] . ")"));
                }
            }

            if(!empty($CurrentCus['subcategoryID'])) {
                $this->db->select('itemCategoryID');
                $this->db->where('groupItemCategoryID', $CurrentCus['subcategoryID']);
                $this->db->where('companyID', $val);
                $this->db->where('companyGroupID', $masterGroupID);
                $categorySubexsist = $this->db->get('srp_erp_groupitemcategorydetails')->row_array();
                if (empty($categorySubexsist)) {
                    $i++;
                    $companyName = get_companyData($val);
                    $this->db->select('description');
                    $this->db->where('itemCategoryID', $CurrentCus['subcategoryID']);
                    $glDesc = $this->db->get('srp_erp_groupitemcategory')->row_array();
                    array_push($comparr, array("companyname" => $companyName['company_name'], "message" => "Sub Category not linked" . " (" . $glDesc['description'] . ")"));
                }
            }

            if(!empty($CurrentCus['subSubCategoryID'])) {
                $this->db->select('itemCategoryID');
                $this->db->where('groupItemCategoryID', $CurrentCus['subSubCategoryID']);
                $this->db->where('companyID', $val);
                $this->db->where('companyGroupID', $masterGroupID);
                $categorySubSubexsist = $this->db->get('srp_erp_groupitemcategorydetails')->row_array();

                if (empty($categorySubSubexsist)) {
                    $i++;
                    $companyName = get_companyData($val);
                    $this->db->select('description');
                    $this->db->where('itemCategoryID', $CurrentCus['subSubCategoryID']);
                    $glDesc = $this->db->get('srp_erp_groupitemcategory')->row_array();
                    array_push($comparr, array("companyname" => $companyName['company_name'], "message" => "Sub Sub Category not linked" . " (" . $glDesc['description'] . ")"));
                }
            }

            if(!empty($CurrentCus['defaultUnitOfMeasureID'])) {
                $this->db->select('UOMMasterID');
                $this->db->where('groupUOMMasterID', $CurrentCus['defaultUnitOfMeasureID']);
                $this->db->where('companyID', $val);
                $this->db->where('companyGroupID', $masterGroupID);
                $uomexsist = $this->db->get('srp_erp_groupuomdetails')->row_array();

                if (empty($uomexsist)) {
                    $i++;
                    $companyName = get_companyData($val);
                    $this->db->select('UnitDes');
                    $this->db->where('UnitID', $CurrentCus['defaultUnitOfMeasureID']);
                    $unitDesc = $this->db->get('srp_erp_group_unit_of_measure')->row_array();
                    array_push($comparr, array("companyname" => $companyName['company_name'], "message" => "UOM not linked" . " (" . $unitDesc['UnitDes'] . ")"));
                }
            }



            $this->db->select('itemAutoID');
            $this->db->where('itemName', $CurrentCus['itemName']);
            $this->db->where('companyID', $val);
            $CurrentCOAexsist = $this->db->get('srp_erp_itemmaster')->row_array();

            if (!empty($CurrentCOAexsist)) {
                $i++;
                $companyName = get_companyData($val);

                array_push($comparr, array("companyname" => $companyName['company_name'], "message" => "Item name already exist" . " (" . $CurrentCus['itemName'] . ")"));
            }

            if($i==0){
                if(empty($linkexsist) && empty($CurrentCOAexsist)){
                    $this->db->select('company_code,company_default_currencyID,company_default_currency,company_default_decimal,company_reporting_currencyID,company_reporting_currency,company_reporting_decimal');
                    $this->db->where('company_id', $val);
                    $compDetails = $this->db->get('srp_erp_company')->row_array();

                    $this->db->select('description,codePrefix');
                    $this->db->where('itemCategoryID', $categoryMainexsist['itemCategoryID']);
                    $mainCatdet = $this->db->get('srp_erp_itemcategory')->row_array();
                    $data['isActive'] = $CurrentCus['isActive'];
                    $data['seconeryItemCode'] = $CurrentCus['secondaryItemCode'];
                    $data['itemName'] = $CurrentCus['itemName'];
                    $data['itemDescription'] = $CurrentCus['itemDescription'];
                    $data['subcategoryID'] = $categorySubexsist['itemCategoryID'];
                    if(!empty($categorySubSubexsist['itemCategoryID'])) {
                        $data['subSubCategoryID'] = $categorySubSubexsist['itemCategoryID'];
                    }
                    $data['partNo'] = $CurrentCus['partNo'];
                    $data['reorderPoint'] = $CurrentCus['partNo'];
                    $data['maximunQty'] = $CurrentCus['maximunQty'];
                    $data['minimumQty'] = $CurrentCus['minimumQty'];
                    $data['comments'] = $CurrentCus['comments'];
                    $data['modifiedPCID'] = $this->common_data['current_pc'];
                    $data['modifiedUserID'] = $this->common_data['current_userID'];
                    $data['modifiedUserName'] = $this->common_data['current_user'];
                    $data['modifiedDateTime'] = $this->common_data['current_date'];
                    $data['companyLocalCurrencyID'] = $compDetails['company_default_currencyID'];
                    $data['companyLocalCurrency'] = $compDetails['company_default_currency'];
                    $data['companyLocalExchangeRate'] = 1;
                    $data['companyLocalSellingPrice'] = $CurrentCus['companyLocalSellingPrice'];
                    $data['companyLocalCurrencyDecimalPlaces'] = $compDetails['company_default_decimal'];
                    $data['companyReportingCurrency'] = $compDetails['company_reporting_currency'];
                    $data['companyReportingCurrencyID'] = $compDetails['company_reporting_currencyID'];
                    $reporting_currency = currency_conversion($data['companyLocalCurrency'], $data['companyReportingCurrency']);
                    $data['companyReportingExchangeRate'] = $reporting_currency['conversion'];
                    $data['companyReportingCurrencyDecimalPlaces'] = $compDetails['company_reporting_decimal'];
                    $data['companyReportingSellingPrice'] = $CurrentCus['companyReportingSellingPrice'];
                    $data['isSubitemExist'] = $CurrentCus['isSubitemExist'];
                    if(!empty($categoryACOAexsist['chartofAccountID'])){
                        $data['assteGLAutoID'] = $categoryACOAexsist['chartofAccountID'];
                    }
                    if(!empty($categoryFCCOAexsist['chartofAccountID'])){
                        $data['faCostGLAutoID'] = $categoryFCCOAexsist['chartofAccountID'];
                    }
                    if(!empty($categoryFACOAexsist['chartofAccountID'])){
                        $data['faACCDEPGLAutoID'] = $categoryFACOAexsist['chartofAccountID'];
                    }
                    if(!empty($categoryFADCCOAexsist['chartofAccountID'])){
                        $data['faDEPGLAutoID'] = $categoryFADCCOAexsist['chartofAccountID'];
                    }
                    if(!empty($categoryFADICOAexsist['chartofAccountID'])){
                        $data['faDISPOGLAutoID'] = $categoryFADICOAexsist['chartofAccountID'];
                    }
                    if(!empty($categoryCCOAexsist['chartofAccountID'])){
                        $data['costGLAutoID'] = $categoryCCOAexsist['chartofAccountID'];
                        $costglDet=fetch_gl_account_desc($categoryCCOAexsist['chartofAccountID']);
                        $data['costSystemGLCode'] = $costglDet['systemAccountCode'];
                        $data['costGLCode'] = $costglDet['GLSecondaryCode'];
                        $data['costDescription'] = $costglDet['GLDescription'];
                        $data['costType'] = $costglDet['subCategory'];
                    }

                    if(!empty($categoryRCOAexsist['chartofAccountID'])) {
                        $data['revanueGLAutoID'] = $categoryRCOAexsist['chartofAccountID'];
                        $revglDet = fetch_gl_account_desc($categoryRCOAexsist['chartofAccountID']);
                        $data['revanueSystemGLCode'] = $revglDet['systemAccountCode'];
                        $data['revanueGLCode'] = $revglDet['GLSecondaryCode'];
                        $data['revanueDescription'] = $revglDet['GLDescription'];
                        $data['revanueType'] = $revglDet['subCategory'];
                    }
                    /*if(!empty($categoryRCOAexsist['chartofAccountID'])) {
                        $data['stockAdjustmentGLAutoID'] = $categoryRCOAexsist['chartofAccountID'];
                        $stkglDet = fetch_gl_account_desc($categoryRCOAexsist['chartofAccountID']);
                        $data['stockAdjustmentSystemGLCode'] = $stkglDet['systemAccountCode'];
                        $data['stockAdjustmentGLCode'] = $stkglDet['systemAccountCode'];
                        $data['stockAdjustmentDescription'] = $stkglDet['systemAccountCode'];
                        $data['stockAdjustmentType'] = $stkglDet['systemAccountCode'];
                    }*/
                    if(!empty($categoryACOAexsist['chartofAccountID'])) {
                        $data['assteGLAutoID'] = $categoryACOAexsist['chartofAccountID'];
                        $astglDet = fetch_gl_account_desc($categoryRCOAexsist['chartofAccountID']);
                        $data['assteSystemGLCode'] = $astglDet['systemAccountCode'];
                        $data['assteGLCode'] = $astglDet['GLSecondaryCode'];
                        $data['assteDescription'] = $astglDet['GLDescription'];
                        $data['assteType'] = $astglDet['subCategory'];
                    }
                    $this->db->SELECT("UnitID,UnitDes,UnitShortCode");
                    $this->db->FROM('srp_erp_unit_of_measure');
                    $this->db->WHERE('UnitID', $uomexsist['UOMMasterID']);
                    $units = $this->db->get()->row_array();

                    $data['barcode'] = $CurrentCus['barcode'];
                    $data['itemImage'] = 'no-image.png';
                    $data['defaultUnitOfMeasureID'] = $uomexsist['UOMMasterID'];
                    $data['defaultUnitOfMeasure'] = $units['UnitShortCode'];
                    $data['mainCategoryID'] = $categoryMainexsist['itemCategoryID'];
                    $data['mainCategory'] = trim($mainCatdet['description']);
                    $data['financeCategory'] = $this->finance_category($data['mainCategoryID']);

                    $data['companyLocalWacAmount'] = 0.00;
                    $data['companyReportingWacAmount'] = 0.00;
                    $data['companyID'] = $val;
                    $companyCode = get_companyData($val);
                    $data['companyCode'] = $companyCode['company_code'];
                    $data['createdUserGroup'] = $this->common_data['user_group'];
                    $data['createdPCID'] = $this->common_data['current_pc'];
                    $data['createdUserID'] = $this->common_data['current_userID'];
                    $data['createdUserName'] = $this->common_data['current_user'];
                    $data['createdDateTime'] = $this->common_data['current_date'];
                    $this->load->library('sequence');

                    //$data['itemSystemCode'] = $this->sequence->sequence_generator(trim($mainCatdet['codePrefix']));
                    $data['itemSystemCode'] = $this->sequence->sequence_generator_group(trim($mainCatdet['codePrefix']), 0, $val, $companyCode['company_code']);
                    $this->db->insert('srp_erp_itemmaster', $data);
                    $last_id = $this->db->insert_id();


                    $dataLink['groupItemMasterID'] = trim($this->input->post('itemAutoIDDuplicatehn'));
                    $dataLink['ItemAutoID'] = trim($last_id);
                    $dataLink['companyID'] = trim($val);
                    $dataLink['companyGroupID'] = $masterGroupID;

                    $dataLink['createdPCID'] = $this->common_data['current_pc'];
                    $dataLink['createdUserID'] = $this->common_data['current_userID'];
                    $dataLink['createdUserName'] = $this->common_data['current_user'];
                    $dataLink['createdDateTime'] = $this->common_data['current_date'];

                    $results = $this->db->insert('srp_erp_groupitemmasterdetails', $dataLink);

                }
            }else{
                continue;
            }

        }

        if ($results) {
            return array('s', 'Item Replicated Successfully',$comparr);
        } else {
            return array('e', 'Item Replication not successful',$comparr);
        }

    }


}
