<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** ================================
 * -- File Name : Salary_category.php
 * -- Project Name : Gs_SME
 * -- Module Name : Employee Salary category
 * -- Author : Nasik Ahamed
 * -- Create date : 11 - May 2016
 * -- Description :
 */
class Salary_category extends ERP_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Salary_category_model');
        $this->load->model('Employee_model');
    }

    public function fetch_salaryCategory()
    {

        $companyID = current_companyID();

        $this->datatables->select('salaryCategoryID, salaryDescription, salaryCategoryType, deductionPercntage, payrollCatID, IFNULL(t1.GLCode,0) AS expenseGLCode, GLSecondaryCode,
            GLDescription, CONCAT(GLSecondaryCode, \' - \', GLDescription) glData, payrollCatID, IFNULL(companyContributionPercentage,0) AS CC_Percentage, description AS sys_description,
            IFNULL(companyContributionGLCode,0) AS CC_GLCode,
            salaryCategoryType AS catType, isPayrollCategory', false)
            ->from('srp_erp_pay_salarycategories t1')
            ->join('srp_erp_chartofaccounts t2', 't2.GLAutoID=t1.GLCode', 'left')
            ->join('srp_erp_defaultpayrollcategories t3', 't3.id=t1.payrollCatID', 'left')
            ->edit_column('salaryCategoryType', '$1', 'convertCatType(salaryCategoryType)')
            ->edit_column('isPayrollCategoryStr', '$1', 'isPayrollCategoryStr(isPayrollCategory)')
            ->edit_column('per', '$1', 'convertPercentage(deductionPercntage,catType)')
            ->add_column('edit', '$1', 'onclickFunction(salaryCategoryID, salaryDescription, salaryCategoryType, deductionPercntage, expenseGLCode, CC_Percentage, CC_GLCode, payrollCatID, isPayrollCategory)')
            ->where('t1.companyID', $companyID);
        echo $this->datatables->generate();

    }

    public function saveCategory()
    {
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('category', 'Type', 'trim|required');
        $this->form_validation->set_rules('isPayrollCategory', 'Payroll Category', 'trim|required');
        $defaultTypes = $this->input->post('defaultTypes');

        if (!empty($defaultTypes)) {
            $isGLRequired = $this->db->query("SELECT isGLCodeRequired FROM srp_erp_defaultpayrollcategories WHERE id={$defaultTypes}")->row('isGLCodeRequired');

            if ($isGLRequired == 'Y') {
                $this->form_validation->set_rules('glCode', 'GL Code', 'trim|required');
            }
        } else {
            $this->form_validation->set_rules('glCode', 'GL Code', 'trim|required');
        }


        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {


            if (!empty($defaultTypes)) {
                $isAlreadyDefaultCategoryExist = $this->isAlreadyDefaultCategoryExist();
                if (!empty($isAlreadyDefaultCategoryExist)) {
                    die(json_encode(['e', 'Default category \'' . $isAlreadyDefaultCategoryExist['description'] . '\' is already exist']));
                }
            }
            $description = $this->input->post('description');
            $category = $this->input->post('category');
            $percentage = $this->input->post('percentage');
            $glCode = $this->input->post('glCode');
            $percentage_com = $this->input->post('percentage-company');
            $isPayrollCategory = $this->input->post('isPayrollCategory');
            $glCode_com = $this->input->post('glCode-company');


            $isExist = $this->Salary_category_model->isExistDescription($description, $isPayrollCategory);

            if ($isExist != null) {
                echo json_encode(array('e', 'This category already exists'));
            } else {
                $companyID = current_companyID();
                $companyCode = $this->common_data['company_data']['company_code'];
                $createdPCID = $this->common_data['current_pc'];
                $createdUserID = $this->common_data['current_userID'];
                $createdUserName = $this->common_data['current_user'];
                $createdUserGroup = $this->common_data['user_group'];
                $createdDateTime = date('Y-m-d H:i:s');


                $data = array(
                    'salaryDescription' => $description,
                    'salaryCategoryType' => $category,
                    'deductionPercntage' => $percentage,
                    'payrollCatID' => $defaultTypes,
                    'GLCode' => $glCode,
                    'companyContributionPercentage' => $percentage_com,
                    'companyContributionGLCode' => $glCode_com,
                    'isPayrollCategory' => $isPayrollCategory,
                    'companyID' => $companyID,
                    'companyCode' => $companyCode,
                    'createdPCID' => $createdPCID,
                    'createdUserGroup' => $createdUserGroup,
                    'createdUserID' => $createdUserID,
                    'createdDateTime' => $createdDateTime,
                    'createdUserName' => $createdUserName
                );

                echo json_encode($this->Salary_category_model->saveCategory($data));
            }
        }

    }

    public function editCategory()
    {

        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('h_category', 'Category', 'trim|required');
        $this->form_validation->set_rules('isPayrollCategory_hidden', 'Payroll Category', 'trim|required');
        $defaultTypes = $this->input->post('defaultTypes');

        if (!empty($defaultTypes)) {
            $isGLRequired = $this->db->query("SELECT isGLCodeRequired FROM srp_erp_defaultpayrollcategories WHERE id={$defaultTypes}")->row('isGLCodeRequired');

            if ($isGLRequired == 'Y') {
                $this->form_validation->set_rules('glCode', 'GL Code', 'trim|required');
            }
        } else {
            $this->form_validation->set_rules('glCode', 'GL Code', 'trim|required');
        }

        if ($this->input->post('h_category') == 'D') {
            $this->form_validation->set_rules('percentage', 'Percentage', 'trim|required|numeric');
            $this->form_validation->set_rules('percentage-company', 'Company Contribution Percentage', 'trim|numeric|callback_check_company_contribution');
        }

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {

            $eID = $this->input->post('catEditID');

            if (!empty($defaultTypes)) {
                $isAlreadyDefaultCategoryExist = $this->isAlreadyDefaultCategoryExist();
                if (!empty($isAlreadyDefaultCategoryExist)) {
                    if ($isAlreadyDefaultCategoryExist['salaryCategoryID'] != $eID) {
                        die(json_encode(['e', 'Default category \'' . $isAlreadyDefaultCategoryExist['description'] . '\' is already exist']));
                    }
                }
            }


            $description = $this->input->post('description');
            $percentage = $this->input->post('percentage');
            $glCode = $this->input->post('glCode');
            $percentage_com = $this->input->post('percentage-company');
            $glCode_com = $this->input->post('glCode-company');
            $isPayrollCategory = $this->input->post('isPayrollCategory_hidden');
            $defaultTypes = $this->input->post('defaultTypes');

            $modifiedPCID = $this->common_data['current_pc'];
            $modifiedUserID = $this->common_data['current_userID'];
            $modifiedUserName = $this->common_data['current_user'];
            $modifiedDateTime = date('Y-m-d H:i:s');


            $data = array(
                'salaryDescription' => $description,
                'deductionPercntage' => $percentage,
                'GLCode' => $glCode,
                'companyContributionPercentage' => $percentage_com,
                'companyContributionGLCode' => $glCode_com,
                'payrollCatID' => $defaultTypes,
                'modifiedPCID' => $modifiedPCID,
                'modifiedUserID' => $modifiedUserID,
                'modifiedDateTime' => $modifiedDateTime,
                'modifiedUserName' => $modifiedUserName
            );

            $isExist = $this->Salary_category_model->isExistDescription($description, $isPayrollCategory);
            $numOfResult = sizeof($isExist);

            if ($isExist == true) {
                //print_r($isExist->salaryCategoryID);die();
                if ($isExist[0]['salaryCategoryID'] == $eID && $numOfResult == 1) {
                    echo json_encode($this->Salary_category_model->editCategory($data, $eID));
                } else {
                    $this->session->set_flashdata('e', 'This Category is already exist.');
                    echo json_encode(array('e', 'This Category is already exist.'));
                }
            } else {
                echo json_encode($this->Salary_category_model->editCategory($data, $eID));
            }

        }
    }

    function check_company_contribution()
    {
        $com_per = trim($this->input->post('percentage-company'));
        $com_gl = trim($this->input->post('glCode-company'));
        $error_msg = null;

        if (!empty($com_per)) {
            if ($com_per < 1) {
                $error_msg = '<p>The Company contribution percentage should be greater than zero</p>';
            }

            if (empty($com_gl)) {
                $error_msg .= '<p>The Company contribution GL Code field is required</p>';
            }
        }

        if (!empty($com_gl)) {
            if ($com_per == '') {
                $error_msg .= '<p>The Company contribution percentage field is required</p>';
            } elseif ($com_per < 1) {
                $error_msg .= '<p>The Company contribution percentage should be greater than zero</p>';
            }
        }


        if ($error_msg == null) {
            return true;
        } else {
            $this->form_validation->set_message('check_company_contribution', $error_msg);
            return false;
        }

    }

    public function delete_salCat()
    {
        $catID = $this->input->post('catID');
        $isUsed = $this->Salary_category_model->salaryCategoryIsInUse($catID);
        if (!empty($isUsed)) {
            die( json_encode(array('e', 'You can not delete this record.Be cause this category is used in salary declaration')) );
        }

        $isUsedInMonthlyAD = $this->isUsedInMonthlyAD_types($catID);
        if ($isUsedInMonthlyAD[0] == 'e') {
            die( json_encode($isUsedInMonthlyAD) );
        }

        $isUsedInFormula = $this->isUsedInFormula($catID);
        if ($isUsedInFormula[0] == 'e') {
           die( json_encode($isUsedInFormula) );
        }
        
        echo json_encode($this->Salary_category_model->deleteCat($catID));

    }

    function isUsedInMonthlyAD_types($catID){
        $companyID = current_companyID();
        $items = $this->db->query("SELECT monthlyDeclaration FROM srp_erp_pay_monthlydeclarationstypes
                                  WHERE salaryCategoryID={$catID} AND companyID={$companyID}")->result_array();

        if(!empty($items)){
            $description = implode('<br/>-', array_column($items, 'monthlyDeclaration'));
            return['e', 'Following monthly addition/deduction types contain this salary category, <br/>
                         so you can not delete this<br/>-'.$description];
        }

        return ['s'];
    }

    function isAlreadyDefaultCategoryExist()
    {
        $defaultTypes = $this->input->post('defaultTypes');
        $companyID = current_companyID();

        $isMultipleCategories = $this->db->query("SELECT isMultipleCategories FROM srp_erp_defaultpayrollcategories
                                                  WHERE id={$defaultTypes}")->row('isMultipleCategories');

        if( $isMultipleCategories == 1 ){
            return false;
        }

        $data = $this->db->query("SELECT salaryCategoryID, description FROM srp_erp_pay_salarycategories AS catMaster
                                  JOIN srp_erp_defaultpayrollcategories AS defaultCat ON defaultCat.id = catMaster.payrollCatID
                                  WHERE payrollCatID={$defaultTypes}
                                  AND companyID={$companyID}")->row_array();

        return $data;
    }

    function isUsedInFormula($catID){
        $companyID = current_companyID();

        $items = $this->db->query("SELECT payGroup.description  FROM srp_erp_paygroupmaster AS payGroup
                                   JOIN srp_erp_paygroupformula AS payFormula ON payFormula.payGroupID=payGroup.payGroupID
                                   AND payFormula.companyID={$companyID}
                                   WHERE payGroup.companyID={$companyID} AND
                                   (
                                      salaryCategories LIKE '%,{$catID},%' OR salaryCategories='{$catID}' OR salaryCategories
                                      LIKE '{$catID},%' OR salaryCategories LIKE '%,{$catID}'
                                   )
                                   UNION
                                   SELECT CONCAT('SSO slab | ', description ,' ( ', startRangeAmount,' - ',endRangeAmount,' )') AS description
                                   FROM srp_erp_ssoslabmaster AS slabmaster
                                   JOIN srp_erp_ssoslabdetails AS slabDetails ON slabDetails.ssoSlabMasterID=slabmaster.ssoSlabMasterID
                                   AND slabDetails.companyID={$companyID}
                                   WHERE slabmaster.companyID={$companyID} AND
                                   (
                                      salaryCategories LIKE '%,{$catID},%' OR salaryCategories='{$catID}' OR salaryCategories
                                      LIKE '{$catID},%' OR salaryCategories LIKE '%,{$catID}'
                                   )
                                   UNION
                                   SELECT CONCAT('Salary Comparison | ', description ) AS description
                                   FROM srp_erp_salarycomparisonsystemtable AS salaryComTB
                                   JOIN srp_erp_salarycomparisonformula AS salaryFormulaTB ON salaryFormulaTB.masterID=salaryComTB.id
                                   AND salaryFormulaTB.companyID={$companyID}
                                   WHERE  (
                                      salaryCategories LIKE '%,{$catID},%' OR salaryCategories='{$catID}' OR salaryCategories
                                      LIKE '{$catID},%' OR salaryCategories LIKE '%,{$catID}'
                                   )
                                   UNION
                                   SELECT CONCAT('No Pay setup | ', description ) AS description
                                   FROM srp_erp_nopaysystemtable AS systemTB
                                   JOIN srp_erp_nopayformula AS formulaTB ON formulaTB.nopaySystemID=systemTB.id
                                   AND formulaTB.companyID={$companyID}
                                   WHERE  (
                                      salaryCategories LIKE '%,{$catID},%' OR salaryCategories='{$catID}' OR salaryCategories
                                      LIKE '{$catID},%' OR salaryCategories LIKE '%,{$catID}'
                                   )
                                   UNION
                                   SELECT CONCAT('OT setup | ', otMaster.description, ' ( ',otCat.description, ' )') AS description
                                   FROM srp_erp_pay_overtimegroupdetails AS formulaTB
                                   JOIN srp_erp_pay_overtimecategory AS otCat ON otCat.ID=formulaTB.overTimeID
                                   AND otCat.companyID={$companyID}
                                   JOIN srp_erp_pay_overtimegroupmaster AS otMaster ON otMaster.groupID = formulaTB.groupID
                                   AND otMaster.companyID={$companyID}
                                   WHERE formulaTB.companyID={$companyID} AND  (
                                      salaryCategories LIKE '%,{$catID},%' OR salaryCategories='{$catID}' OR salaryCategories
                                      LIKE '{$catID},%' OR salaryCategories LIKE '%,{$catID}'
                                   )")->result_array();

        if(!empty($items)){
            $description = implode('<br/>-', array_column($items, 'description'));
            return['e', 'Following SSO/ PAYE/ SSO slab/ Salary comparison/ <br/>
                         No pay setup/ report contain this salary category,<br/>so you can not delete this<br/>-'.$description];
        }
        else{
            return ['s', ''];
        }
    }

    /***************************** Monthly Declarations **************************/
    public function fetch_monthlyDeclarationSalaryCategory()
    {

        $companyID = current_companyID();

        $this->datatables->select('monthlyDeclarationID, monthlyDeclaration, monthlyDeclarationType, expenseGLCode, GLSecondaryCode,
                    GLDescription, salaryCategoryID, isPayrollCategory', false)
            ->from('srp_erp_pay_monthlydeclarationstypes t1')
            ->join('srp_erp_chartofaccounts t2', 't2.GLAutoID=t1.expenseGLCode', 'left')
            ->edit_column('monthlyDeclarationType', '$1', 'convertCatType(monthlyDeclarationType)')
            ->edit_column('isPayrollCategoryStr', '$1', 'isPayrollCategoryStr(isPayrollCategory)')
            ->add_column('edit', '$1', 'onclickFunction(monthlyDeclarationID, monthlyDeclaration, monthlyDeclarationType, 0, expenseGLCode, salaryCategoryID, isPayrollCategory)')
            ->where('t1.companyID', $companyID);
        echo $this->datatables->generate();

    }

    public function saveMonthlyDeclarationCategory()
    {

        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('category', 'Type', 'trim|required');
        $this->form_validation->set_rules('glCode', 'GL Code', 'trim|required');


        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            $description = $this->input->post('description');
            $declarationType = $this->input->post('category');
            $glCode = $this->input->post('glCode');
            $salarySubCatID = $this->input->post('salarySubCatID');
            $isPayrollCategory = $this->input->post('isPayrollCategory');

            $isExist = $this->Salary_category_model->isExistMonthlyDeclaration($description);

            if ($isExist != null) {
                echo json_encode(array('e', 'This declaration is already exist.'));
            } else {
                $companyID = current_companyID();
                $companyCode = current_companyCode();
                $createdPCID = current_pc();
                $createdUserID = current_userID();
                $createdUserName = current_employee();
                $createdUserGroup = current_user_group();
                $createdDateTime = date('Y-m-d H:i:s');


                $data = array(
                    'monthlyDeclaration' => $description,
                    'monthlyDeclarationType' => $declarationType,
                    'salaryCategoryID' => $salarySubCatID,
                    'expenseGLCode' => $glCode,
                    'companyID' => $companyID,
                    'isPayrollCategory' => $isPayrollCategory,
                    'companyCode' => $companyCode,
                    'createdPCID' => $createdPCID,
                    'createdUserGroup' => $createdUserGroup,
                    'createdUserID' => $createdUserID,
                    'createdDateTime' => $createdDateTime,
                    'createdUserName' => $createdUserName
                );

                echo json_encode($this->Salary_category_model->saveMonthlyDeclarationCategory($data));
            }
        }

    }

    public function editMonthlyDeclarationCategory()
    {

        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('glCode', 'GL Code', 'trim|required');


        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            $eID = $this->input->post('catEditID');
            $description = $this->input->post('description');
            $declarationType = $this->input->post('category');
            $glCode = $this->input->post('glCode');
            $salarySubCatID = $this->input->post('salarySubCatID');

            $modifiedPCID = current_pc();
            $modifiedUserID = current_userID();
            $modifiedUserName = current_employee();
            $modifiedDateTime = date('Y-m-d H:i:s');


            $data = array(
                'monthlyDeclaration' => $description,
                'salaryCategoryID' => $salarySubCatID,
                'expenseGLCode' => $glCode,
                'modifiedPCID' => $modifiedPCID,
                'modifiedUserID' => $modifiedUserID,
                'modifiedDateTime' => $modifiedDateTime,
                'modifiedUserName' => $modifiedUserName
            );

            $isExist = $this->Salary_category_model->isExistMonthlyDeclaration($description);
            $numOfResult = sizeof($isExist);

            if ($isExist == true) {

                if ($isExist[0]['monthlyDeclarationID'] == $eID && $numOfResult == 1) {
                    echo json_encode($this->Salary_category_model->editMonthlyDeclaration($data, $eID));
                } else {

                    echo json_encode(array('e', 'This declaration is already exist.'));
                }
            } else {
                echo json_encode($this->Salary_category_model->editMonthlyDeclaration($data, $eID));
            }

        }
    }

    public function delete_monthlyDeclarationSalCat()
    {
        $declarationID = $this->input->post('declarationID');
        $declarationType = $this->input->post('declarationType');

        $detailTB = ($declarationType == 'A')? 'srp_erp_pay_monthlyadditiondetail' : 'srp_erp_pay_monthlydeductiondetail';

        $isUsed = $this->db->query("SELECT count(empID) AS usageCount FROM {$detailTB}
                                    WHERE declarationID={$declarationID}")->row('usageCount');

        if ($isUsed == 0) {
            echo json_encode($this->Salary_category_model->delete_monthlyDeclarationSalCat($declarationID));
        } else {
            echo json_encode(array('e', 'You can not delete this record.<br/>This declaration is in use'));
        }
    }

    /** Over-time management for Salam-Air **/
    public function saveOTElement()
    {
        $this->form_validation->set_rules('description', 'Description', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Salary_category_model->saveOTElement());
        }
    }

    public function tableOTElement()
    {
        $companyID = current_companyID();
        $this->datatables->select('fixedElementID,fixedElementDescription, usageCount', false)
            ->from('srp_erp_ot_fixedelements AS t1')
            ->join('( SELECT count(employeeNo) AS usageCount, fixedElementID AS fixID FROM srp_erp_ot_fixedelementdeclarationdetails
                WHERE companyID='.$companyID.' GROUP BY fixedElementID) AS t2', 't2.fixID=t1.fixedElementID', 'left')
            ->add_column('edit', '$1', 'edit_otElement(fixedElementID,fixedElementDescription,usageCount)')
            ->where('companyID', $companyID);

        echo $this->datatables->generate();
    }

    public function editOTElement()
    {
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('hiddenID', 'Element ID', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Salary_category_model->editOTElement());
        }
    }

    function delete_ot_element()
    {
        echo json_encode($this->Salary_category_model->delete_ot_element());
    }

    public function table_overtime_group()
    {
        $companyID = current_companyID();
        $this->datatables->select('otGroupID, otGroupDescription,srp_erp_ot_groups.CurrencyID,srp_erp_currencymaster.CurrencyCode as CurrencyCode ', FALSE)
            ->join('srp_erp_currencymaster', 'srp_erp_currencymaster.currencyID=srp_erp_ot_groups.CurrencyID', 'left')
            ->from('srp_erp_ot_groups')
            ->add_column('edit', '$1', 'edit_overtimegroup(otGroupID)')
            ->where('companyID', $companyID);
        echo $this->datatables->generate();
    }

    function create_overTimeGroup()
    {
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('CurrencyID', 'Currency', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Salary_category_model->create_overTimeGroup());
        }
    }

    function delete_ot_group()
    {
        echo json_encode($this->Salary_category_model->delete_ot_group());
    }

    function saveInputRates()
    {
        ;
        $inputType = $this->input->post('inputType');
        $this->form_validation->set_rules('systemInputID', 'Input Type', 'trim|required');
        if ($inputType == 1) {
            $this->form_validation->set_rules('slabID', 'Slab', 'trim|required');
        } else {
            $this->form_validation->set_rules('rate', 'Rate', 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Salary_category_model->saveInputRates());
        }
    }

    public function table_overtimeDetail_group()
    {
        $otGroupID = $this->input->post('otGroupID');
        $companyID = current_companyID();
        $this->datatables->select('otGroupDetailID, inputDescription, IF(ISNULL(hourlyRate), \'-\', hourlyRate) AS hourlyRateStr,
             IF(slabMasterID=0, \'-\', Description) AS slabMasterStr ,slabMasterID as slabMasterID, inputTB.systemInputID as systemInputID, inputType', FALSE)
            ->from('srp_erp_ot_groupdetail AS groupTB')
            ->join('srp_erp_ot_systeminputs AS inputTB', 'inputTB.systemInputID=groupTB.systemInputID')
            ->join("(SELECT otSlabsMasterID, Description FROM srp_erp_ot_slabsmaster WHERE companyID={$companyID}) AS slabDB",
                'slabDB.otSlabsMasterID=groupTB.slabMasterID', 'left')
            ->add_column('edit', '$1', 'edit_overTimeGroupDetail(otGroupDetailID,systemInputID,hourlyRateStr,slabMasterID,inputType)')
            ->where('otGroupID', $otGroupID)
            ->where('companyID', $companyID);
        echo $this->datatables->generate();
    }

    function editInputRates()
    {
        $inputType = $this->input->post('inputType');
        $this->form_validation->set_rules('systemInputID', 'System Input', 'trim|required');
        if ($inputType == 1) {
            $this->form_validation->set_rules('slabID', 'Slab', 'trim|required');
        } else {
            $this->form_validation->set_rules('rate', 'Rate', 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Salary_category_model->editInputRates());
        }
    }

    function table_overtimeEmployees_group()
    {
        $companyid = $this->common_data['company_data']['company_id'];
        $otGroupID = $this->input->post('otGroupID');
        $this->datatables->select("otGroupEmpID,srp_erp_ot_groupemployees.otGroupID,empID,srp_employeesdetails.Ename2 as Ename2,srp_erp_ot_groups.otGroupDescription as otGroupDescription,ECode");
        $this->datatables->join('srp_employeesdetails', 'srp_erp_ot_groupemployees.empID = srp_employeesdetails.EIdNo');
        $this->datatables->join('srp_erp_ot_groups', 'srp_erp_ot_groupemployees.otGroupID = srp_erp_ot_groups.otGroupID');
        $this->datatables->from('srp_erp_ot_groupemployees');
        $this->datatables->where('srp_erp_ot_groupemployees.companyID  =  ' . $companyid . '   ');
        $this->datatables->where('srp_erp_ot_groupemployees.otGroupID  =  ' . $otGroupID . '   ');
        $this->datatables->where('srp_employeesdetails.isDischarged  =  0');
        $this->datatables->add_column('edit', '$1', 'load_OT_group_employee_action(otGroupEmpID)');
        echo $this->datatables->generate();
    }

    function load_dropdown_unassigned_employees()
    {
        $companyID = current_companyID();
        $otGroupID = $this->input->post('otGroupID');
        $designation = $this->input->post('designation');

        $designation_filter = '';
        if (!empty($designation)) {
            $contractType = explode(',', $this->input->post('designation'));
            $whereIN = "( '" . join("' , '", $contractType) . "' )";
            $designation_filter = " AND EmpDesignationId IN " . $whereIN;
        }

        $con = "IFNULL(Ename2, '')";
        $CurrencyID = $this->db->query("SELECT
	CurrencyID
FROM
	srp_erp_ot_groups

WHERE otGroupID = $otGroupID")->row_array();

        $Currency = $CurrencyID['CurrencyID'];

        $where = 'srp_employeesdetails.isPayrollEmployee = 1 AND srp_employeesdetails.empConfirmedYN = 1 '.$designation_filter.' AND srp_employeesdetails.payCurrencyID=' . $Currency . ' AND srp_employeesdetails.Erp_companyID = ' . $companyID . ' AND  EIdNo NOT IN (
                        SELECT  EmpID
        FROM    srp_erp_ot_groupemployees
        WHERE   srp_erp_ot_groupemployees.empID = srp_employeesdetails.EIdNo AND srp_erp_ot_groupemployees.companyID = ' . $companyID .  '
                  ) ';


        $this->datatables->select('EIdNo, ECode, CONCAT(' . $con . ') AS empName, DesDescription');
        $this->datatables->from('srp_employeesdetails');
        $this->datatables->join('srp_designation', 'srp_employeesdetails.EmpDesignationId = srp_designation.DesignationID');
        $this->datatables->add_column('addBtn', '$1', 'addBtn()');
        $this->datatables->where($where);

        echo $this->datatables->generate();
    }

    function delete_ot_group_emp()
    {
        echo json_encode($this->Salary_category_model->delete_ot_group_emp());
    }

    function save_assigned_OT_employees()
    {
        echo json_encode($this->Salary_category_model->save_assigned_OT_employees());
    }

    function delete_ot_group_detail()
    {
        echo json_encode($this->Salary_category_model->delete_ot_group_detail());
    }

    function load_ot_group_description(){
        echo json_encode($this->Salary_category_model->load_ot_group_description());
    }

    function edit_group_description(){
        $this->form_validation->set_rules('otGroupDescription', 'Group Description', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('e', validation_errors()));
        } else {
            echo json_encode($this->Salary_category_model->edit_group_description());
        }

    }
}