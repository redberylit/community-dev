<?php

class MFQ_Item_model extends ERP_Model
{
    function save_item_master()
    {
        $this->db->trans_start();
        if (!empty(trim($this->input->post('revanue')) && trim($this->input->post('revanue') != 'Select Revenue GL Account'))) {
            $revanue = explode('|', trim($this->input->post('revanue')));
        }
        $cost = explode('|', trim($this->input->post('cost')));
        $asste = explode('|', trim($this->input->post('asste')));
        $mainCategory = explode('|', trim($this->input->post('mainCategory')));
        $isactive = 0;
        if (!empty($this->input->post('isActive'))) {
            $isactive = 1;
        }

        $data['isActive'] = $isactive;
        $data['seconeryItemCode'] = trim($this->input->post('seconeryItemCode'));
        $data['itemName'] = clear_descriprions(trim($this->input->post('itemName')));
        $data['itemDescription'] = clear_descriprions(trim($this->input->post('itemDescription')));
        $data['subcategoryID'] = trim($this->input->post('subcategoryID'));
        $data['subSubCategoryID'] = trim($this->input->post('subSubCategoryID'));
        $data['partNo'] = trim($this->input->post('partno'));
        $data['reorderPoint'] = trim($this->input->post('reorderPoint'));
        $data['maximunQty'] = trim($this->input->post('maximunQty'));
        $data['minimumQty'] = trim($this->input->post('minimumQty'));
        $data['barcode'] = trim($this->input->post('barcode'));
        $data['comments'] = trim($this->input->post('comments'));
        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedUserName'] = $this->common_data['current_user'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];
        $data['companyLocalCurrencyID'] = $this->common_data['company_data']['company_default_currencyID'];
        $data['companyLocalCurrency'] = $this->common_data['company_data']['company_default_currency'];
        $data['companyLocalExchangeRate'] = 1;
        $data['companyLocalSellingPrice'] = trim($this->input->post('companyLocalSellingPrice'));
        $data['companyLocalCurrencyDecimalPlaces'] = $this->common_data['company_data']['company_default_decimal'];
        $data['companyReportingCurrency'] = $this->common_data['company_data']['company_reporting_currency'];
        $data['companyReportingCurrencyID'] = $this->common_data['company_data']['company_reporting_currencyID'];
        $reporting_currency = currency_conversion($data['companyLocalCurrency'], $data['companyReportingCurrency']);
        $data['companyReportingExchangeRate'] = $reporting_currency['conversion'];
        $data['companyReportingCurrencyDecimalPlaces'] = $reporting_currency['DecimalPlaces'];
        $data['companyReportingSellingPrice'] = ($data['companyLocalSellingPrice'] / $data['companyReportingExchangeRate']);

        if (trim($this->input->post('itemAutoID'))) {
            $this->db->where('itemAutoID', trim($this->input->post('itemAutoID')));
            $this->db->update('srp_erp_itemmaster', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('e', 'Item : ' . $data['itemSystemCode'] . ' - ' . $data['itemName'] . ' Update Failed ' . $this->db->_error_message());
                $this->db->trans_rollback();
                //$this->lib_log->log_event('Item','Error','Item : ' .$data['itemSystemCode'].' - '. $data['itemName'] . ' Update Failed '.$this->db->_error_message(),'Item');
                return array('status' => false);
            } else {
                $this->session->set_flashdata('s', 'Item : ' . $data['itemName'] . ' Updated Successfully.');
                $this->db->trans_commit();
                //$this->lib_log->log_event('Item','Success','Item : ' . $data['companyCode'].' Update Successfully. Affected Rows - ' . $this->db->affected_rows(),'Item');
                return array('status' => true, 'last_id' => $this->input->post('itemAutoID'));
            }
        } else {
            $uom = explode('|', trim($this->input->post('uom')));
            $this->load->library('sequence');
            // $this->db->select('codePrefix');
            // $this->db->where('itemCategoryID', $this->input->post('mainCategoryID'));
            // $code = $this->db->get('srp_erp_itemcategory')->row_array();
            $data['isActive'] = $isactive;
            $data['itemImage'] = 'no-image.png';
            $data['defaultUnitOfMeasureID'] = trim($this->input->post('defaultUnitOfMeasureID'));
            $data['defaultUnitOfMeasure'] = trim($uom[0]);
            $data['mainCategoryID'] = trim($this->input->post('mainCategoryID'));
            $data['mainCategory'] = trim($mainCategory[1]);
            $data['financeCategory'] = $this->finance_category($data['mainCategoryID']);
            $data['assteGLAutoID'] = trim($this->input->post('assteGLAutoID'));
            $data['faCostGLAutoID'] = trim($this->input->post('COSTGLCODEdes'));
            $data['faACCDEPGLAutoID'] = trim($this->input->post('ACCDEPGLCODEdes'));
            $data['faDEPGLAutoID'] = trim($this->input->post('DEPGLCODEdes'));
            $data['faDISPOGLAutoID'] = trim($this->input->post('DISPOGLCODEdes'));

            if ($data['mainCategory'] == 'Fixed Assets') {
                $data['assteGLAutoID'] = trim($this->input->post('assteGLAutoID'));
                /*                $data['assteSystemGLCode']            = trim($asste[0]);
                                $data['assteGLCode']                  = trim($asste[1]);
                                $data['assteDescription']             = trim($asste[2]);
                                $data['assteType']                    = trim($asste[3]);
                                $data['revanueGLAutoID']              = trim($this->input->post('revanueGLAutoID'));
                                $data['revanueSystemGLCode']          = trim($revanue[0]);
                                $data['revanueGLCode']                = trim($revanue[1]);
                                $data['revanueDescription']           = trim($revanue[2]);
                                $data['revanueType']                  = trim($revanue[3]);*/
                $data['faCostGLAutoID'] = trim($this->input->post('COSTGLCODEdes'));
                $data['faACCDEPGLAutoID'] = trim($this->input->post('ACCDEPGLCODEdes'));
                $data['faDEPGLAutoID'] = trim($this->input->post('DEPGLCODEdes'));
                $data['faDISPOGLAutoID'] = trim($this->input->post('DISPOGLCODEdes'));

                $data['costGLAutoID'] = '';
                $data['costSystemGLCode'] = '';
                $data['costGLCode'] = '';
                $data['costDescription'] = '';
                $data['costType'] = '';
            } elseif ($data['mainCategory'] == 'Service' or $data['mainCategory'] == 'Non Inventory') {
                $data['assteGLAutoID'] = '';
                $data['assteSystemGLCode'] = '';
                $data['assteGLCode'] = '';
                $data['assteDescription'] = '';
                $data['assteType'] = '';
                $data['revanueGLAutoID'] = trim($this->input->post('revanueGLAutoID'));
                if (!empty($revanue)) {
                    $data['revanueSystemGLCode'] = trim($revanue[0]);
                    $data['revanueGLCode'] = trim($revanue[1]);
                    $data['revanueDescription'] = trim($revanue[2]);
                    $data['revanueType'] = trim($revanue[3]);
                }
                $data['costGLAutoID'] = trim($this->input->post('costGLAutoID'));
                $data['costSystemGLCode'] = trim($cost[0]);
                $data['costGLCode'] = trim($cost[1]);
                $data['costDescription'] = trim($cost[2]);
                $data['costType'] = trim($cost[3]);
            } else {
                $data['assteGLAutoID'] = trim($this->input->post('assteGLAutoID'));
                $data['assteSystemGLCode'] = trim($asste[0]);
                $data['assteGLCode'] = trim($asste[1]);
                $data['assteDescription'] = trim($asste[2]);
                $data['assteType'] = trim($asste[3]);
                $data['revanueGLAutoID'] = trim($this->input->post('revanueGLAutoID'));
                if (!empty($revanue)) {
                    $data['revanueSystemGLCode'] = trim($revanue[0]);
                    $data['revanueGLCode'] = trim($revanue[1]);
                    $data['revanueDescription'] = trim($revanue[2]);
                    $data['revanueType'] = trim($revanue[3]);
                }
                $data['costGLAutoID'] = trim($this->input->post('costGLAutoID'));
                $data['costSystemGLCode'] = trim($cost[0]);
                $data['costGLCode'] = trim($cost[1]);
                $data['costDescription'] = trim($cost[2]);
                $data['costType'] = trim($cost[3]);
            }
            $data['companyLocalWacAmount'] = 0.00;
            $data['companyReportingWacAmount'] = 0.00;
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $data['itemSystemCode'] = $this->sequence->sequence_generator(trim($mainCategory[0]));
            $this->db->insert('srp_erp_itemmaster', $data);
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

    function item_image_upload()
    {
        $this->db->trans_start();
        $output_dir = "uploads/itemMaster/";
        if (!file_exists($output_dir)) {
            mkdir("uploads/itemMaster/", 007);
        }
        $attachment_file = $_FILES["files"];
        $info = new SplFileInfo($_FILES["files"]["name"]);
        $fileName = 'Item_' . trim($this->input->post('faID')) . '.' . $info->getExtension();
        move_uploaded_file($_FILES["files"]["tmp_name"], $output_dir . $fileName);

        $data['itemimage'] = $fileName;

        $this->db->where('itemAutoID', trim($this->input->post('faID')));
        $this->db->update('srp_erp_itemmaster', $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('e', "Image Upload Failed." . $this->db->_error_message());
            $this->db->trans_rollback();
            return array('status' => false);
        } else {
            $this->session->set_flashdata('s', 'Image uploaded  Successfully.');
            $this->db->trans_commit();
            return array('status' => true, 'last_id' => trim($this->input->post('faID')));
        }
    }

    function finance_category($id)
    {
        $this->db->select('categoryTypeID');
        $this->db->where('itemCategoryID', $id);
        return $this->db->get('srp_erp_itemcategory')->row('categoryTypeID');
    }


    function load_item_header()
    {
        $this->db->select('*');
        $this->db->where('itemAutoID', $this->input->post('itemAutoID'));
        return $this->db->get('srp_erp_itemmaster')->row_array();
    }

    function delete_item()
    {
        $this->db->where('itemAutoID', $this->input->post('itemAutoID'));
        $this->db->delete('srp_erp_itemmaster');
        $this->session->set_flashdata('s', 'Item Deleted Successfully');
        return true;
    }

    function load_subcat()
    {
        $this->db->select('itemCategoryID,description,masterID');
        $this->db->where('masterID', $this->input->post('subid'));
        $this->db->where('companyID', $this->common_data['company_data']['company_id']);
        $this->db->from('srp_erp_itemcategory');
        return $subcat = $this->db->get()->result_array();
    }

    function load_subsubcat()
    {
        $this->db->select('itemCategoryID,description,masterID');
        $this->db->where('masterID', $this->input->post('subsubid'));
        $this->db->where('companyID', $this->common_data['company_data']['company_id']);
        $this->db->from('srp_erp_itemcategory');
        return $subsubcat = $this->db->get()->result_array();
    }

    function edit_item()
    {
        $this->db->select('*');
        $this->db->where('itemAutoID', $this->input->post('id'));
        $this->db->where('companyID', $this->common_data['company_data']['company_id']);
        return $this->db->get('srp_erp_itemmaster')->row_array();
    }


    function item_master_img_uplode()
    {
        $output_dir = "uploads/itemmaster/";
        $itemAutoID = trim($this->input->post('image_itemmaster_hn'));
        $attachment_file = $_FILES["img_file"];
        $info = new SplFileInfo($_FILES["img_file"]["name"]);
        $fileName = 'ITM' . "_" . $itemAutoID . '.' . $info->getExtension();
        move_uploaded_file($_FILES["img_file"]["tmp_name"], $output_dir . $fileName);
        $this->db->where('itemAutoID', $itemAutoID);
        $result = $this->db->update('srp_erp_itemmaster', array('itemImage' => $output_dir . $fileName));
        if ($result) {
            $this->session->set_flashdata('s', 'Image Uploaded Successfully');
            return true;
        }
    }

    function img_uplode()
    {
        $output_dir = "images/item/";
        $attachment_file = ($_FILES["img_file"] ? $_FILES["img_file"] : 'no-image.png');
        $info = new SplFileInfo($_FILES["img_file"]["name"]);
        $fileName = trim($this->input->post('item_id')) . '.' . $info->getExtension();
        move_uploaded_file($_FILES["img_file"]["tmp_name"], $output_dir . $fileName);
        $this->db->where('itemAutoID', trim($this->input->post('item_id')));
        $this->db->update('srp_erp_itemmaster', array('itemImage' => $fileName));
        return array('status' => true);
    }

    function load_gl_codes()
    {
        $this->db->select('revenueGL,costGL,assetGL,faCostGLAutoID,faACCDEPGLAutoID,faDEPGLAutoID,faDISPOGLAutoID');
        $this->db->where('itemCategoryID', $this->input->post('itemCategoryID'));
        return $this->db->get('srp_erp_itemcategory')->row_array();
    }

    function changeitemactive()
    {

        $data['isActive'] = ($this->input->post('chkedvalue'));
        $this->db->where('itemAutoID', $this->input->post('itemAutoID'));
        $result = $this->db->update('srp_erp_itemmaster', $data);
        if ($result) {
            $this->session->set_flashdata('s', 'Records Updated Successfully');
            return true;
        }
    }

    function load_category_type_id()
    {
        $this->db->select('itemCategoryID,categoryTypeID');
        $this->db->where('itemCategoryID', $this->input->post('itemCategoryID'));
        return $this->db->get('srp_erp_itemcategory')->row_array();
    }

    function load_unitprice_exchangerate()
    {

        $localwacAmount = trim($this->input->post('LocalWacAmount'));
        $this->db->select('purchaseOrderID,transactionCurrencyID,transactionExchangeRate,transactionCurrency,companyLocalCurrency,transactionCurrencyDecimalPlaces');
        $this->db->where('purchaseOrderID', $this->input->post('poID'));
        $result = $this->db->get('srp_erp_purchaseordermaster')->row_array();

        $localCurrency = currency_conversion($result['companyLocalCurrency'], $result['transactionCurrency']);

        $unitprice = round(($localwacAmount / $localCurrency['conversion']), $result['transactionCurrencyDecimalPlaces']);

        return array('status' => true, 'amount' => $unitprice);
    }

    function add_item()
    {
        $result = $this->db->query('INSERT INTO srp_erp_mfq_itemmaster (
                                    itemAutoID, categoryType, itemSystemCode, secondaryItemCode, itemImage,
                                    itemName, itemDescription, mainCategoryID,mainCategory,subcategoryID, subSubCategoryID, 
                                    itemUrl, barcode, financeCategory, partNo,
                                    defaultUnitOfMeasureID, defaultUnitOfMeasure, currentStock, reorderPoint,
                                    maximunQty, minimumQty, revenueGLAutoID, revenueSystemGLCode, revenueGLCode,
                                    revenueDescription, revenueType, costGLAutoID, costSystemGLCode, costGLCode,
                                    costDescription, costType, assetGLAutoID, assetSystemGLCode, assetGLCode, assetDescription,
                                    assetType, faCostGLAutoID, faACCDEPGLAutoID, faDEPGLAutoID, faDISPOGLAutoID,
                                    isActive, comments, companyLocalCurrencyID, companyLocalCurrency, companyLocalExchangeRate,
                                    companyLocalSellingPrice, companyLocalWacAmount, companyLocalCurrencyDecimalPlaces,
                                    companyReportingCurrencyID, companyReportingCurrency, companyID, companyCode
                                ) SELECT
                                
                                 itemAutoID, IF ( mainCategory = "Inventory" OR mainCategoryID = "Non Inventory", 1,
                                 IF ( mainCategory = "Service", 2, NULL ) ) AS categoryType,
                                 itemSystemCode, seconeryItemCode, itemImage, itemName,
                                 itemDescription, mainCategoryID,mainCategory,subcategoryID, subSubCategoryID, 
                                 itemUrl, barcode, financeCategory,
                                 partNo, defaultUnitOfMeasureID, defaultUnitOfMeasure,
                                 currentStock, reorderPoint, maximunQty, minimumQty,
                                 revanueGLAutoID, revanueSystemGLCode, revanueGLCode,
                                 revanueDescription, revanueType, costGLAutoID, costSystemGLCode, costGLCode,
                                 costDescription, costType, assteGLAutoID, assteSystemGLCode,
                                 assteGLCode, assteDescription, assteType, faCostGLAutoID, faACCDEPGLAutoID,
                                 faDEPGLAutoID, faDISPOGLAutoID, isActive, comments, companyLocalCurrencyID,
                                 companyLocalCurrency, companyLocalExchangeRate, companyLocalSellingPrice,
                                 companyLocalWacAmount, companyLocalCurrencyDecimalPlaces, companyReportingCurrencyID,
                                 companyReportingCurrency, companyID, companyCode
                                FROM
                                    srp_erp_itemmaster WHERE companyID = ' . $this->common_data['company_data']['company_id'] . ' AND itemAutoID IN(' . join(",", $this->input->post('selectedItemsSync')) . ')');

        $result2 = $this->db->query('INSERT INTO srp_erp_mfq_overhead (erpitemAutoID,overHeadCode,description,unitOfMeasureID,financeGLAutoID,companyID) SELECT itemAutoID,itemSystemCode,itemDescription,defaultUnitOfMeasureID,costGLAutoID,companyID FROM srp_erp_itemmaster WHERE companyID = ' . $this->common_data['company_data']['company_id'] . ' AND itemAutoID IN(' . join(",", $this->input->post('selectedItemsSync')) . ') AND mainCategory="Service"');
        if ($result && $result2) {
            $this->session->set_flashdata('s', 'Records added Successfully');
            return array('status' => true);
        }
    }

    function fetch_itemrecord()
    {

        $companyCode = $this->common_data['company_data']['company_id'];
        $search_string = "%" . $_GET['q'] . "%";
        return $this->db->query('SELECT mainCategoryID,subcategoryID,seconeryItemCode,subSubCategoryID,revanueGLCode,itemSystemCode,costGLCode,assteGLCode,defaultUnitOfMeasure,defaultUnitOfMeasureID,itemDescription,itemAutoID,currentStock,companyLocalWacAmount,companyLocalSellingPrice,CONCAT(itemDescription, " (" ,itemSystemCode,")") AS "Match",companyLocalCurrencyID FROM srp_erp_mfq_itemmaster WHERE (itemSystemCode LIKE "' . $search_string . '" OR itemDescription LIKE "' . $search_string . '" OR seconeryItemCode LIKE "' . $search_string . '") AND companyID = "' . $companyCode . '" AND isActive="1"')->result_array();
    }

    function get_mfq_childCategory($categoryID)
    {
        $this->db->select('*');
        $this->db->from('srp_erp_mfq_category');
        $this->db->where('masterID', $categoryID);
        $r = $this->db->get()->result_array();
        return $r;
    }

    function update_srp_erp_mfq_itemmaster($id, $data = array())
    {

        $this->db->where('mfqItemID', $id);
        $result = $this->db->update('srp_erp_mfq_itemmaster', $data);
        return $result;

    }

    function insert_item()
    {
        $maincatid = $this->input->post('mainCategoryID');
        $companyid = $this->common_data['company_data']['company_id'];
        $resultcat = $this->db->query("SELECT categoryTypeID FROM `srp_erp_itemcategory` 
         WHERE
	    `masterID` IS NULL 
	    AND `companyID` = $companyid
	    AND itemCategoryID = $maincatid ")->row_array();

        $this->db->select('*');
        $this->db->from('srp_erp_mfq_itemmaster');
        $this->db->where('itemName', trim($this->input->post('itemName')));
        $this->db->where('companyID', current_companyID());
        $item = $this->db->get()->row_array();

        if (!$item) {
            $post = $this->input->post();
            unset($post['mfqItemID']);
            $codes = generateMFQ_SystemCode('srp_erp_mfq_itemmaster', 'mfqItemID', 'companyID');
            $uom = explode('|', trim($this->input->post('defaultUnitOfMeasure')));
            $mainCategory = explode('|', trim($this->input->post('mainCategory')));
            $datetime = format_date_mysql_datetime();
            $post['serialNo'] = $codes['serialNo'];
            $post['itemSystemCode'] = $codes['systemCode'];
            $post['itemDescription'] = $this->input->post('itemName');
            $post['itemType'] = $this->input->post('itemType');
            $post['mainCategoryID'] = $this->input->post('mainCategoryID');
            $post['mainCategory'] = trim($mainCategory[1]);
            $post['subcategoryID'] = $this->input->post('subcategoryID');
            $post['subSubCategoryID'] = $this->input->post('subSubCategoryID');
            $post['financeCategory'] = $this->finance_category($post['mainCategoryID']);
            $post['companyID'] = current_companyID();
            $post['companyCode'] = current_companyCode();
            $post['createdUserID'] = current_userID();
            $post['createdDateTime'] = $datetime;
            $post['createdPCID'] = current_pc();
            $post['timestamp'] = $datetime;
            $post['isFromERP'] = 0;
            $post['isActive'] = 1;
            $post['defaultUnitOfMeasure'] = trim($uom[1]);
            if($resultcat['categoryTypeID'] == 2)
            {
                $post['unbilledServicesGLAutoID'] = $this->input->post('unbilledServicesGLAutoID');
            }else
            {
                $post['unbilledServicesGLAutoID'] = null;
            }

            $result = $this->db->insert('srp_erp_mfq_itemmaster', $post);
            if ($result) {
                return array('error' => 0, 'message' => 'Item successfully Added', 'code' => 1);
            } else {
                return array('error' => 1, 'message' => 'Code: ' . $this->db->_error_number() . ' <br/>Message: ' . $this->db->_error_message());
            }
        } else {
            return array('error' => 1, 'message' => 'This item name already exist!, please try different item names');
        }
    }


    function update_item()
    {
        $maincatid = $this->input->post('mainCategoryID');
        $companyid = $this->common_data['company_data']['company_id'];
        $resultcat=$this->db->query("SELECT categoryTypeID FROM `srp_erp_itemcategory` 
         WHERE
	    `masterID` IS NULL 
	    AND `companyID` = $companyid
	    AND itemCategoryID = $maincatid ")->row_array();
        /*$this->db->select('*');
        $this->db->from('srp_erp_mfq_itemmaster');
        $this->db->where('itemName', trim($this->input->post('itemName')));
        $this->db->where('companyID', current_companyID());
        $item = $this->db->get()->row_array();

        if (!$item) {*/
        $post = $this->input->post();
        $uom = explode('|', trim($this->input->post('defaultUnitOfMeasure')));
        $mainCategory = explode('|', trim($this->input->post('mainCategory')));
        unset($post['mfqItemID']);
        $datetime = format_date_mysql_datetime();
        $post['itemDescription'] = $this->input->post('itemName');
        $post['secondaryItemCode'] = $this->input->post('secondaryItemCode');
        $post['itemType'] = $this->input->post('itemType');
        $post['mainCategoryID'] = $this->input->post('mainCategoryID');
        $post['mainCategory'] = trim($mainCategory[1]);
        $post['financeCategory'] = $this->finance_category($post['mainCategoryID']);
        $post['subcategoryID'] = $this->input->post('subcategoryID');
        $post['subSubCategoryID'] = $this->input->post('subSubCategoryID');
        $post['modifiedUserID'] = current_userID();
        $post['modifiedUserName'] = current_user();
        $post['modifiedDateTime'] = $datetime;
        $post['modifiedPCID'] = current_pc();
        $post['defaultUnitOfMeasure'] = trim($uom[1]);
        if($resultcat['categoryTypeID']==2)
        {
            $post['unbilledServicesGLAutoID'] = $this->input->post('unbilledServicesGLAutoID');
        }else
        {
            $post['unbilledServicesGLAutoID'] = null;
        }

        $this->db->where('mfqItemID', $this->input->post('mfqItemID'));
        $result = $this->db->update('srp_erp_mfq_itemmaster', $post);
        if ($result) {
            return array('error' => 0, 'message' => 'Item successfully updated', 'code' => 2);
        } else {
            return array('error' => 1, 'message' => 'Code: ' . $this->db->_error_number() . ' <br/>Message: ' . $this->db->_error_message());
        }
        /*} else {
            return array('error' => 1, 'message' => 'This item name already exist!, please try different item names');
        }*/
    }


    function get_srp_erp_mfq_itemmaster($mfqItemID)
    {
        $this->db->select('*,item.categoryTypeID as categoryTypeID');
        $this->db->from('srp_erp_mfq_itemmaster');
        $this->db->join('srp_erp_itemcategory item', 'item.itemCategoryID = srp_erp_mfq_itemmaster.mainCategoryID');
        $this->db->where('mfqItemID', $mfqItemID);
        $result = $this->db->get()->row_array();
        return $result;
    }

    function link_item()
    {
        $itemFromErp = $this->db->query('SELECT
                                 itemAutoID, IF ( mainCategory = "Inventory" OR mainCategoryID = "Non Inventory", 1,
                                 IF ( mainCategory = "Service", 2, NULL ) ) AS categoryType,
                                 itemSystemCode, seconeryItemCode, itemImage, itemName,
                                 itemDescription, mainCategoryID,mainCategory,subcategoryID, subSubCategoryID, 
                                 itemUrl, barcode, financeCategory,
                                 partNo, defaultUnitOfMeasureID, defaultUnitOfMeasure,
                                 currentStock, reorderPoint, maximunQty, minimumQty,
                                 revanueGLAutoID, revanueSystemGLCode, revanueGLCode,
                                 revanueDescription, revanueType, costGLAutoID, costSystemGLCode, costGLCode,
                                 costDescription, costType, assteGLAutoID, assteSystemGLCode,
                                 assteGLCode, assteDescription, assteType, faCostGLAutoID, faACCDEPGLAutoID,
                                 faDEPGLAutoID, faDISPOGLAutoID, isActive, comments, companyLocalCurrencyID,
                                 companyLocalCurrency, companyLocalExchangeRate, companyLocalSellingPrice,
                                 companyLocalWacAmount, companyLocalCurrencyDecimalPlaces, companyReportingCurrencyID,
                                 companyReportingCurrency, companyID, companyCode
                                FROM
                                    srp_erp_itemmaster WHERE companyID = ' . $this->common_data['company_data']['company_id'] . ' AND itemAutoID = ' . $this->input->post('selectedItemsSync'))->row_array();
        $result="";
        if ($itemFromErp) {
            $this->db->set('itemAutoID', $itemFromErp["itemAutoID"]);
            $this->db->set('categoryType', $itemFromErp["categoryType"]);
            $this->db->set('mainCategoryID', $itemFromErp["mainCategoryID"]);
            $this->db->set('mainCategory', $itemFromErp["mainCategory"]);
            $this->db->set('subcategoryID', $itemFromErp["subcategoryID"]);
            $this->db->set('subSubCategoryID', $itemFromErp["subSubCategoryID"]);
            $this->db->set('itemUrl', $itemFromErp["itemUrl"]);
            $this->db->set('barcode', $itemFromErp["barcode"]);
            $this->db->set('financeCategory', $itemFromErp["financeCategory"]);
            $this->db->set('partNo', $itemFromErp["partNo"]);
            $this->db->set('defaultUnitOfMeasureID', $itemFromErp["defaultUnitOfMeasureID"]);
            $this->db->set('defaultUnitOfMeasure', $itemFromErp["defaultUnitOfMeasure"]);
            $this->db->set('currentStock', $itemFromErp["currentStock"]);
            $this->db->set('reorderPoint', $itemFromErp["reorderPoint"]);
            $this->db->set('maximunQty', $itemFromErp["maximunQty"]);
            $this->db->set('minimumQty', $itemFromErp["minimumQty"]);
            $this->db->set('revenueGLAutoID', $itemFromErp["revanueGLAutoID"]);
            $this->db->set('revenueSystemGLCode', $itemFromErp["revanueSystemGLCode"]);
            $this->db->set('revenueGLCode', $itemFromErp["revanueGLCode"]);
            $this->db->set('revenueDescription', $itemFromErp["revanueDescription"]);
            $this->db->set('revenueType', $itemFromErp["revanueType"]);
            $this->db->set('costGLAutoID', $itemFromErp["costGLAutoID"]);
            $this->db->set('costSystemGLCode', $itemFromErp["costSystemGLCode"]);
            $this->db->set('costGLCode', $itemFromErp["costGLCode"]);
            $this->db->set('costDescription', $itemFromErp["costDescription"]);
            $this->db->set('costType', $itemFromErp["costType"]);
            $this->db->set('assetGLAutoID', $itemFromErp["assteGLAutoID"]);
            $this->db->set('assetSystemGLCode', $itemFromErp["assteSystemGLCode"]);
            $this->db->set('assetGLCode', $itemFromErp["assteGLCode"]);
            $this->db->set('assetDescription', $itemFromErp["assteDescription"]);
            $this->db->set('assetType', $itemFromErp["assteType"]);
            $this->db->set('faCostGLAutoID', $itemFromErp["faCostGLAutoID"]);
            $this->db->set('faACCDEPGLAutoID', $itemFromErp["faACCDEPGLAutoID"]);
            $this->db->set('faDEPGLAutoID', $itemFromErp["faDEPGLAutoID"]);
            $this->db->set('faDISPOGLAutoID', $itemFromErp["faDISPOGLAutoID"]);
            $this->db->set('isActive', $itemFromErp["isActive"]);
            $this->db->set('comments', $itemFromErp["comments"]);
            $this->db->set('companyLocalCurrencyID', $itemFromErp["companyLocalCurrencyID"]);
            $this->db->set('companyLocalCurrency', $itemFromErp["companyLocalCurrency"]);
            $this->db->set('companyLocalExchangeRate', $itemFromErp["companyLocalExchangeRate"]);
            $this->db->set('companyLocalSellingPrice', $itemFromErp["companyLocalSellingPrice"]);
            $this->db->set('companyLocalWacAmount', $itemFromErp["companyLocalWacAmount"]);
            $this->db->set('companyLocalCurrencyDecimalPlaces', $itemFromErp["companyLocalCurrencyDecimalPlaces"]);
            $this->db->set('companyReportingCurrencyID', $itemFromErp["companyReportingCurrencyID"]);
            $this->db->set('companyReportingCurrency', $itemFromErp["companyReportingCurrency"]);
            $this->db->set('companyID', $itemFromErp["companyID"]);
            $this->db->set('companyCode', $itemFromErp["companyCode"]);
            $this->db->where('mfqItemID', $this->input->post('mfqItemID'));
            $result = $this->db->update('srp_erp_mfq_itemmaster');
        }

        if ($result) {
            $this->session->set_flashdata('s', 'Records added Successfully');
            return array('status' => true);
        }
        else{
            $this->session->set_flashdata('e', 'Records adding failed');
            return array('status' => false);
        }
    }

}
