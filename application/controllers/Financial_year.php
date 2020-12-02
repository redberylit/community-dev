<?php

class Financial_year extends ERP_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Financial_year_model');
    }

    function load_Financial_year()
    {
        $this->datatables->select("companyFinanceYearID,beginingDate,endingDate,comments,isActive,isCurrent,isClosed");
        $this->datatables->where('companyID', $this->common_data['company_data']['company_id']);
        $this->datatables->from('srp_erp_companyfinanceyear');
        $this->datatables->add_column('financial_year', '<center> $1- $2 </center>', 'beginingDate,endingDate');
/*        $this->datatables->add_column('current_status', '$1', 'confirm(isCurrent)');
        $this->datatables->add_column('closed_status', '$1', 'confirm(isClosed)');*/
        $this->datatables->add_column('status', '$1', 'load_Financial_year_status(companyFinanceYearID,isActive)');
        $this->datatables->add_column('current', '$1', 'load_Financial_year_current(companyFinanceYearID,isCurrent)');
        $this->datatables->add_column('close', '$1', 'load_Financial_year_close(companyFinanceYearID,isClosed)');
        $this->datatables->add_column('action', '<span class="pull-right"><a onclick="openisactiveeditmodel($1)"><span title="Edit" rel="tooltip" class="glyphicon glyphicon-pencil"></span></a></span>', 'companyFinanceYearID');
        echo $this->datatables->generate();
    }

    function save_financial_year()
    {
        $this->form_validation->set_rules('beginningdate', 'Beginning Date', 'trim|required');
        $this->form_validation->set_rules('endingdate', 'Ending Date', 'trim|required');
        $this->form_validation->set_rules('comments', 'Comments', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata($msgtype = 'e', validation_errors());
            echo json_encode(FALSE);
        } else {
            $companyID = current_companyID();
            $chkFinanceYear = $this->db->query("SELECT companyFinanceYearID,beginingDate,endingDate,companyID FROM srp_erp_companyfinanceyear where companyID = {$companyID} AND ('" . $this->input->post('beginningdate') . "' BETWEEN beginingDate AND endingDate OR '" . $this->input->post('endingdate') . "' BETWEEN beginingDate AND endingDate)")->row_array();

            if ($chkFinanceYear) {
                $this->session->set_flashdata('e', 'Financial Year already created !');
                echo json_encode(FALSE);
            } else {
                echo json_encode($this->Financial_year_model->save_financial_year());
            }
        }
    }

    function update_year_status()
    {
        $chkFinanceYearCurrent = $this->db->query("SELECT companyFinanceYearID,isActive,isCurrent,isClosed FROM srp_erp_companyfinanceyear where companyFinanceYearID = " . $this->input->post('companyFinanceYearID') . "")->row_array();

        if ($chkFinanceYearCurrent['isClosed'] == 1) {
            $this->session->set_flashdata('e', 'A closed financial year cannot be set as current year');
            echo json_encode(FALSE);
        } else {
            echo json_encode($this->Financial_year_model->update_year_status());
        }

    }

    function update_year_close()
    {
        echo json_encode($this->Financial_year_model->update_year_close());
    }

    function update_year_current()
    {
        $chkFinanceYearCurrent = $this->db->query("SELECT companyFinanceYearID,isActive,isCurrent,isClosed FROM srp_erp_companyfinanceyear where companyFinanceYearID = " . $this->input->post('companyFinanceYearID') . "")->row_array();

        if ($chkFinanceYearCurrent['isClosed'] == 1) {
            $this->session->set_flashdata('e', 'A closed financial year cannot be set as current year');
            echo json_encode(FALSE);
        } else {
            if ($chkFinanceYearCurrent['isActive'] == 0) {
                $this->session->set_flashdata('e', 'This Financial Year is not activated !');
                echo json_encode(FALSE);
            } else {
                echo json_encode($this->Financial_year_model->update_year_current());
            }
        }
    }

    function load_isactiveeditdetails()
    {
        $this->datatables->select("companyFinancePeriodID,companyFinanceYearID,dateFrom,dateTo,isActive,isCurrent,isClosed");
        $this->datatables->where('companyFinanceYearID', $this->input->post('companyFinanceYearID'));
        //$this->datatables->where('isClosed', 0);
        $this->datatables->from('srp_erp_companyfinanceperiod');
        $this->datatables->add_column('status', '$1', 'load_Financial_year_isactive_status(companyFinancePeriodID,isActive)');
        $this->datatables->add_column('current', '$1', 'load_Financial_year_isactive_current(companyFinancePeriodID,isCurrent,companyFinanceYearID)');
        $this->datatables->add_column('closed', '$1', 'load_financialperiod_isclosed_closed(companyFinancePeriodID,isClosed)');
        $this->datatables->edit_column('DT_RowClass', '$1', 'set_is_closed_is_current_class(isClosed,isCurrent)');
        //$this->datatables->edit_column('DT_RowClass', '$1', 'set_is_current_class(isCurrent)');
        echo $this->datatables->generate();
    }

    function update_financial_year_isactive_status()
    {
        echo json_encode($this->Financial_year_model->update_financial_year_isactive_status());
    }

    function change_financial_period_current()
    {
        echo json_encode($this->Financial_year_model->change_financial_period_current());
    }

    function update_financialperiodclose()
    {
        echo json_encode($this->Financial_year_model->update_financialperiodclose());
    }

    function check_financial_period_iscurrent_activated()
    {
        echo json_encode($this->Financial_year_model->check_financial_period_iscurrent_activated());
    }

    function check_financial_year_iscurrent_activated()
    {
        echo json_encode($this->Financial_year_model->check_financial_year_iscurrent_activated());
    }


}
