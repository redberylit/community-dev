<?php

  class Boq_model extends ERP_Model {

    function save_boq_category() {


      $this->db->trans_start();

      $data['categoryCode']        = trim($this->input->post('CatCode'));
      $data['projectID']           = trim($this->input->post('projectID'));
      $data['categoryDescription'] = trim($this->input->post('CatDescrip'));
      $GLAutoID                    = trim($this->input->post('GLcode'));
      $get_gl                      = $this->db->query("SELECT GLDescription,systemAccountCode FROM srp_erp_chartofaccounts WHERE GLAutoID={$GLAutoID}")->row_array();
      $data['companyID']           = $this->common_data['company_data']['company_id'];


      $data['GLDescription'] = $get_gl['GLDescription'];
      $data['GLcode']        = $get_gl['systemAccountCode'];
      $data['sortOrder']     = trim($this->input->post('SortOrder'));


      if (trim($this->input->post('categoryID'))) {
        $data['modifiedPcID']     = $this->common_data['current_pc'];
        $data['modifiedUserID']   = $this->common_data['current_userID'];
        $data['modifiedDateTime'] = date('Y-m-d H:i:s');

      }
      else {
        $data['createdUserGroup'] = $this->common_data['user_group'];
        $data['createdPCID']      = date('Y-m-d H:i:s');
        $data['createdUserID']    = $this->common_data['current_userID'];
        $data['createdDateTime']  = date('Y-m-d H:i:s');
        //$data['timestamp'] = date('Y-m-d h:s');

        $this->db->insert('srp_erp_boq_category', $data);
        $last_id = $this->db->insert_id();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
          return array('e', 'Category save failed');
          $this->db->trans_rollback();
        }
        else {

          $this->db->trans_commit();

          return array('s', 'Category saved successfully');
        }
      }
    }

    function getCategorySortID() {

      $this->db->select_max('sortOrder');
      $this->db->from('srp_erp_boq_category');
      $this->db->where('companyID', $this->common_data['company_data']['company_id']);
      $this->db->where('projectID', $this->input->post('projectID'));

      $data = $this->db->get()->row_array();
      if (is_null($data['sortOrder'])) {
        return 1;
      }
      else {
        return $data['sortOrder'] + 1;
      }
    }

    function getSubcategorySortID() {

      $this->db->select_max('sortOrder');
      $this->db->from('srp_erp_boq_subcategory');
      $this->db->where('categoryID', $this->input->post('MainCatID'));


      $data = $this->db->get()->row_array();
      if (is_null($data['sortOrder'])) {
        return $this->input->post('categoryID') + 0.1;
      }
      else {
        return $data['sortOrder'] + 0.1;
      }
    }

    function save_boq_subcategory() {
      $this->db->trans_start();

      $data['categoryID']  = trim($this->input->post('MainCatID'));
      $data['description'] = trim($this->input->post('SubCatDes'));
      $data['sortOrder']   = trim($this->input->post('subSortOrder'));
      $data['unitID']      = trim($this->input->post('unitID'));


      if (trim($this->input->post('AutoID'))) {
        $data['modifiedPCID']     = current_pc();
        $data['modifiedUserID']   = current_userID();
        $data['modifiedDateTime'] = date('Y-m-d h:s');
      }
      else {
        $data['createdUserGroup'] = user_group();
        $data['createdPCID']      = current_pc();
        $data['createdUserID']    = current_userID();
        $data['createdDateTime']  = date('Y-m-d h:s');
        // $data['timestamp'] = date('Y-m-d h:s');

        $this->db->insert('srp_erp_boq_subcategory', $data);
        $last_id = $this->db->insert_id();

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {

          $this->db->trans_rollback();

          return array('e', 'Save Failed');
        }
        else {
          $this->db->trans_commit();

          return array('s', 'Saved Successfully', $last_id);
        }
      }
    }

    function getReportingCurrency() {
      return load_currency_drop();
    }


    function save_boq_header() {

      $this->db->trans_start();

      $date_format_policy = date_format_policy();
      $projectStartDate   = $this->input->post('prjStartDate');
      $projectEndDate     = $this->input->post('prjEndDate');
      $documentdate       = $this->input->post('documentdate');

      $projectStartDate = input_format_date($projectStartDate, $date_format_policy);
      $projectEndDate   = input_format_date($projectEndDate, $date_format_policy);
      $documentdate     = input_format_date($documentdate, $date_format_policy);

      $data['projectDateFrom']     = $projectStartDate;
      $data['projectDateTo']       = $projectEndDate;
      $data['projectDocumentDate'] = $documentdate;
      $data['comment']             = trim($this->input->post('comments'));


      if (trim($this->input->post('headerID'))) {


        $data['modifiedPCID']     = current_pc();
        $data['modifiedUserID']   = current_userID();
        $data['modifiedDateTime'] = date('Y-m-d h:s');
        /*  $data['projectDateFrom']     = trim($this->input->post('prjStartDate'));
          $data['projectDateTo']       = trim($this->input->post('prjEndDate'));
          $data['projectDocumentDate'] = trim($this->input->post('documentdate'));
          $data['comment']             = trim($this->input->post('comments'));*/

        $this->db->where('headerID', $this->input->post('headerID'));
        $this->db->update('srp_erp_boq_header', $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {

          $this->db->trans_rollback();

          return array('e', 'Save Failed');
        }
        else {


          $this->db->trans_commit();

          return array('s', 'Saved Successfully ', $this->input->post('headerID'));

        }


      }
      else {
        $projectID = $this->input->post('projectID');
        $exist     = $this->db->query("select * from `srp_erp_boq_header` WHERE projectID=$projectID")->row_array();
        if (!empty($exist)) {
          return array('e', 'Project already assigned');
          exit;
        }
        $data['projectID']       = trim($this->input->post('projectID'));
        $data['documentID']      = 'P';
        $data['companyID']       = current_companyID();
        $data['companyName']     = current_companyName();
        $data['segementID']      = trim($this->input->post('segement'));
        $data['createdDateTime'] = trim($this->input->post('documentdate'));
        $customer                = trim($this->input->post('customer'));
        $data['customerCode']    = $customer;
        $data['customerName']    = trim($this->input->post('customerName'));

        $data['customerCurrencyID'] = trim($this->input->post('currency'));


        $data['createdUserGroup'] = user_group();
        $data['createdPCID']      = current_pc();
        $data['createdUserID']    = current_userID();
        $data['createdDateTime']  = date('Y-m-d h:s:i');
        $this->load->library('sequence');
        $data['projectCode'] = $this->sequence->sequence_generator('P');


        /**/
        $data['localCurrencyID']   = $this->common_data['company_data']['company_default_currencyID'];
        $data['localCurrencyName'] = $this->common_data['company_data']['company_default_currency'];

        $default_currency        = currency_conversionID($this->input->post('currency'), $data['localCurrencyID']);
        $data['localCurrencyER'] = $default_currency['conversion'];
        $reporting_currency      = currency_conversionID($data['localCurrencyID'],
          $this->common_data['company_data']['company_reporting_currencyID']);
        $data['rptCurrencyID']   = $this->common_data['company_data']['company_reporting_currencyID'];
        $data['rptCurencyName']  = $this->common_data['company_data']['company_reporting_currency'];
        $data['localCurrencyER'] = $reporting_currency['conversion'];
        /**/


        $this->db->insert('srp_erp_boq_header', $data);
        $last_id = $this->db->insert_id();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {

          $this->db->trans_rollback();

          return array('e', 'Save Failed');
        }
        else {

          $this->db->trans_commit();

          return array('s', 'Successfully Saved', $last_id, $data['projectCode']);
          /*   return array('status' => true, 'last_id' => $last_id, 'pcode' => $data['projectCode']);*/
        }
      }

    }

    function getSubcategoryDropDown() {
      $this->db->select('subCategoryID,description,unitID');
      $this->db->from('srp_erp_boq_subcategory');


      $this->db->where('categoryID', $this->input->post('categoryID'));

      $data = $this->db->get()->result_array();

      return $data;
    }

    function save_boq_header_details() {
      $this->db->trans_start();
      $data['unitID']          = trim($this->input->post('unitID'));
      $data['categoryID']      = trim($this->input->post('category'));
      $data['subCategoryID']   = trim($this->input->post('subcategory'));
      $data['itemDescription'] = trim($this->input->post('description'));
      $data['headerID']        = trim($this->input->post('headerID'));;

      $d = $this->db->query("select * from srp_erp_boq_category where categoryID={$data['categoryID']}")->row_array();


      $data['categoryName'] = $d['categoryDescription'];

      $s = $this->db->query("select * from srp_erp_boq_subcategory where subCategoryID={$data['subCategoryID']}")->row_array();

      $data['subCategoryName'] = $s['description'];


      if (trim($this->input->post('detailID'))) {

      }
      else {


        $this->db->insert('srp_erp_boq_details', $data);
        $last_id = $this->db->insert_id();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {

          $this->db->trans_rollback();

          return array('e' => 'Save Failed');
        }
        else {
          $this->db->trans_commit();

          return array('s', $last_id);
        }
      }

    }

    function fetchItems() {
      $companyID     = current_companyID();
      $search_string = "%" . $_GET['q'] . "%";
      /* return $this->db->query('SELECT itemmaster.primaryCode,itemmaster.financeCategoryMaster,itemassigned.itemUnitOfMeasure,units.UnitShortCode,itemassigned.secondaryItemCode,itemassigned.itemDescription,itemassigned.itemCodeSystem,CONCAT(itemassigned.itemDescription, " (" ,itemassigned.itemPrimaryCode,")") AS "Match" FROM itemassigned INNER JOIN itemmaster ON itemmaster.itemCodeSystem=itemassigned.itemCodeSystem INNER JOIN units ON units.UnitID=itemassigned.itemUnitOfMeasure WHERE itemassigned.isActive = 1 AND itemassigned.isAssigned = -1 AND itemassigned.companyID = "' . $companyID . '" AND (itemassigned.itemPrimaryCode LIKE "' . $search_string . '" OR itemassigned.itemDescription LIKE "' . $search_string . '")')->result_array();*/

      $dataArr  = array();
      $dataArr2 = array();
      $data     = $this->db->query('SELECT mainCategoryID,subcategoryID,seconeryItemCode,subSubCategoryID,revanueGLCode,itemSystemCode,costGLCode,assteGLCode,defaultUnitOfMeasure,defaultUnitOfMeasureID,itemDescription,itemAutoID,currentStock,companyLocalWacAmount,companyLocalSellingPrice,CONCAT(itemDescription, " (" ,itemSystemCode,")") AS "Match" , isSubitemExist FROM srp_erp_itemmaster WHERE (itemSystemCode LIKE "' . $search_string . '" OR itemDescription LIKE "' . $search_string . '" OR seconeryItemCode LIKE "' . $search_string . '") AND companyID = "' . $companyID . '" AND isActive=1 ')->result_array();

      return $data;

    }

    function save_boq_cost_sheet() {
      $this->db->trans_start();


      $data['headerID']         = trim($this->input->post('headerID'));
      $data['categoryID']       = trim($this->input->post('categoryID'));
      $data['subCategoryID']    = trim($this->input->post('subcategoryID'));
      $data['Qty']              = trim($this->input->post('qty'));
      $data['UnitShortCode']    = trim($this->input->post('uom'));
      $data['unitCost']         = trim($this->input->post('unitcost'));
      $data['totalCost']        = trim($this->input->post('totalcost'));
      $data['costCurrencyCode'] = trim($this->input->post('customerCurrencyID'));
      $data['detailID']         = trim($this->input->post('detailID'));


      $item = trim($this->input->post('search'));

      $t = explode(' ', $item);
      $v = array_pop($t);

      $itemcode = str_replace(array('(', ')'), '', $v);

      $data['itemCode']        = $itemcode;
      $data['itemdescription'] = $item;


      if (trim($this->input->post('costingID'))) {
        $data['modifiedPCID']     = $this->common_data['current_pc'];
        $data['modifiedUserID']   = $this->common_data['current_userID'];
        $data['modifiedDateTime'] = date('Y-m-d H:i:s');
      }
      else {
        $data['createdUserGroup'] = user_group();
        $data['createdPCID']      = $this->common_data['current_pc'];
        $data['createdUserID']    = $this->common_data['current_userID'];
        $data['createdDateTime']  = date('Y-m-d H:i:s');

        $this->db->insert('srp_erp_boq_costing', $data);
        $last_id = $this->db->insert_id();
        $this->update_costing_sheet($data['detailID']);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {

          $this->db->trans_rollback();

          return array('e' => 'Saved Failed');
        }
        else {

          $this->db->trans_commit();

          return array('s', 'Saved Successfully', $last_id);
        }
      }

    }

    function update_costing_sheet($detailID) {
      $CI =& get_instance();
      $CI->db->select_sum('totalCost');
      $CI->db->from('srp_erp_boq_costing');
      $CI->db->where('detailID', $detailID);
      $d = $CI->db->get()->row_array();

      $data['detailID'] = $detailID;
      $data['Amount']   = $d['totalCost'];

      $CI =& get_instance();
      $CI->db->select('*');
      $CI->db->from('srp_erp_boq_costingsheet');
      $CI->db->where('detailID', $detailID);
      $exist                               = $CI->db->get()->row_array();

      $detailID                            = $this->input->post('detailID');
      $details                             = $this->db->query("select * from srp_erp_boq_details where detailID= $detailID")->row_array();
      $unit['unitRateTransactionCurrency'] = $data['Amount'] * (100 + $details['markUp']) / 100; //formaula for get unit rate
      $unit['totalTransCurrency']          = $unit['unitRateTransactionCurrency'] * $details['Qty'];
      $unit['totalCostTranCurrency']       = $data['Amount'] * $details['Qty'];


      $unit['totalCostAmountTranCurrency'] = $unit['totalCostTranCurrency'] + $details['totalLabourTranCurrency'];


      //hit other details using header exchange rate
      $CI =& get_instance();
      $CI->db->select('rptCurrencyER,localCurrencyER');
      $CI->db->from('srp_erp_boq_details');
      $CI->db->join("srp_erp_boq_header", "srp_erp_boq_details.headerID = srp_erp_boq_header.headerID", "INNER");
      $CI->db->where('detailID', $detailID);
      $ER = $CI->db->get()->row_array();



      $unit['unitRateLocal']       = $unit['unitRateTransactionCurrency'] / $ER['localCurrencyER'];
      $unit['unitRateRptCurrency'] = $unit['unitRateTransactionCurrency'] / $ER['rptCurrencyER'];

      $unit['totalLocalCurrency'] = $unit['totalCostTranCurrency'] / $ER['localCurrencyER'];
      $unit['totalRptCurrency']   = $unit['totalCostTranCurrency'] / $ER['rptCurrencyER'];

      $unit['costUnitLocalCurrency'] = $unit['totalCostTranCurrency'] / $ER['localCurrencyER'];
      $unit['costUnitRptCurrency']   = $unit['totalCostTranCurrency'] / $ER['rptCurrencyER'];

     /* $unit['totalLabourLocalCurrency'] = $unit['totalLabourTranCurrency'] / $ER['localCurrencyER'];
      $unit['totalLabourRptCurrency']   = $unit['totalLabourTranCurrency'] / $ER['rptCurrencyER'];*/

      $unit['totalCostAmountLocalCurrency'] = $unit['totalCostAmountTranCurrency'] / $ER['localCurrencyER'];
      $unit['totalCostAmountRptCurrency']   = $unit['totalCostAmountTranCurrency'] / $ER['rptCurrencyER'];


      $unit['unitCostTranCurrency'] = $data['Amount'];

      //var_dump($unit);
      $this->db->where('detailID', $detailID);
      $this->db->update('srp_erp_boq_details', $unit);

      if (!empty($exist)) {

        $this->db->where('detailID', $detailID);
        $this->db->update('srp_erp_boq_costingsheet', $data);


      }
      else {
        $this->db->insert('srp_erp_boq_costingsheet', $data);
      }

      // return true;

      // return true;
    }

    function saveboqdetailscalculation() {
      $data['detailID']                    = trim($this->input->post('detailID'));
      $data['Qty']                         = trim($this->input->post('Qty'));
      $data['unitRateTransactionCurrency'] = trim($this->input->post('unitRateTransactionCurrency'));
      $data['totalTransCurrency']          = trim($this->input->post('totalTransCurrency'));
      $data['markUp']                      = trim($this->input->post('markUp'));
      $data['totalCostTranCurrency']       = trim($this->input->post('totalCostTranCurrency'));
      $data['totalLabourTranCurrency']     = trim($this->input->post('totalLabourTranCurrency'));
      $data['totalCostAmountTranCurrency'] = trim($this->input->post('totalCostAmountTranCurrency'));


      $CI =& get_instance();
      $CI->db->select('rptCurrencyER,localCurrencyER');
      $CI->db->from('srp_erp_boq_details');
      $CI->db->join("srp_erp_boq_header", "srp_erp_boq_details.headerID = srp_erp_boq_header.headerID", "INNER");
      $CI->db->where('detailID', $data['detailID']);
      $ER = $CI->db->get()->row_array();

      $data['unitRateLocal']       = $data['unitRateTransactionCurrency'] / $ER['localCurrencyER'];
      $data['unitRateRptCurrency'] = $data['unitRateTransactionCurrency'] / $ER['rptCurrencyER'];

      $data['totalLocalCurrency'] = $data['totalCostTranCurrency'] / $ER['localCurrencyER'];
      $data['totalRptCurrency']   = $data['totalCostTranCurrency'] / $ER['rptCurrencyER'];

      $data['costUnitLocalCurrency'] = $data['totalCostTranCurrency'] / $ER['localCurrencyER'];
      $data['costUnitRptCurrency']   = $data['totalCostTranCurrency'] / $ER['rptCurrencyER'];

      $data['totalLabourLocalCurrency'] = $data['totalLabourTranCurrency'] / $ER['localCurrencyER'];
      $data['totalLabourRptCurrency']   = $data['totalLabourTranCurrency'] / $ER['rptCurrencyER'];

      $data['totalCostAmountLocalCurrency'] = $data['totalCostAmountTranCurrency'] / $ER['localCurrencyER'];
      $data['totalCostAmountRptCurrency']   = $data['totalCostAmountTranCurrency'] / $ER['rptCurrencyER'];

      $this->db->where('detailID', $data['detailID']);
      $this->db->update('srp_erp_boq_details', $data);

      return array('status' => TRUE, '' => '');

    }

    function getallsavedvalues() {
      $convertFormat = convert_date_format_sql();
      $this->db->select('confirmedYN,projectID,projectCode, comment, companyID,companyName, segementID, customerCode,customerName, customerCurrencyID, DATE_FORMAT(projectDateFrom, "' . $convertFormat . '") as projectDateFrom, DATE_FORMAT(projectDateTo, "' . $convertFormat . '") as projectDateTo, DATE_FORMAT(projectDocumentDate, "' . $convertFormat . '") as projectDocumentDate');
      $this->db->from('srp_erp_boq_header');

      $this->db->where('headerID', $this->input->post('headerID'));

      $data = $this->db->get()->row();

      return $data;
    }

    function deleteBoqHeader() {
      $this->db->trans_begin();

      $this->db->delete('srp_erp_boq_costing', array('headerID' => $this->input->post('headerID')));
      $this->db->delete('srp_erp_boq_details', array('headerID' => $this->input->post('headerID')));
      $this->db->delete('srp_erp_boq_header', array('headerID' => $this->input->post('headerID')));
      $this->db->delete('srp_erp_projectplanning', array('headerID' => $this->input->post('headerID')));
      $this->db->delete('`srp_erp_projectplanningassignee` ', array('headerID' => $this->input->post('headerID')));

      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        echo json_encode(array('e', 'failed'));
      }
      else {
        $this->db->trans_commit();
        echo json_encode(array('s', 'Successfully Deleted'));
      }
    }

    function deleteboqdetail() {

      $this->db->trans_begin();

      $this->db->delete('srp_erp_boq_costing', array('detailID' => $this->input->post('detailID')));
      $this->db->delete('srp_erp_boq_details', array('detailID' => $this->input->post('detailID')));

      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        echo json_encode(array('e', 'failed'));

      }
      else {
        $this->db->trans_commit();
        echo json_encode(array('s', 'Successfully Deleted'));

      }

    }

    function deleteboqcost() {
      $this->db->delete('srp_erp_boq_costing', array('costingID' => $this->input->post('costingID')));

      if ($this->db->affected_rows()) {


        $this->db->select_sum('totalCost');
        $this->db->from('srp_erp_boq_costing');
        $this->db->where('detailID', $this->input->post('detailID'));
        $totalcost      = $this->db->get()->row_array();
        $data['Amount'] = $totalcost['totalCost'];
        $detailID       = $this->input->post('detailID');

        $details                             = $this->db->query('select * from srp_erp_boq_details where detailID = ' . $detailID . ' ')->row_array();

        $unit['unitRateTransactionCurrency'] = $data['Amount'] * (100 + $details['markUp']) / 100; //formaula for get unit rate
        $unit['totalTransCurrency']          = $unit['unitRateTransactionCurrency'] * $details['Qty'];
        $unit['totalCostTranCurrency']       = $data['Amount'] * $details['Qty'];


        $unit['totalCostAmountTranCurrency'] = $unit['totalCostTranCurrency'] + $details['totalLabourTranCurrency'];


        //hit other details using header exchange rate

        $this->db->select('rptCurrencyER,localCurrencyER');
        $this->db->from('srp_erp_boq_details');
        $this->db->join("srp_erp_boq_header", "srp_erp_boq_details.headerID = srp_erp_boq_header.headerID", "INNER");
        $this->db->where('detailID', $detailID);
        $ER = $this->db->get()->row_array();


        $unit['unitRateLocal']       = $unit['unitRateTransactionCurrency'] / $ER['localCurrencyER'];
        $unit['unitRateRptCurrency'] = $unit['unitRateTransactionCurrency'] / $ER['rptCurrencyER'];

        $unit['totalLocalCurrency'] = $unit['totalCostTranCurrency'] / $ER['localCurrencyER'];
        $unit['totalRptCurrency']   = $unit['totalCostTranCurrency'] / $ER['rptCurrencyER'];

        $unit['costUnitLocalCurrency'] = $unit['totalCostTranCurrency'] / $ER['localCurrencyER'];
        $unit['costUnitRptCurrency']   = $unit['totalCostTranCurrency'] / $ER['rptCurrencyER'];

    /*    $unit['totalLabourLocalCurrency'] = $unit['totalLabourTranCurrency'] / $ER['localCurrencyER'];
        $unit['totalLabourRptCurrency']   = $unit['totalLabourTranCurrency'] / $ER['rptCurrencyER'];*/

        $unit['totalCostAmountLocalCurrency'] = $unit['totalCostAmountTranCurrency'] / $ER['localCurrencyER'];
        $unit['totalCostAmountRptCurrency']   = $unit['totalCostAmountTranCurrency'] / $ER['rptCurrencyER'];


        $unit['unitCostTranCurrency'] = $data['Amount'];

        $this->db->where('detailID', $detailID);
        $this->db->update('srp_erp_boq_details', $unit);


        echo json_encode(array('s', 'This Item ' . $this->input->post('dec') . '  deleted Successfully .'));

      }
      else {
        echo json_encode(array('e', 'Operation could not proceed. Please contact IT team'));

      }

    }

    function save_project() {
      $date_format_policy = date_format_policy();
      $projectStartDate   = $this->input->post('projectStartDate');
      $projectEndDate     = $this->input->post('projectEndDate');
      $projectStartDate   = input_format_date($projectStartDate, $date_format_policy);
      $projectEndDate     = input_format_date($projectEndDate, $date_format_policy);
      $this->db->trans_begin();
      $data['companyID'] = $this->common_data['company_data']['company_id'];

      $data['segmentID']        = $this->input->post('segementID');
      $data['projectStartDate'] = $projectStartDate;
      $data['projectEndDate']   = $projectEndDate;
      $data['projectType']      = 1;//BOQ;

      $data['description'] = $this->input->post('description');


      $projectID = $this->input->post('projectID');
      if ($projectID != NULL) {
        $this->db->update('srp_erp_projects', $data, array('projectID' => $projectID));

        if ($this->db->trans_status() === FALSE) {
          $this->db->trans_rollback();
          echo json_encode(array('e', 'failed'));

        }
        else {
          $this->db->trans_commit();
          echo json_encode(array('s', 'Successfully Updated'));

        }
      }
      else {
        $data['projectName']       = $this->input->post('projectName');
        $data['projectCurrencyID'] = $this->input->post('projectCurrencyID');
        $data['createdUserName']   = $this->common_data['current_user'];
        $data['createdPCID']       = current_pc();
        $data['createdUserID']     = current_userID();
        $data['createdDateTime']   = date('Y-m-d h:s:i');
        $this->db->insert('srp_erp_projects', $data);

        if ($this->db->trans_status() === FALSE) {
          $this->db->trans_rollback();
          echo json_encode(array('e', 'failed'));

        }
        else {
          $this->db->trans_commit();
          echo json_encode(array('s', 'Successfully Saved'));

        }
      }


    }

    function delete_project() {

      $this->db->trans_begin();
      $projetID = $this->input->post('projectID');
      $header   = $this->db->query("select * from srp_erp_boq_header where projectID=$projetID ")->row_array();
      if (!empty($header)) {
        echo json_encode(array('e', 'You cannot delete, please delete all assigned documents to continue'));
        exit;
      }
      $category = $this->db->query("select * from srp_erp_boq_category where projectID=$projetID ")->row_array();
      if (!empty($category)) {
        echo json_encode(array('e', 'You cannot delete, please delete all assigned category to continue'));
        exit;
      }
      $this->db->delete('srp_erp_projects', array('projectID' => $this->input->post('projectID')));


      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        echo json_encode(array('e', 'failed'));
      }
      else {
        $this->db->trans_commit();
        echo json_encode(array('s', 'Successfully Deleted'));
      }

    }

    function get_project_data() {
      $projectID     = $this->input->post('projectID');
      $convertFormat = convert_date_format_sql();

      $data = $this->db->query('Select DATE_FORMAT(projectStartDate,"' . $convertFormat . '") AS projectStartDate,DATE_FORMAT(projectEndDate,"' . $convertFormat . '") AS projectEndDate,  projectCurrencyID, projectID, projectName, projectType, segmentID,description from srp_erp_projects WHERE projectID=' . $projectID . ' ')->row_array();

      return $data;
    }

    function delete_category() {
      $categoryID = $this->input->post('categoryID');
      $this->db->trans_begin();

      $header = $this->db->query("select * from srp_erp_boq_details where categoryID=$categoryID ")->row_array();
      if (!empty($header)) {
        echo json_encode(array('e', 'You cannot delete, Category assigned for a project'));
        exit;
      }

      $this->db->delete('srp_erp_boq_category', array('categoryID' => $this->input->post('categoryID')));
      $this->db->delete('srp_erp_boq_subcategory', array('categoryID' => $this->input->post('categoryID')));

      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        echo json_encode(array('e', 'failed'));
      }
      else {
        $this->db->trans_commit();
        echo json_encode(array('s', 'Successfully Deleted'));
      }

    }

    function deletesubcategory() {
      $subCategoryID = $this->input->post('subCategoryID');
      $this->db->trans_begin();

      $header = $this->db->query("select * from srp_erp_boq_details where subCategoryID=$subCategoryID ")->row_array();
      if (!empty($header)) {
        echo json_encode(array('e', 'You cannot delete, Sub category assigned for a project'));
        exit;
      }


      $this->db->delete('srp_erp_boq_subcategory', array('subCategoryID' => $this->input->post('subCategoryID')));

      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        echo json_encode(array('e', 'failed'));
      }
      else {
        $this->db->trans_commit();
        echo json_encode(array('s', 'Successfully Deleted'));
      }
    }

    function confirm_boq() {

      $headerID = $this->input->post('headerID');

      $this->load->library('approvals');
      $master           = $this->db->query("select projectCode from srp_erp_boq_header where headerID={$headerID}")->row_array();
      $approvals_status = $this->approvals->CreateApproval('P', $headerID, $master['projectCode'],
        'Project', 'srp_erp_boq_header', 'headerID');


      if ($approvals_status) {
        $data = array(
          'confirmedYN'      => 1,
          'confirmedDate'    => $this->common_data['current_date'],
          'confirmedByEmpID' => $this->common_data['current_userID'],
          'confirmedByName'  => $this->common_data['current_user'],
        );
        $this->db->where('headerID', $headerID);
        $this->db->update('srp_erp_boq_header', $data);

        echo json_encode(array('s', 'Successfully confirmed'));
      }
      else {
        echo json_encode(array('s', 'Failed'));
      }

    }

    function confirm_project_approval() {

      $this->db->trans_start();
      $this->load->library('approvals');
      $system_code = trim($this->input->post('headerID'));
      $level_id    = trim($this->input->post('Level'));
      $status      = trim($this->input->post('status'));
      $comments    = trim($this->input->post('comments'));

      $approvals_status = $this->approvals->approve_document($system_code, $level_id, $status, $comments, 'P');

      $this->db->trans_complete();
      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();

        return TRUE;
      }
      else {
        $this->db->trans_commit();
        $this->session->set_flashdata('s', 'Project Approved Successfully.');

        return TRUE;
      }

    }

    function save_boq_projectPlanning() {
      $date_format_policy = date_format_policy();
      $this->db->trans_start();
      $data['description'] = $this->input->post('description');
      $data['note']        = $this->input->post('note');


      $projectStartDate = $this->input->post('startDate');
      $projectEndDate   = $this->input->post('endDate');


      $projectStartDate = input_format_date($projectStartDate, $date_format_policy);
      $projectEndDate   = input_format_date($projectEndDate, $date_format_policy);
      if ($projectStartDate > $projectEndDate) {
        echo json_encode(array('e', 'Please check the date'));
        exit;
      }
      $data['startDate']  = $projectStartDate;
      $data['endDate']    = $projectEndDate;
      $data['sortOrder']  = $this->input->post('sortOrder');
      $data['headerID']   = $this->input->post('headerID');
      $data['percentage'] = $this->input->post('percentage');
      $data['bgColor']    = $this->input->post('color');
      $data['companyID']  = $this->common_data['company_data']['company_id'];

      $projectPlannningID = $this->input->post('projectPlannningID');
      if ($projectPlannningID != 0) {
        $data['masterID'] = $projectPlannningID;

        $validate = $this->db->query("select startDate,endDate from srp_erp_projectplanning where  projectPlannningID = $projectPlannningID")->row_array();
        if (!empty($validate)) {
          if ($validate['startDate'] > $projectStartDate || $validate['endDate'] < $projectStartDate) {
            echo json_encode(array('e', 'start date should be lower than main task date'));
            exit;
          }
          if ($validate['startDate'] > $projectEndDate || $validate['endDate'] < $projectEndDate) {
            echo json_encode(array('e', 'end date should be lower than main task date'));
            exit;
          }
        }

      }
      else {
        $data['masterID'] = 0;
      }
      $this->db->insert('srp_erp_projectplanning', $data);
      $last_id = $this->db->insert_id();

      $empID         = $this->input->post('assignedEmployee');
      $data['empID'] = $empID[0];
      if (!empty($empID)) {
        $x = 0;
        foreach ($empID as $value) {
          $x++;
          $detail[$x]['projectPlannningID'] = $last_id;
          $detail[$x]['headerID']           = $this->input->post('headerID');
          $detail[$x]['empID']              = $value;
        }


        $this->db->insert_batch('srp_erp_projectplanningassignee', $detail);
      }

      $this->db->trans_complete();
      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        echo json_encode(array('e', 'failed'));
        exit;
      }
      else {
        $this->db->trans_commit();

        echo json_encode(array('s', 'Task added successfully'));
        exit;

      }
    }


    function deleteplanning() {
      $this->db->trans_begin();

      $this->db->delete('srp_erp_projectplanning',
        array('projectPlannningID' => $this->input->post('projectPlannningID')));
      $this->db->delete('srp_erp_projectplanning', array('masterID' => $this->input->post('projectPlannningID')));
      $this->db->delete('`srp_erp_projectplanningassignee` ',
        array('projectPlannningID' => $this->input->post('projectPlannningID')));
      if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        echo json_encode(array('e', 'failed'));
      }
      else {
        $this->db->trans_commit();
        echo json_encode(array('s', 'Successfully Deleted'));
      }
    }

    function summaryTableOrderByselling() {

      $sumtotalTransCurrency          = 0;
      $sumtotalCostTranCurrency       = 0;
      $sumtotalLabourTranCurrency     = 0;
      $sumtotalCostAmountTranCurrency = 0;
      $table                          = '<table id="summarytable" class="' . table_class() . 'custometbl"><thead>';
      $table                          .= '<tr><th rowspan="3">S.No</th><th rowspan="3">Items</th><th rowspan="3" >UOM</th></th><th rowspan="2" colspan="3">Selling Price</th><th rowspan="3" style="width: 70px">Markup %</th><th colspan="4">Cost</th></tr>';
      $table                          .= '<tr>';
      $table                          .= '<th colspan="2">Material Cost</th><th rowspan="2">Total Labour Cost</th><th rowspan="2">Total Cost</th>';
      $table                          .= '</tr>';

      $table .= '<tr><th>Qty</th><th>Unit Rate</th><th>Total Value</th><th>Unit <!--cost--></th><th>Total</th></tr>';
      $table .= '</thead>';
      $table .= '<tbody>';


      $this->db->select('srp_erp_boq_details.categoryID,headerID,srp_erp_boq_details.categoryName,sortOrder');
      $this->db->from('srp_erp_boq_details');
      $this->db->join('srp_erp_boq_category', 'srp_erp_boq_category.categoryID = srp_erp_boq_details.categoryID');
      $this->db->where('headerID', $this->input->post('headerID'));
      $this->db->group_by("categoryID");
      $this->db->order_by("sortOrder", "ASC");
      $details = $this->db->get()->result_array();


      if ($details) {
        $i = 0;
        foreach ($details as $value) {
          $i++;
          $table .= '<tr><td><strong>' . $i . '</strong></td>';
          $table .= '<td><strong>' . $value['categoryName'] . '</strong></td>';
          $table .= '<td></td>';
          $table .= '<td></td>';
          $table .= '<td></td>';
          $table .= '<td></td>';
          $table .= '<td></td>';
          $table .= '<td></td>';
          $table .= '<td></td>';
          $table .= '<td></td>';
          $table .= '<td></td></tr>';


          $this->db->select('srp_erp_boq_details.subCategoryID,headerID,srp_erp_boq_details.subCategoryName,sortOrder');
          $this->db->from('srp_erp_boq_details');
          $this->db->join('srp_erp_boq_subcategory',
            'srp_erp_boq_subcategory.subCategoryID = srp_erp_boq_details.subCategoryID', 'categoryID');
          $this->db->where('headerID', $value['headerID']);
          $this->db->where('srp_erp_boq_details.categoryID', $value['categoryID']);
          $this->db->group_by("subCategoryID");
          $this->db->order_by("sortOrder", "ASC");
          $subcategory = $this->db->get()->result_array();

          if ($subcategory) {
            $x         = 0;
            $amount    = 0;
            $cost      = 0;
            $lablour   = 0;
            $totalcost = 0;
            foreach ($subcategory as $sub) {
              $x++;
              $table .= '<tr><td><strong>' . $i . '.' . $x . '</strong></td>';
              $table .= '<td><strong>' . $sub['subCategoryName'] . '</strong></td>';
              $table .= '<td></td>';
              $table .= '<td></td>';
              $table .= '<td></td>';
              $table .= '<td></td>';
              $table .= '<td></td>';
              $table .= '<td></td>';
              $table .= '<td></td>';
              $table .= '<td></td>';
              $table .= '<td></td></tr>';


              /**/

              $this->db->select('detailID,categoryName,UnitID as UnitShortCode,unitRateTransactionCurrency,categoryID,totalTransCurrency,subCategoryID,subCategoryName,markUp,itemDescription,srp_erp_boq_details.unitID,Qty,unitCostTranCurrency,totalCostTranCurrency,totalLabourTranCurrency,totalCostAmountTranCurrency,srp_erp_boq_header.customerCurrencyID as customerCurrencyID');
              $this->db->from('srp_erp_boq_details');
              $this->db->join('srp_erp_boq_header', 'srp_erp_boq_header.headerID = srp_erp_boq_details.headerID',
                'inner');

              $this->db->where('srp_erp_boq_header.headerID', $value['headerID']);
              $this->db->where('srp_erp_boq_details.categoryID', $value['categoryID']);
              $this->db->where('srp_erp_boq_details.subCategoryID', $sub['subCategoryID']);

              $subdetails = $this->db->get()->result_array();

              if ($subdetails) {

                $y = 0;
                foreach ($subdetails as $val) {
                  $y++;

                  $table .= '<tr>';


                  $table .= '<td width="10px">' . $i . '.' . $x . '.' . $y . '</td>';
                  $table .= '<td>' . $val['itemDescription'] . '</td>';
                  /*        $table .= '<td>'.$val['itemDescription'].'</td>';*/
                  $table .= '<td width="40px">' . $val['UnitShortCode'] . '</td>';
                  $table .= '<td width="40px" style="text-align: right">' . $val['Qty'] . '</td>';

                  $amount    += $val['totalTransCurrency'];
                  $cost      += $val['totalCostTranCurrency'];
                  $lablour   += $val['totalLabourTranCurrency'];
                  $totalcost += $val['totalCostAmountTranCurrency'];

                  $sumtotalTransCurrency          += $val['totalTransCurrency'];
                  $sumtotalCostTranCurrency       += $val['totalCostTranCurrency'];
                  $sumtotalLabourTranCurrency     += $val['totalLabourTranCurrency'];
                  $sumtotalCostAmountTranCurrency += $val['totalCostAmountTranCurrency'];

                  $unitRateTransactionCurrency = number_format((float) $val['unitRateTransactionCurrency'], 2, '.',
                    ',');
                  $totalTransCurrency          = number_format((float) $val['totalTransCurrency'], 2, '.', ',');
                  $unitCostTranCurrency        = number_format((float) $val['unitCostTranCurrency'], 2, '.', ',');
                  $totalCostTranCurrency       = number_format((float) $val['totalCostTranCurrency'], 2, '.', ',');
                  $totalLabourTranCurrency     = number_format((float) $val['totalLabourTranCurrency'], 2, '.', ',');
                  $totalCostAmountTranCurrency = number_format((float) $val['totalCostAmountTranCurrency'], 2, '.',
                    ',');


                  $table .= '<td width="140px" style="text-align: right">' . $unitRateTransactionCurrency . '</td>';

                  $table .= '<td width="140px" style="text-align: right">' . $totalTransCurrency . '</td>';

                  $table .= '<td width="60px" style="text-align: right">' . $val['markUp'] . '</td>';

                  $table .= '<td width="140px" style="text-align: right">' . $unitCostTranCurrency . '</td>';

                  $table .= '<td width="140px" style="text-align: right">' . $totalCostTranCurrency . '</td>';

                  $table .= '<td width="140px" style="text-align: right">' . $totalLabourTranCurrency . '</td>';

                  $table .= '<td width="140px" style="text-align: right">' . $totalCostAmountTranCurrency . '</td>';


                  $table .= '</tr>';

                }
              }


            }
            $table .= '<tr style="background-color: #d6e9c6"><td></td>';
            $table .= '<td><strong>Sub Total to Summary</strong></td>';
            $table .= '<td></td>';
            $table .= '<td></td>';
            $table .= '<td></td>';
            $table .= '<td style="text-align: right"><strong>' . number_format((float) $amount, 2, '.',
                ',') . '</strong></td>';
            $table .= '<td></td>';
            $table .= '<td></td>';
            $table .= '<td style="text-align: right"><strong>' . number_format((float) $cost, 2, '.',
                ',') . '</strong></td>';
            $table .= '<td style="text-align: right"><strong>' . number_format((float) $lablour, 2, '.',
                ',') . '</strong></td>';
            $table .= '<td style="text-align: right"><strong>' . number_format((float) $totalcost, 2, '.',
                ',') . '</strong></td>';


            $table .= '</tr>';

          }


        }
      }

      $table .= '<tr>';
      $table .= '<td style="text-align: " colspan="5"><strong>Total</strong></td>';
      $table .= '<td style="text-align: right"><strong>' . number_format((float) $sumtotalTransCurrency, 2, '.', ',');
      '</strong></td>';
      $table .= '<td colspan="3" style="text-align: right">' . number_format((float) $sumtotalCostTranCurrency, 2,
          '.',
          ',');
      '</strong></td>';
      $table .= '<td style="text-align: right"><strong>' . number_format((float) $sumtotalLabourTranCurrency, 2, '.',
          ',');
      '</strong></td>';
      $table .= '<td style="text-align: right"><strong>' . number_format((float) $sumtotalCostAmountTranCurrency, 2,
          '.', ',');
      '</strong></td>';

      $table         .= '</tr>';
      $actualrevenue = 0;
      $actualCost    = 0;
      $headerID      = $this->input->post('headerID');
      $project       = $this->db->query("select projectID from srp_erp_boq_header WHERE headerID={$headerID} ")->row_array();
      $actual        = $this->db->query("SELECT projectID, sum( transactionAmount /projectExchangeRate) as amount FROM `srp_erp_generalledger` WHERE projectID ={$project['projectID']} and (  GLType='PLI') GROUP BY projectID")->row_array();
      $actualPLE     = $this->db->query("SELECT projectID, sum( transactionAmount /projectExchangeRate) as amount FROM `srp_erp_generalledger` WHERE projectID ={$project['projectID']} and (  GLType='PLE') GROUP BY projectID")->row_array();
      if (!empty($actual)) {
        $actualrevenue = $actual['amount'];
      }
      if (!empty($actualPLE)) {
        $actualCost = $actualPLE['amount'];
      }

      $table .= '<tr><td colspan="5" style="text-align: right"><strong>Estimated Revenue</strong></td><td style="text-align: right"><strong>' . number_format($sumtotalTransCurrency,
          2) . '</strong></td><td colspan="4" style="text-align: right"><strong>Estimated Cost</strong></td><td style="text-align: right"><strong>' . number_format($sumtotalCostAmountTranCurrency,
          2) . '</strong></td></tr>';
      $table .= '<tr><td colspan="5" style="text-align: right"><strong>Actual Revenue</strong></td><td style="text-align: right"><strong>' . number_format((-1 * $actualrevenue),
          2) . '</strong></td><td colspan="4" style="text-align: right"><strong>Actual Cost</strong></td><td style="text-align: right"><strong>' . number_format($actualCost,
          2) . '</strong></td></tr>';
      $table .= '</tbody></table>';


      return $table;


    }


    function summaryTableOrderBycost() {


      $sumtotalTransCurrency          = 0;
      $sumtotalCostTranCurrency       = 0;
      $sumtotalLabourTranCurrency     = 0;
      $sumtotalCostAmountTranCurrency = 0;
      /*   $table                          = '<table id="summarytable" class="' . table_class() . 'custometbl"><thead>';
         $table                          .= '<tr><th rowspan="3">S.No</th><th rowspan="3">Items</th><th rowspan="3" >Unit</th></th><th rowspan="2" colspan="3">Selling Price</th><th rowspan="3" style="width: 70px">Markup %</th><th colspan="4">Cost</th></tr>';
         $table                          .= '<tr>';
         $table                          .= '<th colspan="2">Material Cost</th><th rowspan="2">Total Labour Cost</th><th rowspan="2">Total Cost</th>';
         $table                          .= '</tr>';

         $table .= '<tr><th>Qty</th><th>Unit Rate</th><th>Total Value</th><th>Unit <!--cost--></th><th>Total</th></tr>';
         $table .= '</thead>';
         $table .= '<tbody>';*/
      $table = '<table id="summarytable" class="' . table_class() . 'custometbl"><thead>';
      $table .= '<tr><th rowspan="3">S.No</th><th rowspan="3">Items</th><th rowspan="3" >UOM</th><th colspan="4">Cost</th><th rowspan="2" colspan="3">Selling Price</th><th rowspan="3" style="width: 70px">Markup %</th></tr>';
      $table .= '<tr>';
      $table .= '<th colspan="2">Material Cost</th><th rowspan="2">Total Labour Cost</th><th rowspan="2">Total Cost</th>';
      $table .= '</tr>';

      $table .= '<tr><th>Unit <!--cost--></th><th>Total</th><th>Qty</th><th>Unit Rate</th><th>Total Value</th></tr>';
      $table .= '</thead>';
      $table .= '<tbody>';

      $this->db->select('srp_erp_boq_details.categoryID,headerID,srp_erp_boq_details.categoryName,sortOrder');
      $this->db->from('srp_erp_boq_details');
      $this->db->join('srp_erp_boq_category', 'srp_erp_boq_category.categoryID = srp_erp_boq_details.categoryID');
      $this->db->where('headerID', $this->input->post('headerID'));
      $this->db->group_by("categoryID");
      $this->db->order_by("sortOrder", "ASC");
      $details = $this->db->get()->result_array();


      if ($details) {
        $i = 0;
        foreach ($details as $value) {
          $i++;
          $table .= '<tr><td><strong>' . $i . '</strong></td>';
          $table .= '<td><strong>' . $value['categoryName'] . '</strong></td>';
          $table .= '<td></td>';
          $table .= '<td></td>';
          $table .= '<td></td>';
          $table .= '<td></td>';
          $table .= '<td></td>';
          $table .= '<td></td>';
          $table .= '<td></td>';
          $table .= '<td></td>';
          $table .= '<td></td></tr>';


          $this->db->select('srp_erp_boq_details.subCategoryID,headerID,srp_erp_boq_details.subCategoryName,sortOrder');
          $this->db->from('srp_erp_boq_details');
          $this->db->join('srp_erp_boq_subcategory',
            'srp_erp_boq_subcategory.subCategoryID = srp_erp_boq_details.subCategoryID', 'categoryID');
          $this->db->where('headerID', $value['headerID']);
          $this->db->where('srp_erp_boq_details.categoryID', $value['categoryID']);
          $this->db->group_by("subCategoryID");
          $this->db->order_by("sortOrder", "ASC");
          $subcategory = $this->db->get()->result_array();

          if ($subcategory) {
            $x         = 0;
            $amount    = 0;
            $cost      = 0;
            $lablour   = 0;
            $totalcost = 0;
            foreach ($subcategory as $sub) {
              $x++;
              $table .= '<tr><td><strong>' . $i . '.' . $x . '</strong></td>';
              $table .= '<td><strong>' . $sub['subCategoryName'] . '</strong></td>';
              $table .= '<td></td>';
              $table .= '<td></td>';
              $table .= '<td></td>';
              $table .= '<td></td>';
              $table .= '<td></td>';
              $table .= '<td></td>';
              $table .= '<td></td>';
              $table .= '<td></td>';
              $table .= '<td></td></tr>';


              /**/

              $this->db->select('detailID,categoryName,UnitID as UnitShortCode,unitRateTransactionCurrency,categoryID,totalTransCurrency,subCategoryID,subCategoryName,markUp,itemDescription,srp_erp_boq_details.unitID,Qty,unitCostTranCurrency,totalCostTranCurrency,totalLabourTranCurrency,totalCostAmountTranCurrency,srp_erp_boq_header.customerCurrencyID as customerCurrencyID');
              $this->db->from('srp_erp_boq_details');
              $this->db->join('srp_erp_boq_header', 'srp_erp_boq_header.headerID = srp_erp_boq_details.headerID',
                'inner');

              $this->db->where('srp_erp_boq_header.headerID', $value['headerID']);
              $this->db->where('srp_erp_boq_details.categoryID', $value['categoryID']);
              $this->db->where('srp_erp_boq_details.subCategoryID', $sub['subCategoryID']);

              $subdetails = $this->db->get()->result_array();

              if ($subdetails) {

                $y = 0;
                foreach ($subdetails as $val) {
                  $y++;

                  $table .= '<tr>';


                  $table .= '<td width="10px">' . $i . '.' . $x . '.' . $y . '</td>';
                  $table .= '<td>' . $val['itemDescription'] . '</td>';
                  /*        $table .= '<td>'.$val['itemDescription'].'</td>';*/
                  $table .= '<td width="40px">' . $val['UnitShortCode'] . '</td>';


                  $amount    += $val['totalTransCurrency'];
                  $cost      += $val['totalCostTranCurrency'];
                  $lablour   += $val['totalLabourTranCurrency'];
                  $totalcost += $val['totalCostAmountTranCurrency'];

                  $sumtotalTransCurrency          += $val['totalTransCurrency'];
                  $sumtotalCostTranCurrency       += $val['totalCostTranCurrency'];
                  $sumtotalLabourTranCurrency     += $val['totalLabourTranCurrency'];
                  $sumtotalCostAmountTranCurrency += $val['totalCostAmountTranCurrency'];

                  $unitRateTransactionCurrency = number_format((float) $val['unitRateTransactionCurrency'], 2, '.',
                    ',');
                  $totalTransCurrency          = number_format((float) $val['totalTransCurrency'], 2, '.', ',');
                  $unitCostTranCurrency        = number_format((float) $val['unitCostTranCurrency'], 2, '.', ',');
                  $totalCostTranCurrency       = number_format((float) $val['totalCostTranCurrency'], 2, '.', ',');
                  $totalLabourTranCurrency     = number_format((float) $val['totalLabourTranCurrency'], 2, '.', ',');
                  $totalCostAmountTranCurrency = number_format((float) $val['totalCostAmountTranCurrency'], 2, '.',
                    ',');

                  $table .= '<td width="140px" style="text-align: right">' . $unitCostTranCurrency . '</td>';

                  $table .= '<td width="140px" style="text-align: right">' . $totalCostTranCurrency . '</td>';

                  $table .= '<td width="140px" style="text-align: right">' . $totalLabourTranCurrency . '</td>';

                  $table .= '<td width="140px" style="text-align: right">' . $totalCostAmountTranCurrency . '</td>';

                  $table .= '<td width="40px" style="text-align: right">' . $val['Qty'] . '</td>';
                  $table .= '<td width="140px" style="text-align: right">' . $unitRateTransactionCurrency . '</td>';

                  $table .= '<td width="140px" style="text-align: right">' . $totalTransCurrency . '</td>';

                  $table .= '<td width="60px" style="text-align: right">' . $val['markUp'] . '</td>';


                  $table .= '</tr>';

                }
              }


            }
            $table .= '<tr style="background-color: #d6e9c6"><td></td>';
            $table .= '<td><strong>Sub Total to Summary</strong></td>';
            $table .= '<td></td>';
            $table .= '<td></td>';

            $table .= '<td style="text-align: right"><strong>' . number_format((float) $cost, 2, '.',
                ',') . '</strong></td>';
            $table .= '<td style="text-align: right"><strong>' . number_format((float) $lablour, 2, '.',
                ',') . '</strong></td>';
            $table .= '<td style="text-align: right"><strong>' . number_format((float) $totalcost, 2, '.',
                ',') . '</strong></td>';
            $table .= '<td></td>';
            $table .= '<td></td>';
            $table .= '<td style="text-align: right"><strong>' . number_format((float) $amount, 2, '.',
                ',') . '</strong></td>';
            $table .= '<td></td>';


            $table .= '</tr>';

          }


        }
      }

      $table .= '<tr>';
      $table .= '<td style="text-align: " colspan="4"><strong>Total</strong></td>';
      $table .= '<td colspan="" style="text-align: right">' . number_format((float) $sumtotalCostTranCurrency, 2,
          '.',
          ',');
      '</strong></td>';
      $table .= '<td style="text-align: right"><strong>' . number_format((float) $sumtotalLabourTranCurrency, 2, '.',
          ',');
      '</strong></td>';
      $table .= '<td style="text-align: right"><strong>' . number_format((float) $sumtotalCostAmountTranCurrency, 2,
          '.', ',');
      '</strong></td>';
      $table .= '<td colspan="3" style="text-align: right"><strong>' . number_format((float) $sumtotalTransCurrency,
          2, '.', ',');
      '</strong></td>';
      $table .= '<td></td>';


      $table         .= '</tr>';
      $actualrevenue = 0;
      $actualCost    = 0;
      $headerID      = $this->input->post('headerID');
      $project       = $this->db->query("select projectID from srp_erp_boq_header WHERE headerID={$headerID} ")->row_array();
      $actual        = $this->db->query("SELECT projectID, sum( transactionAmount /projectExchangeRate) as amount FROM `srp_erp_generalledger` WHERE projectID ={$project['projectID']} and (  GLType='PLI') GROUP BY projectID")->row_array();
      $actualPLE     = $this->db->query("SELECT projectID, sum( transactionAmount /projectExchangeRate) as amount FROM `srp_erp_generalledger` WHERE projectID ={$project['projectID']} and (  GLType='PLE') GROUP BY projectID")->row_array();
      if (!empty($actual)) {
        $actualrevenue = $actual['amount'];
      }
      if (!empty($actualPLE)) {
        $actualCost = $actualPLE['amount'];
      }

      $table .= '<tr><td colspan="6" style="text-align: right"><strong>Estimated Cost</strong></td><td style="text-align: right"><strong>' . number_format($sumtotalCostAmountTranCurrency,
          2) . '</strong></td><td colspan="2" style="text-align: right"><strong>Estimated Revenue</strong></td><td style="text-align: right"><strong>' . number_format($sumtotalTransCurrency,
          2) . '</strong></td><td></td></tr>';
      $table .= '<tr><td colspan="6" style="text-align: right"><strong>Actual Cost</strong></td><td style="text-align: right"><strong>' . number_format($actualCost,
          2) . '</strong></td><td colspan="2" style="text-align: right"><strong>Actual Revenue</strong></td><td style="text-align: right"><strong>' . number_format((-1 * $actualrevenue),
          2) . '</strong></td><td></td></tr>';
      $table .= '</tbody></table>';


      return $table;


    }


    function detailTableOrderByselling() {


      $sumtotalTransCurrency          = 0;
      $sumtotalCostTranCurrency       = 0;
      $sumtotalLabourTranCurrency     = 0;
      $sumtotalCostAmountTranCurrency = 0;
      $this->db->select('categoryID,headerID,categoryName');
      $this->db->from('srp_erp_boq_details');
      $this->db->where('headerID', $this->input->post('headerID'));
      $this->db->group_by("categoryID");
      $details = $this->db->get()->result_array();
      $table   = '<table id="loadcosttable" class="' . table_class() . 'custometbl"><thead>';
      $table   .= '<tr><th rowspan="3">Category</th><th rowspan="3">Description</th><th rowspan="3" >UOM</th></th><th rowspan="2" colspan="3">Selling Price</th><th rowspan="3" width="70px">Markup %</th><th colspan="4">Cost</th><th></th></tr>';
      $table   .= '<tr>';
      $table   .= '<th colspan="2">Material Cost</th><th rowspan="2">Total Labour Cost</th><th rowspan="2">Total Cost</th><th rowspan="2"></th>';
      $table   .= '</tr>';

      $table .= '<tr><th>Qty</th><th>Unit Rate</th><th>Total Value</th><th>Unit <!--cost--></th><th>Total</th></tr>';
      $table .= '</thead>';
      $table .= '<tbody>';
      if ($details) {
        foreach ($details as $val) {
          $table .= '<tr>';
          $table .= '<td  colspan="12"><b>' . $val['categoryName'] . '</b></td>';
          $table .= '</tr>';

          $this->db->select('detailID,categoryName,unitID,unitRateTransactionCurrency,categoryID,totalTransCurrency,subCategoryID,subCategoryName,markUp,itemDescription,Qty,unitCostTranCurrency,totalCostTranCurrency,totalLabourTranCurrency,totalCostAmountTranCurrency,srp_erp_boq_header.customerCurrencyID as customerCurrencyID');
          $this->db->from('srp_erp_boq_details');
          $this->db->join('srp_erp_boq_header', 'srp_erp_boq_header.headerID = srp_erp_boq_details.headerID', 'inner');
          $this->db->where('srp_erp_boq_header.headerID', $val['headerID']);
          $this->db->where('srp_erp_boq_details.categoryID', $val['categoryID']);
          $subdetails = $this->db->get()->result_array();
          if ($subdetails) {
            foreach ($subdetails as $value) {
              $sumtotalTransCurrency          += $value['totalTransCurrency'];
              $sumtotalCostTranCurrency       += $value['totalCostTranCurrency'];
              $sumtotalLabourTranCurrency     += $value['totalLabourTranCurrency'];
              $sumtotalCostAmountTranCurrency += $value['totalCostAmountTranCurrency'];
              $table                          .= '<tr>';
              /* $table .= '<td></td>';*/
              $table                       .= '<td style="vertical-align: middle">' . $value['subCategoryName'] . '</td>';
              $table                       .= '<td style="vertical-align: middle">' . $value['itemDescription'] . '</td>';
              $table                       .= '<td style="vertical-align: middle" width="40px">' . $value['unitID'] . '</td>';
              $table                       .= '<td width="60px"><input class="form-control" style="text-align: right;" min="0" type="number" name="Qty" id="Qty_' . $value['detailID'] . '" value="' . $value['Qty'] . '" onchange="calculateonchangqty(' . $value['detailID'] . ')" ></td>';
              $unitRateTransactionCurrency = number_format((float) $value['unitRateTransactionCurrency'], 2, '.', ',');
              $totalTransCurrency          = number_format((float) $value['totalTransCurrency'], 2, '.', ',');
              $unitCostTranCurrency        = number_format((float) $value['unitCostTranCurrency'], 2, '.', ',');
              $totalCostTranCurrency       = number_format((float) $value['totalCostTranCurrency'], 2, '.', ',');
              $totalLabourTranCurrency     = number_format((float) $value['totalLabourTranCurrency'], 2, '.', ',');
              $totalCostAmountTranCurrency = number_format((float) $value['totalCostAmountTranCurrency'], 2, '.', ',');
              $table                       .= '<td width="110px"><input  class="form-control" style="text-align: right;" type="text" readonly="readonly" name="unitRateTransactionCurrency" id="unitRateTransactionCurrency_' . $value['detailID'] . '" value=' . $unitRateTransactionCurrency . '  ></td>';

              $table .= '<td width="110px"><input  class="form-control" style="text-align: right;" type="text" readonly="readonly" name="totalTransCurrency" id="totalTransCurrency_' . $value['detailID'] . '" value=' . $totalTransCurrency . '  ></td>';

              $table .= '<td width="60px"><input class="form-control" style="text-align: right;" type="number" min="0" name="markUp" id="markUp_' . $value['detailID'] . '" value="' . $value['markUp'] . '" onchange="calculatetotalchangemarkup(' . $value['detailID'] . ')" ></td>';

              $table .= '<td width="110"><a onclick="modalcostsheet(' . $value['categoryID'] . ',' . $value['subCategoryID'] . ',' . $value['customerCurrencyID'] . ',' . $value['detailID'] . ')" class="btn btn-default btn-xs fa fa-plus"></a><input  class="form-control" style="width: 70px;
    text-align: right;
    float: right; text-align: right;" type="text" readonly="readonly" id="unitCostTranCurrency_' . $value['detailID'] . '" name="unitCostTranCurrency" id="" value="' . $unitCostTranCurrency . '"></td>';

              $table .= '<td width="110px"><input class="form-control" style="text-align: right;" readonly="readonly" type="text" name="totalCostTranCurrency" id="totalCostTranCurrency_' . $value['detailID'] . '" value="' . $totalCostTranCurrency . '"  ></td>';

              $table .= '<td width="110px"><input class="form-control" style="text-align: right;" id="totalLabourTranCurrency_' . $value['detailID'] . '"  type="text" step="any" value="' . $totalLabourTranCurrency . '" name="totalLabourTranCurrency" onchange="calculatelabourcost(' . $value['detailID'] . ')" ></td>';
              $table .= '<td width="110px"><input class="form-control" style="text-align: right;" id="totalCostAmountTranCurrency_' . $value['detailID'] . '" type="text" step="any" value="' . $totalCostAmountTranCurrency . '" name="totalCostAmountTranCurrency" onchange="calculatetotalamount(' . $value['detailID'] . ')" ></td>';


              $table .= '<td> <span class="pull-right"><a onclick="deleteBoqdetail(' . $value['detailID'] . ')" ><span style="color:#ff3f3a" class="glyphicon glyphicon-trash "></span></a></td>';


              $table .= '</tr>';
            }
          }
        }
      }

      $table .= '<tr>
                        <td style="text-align: " colspan="5"><strong>Total</strong></td>
                        <td style="text-align: right"><strong>' . number_format((float) $sumtotalTransCurrency, 2, '.',
          ',') . '</strong></td>
                        <td colspan="3" style="text-align: right">' . number_format((float) $sumtotalCostTranCurrency,
          2, '.', ',') . '</strong></td>
                        <td style="text-align: right"><strong>' . number_format((float) $sumtotalLabourTranCurrency, 2,
          '.', ',') . '</strong></td>
                        <td style="text-align: right"><strong>' . number_format((float) $sumtotalCostAmountTranCurrency,
          2, '.', ',') . '</strong></td>
                        <td></td>
                    </tr>';

      $actualrevenue = 0;
      $actualCost    = 0;
      $headerID      = $this->input->post('headerID');
      $project       = $this->db->query("select projectID from srp_erp_boq_header WHERE headerID={$headerID} ")->row_array();
      $actual        = $this->db->query("SELECT projectID, sum( transactionAmount /projectExchangeRate) as amount FROM `srp_erp_generalledger` WHERE projectID ={$project['projectID']} and (  GLType='PLI') GROUP BY projectID")->row_array();
      $actualPLE     = $this->db->query("SELECT projectID, sum( transactionAmount /projectExchangeRate) as amount FROM `srp_erp_generalledger` WHERE projectID ={$project['projectID']} and (  GLType='PLE') GROUP BY projectID")->row_array();
      if (!empty($actual)) {
        $actualrevenue = $actual['amount'];
      }
      if (!empty($actualPLE)) {
        $actualCost = $actualPLE['amount'];
      }

      /*$sumtotalCostTranCurrency*/
      $table .= '<tr>
            <td colspan="5" style="text-align: right"><strong>Estimated Revenue</strong></td>
            <td style="text-align: right"><strong>' . number_format($sumtotalTransCurrency, 2) . '</strong></td>
            <td colspan="4" style="text-align: right"><strong>Estimated Cost</strong></td>
            <td style="text-align: right"><strong>' . number_format($sumtotalCostAmountTranCurrency, 2) . '</strong>
            </td>
            <td></td>
        </tr>';
      $table .= '
        <tr>
            <td colspan="5" style="text-align: right"><strong>Actual Revenue</strong></td>
            <td style="text-align: right"><strong>' . number_format((-1 * $actualrevenue), 2) . '</strong></td>
            <td colspan="4" style="text-align: right"><strong>Actual Cost</strong></td>
            <td style="text-align: right"><strong>' . number_format($actualCost, 2) . '</strong></td>
            <td></td>
        </tr>';

      $table .= '</table>';

      return $table;


    }


    function detailTableOrderBycost() {


      $sumtotalTransCurrency          = 0;
      $sumtotalCostTranCurrency       = 0;
      $sumtotalLabourTranCurrency     = 0;
      $sumtotalCostAmountTranCurrency = 0;
      $this->db->select('categoryID,headerID,categoryName');
      $this->db->from('srp_erp_boq_details');
      $this->db->where('headerID', $this->input->post('headerID'));
      $this->db->group_by("categoryID");
      $details = $this->db->get()->result_array();
      $table   = '<table id="loadcosttable" class="' . table_class() . 'custometbl"><thead>';
      $table   .= '<tr><th rowspan="3">Category</th><th rowspan="3">Description</th><th rowspan="3" >UOM</th><th colspan="4">Cost</th></th><th rowspan="2" colspan="3">Selling Price</th><th rowspan="3" width="70px">Markup %</th><th rowspan="3"></th></tr>';
      $table   .= '<tr>';
      $table   .= '<th colspan="2">Material Cost</th><th rowspan="2">Total Labour Cost</th><th rowspan="2">Total Cost</th>';
      $table   .= '</tr>';

      $table .= '<tr><th>Unit <!--cost--></th><th>Total</th><th>Qty</th><th>Unit Rate</th><th>Total Value</th></tr>';
      $table .= '</thead>';
      $table .= '<tbody>';
      if ($details) {
        foreach ($details as $val) {
          $table .= '<tr>';
          $table .= '<td  colspan="12"><b>' . $val['categoryName'] . '</b></td>';
          $table .= '</tr>';

          $this->db->select('detailID,categoryName,unitID,unitRateTransactionCurrency,categoryID,totalTransCurrency,subCategoryID,subCategoryName,markUp,itemDescription,Qty,unitCostTranCurrency,totalCostTranCurrency,totalLabourTranCurrency,totalCostAmountTranCurrency,srp_erp_boq_header.customerCurrencyID as customerCurrencyID');
          $this->db->from('srp_erp_boq_details');
          $this->db->join('srp_erp_boq_header', 'srp_erp_boq_header.headerID = srp_erp_boq_details.headerID', 'inner');
          $this->db->where('srp_erp_boq_header.headerID', $val['headerID']);
          $this->db->where('srp_erp_boq_details.categoryID', $val['categoryID']);
          $subdetails = $this->db->get()->result_array();
          if ($subdetails) {
            foreach ($subdetails as $value) {
              $sumtotalTransCurrency          += $value['totalTransCurrency'];
              $sumtotalCostTranCurrency       += $value['totalCostTranCurrency'];
              $sumtotalLabourTranCurrency     += $value['totalLabourTranCurrency'];
              $sumtotalCostAmountTranCurrency += $value['totalCostAmountTranCurrency'];
              $table                          .= '<tr>';
              /* $table .= '<td></td>';*/
              $table .= '<td style="vertical-align: middle">' . $value['subCategoryName'] . '</td>';
              $table .= '<td style="vertical-align: middle">' . $value['itemDescription'] . '</td>';
              $table .= '<td style="vertical-align: middle" width="40px">' . $value['unitID'] . '</td>';

              $unitRateTransactionCurrency = number_format((float) $value['unitRateTransactionCurrency'], 2, '.', ',');
              $totalTransCurrency          = number_format((float) $value['totalTransCurrency'], 2, '.', ',');
              $unitCostTranCurrency        = number_format((float) $value['unitCostTranCurrency'], 2, '.', ',');
              $totalCostTranCurrency       = number_format((float) $value['totalCostTranCurrency'], 2, '.', ',');
              $totalLabourTranCurrency     = number_format((float) $value['totalLabourTranCurrency'], 2, '.', ',');
              $totalCostAmountTranCurrency = number_format((float) $value['totalCostAmountTranCurrency'], 2, '.', ',');
              $table                       .= '<td width="110"><a onclick="modalcostsheet(' . $value['categoryID'] . ',' . $value['subCategoryID'] . ',' . $value['customerCurrencyID'] . ',' . $value['detailID'] . ')" class="btn btn-default btn-xs fa fa-plus"></a><input  class="form-control" style="width: 70px;
    text-align: right;
    float: right; text-align: right;" type="text" readonly="readonly" id="unitCostTranCurrency_' . $value['detailID'] . '" name="unitCostTranCurrency" id="" value="' . $unitCostTranCurrency . '"></td>';

              $table .= '<td width="110px"><input class="form-control" style="text-align: right;" readonly="readonly" type="text" name="totalCostTranCurrency" id="totalCostTranCurrency_' . $value['detailID'] . '" value="' . $totalCostTranCurrency . '"  ></td>';

              $table .= '<td width="110px"><input class="form-control" style="text-align: right;" id="totalLabourTranCurrency_' . $value['detailID'] . '"  type="text" step="any" value="' . $totalLabourTranCurrency . '" name="totalLabourTranCurrency" onchange="calculatelabourcost(' . $value['detailID'] . ')" ></td>';
              $table .= '<td width="110px"><input class="form-control" style="text-align: right;" id="totalCostAmountTranCurrency_' . $value['detailID'] . '" type="text" step="any" value="' . $totalCostAmountTranCurrency . '" name="totalCostAmountTranCurrency" onchange="calculatetotalamount(' . $value['detailID'] . ')" ></td>';

              $table .= '<td width="60px"><input class="form-control" style="text-align: right;" min="0" type="number" name="Qty" id="Qty_' . $value['detailID'] . '" value="' . $value['Qty'] . '" onchange="calculateonchangqty(' . $value['detailID'] . ')" ></td>';

              $table .= '<td width="110px"><input  class="form-control" style="text-align: right;" type="text" readonly="readonly" name="unitRateTransactionCurrency" id="unitRateTransactionCurrency_' . $value['detailID'] . '" value=' . $unitRateTransactionCurrency . '  ></td>';

              $table .= '<td width="110px"><input  class="form-control" style="text-align: right;" type="text" readonly="readonly" name="totalTransCurrency" id="totalTransCurrency_' . $value['detailID'] . '" value=' . $totalTransCurrency . '  ></td>';

              $table .= '<td width="60px"><input class="form-control" style="text-align: right;" type="number" min="0" name="markUp" id="markUp_' . $value['detailID'] . '" value="' . $value['markUp'] . '" onchange="calculatetotalchangemarkup(' . $value['detailID'] . ')" ></td>';


              $table .= '<td> <span class="pull-right"><a onclick="deleteBoqdetail(' . $value['detailID'] . ')" ><span style="color:#ff3f3a" class="glyphicon glyphicon-trash "></span></a></td>';


              $table .= '</tr>';
            }
          }
        }
      }

      $table .= '<tr>
                        <td style="text-align: " colspan="4"><strong>Total</strong></td>
                             <td colspan="" style="text-align: right">' . number_format((float) $sumtotalCostTranCurrency,
          2, '.', ',') . '</strong></td>
                        <td colspan="" style="text-align: right"><strong>' . number_format((float) $sumtotalLabourTranCurrency,
          2,
          '.', ',') . '</strong></td>
                        <td style="text-align: right"><strong>' . number_format((float) $sumtotalCostAmountTranCurrency,
          2, '.', ',') . '</strong></td>
                        <td  colspan="3" style="text-align: right"><strong>' . number_format((float) $sumtotalTransCurrency,
          2, '.',
          ',') . '</strong></td>
                   
                        <td></td>
                            <td></td>
                    </tr>';

      $actualrevenue = 0;
      $actualCost    = 0;
      $headerID      = $this->input->post('headerID');
      $project       = $this->db->query("select projectID from srp_erp_boq_header WHERE headerID={$headerID} ")->row_array();
      $actual        = $this->db->query("SELECT projectID, sum( transactionAmount /projectExchangeRate) as amount FROM `srp_erp_generalledger` WHERE projectID ={$project['projectID']} and (  GLType='PLI') GROUP BY projectID")->row_array();
      $actualPLE     = $this->db->query("SELECT projectID, sum( transactionAmount /projectExchangeRate) as amount FROM `srp_erp_generalledger` WHERE projectID ={$project['projectID']} and (  GLType='PLE') GROUP BY projectID")->row_array();
      if (!empty($actual)) {
        $actualrevenue = $actual['amount'];
      }
      if (!empty($actualPLE)) {
        $actualCost = $actualPLE['amount'];
      }

      /*$sumtotalCostTranCurrency*/
      $table .= '<tr>
  <td colspan="6" style="text-align: right"><strong>Estimated Cost</strong></td>
              <td style="text-align: right"><strong>' . number_format($sumtotalCostAmountTranCurrency, 2) . '</strong>
            </td>
               
            <td colspan="2" style="text-align: right"><strong>Estimated Revenue</strong></td>
         <td style="text-align: right"><strong>' . number_format($sumtotalTransCurrency, 2) . '</strong></td>
        
            <td></td>
            
            <td></td>
        </tr>';
      $table .= '
        <tr>
             <td colspan="6" style="text-align: right"><strong>Actual Cost</strong></td>
            <td style="text-align: right"><strong>' . number_format($actualCost, 2) . '</strong></td>
            <td colspan="2" style="text-align: right"><strong>Actual Revenue</strong></td>
            <td style="text-align: right"><strong>' . number_format((-1 * $actualrevenue), 2) . '</strong></td>
       
            <td></td>
            <td></td>
        </tr>';

      $table .= '</table>';

      return $table;


    }


  }