<?php

class Budget_model extends ERP_Model
{

    function get_budget_master_header($budgetAutoID)
    {
        $q = "SELECT * FROM srp_erp_budgetmaster WHERE budgetAutoID={$budgetAutoID}";
        $results = $this->db->query($q)->row_array();
        return $results;
    }

    function fetch_finance_year_period_budget(){
        $convertFormat=convert_date_format_sql();
        $this->db->select('companyFinancePeriodID,companyFinanceYearID,DATE_FORMAT(dateFrom,\''.$convertFormat.'\') AS dateFrom,DATE_FORMAT(dateTo,\''.$convertFormat.'\') AS dateTo ');
        $this->db->from('srp_erp_companyfinanceperiod');
        $this->db->where('companyFinanceYearID',$this->input->post('companyFinanceYearID'));
        //$this->db->where('isActive',1);
        //$this->db->where('isCurrent',1);
        //$this->db->where('isClosed',0);
        return $this->db->get()->result_array();
    }

    function fetch_finance_year_period_budget_load_missing($companyFinanceYearID){
        $convertFormat=convert_date_format_sql();
        $this->db->select('companyFinancePeriodID,companyFinanceYearID,DATE_FORMAT(dateFrom,\''.$convertFormat.'\') AS dateFrom,DATE_FORMAT(dateTo,\''.$convertFormat.'\') AS dateTo ');
        $this->db->from('srp_erp_companyfinanceperiod');
        $this->db->where('companyFinanceYearID',$companyFinanceYearID);
        //$this->db->where('isActive',1);
        //$this->db->where('isCurrent',1);
        //$this->db->where('isClosed',0);
        return $this->db->get()->result_array();
    }
}