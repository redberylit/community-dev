<?php

class journeyplan_model extends ERP_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function load_vehicale_details()
    {
        $convertFormat = convert_date_format_sql();
        $companyid = current_companyID();
        $vehicalemasterid = trim($this->input->post('vehicalemasterid'));
        $data = $this->db->query("select *,CASE WHEN `isActive` = \"1\" THEN \"Active\" ELSE \"In Actice\" END vehicalestatus from fleet_vehiclemaster where companyID = $companyid AND vehicleMasterID = $vehicalemasterid")->row_array();
        return $data;
    }

    function load_driver_details()
    {
        $companyid = current_companyID();
        $drivermasterid = trim($this->input->post('drivermasterid'));
        $data = $this->db->query("select * from fleet_drivermaster  where companyID = $companyid AND driverMasID = $drivermasterid")->row_array();
        return $data;
    }

    function fetch_employee_details()
    {
        $companyid = current_companyID();
        $employeeid = trim($this->input->post('employeeid'));
        $data = $this->db->query("select * from srp_employeesdetails where Erp_companyID = $companyid AND EIdNo = $employeeid")->row_array();
        return $data;
    }

    function fetch_jp_number()
    {
        $companyid = current_companyID();
        $serialno = $this->db->query("SELECT IF( isnull( MAX( serialNumber ) ), 1, ( MAX( serialNumber ) + 1 ) ) AS serialNumber FROM srp_erp_journeyplan_master WHERE companyID = $companyid")->row_array();
        $company_code = $this->common_data['company_data']['company_code'];

        $data = ($company_code . '/' . 'JP' . str_pad($serialno['serialNumber'], 6, '0', STR_PAD_LEFT));

        return $data;

    }

    function save_journeyplan_header()
    {
        $this->db->trans_start();
        $date_format_policy = date_format_policy();
        $company_code = $this->common_data['company_data']['company_code'];
        $companyID = $this->common_data['company_data']['company_id'];
        $departuredate = $this->input->post('departuredate');
        $jpmasterid = trim($this->input->post('jpmasterid'));
        $curDate = format_date_mysql_datetime();
        $format_departuredate = null;
        if (isset($departuredate) && !empty($departuredate)) {
            $format_departuredate = input_format_date($departuredate, $date_format_policy);
        }


        $data['departureDate'] = $format_departuredate;
        $data['vehicleID'] = $this->input->post('vehiclenumber');
        $data['driverID'] = $this->input->post('drivername');
        $data['driverMobileNumber'] = $this->input->post('phonenumber');
        $data['commentsForDriver'] = $this->input->post('commentfordrivers');
        $data['offlineTrackingRefNo'] = $this->input->post('offlinetrackingnumber');
        if ($this->input->post('employeeID')) {
            $empdet = explode('|', trim($this->input->post('employee_det')));
            $data['journeyManagerName'] = $empdet[1];
            $data['journeyManagerEmpID'] = trim($this->input->post('employeeID'));
            $data['journeyManagerOfficeNo'] = $this->input->post('jmphonenumber');
            $data['journeyManagerMobileNo'] = $this->input->post('jmphonenumbermob');
        } else {
            $data['journeyManagerName'] = trim($this->input->post('journeymanager'));
            $data['journeyManagerEmpID'] = NULL;
            $data['journeyManagerOfficeNo'] = $this->input->post('jmphonenumber');
            $data['journeyManagerMobileNo'] = $this->input->post('jmphonenumbermob');
        }

        $data['reasonForNightDriving'] = $this->input->post('reasonnightdriving');
        $data['vehicleDailyCheck'] = $this->input->post('vehicaledailychk');
        $data['counsellingForDriver'] = $this->input->post('counsellingdr');
        $data['companyID'] = $companyID;
        $data['timestamp'] = $curDate;
        if ($jpmasterid) {
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedDate'] = $this->common_data['current_date'];
            $this->db->where('journeyPlanMasterID', $jpmasterid);
            $this->db->update('srp_erp_journeyplan_master', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error while updating the Journey Plan!');
            } else {
                $this->db->trans_commit();
                return array('s', 'Journey Plan updated Successfully!', $jpmasterid);
            }
        } else {
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdDate'] = $this->common_data['current_date'];
            $data['documentID'] = 'JP';
            $serial = $this->db->query("SELECT IF( isnull( MAX( serialNumber ) ), 1, ( MAX( serialNumber ) + 1 ) ) AS serialNumber FROM srp_erp_journeyplan_master WHERE companyID = $companyID")->row_array();
            $data['serialNumber'] = $serial['serialNumber'];
            $data['documentCode'] = ($company_code . '/' . 'JP' . str_pad($data['serialNumber'], 6, '0', STR_PAD_LEFT));
            $this->db->insert('srp_erp_journeyplan_master', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error Occured' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', '' . $data['documentCode'] . ' Journey Plan created successfully.', $last_id);
            }
        }
    }
    function fetch_jp_header_details()
    {
      $companyid = current_companyID();
      $jpmasterid = trim($this->input->post('jpnumber'));
        $convertFormat = convert_date_format_sql();
        $data = $this->db->query("SELECT jpmaster.*,DATE_FORMAT(jpmaster.departureDate,'.$convertFormat. %h:%i:%s') AS departureDateconverted,driver.driverName,driver.driverCode,vehicalemaster.vehDescription,vehicalemaster.vehicleCode,vehicalemaster.ivmsNo FROM
	srp_erp_journeyplan_master jpmaster LEFT JOIN fleet_drivermaster driver on driver.driverMasID =  jpmaster.driverID LEFT JOIN fleet_vehiclemaster vehicalemaster on vehicalemaster.vehicleMasterID =  jpmaster.vehicleID
WHERE jpmaster.companyID = $companyid AND jpmaster.journeyPlanMasterID = $jpmasterid")->row_array();
        return $data;
    }
    function save_jp_details()
    {
        $this->db->trans_start();
        $restyn = $this->input->post('restyn');
        $jpnumber = $this->input->post('jpnumberadddetail');
        $arrivedDate = $this->input->post('arrivedate');
        $dateDepart = $this->input->post('departdate');
        $placename = $this->input->post('placenames');
        $arivetime = $this->input->post('arrivetime');
        $departtime = $this->input->post('departtime');
        $sleep = $this->input->post('sleepmotelname');
        $date_format_policy = date_format_policy();

        $this->db->delete('srp_erp_journeyplan_routedetails', array('journeyPlanMasterID' => $jpnumber));
        foreach ($restyn as $key => $val) {
            $format_arrivedDate = null;
            $format_dateDepart = null;
            if (isset($arrivedDate[$key]) && !empty($arrivedDate[$key])) {
                $format_arrivedDate = input_format_date($arrivedDate[$key], $date_format_policy);
            }
            if (isset($dateDepart[$key]) && !empty($dateDepart[$key])) {
                $format_dateDepart = input_format_date($dateDepart[$key], $date_format_policy);
            }
            $data[$key]['journeyPlanMasterID'] = $jpnumber;
            $data[$key]['placeName'] = $placename[$key];
              if($key == 0)
             {
                 $data[$key]['dateArived'] = '';
                 $data[$key]['timeArrive'] = '' ;
             }else
              {
                  $data[$key]['dateArived'] = $format_arrivedDate;
                  $data[$key]['timeArrive'] = $arivetime[$key] ;
              }

            $data[$key]['dateDepart'] =  $format_dateDepart;
            $data[$key]['timeDepart'] =  $departtime[$key];
            $data[$key]['restTick'] =  $val;
            $data[$key]['sleep'] =  $sleep[$key];
            $data[$key]['companyID'] =  current_companyID();
            $data[$key]['createdUserID'] = $this->common_data['current_userID'];
            $data[$key]['createdPCID'] = $this->common_data['current_pc'];
            $data[$key]['createdDate'] = $this->common_data['current_date'];
        }
        $this->db->insert_batch('srp_erp_journeyplan_routedetails', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Journey Plan Detail :  Save Failed ' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Journey Plan Detail :  Saved Successfully.');
        }
    }
    function fetch_item_jv_table()
    {
        $companyid = current_companyID();
        $jpnumber = $this->input->post('jpnumber');
        $convertFormat = convert_date_format_sql();

        $data['detail'] = $this->db->query("select *,DATE_FORMAT( dateArived, '$convertFormat' ) AS dateArivedcon,DATE_FORMAT( dateDepart, '$convertFormat' ) AS dateDepartcon  from srp_erp_journeyplan_routedetails where companyID = $companyid  And journeyPlanMasterID = $jpnumber")->result_array();
        return $data;
    }
    function save_jp_passanger_details()
    {
        $this->db->trans_start();
        $jpnumber = $this->input->post('jpnumberadd');
        $passanger = $this->input->post('passangername');
        $conatctno = $this->input->post('contactno');
        $this->db->delete('srp_erp_journeyplan_passengerdetails', array('journeyPlanMasterID' => $jpnumber));
        foreach ($passanger as $key => $val) {

            $data[$key]['journeyPlanMasterID'] = $jpnumber;
            $data[$key]['passengerName'] = $val;
            $data[$key]['contactNo'] = $conatctno[$key];
            $data[$key]['companyID'] =  current_companyID();
            $data[$key]['createdUserID'] = $this->common_data['current_userID'];
            $data[$key]['createdPCID'] = $this->common_data['current_pc'];
            $data[$key]['createdDate'] = $this->common_data['current_date'];

        }
    
        $datapassenger['noOfPassengers'] = sizeof($passanger);
        $this->db->where('journeyPlanMasterID', $jpnumber);
        $this->db->update('srp_erp_journeyplan_master', $datapassenger);

        $this->db->insert_batch('srp_erp_journeyplan_passengerdetails', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Journey Plan Passanger Detail :  Save Failed ' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Journey Plan Passanger Detail :  Saved Successfully.');
        }
    }
    function fetch_passenger_detail_tbl()
    {
        $companyid = current_companyID();
        $jpnumber = $this->input->post('jpnumber');
        $data['detail'] = $this->db->query("select * from srp_erp_journeyplan_passengerdetails where companyID = $companyid AND journeyPlanMasterID = $jpnumber ")->result_array();
        return $data;
    }
    function jp_confirmation()
    {
        {
            $jpmasterid = trim($this->input->post('jpnumber'));
            $this->db->select('journeyPlanMasterID');
            $this->db->where('journeyPlanMasterID', $jpmasterid);
            $this->db->where('confirmedYN', 1);
            $this->db->from('srp_erp_journeyplan_master');
            $Confirmed = $this->db->get()->row_array();
            if (!empty($Confirmed)) {
                    $this->session->set_flashdata('w', 'Document already confirmed ');
                    return false;
                } else {

                    $this->load->library('approvals');
                    $this->db->select('journeyPlanMasterID, documentID,documentCode');
                    $this->db->where('journeyPlanMasterID', $jpmasterid);
                    $this->db->from('srp_erp_journeyplan_master');
                    $app_data = $this->db->get()->row_array();


                    $approvals_status = $this->approvals->CreateApproval('JP', $app_data['journeyPlanMasterID'], $app_data['documentCode'], 'Journey Plan', 'srp_erp_journeyplan_master', 'journeyPlanMasterID');

                if ($approvals_status == 1) {
                        $data = array(
                            'confirmedYN' => 1,
                            'confirmedDate' => $this->common_data['current_date'],
                            'confirmedByEmpID' => $this->common_data['current_userID'],
                            'confirmedByName' => $this->common_data['current_user']


                        );

                        $this->db->where('journeyPlanMasterID', trim($this->input->post('jpnumber')));
                        $this->db->update('srp_erp_journeyplan_master', $data);

                        return array('error' => 0, 'message' => 'document successfully confirmed');

                    } else {
                        return array('error' => 1, 'message' => 'Approval setting are not configured!, please contact your system team.');
                    }


                }


    }
}
function fetch_jp_details($jpmasterid)
{
    $companyid = current_companyID();
    $convertFormat = convert_date_format_sql();


    $data['detail'] = $this->db->query("SELECT
	mastertbl.*,
	DATE_FORMAT( departureDate, '%d-%m-%Y' ) AS departureDatecon,
	fleettbl.driverName,
	vehicalemaster.ivmsNo as invmsnovehicalemaster,
vehicalemaster.VehicleNo as VehicleNo,
vehicalemaster.vehDescription as vehDescription,
DATE_FORMAT(vehicalemaster.licenseDate,'$convertFormat') as licensedate,
DATE_FORMAT(vehicalemaster.insuranceDate,'$convertFormat') as insurancedate,
DATE_FORMAT(mastertbl.approvedDate,'$convertFormat') as approvedDatemaster,
DATE_FORMAT(mastertbl.createdDate,'$convertFormat') as createdDate,
	CASE
	
	WHEN mastertbl.vehicleDailyCheck = \"1\" THEN
	\"Yes\" 
	ELSE 
		\"No\"
	END vehicleDailyCheckyn,
	CASE
WHEN mastertbl.counsellingForDriver = \"1\" THEN
	\"Yes\" 
	ELSE 
		\"No\"
	END counsellingForDriveryn 
FROM
	srp_erp_journeyplan_master  mastertbl
	left join fleet_drivermaster fleettbl on fleettbl.driverMasID = mastertbl.driverID
		LEFT JOIN fleet_vehiclemaster vehicalemaster ON vehicalemaster.vehicleMasterID = mastertbl.vehicleID 
WHERE
	mastertbl.companyID = $companyid 
	AND journeyPlanMasterID = $jpmasterid")->row_array();


    $data['passengerdet'] = $this->db->query("SELECT
	* 
FROM
	`srp_erp_journeyplan_passengerdetails`
	where 
	companyID = $companyid
	AND journeyPlanMasterID = $jpmasterid")->result_array();

    $data['routedetail'] = $this->db->query("SELECT
	*,
	CONCAT(DATE_FORMAT( dateDepart, '%d-%m-%Y' ),' - ',timeDepart) as departureDatecon,
		CONCAT(DATE_FORMAT( dateArived, '%d-%m-%Y' ),' - ',timeArrive) as arrivedcon,
	CASE
WHEN restTick = \"1\" THEN
	\"Yes\" 
	ELSE 
		\"No\"
	END restTick 
FROM
	`srp_erp_journeyplan_routedetails` 
WHERE
	companyID = $companyid 
	AND journeyPlanMasterID = $jpmasterid")->result_array();

    return $data;
}
    function save_jp_approval()
    {
        $this->db->trans_start();
        $this->load->library('approvals');
        $system_id = trim($this->input->post('jurneyplanid'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('status'));
        $comments = '';
        $approvals_status = $this->approvals->approve_document($system_id, $level_id, $status, $comments, 'JP');

        if ($approvals_status == 1) {
            $data['approvedYN'] = $status;
            $data['approvedbyEmpID'] =  $this->common_data['current_userID'];
            $data['approvedbyEmpName'] =  $this->common_data['current_user'];
            $data['approvedDate'] = $this->common_data['current_date'];


            $this->db->where('journeyPlanMasterID', $system_id);
            $this->db->update('srp_erp_journeyplan_master', $data);
            $this->session->set_flashdata('s', 'Journey Plan Approved Sucessfully.');
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return true;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    function save_jp_status()
    {

        $this->db->trans_start();
        $jpmasterid = $this->input->post('masterid');

        $this->db->select('status');
        $this->db->where('journeyPlanMasterID', $jpmasterid);
        $this->db->from('srp_erp_journeyplan_master');
        $jpmaster = $this->db->get()->row_array();
        if ($jpmaster['status']==3) {
            return array('e', 'You cannot change the status journey plan already closed');
        }else
        {

            $data['status'] = $this->input->post('status');
            $data['statusComments'] = $this->input->post('comment');
            $data['journeyPlanMasterID'] = $jpmasterid;
            $data['companyID'] = current_companyID();
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] =  $this->common_data['current_userID'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $this->db->insert('srp_erp_journeyplanstatus', $data);


            $dataup['status'] = $this->input->post('status');
            $dataup['statusComment'] = $this->input->post('comment');

            if($dataup['status']==3)
            {
                $dataup['closedComment'] = $this->input->post('comment');
                $dataup['closedByEmpID'] = $this->common_data['current_userID'];
                $dataup['closedByEmpName'] = $this->common_data['current_user'];
                $dataup['closedByDate'] = $this->common_data['current_date'];
            }
            if($dataup['status']==4)
            {
                $dataup['canceledYN'] = $this->input->post('status');
                $dataup['canceledDate'] = $this->common_data['current_date'];
                $dataup['canceledEmpId'] = $this->common_data['current_userID'];
                $dataup['canceledEmpName'] = $this->common_data['current_userID'];
                $dataup['canceledComment'] = $this->input->post('comment');
            }

            $this->db->where('journeyPlanMasterID', $jpmasterid);
            $this->db->update('srp_erp_journeyplan_master', $dataup);


            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error while updating the Journey Plan!');
            } else {
                $this->db->trans_commit();
                return array('s', 'Journey Plan Status updated Successfully!', $jpmasterid);
            }
        }


    }
    public function jpdetails($jpid){

        $this->db->select('*');

        $this->db->from('srp_erp_journeyplan_master');

        $this->db->where('journeyPlanMasterID',$jpid);

        $query = $this->db->get();

        if($query->num_rows() == 1)
        {

            return $query->result_array();

        }
        else
        {

            return 0;

        }

    }
}