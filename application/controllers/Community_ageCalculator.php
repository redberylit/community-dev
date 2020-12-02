<?php
class Community_ageCalculator extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        ini_set('max_execution_time', 360);
        ini_set('memory_limit', '2048M');
    }

    function index(){

        $db2 = $this->load->database('db2', TRUE);
        echo "<h3>Age calculator</h3>";

        $company_data = $db2->select('company_id, host, db_name, db_username, db_password,company_code')->from('srp_erp_company')
            ->join("(SELECT company_id AS comm_comID FROM community_companies WHERE is_active = 1) AS comm_tb",'comm_tb.comm_comID=srp_erp_company.company_id')
            ->where('host is NOT NULL', NULL, FALSE)->where('db_username is NOT NULL', NULL, FALSE)
            ->where('db_password is NOT NULL', NULL, FALSE)->where('db_name is NOT NULL', NULL, FALSE)
            ->get()->result_array();

        if(empty($company_data)){
            die('Age calculator not assigned for any of the company in this host');
        }
        else{
            $this->calculate_commMemberAge($company_data);
        }

    }

    function calculate_commMemberAge($company_data)
    {
        foreach ($company_data as $val) {
            $this->setup_db($val); // setup company db
            $company_id = $val['company_id'];

            if(!empty($company_id)){
                $get_members = $this->db->query("SELECT Com_MasterID,CDOB FROM srp_erp_ngo_com_communitymaster WHERE srp_erp_ngo_com_communitymaster.companyID = $company_id");
                $resGet_members = $get_members->result();

                if(!empty($resGet_members)) {
                    foreach($resGet_members as $rowGet_members) {
                        $memDob = $rowGet_members->CDOB;
                        $today = new DateTime();
                        $birthdate = new DateTime($memDob);
                        $interval = $today->diff($birthdate);
                        $memberAge = $interval->format('%yyrs');

                        $data['Age'] = $memberAge;
                        $update = $this->db->update('srp_erp_ngo_com_communitymaster', $data, array('Com_MasterID' => $rowGet_members->Com_MasterID));
                        }
                   }
                }
              }

        // echo json_encode(array('s', 'Successfully updated','s'));

    }

    function setup_db($conn_data){

        $config['hostname'] = trim($this->encryption->decrypt($conn_data["host"]));
        $config['username'] = trim($this->encryption->decrypt($conn_data["db_username"]));
        $config['password'] = trim($this->encryption->decrypt($conn_data["db_password"]));
        $config['database'] = trim($this->encryption->decrypt($conn_data["db_name"]));
        $config['dbdriver'] = 'mysqli';
        $config['db_debug'] = (ENVIRONMENT !== 'production');
        $config['char_set'] = 'utf8';
        $config['dbcollat'] = 'utf8_general_ci';
        $config['cachedir'] = '';
        $config['swap_pre'] = '';
        $config['encrypt'] = FALSE;
        $config['compress'] = FALSE;
        $config['stricton'] = FALSE;
        $config['failover'] = array();
        $config['save_queries'] = TRUE;

        //echo $conn_data['company_name'] . '<br>'.$config['database'] . '<br>';
        $this->load->database($config, FALSE, TRUE);
    }

}

/** ================================
 * -- File Name : Community_ageCalculator.php
 * -- Project Name : Community
 * -- Module Name : Age Calculator
 * -- Author : Moufiya
 * -- Create date : 07 - July 2020
 * -- Description : This controller used for automate function like cron jobs, age calculation
 */

