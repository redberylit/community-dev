<?php defined('BASEPATH') OR exit('No direct script access allowed');

  class Boq extends ERP_Controller {

    function __construct() {
      parent::__construct();
      global $globalHeaderID;
      $this->load->model('Boq_model');
    }

    function fetch_Boq_headertable() {
      $convertFormat = convert_date_format_sql();
      $companyID     =
        $this->datatables->select('headerID, companyID,companyName, customerCode, customerName,  comment, projectID, projectCode, projectNumber,DATE_FORMAT(createdDateTime,"' . $convertFormat . '") AS createdDateTime , DATE_FORMAT(projectDateFrom,"' . $convertFormat . '") AS projectDateFrom, DATE_FORMAT(projectDateTo,"' . $convertFormat . '") AS projectDateTo,approvedYN,confirmedYN')
          ->from('srp_erp_boq_header')
          ->edit_column('confirmedYN', '$1', 'confirm(confirmedYN)')
          ->where('companyID', $this->common_data['company_data']['company_id'])
          ->edit_column('approvedYN', '$1', 'confirm_approval(approvedYN)')
          ->edit_column('action', '$1', 'loadboqheaderaction(headerID)');
      echo $this->datatables->generate();
    }

    function fetch_Boq_categoryTable() {
      $this->datatables->select('categoryID,projectName as project,concat(categoryCode," | ",categoryDescription) as category ,categoryCode,categoryDescription,sortOrder, GLDescription as GLcode')
        ->from('srp_erp_boq_category')
        ->join('srp_erp_projects', 'srp_erp_boq_category.projectID=srp_erp_projects.projectID')
        ->where('srp_erp_boq_category.companyID', $this->common_data['company_data']['company_id'])
        ->edit_column('action', '$1', 'load_boq_category_action(categoryID,categoryDescription)');
      echo $this->datatables->generate();
    }

    function save_boq_category() {
      $this->form_validation->set_rules('CatCode', 'Category Code', 'trim|required');
      $this->form_validation->set_rules('projectID', 'Project', 'trim|required');
      $this->form_validation->set_rules('CatDescrip', 'Description', 'trim|required');
      $this->form_validation->set_rules('GLcode', 'Revenue GL Code', 'trim|required');


      if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata($msgtype = 'e', validation_errors());
        echo json_encode(FALSE);
      }
      else {
        echo json_encode($this->Boq_model->save_boq_category());

      }
    }

    function getCategorySortID() {
      echo json_encode($this->Boq_model->getCategorySortID());

    }

    function getSubcategorySortID() {
      echo json_encode($this->Boq_model->getSubcategorySortID());

    }

    function load_sub_category_table() {


      $this->datatables->select('subCategoryID,description,sortOrder,unitID as UnitShortCode ')
        ->from('srp_erp_boq_subcategory')
        ->where('categoryID', $this->input->post('MainCatID'))
        ->edit_column('action', '$1', 'load_boq_sub_category_action(subCategoryID)');
      echo $this->datatables->generate();
    }

    function save_boq_subcategory() {


      $this->form_validation->set_rules('SubCatDes', 'Description', 'trim|required');


      $this->db->select('categoryCode');
      $this->db->from('srp_erp_boq_category');
      $this->db->where('categoryCode', $this->input->post('MainCatID'));
      $catexist = $this->db->get()->row_array();


      if ($catexist) {
        $this->session->set_flashdata($msgtype = 'e', 'Entered Sub Category is already exist');
        echo json_encode(FALSE);
      }
      else {


        if ($this->form_validation->run() == FALSE) {
          $this->session->set_flashdata($msgtype = 'e', validation_errors());
          echo json_encode(FALSE);
        }
        else {
          echo json_encode($this->Boq_model->save_boq_subcategory());
        }
      }
    }

    function getReportingCurrency() {
      echo json_encode($this->Boq_model->getReportingCurrency());

    }

    function save_boq_header() {
      if ($this->input->post('headerID') == '') {
        $this->form_validation->set_rules('projectID', 'Project', 'trim|required');
        $this->form_validation->set_rules('segement', 'Segement', 'trim|required');
        $this->form_validation->set_rules('currency', 'Currency conversion', 'trim|required');
        $this->form_validation->set_rules('customer', 'Customer name', 'trim|required');

      }
      $this->form_validation->set_rules('documentdate', 'Document start date', 'trim|required');

      $this->form_validation->set_rules('prjStartDate', 'Project start date', 'trim|required');
      $this->form_validation->set_rules('prjEndDate', 'Project end date', 'trim|required');


      if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata($msgtype = 'e', validation_errors());
        echo json_encode(FALSE);
      }
      else {
        echo json_encode($this->Boq_model->save_boq_header());
      }

    }

    function getSubcategoryDropDown() {
      echo json_encode($this->Boq_model->getSubcategoryDropDown());
    }

    function save_boq_header_details() {
      $this->form_validation->set_rules('category', 'Category', 'trim|required');
      $this->form_validation->set_rules('subcategory', 'Sub Category', 'trim|required');
      $this->form_validation->set_rules('description', 'Description', 'trim|required');
      $this->form_validation->set_rules('unitID', 'Unit', 'trim|required');


      if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata($msgtype = 'e', validation_errors());
        echo json_encode(FALSE);
      }
      else {
        echo json_encode($this->Boq_model->save_boq_header_details());
      }

    }

    function loadcostheaderdetailstable() {
      $policy = getPolicyValues('PCR', 'P');
      if($policy==0){
        echo   $this->Boq_model->detailTableOrderByselling();
      }
      else{
        echo  $this->Boq_model->detailTableOrderBycost();
      }



    }


    function fetchItems() {
      echo json_encode($this->Boq_model->fetchItems());
    }

    function save_boq_cost_sheet() {
      $this->form_validation->set_rules('search', 'Category Code', 'trim|required');
      $this->form_validation->set_rules('uom', 'Item ', 'trim|required');
      $this->form_validation->set_rules('qty', 'Revenue GL Code', 'trim|required');
      $this->form_validation->set_rules('unitcost', 'Revenue GL Code', 'trim|required');


      if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata($msgtype = 'e', validation_errors());
        echo json_encode(FALSE);
      }
      else {
        echo json_encode($this->Boq_model->save_boq_cost_sheet());
      }
    }

    function loadboqcosttable() {


/*      $this->db->select("srp_erp_boq_header.customerCurrencyID as customerCurrencyID ,costingID,detailID,UOMID,UnitShortCode,Qty,unitCost,totalCost,costCurrencyCode,itemCode,itemDescription,CONCAT(itemCode," - ",itemDescription) as item");
      $this->db->from('srp_erp_boq_costing');
      $this->db->join('srp_erp_boq_header', 'srp_erp_boq_header.headerID=srp_erp_boq_costing.headerID', 'left');
      $this->db->where('srp_erp_boq_costing.detailID', $this->input->post('detailID'));
      $details = $this->db->get()->result_array();*/
      $detailsID=$this->input->post('detailID');
    $details=  $this->db->query("SELECT srp_erp_boq_header.customerCurrencyID AS customerCurrencyID, costingID, detailID, UOMID, UnitShortCode, Qty, unitCost, totalCost, costCurrencyCode, itemCode, itemDescription, CONCAT(itemCode, ' - ', itemDescription) AS item FROM srp_erp_boq_costing INNER JOIN srp_erp_boq_header on srp_erp_boq_header.headerID=srp_erp_boq_costing.headerID WHERE srp_erp_boq_costing.detailID=$detailsID")->result_array();

      $table   = '<table id="loadcosttable" class="' . table_class() . '"><thead><tr><th>Item</th><th>UOM</th><th>Qty</th><th >Unit Cost</th><th>Total Cost</th><th></th></tr></thead><tbody>';
      $totalValue         = 0;
      if ($details) {

        $customerCurrencyID = 0;
        foreach ($details as $value) {
          $totalValue += $value['totalCost'];

          $customerCurrencyID = $value['customerCurrencyID'];

          $table .= '<tr>';
          $table .= '<td>' . $value["itemDescription"] . '</td>';
          $table .= '<td>' . $value["UnitShortCode"] . '</td>';
          $table .= '<td><div style="text-align: right">' . $value["Qty"] . '</td><div></td>';


          $table .= '<td><div style="text-align: right">' . number_format((float) $value["unitCost"], 2, '.',
              '') . '</div></td>';
          $table .= '<td><div style="text-align: right">' . number_format((float) $value["totalCost"], 2, '.',
              '') . '</div></td>';


          $table  .= '<td><span class="pull-right"><a onclick="deleteBoqCost(' . $value['costingID'] . ',' . $value['detailID'] . ')" ><span style="color:#ff3f3a" class="glyphicon glyphicon-trash "></span></a></td>';
          $status = '';


          $table .= '</tr>';


        }
      }
      $table .= '</tbody>';
      $table .= '<tfoot><tr>';
      $table .= '<td colspan="4" style="text-align: right">Grand Total</td>';


      $table .= '<td><div style="text-align: right">' . number_format((float) $totalValue, 2, '.', '') . '</div></td>';


      $table .= '<td></td>';
      $table .= '</tr></tfoot>';
      $table .= '</table>';
      echo $table;


    }


    function saveboqdetailscalculation() {
      echo json_encode($this->Boq_model->saveboqdetailscalculation());
    }

    function getallsavedvalues() {
      echo json_encode($this->Boq_model->getallsavedvalues());
    }


    function loadsummaryTable() {
      $policy = getPolicyValues('PCR', 'P');
        if($policy==0){
       echo   $this->Boq_model->summaryTableOrderByselling();
        }
        else{
        echo  $this->Boq_model->summaryTableOrderBycost();
        }


    }

    function confirm_boq() {

      echo $this->Boq_model->confirm_boq();
    }

    function item_search() {
      $companyID          = $this->common_data['company_data']['company_id'];
      $com_currency       = $this->common_data['company_data']['company_default_currencyID'];
      $com_currencyDPlace = $this->common_data['company_data']['company_default_decimal'];
      $search_string      = "%" . $this->input->post('q') . "%";
      $currency           = $this->input->post('currency');


      if ($this->input->post('q') == '') {
        $q = "SELECT itemSystemCode, itemDescription, defaultUnitOfMeasure, companyLocalCurrency, companyLocalWacAmount, subCurrencyCode, masterCurrencyCode, conversion, (companyLocalWacAmount/conversion) as cost FROM srp_erp_itemmaster LEFT JOIN `srp_erp_companycurrencyconversion` ON `subCurrencyID` = {$currency} AND masterCurrencyID = {$com_currency} AND srp_erp_companycurrencyconversion.companyID = $companyID WHERE isActive = 1  LIMIT 10";

      }
      else {
        $q = "SELECT itemSystemCode, itemDescription, defaultUnitOfMeasure, companyLocalCurrency, companyLocalWacAmount, subCurrencyCode, masterCurrencyCode, conversion, (companyLocalWacAmount/conversion) as cost FROM srp_erp_itemmaster LEFT JOIN `srp_erp_companycurrencyconversion` ON `subCurrencyID` = {$currency} AND masterCurrencyID = {$com_currency} AND srp_erp_companycurrencyconversion.companyID = $companyID WHERE isActive = 1 AND (itemSystemCode LIKE '{$search_string}' OR itemDescription LIKE '{$search_string}' OR seconeryItemCode LIKE '{$search_string}') LIMIT 10";
      }

      $data = $this->db->query($q)->result_array();

      echo json_encode($data);


    }

    function deleteBoqHeader() {
      echo $this->Boq_model->deleteBoqHeader();
    }

    function deleteboqdetail() {
      echo $this->Boq_model->deleteboqdetail();
    }

    function deleteboqcost() {
      echo $this->Boq_model->deleteboqcost();
    }

    function save_project() {
      if ($this->input->post('projectID') == NULL) {
        $this->form_validation->set_rules('projectName', 'Project Name', 'trim|required');
        $this->form_validation->set_rules('projectCurrencyID', 'Currency', 'trim|required');
      }

      $this->form_validation->set_rules('segementID', 'Segement ', 'trim|required');
      $this->form_validation->set_rules('projectStartDate', 'Start Date', 'trim|required');
      $this->form_validation->set_rules('projectEndDate', 'End Date', 'trim|required');


      if ($this->form_validation->run() == FALSE) {

        echo json_encode($msgtype = 'e', validation_errors());
      }
      else {
        echo $this->Boq_model->save_project();
      }

    }

    function fetch_Boq_projectTable() {
      $convertFormat = convert_date_format_sql();
      $this->datatables->select('projectID, ,projectName ,projectType ,srp_erp_projects.description ,projectCurrencyID, srp_erp_projects.segmentID,DATE_FORMAT(projectStartDate,"' . $convertFormat . '") AS projectStartDate , DATE_FORMAT(projectEndDate,"' . $convertFormat . '") AS projectEndDate,  CONCAT(segmentCode," | ", srp_erp_segment.description) AS segment, CurrencyCode')
        ->from('srp_erp_projects')
        ->join('srp_erp_segment', 'srp_erp_projects.segmentID = srp_erp_segment.segmentID')
        ->join('srp_erp_currencymaster', 'srp_erp_currencymaster.currencyID = srp_erp_projects.projectCurrencyID')
        ->where('srp_erp_projects.companyID', $this->common_data['company_data']['company_id'])
        ->edit_column('action', '$1', 'loadprojectAction(projectID)');
      echo $this->datatables->generate();
    }


    function delete_project() {
      echo $this->Boq_model->delete_project();
    }

    function get_project_data() {
      echo json_encode($this->Boq_model->get_project_data());
    }

    function loadCategory() {
      $companyID = $this->common_data['company_data']['company_id'];
      $projectID = $this->input->post('projectID');
      $category  = $this->db->query("SELECT categoryID,concat(categoryCode,' | ',categoryDescription) as cat FROM `srp_erp_boq_category` WHERE companyID={$companyID} AND projectID={$projectID}")->result_array();

      $c_arr = array('' => 'Please Select');
      if (!empty($category)) {
        foreach ($category as $row) {
          $c_arr[trim($row['categoryID'])] = trim($row['cat']);

        }
      }

      echo $html = form_dropdown('category', $c_arr, '',
        'onchange="getSubcategory()" class="form-control searchbox" id="categoryID" required');


    }

    function delete_category() {
      echo $this->Boq_model->delete_category();
    }

    function deletesubcategory() {
      echo $this->Boq_model->deletesubcategory();
    }

    function get_project_pdf() {


      $headerID = $this->input->post('headerID');
      if (isset($headerID)) {
        $globalHeaderID = $headerID;
      }


      $sumtotalTransCurrency          = 0;
      $sumtotalCostTranCurrency       = 0;
      $sumtotalLabourTranCurrency     = 0;
      $sumtotalCostAmountTranCurrency = 0;
      $table                          = '<table id="summarytable" class="' . table_class() . 'custometbl"><thead>';
      $table                          .= '<tr><th>S.No</th><th >Items</th><th>UOM</th><th>Qty</th><th> Rate</th><th> Amount</th></tr>';
      $table                          .= '<tr>';

      $table .= '</tr>';


      $table .= '</thead>';
      $table .= '<tbody>';


      $this->db->select('srp_erp_boq_details.categoryID,headerID,srp_erp_boq_details.categoryName,sortOrder');
      $this->db->from('srp_erp_boq_details');
      $this->db->join('srp_erp_boq_category', 'srp_erp_boq_category.categoryID = srp_erp_boq_details.categoryID');
      $this->db->where('headerID', $globalHeaderID);
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


                  $table .= '</tr>';

                }
              }


            }
            $table .= '<tr bgcolor="#d6e9c6" style="background-color: #d6e9c6"><td></td>';
            $table .= '<td><strong>Sub Total to Summary</strong></td>';
            $table .= '<td></td>';
            $table .= '<td></td>';
            $table .= '<td></td>';
            $table .= '<td style="text-align: right"><strong>' . number_format((float) $amount, 2, '.',
                ',') . '</strong></td>';


            $table .= '</tr>';

          }


        }
      }

      $table .= '<tr>';
      $table .= '<td style="text-align:right " colspan="5"><strong>Total</strong></td>';
      $table .= '<td style="text-align: right"><strong>' . number_format((float) $sumtotalTransCurrency, 2, '.', ',');
      '</strong></td>';


      $table .= '</tr>';
      $table .= '</tbody></table>';


      $this->db->select('srp_erp_boq_details.categoryID,headerID,srp_erp_boq_details.categoryName,sortOrder');
      $this->db->from('srp_erp_boq_details');
      $this->db->join('srp_erp_boq_category', 'srp_erp_boq_category.categoryID = srp_erp_boq_details.categoryID');
      $this->db->where('headerID', $globalHeaderID);
      $this->db->group_by("categoryID");
      $this->db->order_by("sortOrder", "ASC");
      $details         = $this->db->get()->result_array();
      $data['details'] = $details;
      $convertFormat   = convert_date_format_sql();

      $master         = $this->db->query('SELECT srp_erp_boq_header.comment,srp_erp_projects.description,confirmedYN,approvedYN,customerCurrencyID,customerName,companyName,projectCode,DATE_FORMAT(projectDateFrom,"' . $convertFormat . '") AS projectDateFrom ,DATE_FORMAT(projectDateTo,"' . $convertFormat . '") AS projectDateTo FROM `srp_erp_boq_header` LEFT JOIN srp_erp_projects on srp_erp_boq_header.projectID=srp_erp_projects.projectID WHERE headerID =
    ' . $globalHeaderID . ' ')->row_array();
      $data['master'] = $master;
      $html           = $this->load->view('system/pm/project_summary_pdf', $data, TRUE, $master['approvedYN']);
      $this->load->library('pdf');

      $pdf = $this->pdf->printed($html, 'A4');


    }

    function fetch_project_approval() {
      $convertFormat = convert_date_format_sql();


      $this->datatables->select("comment,headerID,
srp_erp_boq_header.projectID,
projectCode,
projectNumber,
projectName,

segementID,
customerID,
customerCode,
customerName, DATE_FORMAT(projectDateFrom,'$convertFormat') AS projectDateFrom,DATE_FORMAT(projectDateTo,'$convertFormat') AS projectDateTo,DATE_FORMAT(projectDocumentDate,'$convertFormat') AS projectDocumentDate,  srp_erp_documentapproved.approvedYN as approvedYN, documentApprovedID, approvalLevelID,srp_erp_boq_header.confirmedYN,srp_erp_boq_header.approvedYN",
        FALSE);
      $this->datatables->from('srp_erp_boq_header');
      $this->datatables->join('srp_erp_projects', 'srp_erp_boq_header.projectID = `srp_erp_projects` .projectID ',
        'left');
      $this->datatables->join('srp_erp_documentapproved',
        'srp_erp_documentapproved.documentSystemCode = srp_erp_boq_header.headerID AND srp_erp_documentapproved.approvalLevelID = `srp_erp_boq_header.currentLevelNo');
      $this->datatables->join('srp_erp_approvalusers',
        'srp_erp_approvalusers.levelNo = srp_erp_boq_header.currentLevelNo');
      $this->datatables->where('srp_erp_approvalusers.documentID', 'P');
      $this->datatables->where('srp_erp_approvalusers.employeeID', $this->common_data['current_userID']);
      $this->datatables->where('srp_erp_documentapproved.documentID', 'P');
      $this->datatables->where('srp_erp_documentapproved.approvedYN', trim($this->input->post('approvedYN')));
      $this->datatables->where('srp_erp_boq_header.companyID', current_companyID());
      $this->datatables->where('srp_erp_approvalusers.companyID', current_companyID());
      $this->datatables->add_column('projectCode', '$1',
        'approval_change_modal(projectCode,headerID,documentApprovedID,approvalLevelID,approvedYN,"P")');
      $this->datatables->add_column('confirmed', "<center>Level $1</center>", 'approvalLevelID');
      $this->datatables->add_column('approved', '$1', 'document_approval_drilldown(approvedYN, "P", headerID)');
      $this->datatables->add_column('edit', '$1',
        'bank_transfer_approval(headerID,approvalLevelID,approvedYN,documentApprovedID)');
      echo $this->datatables->generate();

    }

    function project_summary() {

      $globalHeaderID = $this->input->post('headerID');


      $sumtotalTransCurrency          = 0;
      $sumtotalCostTranCurrency       = 0;
      $sumtotalLabourTranCurrency     = 0;
      $sumtotalCostAmountTranCurrency = 0;
      $table                          = '<table id="summarytable" class="' . table_class() . 'custometbl"><thead>';
      $table                          .= '<tr><th>S.No</th><th >Items</th><th  >Unit</th><th>Qty</th><th> Rate</th><th> Amount</th></tr>';
      $table                          .= '<tr>';

      $table .= '</tr>';


      $table .= '</thead>';
      $table .= '<tbody>';


      $this->db->select('srp_erp_boq_details.categoryID,headerID,srp_erp_boq_details.categoryName,sortOrder');
      $this->db->from('srp_erp_boq_details');
      $this->db->join('srp_erp_boq_category', 'srp_erp_boq_category.categoryID = srp_erp_boq_details.categoryID');
      $this->db->where('headerID', $globalHeaderID);
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


                  $table .= '</tr>';

                }
              }


            }
            $table .= '<tr bgcolor="#d6e9c6" style="background-color: #d6e9c6"><td></td>';
            $table .= '<td><strong>Sub Total to Summary</strong></td>';
            $table .= '<td></td>';
            $table .= '<td></td>';
            $table .= '<td></td>';
            $table .= '<td style="text-align: right"><strong>' . number_format((float) $amount, 2, '.',
                ',') . '</strong></td>';


            $table .= '</tr>';

          }


        }
      }

      $table .= '<tr>';
      $table .= '<td style="text-align:right " colspan="5"><strong>Total</strong></td>';
      $table .= '<td style="text-align: right"><strong>' . number_format((float) $sumtotalTransCurrency, 2, '.', ',');
      '</strong></td>';


      $table .= '</tr>';
      $table .= '</tbody></table>';


      $this->db->select('srp_erp_boq_details.categoryID,headerID,srp_erp_boq_details.categoryName,sortOrder');
      $this->db->from('srp_erp_boq_details');
      $this->db->join('srp_erp_boq_category', 'srp_erp_boq_category.categoryID = srp_erp_boq_details.categoryID');
      $this->db->where('headerID', $globalHeaderID);
      $this->db->group_by("categoryID");
      $this->db->order_by("sortOrder", "ASC");
      $details         = $this->db->get()->result_array();
      $data['details'] = $details;
      $convertFormat   = convert_date_format_sql();

      $master         = $this->db->query('SELECT srp_erp_boq_header.comment,srp_erp_projects.description,confirmedYN,approvedYN,customerCurrencyID,customerName,companyName,projectCode,DATE_FORMAT(projectDateFrom,"' . $convertFormat . '") AS projectDateFrom ,DATE_FORMAT(projectDateTo,"' . $convertFormat . '") AS projectDateTo FROM `srp_erp_boq_header` LEFT JOIN srp_erp_projects on srp_erp_boq_header.projectID=srp_erp_projects.projectID WHERE headerID =
    ' . $globalHeaderID . ' ')->row_array();
      $data['master'] = $master;
      $html           = $this->load->view('system/pm/project_summary_pdf', $data, TRUE, $master['approvedYN']);
      echo $html;
    }

    function insert_project_approval() {
      $this->form_validation->set_rules('headerID', 'headerID', 'trim|required');
      $this->form_validation->set_rules('Level', 'Level', 'trim|required');
      if ($this->input->post('status') == 2) {
        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');
      }
      $this->form_validation->set_rules('status', 'Status', 'trim|required');
      if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata($msgtype = 'e', validation_errors());
        echo json_encode(FALSE);
      }
      else {
        echo json_encode($this->Boq_model->confirm_project_approval());
      }
    }

    function fetch_Boq_projectPlanning() {
      $convertFormat = convert_date_format_sql();
      $companyID     = $this->common_data['company_data']['company_id'];
      $this->datatables->select('headerID, companyID,companyName, customerCode, customerName,  comment, projectID, projectCode, projectNumber,DATE_FORMAT(createdDateTime,"' . $convertFormat . '") AS createdDateTime , DATE_FORMAT(projectDateFrom,"' . $convertFormat . '") AS projectDateFrom, DATE_FORMAT(projectDateTo,"' . $convertFormat . '") AS projectDateTo,approvedYN,confirmedYN')
        ->from('srp_erp_boq_header')
        ->where('companyID', $this->common_data['company_data']['company_id'])
        ->edit_column('action', '$1', 'loadHeaderBoqlanning(headerID)');
      echo $this->datatables->generate();
    }

    function save_boq_projectPlanning() {
      $this->form_validation->set_rules('description', 'Description', 'trim|required');
      $this->form_validation->set_rules('assignedEmployee[]', 'Assign Employee', 'trim|required');
      $this->form_validation->set_rules('startDate', 'startDate', 'trim|required');
      $this->form_validation->set_rules('endDate', 'endDate', 'trim|required');
      if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata($msgtype = 'e', validation_errors());
        echo json_encode(FALSE);
      }
      else {

        echo json_encode($this->Boq_model->save_boq_projectPlanning());
      }
    }

    function project_planningSortOrder() {
      $headerID  = $this->input->post('headerID');
      $companyID = $this->common_data['company_data']['company_id'];
      $data      = $this->db->query("SELECT IF( isnull(MAX(sortOrder)), 1 , ( MAX(sortOrder) + 1) ) as sortOrder FROM srp_erp_projectplanning WHERE headerID=$headerID AND masterID=0 AND companyID=$companyID")->row_array();
      echo json_encode($data);
    }

    function loadTaskData() {
      $convertFormat  = convert_date_format_sql();
      $headerID       = $this->input->post('headerID');
      $companyID      = $this->common_data['company_data']['company_id'];
      $data['header'] = $this->db->query("SELECT srp_erp_projectplanning.projectPlannningID, srp_erp_projectplanning.headerID, masterID, description, note, percentage, DATE_FORMAT(startDate, '$convertFormat' ) AS startDate, DATE_FORMAT(endDate, '$convertFormat' ) AS endDate, bgColor, levelNo, sortOrder, GROUP_CONCAT(Ename2 SEPARATOR ' , ') AS ename2 FROM srp_erp_projectplanning LEFT JOIN ( SELECT Ename2,srp_erp_projectplanningassignee.headerID, projectPlannningID FROM srp_erp_projectplanningassignee LEFT JOIN `srp_employeesdetails` ON empID = EIdNo WHERE headerID = $headerID order by empID asc ) t ON t.projectPlannningID = srp_erp_projectplanning.projectPlannningID AND t.headerID = srp_erp_projectplanning.headerID WHERE srp_erp_projectplanning.headerID = $headerID AND companyID=$companyID GROUP BY srp_erp_projectplanning.projectPlannningID, masterID ORDER BY sortOrder ASC")->result_array();


      $data['sortOrder'] = $this->db->query("select sortOrder from srp_erp_projectplanning  WHERE headerID = $headerID AND companyID=$companyID AND masterID=0 ")->result_array();
      $html              = $this->load->view('system/pm/ajax-load-planning-data', $data, TRUE);
      echo $html;

    }

    function project_subplanningSortOrder() {
      $companyID = $this->common_data['company_data']['company_id'];
      $headerID  = $this->input->post('headerID');
      $data      = $this->db->query("SELECT IF( isnull(MAX(sortOrder)), 1 , ( MAX(sortOrder) + 1) ) as sortOrder FROM srp_erp_projectplanning WHERE masterID=$headerID AND companyID=$companyID ")->row_array();
      echo json_encode($data);
    }

    function getallchart() {
      $companyID = $this->common_data['company_data']['company_id'];
      $headerID  = $this->input->post('headerID');
      $data      = $this->db->query("SELECT srp_erp_projectplanning.projectPlannningID, srp_erp_projectplanning.headerID, masterID, description, note, percentage, startDate,  endDate, bgColor, levelNo, sortOrder, GROUP_CONCAT(Ename2 SEPARATOR ' , ') AS ename2 FROM srp_erp_projectplanning LEFT JOIN ( SELECT Ename2,srp_erp_projectplanningassignee.headerID, projectPlannningID FROM srp_erp_projectplanningassignee LEFT JOIN `srp_employeesdetails` ON empID = EIdNo WHERE headerID = $headerID order by empID asc ) t ON t.projectPlannningID = srp_erp_projectplanning.projectPlannningID AND t.headerID = srp_erp_projectplanning.headerID WHERE srp_erp_projectplanning.headerID = $headerID AND companyID=$companyID GROUP BY srp_erp_projectplanning.projectPlannningID, masterID ORDER BY sortOrder ASC ")->result_array();
      echo json_encode($data);
    }


    function update_project_planning() {
      $name  = $this->input->post('name');
      $value = $this->input->post('value');
      $pk    = $this->input->post('pk');

      $this->db->update('srp_erp_projectplanning', array($name => $value), array('projectPlannningID' => $pk));

      return TRUE;

    }

    function deleteplanning() {
      echo $this->Boq_model->deleteplanning();
    }

    function change_projectplanningSortOrder() {
      $type      = $this->input->post('type');
      $value     = $this->input->post('value');
      $id        = $this->input->post('id');
      $masterID  = $this->input->post('masterID');
      $companyID = $this->common_data['company_data']['company_id'];
      if ($type == 'm') {
        $main   = $this->db->query("select sortOrder,projectPlannningID from srp_erp_projectplanning where projectPlannningID=$id")->row_array();
        $second = $this->db->query("select sortOrder,projectPlannningID from srp_erp_projectplanning where sortOrder=$value AND masterID=0 AND companyID=$companyID")->row_array();

        $data[0]['sortOrder']          = $second['sortOrder'];
        $data[0]['projectPlannningID'] = $id;
        $data[1]['sortOrder']          = $main['sortOrder'];
        $data[1]['projectPlannningID'] = $second['projectPlannningID'];

        $this->db->update_batch('srp_erp_projectplanning', $data, 'projectPlannningID');

      }

      if ($type == 's') {
        $main   = $this->db->query("select sortOrder,projectPlannningID,description from srp_erp_projectplanning where projectPlannningID=$masterID")->row_array();
        $second = $this->db->query("select sortOrder,projectPlannningID,description from srp_erp_projectplanning where sortOrder=$value AND masterID=$id AND companyID=$companyID")->row_array();

        $data[0]['sortOrder']          = $second['sortOrder'];
        $data[0]['projectPlannningID'] = $masterID;
        $data[1]['sortOrder']          = $main['sortOrder'];
        $data[1]['projectPlannningID'] = $second['projectPlannningID'];
        $this->db->update_batch('srp_erp_projectplanning', $data, 'projectPlannningID');
      }
      echo json_encode(array('s', 'Successfully Updated'));
    }

    function get_project(){
      $convertFormat = convert_date_format_sql();

      $projectID= $this->input->post('projectID');
     $data= $this->db->query("select projectCurrencyID,segmentID, DATE_FORMAT(projectStartDate,'{$convertFormat}') as projectStartDate, DATE_FORMAT(projectEndDate,'{$convertFormat}') as projectEndDate   from srp_erp_projects WHERE projectID={$projectID}")->row_array();
     echo json_encode($data);
    }
  }