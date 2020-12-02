<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Buyback_model extends ERP_Model
{
    function __contruct()
    {
        parent::__contruct();
    }

    function new_location()
    {
        $location = trim($this->input->post('location'));
        $companyID = current_companyID();
        $isExist = $this->db->query("SELECT description FROM srp_erp_buyback_locations WHERE companyID={$companyID} AND description ='$location' ")->row('description');

        if (isset($isExist)) {
            return array('e', 'Area is already Exists');
        } else {

            $data = array(
                'description' => $location,
                'masterID' => 0,
                'companyID' => $this->common_data['company_data']['company_id'],
                'createdUserGroup' => $this->common_data['user_group'],
                'createdPCID' => $this->common_data['current_pc'],
                'createdUserID' => $this->common_data['current_userID'],
                'createdUserName' => $this->common_data['current_user'],
                'createdDateTime' => $this->common_data['current_date'],
            );

            $this->db->insert('srp_erp_buyback_locations', $data);
            if ($this->db->affected_rows() > 0) {
                $titleID = $this->db->insert_id();
                return array('s', 'Area is created successfully.', $titleID);
            } else {
                return array('e', 'Error in Area Creating');
            }
        }

    }

    function save_buyback_sub_location()
    {
        $location = trim($this->input->post('location'));
        $subLocationID = trim($this->input->post('subLocation'));
        $companyID = current_companyID();
        $isExist = $this->db->query("SELECT description FROM srp_erp_buyback_locations WHERE companyID={$companyID} AND masterID = {$location} AND description ='$subLocationID' ")->row('description');

        if (isset($isExist)) {
            return array('e', 'Sub Area is already Exists');
        } else {

            $data = array(
                'description' => $subLocationID,
                'masterID' => $location,
                'companyID' => $this->common_data['company_data']['company_id'],
                'createdUserGroup' => $this->common_data['user_group'],
                'createdPCID' => $this->common_data['current_pc'],
                'createdUserID' => $this->common_data['current_userID'],
                'createdUserName' => $this->common_data['current_user'],
                'createdDateTime' => $this->common_data['current_date'],
            );

            $this->db->insert('srp_erp_buyback_locations', $data);
            if ($this->db->affected_rows() > 0) {
                $titleID = $this->db->insert_id();
                return array('s', 'Sub Area is created successfully.', $titleID);
            } else {
                return array('e', 'Error in Sub Area Creating');
            }
        }

    }

    function save_farm_header()
    {
        $this->db->trans_start();
        $date_format_policy = date_format_policy();
        $this->load->library('sequence');

        $companyID = $this->common_data['company_data']['company_id'];

        $farmID = trim($this->input->post('farmID'));

        $registeredDate = trim($this->input->post('registeredDate'));

        $farmSystemCode = $this->sequence->sequence_generator('F');

        $format_registeredDate = null;
        if (isset($registeredDate) && !empty($registeredDate)) {
            $format_registeredDate = input_format_date($registeredDate, $date_format_policy);
        }

        $data['description'] = trim($this->input->post('description'));
        $data['locationID'] = trim($this->input->post('locationID'));
        $data['subLocationID'] = trim($this->input->post('subLocationID'));
        $data['email'] = trim($this->input->post('email'));
        $data['farmSecondaryCode'] = trim($this->input->post('farmSecondaryCode'));
        $data['farmType'] = trim($this->input->post('farmType'));
        $data['farmerCurrencyID'] = trim($this->input->post('transactionCurrencyID'));
        $data['farmersLiabilityGLautoID'] = trim($this->input->post('farmersLiabilityGLautoID'));
        $data['registeredDate'] = $format_registeredDate;
        $data['noOfCages'] = trim($this->input->post('noOfCages'));
        $data['capacity'] = trim($this->input->post('capacity'));
        $data['contactPerson'] = trim($this->input->post('contactPerson'));
        $data['phoneHome'] = trim($this->input->post('phoneHome'));
        $data['phoneMobile'] = trim($this->input->post('phoneMobile'));
        $data['postalCode'] = trim($this->input->post('postalcode'));
        $data['city'] = trim($this->input->post('city'));
        $data['state'] = trim($this->input->post('state'));
        $data['countryID'] = trim($this->input->post('countryID'));
        $data['address'] = trim($this->input->post('address'));
        $data['narration'] = trim($this->input->post('narration'));
        $data['isActive'] = trim($this->input->post('isActive'));
        $data['depositAmount'] = trim($this->input->post('depositAmount'));

        if ($farmID) {
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];

            $this->db->where('farmID', $farmID);
            $this->db->update('srp_erp_buyback_farmmaster', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Farm Update ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Farm Updated Successfully.');
            }
        } else {
            $data['farmSystemCode'] = $farmSystemCode;
            $data['companyID'] = $companyID;
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_farmmaster', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Farm Creating' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Farm is created successfully.');

            }
        }
    }

    function load_farm_header()
    {
        $farmID = trim($this->input->post('farmID'));
        $this->db->select('*');
        $this->db->where('farmID', $farmID);
        $this->db->from('srp_erp_buyback_farmmaster');
        return $this->db->get()->row_array();
    }

    function add_farm_batch()
    {
        $this->db->trans_start();
        $date_format_policy = date_format_policy();

        $companyID = $this->common_data['company_data']['company_id'];
        $this->load->library('sequence');

        $farmID = trim($this->input->post('farmID'));
        $batchMasterID = trim($this->input->post('batchMasterID'));

        $batchStartDate = trim($this->input->post('batchStartDate'));
        $batchClosingDate = trim($this->input->post('batchClosingDate'));
        $description = trim($this->input->post('descrip'));
        $format_batchStartDate = null;


        if (isset($batchStartDate) && !empty($batchStartDate)) {
            $format_batchStartDate = input_format_date($batchStartDate, $date_format_policy);
        }

        $format_batchClosingDate = null;
        if (isset($batchClosingDate) && !empty($batchClosingDate)) {
            $format_batchClosingDate = input_format_date($batchClosingDate, $date_format_policy);
        }

        $isclosed = 0;
        if (!empty($this->input->post('isClosed'))) {
            $isclosed = 1;
        }

        $batchCode = $this->sequence->sequence_generator('B');

        $data['batchStartDate'] = $format_batchStartDate;
        $data['batchClosingDate'] = $format_batchClosingDate;
        $data['WIPGLAutoID'] = trim($this->input->post('WIPGLAutoID'));
        $data['DirectWagesGLAutoID'] = trim($this->input->post('DirectWagesGLAutoID'));
        $data['isclosed'] = $isclosed;
        $data['farmID'] = $farmID;
        $data['description'] = $description;

        if ($batchMasterID) {
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];

            $this->db->where('batchMasterID', $batchMasterID);
            $this->db->update('srp_erp_buyback_batch', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Batch Update ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Batch Updated Successfully.');
            }
        } else {
            $data['batchCode'] = $batchCode;
            $data['companyID'] = $companyID;
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_batch', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Batch Creating' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Batch is created successfully.');

            }
        }
    }

    function load_farm_batch_header()
    {
        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $this->db->select('srp_erp_buyback_batch.*,dispatch.batchMasterID as dnbatchid,`dispatch`.`confirmedYN` AS `confirmedYNbatch`,,DATE_FORMAT(batchStartDate,\'' . $convertFormat . '\') AS batchStartDate,DATE_FORMAT(batchClosingDate,\'' . $convertFormat . '\') AS batchClosingDate');
        $this->db->where('srp_erp_buyback_batch.batchMasterID', $this->input->post('batchMasterID'));
        $this->db->join('(select batchMasterID,confirmedYN from srp_erp_buyback_dispatchnote dn where dn.batchMasterID =' . $this->input->post('batchMasterID') . ')dispatch', 'dispatch.batchMasterID = srp_erp_buyback_batch.batchMasterID', 'left');
        $this->db->from('srp_erp_buyback_batch');
        return $this->db->get()->row_array();


    }

    function fetch_supplier_data($supplierID)
    {
        $this->db->select('*');
        $this->db->from('srp_erp_suppliermaster');
        $this->db->where('supplierAutoID', $supplierID);
        return $this->db->get()->row_array();
    }

    function add_farm_party()
    {
        $this->db->trans_start();
        $companyID = $this->common_data['company_data']['company_id'];

        $farmID = trim($this->input->post('farmID'));
        $partyType = trim($this->input->post('partyType'));
        $farmPartyAutoID = trim($this->input->post('farmPartyAutoID'));

        if (!$farmPartyAutoID) {
            $this->db->select('partyType,farmID');
            $this->db->from('srp_erp_buyback_farmcustomersupplier');
            $this->db->where('partyType', $partyType);
            $this->db->where('farmID', $farmID);
            $this->db->where('companyID', $companyID);
            $this->db->where('isActive', 1);
            $party_detail = $this->db->get()->row_array();
            if (!empty($party_detail)) {
                return array('w', 'Only One Party Can be add');
            }
        }

        $isActive = 0;
        if (!empty($this->input->post('isActive'))) {
            $isActive = 1;
        }
        if ($partyType == 1) {
            $partyAutoID = trim($this->input->post('supplierPrimaryCode'));
        } else {
            $partyAutoID = trim($this->input->post('customerID'));
        }

        $data['partyType'] = $partyType;
        $data['partyAutoID'] = $partyAutoID;
        $data['farmID'] = $farmID;
        $data['isActive'] = $isActive;
        if ($farmPartyAutoID) {
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];

            $this->db->where('farmPartyAutoID', $farmPartyAutoID);
            $this->db->update('srp_erp_buyback_farmcustomersupplier', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Party Update ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Party Updated Successfully.');
            }
        } else {
            $data['companyID'] = $companyID;
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_farmcustomersupplier', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Party Creating' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Party is created successfully.');

            }
        }
    }

    function load_farm_party_header()
    {
        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $this->db->select('farmPartyAutoID,partyType,partyAutoID,farmID,isActive');
        $this->db->where('farmPartyAutoID', $this->input->post('farmPartyAutoID'));
        $this->db->from('srp_erp_buyback_farmcustomersupplier');
        return $this->db->get()->row_array();

    }

    function add_farm_dealers()
    {
        $this->db->trans_start();
        $companyID = $this->common_data['company_data']['company_id'];

        $farmID = trim($this->input->post('farmID'));
        $farmDealerID = trim($this->input->post('farmDealerID'));
        $customerID = trim($this->input->post('customerID'));

        if (!$farmDealerID) {
            $this->db->select('farmID,customerAutoID');
            $this->db->from('srp_erp_buyback_farmdealers');
            $this->db->where('customerAutoID', trim($this->input->post('customerID')));
            $this->db->where('farmID', $farmID);
            $this->db->where('companyID', $companyID);
            $party_detail = $this->db->get()->row_array();
            if (!empty($party_detail)) {
                return array('w', 'Dealer is Already added');
            }
        }
        $isActive = 0;
        if (!empty($this->input->post('isActive'))) {
            $isActive = 1;
        }

        $customernamebyid = "SELECT
                    customerName
                FROM
                    srp_erp_customermaster
                WHERE
                   customerAutoID =  $customerID AND companyID = $companyID ";
        $result = $this->db->query($customernamebyid)->row_array();

        $data['customerAutoID'] = trim($this->input->post('customerID'));
        $data['dealerName'] = $result['customerName'];
        $data['companyCode'] = $this->common_data['company_data']['company_code'];
        $data['farmID'] = $farmID;
        $data['isActive'] = $isActive;
        if ($farmDealerID) {
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];

            $this->db->where('farmDealerID', $farmDealerID);
            $this->db->update('srp_erp_buyback_farmdealers', $data);

            $isactivestatus = "SELECT
	isActive
FROM
	srp_erp_buyback_farmdealers 
WHERE
	companyID = '" . $companyID . "'
	AND isActive = '1' 
	AND farmID = '" . $farmID . "' AND farmDealerID != '" . $farmDealerID . "' ";
            $result = $this->db->query($isactivestatus)->row_array();
            if ($result && $this->input->post('isActive') == 1) {
                return array('e', 'There is already Active Dealer');
            }

            $farmdealer = "SELECT
	farmID,customerAutoID
FROM
	srp_erp_buyback_farmdealers 
WHERE
	companyID = '" . $companyID . "'
	AND isActive = '1' 
	AND customerAutoID = '" . $customerID . "'
	AND farmID = '" . $farmID . "' AND farmDealerID != '" . $farmDealerID . "' ";
            $result = $this->db->query($farmdealer)->row_array();
            if ($result) {
                return array('w', 'Field Officer is Already added');
            } else {
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return array('e', 'Error in Dealer Update ' . $this->db->_error_message());

                } else {
                    $this->db->trans_commit();
                    return array('s', 'Dealer Updated Successfully.');
                }
            }
        } else {


            $this->db->select('isActive');
            $this->db->from('srp_erp_buyback_farmdealers');
            $this->db->where('companyID', current_companyID());
            $this->db->where('isActive', 1);
            $this->db->where('farmID', $farmID);
            $result = $this->db->get()->row_array();
            if (!empty($result) && $this->input->post('isActive') == 1) {
                return array('e', 'There is already Active Dealer');
            } else {
                $data['companyID'] = $companyID;
                $data['createdUserGroup'] = $this->common_data['user_group'];
                $data['createdPCID'] = $this->common_data['current_pc'];
                $data['createdUserID'] = $this->common_data['current_userID'];
                $data['createdUserName'] = $this->common_data['current_user'];
                $data['createdDateTime'] = $this->common_data['current_date'];
                $this->db->insert('srp_erp_buyback_farmdealers', $data);
                $last_id = $this->db->insert_id();
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return array('e', 'Error in Dealer Creating' . $this->db->_error_message());
                } else {
                    $this->db->trans_commit();
                    return array('s', 'Dealer is created successfully.');

                }
            }
        }
    }

    function load_farm_dealers_header()
    {
        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $this->db->select('farmDealerID,customerAutoID,isActive');
        $this->db->where('farmDealerID', $this->input->post('farmDealerID'));
        $this->db->from('srp_erp_buyback_farmdealers');
        return $this->db->get()->row_array();
    }

    function add_farm_warehouse()
    {
        $this->db->trans_start();
        $companyID = $this->common_data['company_data']['company_id'];

        $farmID = trim($this->input->post('farmID'));
        $farmWarehouseID = trim($this->input->post('farmWarehouseID'));

        if (!$farmWarehouseID) {
            $this->db->select('farmID,warehouseMasterID');
            $this->db->from('srp_erp_buyback_farmwarehouses');
            $this->db->where('warehouseMasterID', trim($this->input->post('warehouseMasterID')));
            $this->db->where('farmID', $farmID);
            $this->db->where('companyID', $companyID);
            $party_detail = $this->db->get()->row_array();
            if (!empty($party_detail)) {
                return array('w', 'Warehouse is Already added');
            }
        }
        $isActive = 0;
        if (!empty($this->input->post('isActive'))) {
            $isActive = 1;
        }
        $data['warehouseMasterID'] = trim($this->input->post('warehouseMasterID'));
        $data['farmID'] = $farmID;
        $data['isActive'] = $isActive;
        if ($farmWarehouseID) {
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];

            $this->db->where('farmWarehouseID', $farmWarehouseID);
            $this->db->update('srp_erp_buyback_farmwarehouses', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Warehouse Update ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Warehouse Updated Successfully.');
            }
        } else {
            $data['companyID'] = $companyID;
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_farmwarehouses', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Warehouse Creating' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Warehouse is created successfully.');

            }
        }
    }

    function load_farm_warehouse_header()
    {
        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $this->db->select('farmWarehouseID,warehouseMasterID,isActive');
        $this->db->where('farmWarehouseID', $this->input->post('farmWarehouseID'));
        $this->db->from('srp_erp_buyback_farmwarehouses');
        return $this->db->get()->row_array();
    }

    function add_farm_notes()
    {
        $this->db->trans_start();

        $companyID = $this->common_data['company_data']['company_id'];

        $farmID = trim($this->input->post('farmID'));

        $data['contactID'] = $farmID;
        $data['description'] = trim($this->input->post('description'));
        $data['companyID'] = $companyID;
        $data['documentID'] = 1;
        $data['createdUserGroup'] = $this->common_data['user_group'];
        $data['createdPCID'] = $this->common_data['current_pc'];
        $data['createdUserID'] = $this->common_data['current_userID'];
        $data['createdUserName'] = $this->common_data['current_user'];
        $data['createdDateTime'] = $this->common_data['current_date'];
        $this->db->insert('srp_erp_buyback_notes', $data);
        $last_id = $this->db->insert_id();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Contact Note Save Failed ' . $this->db->_error_message(), $last_id);
        } else {
            $this->db->trans_commit();
            return array('s', 'Contact Note Saved Successfully.');

        }
    }

    function load_all_notes()
    {
        $data = $this->db->query("select * from srp_erp_buyback_notes where notesID=notesID")->row_array();
        return $data;
    }

    function save_dispatch_note_header()
    {
        $this->db->trans_start();
        $date_format_policy = date_format_policy();

        $dispatchAutoID = trim($this->input->post('dispatchAutoID'));
        $dispatchedDate = $this->input->post('dispatchedDate');
        $deliveredDate = $this->input->post('deliveredDate');
        $format_dispatchedDate = null;
        if (isset($dispatchedDate) && !empty($dispatchedDate)) {
            $format_dispatchedDate = input_format_date($dispatchedDate, $date_format_policy);
        }
        $format_deliveredDate = null;
        if (isset($deliveredDate) && !empty($deliveredDate)) {
            $format_deliveredDate = input_format_date($deliveredDate, $date_format_policy);
        }
        $year = explode(' - ', trim($this->input->post('companyFinanceYear')));
        $FYBegin = input_format_date($year[0], $date_format_policy);
        $FYEnd = input_format_date($year[1], $date_format_policy);
        $currency_code = explode('|', trim($this->input->post('currency_code')));
        $segment = explode('|', trim($this->input->post('segment')));
        $delivery_location = explode('|', trim($this->input->post('delivery_location')));
        $supplier_arr = $this->fetch_supplier_data(trim($this->input->post('supplierID')));
        $farmid = trim($this->input->post('farmID'));

        $farmliabilityglauto = "SELECT farmersLiabilityGLautoID FROM srp_erp_buyback_farmmaster WHERE farmID = $farmid";
        $result = $this->db->query($farmliabilityglauto)->row_array();

        $farmerliabilityglauto = $result['farmersLiabilityGLautoID'];

        $chartofaccounts = "SELECT systemAccountCode,GLSecondaryCode,GLDescription,subCategory FROM  srp_erp_chartofaccounts WHERE GLAutoID = $farmerliabilityglauto";
        $chartofacc = $this->db->query($chartofaccounts)->row_array();

        $data['farmerliabilityAutoID'] = $farmerliabilityglauto;
        $data['farmerliabilitySystemGLCode'] = $chartofacc['systemAccountCode'];
        $data['farmerliabilityGLAccount'] = $chartofacc['GLSecondaryCode'];
        $data['farmerliabilityDescription'] = $chartofacc['GLDescription'];
        $data['farmerliabilityType'] = $chartofacc['subCategory'];
        $data['dispatchType'] = trim($this->input->post('dispatchType'));
        $data['batchMasterID'] = trim($this->input->post('batchMasterID'));
        $data['farmID'] = trim($this->input->post('farmID'));
        $data['documentID'] = 'BBDPN';
        $data['documentDate'] = $format_dispatchedDate;
        $data['dispatchedDate'] = $format_deliveredDate;
        $data['referenceNo'] = trim($this->input->post('referenceno'));
        $data['narration'] = trim($this->input->post('narration'));
        $data['companyFinanceYearID'] = trim($this->input->post('financeyear'));
        $data['companyFinanceYear'] = trim($this->input->post('companyFinanceYear'));

        $data['FYBegin'] = trim($FYBegin);
        $data['FYEnd'] = trim($FYEnd);
        $data['companyFinancePeriodID'] = trim($this->input->post('financeyear_period'));
        $data['wareHouseAutoID'] = trim($this->input->post('location'));
        $data['wareHouseCode'] = trim($delivery_location[0]);
        $data['wareHouseLocation'] = trim($delivery_location[1]);
        $data['wareHouseDescription'] = trim($delivery_location[2]);
        $data['contactPersonName'] = trim($this->input->post('contactPersonName'));
        $data['contactPersonNumber'] = trim($this->input->post('contactPersonNumber'));
        /*        $data['supplierID'] = trim($this->input->post('supplierID'));
                $data['supplierSystemCode'] = $supplier_arr['supplierSystemCode'];
                $data['supplierName'] = $supplier_arr['supplierName'];
                $data['supplierAddress'] = $supplier_arr['supplierAddress1'] . ' ' . $supplier_arr['supplierAddress2'];
                $data['supplierTelephone'] = $supplier_arr['supplierTelephone'];
                $data['supplierFax'] = $supplier_arr['supplierFax'];
                $data['supplierEmail'] = $supplier_arr['supplierEmail'];
                $data['supplierliabilityAutoID'] = $supplier_arr['liabilityAutoID'];
                $data['supplierliabilitySystemGLCode'] = $supplier_arr['liabilitySystemGLCode'];
                $data['supplierliabilityGLAccount'] = $supplier_arr['liabilityGLAccount'];
                $data['supplierliabilityDescription'] = $supplier_arr['liabilityDescription'];
                $data['supplierliabilityType'] = $supplier_arr['liabilityType'];*/

        $data['transactionCurrencyID'] = trim($this->input->post('transactionCurrencyID'));
        $data['transactionCurrency'] = trim($currency_code[0]);
        $data['transactionExchangeRate'] = 1;
        $data['transactionCurrencyDecimalPlaces'] = fetch_currency_desimal_by_id($data['transactionCurrencyID']);
        $data['companyLocalCurrencyID'] = $this->common_data['company_data']['company_default_currencyID'];
        $data['companyLocalCurrency'] = $this->common_data['company_data']['company_default_currency'];
        $default_currency = currency_conversionID($data['transactionCurrencyID'], $data['companyLocalCurrencyID']);
        $data['companyLocalExchangeRate'] = $default_currency['conversion'];
        $data['companyLocalCurrencyDecimalPlaces'] = $default_currency['DecimalPlaces'];
        $data['companyReportingCurrency'] = $this->common_data['company_data']['company_reporting_currency'];
        $data['companyReportingCurrencyID'] = $this->common_data['company_data']['company_reporting_currencyID'];
        $reporting_currency = currency_conversionID($data['transactionCurrencyID'], $data['companyReportingCurrencyID']);
        $data['companyReportingExchangeRate'] = $reporting_currency['conversion'];
        $data['companyReportingCurrencyDecimalPlaces'] = $reporting_currency['DecimalPlaces'];
        /*        $data['supplierCurrencyID'] = $supplier_arr['supplierCurrencyID'];
                $data['supplierCurrency'] = $supplier_arr['supplierCurrency'];
                $supplierCurrency = currency_conversionID($data['transactionCurrencyID'], $data['supplierCurrencyID']);
                $data['supplierCurrencyExchangeRate'] = $supplierCurrency['conversion'];
                $data['supplierCurrencyDecimalPlaces'] = $supplierCurrency['DecimalPlaces'];*/

        $data['segmentID'] = trim($segment[0]);
        $data['segmentCode'] = trim($segment[1]);

        if ($dispatchAutoID) {
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];

            $this->db->where('dispatchAutoID', $dispatchAutoID);
            $this->db->update('srp_erp_buyback_dispatchnote', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Dispatch Note Update ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Dispatch Note Updated Successfully.', $dispatchAutoID);
            }
        } else {
            $this->load->library('sequence');
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $data['documentSystemCode'] = $this->sequence->sequence_generator($data['documentID']);
            $this->db->insert('srp_erp_buyback_dispatchnote', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Dispatch Note Creating' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Dispatch Note is created successfully.', $last_id);

            }
        }
    }

    function load_dispatchNote_header()
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('*,DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate,DATE_FORMAT(dispatchedDate,\'' . $convertFormat . '\') AS dispatchedDate');
        $this->db->from('srp_erp_buyback_dispatchnote');
        $this->db->where('dispatchAutoID', trim($this->input->post('dispatchAutoID')));
        return $this->db->get()->row_array();
    }

    function delete_dispatchNote_master()
    {
        $this->db->delete('srp_erp_buyback_dispatchnote', array('dispatchAutoID' => trim($this->input->post('dispatchAutoID'))));
        $this->db->delete('srp_erp_buyback_dispatchnotedetails', array('dispatchAutoID' => trim($this->input->post('dispatchAutoID'))));
        $this->db->delete('srp_erp_buyback_dispatchnote_addon', array('dispatchAutoID' => trim($this->input->post('dispatchAutoID'))));
        return true;
    }

    function fetch_dispatchNote_data($dispatchAutoID)
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('dispatchAutoID,dispatchType,srp_erp_buyback_batch.batchCode as batchCode,DATE_FORMAT(dispatchedDate,\'' . $convertFormat . '\') AS dispatchedDate,documentSystemCode,transactionCurrencyDecimalPlaces,DATE_FORMAT(srp_erp_buyback_dispatchnote.createdDateTime,"%Y-%d-%m") AS createdDateTime,referenceNo,transactionCurrency,DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate ,wareHouseLocation,srp_erp_buyback_dispatchnote.Narration,srp_erp_buyback_dispatchnote.confirmedYN,srp_erp_buyback_dispatchnote.confirmedByName, DATE_FORMAT(srp_erp_buyback_dispatchnote.confirmedDate,\'' . $convertFormat . ' %h:%i:%s\') as confirmedDate,srp_erp_buyback_dispatchnote.approvedbyEmpID,srp_erp_buyback_dispatchnote.approvedbyEmpName,srp_erp_buyback_dispatchnote.approvedYN,DATE_FORMAT(srp_erp_buyback_dispatchnote.approvedDate,\'' . $convertFormat . ' %h:%i:%s\') AS approvedDate,srp_erp_buyback_farmmaster.description as farmName,srp_erp_buyback_farmmaster.address as farmAddress,srp_erp_buyback_farmmaster.phoneMobile as farmTelephone');
        $this->db->from('srp_erp_buyback_dispatchnote');
        $this->db->join('srp_erp_buyback_farmmaster', 'srp_erp_buyback_farmmaster.farmID = srp_erp_buyback_dispatchnote.farmID', 'left');
        $this->db->join('srp_erp_buyback_batch ', 'srp_erp_buyback_batch.batchMasterID = srp_erp_buyback_dispatchnote.batchMasterID', 'left');
        $this->db->where('dispatchAutoID', $dispatchAutoID);
        $data['master'] = $this->db->get()->row_array();

        $data['master']['CurrencyDes'] = fetch_currency_dec($data['master']['transactionCurrency']);
        $this->db->select('srp_erp_buyback_dispatchnotedetails.*');
        $this->db->from('srp_erp_buyback_dispatchnotedetails');
        $this->db->join('srp_erp_buyback_dispatchnote', 'srp_erp_buyback_dispatchnote.dispatchAutoID = srp_erp_buyback_dispatchnotedetails.dispatchAutoID', 'left');
        $this->db->where('srp_erp_buyback_dispatchnotedetails.dispatchAutoID', $dispatchAutoID);
        $this->db->order_by('dispatchDetailsID', 'ASC');
        $data['detail'] = $this->db->get()->result_array();

        $this->db->select('addonCatagory,referenceNo, total_amount,srp_erp_buyback_addon_category.description as addonCategoryName,transactionCurrency,transactionCurrencyDecimalPlaces');
        $this->db->from('srp_erp_buyback_dispatchnote_addon');
        $this->db->join('srp_erp_buyback_addon_category', 'srp_erp_buyback_addon_category.category_id = srp_erp_buyback_dispatchnote_addon.addonCatagory', 'left');
        $this->db->where('dispatchAutoID', $dispatchAutoID);
        $data['addon'] = $this->db->get()->result_array();
        return $data;
    }


    function edit_sub_itemMaster_tmpTbl($qty = 0, $itemAutoID, $masterID, $detailID, $code = 'GRV', $itemCode = null, $data = array())
    {

        $uom = isset($data['uom']) && !empty($data['uom']) ? $data['uom'] : null;
        $uomID = isset($data['uomID']) && !empty($data['uomID']) ? $data['uomID'] : null;
        $grvDetailsID = isset($data['grvDetailsID']) && !empty($data['grvDetailsID']) ? $data['grvDetailsID'] : null;
        $wareHouseAutoID = isset($data['wareHouseAutoID']) && !empty($data['wareHouseAutoID']) ? $data['wareHouseAutoID'] : null;

        $result = $this->getQty_subItemMaster_tmpTbl($itemAutoID, $masterID, $detailID);
        /*echo $this->db->last_query();
        print_r($result);*/
        $count_subItemMaster = 0;
        if (!empty($result)) {
            $count_subItemMaster = count($result);
        }
        if ($count_subItemMaster != $qty) {

            /** delete existing set */
            $this->delete_sub_itemMaster_existing($itemAutoID, $masterID, $detailID);

            /** Add new set */

            $data_subItemMaster = array();
            if ($qty > 0) {
                $x = 0;
                for ($i = 1; $i <= $qty; $i++) {
                    $data_subItemMaster[$x]['itemAutoID'] = $itemAutoID;
                    $data_subItemMaster[$x]['subItemSerialNo'] = $i;
                    $data_subItemMaster[$x]['subItemCode'] = $itemCode . '/GRV/' . $grvDetailsID . '/' . $i;
                    $data_subItemMaster[$x]['uom'] = $uom;
                    $data_subItemMaster[$x]['uomID'] = $uomID;
                    $data_subItemMaster[$x]['wareHouseAutoID'] = $wareHouseAutoID;
                    $data_subItemMaster[$x]['receivedDocumentID'] = $code;
                    $data_subItemMaster[$x]['receivedDocumentAutoID'] = $masterID;
                    $data_subItemMaster[$x]['receivedDocumentDetailID'] = $detailID;
                    $data_subItemMaster[$x]['companyID'] = $this->common_data['company_data']['company_id'];
                    $data_subItemMaster[$x]['createdUserGroup'] = $this->common_data['user_group'];
                    $data_subItemMaster[$x]['createdPCID'] = $this->common_data['current_pc'];
                    $data_subItemMaster[$x]['createdUserID'] = $this->common_data['current_userID'];
                    $data_subItemMaster[$x]['createdDateTime'] = $this->common_data['current_date'];
                    $x++;
                }
            }


            if (!empty($data_subItemMaster)) {
                /** bulk insert to item master sub */
                $this->batch_insert_srp_erp_itemmaster_subtemp($data_subItemMaster);
            }
        } else if ($count_subItemMaster == 0) {
            $data_subItemMaster = array();
            if ($qty > 0) {
                $x = 0;
                for ($i = 1; $i <= $qty; $i++) {
                    $data_subItemMaster[$x]['itemAutoID'] = $itemAutoID;
                    $data_subItemMaster[$x]['subItemSerialNo'] = $i;
                    $data_subItemMaster[$x]['subItemCode'] = $itemCode . '/' . $i;
                    $data_subItemMaster[$x]['uom'] = $uom;
                    $data_subItemMaster[$x]['receivedDocumentID'] = $code;
                    $data_subItemMaster[$x]['receivedDocumentAutoID'] = $masterID;
                    $data_subItemMaster[$x]['receivedDocumentDetailID'] = $detailID;
                    $data_subItemMaster[$x]['companyID'] = $this->common_data['company_data']['company_id'];
                    $data_subItemMaster[$x]['createdUserGroup'] = $this->common_data['user_group'];
                    $data_subItemMaster[$x]['createdPCID'] = $this->common_data['current_pc'];
                    $data_subItemMaster[$x]['createdUserID'] = $this->common_data['current_userID'];
                    $data_subItemMaster[$x]['createdDateTime'] = $this->common_data['current_date'];
                    $x++;
                }
            }


            if (!empty($data_subItemMaster)) {
                /** bulk insert to item master sub */
                $this->batch_insert_srp_erp_itemmaster_subtemp($data_subItemMaster);
            }
        }


    }

    function getQty_subItemMaster_tmpTbl($itemAutoID, $masterID, $detailID)
    {
        $this->db->select('*');
        $this->db->where('itemAutoID', $itemAutoID);
        $this->db->where('receivedDocumentAutoID', $masterID);
        $this->db->where('receivedDocumentDetailID', $detailID);
        $this->db->from('srp_erp_itemmaster_subtemp');
        $r = $this->db->get()->result_array();
        return $r;
    }

    function delete_sub_itemMaster_existing($itemAutoID, $masterID, $detailID)
    {
        //$this->db->where('itemAutoID', $itemAutoID);
        $this->db->where('receivedDocumentAutoID', $masterID);
        $this->db->where('receivedDocumentDetailID', $detailID);
        $result = $this->db->delete('srp_erp_itemmaster_subtemp');
        return $result;

    }

    function batch_insert_srp_erp_itemmaster_subtemp($data)
    {
        $this->db->insert_batch('srp_erp_itemmaster_subtemp', $data);
    }

    function save_dispatchNote_item_detail()
    {

        $dispatchAutoID = $this->input->post('dispatchAutoID');
        $itemAutoIDs = $this->input->post('itemAutoID');
        $UnitOfMeasureID = $this->input->post('UnitOfMeasureID');
        $uom = $this->input->post('uom');
        $estimatedAmount = $this->input->post('estimatedAmount');
        $quantityRequested = $this->input->post('quantityRequested');
        $comment = $this->input->post('comment');

        $this->db->trans_start();
        $ACA_ID = $this->common_data['controlaccounts']['ACA'];
        $ACA = fetch_gl_account_desc($ACA_ID);

        $this->db->select('wareHouseAutoID,wareHouseLocation,wareHouseDescription,transactionCurrencyID,companyLocalExchangeRate,companyReportingExchangeRate,companyLocalCurrencyDecimalPlaces,companyReportingCurrencyDecimalPlaces');
        $this->db->from('srp_erp_buyback_dispatchnote');
        $this->db->where('dispatchAutoID', $dispatchAutoID);
        $master = $this->db->get()->row_array();

        foreach ($itemAutoIDs as $key => $itemAutoID) {
            if (!$dispatchAutoID) {
                $this->db->select('dispatchAutoID,itemDescription,itemSystemCode');
                $this->db->from('srp_erp_buyback_dispatchnotedetails');
                $this->db->where('dispatchAutoID', $dispatchAutoID);
                $this->db->where('itemAutoID', $itemAutoID);
                $order_detail = $this->db->get()->row_array();
                if (!empty($order_detail)) {
                    return array('e', 'Dispatch Note Detail : ' . $order_detail['itemSystemCode'] . ' ' . $order_detail['itemDescription'] . '  already exists.');
                }
            }
            $item_data = fetch_item_data($itemAutoID);
            $buyback_item_data = fetch_buyback_item_data($itemAutoID);
            $uomEx = explode('|', $uom[$key]);
            if ($item_data['mainCategory'] == 'Inventory' or $item_data['mainCategory'] == 'Non Inventory') {
                $this->db->select('itemAutoID');
                $this->db->where('itemAutoID', $item_data['itemAutoID']);
                $this->db->where('wareHouseAutoID', $master['wareHouseAutoID']);
                $this->db->where('companyID', $this->common_data['company_data']['company_id']);
                $warehouseitems = $this->db->get('srp_erp_warehouseitems')->row_array();

                if (empty($warehouseitems)) {
                    $data_arr = array(
                        'wareHouseAutoID' => $master['wareHouseAutoID'],
                        'wareHouseLocation' => $master['wareHouseLocation'],
                        'wareHouseDescription' => $master['wareHouseDescription'],
                        'itemAutoID' => $item_data['itemAutoID'],
                        'itemSystemCode' => $item_data['itemSystemCode'],
                        'itemDescription' => $item_data['itemDescription'],
                        'unitOfMeasureID' => $item_data['defaultUnitOfMeasureID'],
                        'unitOfMeasure' => $item_data['defaultUnitOfMeasure'],
                        'currentStock' => 0,
                        'companyID' => $this->common_data['company_data']['company_id'],
                        'companyCode' => $this->common_data['company_data']['company_code'],
                    );

                    $this->db->insert('srp_erp_warehouseitems', $data_arr);
                }
            }
            $data['dispatchAutoID'] = $dispatchAutoID;
            $data['itemAutoID'] = $item_data['itemAutoID'];
            $data['itemSystemCode'] = $item_data['itemSystemCode'];
            $data['itemDescription'] = $item_data['itemDescription'];
            $data['itemFinanceCategory'] = $item_data['subcategoryID'];
            $data['itemFinanceCategorySub'] = $item_data['subSubCategoryID'];
            $data['financeCategory'] = $item_data['financeCategory'];
            $data['itemCategory'] = $item_data['mainCategory'];
            $data['buybackItemType'] = $buyback_item_data['buybackItemType'];
            $data['feedType'] = $buyback_item_data['feedType'];
            $totalactualcost = $item_data['companyLocalWacAmount'] / $master['companyLocalExchangeRate'];
            $data['totalActualCostLocal'] = $totalactualcost;

            $data['costGLAutoID'] = $item_data['costGLAutoID'];
            $data['costSystemGLCode'] = $item_data['costSystemGLCode'];
            $data['costGLCode'] = $item_data['costGLCode'];
            $data['costGLDescription'] = $item_data['costDescription'];
            $data['costGLType'] = $item_data['costType'];

            $data['assetGLAutoID'] = $item_data['assteGLAutoID'];
            $data['assetSystemGLCode'] = $item_data['assteSystemGLCode'];
            $data['assetGLCode'] = $item_data['assteGLCode'];
            $data['assetGLDescription'] = $item_data['assteDescription'];
            $data['assetGLType'] = $item_data['assteType'];

            $data['revenueGLAutoID'] = $item_data['revanueGLAutoID'];
            $data['revenueSystemGLCode'] = $item_data['revanueSystemGLCode'];
            $data['revenueGLCode'] = $item_data['revanueGLCode'];
            $data['revenueGLDescription'] = $item_data['revanueDescription'];
            $data['revenueGLType'] = $item_data['revanueType'];

            $data['unitOfMeasure'] = trim($uomEx[0]);
            $data['unitOfMeasureID'] = $UnitOfMeasureID[$key];
            $data['defaultUOM'] = $item_data['defaultUnitOfMeasure'];
            $data['defaultUOMID'] = $item_data['defaultUnitOfMeasureID'];
            $data['conversionRateUOM'] = conversionRateUOM_id($data['unitOfMeasureID'], $data['defaultUOMID']);
            $data['qty'] = trim($quantityRequested[$key]);
            $data['unitTransferCost'] = trim($estimatedAmount[$key]);
            $data['unitActualCost'] = $item_data['companyLocalWacAmount'];

            $unitTransferCostLocal = $data['unitTransferCost'] / $master['companyLocalExchangeRate'];
            $data['unitTransferCostLocal'] = round($unitTransferCostLocal, $master['companyLocalCurrencyDecimalPlaces']);
            $unitTransferCostReporting = $data['unitTransferCost'] / $master['companyReportingExchangeRate'];
            $data['unitTransferCostReporting'] = round($unitTransferCostReporting, $master['companyReportingCurrencyDecimalPlaces']);
            $data['totalTransferCost'] = ($data['qty'] * $data['unitTransferCost']);
            $data['totalTransferCostLocal'] = ($data['qty'] * $data['unitTransferCostLocal']);
            $data['totalTransferCostReporting'] = ($data['qty'] * $data['unitTransferCostReporting']);
            $data['totalActualCost'] = ($data['qty'] * $item_data['companyLocalWacAmount']);
            //$data['totalActualCostLocal'] = ($data['qty'] * $item_data['companyLocalWacAmount']);
            $totalActualCostReporting = $data['unitActualCost'] / $master['companyReportingExchangeRate'];
            $totalActualCostReportingRound = round($totalActualCostReporting, $master['companyReportingCurrencyDecimalPlaces']);
            $data['totalActualCostReporting'] = ($data['qty'] * $totalActualCostReportingRound);

            $data['comment'] = $comment[$key];
            $data['remarks'] = null;
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_dispatchnotedetails', $data);
            $last_id = $this->db->insert_id();
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Dispatch Note Details :  Save Failed ' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Dispatch Note Details :  Saved Successfully.');
        }

    }

    function fetch_dispatchNote_item_detail()
    {
        $this->db->select('srp_erp_buyback_dispatchnotedetails.*,srp_erp_buyback_itemmaster.currentStock as currentStock,itemmaster.currentStock as itemstock');
        $this->db->from('srp_erp_buyback_dispatchnotedetails');
        $this->db->join('srp_erp_buyback_itemmaster', 'srp_erp_buyback_itemmaster.itemAutoID = srp_erp_buyback_dispatchnotedetails.itemAutoID');
        $this->db->join('srp_erp_itemmaster itemmaster', 'itemmaster.itemAutoID = srp_erp_buyback_itemmaster.itemAutoID', 'left');
        $this->db->where('dispatchDetailsID', trim($this->input->post('dispatchDetailsID')));
        return $this->db->get()->row_array();
    }

    function update_dispatchNote_item_detail()
    {

        $dispatchDetailsID = $this->input->post('dispatchDetailsID');
        $companyLocalCurrency = $this->common_data['company_data']['company_default_currencyID'];
        $companyReportingCurrency = $this->common_data['company_data']['company_reporting_currencyID'];

        if (!trim($dispatchDetailsID)) {
            $this->db->select('srp_erp_buyback_dispatchnotedetails.*');
            $this->db->from('srp_erp_buyback_dispatchnotedetails');
            //$this->db->join('srp_erp_grvmaster', 'srp_erp_grvmaster.grvAutoID = srp_erp_buyback_dispatchnotedetails.grvAutoID', 'left');
            $this->db->where('srp_erp_buyback_dispatchnotedetails.dispatchAutoID', trim($this->input->post('dispatchAutoID')));
            $this->db->where('srp_erp_buyback_dispatchnotedetails.itemAutoID', trim($this->input->post('itemAutoID')));
            $order_detail = $this->db->get()->row_array();
            echo 'xxx' . $this->db->last_query();
            if (!empty($order_detail)) {
                return array('W', 'Dispatch Note Detail : ' . $order_detail['itemSystemCode'] . ' ' . $order_detail['itemDescription'] . '  already exists.');
            }
        }

        $this->db->trans_start();
        $ACA_ID = $this->common_data['controlaccounts']['ACA'];
        $ACA = fetch_gl_account_desc($ACA_ID);
        $uom = explode('|', $this->input->post('uom'));
        $item_data = fetch_item_data(trim($this->input->post('itemAutoID')));
        $buyback_item_data = fetch_buyback_item_data(trim($this->input->post('itemAutoID')));

        $this->db->select('wareHouseAutoID,wareHouseLocation,wareHouseDescription,transactionCurrencyID,companyLocalExchangeRate,companyReportingExchangeRate,companyLocalCurrencyDecimalPlaces,companyReportingCurrencyDecimalPlaces');
        $this->db->from('srp_erp_buyback_dispatchnote');
        $this->db->where('dispatchAutoID', trim($this->input->post('dispatchAutoID')));
        $master = $this->db->get()->row_array();

        $data['dispatchAutoID'] = trim($this->input->post('dispatchAutoID'));
        $data['itemAutoID'] = trim($this->input->post('itemAutoID'));
        $data['itemSystemCode'] = $item_data['itemSystemCode'];
        $data['itemDescription'] = $item_data['itemDescription'];
        $data['itemFinanceCategory'] = $item_data['subcategoryID'];
        $data['itemFinanceCategorySub'] = $item_data['subSubCategoryID'];
        $data['financeCategory'] = $item_data['financeCategory'];
        $data['itemCategory'] = $item_data['mainCategory'];
        $data['buybackItemType'] = $buyback_item_data['buybackItemType'];
        $data['feedType'] = $buyback_item_data['feedType'];

        $data['costGLAutoID'] = $item_data['costGLAutoID'];
        $data['costSystemGLCode'] = $item_data['costSystemGLCode'];
        $data['costGLCode'] = $item_data['costGLCode'];
        $data['costGLDescription'] = $item_data['costDescription'];
        $data['costGLType'] = $item_data['costType'];

        $data['assetGLAutoID'] = $item_data['assteGLAutoID'];
        $data['assetSystemGLCode'] = $item_data['assteSystemGLCode'];
        $data['assetGLCode'] = $item_data['assteGLCode'];
        $data['assetGLDescription'] = $item_data['assteDescription'];
        $data['assetGLType'] = $item_data['assteType'];

        $data['revenueGLAutoID'] = $item_data['revanueGLAutoID'];
        $data['revenueSystemGLCode'] = $item_data['revanueSystemGLCode'];
        $data['revenueGLCode'] = $item_data['revanueGLCode'];
        $data['revenueGLDescription'] = $item_data['revanueDescription'];
        $data['revenueGLType'] = $item_data['revanueType'];

        $data['unitOfMeasure'] = trim($uom[0]);
        $data['unitOfMeasureID'] = trim($this->input->post('UnitOfMeasureID'));
        $data['defaultUOM'] = $item_data['defaultUnitOfMeasure'];
        $data['defaultUOMID'] = $item_data['defaultUnitOfMeasureID'];
        $data['conversionRateUOM'] = conversionRateUOM_id($data['unitOfMeasureID'], $data['defaultUOMID']);
        $data['qty'] = trim($this->input->post('quantityRequested'));
        $data['unitTransferCost'] = trim($this->input->post('estimatedAmount'));

        $unitTransferCostLocal = $data['unitTransferCost'] / $master['companyLocalExchangeRate'];
        $data['unitTransferCostLocal'] = round($unitTransferCostLocal, $master['companyLocalCurrencyDecimalPlaces']);
        $unitTransferCostReporting = $data['unitTransferCost'] / $master['companyReportingExchangeRate'];
        $data['unitTransferCostReporting'] = round($unitTransferCostReporting, $master['companyReportingCurrencyDecimalPlaces']);
        $data['totalTransferCost'] = ($data['qty'] * $data['unitTransferCost']);
        $data['totalTransferCostLocal'] = ($data['qty'] * $data['unitTransferCostLocal']);
        $data['totalTransferCostReporting'] = ($data['qty'] * $data['unitTransferCostReporting']);

        $data['unitActualCost'] = $item_data['companyLocalWacAmount'];
        $data['totalActualCost'] = ($data['qty'] * $data['unitActualCost']);
        $data['comment'] = trim($this->input->post('comment'));
        $data['remarks'] = trim($this->input->post('remarks'));
        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedUserName'] = $this->common_data['current_user'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];

        if ($data['itemCategory'] == 'Inventory' or $data['itemCategory'] == 'Non Inventory') {
            $this->db->select('itemAutoID');
            $this->db->where('itemAutoID', trim($this->input->post('itemAutoID')));
            $this->db->where('wareHouseAutoID', $master['wareHouseAutoID']);
            $this->db->where('companyID', $this->common_data['company_data']['company_id']);
            $warehouseitems = $this->db->get('srp_erp_warehouseitems')->row_array();

            if (empty($warehouseitems)) {
                $data_arr = array(
                    'wareHouseAutoID' => $master['wareHouseAutoID'],
                    'wareHouseLocation' => $master['wareHouseLocation'],
                    'wareHouseDescription' => $master['wareHouseDescription'],
                    'itemAutoID' => $data['itemAutoID'],
                    'itemSystemCode' => $data['itemSystemCode'],
                    'itemDescription' => $data['itemDescription'],
                    'unitOfMeasureID' => $data['defaultUOMID'],
                    'unitOfMeasure' => $data['defaultUOM'],
                    'currentStock' => 0,
                    'companyID' => $this->common_data['company_data']['company_id'],
                    'companyCode' => $this->common_data['company_data']['company_code'],
                );
                $this->db->insert('srp_erp_warehouseitems', $data_arr);
            }
        }

        if (trim($dispatchDetailsID)) {
            $this->db->where('dispatchDetailsID', trim($dispatchDetailsID));
            $this->db->update('srp_erp_buyback_dispatchnotedetails', $data);

            /** update sub item master */

            $this->db->select('srp_erp_buyback_dispatchnotedetails.*,srp_erp_buyback_dispatchnote.wareHouseAutoID');
            $this->db->from('srp_erp_buyback_dispatchnotedetails');
            $this->db->join('srp_erp_buyback_dispatchnote', 'srp_erp_buyback_dispatchnote.dispatchAutoID = srp_erp_buyback_dispatchnotedetails.dispatchAutoID', 'left');
            $this->db->where('srp_erp_buyback_dispatchnotedetails.itemAutoID', trim($this->input->post('itemAutoID')));
            $detail = $this->db->get()->row_array();


            $subData['uom'] = $data['unitOfMeasure'];
            $subData['uomID'] = $data['unitOfMeasureID'];
            $subData['grvDetailsID'] = $dispatchDetailsID;
            $subData['wareHouseAutoID'] = $detail['wareHouseAutoID'];


            $this->edit_sub_itemMaster_tmpTbl($this->input->post('quantityRequested'), $item_data['itemAutoID'], $data['dispatchAutoID'], $dispatchDetailsID, 'BBDPN', $data['itemSystemCode'], $subData);


            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Dispatch Note Detail : ' . $data['itemSystemCode'] . ' Update Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Dispatch Note Detail : ' . $data['itemSystemCode'] . ' Updated Successfully.');

            }
        }
    }

    function delete_dispatchNote_detail_item()
    {
        $dispatchDetailsID = trim($this->input->post('dispatchDetailsID'));
        $this->db->delete('srp_erp_buyback_dispatchnotedetails', array('dispatchDetailsID' => $dispatchDetailsID));
        //$this->db->delete('srp_erp_grv_addon', array('impactFor' => $grvDetailID));
        $this->db->delete('srp_erp_itemmaster_subtemp', array('receivedDocumentDetailID' => $dispatchDetailsID, 'receivedDocumentID' => 'BBDPN'));
        /*        $po_data['GRVSelectedYN'] = 0;
                $po_data['goodsRecievedYN'] = 0;
                $this->db->where('purchaseOrderDetailsID', $Detail['purchaseOrderDetailsID']);
                $this->db->update('srp_erp_purchaseorderdetails', $po_data);*/

        return true;
    }

    function save_dispatchNote_addon()
    {
        $dispatchAddonAutoID = trim($this->input->post('dispatchAddonAutoID'));
        $this->db->trans_start();
        $booking_code = explode('|', trim($this->input->post('booking_code')));
        $this->db->select('transactionCurrencyID,transactionCurrency,transactionExchangeRate, transactionCurrencyDecimalPlaces,farmID');
        $this->db->from('srp_erp_buyback_dispatchnote');
        $this->db->where('dispatchAutoID', trim($this->input->post('dispatchAutoID')));
        $master = $this->db->get()->row_array();
        //$supplier_arr = $this->fetch_supplier_data(trim($this->input->post('supplier')));
        $gl_code = explode('|', $this->input->post('glcode_dec'));
        $data['farmID'] = $master['farmID'];
        $data['dispatchAutoID'] = trim($this->input->post('dispatchAutoID'));
        $data['referenceNo'] = trim($this->input->post('referenceNo'));
        $data['addonCatagory'] = trim($this->input->post('addonCatagory'));
        $data['systemGLCode'] = trim($gl_code[0]);
        $data['GLAutoID'] = trim($this->input->post('GLAutoID'));
        $data['GLCode'] = trim($gl_code[1]);
        $data['GLDescription'] = trim($gl_code[2]);
        $data['GLType'] = trim($gl_code[3]);
        $data['transactionCurrencyID'] = $master['transactionCurrencyID'];
        $data['transactionCurrency'] = $master['transactionCurrency'];
        //$transaction_currency = currency_conversionID($data['bookingCurrencyID'], $data['transactionCurrencyID']);
        //$data['transactionExchangeRate'] = $transaction_currency['conversion'];
        //$data['transactionCurrencyDecimalPlaces'] = $transaction_currency['DecimalPlaces'];
        $data['total_amount'] = trim($this->input->post('total_amount'));
        $data['companyLocalCurrency'] = $this->common_data['company_data']['company_default_currency'];
        $data['companyLocalCurrencyID'] = $this->common_data['company_data']['company_default_currencyID'];
        //$default_currency = currency_conversionID($data['bookingCurrencyID'], $data['companyLocalCurrencyID']);
        //$data['companyLocalExchangeRate'] = $default_currency['conversion'];
        $data['companyReportingCurrency'] = $this->common_data['company_data']['company_reporting_currency'];
        $data['companyReportingCurrencyID'] = $this->common_data['company_data']['company_reporting_currencyID'];
        //$reporting_currency = currency_conversionID($data['bookingCurrencyID'], $data['companyReportingCurrencyID']);
        //$data['companyReportingExchangeRate'] = $reporting_currency['conversion'];

        if ($dispatchAddonAutoID) {
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $this->db->where('dispatchAddonAutoID', $dispatchAddonAutoID);
            $this->db->update('srp_erp_buyback_dispatchnote_addon', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Addon Cost  :  Update Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Addon Cost  : Updated Successfully.', $dispatchAddonAutoID);
            }
        } else {

            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_dispatchnote_addon', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Addon Cost Add Failed ');
            } else {
                $this->db->trans_commit();
                return array('s', 'Addon Cost Added Successfully.', $last_id);
            }
        }
    }

    function delete_dispatchNote_detail_addon()
    {
        $dispatchAddonAutoID = trim($this->input->post('dispatchAddonAutoID'));
        $this->db->delete('srp_erp_buyback_dispatchnote_addon', array('dispatchAddonAutoID' => $dispatchAddonAutoID));
        return true;
    }

    function fetch_dispatchNote_addonCost_detail()
    {
        $this->db->select('*');
        $this->db->from('srp_erp_buyback_dispatchnote_addon');
        $this->db->where('dispatchAddonAutoID', trim($this->input->post('dispatchAddonAutoID')));
        return $this->db->get()->row_array();
    }

    function save_addon_categoryMaster()
    {

        $category_id = trim($this->input->post('category_id'));
        $this->db->trans_start();

        $data['description'] = trim($this->input->post('description'));
        $data['GLAutoID'] = trim($this->input->post('GLAutoID'));

        if (!empty($category_id)) {
            $this->db->where('category_id', $category_id);
            $this->db->update('srp_erp_buyback_addon_category', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Add-on Category Update Failed.');
            } else {
                $this->db->trans_commit();
                return array('s', 'Add-on Category Updated Successfully.');
            }
        } else {
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $this->db->insert('srp_erp_buyback_addon_category', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Add-on Category Add Failed.');
            } else {
                $this->db->trans_commit();
                return array('s', 'Add-on Category Added Successfully.', 'last_id' => $last_id);
            }
        }
    }

    function fetch_addonCategory_detail()
    {
        $this->db->select('*');
        $this->db->from('srp_erp_buyback_addon_category');
        $this->db->where('category_id', trim($this->input->post('category_id')));
        return $this->db->get()->row_array();
    }

    function fetch_farmer_currencyID()
    {
        $this->db->select('farmerCurrencyID');
        $this->db->from('srp_erp_buyback_farmmaster');
        $this->db->where('farmID', trim($this->input->post('farmID')));
        return $this->db->get()->row_array();
    }

    function save_good_receipt_note_header()
    {
        $this->db->trans_start();
        $date_format_policy = date_format_policy();

        $grnAutoID = trim($this->input->post('grnAutoID'));
        $documentDate = $this->input->post('documentDate');
        $deliveredDate = $this->input->post('deliveredDate');
        $format_documentDate = null;
        if (isset($documentDate) && !empty($documentDate)) {
            $format_documentDate = input_format_date($documentDate, $date_format_policy);
        }
        $format_deliveredDate = null;
        if (isset($deliveredDate) && !empty($deliveredDate)) {
            $format_deliveredDate = input_format_date($deliveredDate, $date_format_policy);
        }
        $year = explode(' - ', trim($this->input->post('companyFinanceYear')));
        $FYBegin = input_format_date($year[0], $date_format_policy);
        $FYEnd = input_format_date($year[1], $date_format_policy);
        $currency_code = explode('|', trim($this->input->post('currency_code')));
        $segment = explode('|', trim($this->input->post('segment')));
        $delivery_location = explode('|', trim($this->input->post('delivery_location')));
        $supplier_arr = $this->fetch_supplier_data(trim($this->input->post('supplierID')));

        $data['grnType'] = trim($this->input->post('grnType'));
        $data['batchMasterID'] = trim($this->input->post('batchMasterID'));
        $data['farmID'] = trim($this->input->post('farmID'));
        $data['documentID'] = 'BBGRN';
        $data['documentDate'] = $format_documentDate;
        $data['deliveryDate'] = $format_deliveredDate;
        $data['referenceNo'] = trim($this->input->post('referenceno'));
        $data['narration'] = trim($this->input->post('narration'));
        $data['companyFinanceYearID'] = trim($this->input->post('financeyear'));
        $data['companyFinanceYear'] = trim($this->input->post('companyFinanceYear'));

        $data['FYBegin'] = trim($FYBegin);
        $data['FYEnd'] = trim($FYEnd);
        $data['companyFinancePeriodID'] = trim($this->input->post('financeyear_period'));
        $data['wareHouseAutoID'] = trim($this->input->post('location'));
        $data['wareHouseCode'] = trim($delivery_location[0]);
        $data['wareHouseLocation'] = trim($delivery_location[1]);
        $data['wareHouseDescription'] = trim($delivery_location[2]);
        $data['contactPersonName'] = trim($this->input->post('contactPersonName'));
        $data['contactPersonNumber'] = trim($this->input->post('contactPersonNumber'));
        $data['driverID'] = trim($this->input->post('driverID'));
        $data['helperID'] = trim($this->input->post('helperID'));

        $data['transactionCurrencyID'] = trim($this->input->post('transactionCurrencyID'));
        $data['transactionCurrency'] = trim($currency_code[0]);
        $data['transactionExchangeRate'] = 1;
        $data['transactionCurrencyDecimalPlaces'] = fetch_currency_desimal_by_id($data['transactionCurrencyID']);
        $data['companyLocalCurrencyID'] = $this->common_data['company_data']['company_default_currencyID'];
        $data['companyLocalCurrency'] = $this->common_data['company_data']['company_default_currency'];
        $default_currency = currency_conversionID($data['transactionCurrencyID'], $data['companyLocalCurrencyID']);
        $data['companyLocalExchangeRate'] = $default_currency['conversion'];
        $data['companyLocalCurrencyDecimalPlaces'] = $default_currency['DecimalPlaces'];
        $data['companyReportingCurrency'] = $this->common_data['company_data']['company_reporting_currency'];
        $data['companyReportingCurrencyID'] = $this->common_data['company_data']['company_reporting_currencyID'];
        $reporting_currency = currency_conversionID($data['transactionCurrencyID'], $data['companyReportingCurrencyID']);
        $data['companyReportingExchangeRate'] = $reporting_currency['conversion'];
        $data['companyReportingCurrencyDecimalPlaces'] = $reporting_currency['DecimalPlaces'];
        /*        $data['supplierCurrencyID'] = $supplier_arr['supplierCurrencyID'];
                $data['supplierCurrency'] = $supplier_arr['supplierCurrency'];
                $supplierCurrency = currency_conversionID($data['transactionCurrencyID'], $data['supplierCurrencyID']);
                $data['supplierCurrencyExchangeRate'] = $supplierCurrency['conversion'];
                $data['supplierCurrencyDecimalPlaces'] = $supplierCurrency['DecimalPlaces'];*/

        $data['segmentID'] = trim($segment[0]);
        $data['segmentCode'] = trim($segment[1]);

        if ($grnAutoID) {
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];

            $this->db->where('grnAutoID', $grnAutoID);
            $this->db->update('srp_erp_buyback_grn', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Good Receipt Note Update ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Good Receipt Note Updated Successfully.', $grnAutoID);
            }
        } else {
            $this->load->library('sequence');
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $data['documentSystemCode'] = $this->sequence->sequence_generator($data['documentID']);
            $this->db->insert('srp_erp_buyback_grn', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Good Receipt Note Creating' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Good Receipt Note is created successfully.', $last_id);

            }
        }
    }

    function load_good_receiptNote_header()
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('*,DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate,DATE_FORMAT(deliveryDate,\'' . $convertFormat . '\') AS deliveryDate');
        $this->db->from('srp_erp_buyback_grn');
        $this->db->where('grnAutoID', trim($this->input->post('grnAutoID')));
        return $this->db->get()->row_array();
    }

    function save_goodReceiptNote_item_detail()
    {
        $grnAutoID = $this->input->post('grnAutoID');
        $itemAutoIDs = $this->input->post('itemAutoID');
        $UnitOfMeasureID = $this->input->post('UnitOfMeasureID');
        $uom = $this->input->post('uom');
        $estimatedAmount = $this->input->post('Amount');
        $quantityRequested = $this->input->post('kgweight');
        $noOfBirds = $this->input->post('noofbirds');


        $this->db->trans_start();
        $ACA_ID = $this->common_data['controlaccounts']['ACA'];
        $ACA = fetch_gl_account_desc($ACA_ID);

        $this->db->select('wareHouseAutoID,wareHouseLocation,wareHouseDescription,transactionCurrencyID,companyLocalExchangeRate,companyReportingExchangeRate,companyLocalCurrencyDecimalPlaces,companyReportingCurrencyDecimalPlaces,batchMasterID');
        $this->db->from('srp_erp_buyback_grn');
        $this->db->where('grnAutoID', trim($this->input->post('grnAutoID')));
        $master = $this->db->get()->row_array();
        $batchid = $master['batchMasterID'];
        $TotalDispatchQty = $this->db->query("SELECT
	sum(IFNULL(transactionQTY,0)) as transactionQTY
FROM
	srp_erp_buyback_itemledger
WHERE
	buybackItemType = 1 AND batchID = $batchid AND documentCode= 'BBDPN'")->row_array();

        $TotalGRNQty = $this->db->query("SELECT
	IFNULL(sum(transactionQTY),0) as transactionQTY
FROM
	srp_erp_buyback_itemledger
WHERE
	batchID = $batchid
AND buybackItemType = 1
AND documentCode = 'BBGRN'")->row_array();

        $totalDispatchAmount = $this->db->query("SELECT
	IFNULL(sum(totalTransactionAmount),0) as totalTransactionAmount
FROM
	srp_erp_buyback_itemledger
WHERE
	batchID = $batchid
AND documentCode = 'BBDPN'")->row_array();

        $totalMortality = $this->db->query("SELECT
	IFNULL(sum(noOfBirds),0) as totmortality
FROM
	srp_erp_buyback_mortalitydetails detail
JOIN srp_erp_buyback_mortalitymaster mast ON detail.mortalityAutoID = mast.mortalityAutoID
WHERE
	mast.confirmedYN = 1 AND mast.batchMasterID =$batchid ")->row_array();

        $totalGrnAmount = $this->db->query("SELECT
	IFNULL(sum(totalTransactionAmount),0) as totalTransactionAmount
FROM
	srp_erp_buyback_itemledger
WHERE
	batchID =$batchid
AND documentCode = 'BBGRN'")->row_array();

        $unitcost = ($totalDispatchAmount['totalTransactionAmount'] - $totalGrnAmount['totalTransactionAmount']) / ($TotalDispatchQty['transactionQTY'] - ($totalMortality['totmortality'] + $TotalGRNQty['transactionQTY']));

        foreach ($itemAutoIDs as $key => $itemAutoID) {
            if (!$grnAutoID) {
                $this->db->select('grnAutoID,itemDescription,itemSystemCode');
                $this->db->from('srp_erp_buyback_grndetails');
                $this->db->where('grnAutoID', $grnAutoID);
                $this->db->where('itemAutoID', $itemAutoID);
                $order_detail = $this->db->get()->row_array();
                if (!empty($order_detail)) {
                    return array('e', 'Good Receipt Note Detail : ' . $order_detail['itemSystemCode'] . ' ' . $order_detail['itemDescription'] . '  already exists.');
                }
            }
            $item_data = fetch_item_data($itemAutoID);
            $uomEx = explode('|', $uom[$key]);
            if ($item_data['mainCategory'] == 'Inventory' or $item_data['mainCategory'] == 'Non Inventory') {
                $this->db->select('itemAutoID');
                $this->db->where('itemAutoID', $item_data['itemAutoID']);
                $this->db->where('wareHouseAutoID', $master['wareHouseAutoID']);
                $this->db->where('companyID', $this->common_data['company_data']['company_id']);
                $warehouseitems = $this->db->get('srp_erp_warehouseitems')->row_array();

                if (empty($warehouseitems)) {
                    $data_arr = array(
                        'wareHouseAutoID' => $master['wareHouseAutoID'],
                        'wareHouseLocation' => $master['wareHouseLocation'],
                        'wareHouseDescription' => $master['wareHouseDescription'],
                        'itemAutoID' => $item_data['itemAutoID'],
                        'itemSystemCode' => $item_data['itemSystemCode'],
                        'itemDescription' => $item_data['itemDescription'],
                        'unitOfMeasureID' => $item_data['defaultUnitOfMeasureID'],
                        'unitOfMeasure' => $item_data['defaultUnitOfMeasure'],
                        'currentStock' => 0,
                        'companyID' => $this->common_data['company_data']['company_id'],
                        'companyCode' => $this->common_data['company_data']['company_code'],
                    );

                    $this->db->insert('srp_erp_warehouseitems', $data_arr);
                }
            }
            $data['grnAutoID'] = $grnAutoID;
            $data['itemAutoID'] = $item_data['itemAutoID'];
            $data['itemSystemCode'] = $item_data['itemSystemCode'];
            $data['itemDescription'] = $item_data['itemDescription'];
            $data['itemFinanceCategory'] = $item_data['subcategoryID'];
            $data['itemFinanceCategorySub'] = $item_data['subSubCategoryID'];
            $data['financeCategory'] = $item_data['financeCategory'];
            $data['itemCategory'] = $item_data['mainCategory'];

            $data['costGLAutoID'] = $item_data['costGLAutoID'];
            $data['costSystemGLCode'] = $item_data['costSystemGLCode'];
            $data['costGLCode'] = $item_data['costGLCode'];
            $data['costGLDescription'] = $item_data['costDescription'];
            $data['costGLType'] = $item_data['costType'];

            $data['assetGLAutoID'] = $item_data['assteGLAutoID'];
            $data['assetSystemGLCode'] = $item_data['assteSystemGLCode'];
            $data['assetGLCode'] = $item_data['assteGLCode'];
            $data['assetGLDescription'] = $item_data['assteDescription'];
            $data['assetGLType'] = $item_data['assteType'];

            $data['revenueGLAutoID'] = $item_data['revanueGLAutoID'];
            $data['revenueSystemGLCode'] = $item_data['revanueSystemGLCode'];
            $data['revenueGLCode'] = $item_data['revanueGLCode'];
            $data['revenueGLDescription'] = $item_data['revanueDescription'];
            $data['revenueGLType'] = $item_data['revanueType'];

            $data['unitOfMeasure'] = trim($uomEx[0]);
            $data['unitOfMeasureID'] = $UnitOfMeasureID[$key];
            $data['defaultUOM'] = $item_data['defaultUnitOfMeasure'];
            $data['defaultUOMID'] = $item_data['defaultUnitOfMeasureID'];
            $data['conversionRateUOM'] = conversionRateUOM_id($data['unitOfMeasureID'], $data['defaultUOMID']);
            $data['noOfBirds'] = trim($noOfBirds[$key]);
            $data['qty'] = trim($quantityRequested[$key]);
            $data['unitCost'] = trim($unitcost);
            $data['unitTransferCost'] = trim($estimatedAmount[$key]);

            $unitCostLocal = $data['unitCost'] / $master['companyLocalExchangeRate'];
            $unitTransferCostLocal = $data['unitTransferCost'] / $master['companyLocalExchangeRate'];
            $data['unitCostLocal'] = round($unitCostLocal, $master['companyLocalCurrencyDecimalPlaces']);
            $data['unitTransferCostLocal'] = round($unitTransferCostLocal, $master['companyLocalCurrencyDecimalPlaces']);
            $unitCostReporting = $data['unitCost'] / $master['companyReportingExchangeRate'];
            $unitTransferCostReporting = $data['unitTransferCost'] / $master['companyReportingExchangeRate'];
            $data['unitTransferCostReporting'] = round($unitTransferCostReporting, $master['companyReportingCurrencyDecimalPlaces']);
            $data['unitCostReporting'] = round($unitCostReporting, $master['companyReportingCurrencyDecimalPlaces']);
            $data['totalCost'] = ($data['noOfBirds'] * $data['unitCost']);


            $data['totalCostTransfer'] = ($data['qty'] * $data['unitTransferCost']);
            //$data['comment'] = $comment[$key];
            $data['remarks'] = null;
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_grndetails', $data);
            $last_id = $this->db->insert_id();

            if ($item_data['isSubitemExist'] == 1) {
                $qty = 0;
                if (!empty($itemAutoIDs)) {
                    foreach ($itemAutoIDs as $key => $itemAutoIDTmp) {
                        if ($itemAutoIDTmp == $itemAutoID) {
                            $qty = $quantityRequested[$key];
                        }
                    }
                }
                $subData['uom'] = $data['unitOfMeasure'];
                $subData['uomID'] = $data['unitOfMeasureID'];
                $subData['grv_detailID'] = $last_id;
                $subData['warehouseAutoID'] = $master['wareHouseAutoID'];
                $this->add_sub_itemMaster_tmpTbl($qty, $itemAutoID, $grnAutoID, $last_id, 'BBGRN', $item_data['itemSystemCode'], $subData);
            }
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Good Receipt Note Details :  Save Failed ' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Good Receipt Note Details :  Saved Successfully.');
        }

    }

    function fetch_goodReceiptNote_item_detail()
    {
        $this->db->select('*');
        $this->db->from('srp_erp_buyback_grndetails');
        $this->db->where('grnDetailsID', trim($this->input->post('grnDetailsID')));
        return $this->db->get()->row_array();
    }

    function update_goodReceiptNote_item_detail()
    {

        $grnDetailsID = $this->input->post('grnDetailsID');

        if (!trim($grnDetailsID)) {
            $this->db->select('srp_erp_buyback_grndetails.*');
            $this->db->from('srp_erp_buyback_grndetails');
            //$this->db->join('srp_erp_grvmaster', 'srp_erp_grvmaster.grvAutoID = srp_erp_buyback_grndetails.grvAutoID', 'left');
            $this->db->where('srp_erp_buyback_grndetails.grnAutoID', trim($this->input->post('grnAutoID')));
            $this->db->where('srp_erp_buyback_grndetails.itemAutoID', trim($this->input->post('itemAutoID')));
            $order_detail = $this->db->get()->row_array();
            echo 'xxx' . $this->db->last_query();
            if (!empty($order_detail)) {
                return array('W', 'goodReceiptNote Detail : ' . $order_detail['itemSystemCode'] . ' ' . $order_detail['itemDescription'] . '  already exists.');
            }
        }

        $this->db->trans_start();
        $ACA_ID = $this->common_data['controlaccounts']['ACA'];
        $ACA = fetch_gl_account_desc($ACA_ID);
        $uom = explode('|', $this->input->post('uom'));
        $item_data = fetch_item_data(trim($this->input->post('itemAutoID')));


        $this->db->select('wareHouseAutoID,wareHouseLocation,wareHouseDescription,transactionCurrencyID,companyLocalExchangeRate,companyReportingExchangeRate,companyLocalCurrencyDecimalPlaces,companyReportingCurrencyDecimalPlaces,batchMasterID');
        $this->db->from('srp_erp_buyback_grn');
        $this->db->where('grnAutoID', trim($this->input->post('grnAutoID')));
        $master = $this->db->get()->row_array();
        $batchid = $master['batchMasterID'];

        $TotalDispatchQty = $this->db->query("SELECT
	sum(IFNULL(transactionQTY,0)) as transactionQTY
FROM
	srp_erp_buyback_itemledger
WHERE
	buybackItemType = 1 AND batchID = $batchid AND documentCode= 'BBDPN'")->row_array();

        $TotalGRNQty = $this->db->query("SELECT
	IFNULL(sum(transactionQTY),0) as transactionQTY
FROM
	srp_erp_buyback_itemledger
WHERE
	batchID = $batchid
AND buybackItemType = 1
AND documentCode = 'BBGRN'")->row_array();

        $totalDispatchAmount = $this->db->query("SELECT
	IFNULL(sum(totalTransactionAmount),0) as totalTransactionAmount
FROM
	srp_erp_buyback_itemledger
WHERE
	batchID = $batchid
AND documentCode = 'BBDPN'")->row_array();

        $totalMortality = $this->db->query("SELECT
	IFNULL(sum(noOfBirds),0) as totmortality
FROM
	srp_erp_buyback_mortalitydetails detail
JOIN srp_erp_buyback_mortalitymaster mast ON detail.mortalityAutoID = mast.mortalityAutoID
WHERE
	mast.confirmedYN = 1 AND mast.batchMasterID =$batchid ")->row_array();

        $totalGrnAmount = $this->db->query("SELECT
	IFNULL(sum(totalTransactionAmount),0) as totalTransactionAmount
FROM
	srp_erp_buyback_itemledger
WHERE
	batchID =$batchid
AND documentCode = 'BBGRN'")->row_array();

        $unitcost = ($totalDispatchAmount['totalTransactionAmount'] - $totalGrnAmount['totalTransactionAmount']) / ($TotalDispatchQty['transactionQTY'] - ($totalMortality['totmortality'] + $TotalGRNQty['transactionQTY']));

        $data['grnAutoID'] = trim($this->input->post('grnAutoID'));
        $data['itemAutoID'] = trim($this->input->post('itemAutoID'));
        $data['itemSystemCode'] = $item_data['itemSystemCode'];
        $data['itemDescription'] = $item_data['itemDescription'];
        $data['itemFinanceCategory'] = $item_data['subcategoryID'];
        $data['itemFinanceCategorySub'] = $item_data['subSubCategoryID'];
        $data['financeCategory'] = $item_data['financeCategory'];
        $data['itemCategory'] = $item_data['mainCategory'];

        $data['costGLAutoID'] = $item_data['costGLAutoID'];
        $data['costSystemGLCode'] = $item_data['costSystemGLCode'];
        $data['costGLCode'] = $item_data['costGLCode'];
        $data['costGLDescription'] = $item_data['costDescription'];
        $data['costGLType'] = $item_data['costType'];

        $data['assetGLAutoID'] = $item_data['assteGLAutoID'];
        $data['assetSystemGLCode'] = $item_data['assteSystemGLCode'];
        $data['assetGLCode'] = $item_data['assteGLCode'];
        $data['assetGLDescription'] = $item_data['assteDescription'];
        $data['assetGLType'] = $item_data['assteType'];

        $data['revenueGLAutoID'] = $item_data['revanueGLAutoID'];
        $data['revenueSystemGLCode'] = $item_data['revanueSystemGLCode'];
        $data['revenueGLCode'] = $item_data['revanueGLCode'];
        $data['revenueGLDescription'] = $item_data['revanueDescription'];
        $data['revenueGLType'] = $item_data['revanueType'];

        $data['unitOfMeasure'] = trim($uom[0]);
        $data['unitOfMeasureID'] = trim($this->input->post('UnitOfMeasureID'));
        $data['defaultUOM'] = $item_data['defaultUnitOfMeasure'];
        $data['defaultUOMID'] = $item_data['defaultUnitOfMeasureID'];
        $data['conversionRateUOM'] = conversionRateUOM_id($data['unitOfMeasureID'], $data['defaultUOMID']);
        $data['noOfBirds'] = trim($this->input->post('noofbirds'));
        $data['qty'] = trim($this->input->post('kgweight'));
        $data['unitCost'] = trim($unitcost);
        $data['unitTransferCost'] = trim($this->input->post('Amount'));
        $unitCostLocal = $data['unitCost'] / $master['companyLocalExchangeRate'];
        $data['unitCostLocal'] = round($unitCostLocal, $master['companyLocalCurrencyDecimalPlaces']);
        $unitTransferCostLocal = $data['unitTransferCost'] / $master['companyLocalExchangeRate'];
        $unitCostReporting = $data['unitCost'] / $master['companyReportingExchangeRate'];
        $data['unitCostReporting'] = round($unitCostReporting, $master['companyReportingCurrencyDecimalPlaces']);
        $data['totalCost'] = ($data['noOfBirds'] * $data['unitCost']);
        $data['unitTransferCostLocal'] = round($unitTransferCostLocal, $master['companyLocalCurrencyDecimalPlaces']);
        $unitTransferCostReporting = $data['unitTransferCost'] / $master['companyReportingExchangeRate'];
        $data['unitTransferCostReporting'] = round($unitTransferCostReporting, $master['companyReportingCurrencyDecimalPlaces']);
        $data['totalCostTransfer'] = ($data['qty'] * $data['unitTransferCost']);
        $data['comment'] = trim($this->input->post('comment'));
        $data['remarks'] = trim($this->input->post('remarks'));
        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedUserName'] = $this->common_data['current_user'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];

        if ($data['itemCategory'] == 'Inventory' or $data['itemCategory'] == 'Non Inventory') {
            $this->db->select('itemAutoID');
            $this->db->where('itemAutoID', trim($this->input->post('itemAutoID')));
            $this->db->where('wareHouseAutoID', $master['wareHouseAutoID']);
            $this->db->where('companyID', $this->common_data['company_data']['company_id']);
            $warehouseitems = $this->db->get('srp_erp_warehouseitems')->row_array();

            if (empty($warehouseitems)) {
                $data_arr = array(
                    'wareHouseAutoID' => $master['wareHouseAutoID'],
                    'wareHouseLocation' => $master['wareHouseLocation'],
                    'wareHouseDescription' => $master['wareHouseDescription'],
                    'itemAutoID' => $data['itemAutoID'],
                    'itemSystemCode' => $data['itemSystemCode'],
                    'itemDescription' => $data['itemDescription'],
                    'unitOfMeasureID' => $data['defaultUOMID'],
                    'unitOfMeasure' => $data['defaultUOM'],
                    'currentStock' => 0,
                    'companyID' => $this->common_data['company_data']['company_id'],
                    'companyCode' => $this->common_data['company_data']['company_code'],
                );
                $this->db->insert('srp_erp_warehouseitems', $data_arr);
            }
        }

        if (trim($grnDetailsID)) {
            $this->db->where('grnDetailsID', trim($grnDetailsID));
            $this->db->update('srp_erp_buyback_grndetails', $data);

            /** update sub item master */

            /*            $this->db->select('srp_erp_buyback_grndetails.*,srp_erp_buyback_dispatchnote.wareHouseAutoID');
                        $this->db->from('srp_erp_buyback_grndetails');
                        $this->db->join('srp_erp_buyback_dispatchnote', 'srp_erp_buyback_dispatchnote.dispatchAutoID = srp_erp_buyback_grndetails.dispatchAutoID', 'left');
                        $this->db->where('srp_erp_buyback_grndetails.itemAutoID', trim($this->input->post('itemAutoID')));
                        $detail = $this->db->get()->row_array();


                        $subData['uom'] = $data['unitOfMeasure'];
                        $subData['uomID'] = $data['unitOfMeasureID'];
                        $subData['grvDetailsID'] = $grnDetailsID;
                        $subData['wareHouseAutoID'] = $detail['wareHouseAutoID'];


                        $this->edit_sub_itemMaster_tmpTbl($this->input->post('quantityRequested'), $item_data['itemAutoID'], $data['dispatchAutoID'], $grnDetailsID, 'DPN', $data['itemSystemCode'], $subData);*/


            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Good Receipt Note Detail : ' . $data['itemSystemCode'] . ' Update Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Good Receipt Note Detail : ' . $data['itemSystemCode'] . ' Updated Successfully.');

            }
        }
    }

    function delete_goodReceiptNote_detail_item()
    {
        $grnDetailsID = trim($this->input->post('grnDetailsID'));
        $this->db->delete('srp_erp_buyback_grndetails', array('grnDetailsID' => $grnDetailsID));
        //$this->db->delete('srp_erp_grv_addon', array('impactFor' => $grvDetailID));
        //$this->db->delete('srp_erp_itemmaster_subtemp', array('receivedDocumentDetailID' => $dispatchDetailsID, 'receivedDocumentID' => 'DPN'));
        return true;
    }

    function fetch_goodReceiptNote_data($grnAutoID)
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('grnAutoID,srp_erp_buyback_batch.batchCode as batchCode,DriverName,HelperName,vehicleNo,JourneyStartTime,JourneyEndTime,srp_erp_segment.description as segmentdescription,driver.Ename2 as drivername,helper.Ename2 as helpername,grnType,DATE_FORMAT(deliveryDate,\'' . $convertFormat . '\') AS deliveryDate,documentSystemCode,transactionCurrencyDecimalPlaces,DATE_FORMAT(srp_erp_buyback_grn.createdDateTime,"%Y-%d-%m") AS createdDateTime,referenceNo,transactionCurrency,DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate ,wareHouseLocation,srp_erp_buyback_grn.Narration,srp_erp_buyback_grn.confirmedYN,srp_erp_buyback_grn.confirmedByName,srp_erp_buyback_grn.confirmedDate,,srp_erp_buyback_grn.approvedbyEmpID,srp_erp_buyback_grn.approvedbyEmpName,srp_erp_buyback_grn.approvedYN,DATE_FORMAT(srp_erp_buyback_grn.approvedDate,\'' . $convertFormat . ' %h:%i:%s\') AS approvedDate,srp_erp_buyback_farmmaster.description as farmName,srp_erp_buyback_farmmaster.address as farmAddress,srp_erp_buyback_farmmaster.phoneMobile as farmTelephone,case grnType when 1 then \'Buyback\' when 2 then \'others\' end as grnType');
        $this->db->from('srp_erp_buyback_grn');
        $this->db->join('srp_erp_buyback_farmmaster', 'srp_erp_buyback_farmmaster.farmID = srp_erp_buyback_grn.farmID', 'left');
        $this->db->join('srp_employeesdetails driver', 'driver.EIdNo = srp_erp_buyback_grn.driverID', 'left');
        $this->db->join('srp_employeesdetails helper', 'helper.EIdNo = srp_erp_buyback_grn.helperID', 'left');
        $this->db->join('srp_erp_segment', 'srp_erp_segment.segmentID = srp_erp_buyback_grn.segmentID', 'left');
        $this->db->join('srp_erp_buyback_batch', 'srp_erp_buyback_batch.batchMasterID = srp_erp_buyback_grn.batchMasterID', 'left');
        $this->db->where('grnAutoID', $grnAutoID);
        $data['master'] = $this->db->get()->row_array();

        $data['master']['CurrencyDes'] = fetch_currency_dec($data['master']['transactionCurrency']);
        $this->db->select('srp_erp_buyback_grndetails.*');
        $this->db->where('srp_erp_buyback_grndetails.grnAutoID', $grnAutoID);
        $this->db->from('srp_erp_buyback_grndetails');
        $this->db->join('srp_erp_buyback_grn', 'srp_erp_buyback_grn.grnAutoID = srp_erp_buyback_grndetails.grnAutoID', 'left');
        $data['detail'] = $this->db->get()->result_array();

        return $data;
    }


    function add_item()
    {
        $result = $this->db->query('INSERT INTO srp_erp_buyback_itemmaster (
                                    itemAutoID, itemSystemCode, secondaryItemCode, itemImage,
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
                                 itemAutoID,itemSystemCode, seconeryItemCode, itemImage, itemName,
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
        if ($result) {
            $this->session->set_flashdata('s', 'Records added Successfully');
            return array('status' => true);
        }
    }

    function buyback_itemType_update()
    {
        $buybackItemID = trim($this->input->post('buybackItemID'));
        $buybackItemType = trim($this->input->post('buybackItemType'));

        if ($buybackItemType == 2) {
            $data['buybackItemType'] = trim($this->input->post('buybackItemType'));
            $data['feedType'] = trim($this->input->post('buybackFeedType'));
        } else {
            $data['buybackItemType'] = trim($this->input->post('buybackItemType'));
            $data['feedType'] = '';
        }

        $this->db->where('buybackItemID', $buybackItemID);
        $this->db->update('srp_erp_buyback_itemmaster', $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Item Type Update Failed ' . $this->db->_error_message());

        } else {
            $this->db->trans_commit();
            return array('s', 'Item Type Updated Successfully.');
        }
    }

    function fetch_buyback_item_recode()
    {

            $dataArr = array();
            $dataArr2 = array();
            $dataArr2['query'] = 'test';
            $companyCode = $this->common_data['company_data']['company_code'];
            $search_string = "%" . $_GET['query'] . "%";
            $data = $this->db->query('SELECT srp_erp_buyback_itemmaster.mainCategoryID,srp_erp_buyback_itemmaster.subcategoryID,srp_erp_buyback_itemmaster.secondaryItemCode,srp_erp_buyback_itemmaster.subSubCategoryID,srp_erp_buyback_itemmaster.revenueGLCode,srp_erp_buyback_itemmaster.itemSystemCode,srp_erp_buyback_itemmaster.costGLCode,assetSystemGLCode,srp_erp_buyback_itemmaster.defaultUnitOfMeasure,srp_erp_buyback_itemmaster.defaultUnitOfMeasureID,srp_erp_buyback_itemmaster.itemDescription,srp_erp_buyback_itemmaster.itemAutoID,srp_erp_buyback_itemmaster.currentStock,srp_erp_buyback_itemmaster.companyLocalWacAmount,srp_erp_buyback_itemmaster.companyLocalSellingPrice,srp_erp_itemmaster.currentStock as currentStock,CONCAT(srp_erp_buyback_itemmaster.itemDescription, " (" ,srp_erp_buyback_itemmaster.itemSystemCode,")") AS "Match"  FROM srp_erp_buyback_itemmaster LEFT JOIN `srp_erp_itemmaster` on srp_erp_itemmaster.itemAutoID = srp_erp_buyback_itemmaster.itemAutoID
        WHERE (srp_erp_itemmaster.itemSystemCode LIKE "' . $search_string . '" OR srp_erp_itemmaster.itemDescription LIKE "' . $search_string . '" OR srp_erp_buyback_itemmaster.secondaryItemCode LIKE "' . $search_string . '") AND srp_erp_buyback_itemmaster.companyCode = "' . $companyCode . '" AND srp_erp_buyback_itemmaster.isActive = 1 AND buybackItemType IS NOT NULL')->result_array();
            if (!empty($data)) {
                foreach ($data as $val) {
                    $dataArr[] = array('value' => $val["Match"], 'data' => $val['itemSystemCode'], 'itemAutoID' => $val['itemAutoID'], 'currentStock' => $val['currentStock'], 'defaultUnitOfMeasure' => $val['defaultUnitOfMeasure'], 'defaultUnitOfMeasureID' => $val['defaultUnitOfMeasureID'], 'companyLocalSellingPrice' => $val['companyLocalSellingPrice'], 'companyLocalWacAmount' => $val['companyLocalWacAmount']);
                }

            }
            $dataArr2['suggestions'] = $dataArr;
            return $dataArr2;
        }

    function fetch_buyback_item_recode_grn()
    {
        $dataArr = array();
        $dataArr2 = array();
        $dataArr2['query'] = 'test';
        $companyCode = $this->common_data['company_data']['company_code'];
        $search_string = "%" . $_GET['query'] . "%";
        $data = $this->db->query('SELECT srp_erp_buyback_itemmaster.mainCategoryID,srp_erp_buyback_itemmaster.subcategoryID,srp_erp_buyback_itemmaster.secondaryItemCode,srp_erp_buyback_itemmaster.subSubCategoryID,srp_erp_buyback_itemmaster.revenueGLCode,srp_erp_buyback_itemmaster.itemSystemCode,srp_erp_buyback_itemmaster.costGLCode,assetSystemGLCode,srp_erp_buyback_itemmaster.defaultUnitOfMeasure,srp_erp_buyback_itemmaster.defaultUnitOfMeasureID,srp_erp_buyback_itemmaster.itemDescription,srp_erp_buyback_itemmaster.itemAutoID,srp_erp_buyback_itemmaster.currentStock,srp_erp_buyback_itemmaster.companyLocalWacAmount,srp_erp_buyback_itemmaster.companyLocalSellingPrice,srp_erp_itemmaster.currentStock as currentStock,CONCAT(srp_erp_buyback_itemmaster.itemDescription, " (" ,srp_erp_buyback_itemmaster.itemSystemCode,")") AS "Match"  FROM srp_erp_buyback_itemmaster LEFT JOIN `srp_erp_itemmaster` on srp_erp_itemmaster.itemAutoID = srp_erp_buyback_itemmaster.itemAutoID
        WHERE (srp_erp_itemmaster.itemSystemCode LIKE "' . $search_string . '" OR srp_erp_itemmaster.itemDescription LIKE "' . $search_string . '" OR srp_erp_buyback_itemmaster.secondaryItemCode LIKE "' . $search_string . '") AND srp_erp_buyback_itemmaster.companyCode = "' . $companyCode . '" AND srp_erp_buyback_itemmaster.isActive = 1 AND buybackItemType = 4')->result_array();
        if (!empty($data)) {
            foreach ($data as $val) {
                $dataArr[] = array('value' => $val["Match"], 'data' => $val['itemSystemCode'], 'itemAutoID' => $val['itemAutoID'], 'currentStock' => $val['currentStock'], 'defaultUnitOfMeasure' => $val['defaultUnitOfMeasure'], 'defaultUnitOfMeasureID' => $val['defaultUnitOfMeasureID'], 'companyLocalSellingPrice' => $val['companyLocalSellingPrice'], 'companyLocalWacAmount' => $val['companyLocalWacAmount']);
            }

        }
        $dataArr2['suggestions'] = $dataArr;
        return $dataArr2;
    }

    function dispatch_note_confirmation()
    {
        $dispatchAutoID = trim($this->input->post('dispatchAutoID'));
        $this->db->select('dispatchAutoID,batchMasterID');
        $this->db->where('dispatchAutoID', $dispatchAutoID);
        $this->db->from('srp_erp_buyback_dispatchnote');
        $BatchID = $this->db->get()->result_array();

        if (count($BatchID) > 1){

            $this->db->select('dispatchDetailsID');
            $this->db->where('dispatchAutoID', $dispatchAutoID);
            $this->db->from('srp_erp_buyback_dispatchnotedetails');
            $results = $this->db->get()->result_array();
            if (empty($results)) {
                return array('error' => 2, 'message' => 'There are no records to confirm this document!');
            } else {
                $this->db->select('*');
                $this->db->where('dispatchAutoID', $dispatchAutoID);
                $this->db->where('confirmedYN', 1);
                $this->db->from('srp_erp_buyback_dispatchnote');
                $row = $this->db->get()->row_array();
                if (!empty($row)) {
                    return array('w', 'Document already confirmed');
                } else {
                    $this->load->library('approvals');
                    $this->db->select('*');
                    $this->db->where('dispatchAutoID', $dispatchAutoID);
                    $this->db->from('srp_erp_buyback_dispatchnote');
                    $row = $this->db->get()->row_array();
                    $approvals_status = $this->approvals->CreateApproval('BBDPN', $row['dispatchAutoID'],
                        $row['documentSystemCode'], 'Dispatch Note', 'srp_erp_buyback_dispatchnote', 'dispatchAutoID', 0);
                    if ($approvals_status == 1) {
                        $data = array('confirmedYN' => 1, 'confirmedDate' => $this->common_data['current_date'],
                            'confirmedByEmpID' => $this->common_data['current_userID'],
                            'confirmedByName' => $this->common_data['current_user']);

                        $this->db->where('dispatchAutoID', $dispatchAutoID);
                        $this->db->update('srp_erp_buyback_dispatchnote', $data);

                        return array('s', 'Dispatch Note : Confirmed Successfully. ');
                    } else {
                        return array('e', 'something went wrong');

                    }
                }
            }
        }else{
            $this->db->select('dispatchDetailsID');
            $this->db->where('dispatchAutoID', $dispatchAutoID);
            $this->db->from('srp_erp_buyback_dispatchnotedetails');
            $results = $this->db->get()->result_array();
            if (empty($results)) {
                return array('error' => 2, 'message' => 'There are no records to confirm this document!');
            } else {
                $this->db->select('buybackItemType');
                $this->db->where('dispatchAutoID', $dispatchAutoID);
                $this->db->where('buybackItemType', 1);
                $this->db->from('srp_erp_buyback_dispatchnotedetails');
                $itemType = $this->db->get()->result_array();

                if(!empty($itemType)){
                    $this->db->select('*');
                    $this->db->where('dispatchAutoID', $dispatchAutoID);
                    $this->db->where('confirmedYN', 1);
                    $this->db->from('srp_erp_buyback_dispatchnote');
                    $row = $this->db->get()->row_array();
                    if (!empty($row)) {
                        return array('w', 'Document already confirmed');
                    } else {
                        $this->load->library('approvals');
                        $this->db->select('*');
                        $this->db->where('dispatchAutoID', $dispatchAutoID);
                        $this->db->from('srp_erp_buyback_dispatchnote');
                        $row = $this->db->get()->row_array();
                        $approvals_status = $this->approvals->CreateApproval('BBDPN', $row['dispatchAutoID'],
                            $row['documentSystemCode'], 'Dispatch Note', 'srp_erp_buyback_dispatchnote', 'dispatchAutoID', 0);
                        if ($approvals_status == 1) {
                            $data = array('confirmedYN' => 1, 'confirmedDate' => $this->common_data['current_date'],
                                'confirmedByEmpID' => $this->common_data['current_userID'],
                                'confirmedByName' => $this->common_data['current_user']);

                            $this->db->where('dispatchAutoID', $dispatchAutoID);
                            $this->db->update('srp_erp_buyback_dispatchnote', $data);

                            return array('s', 'Dispatch Note : Confirmed Successfully. ');
                        } else {
                            return array('e', 'something went wrong');

                        }
                    }
                } else{
                    return array('error' => 2, 'message' => 'No chicks are assigned to this batch');
                }
            }
        }

    }

    function dispatch_note_confirmation_test()
    {
        $dispatchAutoID = trim($this->input->post('dispatchAutoID'));

        $this->db->trans_start();

        $data = array(
            'confirmedYN' => 1,
            'confirmedDate' => $this->common_data['current_date'],
            'confirmedByEmpID' => $this->common_data['current_userID'],
            'confirmedByName' => $this->common_data['current_user'],
        );
        $this->db->where('dispatchAutoID', $dispatchAutoID);
        $updateMaster = $this->db->update('srp_erp_buyback_dispatchnote', $data);

        $this->db->select('*');
        $this->db->where('dispatchAutoID', $dispatchAutoID);
        $this->db->from('srp_erp_buyback_dispatchnote');
        $master = $this->db->get()->row_array();

        $this->db->select('*');
        $this->db->where('dispatchAutoID', $dispatchAutoID);
        $this->db->from('srp_erp_buyback_dispatchnotedetails');
        $item_detail = $this->db->get()->result_array();

        if ($updateMaster) {
            for ($a = 0; $a < count($item_detail); $a++) {
                $item = fetch_item_data($item_detail[$a]['itemAutoID']);
                if ($item['mainCategory'] == 'Inventory' or $item['mainCategory'] == 'Non Inventory') {
                    $itemAutoID = $item_detail[$a]['itemAutoID'];
                    $qty = $item_detail[$a]['qty'] / $item_detail[$a]['conversionRateUOM'];
                    //$wareHouseAutoID = $item_detail[$a]['wareHouseAutoID'];

                    $item_arr[$a]['itemAutoID'] = $item_detail[$a]['itemAutoID'];
                    $item_arr[$a]['currentStock'] = ($item['currentStock'] - $qty);
                    $item_arr[$a]['companyLocalWacAmount'] = round(((($item['currentStock'] * $item['companyLocalWacAmount']) - ($item['companyLocalWacAmount'] * $qty)) / $item_arr[$a]['currentStock']), $master['companyLocalCurrencyDecimalPlaces']);
                    $item_arr[$a]['companyReportingWacAmount'] = round(((($item['currentStock'] * $item['companyReportingWacAmount']) - ($item['companyReportingWacAmount'] * $qty)) / $item_arr[$a]['currentStock']), $master['companyReportingCurrencyDecimalPlaces']);
                    $itemledger_arr[$a]['documentCode'] = $master['documentID'];
                    $itemledger_arr[$a]['documentAutoID'] = $master['dispatchAutoID'];
                    $itemledger_arr[$a]['documentSystemCode'] = $master['documentSystemCode'];
                    $itemledger_arr[$a]['documentDate'] = $master['documentDate'];
                    $itemledger_arr[$a]['batchID'] = $master['batchMasterID'];
                    $itemledger_arr[$a]['farmID'] = $master['farmID'];
                    $itemledger_arr[$a]['referenceNumber'] = $master['referenceNo'];
                    $itemledger_arr[$a]['companyFinanceYearID'] = $master['companyFinanceYearID'];
                    $itemledger_arr[$a]['companyFinanceYear'] = $master['companyFinanceYear'];
                    $itemledger_arr[$a]['FYBegin'] = $master['FYBegin'];
                    $itemledger_arr[$a]['FYEnd'] = $master['FYEnd'];
                    $itemledger_arr[$a]['FYPeriodDateFrom'] = $master['FYPeriodDateFrom'];
                    $itemledger_arr[$a]['FYPeriodDateTo'] = $master['FYPeriodDateTo'];
                    //$itemledger_arr[$a]['wareHouseAutoID'] = $item_detail[$a]['wareHouseAutoID'];
                    //$itemledger_arr[$a]['wareHouseCode'] = $item_detail[$a]['wareHouseCode'];
                    //$itemledger_arr[$a]['wareHouseLocation'] = $item_detail[$a]['wareHouseLocation'];
                    //$itemledger_arr[$a]['wareHouseDescription'] = $item_detail[$a]['wareHouseDescription'];
                    $itemledger_arr[$a]['itemAutoID'] = $item_detail[$a]['itemAutoID'];
                    $itemledger_arr[$a]['itemSystemCode'] = $item_detail[$a]['itemSystemCode'];
                    $itemledger_arr[$a]['buybackItemType'] = $item_detail[$a]['buybackItemType'];
                    $itemledger_arr[$a]['itemDescription'] = $item_detail[$a]['itemDescription'];
                    $itemledger_arr[$a]['defaultUOMID'] = $item_detail[$a]['defaultUOMID'];
                    $itemledger_arr[$a]['defaultUOM'] = $item_detail[$a]['defaultUOM'];
                    $itemledger_arr[$a]['transactionUOMID'] = $item_detail[$a]['unitOfMeasureID'];
                    $itemledger_arr[$a]['transactionUOM'] = $item_detail[$a]['unitOfMeasure'];
                    $itemledger_arr[$a]['transactionQTY'] = $item_detail[$a]['qty'];
                    $itemledger_arr[$a]['convertionRate'] = $item_detail[$a]['conversionRateUOM'];
                    $itemledger_arr[$a]['currentStock'] = $item_arr[$a]['currentStock'];

                    $itemledger_arr[$a]['expenseGLAutoID'] = $item['costGLAutoID'];
                    $itemledger_arr[$a]['expenseGLCode'] = $item['costGLCode'];
                    $itemledger_arr[$a]['expenseSystemGLCode'] = $item['costSystemGLCode'];
                    $itemledger_arr[$a]['expenseGLDescription'] = $item['costDescription'];
                    $itemledger_arr[$a]['expenseGLType'] = $item['costType'];
                    $itemledger_arr[$a]['revenueGLAutoID'] = $item['revanueGLAutoID'];
                    $itemledger_arr[$a]['revenueGLCode'] = $item['revanueGLCode'];
                    $itemledger_arr[$a]['revenueSystemGLCode'] = $item['revanueSystemGLCode'];
                    $itemledger_arr[$a]['revenueGLDescription'] = $item['revanueDescription'];
                    $itemledger_arr[$a]['revenueGLType'] = $item['revanueType'];
                    $itemledger_arr[$a]['assetGLAutoID'] = $item['assteGLAutoID'];
                    $itemledger_arr[$a]['assetGLCode'] = $item['assteGLCode'];
                    $itemledger_arr[$a]['assetSystemGLCode'] = $item['assteSystemGLCode'];
                    $itemledger_arr[$a]['assetGLDescription'] = $item['assteDescription'];
                    $itemledger_arr[$a]['assetGLType'] = $item['assteType'];
                    $itemledger_arr[$a]['transactionCurrencyDecimalPlaces'] = $master['transactionCurrencyDecimalPlaces'];

                    $unitTransactionAmount = $item_detail[$a]['unitActualCost'] / $master['transactionExchangeRate'];
                    $itemledger_arr[$a]['unitTransactionAmount'] = round($unitTransactionAmount, $master['transactionCurrencyDecimalPlaces']);
                    $itemledger_arr[$a]['totalTransactionAmount'] = $itemledger_arr[$a]['unitTransactionAmount'] * $item_detail[$a]['qty'];
                    $itemledger_arr[$a]['unitTransferAmountTransaction'] = $item_detail[$a]['unitTransferCost'];
                    $itemledger_arr[$a]['totalTransferAmountTransaction'] = $item_detail[$a]['totalTransferCost'];

                    $itemledger_arr[$a]['unitLocalAmount'] = $item_detail[$a]['unitActualCost'];
                    $itemledger_arr[$a]['totalLocalAmount'] = ($item_detail[$a]['qty'] * $item_detail[$a]['unitActualCost']);
                    $itemledger_arr[$a]['unitTransferAmountLocal'] = $item_detail[$a]['unitTransferCostLocal'];
                    $itemledger_arr[$a]['totalTransferAmountLocal'] = $item_detail[$a]['totalTransferCostLocal'];

                    $unitReportingAmount = $item_detail[$a]['unitActualCost'] / $master['companyReportingExchangeRate'];
                    $itemledger_arr[$a]['unitReportingAmount'] = round($unitReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);
                    $itemledger_arr[$a]['totalReportingAmount'] = $itemledger_arr[$a]['unitReportingAmount'] * $item_detail[$a]['qty'];
                    $itemledger_arr[$a]['unitTransferAmountReporting'] = $item_detail[$a]['unitTransferCostReporting'];
                    $itemledger_arr[$a]['totalTranferAmountReporting'] = ($item_detail[$a]['totalTransferCostReporting']);

                    //$ex_rate_wac = (1 / $master['companyLocalExchangeRate']);
                    //$itemledger_arr[$a]['transactionAmount'] = round((($item_detail[$a]['companyLocalWacAmount'] / $ex_rate_wac) * ($itemledger_arr[$a]['transactionQTY'] / $item_detail[$a]['conversionRateUOM'])), $itemledger_arr[$a]['transactionCurrencyDecimalPlaces']);
                    //$itemledger_arr[$a]['salesPrice'] = (($item_detail[$a]['transactionAmount'] / ($itemledger_arr[$a]['transactionQTY'] / $item_detail[$a]['conversionRateUOM'])) * -1);
                    $itemledger_arr[$a]['transactionCurrencyID'] = $master['transactionCurrencyID'];
                    $itemledger_arr[$a]['transactionCurrency'] = $master['transactionCurrency'];
                    $itemledger_arr[$a]['transactionExchangeRate'] = $master['transactionExchangeRate'];

                    $itemledger_arr[$a]['companyLocalCurrencyID'] = $master['companyLocalCurrencyID'];
                    $itemledger_arr[$a]['companyLocalCurrency'] = $master['companyLocalCurrency'];
                    $itemledger_arr[$a]['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                    $itemledger_arr[$a]['companyLocalCurrencyDecimalPlaces'] = $master['companyLocalCurrencyDecimalPlaces'];
                    //$itemledger_arr[$a]['companyLocalAmount'] = round(($itemledger_arr[$a]['transactionAmount'] / $itemledger_arr[$a]['companyLocalExchangeRate']), $itemledger_arr[$a]['companyLocalCurrencyDecimalPlaces']);
                    $itemledger_arr[$a]['companyLocalWacAmount'] = $item['companyLocalWacAmount'];
                    $itemledger_arr[$a]['companyReportingCurrencyID'] = $master['companyReportingCurrencyID'];
                    $itemledger_arr[$a]['companyReportingCurrency'] = $master['companyReportingCurrency'];
                    $itemledger_arr[$a]['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                    $itemledger_arr[$a]['companyReportingCurrencyDecimalPlaces'] = $master['companyReportingCurrencyDecimalPlaces'];
                    //$itemledger_arr[$a]['companyReportingAmount'] = round(($itemledger_arr[$a]['transactionAmount'] / $itemledger_arr[$a]['companyReportingExchangeRate']), $itemledger_arr[$a]['companyReportingCurrencyDecimalPlaces']);
                    $itemledger_arr[$a]['companyReportingWacAmount'] = $item['companyReportingWacAmount'];

                    $itemledger_arr[$a]['partyCurrencyID'] = $master['farmerCurrencyID'];
                    $itemledger_arr[$a]['partyCurrency'] = $master['farmerCurrency'];
                    $itemledger_arr[$a]['partyCurrencyExchangeRate'] = $master['farmerCurrencyExchangeRate'];
                    $itemledger_arr[$a]['partyCurrencyDecimalPlaces'] = $master['farmerCurrencyDecimalPlaces'];
                    //$itemledger_arr[$a]['partyCurrencyAmount'] = round(($itemledger_arr[$a]['transactionAmount'] / $itemledger_arr[$a]['partyCurrencyExchangeRate']), $itemledger_arr[$a]['partyCurrencyDecimalPlaces']);

                    $itemledger_arr[$a]['confirmedYN'] = $master['confirmedYN'];
                    $itemledger_arr[$a]['confirmedByEmpID'] = $master['confirmedByEmpID'];
                    $itemledger_arr[$a]['confirmedByName'] = $master['confirmedByName'];
                    $itemledger_arr[$a]['confirmedDate'] = $master['confirmedDate'];
                    $itemledger_arr[$a]['approvedYN'] = $master['approvedYN'];
                    $itemledger_arr[$a]['approvedDate'] = $master['approvedDate'];
                    $itemledger_arr[$a]['approvedbyEmpID'] = $master['approvedbyEmpID'];
                    $itemledger_arr[$a]['approvedbyEmpName'] = $master['approvedbyEmpName'];
                    $itemledger_arr[$a]['segmentID'] = $master['segmentID'];
                    $itemledger_arr[$a]['segmentCode'] = $master['segmentCode'];
                    $itemledger_arr[$a]['companyID'] = $master['companyID'];
                    $itemledger_arr[$a]['companyCode'] = $master['companyCode'];
                    $itemledger_arr[$a]['createdUserGroup'] = $master['createdUserGroup'];
                    $itemledger_arr[$a]['createdPCID'] = $master['createdPCID'];
                    $itemledger_arr[$a]['createdUserID'] = $master['createdUserID'];
                    $itemledger_arr[$a]['createdDateTime'] = $master['createdDateTime'];
                    $itemledger_arr[$a]['createdUserName'] = $master['createdUserName'];
                    $itemledger_arr[$a]['modifiedPCID'] = $master['modifiedPCID'];
                    $itemledger_arr[$a]['modifiedUserID'] = $master['modifiedUserID'];
                    $itemledger_arr[$a]['modifiedDateTime'] = $master['modifiedDateTime'];
                    $itemledger_arr[$a]['modifiedUserName'] = $master['modifiedUserName'];
                }

            }

            if (!empty($itemledger_arr)) {
                $itemledger_arr = array_values($itemledger_arr);
                $this->db->insert_batch('srp_erp_buyback_itemledger', $itemledger_arr);
            }

        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Dispatch Note Confirm Failed ' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Dispatch Note Confirmed Successfully.');
        }
    }


    function getBatch_detail($batchMasterID)
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select("batchMasterID,closingComment,grade,confirmedYN,approvedYN,isclosed,batchCode,srp_erp_buyback_farmmaster.description AS farmerName,srp_erp_buyback_farmmaster.address as farmerAddress,srp_erp_buyback_farmmaster.farmSystemCode AS farmerCode,batchClosingDate");
        $this->db->from('srp_erp_buyback_batch');
        $this->db->join('srp_erp_buyback_farmmaster', 'srp_erp_buyback_farmmaster.farmID = srp_erp_buyback_batch.farmID', 'LEFT');
        $this->db->where('batchMasterID', $batchMasterID);
        $result = $this->db->get()->row_array();
        return $result;
    }

    function buyback_batchLock_confirmation()
    {
        $batchMasterID = trim($this->input->post('batchMasterID'));

        $this->db->select('*');
        $this->db->where('batchMasterID', $batchMasterID);
        $this->db->where('confirmedYN', 1);
        $this->db->from('srp_erp_buyback_batch');
        $row = $this->db->get()->row_array();
        if (!empty($row)) {
            return array('w', 'Document already confirmed');
        } else {
            $this->load->library('approvals');
            $this->db->select('*');
            $this->db->where('batchMasterID', $batchMasterID);
            $this->db->from('srp_erp_buyback_batch');
            $row = $this->db->get()->row_array();
            $approvals_status = $this->approvals->CreateApproval('BBBC', $row['batchMasterID'],
                $row['batchCode'], 'Batch', 'srp_erp_buyback_batch', 'batchMasterID', 0);
            if ($approvals_status == 1) {
                $data = array('confirmedYN' => 1, 'confirmedDate' => $this->common_data['current_date'],
                    'confirmedByEmpID' => $this->common_data['current_userID'],
                    'confirmedByName' => $this->common_data['current_user']);

                $this->db->where('batchMasterID', $batchMasterID);
                $this->db->update('srp_erp_buyback_batch', $data);

                $data_lock['isclosed'] = 1;
                $data_lock['closedByEmpID'] = $this->common_data['current_userID'];
                $data_lock['closedDate'] = $this->common_data['current_date'];
                $data_lock['closingComment'] = $this->input->post('comments');
                $data_lock['grade'] = $this->input->post('Grading');
                $this->db->where('batchMasterID', $batchMasterID);
                $this->db->update('srp_erp_buyback_batch', $data_lock);

                return array('s', 'Batch : Confirmed Successfully. ');
            } else {
                return array('e', 'something went wrong');

            }
        }
    }

    function save_mortality_header()
    {
        $this->db->trans_start();
        $date_format_policy = date_format_policy();

        $mortalityAutoID = trim($this->input->post('mortalityAutoID'));
        $documentDate = $this->input->post('documentDate');

        $format_documentDate = null;
        if (isset($documentDate) && !empty($documentDate)) {
            $format_documentDate = input_format_date($documentDate, $date_format_policy);
        }

        $data['batchMasterID'] = trim($this->input->post('batchMasterID'));
        $data['farmID'] = trim($this->input->post('farmID'));
        $data['documentDate'] = $format_documentDate;
        //$data['referenceNo'] = trim($this->input->post('referenceno'));
        $data['narration'] = trim($this->input->post('narration'));

        if ($mortalityAutoID) {
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];

            $this->db->where('mortalityAutoID', $mortalityAutoID);
            $this->db->update('srp_erp_buyback_mortalitymaster', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Mortality Update ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Mortality Updated Successfully.', $mortalityAutoID);
            }
        } else {

            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_mortalitymaster', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Mortality Creating' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Mortality is created successfully.', $last_id);

            }
        }
    }

    function load_mortality_header()
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('*,DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate');
        $this->db->from('srp_erp_buyback_mortalitymaster');
        $this->db->where('mortalityAutoID', trim($this->input->post('mortalityAutoID')));
        return $this->db->get()->row_array();
    }

    function save_mortality_birds_detail()
    {
        $mortalityAutoID = trim($this->input->post('mortalityAutoID'));
        $currentbirds = trim($this->input->post('currentbirds'));
        $causeIDs = $this->input->post('causeID');
        $noOfBirds = $this->input->post('noOfBirds');
        $comment = $this->input->post('comment');
        $totalbirds = 0;
        $isIncre = 0;
        $this->db->trans_start();

        $farm_officer_id = $this->db->query("select mortalityAutoID,mm.farmID,fm.empID as empid,mm.documentDate as inspecteddate from srp_erp_buyback_mortalitymaster mm left join srp_erp_buyback_farmfieldofficers fm on fm.farmID = mm.farmID 
where mortalityAutoID = $mortalityAutoID")->row_array();

        foreach ($causeIDs as $key => $causeID) {

            $totalbirds += $noOfBirds[$key];
            if ($totalbirds > $currentbirds) {
                $isIncre++;
            } else {
                $data['mortalityAutoID'] = $mortalityAutoID;
                $data['causeID'] = $causeID;
                $data['noOfBirds'] = $noOfBirds[$key];
                $data['remarks'] = $comment[$key];
                $data['companyCode'] = $this->common_data['company_data']['company_code'];
                $data['companyID'] = $this->common_data['company_data']['company_id'];
                $data['createdUserGroup'] = $this->common_data['user_group'];
                $data['createdPCID'] = $this->common_data['current_pc'];
                $data['createdUserID'] = $this->common_data['current_userID'];
                $data['createdUserName'] = $this->common_data['current_user'];
                $data['createdDateTime'] = $this->common_data['current_date'];
                $data['inpectedEmpID'] = $farm_officer_id['empid'];
                $data['inspectedDate'] = $farm_officer_id['inspecteddate'];
                $this->db->insert('srp_erp_buyback_mortalitydetails', $data);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Mortality Details :  Save Failed ' . $this->db->_error_message());
        } else {
            if ($isIncre > 0) {
                $this->db->trans_rollback();
                return array('e', 'No of birds cannot be greater than current birds');
            } else {
                $this->db->trans_commit();
                return array('s', 'Mortality Details :  Added Successfully.');

            }
        }


    }

    function fetch_mortality_bird_detail()
    {
        $this->db->select('*');
        $this->db->from('srp_erp_buyback_mortalitydetails');
        $this->db->where('mortalityDetailID', trim($this->input->post('mortalityDetailID')));
        return $this->db->get()->row_array();
    }

    function fetch_buyback_item_masterEdit()
    {
        $this->db->select('buybackItemID,buybackItemType,feedType');
        $this->db->from('srp_erp_buyback_itemmaster');
        $this->db->where('buybackItemID', trim($this->input->post('buybackItemID')));
        return $this->db->get()->row_array();
    }

    function update_mortality_birds_detail()
    {

        $mortalityDetailID = $this->input->post('mortalityDetailID');

        $this->db->trans_start();

        $data['causeID'] = trim($this->input->post('causeID'));
        $data['noOfBirds'] = trim($this->input->post('noOfBirds'));
        $data['remarks'] = trim($this->input->post('remarks'));
        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedUserName'] = $this->common_data['current_user'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];

        if (trim($mortalityDetailID)) {
            $this->db->where('mortalityDetailID', trim($mortalityDetailID));
            $this->db->update('srp_erp_buyback_mortalitydetails', $data);

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Mortality Detail Update Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Mortality Detail Updated Successfully.');

            }
        }
    }

    function delete_mortality_birds_detail()
    {
        $mortalityDetailID = trim($this->input->post('mortalityDetailID'));
        $this->db->delete('srp_erp_buyback_mortalitydetails', array('mortalityDetailID' => $mortalityDetailID));
        return true;
    }

    function delete_mortality_master()
    {
        $mortalityAutoID = trim($this->input->post('mortalityAutoID'));
        $this->db->delete('srp_erp_buyback_mortalitydetails', array('mortalityAutoID' => $mortalityAutoID));
        $this->db->delete('srp_erp_buyback_mortalitymaster', array('mortalityAutoID' => $mortalityAutoID));
        return true;
    }

    function load_mortality_confirmation($mortalityAutoID)
    {

        $convertFormat = convert_date_format_sql();

        $data['master'] = $this->db->query("select mm.confirmedByEmpID ,mm.confirmedByName ,DATE_FORMAT(mm.confirmedDate,'.$convertFormat. %h:%i:%s') AS confirmedDate,DATE_FORMAT(documentDate,'{$convertFormat}') AS documentDate, mm.confirmedYN,fm.description as farmerName,batch.batchCode,fm.farmSystemCode as farmerCode FROM srp_erp_buyback_mortalitymaster mm LEFT JOIN srp_erp_buyback_farmmaster fm ON fm.farmID = mm.farmID LEFT JOIN srp_erp_buyback_batch batch ON mm.batchMasterID = batch.batchMasterID WHERE mortalityAutoID = $mortalityAutoID ")->row_array();

        $data['bird_detail'] = $this->db->query("select md.mortalityDetailID,md.noOfBirds,md.remarks,mc.Description AS mortalityCause FROM srp_erp_buyback_mortalitydetails md LEFT JOIN srp_erp_buyback_mortalitycauses mc ON mc.causeID = md.causeID  WHERE mortalityAutoID = $mortalityAutoID ORDER BY mortalityDetailID DESC")->result_array();

        return $data;

    }

    function mortality_confirmation()
    {

        $mortalityAutoID = trim($this->input->post('mortalityAutoID'));

        $data = array('confirmedYN' => 1, 'confirmedDate' => $this->common_data['current_date'],
            'confirmedByEmpID' => $this->common_data['current_userID'],
            'confirmedByName' => $this->common_data['current_user']);

        $this->db->where('mortalityAutoID', $mortalityAutoID);
        $this->db->update('srp_erp_buyback_mortalitymaster', $data);

        return array('s', 'Confirmed Successfully. ');

    }

    function load_mortalityCourses_header()
    {
        $causeID = trim($this->input->post('causeID'));
        $data = $this->db->query("select causeID,Description FROM srp_erp_buyback_mortalitycauses WHERE causeID = {$causeID} ")->row_array();
        return $data;
    }

    function save_mortalityCourses_header()
    {
        $this->db->trans_start();

        $companyID = $this->common_data['company_data']['company_id'];

        $causeID = trim($this->input->post('causeID'));

        $data['Description'] = trim($this->input->post('description'));

        if ($causeID) {
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];

            $this->db->where('causeID', $causeID);
            $this->db->update('srp_erp_buyback_mortalitycauses', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Mortality Course Update ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Mortality Course Updated Successfully.');
            }
        } else {
            $data['companyID'] = $companyID;
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_mortalitycauses', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Mortality Course Creating' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Mortality Course created successfully.');

            }
        }
    }

    function delete_mortalityCourse()
    {
        $causeID = trim($this->input->post('causeID'));
        $this->db->delete('srp_erp_buyback_mortalitycauses', array('causeID' => $causeID));
        return true;
    }

    function good_receipt_note_confirmation()
    {
        $grnAutoID = trim($this->input->post('grnAutoID'));
        $this->db->select('*');
        $this->db->where('grnAutoID', $grnAutoID);
        $this->db->where('confirmedYN', 1);
        $this->db->from('srp_erp_buyback_grn');
        $row = $this->db->get()->row_array();
        if (!empty($row)) {
            return array('w', 'Document already confirmed');
        } else {
            $this->load->library('approvals');
            $this->db->select('*');
            $this->db->where('grnAutoID', $grnAutoID);
            $this->db->from('srp_erp_buyback_grn');
            $row = $this->db->get()->row_array();
            $approvals_status = $this->approvals->CreateApproval('BBGRN', $row['grnAutoID'],
                $row['documentSystemCode'], 'Good Receipt Note', 'srp_erp_buyback_grn', 'grnAutoID', 0);
            if ($approvals_status == 1) {
                $data = array('confirmedYN' => 1, 'confirmedDate' => $this->common_data['current_date'],
                    'confirmedByEmpID' => $this->common_data['current_userID'],
                    'confirmedByName' => $this->common_data['current_user']);

                $this->db->where('grnAutoID', $grnAutoID);
                $this->db->update('srp_erp_buyback_grn', $data);

                return array('s', 'Good Receipt Note : Confirmed Successfully. ');
            } else {
                return array('e', 'something went wrong');

            }
        }
    }


    function good_receipt_note_confirmation_test()
    {
        $grnAutoID = trim($this->input->post('grnAutoID'));

        $this->db->trans_start();

        $data = array(
            'confirmedYN' => 1,
            'confirmedDate' => $this->common_data['current_date'],
            'confirmedByEmpID' => $this->common_data['current_userID'],
            'confirmedByName' => $this->common_data['current_user'],
        );
        $this->db->where('grnAutoID', $grnAutoID);
        $updateMaster = $this->db->update('srp_erp_buyback_grn', $data);

        $this->db->select('*');
        $this->db->where('grnAutoID', $grnAutoID);
        $this->db->from('srp_erp_buyback_grn');
        $master = $this->db->get()->row_array();

        $this->db->select('*');
        $this->db->where('grnAutoID', $grnAutoID);
        $this->db->from('srp_erp_buyback_grndetails');
        $item_detail = $this->db->get()->result_array();

        if ($updateMaster) {
            for ($a = 0; $a < count($item_detail); $a++) {
                $item = fetch_item_data($item_detail[$a]['itemAutoID']);
                if ($item['mainCategory'] == 'Inventory' or $item['mainCategory'] == 'Non Inventory') {
                    $itemAutoID = $item_detail[$a]['itemAutoID'];
                    $qty = $item_detail[$a]['qty'] / $item_detail[$a]['conversionRateUOM'];
                    //$wareHouseAutoID = $item_detail[$a]['wareHouseAutoID'];

                    $item_arr[$a]['itemAutoID'] = $item_detail[$a]['itemAutoID'];
                    $item_arr[$a]['currentStock'] = ($item['currentStock'] - $qty);
                    $item_arr[$a]['companyLocalWacAmount'] = round(((($item['currentStock'] * $item['companyLocalWacAmount']) - ($item['companyLocalWacAmount'] * $qty)) / $item_arr[$a]['currentStock']), $master['companyLocalCurrencyDecimalPlaces']);
                    $item_arr[$a]['companyReportingWacAmount'] = round(((($item['currentStock'] * $item['companyReportingWacAmount']) - ($item['companyReportingWacAmount'] * $qty)) / $item_arr[$a]['currentStock']), $master['companyReportingCurrencyDecimalPlaces']);
                    $itemledger_arr[$a]['documentCode'] = $master['documentID'];
                    $itemledger_arr[$a]['documentAutoID'] = $master['grnAutoID'];
                    $itemledger_arr[$a]['documentSystemCode'] = $master['documentSystemCode'];
                    $itemledger_arr[$a]['documentDate'] = $master['documentDate'];
                    $itemledger_arr[$a]['batchID'] = $master['batchMasterID'];
                    $itemledger_arr[$a]['farmID'] = $master['farmID'];
                    $itemledger_arr[$a]['referenceNumber'] = $master['referenceNo'];
                    $itemledger_arr[$a]['companyFinanceYearID'] = $master['companyFinanceYearID'];
                    $itemledger_arr[$a]['companyFinanceYear'] = $master['companyFinanceYear'];
                    $itemledger_arr[$a]['FYBegin'] = $master['FYBegin'];
                    $itemledger_arr[$a]['FYEnd'] = $master['FYEnd'];
                    $itemledger_arr[$a]['FYPeriodDateFrom'] = $master['FYPeriodDateFrom'];
                    $itemledger_arr[$a]['FYPeriodDateTo'] = $master['FYPeriodDateTo'];
                    //$itemledger_arr[$a]['wareHouseAutoID'] = $item_detail[$a]['wareHouseAutoID'];
                    //$itemledger_arr[$a]['wareHouseCode'] = $item_detail[$a]['wareHouseCode'];
                    //$itemledger_arr[$a]['wareHouseLocation'] = $item_detail[$a]['wareHouseLocation'];
                    //$itemledger_arr[$a]['wareHouseDescription'] = $item_detail[$a]['wareHouseDescription'];
                    $itemledger_arr[$a]['itemAutoID'] = $item_detail[$a]['itemAutoID'];
                    $itemledger_arr[$a]['itemSystemCode'] = $item_detail[$a]['itemSystemCode'];
                    $itemledger_arr[$a]['itemDescription'] = $item_detail[$a]['itemDescription'];
                    $itemledger_arr[$a]['defaultUOMID'] = $item_detail[$a]['defaultUOMID'];
                    $itemledger_arr[$a]['defaultUOM'] = $item_detail[$a]['defaultUOM'];
                    $itemledger_arr[$a]['transactionUOMID'] = $item_detail[$a]['unitOfMeasureID'];
                    $itemledger_arr[$a]['transactionUOM'] = $item_detail[$a]['unitOfMeasure'];
                    $itemledger_arr[$a]['transactionQTY'] = $item_detail[$a]['qty'];
                    $itemledger_arr[$a]['convertionRate'] = $item_detail[$a]['conversionRateUOM'];
                    //$itemledger_arr[$a]['currentStock'] = $item_detail[$a]['currentStock'];
                    $itemledger_arr[$a]['noOfBirds'] = $item_detail[$a]['noOfBirds'];

                    $itemledger_arr[$a]['expenseGLAutoID'] = $item['costGLAutoID'];
                    $itemledger_arr[$a]['expenseGLCode'] = $item['costGLCode'];
                    $itemledger_arr[$a]['expenseSystemGLCode'] = $item['costSystemGLCode'];
                    $itemledger_arr[$a]['expenseGLDescription'] = $item['costDescription'];
                    $itemledger_arr[$a]['expenseGLType'] = $item['costType'];
                    $itemledger_arr[$a]['revenueGLAutoID'] = $item['revanueGLAutoID'];
                    $itemledger_arr[$a]['revenueGLCode'] = $item['revanueGLCode'];
                    $itemledger_arr[$a]['revenueSystemGLCode'] = $item['revanueSystemGLCode'];
                    $itemledger_arr[$a]['revenueGLDescription'] = $item['revanueDescription'];
                    $itemledger_arr[$a]['revenueGLType'] = $item['revanueType'];
                    $itemledger_arr[$a]['assetGLAutoID'] = $item['assteGLAutoID'];
                    $itemledger_arr[$a]['assetGLCode'] = $item['assteGLCode'];
                    $itemledger_arr[$a]['assetSystemGLCode'] = $item['assteSystemGLCode'];
                    $itemledger_arr[$a]['assetGLDescription'] = $item['assteDescription'];
                    $itemledger_arr[$a]['assetGLType'] = $item['assteType'];
                    $itemledger_arr[$a]['transactionCurrencyDecimalPlaces'] = $master['transactionCurrencyDecimalPlaces'];

                    //$unitTransactionAmount = $item_detail[$a]['unitActualCost'] / $master['transactionExchangeRate'];
                    //$itemledger_arr[$a]['unitTransactionAmount'] = round($unitTransactionAmount, $master['transactionCurrencyDecimalPlaces']);
                    //$itemledger_arr[$a]['totalTransactionAmount'] = $itemledger_arr[$a]['unitTransactionAmount'] * $item_detail[$a]['qty'];
                    $itemledger_arr[$a]['unitTransferAmountTransaction'] = $item_detail[$a]['unitCost'];
                    $itemledger_arr[$a]['totalTransferAmountTransaction'] = $item_detail[$a]['totalCost'];

                    //$itemledger_arr[$a]['unitLocalAmount'] = $item_detail[$a]['unitActualCost'];
                    //$itemledger_arr[$a]['totalLocalAmount'] = ($item_detail[$a]['qty'] * $item_detail[$a]['unitActualCost']);
                    $itemledger_arr[$a]['unitTransferAmountLocal'] = $item_detail[$a]['unitCostLocal'];
                    $itemledger_arr[$a]['totalTransferAmountLocal'] = ($item_detail[$a]['unitCostLocal'] * $item_detail[$a]['qty']);

                    //$unitReportingAmount = $item_detail[$a]['unitActualCost'] / $master['companyReportingExchangeRate'];
                    //$itemledger_arr[$a]['unitReportingAmount'] = round($unitReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);
                    //$itemledger_arr[$a]['totalReportingAmount'] = $itemledger_arr[$a]['unitReportingAmount'] * $item_detail[$a]['qty'];
                    $itemledger_arr[$a]['unitTransferAmountReporting'] = $item_detail[$a]['unitCostReporting'];
                    $itemledger_arr[$a]['totalTranferAmountReporting'] = ($item_detail[$a]['unitCostReporting'] * $item_detail[$a]['qty']);

                    //$ex_rate_wac = (1 / $master['companyLocalExchangeRate']);
                    //$itemledger_arr[$a]['transactionAmount'] = round((($item_detail[$a]['companyLocalWacAmount'] / $ex_rate_wac) * ($itemledger_arr[$a]['transactionQTY'] / $item_detail[$a]['conversionRateUOM'])), $itemledger_arr[$a]['transactionCurrencyDecimalPlaces']);
                    //$itemledger_arr[$a]['salesPrice'] = (($item_detail[$a]['transactionAmount'] / ($itemledger_arr[$a]['transactionQTY'] / $item_detail[$a]['conversionRateUOM'])) * -1);
                    $itemledger_arr[$a]['transactionCurrencyID'] = $master['transactionCurrencyID'];
                    $itemledger_arr[$a]['transactionCurrency'] = $master['transactionCurrency'];
                    $itemledger_arr[$a]['transactionExchangeRate'] = $master['transactionExchangeRate'];

                    $itemledger_arr[$a]['companyLocalCurrencyID'] = $master['companyLocalCurrencyID'];
                    $itemledger_arr[$a]['companyLocalCurrency'] = $master['companyLocalCurrency'];
                    $itemledger_arr[$a]['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                    $itemledger_arr[$a]['companyLocalCurrencyDecimalPlaces'] = $master['companyLocalCurrencyDecimalPlaces'];
                    //$itemledger_arr[$a]['companyLocalAmount'] = round(($itemledger_arr[$a]['transactionAmount'] / $itemledger_arr[$a]['companyLocalExchangeRate']), $itemledger_arr[$a]['companyLocalCurrencyDecimalPlaces']);
                    $itemledger_arr[$a]['companyLocalWacAmount'] = $item['companyLocalWacAmount'];
                    $itemledger_arr[$a]['companyReportingCurrencyID'] = $master['companyReportingCurrencyID'];
                    $itemledger_arr[$a]['companyReportingCurrency'] = $master['companyReportingCurrency'];
                    $itemledger_arr[$a]['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                    $itemledger_arr[$a]['companyReportingCurrencyDecimalPlaces'] = $master['companyReportingCurrencyDecimalPlaces'];
                    //$itemledger_arr[$a]['companyReportingAmount'] = round(($itemledger_arr[$a]['transactionAmount'] / $itemledger_arr[$a]['companyReportingExchangeRate']), $itemledger_arr[$a]['companyReportingCurrencyDecimalPlaces']);
                    $itemledger_arr[$a]['companyReportingWacAmount'] = $item['companyReportingWacAmount'];

                    $itemledger_arr[$a]['partyCurrencyID'] = $master['farmerCurrencyID'];
                    $itemledger_arr[$a]['partyCurrency'] = $master['farmerCurrency'];
                    $itemledger_arr[$a]['partyCurrencyExchangeRate'] = $master['farmerCurrencyExchangeRate'];
                    $itemledger_arr[$a]['partyCurrencyDecimalPlaces'] = $master['farmerCurrencyDecimalPlaces'];
                    //$itemledger_arr[$a]['partyCurrencyAmount'] = round(($itemledger_arr[$a]['transactionAmount'] / $itemledger_arr[$a]['partyCurrencyExchangeRate']), $itemledger_arr[$a]['partyCurrencyDecimalPlaces']);

                    $itemledger_arr[$a]['confirmedYN'] = $master['confirmedYN'];
                    $itemledger_arr[$a]['confirmedByEmpID'] = $master['confirmedByEmpID'];
                    $itemledger_arr[$a]['confirmedByName'] = $master['confirmedByName'];
                    $itemledger_arr[$a]['confirmedDate'] = $master['confirmedDate'];
                    $itemledger_arr[$a]['approvedYN'] = $master['approvedYN'];
                    $itemledger_arr[$a]['approvedDate'] = $master['approvedDate'];
                    $itemledger_arr[$a]['approvedbyEmpID'] = $master['approvedbyEmpID'];
                    $itemledger_arr[$a]['approvedbyEmpName'] = $master['approvedbyEmpName'];
                    $itemledger_arr[$a]['segmentID'] = $master['segmentID'];
                    $itemledger_arr[$a]['segmentCode'] = $master['segmentCode'];
                    $itemledger_arr[$a]['companyID'] = $master['companyID'];
                    $itemledger_arr[$a]['companyCode'] = $master['companyCode'];
                    $itemledger_arr[$a]['createdUserGroup'] = $master['createdUserGroup'];
                    $itemledger_arr[$a]['createdPCID'] = $master['createdPCID'];
                    $itemledger_arr[$a]['createdUserID'] = $master['createdUserID'];
                    $itemledger_arr[$a]['createdDateTime'] = $master['createdDateTime'];
                    $itemledger_arr[$a]['createdUserName'] = $master['createdUserName'];
                    $itemledger_arr[$a]['modifiedPCID'] = $master['modifiedPCID'];
                    $itemledger_arr[$a]['modifiedUserID'] = $master['modifiedUserID'];
                    $itemledger_arr[$a]['modifiedDateTime'] = $master['modifiedDateTime'];
                    $itemledger_arr[$a]['modifiedUserName'] = $master['modifiedUserName'];
                }

            }

            if (!empty($itemledger_arr)) {
                $itemledger_arr = array_values($itemledger_arr);
                $this->db->insert_batch('srp_erp_buyback_itemledger', $itemledger_arr);
            }

        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Good Receipt Note Confirm Failed ' . $this->db->_error_message());

        } else {
            $this->db->trans_commit();
            return array('s', 'Good Receipt Note Confirmed Successfully.');
        }


    }

    function fetch_farmerDetails_For_dispatchNote()
    {
        $this->db->select('contactPerson,phoneHome');
        $this->db->from('srp_erp_buyback_farmmaster');
        $this->db->where('farmID', trim($this->input->post('farmID')));
        return $this->db->get()->row_array();
    }


    function save_payment_voucher_header()
    {
        $this->db->trans_start();
        $date_format_policy = date_format_policy();

        $pvMasterAutoID = trim($this->input->post('pvMasterAutoID'));
        $documentDate = $this->input->post('documentDate');
        $PVchequeDate = $this->input->post('PVchequeDate');
        $PVtype = trim($this->input->post('PVtype'));
        $segment = explode('|', trim($this->input->post('segment')));
        $massageType = '';
        if ($PVtype == 1) {
            $massageType = 'Payment Voucher';
        } else {
            $massageType = 'Receipt Voucher';
        }
        $format_documentDate = null;
        if (isset($documentDate) && !empty($documentDate)) {
            $format_documentDate = input_format_date($documentDate, $date_format_policy);
        }
        $format_PVchequeDate = null;
        if (isset($PVchequeDate) && !empty($PVchequeDate)) {
            $format_PVchequeDate = input_format_date($PVchequeDate, $date_format_policy);
        }
        $year = explode(' - ', trim($this->input->post('companyFinanceYear')));
        $FYBegin = input_format_date($year[0], $date_format_policy);
        $FYEnd = input_format_date($year[1], $date_format_policy);
        $currency_code = explode('|', trim($this->input->post('currency_code')));

        $data['PVtype'] = $PVtype;
        $data['documentDate'] = $format_documentDate;
        $data['referenceNo'] = trim($this->input->post('referenceno'));
        $data['PVNarration'] = trim($this->input->post('narration'));
        $data['companyFinanceYearID'] = trim($this->input->post('financeyear'));
        $data['companyFinanceYear'] = trim($this->input->post('companyFinanceYear'));
        $data['farmID'] = trim($this->input->post('farmID'));
        $data['FYBegin'] = trim($FYBegin);
        $data['FYEnd'] = trim($FYEnd);
        $data['companyFinancePeriodID'] = trim($this->input->post('financeyear_period'));
        $data['PVbankCode'] = trim($this->input->post('PVbankCode'));
        $bank_detail = fetch_gl_account_desc($data['PVbankCode']);
        $data['bankGLAutoID'] = $bank_detail['GLAutoID'];
        $data['bankSystemAccountCode'] = $bank_detail['systemAccountCode'];
        $data['bankGLSecondaryCode'] = $bank_detail['GLSecondaryCode'];
        $data['bankCurrencyID'] = $bank_detail['bankCurrencyID'];
        $data['bankCurrency'] = $bank_detail['bankCurrencyCode'];
        $data['PVbank'] = $bank_detail['bankName'];
        $data['PVbankBranch'] = $bank_detail['bankBranch'];
        $data['PVbankSwiftCode'] = $bank_detail['bankSwiftCode'];
        $data['PVbankAccount'] = $bank_detail['bankAccountNumber'];
        $data['PVbankType'] = $bank_detail['subCategory'];
        $data['BatchID'] = trim($this->input->post('batchMasterID'));
        if ($bank_detail['isCash'] == 1) {
            $data['PVchequeNo'] = null;
            $data['PVchequeDate'] = null;
        } else {
            $data['PVchequeNo'] = trim($this->input->post('PVchequeNo'));
            $data['PVchequeDate'] = trim($format_PVchequeDate);
        }
        $data['modeOfPayment'] = ($bank_detail['isCash'] == 1 ? 1 : 2);
        $data['transactionCurrencyID'] = trim($this->input->post('transactionCurrencyID'));
        $data['transactionCurrency'] = trim($currency_code[0]);
        $data['transactionExchangeRate'] = 1;
        $data['transactionCurrencyDecimalPlaces'] = fetch_currency_desimal_by_id($data['transactionCurrencyID']);
        $data['companyLocalCurrencyID'] = $this->common_data['company_data']['company_default_currencyID'];
        $data['companyLocalCurrency'] = $this->common_data['company_data']['company_default_currency'];
        $default_currency = currency_conversionID($data['transactionCurrencyID'], $data['companyLocalCurrencyID']);
        $data['companyLocalExchangeRate'] = $default_currency['conversion'];
        $data['companyLocalCurrencyDecimalPlaces'] = $default_currency['DecimalPlaces'];
        $data['companyReportingCurrency'] = $this->common_data['company_data']['company_reporting_currency'];
        $data['companyReportingCurrencyID'] = $this->common_data['company_data']['company_reporting_currencyID'];
        $reporting_currency = currency_conversionID($data['transactionCurrencyID'], $data['companyReportingCurrencyID']);
        $data['companyReportingExchangeRate'] = $reporting_currency['conversion'];
        $data['companyReportingCurrencyDecimalPlaces'] = $reporting_currency['DecimalPlaces'];
        $data['segmentID'] = trim($segment[0]);
        $data['segmentCode'] = trim($segment[1]);
        if ($pvMasterAutoID) {
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];

            $this->db->where('pvMasterAutoID', $pvMasterAutoID);
            $this->db->update('srp_erp_buyback_paymentvouchermaster', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in ' . $massageType . ' Update ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', '' . $massageType . ' Updated Successfully.', $pvMasterAutoID);
            }
        } else {
            $this->load->library('sequence');
            if ($PVtype == 1) {
                $data['documentID'] = 'BBPV';
            } else if ($PVtype == 2) {
                $data['documentID'] = 'BBRV';
            } else if ($PVtype == 3) {
                $data['documentID'] = 'BBSV';
            }
            $data['documentSystemCode'] = $this->sequence->sequence_generator($data['documentID']);
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_paymentvouchermaster', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in ' . $massageType . ' Creating' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', '' . $massageType . ' is created successfully.', $last_id);

            }
        }
    }


    function load_paymentVoucher_header()
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('*,DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate,DATE_FORMAT(PVchequeDate,\'' . $convertFormat . '\') AS PVchequeDate');
        $this->db->from('srp_erp_buyback_paymentvouchermaster');
        $this->db->where('pvMasterAutoID', trim($this->input->post('pvMasterAutoID')));
        return $this->db->get()->row_array();
    }

    function save_paymentVoucher_advance()
    {
        $this->db->trans_start();
        $batchMasterID = trim($this->input->post('batchMasterID'));
        $pvDetailID = trim($this->input->post('pvDetailID'));
        $pvMasterAutoID = trim($this->input->post('pvMasterAutoID'));

        $this->db->select('transactionCurrency, transactionExchangeRate, transactionCurrencyID,companyLocalCurrencyID, companyLocalCurrency,companyLocalExchangeRate, companyReportingCurrency ,companyReportingExchangeRate ,partyCurrency,partyExchangeRate,partyCurrencyID,companyReportingCurrencyID,segmentID,segmentCode');
        $this->db->where('pvMasterAutoID', trim($this->input->post('pvMasterAutoID')));
        $master = $this->db->get('srp_erp_buyback_paymentvouchermaster')->row_array();

        $data['pvMasterAutoID'] = $pvMasterAutoID;
        $data['BatchID'] = $batchMasterID;
        $data['transactionAmount'] = trim($this->input->post('amount'));
        $data['segmentID'] = $master['segmentID'];
        $data['segmentCode'] = $master['segmentCode'];
        $data['transactionCurrencyID'] = $master['transactionCurrencyID'];
        $data['transactionCurrency'] = $master['transactionCurrency'];
        $data['transactionExchangeRate'] = $master['transactionExchangeRate'];
        $data['companyLocalCurrencyID'] = $master['companyLocalCurrencyID'];
        $data['companyLocalCurrency'] = $master['companyLocalCurrency'];
        $data['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
        $data['companyLocalAmount'] = ($data['transactionAmount'] / $master['companyLocalExchangeRate']);
        $data['companyReportingCurrencyID'] = $master['companyReportingCurrencyID'];
        $data['companyReportingCurrency'] = $master['companyReportingCurrency'];
        $data['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
        $data['companyReportingAmount'] = ($data['transactionAmount'] / $master['companyReportingExchangeRate']);
        //$data['partyAmount'] = ($data['transactionAmount'] / $data['partyExchangeRate']);
        //$data['payVoucherAutoId'] = trim($this->input->post('payVoucherAutoId'));
        $data['comment'] = trim($this->input->post('description'));
        $data['type'] = 'Advance';

        if ($pvDetailID) {
            $this->db->where('pvDetailID', $pvDetailID);
            $this->db->update('srp_erp_buyback_paymentvoucherdetail', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Payment Voucher Advance Update Failed' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Payment Voucher Advance Updated Successfully', $pvMasterAutoID);
            }
        } else {
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_paymentvoucherdetail', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Payment Voucher Advance Saved Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Payment Voucher Advance Saved Successfully.', $pvMasterAutoID);
            }
        }
    }

    function fetch_paymentVoucher_data($pvMasterAutoID)
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('pvm.pvMasterAutoID,DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate,pvm.documentSystemCode,pvm.referenceNo,pvm.confirmedYN,pvm.confirmedByName,pvm.confirmedDate,pvm.approvedbyEmpID,pvm.approvedbyEmpName,pvm.approvedYN,DATE_FORMAT(approvedDate,\'' . $convertFormat . ' %h:%i:%s\') AS approvedDate,fm.description as farmName,fm.farmSystemCode as farmerCode,fm.address as farmAddress,fm.phoneMobile as farmTelephone,pvm.PVbank,pvm.PVbankBranch,pvm.PVbankAccount,pvm.PVbankSwiftCode,cm.CurrencyName,pvm.transactionCurrency,pvm.PVchequeNo,pvm.PVNarration,DATE_FORMAT(PVchequeDate,\'' . $convertFormat . '\') AS PVchequeDate,pvm.transactionCurrencyDecimalPlaces,PVtype');
        $this->db->from('srp_erp_buyback_paymentvouchermaster pvm');
        $this->db->join('srp_erp_buyback_farmmaster fm', 'fm.farmID = pvm.farmID', 'left');
        $this->db->join('srp_erp_currencymaster cm', 'pvm.transactionCurrencyID = cm.currencyID', 'left');
        $this->db->where('pvMasterAutoID', $pvMasterAutoID);
        $data['master'] = $this->db->get()->row_array();

        $this->db->select('batch.batchCode as documentSystemCode');
        $this->db->from('srp_erp_buyback_paymentvoucherdetail pvd');
        $this->db->join('srp_erp_buyback_batch batch', 'pvd.BatchID = batch.batchMasterID', 'left');
        $this->db->where('pvMasterAutoID', $pvMasterAutoID);
        $data['detail'] = $this->db->get()->row_array();

        $data['master']['CurrencyDes'] = fetch_currency_dec($data['master']['transactionCurrency']);
        $this->db->select('pvDetailID,batch.batchCode,pvd.GLCode,pvd.GLDescription,segmentCode,transactionAmount,transactionCurrencyDecimalPlaces');
        $this->db->from('srp_erp_buyback_paymentvoucherdetail pvd');
        $this->db->join('srp_erp_buyback_batch batch', 'pvd.lossedBatchID = batch.batchMasterID', 'left');
        $this->db->where('pvMasterAutoID', $pvMasterAutoID);
        $this->db->where('type', 'Expense');
        $this->db->order_by('pvDetailID', 'DESC');
        $data['expense'] = $this->db->get()->result_array();

        $this->db->select('pvDetailID,batch.batchCode,pvd.comment,transactionAmount,transactionCurrencyDecimalPlaces');
        $this->db->from('srp_erp_buyback_paymentvoucherdetail pvd');
        $this->db->join('srp_erp_buyback_batch batch', 'pvd.lossedBatchID = batch.batchMasterID', 'left');
        $this->db->where('pvMasterAutoID', $pvMasterAutoID);
        $this->db->where('type', 'Advance');
        $this->db->order_by('pvDetailID', 'DESC');
        $data['advance'] = $this->db->get()->result_array();

        $this->db->select('pvDetailID,pvd.comment,transactionAmount,transactionCurrencyDecimalPlaces,GLCode,pvd.GLDescription,segmentCode');
        $this->db->from('srp_erp_buyback_paymentvoucherdetail pvd');
        $this->db->where('pvMasterAutoID', $pvMasterAutoID);
        $this->db->where('type', 'Deposit');
        $this->db->order_by('pvDetailID', 'DESC');
        $data['income'] = $this->db->get()->result_array();

        $this->db->select('pvDetailID,batch.batchCode,pvd.comment,transactionAmount,transactionCurrencyDecimalPlaces,isMatching');
        $this->db->from('srp_erp_buyback_paymentvoucherdetail pvd');
        $this->db->join('srp_erp_buyback_batch batch', 'pvd.lossedBatchID = batch.batchMasterID', 'left');
        $this->db->where('pvMasterAutoID', $pvMasterAutoID);
        $this->db->where('type', 'Loan');
        $this->db->order_by('pvDetailID', 'DESC');
        $data['loan'] = $this->db->get()->result_array();

        $this->db->select('pvDetailID,batch.batchCode,pvd.comment,transactionAmount,transactionCurrencyDecimalPlaces');
        $this->db->from('srp_erp_buyback_paymentvoucherdetail pvd');
        $this->db->join('srp_erp_buyback_batch batch', 'pvd.lossedBatchID = batch.batchMasterID', 'left');
        $this->db->where('pvMasterAutoID', $pvMasterAutoID);
        $this->db->where('type', 'Batch');
        $this->db->order_by('pvDetailID', 'DESC');
        $data['batch'] = $this->db->get()->result_array();

        $this->db->select('pvDetailID,batch.batchCode,pvd.comment,transactionAmount,transactionCurrencyDecimalPlaces,isMatching,type');
        $this->db->from('srp_erp_buyback_paymentvoucherdetail pvd');
        $this->db->join('srp_erp_buyback_batch batch', 'pvd.lossedBatchID = batch.batchMasterID', 'left');
        $this->db->where('pvMasterAutoID', $pvMasterAutoID);
        $this->db->order_by('pvDetailID', 'DESC');
        $data['settlement'] = $this->db->get()->result_array();

        return $data;
    }

    function save_paymentVoucher_expense_multiple()
    {
        $this->db->trans_start();

        $pvMasterAutoID = trim($this->input->post('pvMasterAutoID'));

        $this->db->select('transactionCurrencyID,transactionCurrency, transactionExchangeRate, companyLocalCurrencyID,companyLocalCurrency,companyLocalExchangeRate, companyReportingCurrencyID,companyReportingCurrency ,companyReportingExchangeRate ,partyCurrency,partyExchangeRate');
        $this->db->where('pvMasterAutoID', $pvMasterAutoID);
        $master_record = $this->db->get('srp_erp_buyback_paymentvouchermaster')->row_array();

        $gl_codes = $this->input->post('gl_code');
        $gl_code_des = $this->input->post('gl_code_des');
        $amount = $this->input->post('amount');
        $descriptions = $this->input->post('description');
        $segment_gls = $this->input->post('segment_gl');
        $batchMasterID = $this->input->post('batchMasterID');

        foreach ($gl_codes as $key => $gl_code) {
            $segment = explode('|', $segment_gls[$key]);
            $gl_code = explode('|', $gl_code_des[$key]);

            $data[$key]['pvMasterAutoID'] = $pvMasterAutoID;
            $data[$key]['BatchID'] = $batchMasterID[$key];
            $data[$key]['GLAutoID'] = $gl_codes[$key];
            $data[$key]['systemGLCode'] = trim($gl_code[0]);
            $data[$key]['GLCode'] = trim($gl_code[1]);
            $data[$key]['GLDescription'] = trim($gl_code[2]);
            $data[$key]['GLType'] = trim($gl_code[3]);
            $data[$key]['segmentID'] = trim($segment[0]);
            $data[$key]['segmentCode'] = trim($segment[1]);
            $data[$key]['transactionCurrencyID'] = $master_record['transactionCurrencyID'];
            $data[$key]['transactionCurrency'] = $master_record['transactionCurrency'];
            $data[$key]['transactionExchangeRate'] = $master_record['transactionExchangeRate'];
            $data[$key]['transactionAmount'] = $amount[$key];
            $data[$key]['companyLocalCurrencyID'] = $master_record['companyLocalCurrencyID'];
            $data[$key]['companyLocalCurrency'] = $master_record['companyLocalCurrency'];
            $data[$key]['companyLocalExchangeRate'] = $master_record['companyLocalExchangeRate'];
            $data[$key]['companyLocalAmount'] = ($data[$key]['transactionAmount'] / $master_record['companyLocalExchangeRate']);
            $data[$key]['companyReportingCurrencyID'] = $master_record['companyReportingCurrencyID'];
            $data[$key]['companyReportingCurrency'] = $master_record['companyReportingCurrency'];
            $data[$key]['companyReportingExchangeRate'] = $master_record['companyReportingExchangeRate'];
            $data[$key]['companyReportingAmount'] = ($data[$key]['transactionAmount'] / $master_record['companyReportingExchangeRate']);
            //$data[$key]['partyCurrency'] = $master_record['partyCurrency'];
            //$data[$key]['partyExchangeRate'] = $master_record['partyExchangeRate'];
            //$data[$key]['partyAmount'] = ($data[$key]['transactionAmount'] / $master_record['partyExchangeRate']);
            $data[$key]['comment'] = $descriptions[$key];
            $data[$key]['type'] = 'Expense';
            $data[$key]['companyCode'] = $this->common_data['company_data']['company_code'];
            $data[$key]['companyID'] = $this->common_data['company_data']['company_id'];
            $data[$key]['createdUserGroup'] = $this->common_data['user_group'];
            $data[$key]['createdPCID'] = $this->common_data['current_pc'];
            $data[$key]['createdUserID'] = $this->common_data['current_userID'];
            $data[$key]['createdUserName'] = $this->common_data['current_user'];
            $data[$key]['createdDateTime'] = $this->common_data['current_date'];
        }
        $this->db->insert_batch('srp_erp_buyback_paymentvoucherdetail', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Payment Voucher Expense Detail :  Save Failed' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Payment Voucher Expense Detail :  Saved Successfully.');
        }

    }

    function save_receiptVoucher_income_multiple()
    {
        $this->db->trans_start();

        $pvMasterAutoID = trim($this->input->post('pvMasterAutoID'));

        $this->db->select('transactionCurrencyID,transactionCurrency, transactionExchangeRate, companyLocalCurrencyID,companyLocalCurrency,companyLocalExchangeRate, companyReportingCurrencyID,companyReportingCurrency ,companyReportingExchangeRate ,partyCurrency,partyExchangeRate');
        $this->db->where('pvMasterAutoID', $pvMasterAutoID);
        $master_record = $this->db->get('srp_erp_buyback_paymentvouchermaster')->row_array();

        $gl_codes = $this->input->post('gl_code_income');
        $gl_code_des = $this->input->post('gl_code_des');
        $amount = $this->input->post('amount');
        $descriptions = $this->input->post('description');
        $segment_gls = $this->input->post('segment_gl');

        foreach ($gl_codes as $key => $gl_code) {
            $segment = explode('|', $segment_gls[$key]);
            $gl_code = explode('|', $gl_code_des[$key]);

            $data[$key]['pvMasterAutoID'] = $pvMasterAutoID;
            $data[$key]['GLAutoID'] = $gl_codes[$key];
            $data[$key]['systemGLCode'] = trim($gl_code[0]);
            $data[$key]['GLCode'] = trim($gl_code[1]);
            $data[$key]['GLDescription'] = trim($gl_code[2]);
            $data[$key]['GLType'] = trim($gl_code[3]);
            $data[$key]['segmentID'] = trim($segment[0]);
            $data[$key]['segmentCode'] = trim($segment[1]);
            $data[$key]['transactionCurrencyID'] = $master_record['transactionCurrencyID'];
            $data[$key]['transactionCurrency'] = $master_record['transactionCurrency'];
            $data[$key]['transactionExchangeRate'] = $master_record['transactionExchangeRate'];
            $data[$key]['transactionAmount'] = $amount[$key];
            $data[$key]['companyLocalCurrencyID'] = $master_record['companyLocalCurrencyID'];
            $data[$key]['companyLocalCurrency'] = $master_record['companyLocalCurrency'];
            $data[$key]['companyLocalExchangeRate'] = $master_record['companyLocalExchangeRate'];
            $data[$key]['companyLocalAmount'] = ($data[$key]['transactionAmount'] / $master_record['companyLocalExchangeRate']);
            $data[$key]['companyReportingCurrencyID'] = $master_record['companyReportingCurrencyID'];
            $data[$key]['companyReportingCurrency'] = $master_record['companyReportingCurrency'];
            $data[$key]['companyReportingExchangeRate'] = $master_record['companyReportingExchangeRate'];
            $data[$key]['companyReportingAmount'] = ($data[$key]['transactionAmount'] / $master_record['companyReportingExchangeRate']);
            //$data[$key]['partyCurrency'] = $master_record['partyCurrency'];
            //$data[$key]['partyExchangeRate'] = $master_record['partyExchangeRate'];
            //$data[$key]['partyAmount'] = ($data[$key]['transactionAmount'] / $master_record['partyExchangeRate']);
            $data[$key]['comment'] = $descriptions[$key];
            $data[$key]['type'] = 'Income';
            $data[$key]['companyCode'] = $this->common_data['company_data']['company_code'];
            $data[$key]['companyID'] = $this->common_data['company_data']['company_id'];
            $data[$key]['createdUserGroup'] = $this->common_data['user_group'];
            $data[$key]['createdPCID'] = $this->common_data['current_pc'];
            $data[$key]['createdUserID'] = $this->common_data['current_userID'];
            $data[$key]['createdUserName'] = $this->common_data['current_user'];
            $data[$key]['createdDateTime'] = $this->common_data['current_date'];
        }
        $this->db->insert_batch('srp_erp_buyback_paymentvoucherdetail', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Receipt Voucher Income Detail :  Save Failed ' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Receipt Voucher Income Detail :  Saved Successfully.');
        }

    }

    function delete_paymentVoucher_master()
    {
        $pvMasterAutoID = trim($this->input->post('pvMasterAutoID'));
        $this->db->delete('srp_erp_buyback_paymentvouchermaster', array('pvMasterAutoID' => $pvMasterAutoID));
        $this->db->delete('srp_erp_buyback_paymentvoucherdetail', array('pvMasterAutoID' => $pvMasterAutoID));
        return true;
    }

    function paymentVoucher_confirmation()
    {
        $pvMasterAutoID = trim($this->input->post('pvMasterAutoID'));
        $this->db->select('*');
        $this->db->where('pvMasterAutoID', $pvMasterAutoID);
        $this->db->where('confirmedYN', 1);
        $this->db->from('srp_erp_buyback_paymentvouchermaster');
        $row = $this->db->get()->row_array();
        if (!empty($row)) {
            return array('w', 'Document already confirmed');
        } else {
            if ($row['PVtype'] == 1) {
                $messagePrint = 'Payment Voucher';
            } else if ($row['PVtype'] == 2) {
                $messagePrint = 'Receipt Voucher';
            } else {
                $messagePrint = 'Settlement';
            }
            $this->load->library('approvals');
            $this->db->select('*');
            $this->db->where('pvMasterAutoID', $pvMasterAutoID);
            $this->db->from('srp_erp_buyback_paymentvouchermaster');
            $row = $this->db->get()->row_array();
            $approvals_status = $this->approvals->CreateApproval($row['documentID'], $row['pvMasterAutoID'],
                $row['documentSystemCode'], $messagePrint, 'srp_erp_buyback_paymentvouchermaster', 'pvMasterAutoID', 0);
            if ($approvals_status == 1) {
                $data = array('confirmedYN' => 1, 'confirmedDate' => $this->common_data['current_date'],
                    'confirmedByEmpID' => $this->common_data['current_userID'],
                    'confirmedByName' => $this->common_data['current_user']);
                $this->db->where('pvMasterAutoID', $pvMasterAutoID);
                $this->db->update('srp_erp_buyback_paymentvouchermaster', $data);
                return array('s', '' . $messagePrint . ' : Confirmed Successfully. ');
            } else {
                return array('e', 'something went wrong');
            }
        }
    }

    function fetch_paymentVoucher_expense_detail()
    {
        $this->db->select('*');
        $this->db->from('srp_erp_buyback_paymentvoucherdetail');
        $this->db->where('pvDetailID', trim($this->input->post('pvDetailID')));
        return $this->db->get()->row_array();
    }

    function update_paymentVoucher_expense_detail()
    {
        $this->db->trans_start();
        $this->db->select('transactionCurrency, transactionExchangeRate, companyLocalCurrency,companyLocalExchangeRate, companyReportingCurrency ,companyReportingExchangeRate ,partyCurrency,partyExchangeRate,transactionCurrencyID');
        $this->db->where('pvMasterAutoID', $this->input->post('pvMasterAutoID'));
        $master_recode = $this->db->get('srp_erp_buyback_paymentvouchermaster')->row_array();
        $segment = explode('|', trim($this->input->post('segment_gl')));
        $gl_code = explode('|', trim($this->input->post('gl_code_des')));
        $data['pvMasterAutoID'] = trim($this->input->post('pvMasterAutoID'));
        $data['BatchID'] = trim($this->input->post('batchMasterID'));
        $data['GLAutoID'] = trim($this->input->post('gl_code'));
        $data['systemGLCode'] = trim($gl_code[0]);
        $data['GLCode'] = trim($gl_code[1]);
        $data['GLDescription'] = trim($gl_code[2]);
        $data['GLType'] = trim($gl_code[3]);
        $data['segmentID'] = trim($segment[0]);
        $data['segmentCode'] = trim($segment[1]);
        $data['transactionCurrency'] = $master_recode['transactionCurrency'];
        $data['transactionExchangeRate'] = $master_recode['transactionExchangeRate'];
        $data['transactionAmount'] = trim($this->input->post('amount'));
        $data['companyLocalCurrency'] = $master_recode['companyLocalCurrency'];
        $data['companyLocalExchangeRate'] = $master_recode['companyLocalExchangeRate'];
        $data['companyLocalAmount'] = ($data['transactionAmount'] / $master_recode['companyLocalExchangeRate']);
        $data['companyReportingCurrency'] = $master_recode['companyReportingCurrency'];
        $data['companyReportingExchangeRate'] = $master_recode['companyReportingExchangeRate'];
        $data['companyReportingAmount'] = ($data['transactionAmount'] / $master_recode['companyReportingExchangeRate']);
        //$data['partyCurrency'] = $master_recode['partyCurrency'];
        //$data['partyExchangeRate'] = $master_recode['partyExchangeRate'];
        //$data['partyAmount'] = ($data['transactionAmount'] / $master_recode['partyExchangeRate']);
        $data['comment'] = trim($this->input->post('description'));
        $data['type'] = 'Expense';
        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedUserName'] = $this->common_data['current_user'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];

        if (trim($this->input->post('pvDetailID'))) {
            $this->db->where('pvDetailID', trim($this->input->post('pvDetailID')));
            $this->db->update('srp_erp_buyback_paymentvoucherdetail', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Payment Voucher Expense Detail : ' . $data['GLDescription'] . ' Update Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Payment Voucher Expense Detail : ' . $data['GLDescription'] . ' Updated Successfully.');
            }
        }
    }

    function delete_paymentVoucher_expense_detail()
    {
        $pvDetailID = trim($this->input->post('pvDetailID'));
        $this->db->delete('srp_erp_buyback_paymentvoucherdetail', array('pvDetailID' => $pvDetailID));
        return true;
    }

    function delete_paymentVoucher_advance_detail()
    {
        $pvDetailID = trim($this->input->post('pvDetailID'));
        $this->db->delete('srp_erp_buyback_paymentvoucherdetail', array('pvDetailID' => $pvDetailID));
        return true;
    }


    function update_paymentVoucher_advance_detail()
    {
        $this->db->trans_start();
        $this->db->select('transactionCurrency, transactionExchangeRate, companyLocalCurrency,companyLocalExchangeRate, companyReportingCurrency ,companyReportingExchangeRate ,partyCurrency,partyExchangeRate,transactionCurrencyID');
        $this->db->where('pvMasterAutoID', $this->input->post('pvMasterAutoID'));
        $master_recode = $this->db->get('srp_erp_buyback_paymentvouchermaster')->row_array();
        $segment = explode('|', trim($this->input->post('segment_gl')));
        $gl_code = explode('|', trim($this->input->post('gl_code_des')));
        $data['pvMasterAutoID'] = trim($this->input->post('pvMasterAutoID'));
        $data['BatchID'] = trim($this->input->post('batchMasterID'));
        $data['transactionCurrency'] = $master_recode['transactionCurrency'];
        $data['transactionExchangeRate'] = $master_recode['transactionExchangeRate'];
        $data['transactionAmount'] = trim($this->input->post('amount'));
        $data['companyLocalCurrency'] = $master_recode['companyLocalCurrency'];
        $data['companyLocalExchangeRate'] = $master_recode['companyLocalExchangeRate'];
        $data['companyLocalAmount'] = ($data['transactionAmount'] / $master_recode['companyLocalExchangeRate']);
        $data['companyReportingCurrency'] = $master_recode['companyReportingCurrency'];
        $data['companyReportingExchangeRate'] = $master_recode['companyReportingExchangeRate'];
        $data['companyReportingAmount'] = ($data['transactionAmount'] / $master_recode['companyReportingExchangeRate']);
        //$data['partyCurrency'] = $master_recode['partyCurrency'];
        //$data['partyExchangeRate'] = $master_recode['partyExchangeRate'];
        //$data['partyAmount'] = ($data['transactionAmount'] / $master_recode['partyExchangeRate']);
        $data['comment'] = trim($this->input->post('description'));
        $data['type'] = 'Advance';
        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedUserName'] = $this->common_data['current_user'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];

        if (trim($this->input->post('pvDetailID'))) {
            $this->db->where('pvDetailID', trim($this->input->post('pvDetailID')));
            $this->db->update('srp_erp_buyback_paymentvoucherdetail', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Payment Voucher Advance Detail : Update Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Payment Voucher Advance Detail : Updated Successfully.');
            }
        }
    }

    function delete_goodReceipt_note_master()
    {
        $this->db->delete('srp_erp_buyback_grn', array('grnAutoID' => trim($this->input->post('grnAutoID'))));
        $this->db->delete('srp_erp_buyback_grndetails', array('grnAutoID' => trim($this->input->post('grnAutoID'))));
        return true;
    }


    function fetch_double_entry_buyback_dispatchNote($dispatchAutoID, $code = null)
    {
        $gl_array = array();
        $inv_total = 0;
        $dr_total = 0;
        $party_total = 0;
        $companyLocal_total = 0;
        $companyReporting_total = 0;
        $tax_total = 0;
        $gl_array['gl_detail'] = array();
        $this->db->select('*');
        $this->db->where('dispatchAutoID', $dispatchAutoID);
        $master = $this->db->get('srp_erp_buyback_dispatchnote')->row_array();

        $creditGL = $this->db->query("SELECT dpd.dispatchAutoID,sum(dpd.totalActualCost) as workinprogressamount,(sum(dpd.totalTransferCost)-sum(dpd.totalActualCost)) as wagesamount,dpm.batchMasterID,segmentID,segmentCode,wp.GLAutoID,wp.GLSecondaryCode,wp.GLDescription,wp.systemAccountCode,wp.subCategory AS subCategory,apc.GLAutoID as DWGLAutoID,apc.GLSecondaryCode as DWGLSecondaryCode,apc.GLDescription as DWGLDescription,apc.systemAccountCode as DWsystemAccountCode,apc.subCategory AS DWsubCategory FROM srp_erp_buyback_dispatchnotedetails dpd INNER JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID = dpd.dispatchAutoID INNER JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = dpm.batchMasterID INNER JOIN srp_erp_buyback_farmmaster fm ON fm.farmID = dpm.farmID LEFT JOIN(SELECT * FROM srp_erp_chartofaccounts) wp ON (batch.WIPGLAutoID = wp.GLAutoID) LEFT JOIN(SELECT * FROM srp_erp_chartofaccounts) apc ON (fm.farmersLiabilityGLautoID = apc.GLAutoID) WHERE dpd.dispatchAutoID = $dispatchAutoID")->row_array();

        $this->db->select('*');
        $this->db->where('farmID', $master['farmID']);
        $farmerDetail = $this->db->get('srp_erp_buyback_farmmaster')->row_array();

        $globalArray = array();
        /*creditGL*/
        if ($creditGL) {
            $data_arr['auto_id'] = $creditGL['dispatchAutoID'];
            $data_arr['gl_auto_id'] = $creditGL['GLAutoID'];
            $data_arr['gl_code'] = $creditGL['systemAccountCode'];
            $data_arr['secondary'] = $creditGL['GLSecondaryCode'];
            $data_arr['gl_desc'] = $creditGL['GLDescription'];
            $data_arr['gl_type'] = $creditGL['subCategory'];
            $data_arr['segment_id'] = $creditGL['segmentID'];
            $data_arr['segment'] = $creditGL['segmentCode'];
            $data_arr['projectID'] = NULL;
            $data_arr['projectExchangeRate'] = NULL;
            $data_arr['isAddon'] = 0;
            $data_arr['subLedgerType'] = 0;
            $data_arr['subLedgerDesc'] = null;
            $data_arr['partyContractID'] = null;
            $data_arr['partyType'] = 'Farmer';
            $data_arr['partyAutoID'] = $master['farmID'];
            $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
            $data_arr['partyName'] = $farmerDetail['description'];
            $data_arr['partyCurrencyID'] = $master['farmerCurrencyID'];
            $data_arr['partyCurrency'] = $master['farmerCurrency'];
            $data_arr['transactionExchangeRate'] = null;
            $data_arr['companyLocalExchangeRate'] = null;
            $data_arr['companyReportingExchangeRate'] = null;
            $data_arr['transactionExchangeRate'] = 1;
            $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
            $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
            $data_arr['partyExchangeRate'] = $master['farmerCurrencyExchangeRate'];
            $data_arr['partyCurrencyAmount'] = 0;
            $data_arr['partyCurrencyDecimalPlaces'] = $master['farmerCurrencyDecimalPlaces'];
            $data_arr['gl_dr'] = $creditGL['workinprogressamount'];
            $data_arr['gl_cr'] = '';
            $data_arr['amount_type'] = 'dr';
            array_push($globalArray, $data_arr);

            $data_arr['auto_id'] = $creditGL['dispatchAutoID'];
            $data_arr['gl_auto_id'] = $creditGL['DWGLAutoID'];
            $data_arr['gl_code'] = $creditGL['DWsystemAccountCode'];
            $data_arr['secondary'] = $creditGL['DWGLSecondaryCode'];
            $data_arr['gl_desc'] = $creditGL['DWGLDescription'];
            $data_arr['gl_type'] = $creditGL['DWsubCategory'];
            $data_arr['segment_id'] = $creditGL['segmentID'];
            $data_arr['segment'] = $creditGL['segmentCode'];
            $data_arr['projectID'] = NULL;
            $data_arr['projectExchangeRate'] = NULL;
            $data_arr['isAddon'] = 0;
            $data_arr['subLedgerType'] = 0;
            $data_arr['subLedgerDesc'] = null;
            $data_arr['partyContractID'] = null;
            $data_arr['partyType'] = 'Farmer';
            $data_arr['partyAutoID'] = $master['farmID'];
            $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
            $data_arr['partyName'] = $farmerDetail['description'];
            $data_arr['partyCurrencyID'] = $master['farmerCurrencyID'];
            $data_arr['partyCurrency'] = $master['farmerCurrency'];
            $data_arr['transactionExchangeRate'] = null;
            $data_arr['companyLocalExchangeRate'] = null;
            $data_arr['companyReportingExchangeRate'] = null;
            $data_arr['transactionExchangeRate'] = 1;
            $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
            $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
            $data_arr['partyExchangeRate'] = $master['farmerCurrencyExchangeRate'];
            $data_arr['partyCurrencyAmount'] = 0;
            $data_arr['partyCurrencyDecimalPlaces'] = $master['farmerCurrencyDecimalPlaces'];
            if ($creditGL['wagesamount'] < 0) {
                $data_arr['gl_dr'] = '';
                $data_arr['gl_cr'] = $creditGL['wagesamount'];
            } else {
                $data_arr['gl_dr'] = $creditGL['wagesamount'];
                $data_arr['gl_cr'] = '';
            }
            $data_arr['amount_type'] = 'dr';
            array_push($globalArray, $data_arr);
        }

        /*item GL Asset*/
        $assetGL = $this->db->query("SELECT dpd.dispatchAutoID,sum(dpd.totalActualCost) AS assetGLTotal,dpm.batchMasterID,segmentID,segmentCode,assetGLAutoID,assetSystemGLCode,assetGLCode,assetGLDescription,assetGLType FROM srp_erp_buyback_dispatchnotedetails dpd INNER JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID = dpd.dispatchAutoID INNER JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = dpm.batchMasterID WHERE dpd.dispatchAutoID = $dispatchAutoID GROUP BY assetGLAutoID")->result_array();
        if ($assetGL) {
            foreach ($assetGL as $asset) {
                $data_arr['auto_id'] = $asset['dispatchAutoID'];
                $data_arr['gl_auto_id'] = $asset['assetGLAutoID'];
                $data_arr['gl_code'] = $asset['assetSystemGLCode'];
                $data_arr['secondary'] = $asset['assetGLCode'];
                $data_arr['gl_desc'] = $asset['assetGLDescription'];
                $data_arr['gl_type'] = $asset['assetGLType'];
                $data_arr['segment_id'] = $asset['segmentID'];
                $data_arr['segment'] = $asset['segmentCode'];
                $data_arr['projectID'] = NULL;
                $data_arr['projectExchangeRate'] = NULL;
                $data_arr['isAddon'] = 0;
                $data_arr['subLedgerType'] = 0;
                $data_arr['subLedgerDesc'] = null;
                $data_arr['partyContractID'] = null;
                $data_arr['partyType'] = 'Farmer';
                $data_arr['partyAutoID'] = $master['farmID'];
                $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
                $data_arr['partyName'] = $farmerDetail['description'];
                $data_arr['partyCurrencyID'] = $master['farmerCurrencyID'];
                $data_arr['partyCurrency'] = $master['farmerCurrency'];
                $data_arr['transactionExchangeRate'] = null;
                $data_arr['companyLocalExchangeRate'] = null;
                $data_arr['companyReportingExchangeRate'] = null;
                $data_arr['transactionExchangeRate'] = 1;
                $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                $data_arr['partyExchangeRate'] = $master['farmerCurrencyExchangeRate'];
                $data_arr['partyCurrencyAmount'] = 0;
                $data_arr['partyCurrencyDecimalPlaces'] = $master['farmerCurrencyDecimalPlaces'];;
                $data_arr['gl_dr'] = '';
                $data_arr['gl_cr'] = $asset['assetGLTotal'];
                $data_arr['amount_type'] = 'cr';
                array_push($globalArray, $data_arr);
            }
        }

        /*item GL Revenue*/
        $revenueGL = $this->db->query("SELECT dpd.dispatchAutoID,(sum(dpd.totalTransferCost)-sum(dpd.totalActualCost)) AS revenueGLTotal,dpm.batchMasterID,segmentID,segmentCode,revenueGLAutoID,revenueSystemGLCode,revenueGLCode,revenueGLDescription,revenueGLType FROM srp_erp_buyback_dispatchnotedetails dpd INNER JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID = dpd.dispatchAutoID INNER JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = dpm.batchMasterID WHERE dpd.dispatchAutoID = $dispatchAutoID GROUP BY revenueGLAutoID,batchMasterID")->result_array();
        if ($revenueGL) {
            foreach ($revenueGL as $revenue) {
                $data_arr['auto_id'] = $revenue['dispatchAutoID'];
                $data_arr['gl_auto_id'] = $revenue['revenueGLAutoID'];
                $data_arr['gl_code'] = $revenue['revenueSystemGLCode'];
                $data_arr['secondary'] = $revenue['revenueGLCode'];
                $data_arr['gl_desc'] = $revenue['revenueGLDescription'];
                $data_arr['gl_type'] = $revenue['revenueGLType'];
                $data_arr['segment_id'] = $revenue['segmentID'];
                $data_arr['segment'] = $revenue['segmentCode'];
                $data_arr['projectID'] = NULL;
                $data_arr['projectExchangeRate'] = NULL;
                $data_arr['isAddon'] = 0;
                $data_arr['subLedgerType'] = 0;
                $data_arr['subLedgerDesc'] = null;
                $data_arr['partyContractID'] = null;
                $data_arr['partyType'] = 'Farmer';
                $data_arr['partyAutoID'] = $master['farmID'];
                $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
                $data_arr['partyName'] = $farmerDetail['description'];
                $data_arr['partyCurrencyID'] = $master['farmerCurrencyID'];
                $data_arr['partyCurrency'] = $master['farmerCurrency'];
                $data_arr['transactionExchangeRate'] = null;
                $data_arr['companyLocalExchangeRate'] = null;
                $data_arr['companyReportingExchangeRate'] = null;
                $data_arr['transactionExchangeRate'] = 1;
                $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                $data_arr['partyExchangeRate'] = $master['farmerCurrencyExchangeRate'];
                $data_arr['partyCurrencyAmount'] = 0;
                $data_arr['partyCurrencyDecimalPlaces'] = $master['farmerCurrencyDecimalPlaces'];
                if ($revenue['revenueGLTotal'] < 0) {
                    $data_arr['gl_dr'] = $revenue['revenueGLTotal'];
                    $data_arr['gl_cr'] = '';
                } else {
                    $data_arr['gl_dr'] = '';
                    $data_arr['gl_cr'] = $revenue['revenueGLTotal'];
                }
                $data_arr['amount_type'] = 'cr';
                array_push($globalArray, $data_arr);
            }
        }

        $gl_array['currency'] = $master['transactionCurrency'];
        $gl_array['decimal_places'] = $master['transactionCurrencyDecimalPlaces'];
        $gl_array['code'] = 'BBDPN';
        $gl_array['name'] = 'Dispatch Note';
        $gl_array['primary_Code'] = $master['documentSystemCode'];
        $gl_array['date'] = $master['documentDate'];
        $gl_array['finance_year'] = $master['companyFinanceYear'];
        $gl_array['finance_period'] = $master['FYPeriodDateFrom'] . ' - ' . $master['FYPeriodDateTo'];
        $gl_array['master_data'] = $master;
        $gl_array['farmer'] = $farmerDetail;
        $gl_array['gl_detail'] = $globalArray;

        return $gl_array;
    }

    function fetch_double_entry_buyback_paymentVoucher($pvMasterAutoID, $code = null)
    {
        $gl_array = array();
        $inv_total = 0;
        $dr_total = 0;
        $party_total = 0;
        $companyLocal_total = 0;
        $companyReporting_total = 0;
        $tax_total = 0;
        $gl_array['gl_detail'] = array();
        $this->db->select('*');
        $this->db->where('pvMasterAutoID', $pvMasterAutoID);
        $master = $this->db->get('srp_erp_buyback_paymentvouchermaster')->row_array();


        $this->db->select('*');
        $this->db->where('farmID', $master['farmID']);
        $farmerDetail = $this->db->get('srp_erp_buyback_farmmaster')->row_array();

        $farmerGLMaster = $this->db->query("SELECT farmID,GLAutoID,systemAccountCode,GLSecondaryCode,GLDescription,subCategory,farmerCurrencyID FROM srp_erp_buyback_farmmaster fm LEFT JOIN(SELECT * FROM srp_erp_chartofaccounts) lb ON (fm.farmersLiabilityGLautoID = lb.GLAutoID) WHERE farmID = " . $master['farmID'] . " ")->row_array();

        $creditExGL = $this->db->query("SELECT PVD.pvMasterAutoID,sum(PVD.transactionAmount) AS creditTotal,PVD.segmentID,PVD.segmentCode,PVM.bankGLAutoID,PVM.bankSystemAccountCode,PVM.bankGLSecondaryCode,PVM.PVbankType,PVD.type AS paymentType,isMatching FROM srp_erp_buyback_paymentvoucherdetail PVD INNER JOIN srp_erp_buyback_paymentvouchermaster PVM ON PVM.pvMasterAutoID = PVD.pvMasterAutoID WHERE PVD.pvMasterAutoID = $pvMasterAutoID GROUP BY type")->result_array();

        $globalArray = array();
        if ($master['PVtype'] == 1) {

            /*creditGL*/
            if ($creditExGL) {
                foreach ($creditExGL as $creditGL) {

                    if ($creditGL['paymentType'] == 'Loan' && $creditGL['isMatching'] == 1) {
                        $data_arr['auto_id'] = $creditGL['pvMasterAutoID'];
                        $data_arr['gl_auto_id'] = $farmerGLMaster['GLAutoID'];
                        $data_arr['gl_code'] = $farmerGLMaster['systemAccountCode'];
                        $data_arr['secondary'] = $farmerGLMaster['GLSecondaryCode'];
                        $data_arr['gl_desc'] = $farmerGLMaster['GLDescription'];
                        $data_arr['gl_type'] = $farmerGLMaster['subCategory'];
                        $data_arr['segment_id'] = $creditGL['segmentID'];
                        $data_arr['segment'] = $creditGL['segmentCode'];
                        $data_arr['projectID'] = NULL;
                        $data_arr['projectExchangeRate'] = NULL;
                        $data_arr['isAddon'] = 0;
                        $data_arr['subLedgerType'] = 0;
                        $data_arr['subLedgerDesc'] = null;
                        $data_arr['partyContractID'] = null;
                        $data_arr['partyType'] = 'Farmer';
                        $data_arr['partyAutoID'] = $farmerDetail['farmID'];
                        $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
                        $data_arr['partyName'] = $farmerDetail['description'];
                        $data_arr['partyCurrencyID'] = $farmerDetail['farmerCurrencyID'];
                        $data_arr['partyCurrency'] = fetch_currency_code($farmerDetail['farmerCurrencyID']);
                        $data_arr['transactionExchangeRate'] = 1;
                        $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                        $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                        $data_arr['partyExchangeRate'] = null;
                        $data_arr['partyCurrencyAmount'] = 0;
                        $data_arr['partyCurrencyDecimalPlaces'] = null;
                        $data_arr['gl_dr'] = 0;
                        $data_arr['gl_cr'] = $creditGL['creditTotal'];
                        $data_arr['amount_type'] = 'cr';
                        $data_arr['payment_type'] = $creditGL['paymentType'];
                        array_push($globalArray, $data_arr);
                    } else {
                        $data_arr['auto_id'] = $creditGL['pvMasterAutoID'];
                        $data_arr['gl_auto_id'] = $creditGL['bankGLAutoID'];
                        $data_arr['gl_code'] = $creditGL['bankSystemAccountCode'];
                        $data_arr['secondary'] = $creditGL['bankGLSecondaryCode'];
                        $data_arr['gl_desc'] = $creditGL['bankGLSecondaryCode'];
                        $data_arr['gl_type'] = $creditGL['PVbankType'];
                        $data_arr['segment_id'] = $creditGL['segmentID'];
                        $data_arr['segment'] = $creditGL['segmentCode'];
                        $data_arr['projectID'] = NULL;
                        $data_arr['projectExchangeRate'] = NULL;
                        $data_arr['isAddon'] = 0;
                        $data_arr['subLedgerType'] = 0;
                        $data_arr['subLedgerDesc'] = null;
                        $data_arr['partyContractID'] = null;
                        $data_arr['partyType'] = 'Farmer';
                        $data_arr['partyAutoID'] = $farmerDetail['farmID'];
                        $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
                        $data_arr['partyName'] = $farmerDetail['description'];
                        $data_arr['partyCurrencyID'] = $farmerDetail['farmerCurrencyID'];
                        $data_arr['partyCurrency'] = fetch_currency_code($farmerDetail['farmerCurrencyID']);
                        $data_arr['transactionExchangeRate'] = 1;
                        $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                        $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                        $data_arr['partyExchangeRate'] = null;
                        $data_arr['partyCurrencyAmount'] = 0;
                        $data_arr['partyCurrencyDecimalPlaces'] = null;
                        $data_arr['gl_dr'] = 0;
                        $data_arr['gl_cr'] = $creditGL['creditTotal'];
                        $data_arr['amount_type'] = 'cr';
                        $data_arr['payment_type'] = $creditGL['paymentType'];
                        array_push($globalArray, $data_arr);
                    }

                }
            }

            /*Wages payable Expense*/
            $wagesExpense = $this->db->query("SELECT PVD.pvMasterAutoID,sum(PVD.transactionAmount) AS wagesExpenseTotal,PVD.segmentID,PVD.segmentCode,PVD.GLAutoID,PVD.GLCode,PVD.GLDescription,PVD.systemGLCode,PVD.GLType FROM srp_erp_buyback_paymentvoucherdetail PVD INNER JOIN srp_erp_buyback_paymentvouchermaster PVM ON PVM.pvMasterAutoID = PVD.pvMasterAutoID INNER JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = PVD.BatchID LEFT JOIN(SELECT * FROM srp_erp_chartofaccounts) dwp ON (batch.DirectWagesGLAutoID = dwp.GLAutoID) WHERE PVD.pvMasterAutoID = $pvMasterAutoID AND PVD.type = 'Expense' ")->row_array();

            //INNER JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = dpm.batchMasterID
            if (!empty($wagesExpense) && !empty($wagesExpense['segmentID'])) {
                $data_arr['auto_id'] = $wagesExpense['pvMasterAutoID'];
                $data_arr['gl_auto_id'] = $wagesExpense['GLAutoID'];
                $data_arr['gl_code'] = $wagesExpense['systemGLCode'];
                $data_arr['secondary'] = $wagesExpense['GLCode'];
                $data_arr['gl_desc'] = $wagesExpense['GLDescription'];
                $data_arr['gl_type'] = $wagesExpense['GLType'];
                $data_arr['segment_id'] = $wagesExpense['segmentID'];
                $data_arr['segment'] = $wagesExpense['segmentCode'];
                $data_arr['projectID'] = NULL;
                $data_arr['projectExchangeRate'] = NULL;
                $data_arr['isAddon'] = 0;
                $data_arr['subLedgerType'] = 0;
                $data_arr['subLedgerDesc'] = null;
                $data_arr['partyContractID'] = null;
                $data_arr['partyType'] = 'Farmer';
                $data_arr['partyAutoID'] = $farmerDetail['farmID'];
                $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
                $data_arr['partyName'] = $farmerDetail['description'];
                $data_arr['partyCurrencyID'] = $farmerDetail['farmerCurrencyID'];
                $data_arr['partyCurrency'] = fetch_currency_code($farmerDetail['farmerCurrencyID']);
                $data_arr['transactionExchangeRate'] = null;
                $data_arr['companyLocalExchangeRate'] = null;
                $data_arr['companyReportingExchangeRate'] = null;
                $data_arr['transactionExchangeRate'] = 1;
                $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                $data_arr['partyExchangeRate'] = null;
                $data_arr['partyCurrencyAmount'] = 0;
                $data_arr['partyCurrencyDecimalPlaces'] = null;
                $data_arr['gl_dr'] = $wagesExpense['wagesExpenseTotal'];
                $data_arr['gl_cr'] = '';
                $data_arr['amount_type'] = 'dr';
                $data_arr['payment_type'] = 'Expense';

                array_push($globalArray, $data_arr);
            }

            /*Wages payable Advance*/
            /*$wagesAdvance = $this->db->query("SELECT PVD.pvMasterAutoID,sum(PVD.transactionAmount) AS wagesAdvanceTotal,PVD.segmentID,PVD.segmentCode,dwp.GLAutoID as DWGLAutoID,dwp.GLSecondaryCode as DWGLSecondaryCode,dwp.GLDescription as DWGLDescription,dwp.systemAccountCode as DWsystemAccountCode,dwp.subCategory AS DWsubCategory FROM srp_erp_buyback_paymentvoucherdetail PVD LEFT JOIN srp_erp_buyback_paymentvouchermaster PVM ON PVM.pvMasterAutoID = PVD.pvMasterAutoID LEFT JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = PVD.BatchID LEFT JOIN(SELECT * FROM srp_erp_chartofaccounts) dwp ON (batch.DirectWagesGLAutoID = dwp.GLAutoID) WHERE PVD.pvMasterAutoID = $pvMasterAutoID AND PVD.type = 'Advance' ")->row_array();

            if (!empty($wagesAdvance) && !empty($wagesAdvance['segmentID'])) {

                $data_arr['auto_id'] = $wagesAdvance['pvMasterAutoID'];
                $data_arr['gl_auto_id'] = $wagesAdvance['DWGLAutoID'];
                $data_arr['gl_code'] = $wagesAdvance['DWsystemAccountCode'];
                $data_arr['secondary'] = $wagesAdvance['DWGLSecondaryCode'];
                $data_arr['gl_desc'] = $wagesAdvance['DWGLDescription'];
                $data_arr['gl_type'] = $wagesAdvance['DWsubCategory'];
                $data_arr['segment_id'] = $wagesAdvance['segmentID'];
                $data_arr['segment'] = $wagesAdvance['segmentCode'];
                $data_arr['projectID'] = NULL;
                $data_arr['projectExchangeRate'] = NULL;
                $data_arr['isAddon'] = 0;
                $data_arr['subLedgerType'] = 0;
                $data_arr['subLedgerDesc'] = null;
                $data_arr['partyContractID'] = null;
                $data_arr['partyType'] = 'Farmer';
                $data_arr['partyAutoID'] = $farmerDetail['farmID'];
                $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
                $data_arr['partyName'] = $farmerDetail['description'];
                $data_arr['partyCurrencyID'] = $farmerDetail['farmerCurrencyID'];
                $data_arr['partyCurrency'] = fetch_currency_code($farmerDetail['farmerCurrencyID']);
                $data_arr['transactionExchangeRate'] = null;
                $data_arr['companyLocalExchangeRate'] = null;
                $data_arr['companyReportingExchangeRate'] = null;
                $data_arr['transactionExchangeRate'] = 1;
                $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                $data_arr['partyExchangeRate'] = null;
                $data_arr['partyCurrencyAmount'] = 0;
                $data_arr['partyCurrencyDecimalPlaces'] = null;
                $data_arr['gl_dr'] = $wagesAdvance['wagesAdvanceTotal'];
                $data_arr['gl_cr'] = '';
                $data_arr['amount_type'] = 'dr';
                $data_arr['payment_type'] = 'Advance';

                array_push($globalArray, $data_arr);

            }*/

            /*Wages payable Loan*/
            $wagesLoan = $this->db->query("SELECT PVD.pvMasterAutoID,sum(PVD.transactionAmount) AS wagesLoanTotal,PVD.segmentID,PVD.segmentCode,dwp.GLAutoID as DWGLAutoID,dwp.GLSecondaryCode as DWGLSecondaryCode,dwp.GLDescription as DWGLDescription,dwp.systemAccountCode as DWsystemAccountCode,dwp.subCategory AS DWsubCategory FROM srp_erp_buyback_paymentvoucherdetail PVD LEFT JOIN srp_erp_buyback_paymentvouchermaster PVM ON PVM.pvMasterAutoID = PVD.pvMasterAutoID LEFT JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = PVD.BatchID LEFT JOIN(SELECT * FROM srp_erp_chartofaccounts) dwp ON (batch.DirectWagesGLAutoID = dwp.GLAutoID) WHERE PVD.pvMasterAutoID = $pvMasterAutoID AND  (PVD.type = 'Loan' or PVD.type = 'Advance' or PVD.type = 'Batch') ")->row_array();

            if (!empty($wagesLoan)) {

                $data_arr['auto_id'] = $wagesLoan['pvMasterAutoID'];
                $data_arr['gl_auto_id'] = $farmerGLMaster['GLAutoID'];
                $data_arr['gl_code'] = $farmerGLMaster['systemAccountCode'];
                $data_arr['secondary'] = $farmerGLMaster['GLSecondaryCode'];
                $data_arr['gl_desc'] = $farmerGLMaster['GLDescription'];
                $data_arr['gl_type'] = $farmerGLMaster['subCategory'];
                $data_arr['segment_id'] = $wagesLoan['segmentID'];
                $data_arr['segment'] = $wagesLoan['segmentCode'];
                $data_arr['projectID'] = NULL;
                $data_arr['projectExchangeRate'] = NULL;
                $data_arr['isAddon'] = 0;
                $data_arr['subLedgerType'] = 0;
                $data_arr['subLedgerDesc'] = null;
                $data_arr['partyContractID'] = null;
                $data_arr['partyType'] = 'Farmer';
                $data_arr['partyAutoID'] = $farmerDetail['farmID'];
                $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
                $data_arr['partyName'] = $farmerDetail['description'];
                $data_arr['partyCurrencyID'] = $farmerDetail['farmerCurrencyID'];
                $data_arr['partyCurrency'] = fetch_currency_code($farmerDetail['farmerCurrencyID']);
                $data_arr['transactionExchangeRate'] = null;
                $data_arr['companyLocalExchangeRate'] = null;
                $data_arr['companyReportingExchangeRate'] = null;
                $data_arr['transactionExchangeRate'] = 1;
                $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                $data_arr['partyExchangeRate'] = null;
                $data_arr['partyCurrencyAmount'] = 0;
                $data_arr['partyCurrencyDecimalPlaces'] = null;
                $data_arr['gl_dr'] = $wagesLoan['wagesLoanTotal'];
                $data_arr['gl_cr'] = '';
                $data_arr['amount_type'] = 'dr';
                $data_arr['payment_type'] = 'Loan';

                array_push($globalArray, $data_arr);

            }

        } else if ($master['PVtype'] == 3) {

            if ($creditExGL) {
                foreach ($creditExGL as $creditGL) {
                    $data_arr['auto_id'] = $creditGL['pvMasterAutoID'];
                    $data_arr['gl_auto_id'] = $farmerGLMaster['GLAutoID'];
                    $data_arr['gl_code'] = $farmerGLMaster['systemAccountCode'];
                    $data_arr['secondary'] = $farmerGLMaster['GLSecondaryCode'];
                    $data_arr['gl_desc'] = $farmerGLMaster['GLDescription'];
                    $data_arr['gl_type'] = $farmerGLMaster['subCategory'];
                    $data_arr['segment_id'] = $creditGL['segmentID'];
                    $data_arr['segment'] = $creditGL['segmentCode'];
                    $data_arr['projectID'] = NULL;
                    $data_arr['projectExchangeRate'] = NULL;
                    $data_arr['isAddon'] = 0;
                    $data_arr['subLedgerType'] = 0;
                    $data_arr['subLedgerDesc'] = null;
                    $data_arr['partyContractID'] = null;
                    $data_arr['partyType'] = 'Farmer';
                    $data_arr['partyAutoID'] = $farmerDetail['farmID'];
                    $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
                    $data_arr['partyName'] = $farmerDetail['description'];
                    $data_arr['partyCurrencyID'] = $farmerDetail['farmerCurrencyID'];
                    $data_arr['partyCurrency'] = fetch_currency_code($farmerDetail['farmerCurrencyID']);
                    $data_arr['transactionExchangeRate'] = null;
                    $data_arr['companyLocalExchangeRate'] = null;
                    $data_arr['companyReportingExchangeRate'] = null;
                    $data_arr['transactionExchangeRate'] = 1;
                    $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                    $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                    $data_arr['partyExchangeRate'] = null;
                    $data_arr['partyCurrencyAmount'] = 0;
                    $data_arr['partyCurrencyDecimalPlaces'] = null;
                    $data_arr['gl_dr'] = 0;
                    $data_arr['gl_cr'] = $creditGL['creditTotal'];
                    $data_arr['amount_type'] = 'cr';
                    $data_arr['payment_type'] = $creditGL['paymentType'];
                    array_push($globalArray, $data_arr);

                    $data_arr['auto_id'] = $creditGL['pvMasterAutoID'];
                    $data_arr['gl_auto_id'] = $farmerGLMaster['GLAutoID'];
                    $data_arr['gl_code'] = $farmerGLMaster['systemAccountCode'];
                    $data_arr['secondary'] = $farmerGLMaster['GLSecondaryCode'];
                    $data_arr['gl_desc'] = $farmerGLMaster['GLDescription'];
                    $data_arr['gl_type'] = $farmerGLMaster['subCategory'];
                    $data_arr['segment_id'] = $creditGL['segmentID'];
                    $data_arr['segment'] = $creditGL['segmentCode'];
                    $data_arr['projectID'] = NULL;
                    $data_arr['projectExchangeRate'] = NULL;
                    $data_arr['isAddon'] = 0;
                    $data_arr['subLedgerType'] = 0;
                    $data_arr['subLedgerDesc'] = null;
                    $data_arr['partyContractID'] = null;
                    $data_arr['partyType'] = 'Farmer';
                    $data_arr['partyAutoID'] = $farmerDetail['farmID'];
                    $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
                    $data_arr['partyName'] = $farmerDetail['description'];
                    $data_arr['partyCurrencyID'] = $farmerDetail['farmerCurrencyID'];
                    $data_arr['partyCurrency'] = fetch_currency_code($farmerDetail['farmerCurrencyID']);
                    $data_arr['transactionExchangeRate'] = null;
                    $data_arr['companyLocalExchangeRate'] = null;
                    $data_arr['companyReportingExchangeRate'] = null;
                    $data_arr['transactionExchangeRate'] = 1;
                    $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                    $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                    $data_arr['partyExchangeRate'] = null;
                    $data_arr['partyCurrencyAmount'] = 0;
                    $data_arr['partyCurrencyDecimalPlaces'] = null;
                    $data_arr['gl_dr'] = $creditGL['creditTotal'];
                    $data_arr['gl_cr'] = 0;
                    $data_arr['amount_type'] = 'dr';
                    $data_arr['payment_type'] = $creditGL['paymentType'];
                    array_push($globalArray, $data_arr);
                }
            }
        } else if ($master['PVtype'] == 2) {

            $wagesIncome = $this->db->query("SELECT PVD.pvMasterAutoID,sum(PVD.transactionAmount) AS debitTotal,PVD.segmentID,PVD.segmentCode,PVM.bankGLAutoID,PVM.bankSystemAccountCode,PVM.bankGLSecondaryCode,PVM.PVbankType,PVD.type AS paymentType,isMatching FROM srp_erp_buyback_paymentvoucherdetail PVD INNER JOIN srp_erp_buyback_paymentvouchermaster PVM ON PVM.pvMasterAutoID = PVD.pvMasterAutoID WHERE PVD.pvMasterAutoID = $pvMasterAutoID ")->row_array();

            $data_arr['auto_id'] = $pvMasterAutoID;
            $data_arr['gl_auto_id'] = $wagesIncome['bankGLAutoID'];
            $data_arr['gl_code'] = $wagesIncome['bankSystemAccountCode'];
            $data_arr['secondary'] = $wagesIncome['bankGLSecondaryCode'];
            $data_arr['gl_desc'] = $wagesIncome['bankGLSecondaryCode'];
            $data_arr['gl_type'] = $wagesIncome['PVbankType'];
            $data_arr['segment_id'] = $wagesIncome['segmentID'];
            $data_arr['segment'] = $wagesIncome['segmentCode'];
            $data_arr['projectID'] = NULL;
            $data_arr['projectExchangeRate'] = NULL;
            $data_arr['isAddon'] = 0;
            $data_arr['subLedgerType'] = 0;
            $data_arr['subLedgerDesc'] = null;
            $data_arr['partyContractID'] = null;
            $data_arr['partyType'] = 'Farmer';
            $data_arr['partyAutoID'] = $farmerDetail['farmID'];
            $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
            $data_arr['partyName'] = $farmerDetail['description'];
            $data_arr['partyCurrencyID'] = $farmerDetail['farmerCurrencyID'];
            $data_arr['partyCurrency'] = fetch_currency_code($farmerDetail['farmerCurrencyID']);
            $data_arr['transactionExchangeRate'] = 1;
            $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
            $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
            $data_arr['partyExchangeRate'] = null;
            $data_arr['partyCurrencyAmount'] = 0;
            $data_arr['partyCurrencyDecimalPlaces'] = null;
            $data_arr['gl_dr'] = $wagesIncome['debitTotal'];
            $data_arr['gl_cr'] = '';
            $data_arr['amount_type'] = 'dr';
            $data_arr['payment_type'] = $wagesIncome['paymentType'];
            array_push($globalArray, $data_arr);


            $data_arr['auto_id'] = '';
            $data_arr['gl_auto_id'] = $farmerGLMaster['GLAutoID'];
            $data_arr['gl_code'] = $farmerGLMaster['systemAccountCode'];
            $data_arr['secondary'] = $farmerGLMaster['GLSecondaryCode'];
            $data_arr['gl_desc'] = $farmerGLMaster['GLDescription'];
            $data_arr['gl_type'] = $farmerGLMaster['subCategory'];
            $data_arr['segment_id'] = NULL;
            $data_arr['segment'] = NULL;
            $data_arr['projectID'] = NULL;
            $data_arr['projectExchangeRate'] = NULL;
            $data_arr['isAddon'] = 0;
            $data_arr['subLedgerType'] = 0;
            $data_arr['subLedgerDesc'] = null;
            $data_arr['partyContractID'] = null;
            $data_arr['partyType'] = 'Farmer';
            $data_arr['partyAutoID'] = $farmerDetail['farmID'];
            $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
            $data_arr['partyName'] = $farmerDetail['description'];
            $data_arr['partyCurrencyID'] = $farmerDetail['farmerCurrencyID'];
            $data_arr['partyCurrency'] = fetch_currency_code($farmerDetail['farmerCurrencyID']);
            $data_arr['transactionExchangeRate'] = 1;
            $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
            $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
            $data_arr['partyExchangeRate'] = null;
            $data_arr['partyCurrencyAmount'] = 0;
            $data_arr['partyCurrencyDecimalPlaces'] = null;
            $data_arr['gl_dr'] = 0;
            $data_arr['gl_cr'] = $wagesIncome['debitTotal'];
            $data_arr['amount_type'] = 'cr';
            $data_arr['payment_type'] = $wagesIncome['paymentType'];
            array_push($globalArray, $data_arr);
        }


        $gl_array['currency'] = $master['transactionCurrency'];
        $gl_array['decimal_places'] = $master['transactionCurrencyDecimalPlaces'];
        $gl_array['code'] = $master['documentID'];
        $gl_array['name'] = 'Voucher';
        $gl_array['primary_Code'] = $master['documentSystemCode'];
        $gl_array['date'] = $master['documentDate'];
        $gl_array['finance_year'] = $master['companyFinanceYear'];
        $gl_array['finance_period'] = $master['FYPeriodDateFrom'] . ' - ' . $master['FYPeriodDateTo'];
        $gl_array['master_data'] = $master;
        $gl_array['farmer'] = $farmerDetail;
        $gl_array['gl_detail'] = $globalArray;

        return $gl_array;
    }

    function fetch_double_entry_buyback_goodReceiptNote($grnAutoID, $code = null)
    {
        $gl_array = array();
        $inv_total = 0;
        $dr_total = 0;
        $party_total = 0;
        $companyLocal_total = 0;
        $companyReporting_total = 0;
        $tax_total = 0;
        $gl_array['gl_detail'] = array();
        $this->db->select('*');
        $this->db->where('grnAutoID', $grnAutoID);
        $master = $this->db->get('srp_erp_buyback_grn')->row_array();

        $this->db->select('*');
        $this->db->where('farmID', $master['farmID']);
        $farmerDetail = $this->db->get('srp_erp_buyback_farmmaster')->row_array();

        $debitGL = $this->db->query("SELECT grnm.grnAutoID,sum(grnd.totalCost) AS debitTotal,assetGLAutoID,assetSystemGLCode,assetGLCode,assetGLDescription,assetGLType FROM srp_erp_buyback_grndetails grnd INNER JOIN srp_erp_buyback_grn grnm ON grnm.grnAutoID = grnd.grnAutoID WHERE grnm.grnAutoID = $grnAutoID GROUP BY assetGLAutoID")->row_array();

        $globalArray = array();
        /*creditGL*/
        if ($debitGL) {

            $data_arr['auto_id'] = $debitGL['grnAutoID'];
            $data_arr['gl_auto_id'] = $debitGL['assetGLAutoID'];
            $data_arr['gl_code'] = $debitGL['assetSystemGLCode'];
            $data_arr['secondary'] = $debitGL['assetGLCode'];
            $data_arr['gl_desc'] = $debitGL['assetGLDescription'];
            $data_arr['gl_type'] = $debitGL['assetGLType'];
            $data_arr['segment_id'] = NULL;
            $data_arr['segment'] = NULL;
            $data_arr['projectID'] = NULL;
            $data_arr['projectExchangeRate'] = NULL;
            $data_arr['isAddon'] = 0;
            $data_arr['subLedgerType'] = 0;
            $data_arr['subLedgerDesc'] = null;
            $data_arr['partyContractID'] = null;
            $data_arr['partyType'] = 'Farmer';
            $data_arr['partyAutoID'] = $farmerDetail['farmID'];
            $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
            $data_arr['partyName'] = $farmerDetail['description'];
            $data_arr['partyCurrencyID'] = $farmerDetail['farmerCurrencyID'];
            $data_arr['partyCurrency'] = fetch_currency_code($farmerDetail['farmerCurrencyID']);
            $data_arr['transactionExchangeRate'] = $master['transactionExchangeRate'];
            $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
            $data_arr['transactionExchangeRate'] = 1;
            $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
            $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
            $data_arr['partyExchangeRate'] = null;
            $data_arr['partyCurrencyAmount'] = 0;
            $data_arr['partyCurrencyDecimalPlaces'] = null;
            $data_arr['gl_dr'] = $debitGL['debitTotal'];
            $data_arr['gl_cr'] = 0;
            $data_arr['amount_type'] = 'dr';
            array_push($globalArray, $data_arr);
        }

        $workinProgress = $this->db->query("SELECT grnm.grnAutoID,sum(grnd.totalCost) AS creditTotal,GLAutoID,systemAccountCode,GLSecondaryCode,GLDescription,subCategory FROM srp_erp_buyback_grndetails grnd INNER JOIN srp_erp_buyback_grn grnm ON grnm.grnAutoID = grnd.grnAutoID INNER JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = grnm.batchMasterID LEFT JOIN(SELECT * FROM srp_erp_chartofaccounts) dwp ON (batch.WIPGLAutoID = dwp.GLAutoID) WHERE grnm.grnAutoID = $grnAutoID ")->row_array();

        if (!empty($workinProgress)) {
            $data_arr['auto_id'] = $workinProgress['grnAutoID'];
            $data_arr['gl_auto_id'] = $workinProgress['GLAutoID'];
            $data_arr['gl_code'] = $workinProgress['systemAccountCode'];
            $data_arr['secondary'] = $workinProgress['GLSecondaryCode'];
            $data_arr['gl_desc'] = $workinProgress['GLDescription'];
            $data_arr['gl_type'] = $workinProgress['subCategory'];
            $data_arr['segment_id'] = NULL;
            $data_arr['segment'] = NULL;
            $data_arr['projectID'] = NULL;
            $data_arr['projectExchangeRate'] = NULL;
            $data_arr['isAddon'] = 0;
            $data_arr['subLedgerType'] = 0;
            $data_arr['subLedgerDesc'] = null;
            $data_arr['partyContractID'] = null;
            $data_arr['partyType'] = 'Farmer';
            $data_arr['partyAutoID'] = $farmerDetail['farmID'];
            $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
            $data_arr['partyName'] = $farmerDetail['description'];
            $data_arr['partyCurrencyID'] = $farmerDetail['farmerCurrencyID'];
            $data_arr['partyCurrency'] = fetch_currency_code($farmerDetail['farmerCurrencyID']);
            $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
            $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
            $data_arr['transactionExchangeRate'] = 1;
            $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
            $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
            $data_arr['partyExchangeRate'] = null;
            $data_arr['partyCurrencyAmount'] = 0;
            $data_arr['partyCurrencyDecimalPlaces'] = null;
            $data_arr['gl_dr'] = 0;
            $data_arr['gl_cr'] = $workinProgress['creditTotal'];
            $data_arr['amount_type'] = 'cr';

            array_push($globalArray, $data_arr);
        }

        $gl_array['currency'] = $master['transactionCurrency'];
        $gl_array['decimal_places'] = $master['transactionCurrencyDecimalPlaces'];
        $gl_array['code'] = 'BBGRN';
        $gl_array['name'] = 'Good Receipt Note';
        $gl_array['primary_Code'] = $master['documentSystemCode'];
        $gl_array['date'] = $master['documentDate'];
        $gl_array['finance_year'] = $master['companyFinanceYear'];
        $gl_array['finance_period'] = $master['FYPeriodDateFrom'] . ' - ' . $master['FYPeriodDateTo'];
        $gl_array['master_data'] = $master;
        $gl_array['farmer'] = $farmerDetail;
        $gl_array['gl_detail'] = $globalArray;

        return $gl_array;
    }


    function fetch_double_entry_buyback_batchClosing($batchMasterID, $code = null)
    {
        $gl_array = array();
        $convertFormat = convert_date_format_sql();
        $companyID = current_companyID();

        $gl_array['gl_detail'] = array();

        $master = $this->db->query("SELECT batch.*,GLAutoID,systemAccountCode,GLSecondaryCode,GLDescription,subCategory FROM srp_erp_buyback_batch batch LEFT JOIN(SELECT * FROM srp_erp_chartofaccounts) dwp ON (batch.DirectWagesGLAutoID = dwp.GLAutoID) WHERE batch.batchMasterID = $batchMasterID ")->row_array();

        $farmerMaster = $this->db->query("SELECT farmID,GLAutoID,systemAccountCode,GLSecondaryCode,GLDescription,subCategory,farmerCurrencyID FROM srp_erp_buyback_farmmaster fm LEFT JOIN(SELECT * FROM srp_erp_chartofaccounts) lb ON (fm.farmersLiabilityGLautoID = lb.GLAutoID) WHERE farmID = " . $master['farmID'] . " ")->row_array();

        $this->db->select('*');
        $this->db->where('farmID', $master['farmID']);
        $farmerDetail = $this->db->get('srp_erp_buyback_farmmaster')->row_array();

        $dispatch = $this->db->query("SELECT SUM(totalTransferAmountTransaction) as dispatchTotal FROM srp_erp_buyback_itemledger WHERE batchID = $batchMasterID AND companyID = $companyID AND documentCode = 'BBDPN'")->row_array();

        $expense = $this->db->query("SELECT SUM(pvd.transactionAmount) as expenseTotal FROM srp_erp_buyback_paymentvoucherdetail pvd
LEFT JOIN srp_erp_buyback_paymentvouchermaster pvm ON pvd.pvMasterAutoID = pvm.pvMasterAutoID WHERE
	`pvd`.`BatchID` = $batchMasterID
AND `pvd`.`companyID` = $companyID
AND `pvd`.`type` = 'Expense'
AND `pvm`.`approvedYN` = 1")->row_array();

        $grnTotal = $this->db->query("SELECT
	SUM(totalTransferAmountTransaction) as grnTotal
FROM
	`srp_erp_buyback_itemledger`
WHERE
	`batchID` = $batchMasterID
AND `companyID` = $companyID
AND `documentCode` = 'BBGRN'")->row_array();
        $totalExpense = $dispatch['dispatchTotal'] + $expense['expenseTotal'];
        $totalWages = $grnTotal['grnTotal'] - $totalExpense;

        $globalArray = array();
        /*creditGL*/
        if ($totalWages > 0) {

            $data_arr['auto_id'] = $batchMasterID;
            $data_arr['gl_auto_id'] = $master['GLAutoID'];
            $data_arr['gl_code'] = $master['systemAccountCode'];
            $data_arr['secondary'] = $master['GLSecondaryCode'];
            $data_arr['gl_desc'] = $master['GLDescription'];
            $data_arr['gl_type'] = $master['subCategory'];
            $data_arr['segment_id'] = NULL;
            $data_arr['segment'] = NULL;
            $data_arr['projectID'] = NULL;
            $data_arr['projectExchangeRate'] = NULL;
            $data_arr['isAddon'] = 0;
            $data_arr['subLedgerType'] = 0;
            $data_arr['subLedgerDesc'] = null;
            $data_arr['partyContractID'] = null;
            $data_arr['partyType'] = 'Farmer';
            $data_arr['partyAutoID'] = $farmerDetail['farmID'];
            $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
            $data_arr['partyName'] = $farmerDetail['description'];
            $data_arr['partyCurrencyID'] = $farmerDetail['farmerCurrencyID'];
            $data_arr['partyCurrency'] = fetch_currency_code($farmerDetail['farmerCurrencyID']);
            $data_arr['transactionExchangeRate'] = NULL;
            $data_arr['companyReportingExchangeRate'] = NULL;
            $data_arr['transactionExchangeRate'] = 1;
            $data_arr['companyLocalExchangeRate'] = NULL;
            $data_arr['companyReportingExchangeRate'] = NULL;
            $data_arr['partyExchangeRate'] = null;
            $data_arr['partyCurrencyAmount'] = 0;
            $data_arr['partyCurrencyDecimalPlaces'] = null;
            $data_arr['gl_dr'] = $totalWages;
            $data_arr['gl_cr'] = 0;
            $data_arr['amount_type'] = 'dr';
            array_push($globalArray, $data_arr);

            $data_arr['auto_id'] = $batchMasterID;
            $data_arr['gl_auto_id'] = $farmerMaster['GLAutoID'];
            $data_arr['gl_code'] = $farmerMaster['systemAccountCode'];
            $data_arr['secondary'] = $farmerMaster['GLSecondaryCode'];
            $data_arr['gl_desc'] = $farmerMaster['GLDescription'];
            $data_arr['gl_type'] = $farmerMaster['subCategory'];
            $data_arr['segment_id'] = NULL;
            $data_arr['segment'] = NULL;
            $data_arr['projectID'] = NULL;
            $data_arr['projectExchangeRate'] = NULL;
            $data_arr['isAddon'] = 0;
            $data_arr['subLedgerType'] = 0;
            $data_arr['subLedgerDesc'] = null;
            $data_arr['partyContractID'] = null;
            $data_arr['partyType'] = 'Farmer';
            $data_arr['partyAutoID'] = $farmerDetail['farmID'];
            $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
            $data_arr['partyName'] = $farmerDetail['description'];
            $data_arr['partyCurrencyID'] = $farmerDetail['farmerCurrencyID'];
            $data_arr['partyCurrency'] = fetch_currency_code($farmerDetail['farmerCurrencyID']);
            $data_arr['transactionExchangeRate'] = NULL;
            $data_arr['companyReportingExchangeRate'] = NULL;
            $data_arr['transactionExchangeRate'] = 1;
            $data_arr['companyLocalExchangeRate'] = NULL;
            $data_arr['companyReportingExchangeRate'] = NULL;
            $data_arr['partyExchangeRate'] = null;
            $data_arr['partyCurrencyAmount'] = 0;
            $data_arr['partyCurrencyDecimalPlaces'] = null;
            $data_arr['gl_dr'] = 0;
            $data_arr['gl_cr'] = $totalWages;
            $data_arr['amount_type'] = 'cr';
            array_push($globalArray, $data_arr);
        } else {
            $data_arr['auto_id'] = $batchMasterID;
            $data_arr['gl_auto_id'] = $farmerMaster['GLAutoID'];
            $data_arr['gl_code'] = $farmerMaster['systemAccountCode'];
            $data_arr['secondary'] = $farmerMaster['GLSecondaryCode'];
            $data_arr['gl_desc'] = $farmerMaster['GLDescription'];
            $data_arr['gl_type'] = $farmerMaster['subCategory'];
            $data_arr['segment_id'] = NULL;
            $data_arr['segment'] = NULL;
            $data_arr['projectID'] = NULL;
            $data_arr['projectExchangeRate'] = NULL;
            $data_arr['isAddon'] = 0;
            $data_arr['subLedgerType'] = 0;
            $data_arr['subLedgerDesc'] = null;
            $data_arr['partyContractID'] = null;
            $data_arr['partyType'] = 'Farmer';
            $data_arr['partyAutoID'] = $farmerDetail['farmID'];
            $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
            $data_arr['partyName'] = $farmerDetail['description'];
            $data_arr['partyCurrencyID'] = $farmerDetail['farmerCurrencyID'];
            $data_arr['partyCurrency'] = fetch_currency_code($farmerDetail['farmerCurrencyID']);
            $data_arr['transactionExchangeRate'] = NULL;
            $data_arr['companyReportingExchangeRate'] = NULL;
            $data_arr['transactionExchangeRate'] = 1;
            $data_arr['companyLocalExchangeRate'] = NULL;
            $data_arr['companyReportingExchangeRate'] = NULL;
            $data_arr['partyExchangeRate'] = null;
            $data_arr['partyCurrencyAmount'] = 0;
            $data_arr['partyCurrencyDecimalPlaces'] = null;
            $data_arr['gl_dr'] = $totalWages;
            $data_arr['gl_cr'] = 0;
            $data_arr['amount_type'] = 'dr';
            array_push($globalArray, $data_arr);

            $data_arr['auto_id'] = $batchMasterID;
            $data_arr['gl_auto_id'] = $master['GLAutoID'];
            $data_arr['gl_code'] = $master['systemAccountCode'];
            $data_arr['secondary'] = $master['GLSecondaryCode'];
            $data_arr['gl_desc'] = $master['GLDescription'];
            $data_arr['gl_type'] = $master['subCategory'];
            $data_arr['segment_id'] = NULL;
            $data_arr['segment'] = NULL;
            $data_arr['projectID'] = NULL;
            $data_arr['projectExchangeRate'] = NULL;
            $data_arr['isAddon'] = 0;
            $data_arr['subLedgerType'] = 0;
            $data_arr['subLedgerDesc'] = null;
            $data_arr['partyContractID'] = null;
            $data_arr['partyType'] = 'Farmer';
            $data_arr['partyAutoID'] = $farmerDetail['farmID'];
            $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
            $data_arr['partyName'] = $farmerDetail['description'];
            $data_arr['partyCurrencyID'] = $farmerDetail['farmerCurrencyID'];
            $data_arr['partyCurrency'] = fetch_currency_code($farmerDetail['farmerCurrencyID']);
            $data_arr['transactionExchangeRate'] = NULL;
            $data_arr['companyReportingExchangeRate'] = NULL;
            $data_arr['transactionExchangeRate'] = 1;
            $data_arr['companyLocalExchangeRate'] = NULL;
            $data_arr['companyReportingExchangeRate'] = NULL;
            $data_arr['partyExchangeRate'] = null;
            $data_arr['partyCurrencyAmount'] = 0;
            $data_arr['partyCurrencyDecimalPlaces'] = null;
            $data_arr['gl_dr'] = 0;
            $data_arr['gl_cr'] = $totalWages;
            $data_arr['amount_type'] = 'cr';
            array_push($globalArray, $data_arr);
        }

        $gl_array['currency'] = NULL;
        $gl_array['decimal_places'] = NULL;
        $gl_array['code'] = 'BBBC';
        $gl_array['name'] = 'Batch Closing';
        $gl_array['primary_Code'] = $master['batchCode'];
        $gl_array['date'] = NULL;
        $gl_array['finance_year'] = NULL;
        $gl_array['finance_period'] = NULL;
        $gl_array['master_data'] = $master;
        $gl_array['farmer'] = $farmerDetail;
        $gl_array['gl_detail'] = $globalArray;

        return $gl_array;
    }

    function save_dispatchNote_approval()
    {

        $this->db->trans_start();
        $this->load->library('approvals');
        $system_id = trim($this->input->post('dispatchAutoID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('po_status'));
        $comments = trim($this->input->post('comments'));
        $approvals_status = $this->approvals->approve_document($system_id, $level_id, $status, $comments, 'BBDPN');

        if ($approvals_status == 1) {
            $master = $this->db->query("select * from srp_erp_buyback_dispatchnote WHERE dispatchAutoID={$system_id} ")->row_array();

            /*            $erp_item_detail = $this->db->query("select dpd.*,dpd.dispatchAutoID,dpd.itemAutoID,conversionRateUOM,itemSystemCode,itemDescription, defaultUOMID,defaultUOM,unitOfMeasureID,unitOfMeasure,sum(qty) as qty,sum(totalActualCost) as transactionAmount,sum(totalActualCostLocal)as companyLocalAmount,sum(totalActualCostReporting) as companyReportingAmount,wareHouseAutoID,wareHouseCode,wareHouseLocation,wareHouseDescription FROM srp_erp_buyback_dispatchnotedetails dpd INNER JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID = dpd.dispatchAutoID WHERE dpd.dispatchAutoID = {$system_id} GROUP BY itemAutoID,buybackItemType")->result_array();*/

            $erp_item_detail = $this->db->query("SELECT dpd.dispatchAutoID,dpd.itemAutoID,conversionRateUOM,itemSystemCode,itemDescription,defaultUOMID,defaultUOM,unitOfMeasureID,unitOfMeasure,sum(qty) AS qty,sum(totalTransferCost) AS transactionAmount,sum(totalTransferCostLocal) AS companyLocalAmount,sum(totalTransferCostReporting) AS companyReportingAmount,wareHouseAutoID,wareHouseCode,wareHouseLocation,wareHouseDescription FROM srp_erp_buyback_dispatchnotedetails dpd INNER JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID = dpd.dispatchAutoID WHERE dpd.dispatchAutoID = {$system_id} GROUP BY itemAutoID")->result_array();

            $erp_itemDetail_bb_ledger = $this->db->query("SELECT dpd.*,dpd.dispatchAutoID,dpd.itemAutoID,conversionRateUOM,itemSystemCode,itemDescription,defaultUOMID,defaultUOM,unitOfMeasureID,unitOfMeasure,wareHouseAutoID,wareHouseCode,wareHouseLocation,wareHouseDescription FROM srp_erp_buyback_dispatchnotedetails dpd INNER JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID = dpd.dispatchAutoID WHERE dpd.dispatchAutoID = {$system_id} ORDER BY buybackItemType")->result_array();

            for ($a = 0; $a < count($erp_itemDetail_bb_ledger); $a++) {
                $item = fetch_item_data($erp_itemDetail_bb_ledger[$a]['itemAutoID']);
                if ($item['mainCategory'] == 'Inventory' or $item['mainCategory'] == 'Non Inventory') {

                    $itemAutoID = $erp_itemDetail_bb_ledger[$a]['itemAutoID'];
                    $qty = $erp_itemDetail_bb_ledger[$a]['qty'] / $erp_itemDetail_bb_ledger[$a]['conversionRateUOM'];
                    $wareHouseAutoID = $erp_itemDetail_bb_ledger[$a]['wareHouseAutoID'];

                    $item_arr[$a]['itemAutoID'] = $erp_itemDetail_bb_ledger[$a]['itemAutoID'];
                    $item_arr[$a]['currentStock'] = ($item['currentStock'] - $qty);
                    $item_arr[$a]['companyLocalWacAmount'] = round(((($item['currentStock'] * $item['companyLocalWacAmount']) - ($item['companyLocalWacAmount'] * $qty)) / $item_arr[$a]['currentStock']), $master['companyLocalCurrencyDecimalPlaces']);
                    $item_arr[$a]['companyReportingWacAmount'] = round(((($item['currentStock'] * $item['companyReportingWacAmount']) - ($item['companyReportingWacAmount'] * $qty)) / $item_arr[$a]['currentStock']), $master['companyReportingCurrencyDecimalPlaces']);

                    $itemledger_arr_buyback[$a]['documentCode'] = $master['documentID'];
                    $itemledger_arr_buyback[$a]['documentAutoID'] = $master['dispatchAutoID'];
                    $itemledger_arr_buyback[$a]['documentSystemCode'] = $master['documentSystemCode'];
                    $itemledger_arr_buyback[$a]['documentDate'] = $master['documentDate'];
                    $itemledger_arr_buyback[$a]['batchID'] = $master['batchMasterID'];
                    $itemledger_arr_buyback[$a]['farmID'] = $master['farmID'];
                    $itemledger_arr_buyback[$a]['referenceNumber'] = $master['referenceNo'];
                    $itemledger_arr_buyback[$a]['companyFinanceYearID'] = $master['companyFinanceYearID'];
                    $itemledger_arr_buyback[$a]['companyFinanceYear'] = $master['companyFinanceYear'];
                    $itemledger_arr_buyback[$a]['FYBegin'] = $master['FYBegin'];
                    $itemledger_arr_buyback[$a]['FYEnd'] = $master['FYEnd'];
                    $itemledger_arr_buyback[$a]['FYPeriodDateFrom'] = $master['FYPeriodDateFrom'];
                    $itemledger_arr_buyback[$a]['FYPeriodDateTo'] = $master['FYPeriodDateTo'];
                    $itemledger_arr_buyback[$a]['wareHouseAutoID'] = $erp_itemDetail_bb_ledger[$a]['wareHouseAutoID'];
                    $itemledger_arr_buyback[$a]['wareHouseCode'] = $erp_itemDetail_bb_ledger[$a]['wareHouseCode'];
                    $itemledger_arr_buyback[$a]['wareHouseLocation'] = $erp_itemDetail_bb_ledger[$a]['wareHouseLocation'];
                    $itemledger_arr_buyback[$a]['wareHouseDescription'] = $erp_itemDetail_bb_ledger[$a]['wareHouseDescription'];
                    $itemledger_arr_buyback[$a]['itemAutoID'] = $erp_itemDetail_bb_ledger[$a]['itemAutoID'];
                    $itemledger_arr_buyback[$a]['itemSystemCode'] = $erp_itemDetail_bb_ledger[$a]['itemSystemCode'];
                    $itemledger_arr_buyback[$a]['buybackItemType'] = $erp_itemDetail_bb_ledger[$a]['buybackItemType'];
                    $itemledger_arr_buyback[$a]['itemDescription'] = $erp_itemDetail_bb_ledger[$a]['itemDescription'];
                    $itemledger_arr_buyback[$a]['defaultUOMID'] = $erp_itemDetail_bb_ledger[$a]['defaultUOMID'];
                    $itemledger_arr_buyback[$a]['defaultUOM'] = $erp_itemDetail_bb_ledger[$a]['defaultUOM'];
                    $itemledger_arr_buyback[$a]['transactionUOMID'] = $erp_itemDetail_bb_ledger[$a]['unitOfMeasureID'];
                    $itemledger_arr_buyback[$a]['transactionUOM'] = $erp_itemDetail_bb_ledger[$a]['unitOfMeasure'];
                    $itemledger_arr_buyback[$a]['transactionQTY'] = $erp_itemDetail_bb_ledger[$a]['qty'];
                    $itemledger_arr_buyback[$a]['convertionRate'] = $erp_itemDetail_bb_ledger[$a]['conversionRateUOM'];
                    $itemledger_arr_buyback[$a]['currentStock'] = $item_arr[$a]['currentStock'];

                    $itemledger_arr_buyback[$a]['expenseGLAutoID'] = $item['costGLAutoID'];
                    $itemledger_arr_buyback[$a]['expenseGLCode'] = $item['costGLCode'];
                    $itemledger_arr_buyback[$a]['expenseSystemGLCode'] = $item['costSystemGLCode'];
                    $itemledger_arr_buyback[$a]['expenseGLDescription'] = $item['costDescription'];
                    $itemledger_arr_buyback[$a]['expenseGLType'] = $item['costType'];
                    $itemledger_arr_buyback[$a]['revenueGLAutoID'] = $item['revanueGLAutoID'];
                    $itemledger_arr_buyback[$a]['revenueGLCode'] = $item['revanueGLCode'];
                    $itemledger_arr_buyback[$a]['revenueSystemGLCode'] = $item['revanueSystemGLCode'];
                    $itemledger_arr_buyback[$a]['revenueGLDescription'] = $item['revanueDescription'];
                    $itemledger_arr_buyback[$a]['revenueGLType'] = $item['revanueType'];
                    $itemledger_arr_buyback[$a]['assetGLAutoID'] = $item['assteGLAutoID'];
                    $itemledger_arr_buyback[$a]['assetGLCode'] = $item['assteGLCode'];
                    $itemledger_arr_buyback[$a]['assetSystemGLCode'] = $item['assteSystemGLCode'];
                    $itemledger_arr_buyback[$a]['assetGLDescription'] = $item['assteDescription'];
                    $itemledger_arr_buyback[$a]['assetGLType'] = $item['assteType'];
                    $itemledger_arr_buyback[$a]['transactionCurrencyDecimalPlaces'] = $master['transactionCurrencyDecimalPlaces'];

                    // item price with wac cost (actual cost)
                    $unitTransactionAmount = $erp_itemDetail_bb_ledger[$a]['unitActualCost'] / $master['transactionExchangeRate'];
                    $itemledger_arr_buyback[$a]['unitTransactionAmount'] = round($unitTransactionAmount, $master['transactionCurrencyDecimalPlaces']);
                    $itemledger_arr_buyback[$a]['totalTransactionAmount'] = $itemledger_arr_buyback[$a]['unitTransactionAmount'] * $erp_itemDetail_bb_ledger[$a]['qty'];

                    $unitLocalAmount = $erp_itemDetail_bb_ledger[$a]['unitActualCost'] / $master['companyLocalExchangeRate'];
                    $itemledger_arr_buyback[$a]['unitLocalAmount'] = round($unitLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);;
                    $itemledger_arr_buyback[$a]['totalLocalAmount'] = ($erp_itemDetail_bb_ledger[$a]['qty'] * $itemledger_arr_buyback[$a]['unitLocalAmount']);

                    $unitReportingAmount = $erp_itemDetail_bb_ledger[$a]['unitActualCost'] / $master['companyReportingExchangeRate'];
                    $itemledger_arr_buyback[$a]['unitReportingAmount'] = round($unitReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);
                    $itemledger_arr_buyback[$a]['totalReportingAmount'] = $itemledger_arr_buyback[$a]['unitReportingAmount'] * $erp_itemDetail_bb_ledger[$a]['qty'];

                    // item price with transaction price (transaction cost)
                    $itemledger_arr_buyback[$a]['unitTransferAmountTransaction'] = $erp_itemDetail_bb_ledger[$a]['unitTransferCost'];
                    $itemledger_arr_buyback[$a]['totalTransferAmountTransaction'] = $erp_itemDetail_bb_ledger[$a]['totalTransferCost'];

                    $itemledger_arr_buyback[$a]['unitTransferAmountLocal'] = $erp_itemDetail_bb_ledger[$a]['unitTransferCostLocal'];
                    $itemledger_arr_buyback[$a]['totalTransferAmountLocal'] = $erp_itemDetail_bb_ledger[$a]['totalTransferCostLocal'];

                    $itemledger_arr_buyback[$a]['unitTransferAmountReporting'] = $erp_itemDetail_bb_ledger[$a]['unitTransferCostReporting'];
                    $itemledger_arr_buyback[$a]['totalTranferAmountReporting'] = ($erp_itemDetail_bb_ledger[$a]['totalTransferCostReporting']);

                    //$ex_rate_wac = (1 / $master['companyLocalExchangeRate']);
                    //$itemledger_arr_buyback[$a]['transactionAmount'] = round((($item_detail[$a]['companyLocalWacAmount'] / $ex_rate_wac) * ($itemledger_arr_buyback[$a]['transactionQTY'] / $item_detail[$a]['conversionRateUOM'])), $itemledger_arr_buyback[$a]['transactionCurrencyDecimalPlaces']);
                    //$itemledger_arr_buyback[$a]['salesPrice'] = (($item_detail[$a]['transactionAmount'] / ($itemledger_arr_buyback[$a]['transactionQTY'] / $item_detail[$a]['conversionRateUOM'])) * -1);
                    $itemledger_arr_buyback[$a]['transactionCurrencyID'] = $master['transactionCurrencyID'];
                    $itemledger_arr_buyback[$a]['transactionCurrency'] = $master['transactionCurrency'];
                    $itemledger_arr_buyback[$a]['transactionExchangeRate'] = $master['transactionExchangeRate'];

                    $itemledger_arr_buyback[$a]['companyLocalCurrencyID'] = $master['companyLocalCurrencyID'];
                    $itemledger_arr_buyback[$a]['companyLocalCurrency'] = $master['companyLocalCurrency'];
                    $itemledger_arr_buyback[$a]['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                    $itemledger_arr_buyback[$a]['companyLocalCurrencyDecimalPlaces'] = $master['companyLocalCurrencyDecimalPlaces'];
                    //$itemledger_arr_buyback[$a]['companyLocalAmount'] = round(($itemledger_arr_buyback[$a]['transactionAmount'] / $itemledger_arr_buyback[$a]['companyLocalExchangeRate']), $itemledger_arr_buyback[$a]['companyLocalCurrencyDecimalPlaces']);
                    $itemledger_arr_buyback[$a]['companyLocalWacAmount'] = $item['companyLocalWacAmount'];
                    $itemledger_arr_buyback[$a]['companyReportingCurrencyID'] = $master['companyReportingCurrencyID'];
                    $itemledger_arr_buyback[$a]['companyReportingCurrency'] = $master['companyReportingCurrency'];
                    $itemledger_arr_buyback[$a]['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                    $itemledger_arr_buyback[$a]['companyReportingCurrencyDecimalPlaces'] = $master['companyReportingCurrencyDecimalPlaces'];
                    //$itemledger_arr_buyback[$a]['companyReportingAmount'] = round(($itemledger_arr_buyback[$a]['transactionAmount'] / $itemledger_arr_buyback[$a]['companyReportingExchangeRate']), $itemledger_arr_buyback[$a]['companyReportingCurrencyDecimalPlaces']);
                    $itemledger_arr_buyback[$a]['companyReportingWacAmount'] = $item['companyReportingWacAmount'];

                    $itemledger_arr_buyback[$a]['partyCurrencyID'] = $master['farmerCurrencyID'];
                    $itemledger_arr_buyback[$a]['partyCurrency'] = $master['farmerCurrency'];
                    $itemledger_arr_buyback[$a]['partyCurrencyExchangeRate'] = $master['farmerCurrencyExchangeRate'];
                    $itemledger_arr_buyback[$a]['partyCurrencyDecimalPlaces'] = $master['farmerCurrencyDecimalPlaces'];
                    //$itemledger_arr_buyback[$a]['partyCurrencyAmount'] = round(($itemledger_arr_buyback[$a]['transactionAmount'] / $itemledger_arr_buyback[$a]['partyCurrencyExchangeRate']), $itemledger_arr_buyback[$a]['partyCurrencyDecimalPlaces']);

                    $itemledger_arr_buyback[$a]['confirmedYN'] = $master['confirmedYN'];
                    $itemledger_arr_buyback[$a]['confirmedByEmpID'] = $master['confirmedByEmpID'];
                    $itemledger_arr_buyback[$a]['confirmedByName'] = $master['confirmedByName'];
                    $itemledger_arr_buyback[$a]['confirmedDate'] = $master['confirmedDate'];
                    $itemledger_arr_buyback[$a]['approvedYN'] = $master['approvedYN'];
                    $itemledger_arr_buyback[$a]['approvedDate'] = $master['approvedDate'];
                    $itemledger_arr_buyback[$a]['approvedbyEmpID'] = $master['approvedbyEmpID'];
                    $itemledger_arr_buyback[$a]['approvedbyEmpName'] = $master['approvedbyEmpName'];
                    $itemledger_arr_buyback[$a]['segmentID'] = $master['segmentID'];
                    $itemledger_arr_buyback[$a]['segmentCode'] = $master['segmentCode'];
                    $itemledger_arr_buyback[$a]['companyID'] = $master['companyID'];
                    $itemledger_arr_buyback[$a]['companyCode'] = $master['companyCode'];
                    $itemledger_arr_buyback[$a]['createdUserGroup'] = $master['createdUserGroup'];
                    $itemledger_arr_buyback[$a]['createdPCID'] = $master['createdPCID'];
                    $itemledger_arr_buyback[$a]['createdUserID'] = $master['createdUserID'];
                    $itemledger_arr_buyback[$a]['createdDateTime'] = $master['createdDateTime'];
                    $itemledger_arr_buyback[$a]['createdUserName'] = $master['createdUserName'];
                    $itemledger_arr_buyback[$a]['modifiedPCID'] = $master['modifiedPCID'];
                    $itemledger_arr_buyback[$a]['modifiedUserID'] = $master['modifiedUserID'];
                    $itemledger_arr_buyback[$a]['modifiedDateTime'] = $master['modifiedDateTime'];
                    $itemledger_arr_buyback[$a]['modifiedUserName'] = $master['modifiedUserName'];
                }

            }

            for ($a = 0; $a < count($erp_item_detail); $a++) {

                $item = fetch_item_data($erp_item_detail[$a]['itemAutoID']);
                $itemAutoID = $erp_item_detail[$a]['itemAutoID'];

                $qty = $erp_item_detail[$a]['qty'] / $erp_item_detail[$a]['conversionRateUOM'];
                $wareHouseAutoID = $erp_item_detail[$a]['wareHouseAutoID'];
                $this->db->query("UPDATE srp_erp_warehouseitems SET currentStock = (currentStock -{$qty})  WHERE wareHouseAutoID='{$wareHouseAutoID}' and itemAutoID='{$itemAutoID}'");

                $item_arr_erp[$a]['itemAutoID'] = $erp_item_detail[$a]['itemAutoID'];
                $item_arr_erp[$a]['currentStock'] = ($item['currentStock'] - $qty);
                $item_arr_erps[$a]['companyLocalWacAmount'] = round(((($item['currentStock'] * $item['companyLocalWacAmount']) + ($erp_item_detail[$a]['companyLocalAmount'])) / $item_arr_erp[$a]['currentStock']), $master['companyLocalCurrencyDecimalPlaces']);
                $item_arr_erps[$a]['companyReportingWacAmount'] = round(((($item['currentStock'] * $item['companyReportingWacAmount']) + ($erp_item_detail[$a]['companyReportingAmount'] / $master['companyReportingExchangeRate'])) / $item_arr_erp[$a]['currentStock']), $master['companyReportingCurrencyDecimalPlaces']);

                $itemledger_arr_erp[$a]['documentID'] = $master['documentID'];
                $itemledger_arr_erp[$a]['documentCode'] = $master['documentID'];
                $itemledger_arr_erp[$a]['documentAutoID'] = $master['dispatchAutoID'];
                $itemledger_arr_erp[$a]['documentSystemCode'] = $master['documentSystemCode'];
                $itemledger_arr_erp[$a]['documentDate'] = $master['documentDate'];
                $itemledger_arr_erp[$a]['referenceNumber'] = $master['referenceNo'];
                $itemledger_arr_erp[$a]['companyFinanceYearID'] = $master['companyFinanceYearID'];
                $itemledger_arr_erp[$a]['companyFinanceYear'] = $master['companyFinanceYear'];
                $itemledger_arr_erp[$a]['FYBegin'] = $master['FYBegin'];
                $itemledger_arr_erp[$a]['FYEnd'] = $master['FYEnd'];
                $itemledger_arr_erp[$a]['FYPeriodDateFrom'] = $master['FYPeriodDateFrom'];
                $itemledger_arr_erp[$a]['FYPeriodDateTo'] = $master['FYPeriodDateTo'];
                $itemledger_arr_erp[$a]['wareHouseAutoID'] = $erp_item_detail[$a]['wareHouseAutoID'];
                $itemledger_arr_erp[$a]['wareHouseCode'] = $erp_item_detail[$a]['wareHouseCode'];
                $itemledger_arr_erp[$a]['wareHouseLocation'] = $erp_item_detail[$a]['wareHouseLocation'];
                $itemledger_arr_erp[$a]['wareHouseDescription'] = $erp_item_detail[$a]['wareHouseDescription'];
                $itemledger_arr_erp[$a]['itemAutoID'] = $erp_item_detail[$a]['itemAutoID'];
                $itemledger_arr_erp[$a]['itemSystemCode'] = $erp_item_detail[$a]['itemSystemCode'];
                $itemledger_arr_erp[$a]['itemDescription'] = $erp_item_detail[$a]['itemDescription'];
                $itemledger_arr_erp[$a]['defaultUOMID'] = $erp_item_detail[$a]['defaultUOMID'];
                $itemledger_arr_erp[$a]['defaultUOM'] = $erp_item_detail[$a]['defaultUOM'];
                $itemledger_arr_erp[$a]['transactionUOMID'] = $erp_item_detail[$a]['unitOfMeasureID'];
                $itemledger_arr_erp[$a]['transactionUOM'] = $erp_item_detail[$a]['unitOfMeasure'];
                $itemledger_arr_erp[$a]['transactionQTY'] = ($erp_item_detail[$a]['qty'] * -1);
                $itemledger_arr_erp[$a]['convertionRate'] = $erp_item_detail[$a]['conversionRateUOM'];
                $itemledger_arr_erp[$a]['currentStock'] = $item_arr_erp[$a]['currentStock'];
                $itemledger_arr_erp[$a]['PLGLAutoID'] = $item['revanueGLAutoID'];
                $itemledger_arr_erp[$a]['PLSystemGLCode'] = $item['revanueSystemGLCode'];
                $itemledger_arr_erp[$a]['PLGLCode'] = $item['revanueGLCode'];
                $itemledger_arr_erp[$a]['PLDescription'] = $item['revanueDescription'];
                $itemledger_arr_erp[$a]['PLType'] = $item['revanueType'];
                $itemledger_arr_erp[$a]['BLGLAutoID'] = $item['assteGLAutoID'];
                $itemledger_arr_erp[$a]['BLSystemGLCode'] = $item['assteSystemGLCode'];
                $itemledger_arr_erp[$a]['BLGLCode'] = $item['assteGLCode'];
                $itemledger_arr_erp[$a]['BLDescription'] = $item['assteDescription'];
                $itemledger_arr_erp[$a]['BLType'] = $item['assteType'];
                $itemledger_arr_erp[$a]['transactionCurrencyDecimalPlaces'] = $master['transactionCurrencyDecimalPlaces'];

                $itemledger_arr_erp[$a]['transactionAmount'] = round($erp_item_detail[$a]['transactionAmount'], $itemledger_arr_erp[$a]['transactionCurrencyDecimalPlaces']);
                $itemledger_arr_erp[$a]['salesPrice'] = NULL;
                $itemledger_arr_erp[$a]['transactionCurrencyID'] = $master['transactionCurrencyID'];
                $itemledger_arr_erp[$a]['transactionCurrency'] = $master['transactionCurrency'];
                $itemledger_arr_erp[$a]['transactionExchangeRate'] = $master['transactionExchangeRate'];

                $itemledger_arr_erp[$a]['companyLocalCurrencyID'] = $master['companyLocalCurrencyID'];
                $itemledger_arr_erp[$a]['companyLocalCurrency'] = $master['companyLocalCurrency'];
                $itemledger_arr_erp[$a]['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                $itemledger_arr_erp[$a]['companyLocalCurrencyDecimalPlaces'] = $master['companyLocalCurrencyDecimalPlaces'];
                $itemledger_arr_erp[$a]['companyLocalAmount'] = round(($itemledger_arr_erp[$a]['transactionAmount'] / $itemledger_arr_erp[$a]['companyLocalExchangeRate']), $itemledger_arr_erp[$a]['companyLocalCurrencyDecimalPlaces']);
                $itemledger_arr_erp[$a]['companyLocalWacAmount'] = $item['companyLocalWacAmount'];
                $itemledger_arr_erp[$a]['companyReportingCurrencyID'] = $master['companyReportingCurrencyID'];
                $itemledger_arr_erp[$a]['companyReportingCurrency'] = $master['companyReportingCurrency'];
                $itemledger_arr_erp[$a]['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                $itemledger_arr_erp[$a]['companyReportingCurrencyDecimalPlaces'] = $master['companyReportingCurrencyDecimalPlaces'];
                $itemledger_arr_erp[$a]['companyReportingAmount'] = round(($itemledger_arr_erp[$a]['transactionAmount'] / $itemledger_arr_erp[$a]['companyReportingExchangeRate']), $itemledger_arr_erp[$a]['companyReportingCurrencyDecimalPlaces']);
                $itemledger_arr_erp[$a]['companyReportingWacAmount'] = $item['companyReportingWacAmount'];

                $itemledger_arr_erp[$a]['partyCurrencyID'] = $master['farmerCurrencyID'];
                $itemledger_arr_erp[$a]['partyCurrency'] = $master['farmerCurrency'];
                $itemledger_arr_erp[$a]['partyCurrencyExchangeRate'] = $master['farmerCurrencyExchangeRate'];
                $itemledger_arr_erp[$a]['partyCurrencyDecimalPlaces'] = $master['farmerCurrencyDecimalPlaces'];
                $itemledger_arr_erp[$a]['partyCurrencyAmount'] = NULL;
                $itemledger_arr_erp[$a]['confirmedYN'] = $master['confirmedYN'];
                $itemledger_arr_erp[$a]['confirmedByEmpID'] = $master['confirmedByEmpID'];
                $itemledger_arr_erp[$a]['confirmedByName'] = $master['confirmedByName'];
                $itemledger_arr_erp[$a]['confirmedDate'] = $master['confirmedDate'];
                $itemledger_arr_erp[$a]['approvedYN'] = $master['approvedYN'];
                $itemledger_arr_erp[$a]['approvedDate'] = $master['approvedDate'];
                $itemledger_arr_erp[$a]['approvedbyEmpID'] = $master['approvedbyEmpID'];
                $itemledger_arr_erp[$a]['approvedbyEmpName'] = $master['approvedbyEmpName'];

                $itemledger_arr_erp[$a]['segmentID'] = $master['segmentID'];
                $itemledger_arr_erp[$a]['segmentCode'] = $master['segmentCode'];
                $itemledger_arr_erp[$a]['companyID'] = $master['companyID'];
                $itemledger_arr_erp[$a]['companyCode'] = $master['companyCode'];
                $itemledger_arr_erp[$a]['createdUserGroup'] = $master['createdUserGroup'];
                $itemledger_arr_erp[$a]['createdPCID'] = $master['createdPCID'];
                $itemledger_arr_erp[$a]['createdUserID'] = $master['createdUserID'];
                $itemledger_arr_erp[$a]['createdDateTime'] = $master['createdDateTime'];
                $itemledger_arr_erp[$a]['createdUserName'] = $master['createdUserName'];
                $itemledger_arr_erp[$a]['modifiedPCID'] = $master['modifiedPCID'];
                $itemledger_arr_erp[$a]['modifiedUserID'] = $master['modifiedUserID'];
                $itemledger_arr_erp[$a]['modifiedDateTime'] = $master['modifiedDateTime'];
                $itemledger_arr_erp[$a]['modifiedUserName'] = $master['modifiedUserName'];

            }

            if (!empty($item_arr_erp)) {
                $item_arr_erp = array_values($item_arr_erp);
                $this->db->update_batch('srp_erp_itemmaster', $item_arr_erp, 'itemAutoID');
            }

            if (!empty($itemledger_arr_erp)) {
                $itemledger_arr_erp = array_values($itemledger_arr_erp);
                $this->db->insert_batch('srp_erp_itemledger', $itemledger_arr_erp);
            }

            if (!empty($itemledger_arr_buyback)) {
                $itemledger_arr_buyback = array_values($itemledger_arr_buyback);
                $this->db->insert_batch('srp_erp_buyback_itemledger', $itemledger_arr_buyback);
            }

            $double_entry = $this->fetch_double_entry_buyback_dispatchNote($system_id, 'BBDPN');

            for ($i = 0; $i < count($double_entry['gl_detail']); $i++) {
                $generalledger_arr[$i]['documentMasterAutoID'] = $double_entry['master_data']['dispatchAutoID'];
                $generalledger_arr[$i]['documentCode'] = $double_entry['code'];
                $generalledger_arr[$i]['documentSystemCode'] = $double_entry['master_data']['documentSystemCode'];
                $generalledger_arr[$i]['documentDate'] = $double_entry['master_data']['documentDate'];
                $generalledger_arr[$i]['documentType'] = NULL;
                $generalledger_arr[$i]['documentYear'] = $double_entry['master_data']['documentDate'];
                $generalledger_arr[$i]['documentMonth'] = date("m", strtotime($double_entry['master_data']['documentDate']));
                $generalledger_arr[$i]['documentNarration'] = $double_entry['master_data']['referenceNo'];
                $generalledger_arr[$i]['chequeNumber'] = NULL;
                $generalledger_arr[$i]['transactionCurrencyID'] = $double_entry['master_data']['transactionCurrencyID'];
                $generalledger_arr[$i]['transactionCurrency'] = $double_entry['master_data']['transactionCurrency'];
                $generalledger_arr[$i]['transactionExchangeRate'] = $double_entry['gl_detail'][$i]['transactionExchangeRate'];
                $generalledger_arr[$i]['transactionCurrencyDecimalPlaces'] = $double_entry['master_data']['transactionCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['companyLocalCurrencyID'] = $double_entry['master_data']['companyLocalCurrencyID'];
                $generalledger_arr[$i]['companyLocalCurrency'] = $double_entry['master_data']['companyLocalCurrency'];
                $generalledger_arr[$i]['companyLocalExchangeRate'] = $double_entry['gl_detail'][$i]['companyLocalExchangeRate'];
                $generalledger_arr[$i]['companyLocalCurrencyDecimalPlaces'] = $double_entry['master_data']['companyLocalCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['companyReportingCurrencyID'] = $double_entry['master_data']['companyReportingCurrencyID'];
                $generalledger_arr[$i]['companyReportingCurrency'] = $double_entry['master_data']['companyReportingCurrency'];
                $generalledger_arr[$i]['companyReportingExchangeRate'] = $double_entry['gl_detail'][$i]['companyReportingExchangeRate'];
                $generalledger_arr[$i]['companyReportingCurrencyDecimalPlaces'] = $double_entry['master_data']['companyReportingCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['partyContractID'] = '';
                $generalledger_arr[$i]['partyType'] = $double_entry['gl_detail'][$i]['partyType'];
                $generalledger_arr[$i]['partyAutoID'] = $double_entry['gl_detail'][$i]['partyAutoID'];
                $generalledger_arr[$i]['partySystemCode'] = $double_entry['gl_detail'][$i]['partySystemCode'];
                $generalledger_arr[$i]['partyName'] = $double_entry['gl_detail'][$i]['partyName'];
                $generalledger_arr[$i]['partyCurrencyID'] = $double_entry['gl_detail'][$i]['partyCurrencyID'];
                $generalledger_arr[$i]['partyCurrency'] = $double_entry['gl_detail'][$i]['partyCurrency'];
                $generalledger_arr[$i]['partyExchangeRate'] = $double_entry['gl_detail'][$i]['partyExchangeRate'];
                $generalledger_arr[$i]['partyCurrencyDecimalPlaces'] = $double_entry['gl_detail'][$i]['partyCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['confirmedByEmpID'] = $double_entry['master_data']['confirmedByEmpID'];
                $generalledger_arr[$i]['confirmedByName'] = $double_entry['master_data']['confirmedByName'];
                $generalledger_arr[$i]['confirmedDate'] = $double_entry['master_data']['confirmedDate'];
                $generalledger_arr[$i]['approvedDate'] = $double_entry['master_data']['approvedDate'];
                $generalledger_arr[$i]['approvedbyEmpID'] = $double_entry['master_data']['approvedbyEmpID'];
                $generalledger_arr[$i]['approvedbyEmpName'] = $double_entry['master_data']['approvedbyEmpName'];
                $generalledger_arr[$i]['companyID'] = $double_entry['master_data']['companyID'];
                $generalledger_arr[$i]['companyCode'] = $double_entry['master_data']['companyCode'];
                $amount = $double_entry['gl_detail'][$i]['gl_dr'];
                if ($double_entry['gl_detail'][$i]['amount_type'] == 'cr') {
                    $amount = ($double_entry['gl_detail'][$i]['gl_cr'] * -1);
                }
                $generalledger_arr[$i]['transactionAmount'] = round($amount, $generalledger_arr[$i]['transactionCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['companyLocalAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['companyLocalExchangeRate']), $generalledger_arr[$i]['companyLocalCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['companyReportingAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['companyReportingExchangeRate']), $generalledger_arr[$i]['companyReportingCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['partyCurrencyAmount'] = NULL;
                $generalledger_arr[$i]['amount_type'] = $double_entry['gl_detail'][$i]['amount_type'];
                $generalledger_arr[$i]['documentDetailAutoID'] = $double_entry['gl_detail'][$i]['auto_id'];
                $generalledger_arr[$i]['GLAutoID'] = $double_entry['gl_detail'][$i]['gl_auto_id'];
                $generalledger_arr[$i]['systemGLCode'] = $double_entry['gl_detail'][$i]['gl_code'];
                $generalledger_arr[$i]['GLCode'] = $double_entry['gl_detail'][$i]['secondary'];
                $generalledger_arr[$i]['GLDescription'] = $double_entry['gl_detail'][$i]['gl_desc'];
                $generalledger_arr[$i]['GLType'] = $double_entry['gl_detail'][$i]['gl_type'];
                $generalledger_arr[$i]['segmentID'] = $double_entry['gl_detail'][$i]['segment_id'];
                $generalledger_arr[$i]['segmentCode'] = $double_entry['gl_detail'][$i]['segment'];
                $generalledger_arr[$i]['projectID'] = isset($double_entry['gl_detail'][$i]['projectID']) ? $double_entry['gl_detail'][$i]['projectID'] : null;
                $generalledger_arr[$i]['projectExchangeRate'] = isset($double_entry['gl_detail'][$i]['projectExchangeRate']) ? $double_entry['gl_detail'][$i]['projectExchangeRate'] : null;
                $generalledger_arr[$i]['subLedgerType'] = $double_entry['gl_detail'][$i]['subLedgerType'];
                $generalledger_arr[$i]['subLedgerDesc'] = $double_entry['gl_detail'][$i]['subLedgerDesc'];
                $generalledger_arr[$i]['isAddon'] = $double_entry['gl_detail'][$i]['isAddon'];
                $generalledger_arr[$i]['createdUserGroup'] = $this->common_data['user_group'];
                $generalledger_arr[$i]['createdPCID'] = $this->common_data['current_pc'];
                $generalledger_arr[$i]['createdUserID'] = $this->common_data['current_userID'];
                $generalledger_arr[$i]['createdDateTime'] = $this->common_data['current_date'];
                $generalledger_arr[$i]['createdUserName'] = $this->common_data['current_user'];
                $generalledger_arr[$i]['modifiedPCID'] = $this->common_data['current_pc'];
                $generalledger_arr[$i]['modifiedUserID'] = $this->common_data['current_userID'];
                $generalledger_arr[$i]['modifiedDateTime'] = $this->common_data['current_date'];
                $generalledger_arr[$i]['modifiedUserName'] = $this->common_data['current_user'];
            }
            $this->db->insert_batch('srp_erp_generalledger', $generalledger_arr);

            $data['approvedYN'] = $status;
            $data['approvedbyEmpID'] = $this->common_data['current_userID'];
            $data['approvedbyEmpName'] = $this->common_data['current_user'];
            $data['approvedDate'] = $this->common_data['current_date'];
            /* $data['transactionAmount'] = $total;*/
            $this->db->where('dispatchAutoID', $system_id);
            $this->db->update('srp_erp_buyback_dispatchnote', $data);
            $this->session->set_flashdata('s', 'Dispatch Note Approved Successfully.');
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

    function save_paymentVoucher_approval()
    {
        $this->db->trans_start();
        $this->load->library('approvals');
        $system_id = trim($this->input->post('pvMasterAutoID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('po_status'));
        $comments = trim($this->input->post('comments'));

        $this->db->select("documentID");
        $this->db->from("srp_erp_buyback_paymentvouchermaster");
        $this->db->where("pvMasterAutoID", $system_id);
        $masterVoucher = $this->db->get()->row_array();

        $approvals_status = $this->approvals->approve_document($system_id, $level_id, $status, $comments, $masterVoucher['documentID']);

        if ($approvals_status == 1) {

            $double_entry = $this->fetch_double_entry_buyback_paymentVoucher($system_id, $masterVoucher['documentID']);

            for ($i = 0; $i < count($double_entry['gl_detail']); $i++) {
                $generalledger_arr[$i]['documentMasterAutoID'] = $double_entry['master_data']['pvMasterAutoID'];
                $generalledger_arr[$i]['documentCode'] = $double_entry['code'];
                $generalledger_arr[$i]['documentSystemCode'] = $double_entry['master_data']['documentSystemCode'];
                $generalledger_arr[$i]['documentDate'] = $double_entry['master_data']['documentDate'];
                $generalledger_arr[$i]['documentType'] = NULL;
                $generalledger_arr[$i]['documentYear'] = $double_entry['master_data']['documentDate'];
                $generalledger_arr[$i]['documentMonth'] = date("m", strtotime($double_entry['master_data']['documentDate']));
                $generalledger_arr[$i]['documentNarration'] = $double_entry['master_data']['referenceNo'];
                $generalledger_arr[$i]['chequeNumber'] = NULL;
                $generalledger_arr[$i]['transactionCurrencyID'] = $double_entry['master_data']['transactionCurrencyID'];
                $generalledger_arr[$i]['transactionCurrency'] = $double_entry['master_data']['transactionCurrency'];
                $generalledger_arr[$i]['transactionExchangeRate'] = $double_entry['gl_detail'][$i]['transactionExchangeRate'];
                $generalledger_arr[$i]['transactionCurrencyDecimalPlaces'] = $double_entry['master_data']['transactionCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['companyLocalCurrencyID'] = $double_entry['master_data']['companyLocalCurrencyID'];
                $generalledger_arr[$i]['companyLocalCurrency'] = $double_entry['master_data']['companyLocalCurrency'];
                $generalledger_arr[$i]['companyLocalExchangeRate'] = $double_entry['gl_detail'][$i]['companyLocalExchangeRate'];
                $generalledger_arr[$i]['companyLocalCurrencyDecimalPlaces'] = $double_entry['master_data']['companyLocalCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['companyReportingCurrencyID'] = $double_entry['master_data']['companyReportingCurrencyID'];
                $generalledger_arr[$i]['companyReportingCurrency'] = $double_entry['master_data']['companyReportingCurrency'];
                $generalledger_arr[$i]['companyReportingExchangeRate'] = $double_entry['gl_detail'][$i]['companyReportingExchangeRate'];
                $generalledger_arr[$i]['companyReportingCurrencyDecimalPlaces'] = $double_entry['master_data']['companyReportingCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['partyContractID'] = '';
                $generalledger_arr[$i]['partyType'] = $double_entry['gl_detail'][$i]['partyType'];
                $generalledger_arr[$i]['partyAutoID'] = $double_entry['gl_detail'][$i]['partyAutoID'];
                $generalledger_arr[$i]['partySystemCode'] = $double_entry['gl_detail'][$i]['partySystemCode'];
                $generalledger_arr[$i]['partyName'] = $double_entry['gl_detail'][$i]['partyName'];
                $generalledger_arr[$i]['partyCurrencyID'] = $double_entry['gl_detail'][$i]['partyCurrencyID'];
                $generalledger_arr[$i]['partyCurrency'] = $double_entry['gl_detail'][$i]['partyCurrency'];
                $generalledger_arr[$i]['partyExchangeRate'] = $double_entry['gl_detail'][$i]['partyExchangeRate'];
                $generalledger_arr[$i]['partyCurrencyDecimalPlaces'] = $double_entry['gl_detail'][$i]['partyCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['confirmedByEmpID'] = $double_entry['master_data']['confirmedByEmpID'];
                $generalledger_arr[$i]['confirmedByName'] = $double_entry['master_data']['confirmedByName'];
                $generalledger_arr[$i]['confirmedDate'] = $double_entry['master_data']['confirmedDate'];
                $generalledger_arr[$i]['approvedDate'] = $double_entry['master_data']['approvedDate'];
                $generalledger_arr[$i]['approvedbyEmpID'] = $double_entry['master_data']['approvedbyEmpID'];
                $generalledger_arr[$i]['approvedbyEmpName'] = $double_entry['master_data']['approvedbyEmpName'];
                $generalledger_arr[$i]['companyID'] = $double_entry['master_data']['companyID'];
                $generalledger_arr[$i]['companyCode'] = $double_entry['master_data']['companyCode'];
                $amount = $double_entry['gl_detail'][$i]['gl_dr'];
                if ($double_entry['gl_detail'][$i]['amount_type'] == 'cr') {
                    $amount = ($double_entry['gl_detail'][$i]['gl_cr'] * -1);
                }
                $generalledger_arr[$i]['transactionAmount'] = round($amount, $generalledger_arr[$i]['transactionCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['companyLocalAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['companyLocalExchangeRate']), $generalledger_arr[$i]['companyLocalCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['companyReportingAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['companyReportingExchangeRate']), $generalledger_arr[$i]['companyReportingCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['partyCurrencyAmount'] = NULL;
                $generalledger_arr[$i]['amount_type'] = $double_entry['gl_detail'][$i]['amount_type'];
                $generalledger_arr[$i]['documentDetailAutoID'] = $double_entry['gl_detail'][$i]['auto_id'];
                $generalledger_arr[$i]['GLAutoID'] = $double_entry['gl_detail'][$i]['gl_auto_id'];
                $generalledger_arr[$i]['systemGLCode'] = $double_entry['gl_detail'][$i]['gl_code'];
                $generalledger_arr[$i]['GLCode'] = $double_entry['gl_detail'][$i]['secondary'];
                $generalledger_arr[$i]['GLDescription'] = $double_entry['gl_detail'][$i]['gl_desc'];
                $generalledger_arr[$i]['GLType'] = $double_entry['gl_detail'][$i]['gl_type'];
                $generalledger_arr[$i]['segmentID'] = $double_entry['gl_detail'][$i]['segment_id'];
                $generalledger_arr[$i]['segmentCode'] = $double_entry['gl_detail'][$i]['segment'];
                $generalledger_arr[$i]['projectID'] = isset($double_entry['gl_detail'][$i]['projectID']) ? $double_entry['gl_detail'][$i]['projectID'] : null;
                $generalledger_arr[$i]['projectExchangeRate'] = isset($double_entry['gl_detail'][$i]['projectExchangeRate']) ? $double_entry['gl_detail'][$i]['projectExchangeRate'] : null;
                $generalledger_arr[$i]['subLedgerType'] = $double_entry['gl_detail'][$i]['subLedgerType'];
                $generalledger_arr[$i]['subLedgerDesc'] = $double_entry['gl_detail'][$i]['subLedgerDesc'];
                $generalledger_arr[$i]['isAddon'] = $double_entry['gl_detail'][$i]['isAddon'];
                $generalledger_arr[$i]['createdUserGroup'] = $this->common_data['user_group'];
                $generalledger_arr[$i]['createdPCID'] = $this->common_data['current_pc'];
                $generalledger_arr[$i]['createdUserID'] = $this->common_data['current_userID'];
                $generalledger_arr[$i]['createdDateTime'] = $this->common_data['current_date'];
                $generalledger_arr[$i]['createdUserName'] = $this->common_data['current_user'];
                $generalledger_arr[$i]['modifiedPCID'] = $this->common_data['current_pc'];
                $generalledger_arr[$i]['modifiedUserID'] = $this->common_data['current_userID'];
                $generalledger_arr[$i]['modifiedDateTime'] = $this->common_data['current_date'];
                $generalledger_arr[$i]['modifiedUserName'] = $this->common_data['current_user'];
            }
            $this->db->insert_batch('srp_erp_generalledger', $generalledger_arr);

            $total = $this->db->query("SELECT sum(transactionAmount) as amount FROM srp_erp_buyback_paymentvoucherdetail WHERE pvMasterAutoID ={$double_entry['master_data']['pvMasterAutoID']}")->row_array();
            $amount = $total['amount']; //receipt_voucher_total_value($double_entry['master_data']['receiptVoucherAutoId'], $double_entry['master_data']['transactionCurrencyDecimalPlaces'], 0);
            if ($amount != '') {

                $bankledger_arr['documentMasterAutoID'] = $double_entry['master_data']['pvMasterAutoID'];
                $bankledger_arr['documentDate'] = $double_entry['master_data']['documentDate'];
                $bankledger_arr['transactionType'] = 2;
                $bankledger_arr['bankName'] = $double_entry['master_data']['PVbank'];
                $bankledger_arr['bankGLAutoID'] = $double_entry['master_data']['bankGLAutoID'];
                $bankledger_arr['bankSystemAccountCode'] = $double_entry['master_data']['bankSystemAccountCode'];
                $bankledger_arr['bankGLSecondaryCode'] = $double_entry['master_data']['bankGLSecondaryCode'];
                $bankledger_arr['documentType'] = 'BBPV';
                $bankledger_arr['documentSystemCode'] = $double_entry['master_data']['documentSystemCode'];
                $bankledger_arr['modeofpayment'] = $double_entry['master_data']['modeOfPayment'];
                $bankledger_arr['chequeNo'] = $double_entry['master_data']['PVchequeNo'];
                $bankledger_arr['chequeDate'] = $double_entry['master_data']['PVchequeDate'];
                $bankledger_arr['memo'] = $double_entry['master_data']['PVNarration'];
                $bankledger_arr['partyType'] = 'FAR';
                $bankledger_arr['partyAutoID'] = $double_entry['master_data']['farmID'];
                $bankledger_arr['partyCode'] = $double_entry['farmer']['farmSystemCode'];
                $bankledger_arr['partyName'] = $double_entry['farmer']['description'];
                $bankledger_arr['transactionCurrencyID'] = $double_entry['master_data']['transactionCurrencyID'];
                $bankledger_arr['transactionCurrency'] = $double_entry['master_data']['transactionCurrency'];
                $bankledger_arr['transactionExchangeRate'] = $double_entry['master_data']['transactionExchangeRate'];
                $bankledger_arr['transactionCurrencyDecimalPlaces'] = $double_entry['master_data']['transactionCurrencyDecimalPlaces'];
                $bankledger_arr['transactionAmount'] = $amount;
                $bankledger_arr['partyCurrencyID'] = $double_entry['master_data']['partyCurrencyID'];
                $bankledger_arr['partyCurrency'] = $double_entry['master_data']['partyCurrency'];
                $bankledger_arr['partyCurrencyExchangeRate'] = $double_entry['master_data']['partyExchangeRate'];
                $bankledger_arr['partyCurrencyDecimalPlaces'] = $double_entry['master_data']['partyCurrencyDecimalPlaces'];
                $bankledger_arr['partyCurrencyAmount'] = NULL;
                //$bankledger_arr['partyCurrencyAmount'] = ($bankledger_arr['transactionAmount'] / $bankledger_arr['partyCurrencyExchangeRate']);
                $bankledger_arr['bankCurrencyID'] = $double_entry['master_data']['bankCurrencyID'];
                $bankledger_arr['bankCurrency'] = $double_entry['master_data']['bankCurrency'];
                $bankledger_arr['bankCurrencyExchangeRate'] = $double_entry['master_data']['bankCurrencyExchangeRate'];
                $bankledger_arr['bankCurrencyAmount'] = NULL;
                //$bankledger_arr['bankCurrencyAmount'] = ($bankledger_arr['transactionAmount'] / $bankledger_arr['bankCurrencyExchangeRate']);
                $bankledger_arr['bankCurrencyDecimalPlaces'] = $double_entry['master_data']['bankCurrencyDecimalPlaces'];
                $bankledger_arr['companyID'] = $double_entry['master_data']['companyID'];
                $bankledger_arr['companyCode'] = $double_entry['master_data']['companyCode'];
                $bankledger_arr['segmentID'] = NULL;
                $bankledger_arr['segmentCode'] = NULL;
                $bankledger_arr['createdPCID'] = $this->common_data['current_pc'];
                $bankledger_arr['createdUserID'] = $this->common_data['current_userID'];
                $bankledger_arr['createdDateTime'] = $this->common_data['current_date'];
                $bankledger_arr['createdUserName'] = $this->common_data['current_user'];
                $bankledger_arr['modifiedPCID'] = $this->common_data['current_pc'];
                $bankledger_arr['modifiedUserID'] = $this->common_data['current_userID'];
                $bankledger_arr['modifiedDateTime'] = $this->common_data['current_date'];
                $bankledger_arr['modifiedUserName'] = $this->common_data['current_user'];

                $this->db->insert('srp_erp_bankledger', $bankledger_arr);
            }
            $data['approvedYN'] = $status;
            $data['approvedbyEmpID'] = $this->common_data['current_userID'];
            $data['approvedbyEmpName'] = $this->common_data['current_user'];
            $data['approvedDate'] = $this->common_data['current_date'];
            $this->db->where('pvMasterAutoID', $system_id);
            $this->db->update('srp_erp_buyback_paymentvouchermaster', $data);
            $this->session->set_flashdata('s', 'Payment Voucher Approved Successfully.');
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

    function fetch_goodReciptNote_liveBirds_item()
    {
        $companyID = current_companyID();
        $this->db->select("itemAutoID, CONCAT(itemSystemCode, ' | ',itemDescription) AS itemName,defaultUnitOfMeasureID");
        $this->db->from('srp_erp_buyback_itemmaster');
        $this->db->where('companyID', $companyID);
        $this->db->where('buybackItemType', 4);
        return $this->db->get()->row_array();
    }

    function fetch_goodReciptNote_batch_chicks()
    {
        $companyID = current_companyID();
        $batchID = trim($this->input->post('batchID'));

        $chicks = $this->db->query("SELECT
	(
		ifnull(sum(dpd.qty), 0) - ifnull(ggrn.noOfBirds, 0)
	) AS chicksTotal
FROM
	`srp_erp_buyback_dispatchnotedetails` `dpd`
INNER JOIN `srp_erp_buyback_dispatchnote` `dpm` ON `dpm`.`dispatchAutoID` = `dpd`.`dispatchAutoID`
LEFT JOIN (
	SELECT
		ifnull(SUM(grd.noOfBirds),0) as noOfBirds,
		grn.batchMasterID,
		grnDetailsID
	FROM
		srp_erp_buyback_grn grn
	INNER JOIN srp_erp_buyback_grndetails grd ON grd.grnAutoID = grn.grnAutoID
	WHERE
		`grn`.`batchMasterID` = '$batchID'
	GROUP BY
		batchMasterID
) ggrn ON `ggrn`.`batchMasterID` = `dpm`.`batchMasterID`
WHERE
	`dpm`.`companyID` = '$companyID'
AND `dpm`.`batchMasterID` = '$batchID'
AND `dpd`.`buybackItemType` = 1
AND `dpm`.`approvedYN` = 1")->row_array();


        $mortalityChicks = $this->db->query("SELECT COALESCE(sum(noOfBirds), 0) AS deadChicksTotal FROM srp_erp_buyback_mortalitymaster mm INNER JOIN srp_erp_buyback_mortalitydetails md ON mm.mortalityAutoID = md.mortalityAutoID WHERE batchMasterID = $batchID  AND confirmedYN = 1")->row_array();

        $this->db->select(" SUM(qty )as qtynew");
        $this->db->from(' srp_erp_buyback_dispatchreturn dprm');
        $this->db->join('srp_erp_buyback_dispatchreturndetails dprd ', 'dprm.returnAutoID = dprd.returnAutoID ', 'INNER');
        $this->db->join('srp_erp_buyback_batch batch ', ' batch.batchMasterID = dprm.batchMasterID  ', 'LEFT');
        $this->db->where('dprm.confirmedYN', 1);
        $this->db->where('dprm.batchMasterID', $batchID);
        $this->db->where('dprm.approvedYN', 1);
        $return = $this->db->get()->row_array();


        return $chicks;
    }

    function fetch_goodReciptNote_batch_chicks_edit()
    {
        $companyID = current_companyID();
        $batchID = trim($this->input->post('batchID'));
        $grnDetailsID = trim($this->input->post('grnDetailsID'));

        $chicks = $this->db->query("SELECT
	(
		ifnull(sum(dpd.qty), 0) - ifnull(ggrn.noOfBirds, 0)
	) AS chicksTotal
FROM
	`srp_erp_buyback_dispatchnotedetails` `dpd`
INNER JOIN `srp_erp_buyback_dispatchnote` `dpm` ON `dpm`.`dispatchAutoID` = `dpd`.`dispatchAutoID`
LEFT JOIN (
	SELECT
		ifnull(SUM(grd.noOfBirds),0) as noOfBirds,
		grn.batchMasterID,
		grnDetailsID
	FROM
		srp_erp_buyback_grn grn
	INNER JOIN srp_erp_buyback_grndetails grd ON grd.grnAutoID = grn.grnAutoID
	WHERE
		`grn`.`batchMasterID` = '$batchID' AND grnDetailsID != '$grnDetailsID'
	GROUP BY
		batchMasterID
) ggrn ON `ggrn`.`batchMasterID` = `dpm`.`batchMasterID`
WHERE
	`dpm`.`companyID` = '$companyID'
AND `dpm`.`batchMasterID` = '$batchID'
AND `dpd`.`buybackItemType` = 1
AND `dpm`.`approvedYN` = 1")->row_array();

        return $chicks;
    }

    function fetch_goodReciptNote_batch_mortality()
    {
        $companyID = current_companyID();
        $batchID = trim($this->input->post('batchID'));

        $this->db->select("sum(noOfBirds) as mortalityTotal");
        $this->db->from('srp_erp_buyback_mortalitydetails md');
        $this->db->join('srp_erp_buyback_mortalitymaster mm', 'mm.mortalityAutoID = md.mortalityAutoID', 'INNER');
        $this->db->where('mm.companyID', $companyID);
        $this->db->where('mm.batchMasterID', $batchID);
        $this->db->where('mm.confirmedYN', 1);
        return $this->db->get()->row_array();
        //echo $this->db->last_query();
    }


    function save_grn_detail()
    {
        $grnDetailsID = $this->input->post('grnDetailsID');
        $this->db->trans_start();
        $uom = explode('|', $this->input->post('uom'));
        $item_data = fetch_item_data(trim($this->input->post('itemAutoID')));

        $this->db->select('wareHouseAutoID,wareHouseLocation,wareHouseDescription,transactionCurrencyID,companyLocalExchangeRate,companyReportingExchangeRate,companyLocalCurrencyDecimalPlaces,companyReportingCurrencyDecimalPlaces,batchMasterID');
        $this->db->from('srp_erp_buyback_grn');
        $this->db->where('grnAutoID', trim($this->input->post('grnAutoID')));
        $master = $this->db->get()->row_array();
        $batchid = $master['batchMasterID'];
        $TotalDispatchQty = $this->db->query("SELECT
	sum(IFNULL(transactionQTY,0)) as transactionQTY
FROM
	srp_erp_buyback_itemledger
WHERE
	buybackItemType = 1 AND batchID = $batchid AND documentCode= 'BBDPN'")->row_array();

        $TotalGRNQty = $this->db->query("SELECT
	IFNULL(sum(transactionQTY),0) as transactionQTY
FROM
	srp_erp_buyback_itemledger
WHERE
	batchID = $batchid
AND buybackItemType = 1
AND documentCode = 'BBGRN'")->row_array();

        $totalDispatchAmount = $this->db->query("SELECT
	IFNULL(sum(totalTransactionAmount),0) as totalTransactionAmount
FROM
	srp_erp_buyback_itemledger
WHERE
	batchID = $batchid
AND documentCode = 'BBDPN'")->row_array();

        $totalMortality = $this->db->query("SELECT
	IFNULL(sum(noOfBirds),0) as totmortality
FROM
	srp_erp_buyback_mortalitydetails detail
JOIN srp_erp_buyback_mortalitymaster mast ON detail.mortalityAutoID = mast.mortalityAutoID
WHERE
	mast.confirmedYN = 1 AND mast.batchMasterID =$batchid ")->row_array();

        $totalGrnAmount = $this->db->query("SELECT
	IFNULL(sum(totalTransactionAmount),0) as totalTransactionAmount
FROM
	srp_erp_buyback_itemledger
WHERE
	batchID =$batchid
AND documentCode = 'BBGRN'")->row_array();


        $unitcost = ($totalDispatchAmount['totalTransactionAmount'] - $totalGrnAmount['totalTransactionAmount']) / ($TotalDispatchQty['transactionQTY'] - ($totalMortality['totmortality'] + $TotalGRNQty['transactionQTY']));
//echo $TotalGRNQty['transactionQTY'];
        $data['grnAutoID'] = trim($this->input->post('grnAutoID'));
        $data['itemAutoID'] = trim($this->input->post('itemAutoID'));
        $data['itemSystemCode'] = $item_data['itemSystemCode'];
        $data['itemDescription'] = $item_data['itemDescription'];
        $data['itemFinanceCategory'] = $item_data['subcategoryID'];
        $data['itemFinanceCategorySub'] = $item_data['subSubCategoryID'];
        $data['financeCategory'] = $item_data['financeCategory'];
        $data['itemCategory'] = $item_data['mainCategory'];

        $data['costGLAutoID'] = $item_data['costGLAutoID'];
        $data['costSystemGLCode'] = $item_data['costSystemGLCode'];
        $data['costGLCode'] = $item_data['costGLCode'];
        $data['costGLDescription'] = $item_data['costDescription'];
        $data['costGLType'] = $item_data['costType'];

        $data['assetGLAutoID'] = $item_data['assteGLAutoID'];
        $data['assetSystemGLCode'] = $item_data['assteSystemGLCode'];
        $data['assetGLCode'] = $item_data['assteGLCode'];
        $data['assetGLDescription'] = $item_data['assteDescription'];
        $data['assetGLType'] = $item_data['assteType'];

        $data['revenueGLAutoID'] = $item_data['revanueGLAutoID'];
        $data['revenueSystemGLCode'] = $item_data['revanueSystemGLCode'];
        $data['revenueGLCode'] = $item_data['revanueGLCode'];
        $data['revenueGLDescription'] = $item_data['revanueDescription'];
        $data['revenueGLType'] = $item_data['revanueType'];

        $data['unitOfMeasure'] = trim($uom[0]);
        $data['unitOfMeasureID'] = trim($this->input->post('UnitOfMeasureID'));
        $data['defaultUOM'] = $item_data['defaultUnitOfMeasure'];
        $data['defaultUOMID'] = $item_data['defaultUnitOfMeasureID'];
        $data['conversionRateUOM'] = conversionRateUOM_id($data['unitOfMeasureID'], $data['defaultUOMID']);

        $data['noOfBirds'] = trim($this->input->post('noOfBirds'));
        $data['qty'] = trim($this->input->post('quantityRequested'));
        $data['unitCost'] = trim($unitcost);
        $data['unitTransferCost'] = trim($this->input->post('estimatedAmount'));

        $unitCostLocal = $data['unitCost'] / $master['companyLocalExchangeRate'];
        $unitTransferCostLocal = $data['unitTransferCost'] / $master['companyLocalExchangeRate'];
        $data['unitCostLocal'] = round($unitCostLocal, $master['companyLocalCurrencyDecimalPlaces']);
        $data['unitTransferCostLocal'] = round($unitTransferCostLocal, $master['companyLocalCurrencyDecimalPlaces']);
        $unitCostReporting = $data['unitCost'] / $master['companyReportingExchangeRate'];
        $unitTransferCostReporting = $data['unitTransferCost'] / $master['companyReportingExchangeRate'];
        $data['unitCostReporting'] = round($unitCostReporting, $master['companyReportingCurrencyDecimalPlaces']);
        $data['unitTransferCostReporting'] = round($unitTransferCostReporting, $master['companyReportingCurrencyDecimalPlaces']);
        $data['totalCost'] = ($data['noOfBirds'] * $data['unitCost']);
        $data['totalCostTransfer'] = ($data['qty'] * $data['unitTransferCost']);

        $data['comment'] = trim($this->input->post('comment'));
        $data['remarks'] = trim($this->input->post('remarks'));
        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedUserName'] = $this->common_data['current_user'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];

        if ($data['itemCategory'] == 'Inventory' or $data['itemCategory'] == 'Non Inventory') {
            $this->db->select('itemAutoID');
            $this->db->where('itemAutoID', trim($this->input->post('itemAutoID')));
            $this->db->where('wareHouseAutoID', $master['wareHouseAutoID']);
            $this->db->where('companyID', $this->common_data['company_data']['company_id']);
            $warehouseitems = $this->db->get('srp_erp_warehouseitems')->row_array();

            if (empty($warehouseitems)) {
                $data_arr = array(
                    'wareHouseAutoID' => $master['wareHouseAutoID'],
                    'wareHouseLocation' => $master['wareHouseLocation'],
                    'wareHouseDescription' => $master['wareHouseDescription'],
                    'itemAutoID' => $data['itemAutoID'],
                    'itemSystemCode' => $data['itemSystemCode'],
                    'itemDescription' => $data['itemDescription'],
                    'unitOfMeasureID' => $data['defaultUOMID'],
                    'unitOfMeasure' => $data['defaultUOM'],
                    'currentStock' => 0,
                    'companyID' => $this->common_data['company_data']['company_id'],
                    'companyCode' => $this->common_data['company_data']['company_code'],
                );
                $this->db->insert('srp_erp_warehouseitems', $data_arr);
            }
        }

        if (trim($grnDetailsID)) {
            $this->db->where('grnDetailsID', trim($grnDetailsID));
            $this->db->update('srp_erp_buyback_grndetails', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Good Receipt Note Detail : ' . $data['itemSystemCode'] . ' Update Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Good Receipt Note Detail : ' . $data['itemSystemCode'] . ' Updated Successfully.');

            }
        } else {
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_grndetails', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Good Receipt Note Detail Add Error ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Good Receipt Note Detail Added Successfully.');
            }
        }
    }


    function save_goodReceiptNote_approval()
    {
        $this->db->trans_start();
        $this->load->library('approvals');
        $system_code = trim($this->input->post('grnAutoID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('po_status'));
        $comments = trim($this->input->post('comments'));
        $company_id = $this->common_data['company_data']['company_id'];
        $transaction_tot = 0;
        $company_rpt_tot = 0;
        $supplier_cr_tot = 0;
        $company_loc_tot = 0;

        $approvals_status = $this->approvals->approve_document($system_code, $level_id, $status, $comments, 'BBGRN');

        if ($approvals_status == 1) {
            $this->db->select('*');
            $this->db->where('grnAutoID', $system_code);
            $this->db->where('companyID', $this->common_data['company_data']['company_id']);
            $this->db->from('srp_erp_buyback_grn');
            $master = $this->db->get()->row_array();

            $this->db->select('*');
            $this->db->where('grnAutoID', $system_code);
            $this->db->where('companyID', $this->common_data['company_data']['company_id']);
            $this->db->from('srp_erp_buyback_grndetails');
            $grvdetails = $this->db->get()->result_array();

            $item_arr = array();
            $itemledger_arr_buyback = array();
            $itemledger_arr = array();
            $po_arr = array();
            $wareHouseAutoID = $master['wareHouseAutoID'];
            $company_loc = 0;
            $company_rpt = 0;
            $supplier_cr = 0;
            $grvdetail_tot = 0;

            for ($i = 0; $i < count($grvdetails); $i++) {
                $company_loc = ($grvdetails[$i]['totalCost'] / $master['companyLocalExchangeRate']);
                $company_rpt = ($grvdetails[$i]['totalCost'] / $master['companyReportingExchangeRate']);

                $transaction_tot += $grvdetails[$i]['totalCost'];

                $item = fetch_item_data($grvdetails[$i]['itemAutoID']);
                if ($grvdetails[$i]['itemCategory'] == 'Inventory' or $grvdetails[$i]['itemCategory'] == 'Non Inventory') {
                    $itemAutoID = $grvdetails[$i]['itemAutoID'];
                    $grv_qty = $grvdetails[$i]['qty'] / $grvdetails[$i]['conversionRateUOM'];
                    $this->db->query("UPDATE srp_erp_warehouseitems SET currentStock = (currentStock + {$grv_qty})  WHERE wareHouseAutoID='{$wareHouseAutoID}' AND itemAutoID = '{$itemAutoID}' ");
                    $item_arr[$i]['itemAutoID'] = $grvdetails[$i]['itemAutoID'];
                    $item_arr[$i]['currentStock'] = ($item['currentStock'] + $grv_qty);
                    $item_arr[$i]['companyLocalWacAmount'] = round(((($item['currentStock'] * $item['companyLocalWacAmount']) + $company_loc) / $item_arr[$i]['currentStock']), $master['companyLocalCurrencyDecimalPlaces']);
                    $item_arr[$i]['companyReportingWacAmount'] = round(((($item['currentStock'] * $item['companyReportingWacAmount']) + $company_rpt) / $item_arr[$i]['currentStock']), $master['companyReportingCurrencyDecimalPlaces']);

                    $itemledger_arr_buyback[$i]['documentCode'] = $master['documentID'];
                    $itemledger_arr_buyback[$i]['documentAutoID'] = $master['grnAutoID'];
                    $itemledger_arr_buyback[$i]['documentSystemCode'] = $master['documentSystemCode'];
                    $itemledger_arr_buyback[$i]['documentDate'] = $master['documentDate'];
                    $itemledger_arr_buyback[$i]['batchID'] = $master['batchMasterID'];
                    $itemledger_arr_buyback[$i]['farmID'] = $master['farmID'];
                    $itemledger_arr_buyback[$i]['referenceNumber'] = $master['referenceNo'];
                    $itemledger_arr_buyback[$i]['companyFinanceYearID'] = $master['companyFinanceYearID'];
                    $itemledger_arr_buyback[$i]['companyFinanceYear'] = $master['companyFinanceYear'];
                    $itemledger_arr_buyback[$i]['FYBegin'] = $master['FYBegin'];
                    $itemledger_arr_buyback[$i]['FYEnd'] = $master['FYEnd'];
                    $itemledger_arr_buyback[$i]['FYPeriodDateFrom'] = $master['FYPeriodDateFrom'];
                    $itemledger_arr_buyback[$i]['FYPeriodDateTo'] = $master['FYPeriodDateTo'];
                    $itemledger_arr_buyback[$i]['wareHouseAutoID'] = NULL;
                    $itemledger_arr_buyback[$i]['wareHouseCode'] = NULL;
                    $itemledger_arr_buyback[$i]['wareHouseLocation'] = NULL;
                    $itemledger_arr_buyback[$i]['wareHouseDescription'] = NULL;
                    $itemledger_arr_buyback[$i]['itemAutoID'] = $grvdetails[$i]['itemAutoID'];
                    $itemledger_arr_buyback[$i]['itemSystemCode'] = $grvdetails[$i]['itemSystemCode'];
                    $itemledger_arr_buyback[$i]['buybackItemType'] = 4;
                    $itemledger_arr_buyback[$i]['itemDescription'] = $grvdetails[$i]['itemDescription'];
                    $itemledger_arr_buyback[$i]['defaultUOMID'] = $grvdetails[$i]['defaultUOMID'];
                    $itemledger_arr_buyback[$i]['defaultUOM'] = $grvdetails[$i]['defaultUOM'];
                    $itemledger_arr_buyback[$i]['transactionUOMID'] = $grvdetails[$i]['unitOfMeasureID'];
                    $itemledger_arr_buyback[$i]['transactionUOM'] = $grvdetails[$i]['unitOfMeasure'];
                    $itemledger_arr_buyback[$i]['transactionQTY'] = $grvdetails[$i]['qty'];
                    $itemledger_arr_buyback[$i]['convertionRate'] = $grvdetails[$i]['conversionRateUOM'];
                    $itemledger_arr_buyback[$i]['currentStock'] = $item_arr[$i]['currentStock'];
                    $itemledger_arr_buyback[$i]['noOfBirds'] = $grvdetails[$i]['noOfBirds'];

                    $itemledger_arr_buyback[$i]['expenseGLAutoID'] = $item['costGLAutoID'];
                    $itemledger_arr_buyback[$i]['expenseGLCode'] = $item['costGLCode'];
                    $itemledger_arr_buyback[$i]['expenseSystemGLCode'] = $item['costSystemGLCode'];
                    $itemledger_arr_buyback[$i]['expenseGLDescription'] = $item['costDescription'];
                    $itemledger_arr_buyback[$i]['expenseGLType'] = $item['costType'];
                    $itemledger_arr_buyback[$i]['revenueGLAutoID'] = $item['revanueGLAutoID'];
                    $itemledger_arr_buyback[$i]['revenueGLCode'] = $item['revanueGLCode'];
                    $itemledger_arr_buyback[$i]['revenueSystemGLCode'] = $item['revanueSystemGLCode'];
                    $itemledger_arr_buyback[$i]['revenueGLDescription'] = $item['revanueDescription'];
                    $itemledger_arr_buyback[$i]['revenueGLType'] = $item['revanueType'];
                    $itemledger_arr_buyback[$i]['assetGLAutoID'] = $item['assteGLAutoID'];
                    $itemledger_arr_buyback[$i]['assetGLCode'] = $item['assteGLCode'];
                    $itemledger_arr_buyback[$i]['assetSystemGLCode'] = $item['assteSystemGLCode'];
                    $itemledger_arr_buyback[$i]['assetGLDescription'] = $item['assteDescription'];
                    $itemledger_arr_buyback[$i]['assetGLType'] = $item['assteType'];
                    $itemledger_arr_buyback[$i]['transactionCurrencyDecimalPlaces'] = $master['transactionCurrencyDecimalPlaces'];

                    $itemledger_arr_buyback[$i]['unitTransactionAmount'] = $grvdetails[$i]['unitCost'];
                    $itemledger_arr_buyback[$i]['totalTransactionAmount'] = $itemledger_arr_buyback[$i]['unitTransactionAmount'] * $grvdetails[$i]['qty'];
                    $itemledger_arr_buyback[$i]['unitTransferAmountTransaction'] = $grvdetails[$i]['unitTransferCost'];
                    $itemledger_arr_buyback[$i]['totalTransferAmountTransaction'] = $grvdetails[$i]['totalCostTransfer'];

                    $itemledger_arr_buyback[$i]['unitLocalAmount'] = $grvdetails[$i]['unitCostLocal'];
                    $itemledger_arr_buyback[$i]['totalLocalAmount'] = $itemledger_arr_buyback[$i]['unitLocalAmount'] * $grvdetails[$i]['qty'];
                    $itemledger_arr_buyback[$i]['unitTransferAmountLocal'] = $grvdetails[$i]['unitTransferCostLocal'];
                    $itemledger_arr_buyback[$i]['totalTransferAmountLocal'] = $grvdetails[$i]['unitTransferCostLocal'] * $grvdetails[$i]['qty'];

                    $itemledger_arr_buyback[$i]['unitReportingAmount'] = $grvdetails[$i]['unitCostReporting'];
                    $itemledger_arr_buyback[$i]['totalReportingAmount'] = $itemledger_arr_buyback[$i]['unitReportingAmount'] * $grvdetails[$i]['qty'];
                    $itemledger_arr_buyback[$i]['unitTransferAmountReporting'] = $grvdetails[$i]['unitTransferCostReporting'];
                    $itemledger_arr_buyback[$i]['totalTranferAmountReporting'] = $itemledger_arr_buyback[$i]['unitTransferAmountReporting'] * $grvdetails[$i]['qty'];

                    //$ex_rate_wac = (1 / $master['companyLocalExchangeRate']);
                    //$itemledger_arr_buyback[$i]['transactionAmount'] = round((($item_detail[$i]['companyLocalWacAmount'] / $ex_rate_wac) * ($itemledger_arr_buyback[$i]['transactionQTY'] / $item_detail[$i]['conversionRateUOM'])), $itemledger_arr_buyback[$i]['transactionCurrencyDecimalPlaces']);
                    //$itemledger_arr_buyback[$i]['salesPrice'] = (($item_detail[$i]['transactionAmount'] / ($itemledger_arr_buyback[$i]['transactionQTY'] / $item_detail[$i]['conversionRateUOM'])) * -1);
                    $itemledger_arr_buyback[$i]['transactionCurrencyID'] = $master['transactionCurrencyID'];
                    $itemledger_arr_buyback[$i]['transactionCurrency'] = $master['transactionCurrency'];
                    $itemledger_arr_buyback[$i]['transactionExchangeRate'] = $master['transactionExchangeRate'];

                    $itemledger_arr_buyback[$i]['companyLocalCurrencyID'] = $master['companyLocalCurrencyID'];
                    $itemledger_arr_buyback[$i]['companyLocalCurrency'] = $master['companyLocalCurrency'];
                    $itemledger_arr_buyback[$i]['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                    $itemledger_arr_buyback[$i]['companyLocalCurrencyDecimalPlaces'] = $master['companyLocalCurrencyDecimalPlaces'];
                    //$itemledger_arr_buyback[$i]['companyLocalAmount'] = round(($itemledger_arr_buyback[$i]['transactionAmount'] / $itemledger_arr_buyback[$i]['companyLocalExchangeRate']), $itemledger_arr_buyback[$i]['companyLocalCurrencyDecimalPlaces']);
                    $itemledger_arr_buyback[$i]['companyLocalWacAmount'] = $item['companyLocalWacAmount'];
                    $itemledger_arr_buyback[$i]['companyReportingCurrencyID'] = $master['companyReportingCurrencyID'];
                    $itemledger_arr_buyback[$i]['companyReportingCurrency'] = $master['companyReportingCurrency'];
                    $itemledger_arr_buyback[$i]['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                    $itemledger_arr_buyback[$i]['companyReportingCurrencyDecimalPlaces'] = $master['companyReportingCurrencyDecimalPlaces'];
                    //$itemledger_arr_buyback[$i]['companyReportingAmount'] = round(($itemledger_arr_buyback[$i]['transactionAmount'] / $itemledger_arr_buyback[$i]['companyReportingExchangeRate']), $itemledger_arr_buyback[$i]['companyReportingCurrencyDecimalPlaces']);
                    $itemledger_arr_buyback[$i]['companyReportingWacAmount'] = $item['companyReportingWacAmount'];

                    $itemledger_arr_buyback[$i]['partyCurrencyID'] = $master['farmerCurrencyID'];
                    $itemledger_arr_buyback[$i]['partyCurrency'] = $master['farmerCurrency'];
                    $itemledger_arr_buyback[$i]['partyCurrencyExchangeRate'] = $master['farmerCurrencyExchangeRate'];
                    $itemledger_arr_buyback[$i]['partyCurrencyDecimalPlaces'] = $master['farmerCurrencyDecimalPlaces'];
                    //$itemledger_arr_buyback[$i]['partyCurrencyAmount'] = round(($itemledger_arr_buyback[$i]['transactionAmount'] / $itemledger_arr_buyback[$i]['partyCurrencyExchangeRate']), $itemledger_arr_buyback[$i]['partyCurrencyDecimalPlaces']);

                    $itemledger_arr_buyback[$i]['confirmedYN'] = $master['confirmedYN'];
                    $itemledger_arr_buyback[$i]['confirmedByEmpID'] = $master['confirmedByEmpID'];
                    $itemledger_arr_buyback[$i]['confirmedByName'] = $master['confirmedByName'];
                    $itemledger_arr_buyback[$i]['confirmedDate'] = $master['confirmedDate'];
                    $itemledger_arr_buyback[$i]['approvedYN'] = $master['approvedYN'];
                    $itemledger_arr_buyback[$i]['approvedDate'] = $master['approvedDate'];
                    $itemledger_arr_buyback[$i]['approvedbyEmpID'] = $master['approvedbyEmpID'];
                    $itemledger_arr_buyback[$i]['approvedbyEmpName'] = $master['approvedbyEmpName'];
                    $itemledger_arr_buyback[$i]['segmentID'] = $master['segmentID'];
                    $itemledger_arr_buyback[$i]['segmentCode'] = $master['segmentCode'];
                    $itemledger_arr_buyback[$i]['companyID'] = $master['companyID'];
                    $itemledger_arr_buyback[$i]['companyCode'] = $master['companyCode'];
                    $itemledger_arr_buyback[$i]['createdUserGroup'] = $master['createdUserGroup'];
                    $itemledger_arr_buyback[$i]['createdPCID'] = $master['createdPCID'];
                    $itemledger_arr_buyback[$i]['createdUserID'] = $master['createdUserID'];
                    $itemledger_arr_buyback[$i]['createdDateTime'] = $master['createdDateTime'];
                    $itemledger_arr_buyback[$i]['createdUserName'] = $master['createdUserName'];
                    $itemledger_arr_buyback[$i]['modifiedPCID'] = $master['modifiedPCID'];
                    $itemledger_arr_buyback[$i]['modifiedUserID'] = $master['modifiedUserID'];
                    $itemledger_arr_buyback[$i]['modifiedDateTime'] = $master['modifiedDateTime'];
                    $itemledger_arr_buyback[$i]['modifiedUserName'] = $master['modifiedUserName'];


                    $itemledger_arr[$i]['documentCode'] = $master['documentID'];
                    $itemledger_arr[$i]['documentID'] = $master['documentID'];
                    $itemledger_arr[$i]['documentAutoID'] = $master['grnAutoID'];
                    $itemledger_arr[$i]['documentSystemCode'] = $master['documentSystemCode'];
                    $itemledger_arr[$i]['documentDate'] = $master['documentDate'];
                    //$itemledger_arr[$i]['batchID'] = $master['batchMasterID'];
                    //$itemledger_arr[$i]['farmID'] = $master['farmID'];
                    $itemledger_arr[$i]['referenceNumber'] = $master['referenceNo'];
                    $itemledger_arr[$i]['companyFinanceYearID'] = $master['companyFinanceYearID'];
                    $itemledger_arr[$i]['companyFinanceYear'] = $master['companyFinanceYear'];
                    $itemledger_arr[$i]['FYBegin'] = $master['FYBegin'];
                    $itemledger_arr[$i]['FYEnd'] = $master['FYEnd'];
                    $itemledger_arr[$i]['FYPeriodDateFrom'] = $master['FYPeriodDateFrom'];
                    $itemledger_arr[$i]['FYPeriodDateTo'] = $master['FYPeriodDateTo'];
                    $itemledger_arr[$i]['wareHouseAutoID'] = $master['wareHouseAutoID'];
                    $itemledger_arr[$i]['wareHouseCode'] = $master['wareHouseCode'];
                    $itemledger_arr[$i]['wareHouseLocation'] = $master['wareHouseLocation'];
                    $itemledger_arr[$i]['wareHouseDescription'] = $master['wareHouseDescription'];
                    $itemledger_arr[$i]['itemAutoID'] = $grvdetails[$i]['itemAutoID'];
                    $itemledger_arr[$i]['itemSystemCode'] = $grvdetails[$i]['itemSystemCode'];
                    //$itemledger_arr[$i]['buybackItemType'] = 4;
                    $itemledger_arr[$i]['itemDescription'] = $grvdetails[$i]['itemDescription'];
                    $itemledger_arr[$i]['defaultUOMID'] = $grvdetails[$i]['defaultUOMID'];
                    $itemledger_arr[$i]['defaultUOM'] = $grvdetails[$i]['defaultUOM'];
                    $itemledger_arr[$i]['transactionUOMID'] = $grvdetails[$i]['unitOfMeasureID'];
                    $itemledger_arr[$i]['transactionUOM'] = $grvdetails[$i]['unitOfMeasure'];
                    $itemledger_arr[$i]['transactionQTY'] = $grvdetails[$i]['qty'];
                    $itemledger_arr[$i]['convertionRate'] = $grvdetails[$i]['conversionRateUOM'];
                    $itemledger_arr[$i]['currentStock'] = $item_arr[$i]['currentStock'];
                    //$itemledger_arr[$i]['noOfBirds'] = $grvdetails[$i]['noOfBirds'];
                    $itemledger_arr[$i]['PLGLAutoID'] = $item['costGLAutoID'];
                    $itemledger_arr[$i]['PLSystemGLCode'] = $item['costSystemGLCode'];
                    $itemledger_arr[$i]['PLGLCode'] = $item['costGLCode'];
                    $itemledger_arr[$i]['PLDescription'] = $item['costDescription'];
                    $itemledger_arr[$i]['PLType'] = $item['costType'];
                    $itemledger_arr[$i]['BLGLAutoID'] = $item['assteGLAutoID'];
                    $itemledger_arr[$i]['BLSystemGLCode'] = $item['assteSystemGLCode'];
                    $itemledger_arr[$i]['BLGLCode'] = $item['assteGLCode'];
                    $itemledger_arr[$i]['BLDescription'] = $item['assteDescription'];
                    $itemledger_arr[$i]['BLType'] = $item['assteType'];
                    $itemledger_arr[$i]['expenseGLAutoID'] = $item['costGLAutoID'];
                    $itemledger_arr[$i]['expenseGLCode'] = $item['costGLCode'];
                    $itemledger_arr[$i]['expenseSystemGLCode'] = $item['costSystemGLCode'];
                    $itemledger_arr[$i]['expenseGLDescription'] = $item['costDescription'];
                    $itemledger_arr[$i]['expenseGLType'] = $item['costType'];
                    $itemledger_arr[$i]['revenueGLAutoID'] = $item['revanueGLAutoID'];
                    $itemledger_arr[$i]['revenueGLCode'] = $item['revanueGLCode'];
                    $itemledger_arr[$i]['revenueSystemGLCode'] = $item['revanueSystemGLCode'];
                    $itemledger_arr[$i]['revenueGLDescription'] = $item['revanueDescription'];
                    $itemledger_arr[$i]['revenueGLType'] = $item['revanueType'];
                    $itemledger_arr[$i]['assetGLAutoID'] = $item['assteGLAutoID'];
                    $itemledger_arr[$i]['assetGLCode'] = $item['assteGLCode'];
                    $itemledger_arr[$i]['assetSystemGLCode'] = $item['assteSystemGLCode'];
                    $itemledger_arr[$i]['assetGLDescription'] = $item['assteDescription'];
                    $itemledger_arr[$i]['assetGLType'] = $item['assteType'];
                    $itemledger_arr[$i]['transactionCurrencyDecimalPlaces'] = $master['transactionCurrencyDecimalPlaces'];

                    /*$itemledger_arr[$i]['unitTransactionAmount'] = $grvdetails[$i]['unitCost'];
                    $itemledger_arr[$i]['totalTransactionAmount'] = $itemledger_arr[$i]['unitTransactionAmount'] * $grvdetails[$i]['qty'];
                    $itemledger_arr[$i]['unitTransferAmountTransaction'] = $grvdetails[$i]['unitTransferCost'];
                    $itemledger_arr[$i]['totalTransferAmountTransaction'] = $grvdetails[$i]['totalCostTransfer'];

                    $itemledger_arr[$i]['unitLocalAmount'] = $grvdetails[$i]['unitCostLocal'];
                    $itemledger_arr[$i]['totalLocalAmount'] = $itemledger_arr[$i]['unitLocalAmount'] * $grvdetails[$i]['qty'];
                    $itemledger_arr[$i]['unitTransferAmountLocal'] = $grvdetails[$i]['unitTransferCostLocal'];
                    $itemledger_arr[$i]['totalTransferAmountLocal'] = $grvdetails[$i]['unitTransferCostLocal'] * $grvdetails[$i]['qty'];

                    $itemledger_arr[$i]['unitReportingAmount'] = $grvdetails[$i]['unitCostReporting'];
                    $itemledger_arr[$i]['totalReportingAmount'] =$itemledger_arr[$i]['unitReportingAmount'] * $grvdetails[$i]['qty'];
                    $itemledger_arr[$i]['unitTransferAmountReporting'] = $grvdetails[$i]['unitTransferCostReporting'];
                    $itemledger_arr[$i]['totalTranferAmountReporting'] = $itemledger_arr[$i]['unitTransferAmountReporting'] * $grvdetails[$i]['qty'];*/

                    //$ex_rate_wac = (1 / $master['companyLocalExchangeRate']);
                    $itemledger_arr[$i]['transactionAmount'] = round(($grvdetails[$i]['unitCost'] * $grvdetails[$i]['qty']), $itemledger_arr[$i]['transactionCurrencyDecimalPlaces']);
                    //$itemledger_arr[$i]['salesPrice'] = (($item_detail[$i]['transactionAmount'] / ($itemledger_arr[$i]['transactionQTY'] / $item_detail[$i]['conversionRateUOM'])) * -1);
                    $itemledger_arr[$i]['transactionCurrencyID'] = $master['transactionCurrencyID'];
                    $itemledger_arr[$i]['transactionCurrency'] = $master['transactionCurrency'];
                    $itemledger_arr[$i]['transactionExchangeRate'] = $master['transactionExchangeRate'];

                    $itemledger_arr[$i]['companyLocalCurrencyID'] = $master['companyLocalCurrencyID'];
                    $itemledger_arr[$i]['companyLocalCurrency'] = $master['companyLocalCurrency'];
                    $itemledger_arr[$i]['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                    $itemledger_arr[$i]['companyLocalCurrencyDecimalPlaces'] = $master['companyLocalCurrencyDecimalPlaces'];
                    $itemledger_arr[$i]['companyLocalAmount'] = round(($itemledger_arr[$i]['transactionAmount'] / $itemledger_arr[$i]['companyLocalExchangeRate']), $itemledger_arr[$i]['companyLocalCurrencyDecimalPlaces']);
                    $itemledger_arr[$i]['companyLocalWacAmount'] = $item['companyLocalWacAmount'];
                    $itemledger_arr[$i]['companyReportingCurrencyID'] = $master['companyReportingCurrencyID'];
                    $itemledger_arr[$i]['companyReportingCurrency'] = $master['companyReportingCurrency'];
                    $itemledger_arr[$i]['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                    $itemledger_arr[$i]['companyReportingCurrencyDecimalPlaces'] = $master['companyReportingCurrencyDecimalPlaces'];
                    $itemledger_arr[$i]['companyReportingAmount'] = round(($itemledger_arr[$i]['transactionAmount'] / $itemledger_arr[$i]['companyReportingExchangeRate']), $itemledger_arr[$i]['companyReportingCurrencyDecimalPlaces']);
                    $itemledger_arr[$i]['companyReportingWacAmount'] = $item['companyReportingWacAmount'];

                    $itemledger_arr[$i]['partyCurrencyID'] = $master['farmerCurrencyID'];
                    $itemledger_arr[$i]['partyCurrency'] = $master['farmerCurrency'];
                    $itemledger_arr[$i]['partyCurrencyExchangeRate'] = $master['farmerCurrencyExchangeRate'];
                    $itemledger_arr[$i]['partyCurrencyDecimalPlaces'] = $master['farmerCurrencyDecimalPlaces'];
                    //$itemledger_arr[$i]['partyCurrencyAmount'] = round(($itemledger_arr[$i]['transactionAmount'] / $itemledger_arr[$i]['partyCurrencyExchangeRate']), $itemledger_arr[$i]['partyCurrencyDecimalPlaces']);

                    $itemledger_arr[$i]['confirmedYN'] = $master['confirmedYN'];
                    $itemledger_arr[$i]['confirmedByEmpID'] = $master['confirmedByEmpID'];
                    $itemledger_arr[$i]['confirmedByName'] = $master['confirmedByName'];
                    $itemledger_arr[$i]['confirmedDate'] = $master['confirmedDate'];
                    $itemledger_arr[$i]['approvedYN'] = $master['approvedYN'];
                    $itemledger_arr[$i]['approvedDate'] = $master['approvedDate'];
                    $itemledger_arr[$i]['approvedbyEmpID'] = $master['approvedbyEmpID'];
                    $itemledger_arr[$i]['approvedbyEmpName'] = $master['approvedbyEmpName'];
                    $itemledger_arr[$i]['segmentID'] = $master['segmentID'];
                    $itemledger_arr[$i]['segmentCode'] = $master['segmentCode'];
                    $itemledger_arr[$i]['companyID'] = $master['companyID'];
                    $itemledger_arr[$i]['companyCode'] = $master['companyCode'];
                    $itemledger_arr[$i]['createdUserGroup'] = $master['createdUserGroup'];
                    $itemledger_arr[$i]['createdPCID'] = $master['createdPCID'];
                    $itemledger_arr[$i]['createdUserID'] = $master['createdUserID'];
                    $itemledger_arr[$i]['createdDateTime'] = $master['createdDateTime'];
                    $itemledger_arr[$i]['createdUserName'] = $master['createdUserName'];
                    $itemledger_arr[$i]['modifiedPCID'] = $master['modifiedPCID'];
                    $itemledger_arr[$i]['modifiedUserID'] = $master['modifiedUserID'];
                    $itemledger_arr[$i]['modifiedDateTime'] = $master['modifiedDateTime'];
                    $itemledger_arr[$i]['modifiedUserName'] = $master['modifiedUserName'];
                }
            }

            if (!empty($item_arr)) {
                $item_arr = array_values($item_arr);
                $this->db->update_batch('srp_erp_itemmaster', $item_arr, 'itemAutoID');
            }

            if (!empty($itemledger_arr_buyback)) {
                $itemledger_arr_buyback = array_values($itemledger_arr_buyback);
                $this->db->insert_batch('srp_erp_buyback_itemledger', $itemledger_arr_buyback);
            }
            if (!empty($itemledger_arr)) {
                $itemledger_arr = array_values($itemledger_arr);
                $this->db->insert_batch('srp_erp_itemledger', $itemledger_arr);
            }

            $double_entry = $this->fetch_double_entry_buyback_goodReceiptNote($system_code, 'BBGRN');

            for ($i = 0; $i < count($double_entry['gl_detail']); $i++) {
                $generalledger_arr[$i]['documentMasterAutoID'] = $double_entry['master_data']['grnAutoID'];
                $generalledger_arr[$i]['documentCode'] = $double_entry['code'];
                $generalledger_arr[$i]['documentSystemCode'] = $double_entry['master_data']['documentSystemCode'];
                $generalledger_arr[$i]['documentDate'] = $double_entry['master_data']['documentDate'];
                $generalledger_arr[$i]['documentType'] = NULL;
                $generalledger_arr[$i]['documentYear'] = $double_entry['master_data']['documentDate'];
                $generalledger_arr[$i]['documentMonth'] = date("m", strtotime($double_entry['master_data']['documentDate']));
                $generalledger_arr[$i]['documentNarration'] = $double_entry['master_data']['referenceNo'];
                $generalledger_arr[$i]['chequeNumber'] = NULL;
                $generalledger_arr[$i]['transactionCurrencyID'] = $double_entry['master_data']['transactionCurrencyID'];
                $generalledger_arr[$i]['transactionCurrency'] = $double_entry['master_data']['transactionCurrency'];
                $generalledger_arr[$i]['transactionExchangeRate'] = $double_entry['gl_detail'][$i]['transactionExchangeRate'];
                $generalledger_arr[$i]['transactionCurrencyDecimalPlaces'] = $double_entry['master_data']['transactionCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['companyLocalCurrencyID'] = $double_entry['master_data']['companyLocalCurrencyID'];
                $generalledger_arr[$i]['companyLocalCurrency'] = $double_entry['master_data']['companyLocalCurrency'];
                $generalledger_arr[$i]['companyLocalExchangeRate'] = $double_entry['gl_detail'][$i]['companyLocalExchangeRate'];
                $generalledger_arr[$i]['companyLocalCurrencyDecimalPlaces'] = $double_entry['master_data']['companyLocalCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['companyReportingCurrencyID'] = $double_entry['master_data']['companyReportingCurrencyID'];
                $generalledger_arr[$i]['companyReportingCurrency'] = $double_entry['master_data']['companyReportingCurrency'];
                $generalledger_arr[$i]['companyReportingExchangeRate'] = $double_entry['gl_detail'][$i]['companyReportingExchangeRate'];
                $generalledger_arr[$i]['companyReportingCurrencyDecimalPlaces'] = $double_entry['master_data']['companyReportingCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['partyContractID'] = '';
                $generalledger_arr[$i]['partyType'] = $double_entry['gl_detail'][$i]['partyType'];
                $generalledger_arr[$i]['partyAutoID'] = $double_entry['gl_detail'][$i]['partyAutoID'];
                $generalledger_arr[$i]['partySystemCode'] = $double_entry['gl_detail'][$i]['partySystemCode'];
                $generalledger_arr[$i]['partyName'] = $double_entry['gl_detail'][$i]['partyName'];
                $generalledger_arr[$i]['partyCurrencyID'] = $double_entry['gl_detail'][$i]['partyCurrencyID'];
                $generalledger_arr[$i]['partyCurrency'] = $double_entry['gl_detail'][$i]['partyCurrency'];
                $generalledger_arr[$i]['partyExchangeRate'] = $double_entry['gl_detail'][$i]['partyExchangeRate'];
                $generalledger_arr[$i]['partyCurrencyDecimalPlaces'] = $double_entry['gl_detail'][$i]['partyCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['confirmedByEmpID'] = $double_entry['master_data']['confirmedByEmpID'];
                $generalledger_arr[$i]['confirmedByName'] = $double_entry['master_data']['confirmedByName'];
                $generalledger_arr[$i]['confirmedDate'] = $double_entry['master_data']['confirmedDate'];
                $generalledger_arr[$i]['approvedDate'] = $double_entry['master_data']['approvedDate'];
                $generalledger_arr[$i]['approvedbyEmpID'] = $double_entry['master_data']['approvedbyEmpID'];
                $generalledger_arr[$i]['approvedbyEmpName'] = $double_entry['master_data']['approvedbyEmpName'];
                $generalledger_arr[$i]['companyID'] = $double_entry['master_data']['companyID'];
                $generalledger_arr[$i]['companyCode'] = $double_entry['master_data']['companyCode'];
                $amount = $double_entry['gl_detail'][$i]['gl_dr'];
                if ($double_entry['gl_detail'][$i]['amount_type'] == 'cr') {
                    $amount = ($double_entry['gl_detail'][$i]['gl_cr'] * -1);
                }
                $generalledger_arr[$i]['transactionAmount'] = round($amount, $generalledger_arr[$i]['transactionCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['companyLocalAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['companyLocalExchangeRate']), $generalledger_arr[$i]['companyLocalCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['companyReportingAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['companyReportingExchangeRate']), $generalledger_arr[$i]['companyReportingCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['partyCurrencyAmount'] = NULL;
                $generalledger_arr[$i]['amount_type'] = $double_entry['gl_detail'][$i]['amount_type'];
                $generalledger_arr[$i]['documentDetailAutoID'] = $double_entry['gl_detail'][$i]['auto_id'];
                $generalledger_arr[$i]['GLAutoID'] = $double_entry['gl_detail'][$i]['gl_auto_id'];
                $generalledger_arr[$i]['systemGLCode'] = $double_entry['gl_detail'][$i]['gl_code'];
                $generalledger_arr[$i]['GLCode'] = $double_entry['gl_detail'][$i]['secondary'];
                $generalledger_arr[$i]['GLDescription'] = $double_entry['gl_detail'][$i]['gl_desc'];
                $generalledger_arr[$i]['GLType'] = $double_entry['gl_detail'][$i]['gl_type'];
                $generalledger_arr[$i]['segmentID'] = $double_entry['gl_detail'][$i]['segment_id'];
                $generalledger_arr[$i]['segmentCode'] = $double_entry['gl_detail'][$i]['segment'];
                $generalledger_arr[$i]['projectID'] = isset($double_entry['gl_detail'][$i]['projectID']) ? $double_entry['gl_detail'][$i]['projectID'] : null;
                $generalledger_arr[$i]['projectExchangeRate'] = isset($double_entry['gl_detail'][$i]['projectExchangeRate']) ? $double_entry['gl_detail'][$i]['projectExchangeRate'] : null;
                $generalledger_arr[$i]['subLedgerType'] = $double_entry['gl_detail'][$i]['subLedgerType'];
                $generalledger_arr[$i]['subLedgerDesc'] = $double_entry['gl_detail'][$i]['subLedgerDesc'];
                $generalledger_arr[$i]['isAddon'] = $double_entry['gl_detail'][$i]['isAddon'];
                $generalledger_arr[$i]['createdUserGroup'] = $this->common_data['user_group'];
                $generalledger_arr[$i]['createdPCID'] = $this->common_data['current_pc'];
                $generalledger_arr[$i]['createdUserID'] = $this->common_data['current_userID'];
                $generalledger_arr[$i]['createdDateTime'] = $this->common_data['current_date'];
                $generalledger_arr[$i]['createdUserName'] = $this->common_data['current_user'];
                $generalledger_arr[$i]['modifiedPCID'] = $this->common_data['current_pc'];
                $generalledger_arr[$i]['modifiedUserID'] = $this->common_data['current_userID'];
                $generalledger_arr[$i]['modifiedDateTime'] = $this->common_data['current_date'];
                $generalledger_arr[$i]['modifiedUserName'] = $this->common_data['current_user'];
            }
            $this->db->insert_batch('srp_erp_generalledger', $generalledger_arr);

            $data['approvedYN'] = $status;
            $data['approvedbyEmpID'] = $this->common_data['current_userID'];
            $data['approvedbyEmpName'] = $this->common_data['current_user'];
            $data['approvedDate'] = $this->common_data['current_date'];
            $this->db->where('grnAutoID', $system_code);
            $this->db->update('srp_erp_buyback_grn', $data);
            $this->session->set_flashdata('s', 'Good Receipt Note Approved Successfully.');
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

    function save_batchClosing_approval()
    {
        $this->db->trans_start();
        $this->load->library('approvals');
        $system_code = trim($this->input->post('batchMasterID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('po_status'));
        $comments = trim($this->input->post('comments'));
        $company_id = $this->common_data['company_data']['company_id'];
        $approvals_status = $this->approvals->approve_document($system_code, $level_id, $status, $comments, 'BBBC');

        if ($approvals_status == 1) {

            $this->db->select('*');
            $this->db->where('batchMasterID', $system_code);
            $this->db->where('companyID', $this->common_data['company_data']['company_id']);
            $this->db->from('srp_erp_buyback_batch');
            $master = $this->db->get()->row_array();

            $double_entry = $this->fetch_double_entry_buyback_batchClosing($system_code, 'BBBC');

            for ($i = 0; $i < count($double_entry['gl_detail']); $i++) {
                $generalledger_arr[$i]['documentMasterAutoID'] = $double_entry['master_data']['batchMasterID'];
                $generalledger_arr[$i]['documentCode'] = $double_entry['code'];
                $generalledger_arr[$i]['documentSystemCode'] = $double_entry['master_data']['batchCode'];
                $generalledger_arr[$i]['documentDate'] = $double_entry['master_data']['closedDate'];
                $generalledger_arr[$i]['documentType'] = NULL;
                $generalledger_arr[$i]['documentYear'] = $double_entry['master_data']['closedDate'];
                $generalledger_arr[$i]['documentMonth'] = date("m", strtotime($double_entry['master_data']['closedDate']));
                $generalledger_arr[$i]['documentNarration'] = NULL;
                $generalledger_arr[$i]['chequeNumber'] = NULL;
                $generalledger_arr[$i]['transactionCurrencyID'] = $double_entry['farmer']['farmerCurrencyID'];
                $generalledger_arr[$i]['transactionCurrency'] = fetch_currency_code($double_entry['farmer']['farmerCurrencyID']);
                $generalledger_arr[$i]['transactionExchangeRate'] = 1;
                $generalledger_arr[$i]['transactionCurrencyDecimalPlaces'] = fetch_currency_desimal_by_id($double_entry['farmer']['farmerCurrencyID']);
                $generalledger_arr[$i]['companyLocalCurrencyID'] = $this->common_data['company_data']['company_default_currencyID'];
                $generalledger_arr[$i]['companyLocalCurrency'] = $this->common_data['company_data']['company_default_currency'];
                $default_currency = currency_conversionID($generalledger_arr[$i]['transactionCurrencyID'], $generalledger_arr[$i]['companyLocalCurrencyID']);
                $generalledger_arr[$i]['companyLocalExchangeRate'] = $default_currency['conversion'];
                $generalledger_arr[$i]['companyLocalCurrencyDecimalPlaces'] = $default_currency['DecimalPlaces'];
                $generalledger_arr[$i]['companyReportingCurrencyID'] = $this->common_data['company_data']['company_reporting_currencyID'];
                $generalledger_arr[$i]['companyReportingCurrency'] = $this->common_data['company_data']['company_reporting_currency'];
                $reporting_currency = currency_conversionID($generalledger_arr[$i]['transactionCurrencyID'], $generalledger_arr[$i]['companyReportingCurrencyID']);
                $generalledger_arr[$i]['companyReportingExchangeRate'] = $reporting_currency['conversion'];
                $generalledger_arr[$i]['companyReportingCurrencyDecimalPlaces'] = $reporting_currency['DecimalPlaces'];
                $generalledger_arr[$i]['partyContractID'] = '';
                $generalledger_arr[$i]['partyType'] = $double_entry['gl_detail'][$i]['partyType'];
                $generalledger_arr[$i]['partyAutoID'] = $double_entry['gl_detail'][$i]['partyAutoID'];
                $generalledger_arr[$i]['partySystemCode'] = $double_entry['gl_detail'][$i]['partySystemCode'];
                $generalledger_arr[$i]['partyName'] = $double_entry['gl_detail'][$i]['partyName'];
                $generalledger_arr[$i]['partyCurrencyID'] = $double_entry['gl_detail'][$i]['partyCurrencyID'];
                $generalledger_arr[$i]['partyCurrency'] = $double_entry['gl_detail'][$i]['partyCurrency'];
                $generalledger_arr[$i]['partyExchangeRate'] = $double_entry['gl_detail'][$i]['partyExchangeRate'];
                $generalledger_arr[$i]['partyCurrencyDecimalPlaces'] = $double_entry['gl_detail'][$i]['partyCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['confirmedByEmpID'] = $double_entry['master_data']['confirmedByEmpID'];
                $generalledger_arr[$i]['confirmedByName'] = $double_entry['master_data']['confirmedByName'];
                $generalledger_arr[$i]['confirmedDate'] = $double_entry['master_data']['confirmedDate'];
                $generalledger_arr[$i]['approvedDate'] = $double_entry['master_data']['approvedDate'];
                $generalledger_arr[$i]['approvedbyEmpID'] = $double_entry['master_data']['approvedbyEmpID'];
                $generalledger_arr[$i]['approvedbyEmpName'] = $double_entry['master_data']['approvedbyEmpName'];
                $generalledger_arr[$i]['companyID'] = $double_entry['master_data']['companyID'];
                $generalledger_arr[$i]['companyCode'] = NULL;
                $amount = $double_entry['gl_detail'][$i]['gl_dr'];
                if ($double_entry['gl_detail'][$i]['amount_type'] == 'cr') {
                    $amount = ($double_entry['gl_detail'][$i]['gl_cr'] * -1);
                }
                $generalledger_arr[$i]['transactionAmount'] = round($amount, $generalledger_arr[$i]['transactionCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['companyLocalAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['companyLocalExchangeRate']), $generalledger_arr[$i]['companyLocalCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['companyReportingAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['companyReportingExchangeRate']), $generalledger_arr[$i]['companyReportingCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['partyCurrencyAmount'] = NULL;
                $generalledger_arr[$i]['amount_type'] = $double_entry['gl_detail'][$i]['amount_type'];
                $generalledger_arr[$i]['documentDetailAutoID'] = $double_entry['gl_detail'][$i]['auto_id'];
                $generalledger_arr[$i]['GLAutoID'] = $double_entry['gl_detail'][$i]['gl_auto_id'];
                $generalledger_arr[$i]['systemGLCode'] = $double_entry['gl_detail'][$i]['gl_code'];
                $generalledger_arr[$i]['GLCode'] = $double_entry['gl_detail'][$i]['secondary'];
                $generalledger_arr[$i]['GLDescription'] = $double_entry['gl_detail'][$i]['gl_desc'];
                $generalledger_arr[$i]['GLType'] = $double_entry['gl_detail'][$i]['gl_type'];
                $generalledger_arr[$i]['segmentID'] = $double_entry['gl_detail'][$i]['segment_id'];
                $generalledger_arr[$i]['segmentCode'] = $double_entry['gl_detail'][$i]['segment'];
                $generalledger_arr[$i]['projectID'] = isset($double_entry['gl_detail'][$i]['projectID']) ? $double_entry['gl_detail'][$i]['projectID'] : null;
                $generalledger_arr[$i]['projectExchangeRate'] = isset($double_entry['gl_detail'][$i]['projectExchangeRate']) ? $double_entry['gl_detail'][$i]['projectExchangeRate'] : null;
                $generalledger_arr[$i]['subLedgerType'] = $double_entry['gl_detail'][$i]['subLedgerType'];
                $generalledger_arr[$i]['subLedgerDesc'] = $double_entry['gl_detail'][$i]['subLedgerDesc'];
                $generalledger_arr[$i]['isAddon'] = $double_entry['gl_detail'][$i]['isAddon'];
                $generalledger_arr[$i]['createdUserGroup'] = $this->common_data['user_group'];
                $generalledger_arr[$i]['createdPCID'] = $this->common_data['current_pc'];
                $generalledger_arr[$i]['createdUserID'] = $this->common_data['current_userID'];
                $generalledger_arr[$i]['createdDateTime'] = $this->common_data['current_date'];
                $generalledger_arr[$i]['createdUserName'] = $this->common_data['current_user'];
                $generalledger_arr[$i]['modifiedPCID'] = $this->common_data['current_pc'];
                $generalledger_arr[$i]['modifiedUserID'] = $this->common_data['current_userID'];
                $generalledger_arr[$i]['modifiedDateTime'] = $this->common_data['current_date'];
                $generalledger_arr[$i]['modifiedUserName'] = $this->common_data['current_user'];
            }
            $this->db->insert_batch('srp_erp_generalledger', $generalledger_arr);

            $dispatchNoteCost = $this->db->query("SELECT sum(totalTransferAmountTransaction) AS totalCost,sum(totalTransactionAmount) AS totalActualCost,(SUM(totalTransferAmountTransaction) - SUM(totalTransactionAmount)) AS indirectProfit FROM srp_erp_buyback_itemledger WHERE batchID = $system_code AND documentCode = 'BBDPN'")->row_array();

            $goodReciptCost = $this->db->query("SELECT totalTransferAmountTransaction as grnTotal FROM srp_erp_buyback_itemledger WHERE batchID = $system_code AND documentCode = 'BBGRN'")->row_array();

            $paymenVoucher = $this->db->query("SELECT transactionAmount as expense FROM srp_erp_buyback_paymentvoucherdetail WHERE BatchID = $system_code AND (type = 'Advance' OR type = 'Expense')")->row_array();

            $batchPayableAmount = 0;
            $batchPayableAmount = ($goodReciptCost['grnTotal'] - ($dispatchNoteCost['totalCost'] + $paymenVoucher['expense']));

            $data['approvedYN'] = $status;
            $data['batchPayableAmount'] = $batchPayableAmount;
            $data['isclosed'] = 1;
            $data['approvedbyEmpID'] = $this->common_data['current_userID'];
            $data['approvedbyEmpName'] = $this->common_data['current_user'];
            $data['approvedDate'] = $this->common_data['current_date'];
            $this->db->where('batchMasterID', $system_code);
            $this->db->update('srp_erp_buyback_batch', $data);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'save batchClosing_approval failed');
        } else {
            $this->db->trans_commit();
            return array('s', 'save_batchClosing_approvalSuccessfully.');
        }
    }

    function save_fieldChart_header()
    {
        $this->db->trans_start();
        $feedScheduleID = trim($this->input->post('feedScheduleID'));

        $data['feedTypeID'] = trim($this->input->post('feedTypeID'));
        $data['startDay'] = trim($this->input->post('startDay'));
        $data['endDay'] = trim($this->input->post('endDay'));
        $data['uomID'] = trim($this->input->post('uomID'));
        $data['feedAmount'] = trim($this->input->post('feedAmount'));

        if ($feedScheduleID) {
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];

            $this->db->where('feedScheduleID', $feedScheduleID);
            $this->db->update('srp_erp_buyback_feedschedulemaster', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Feed Chart Header Update ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Feed Chart Header Updated Successfully.');
            }
        } else {
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_feedschedulemaster', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Feed Chart Header Creating' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Feed Chart Header is created successfully.');

            }
        }
    }

    function save_fieldChart_detail_multiple()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $feedPer_day = $this->input->post('feedPer_day');
        $agedays = $this->input->post('age_day');
        $uomID = $this->input->post('uomID');
        $bodyWeight_min = $this->input->post('bodyWeight_min');
        $bodyWeight_max = $this->input->post('bodyWeight_max');
        $fcr_min = $this->input->post('fcr_min');
        $fcr_max = $this->input->post('fcr_max');

        $this->db->trans_start();

        foreach ($agedays as $key => $ageday) {

            $totalAmount = 0;
            $lastTotal = $this->db->query("SELECT perDayFeed,totalAmount FROM srp_erp_buyback_feedscheduledetail WHERE companyID = $companyID ORDER BY feedscheduledetailID DESC")->row_array();

            if (!empty($lastTotal)) {
                $totalAmount = $lastTotal['totalAmount'] + $feedPer_day[$key];
            } else {
                $totalAmount = $feedPer_day[$key];
            }

            $data['age'] = $ageday;
            $data['uomID'] = $uomID[$key];
            $data['perDayFeed'] = $feedPer_day[$key];
            $data['totalAmount'] = $totalAmount;
            $data['minBodyWeight'] = $bodyWeight_min[$key];
            $data['maxBodyWeight'] = $bodyWeight_max[$key];
            $data['minFCR'] = $fcr_min[$key];
            $data['maxFCR'] = $fcr_max[$key];
            $data['companyID'] = $companyID;
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_feedscheduledetail', $data);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Mortality Details :  Save Failed ' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Mortality Details :  Added Successfully.');
        }
    }

    function delete_feedChart_header()
    {
        $this->db->delete('srp_erp_buyback_feedschedulemaster', array('feedScheduleID' => trim($this->input->post('feedScheduleID'))));
        return true;
    }

    function delete_feedChart_detail()
    {
        $this->db->delete('srp_erp_buyback_feedscheduledetail', array('feedscheduledetailID' => trim($this->input->post('feedscheduledetailID'))));
        return true;
    }

    function edit_feedChart_header()
    {
        $this->db->select('*');
        $this->db->from('srp_erp_buyback_feedschedulemaster');
        $this->db->where('feedScheduleID', trim($this->input->post('feedScheduleID')));
        return $this->db->get()->row_array();
    }

    function edit_feedChart_detail()
    {
        $this->db->select('*');
        $this->db->from('srp_erp_buyback_feedscheduledetail');
        $this->db->where('feedscheduledetailID', trim($this->input->post('feedscheduledetailID')));
        return $this->db->get()->row_array();
    }

    function update_fieldChart_detail()
    {
        $this->db->trans_start();
        $feedscheduledetailID = trim($this->input->post('feedscheduledetailID'));

        $data['age'] = trim($this->input->post('age_day'));
        $data['uomID'] = trim($this->input->post('uomID'));
        $data['perDayFeed'] = trim($this->input->post('feedPer_day'));
        $data['minBodyWeight'] = trim($this->input->post('bodyWeight_min'));
        $data['maxBodyWeight'] = trim($this->input->post('bodyWeight_max'));
        $data['minFCR'] = trim($this->input->post('fcr_min'));
        $data['maxFCR'] = trim($this->input->post('fcr_max'));

        if ($feedscheduledetailID) {
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];

            $this->db->where('feedscheduledetailID', $feedscheduledetailID);
            $this->db->update('srp_erp_buyback_feedscheduledetail', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Feed Chart Detail Update ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Feed Chart Detail Updated Successfully.');
            }
        } else {
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_feedscheduledetail', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Feed Chart Detail Creating' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Feed Chart Detail is created successfully.');

            }
        }
    }

    function save_feedType_header()
    {
        $this->db->trans_start();
        $companyID = $this->common_data['company_data']['company_id'];
        $buybackFeedtypeID = trim($this->input->post('buybackFeedtypeID'));
        $data['description'] = trim($this->input->post('description'));

        if ($buybackFeedtypeID) {
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];

            $this->db->where('buybackFeedtypeID', $buybackFeedtypeID);
            $this->db->update('srp_erp_buyback_feedtypes', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Feed Type Update ' . $this->db->_error_message());

            } else {
                $this->db->trans_commit();
                return array('s', 'Feed Type Updated Successfully.');
            }
        } else {
            $data['companyID'] = $companyID;
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_feedtypes', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Feed Type Creating' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Feed Type created successfully.');

            }
        }
    }

    function load_feedType_header()
    {
        $buybackFeedtypeID = trim($this->input->post('buybackFeedtypeID'));
        $data = $this->db->query("select buybackFeedtypeID,description FROM srp_erp_buyback_feedtypes WHERE buybackFeedtypeID = {$buybackFeedtypeID} ")->row_array();
        return $data;
    }

    function delete_feedType()
    {
        $buybackFeedtypeID = trim($this->input->post('buybackFeedtypeID'));
        $this->db->delete('srp_erp_buyback_feedtypes', array('buybackFeedtypeID' => $buybackFeedtypeID));
        return true;
    }

    function update_receiptVoucher_income_detail()
    {
        $this->db->trans_start();
        $this->db->select('transactionCurrency, transactionExchangeRate, companyLocalCurrency,companyLocalExchangeRate, companyReportingCurrency ,companyReportingExchangeRate ,partyCurrency,partyExchangeRate,transactionCurrencyID');
        $this->db->where('pvMasterAutoID', $this->input->post('pvMasterAutoID'));
        $master_recode = $this->db->get('srp_erp_buyback_paymentvouchermaster')->row_array();
        $segment = explode('|', trim($this->input->post('segment_gl')));
        $gl_code = explode('|', trim($this->input->post('gl_code_des')));
        $data['pvMasterAutoID'] = trim($this->input->post('pvMasterAutoID'));
        $data['GLAutoID'] = trim($this->input->post('gl_code'));
        $data['systemGLCode'] = trim($gl_code[0]);
        $data['GLCode'] = trim($gl_code[1]);
        $data['GLDescription'] = trim($gl_code[2]);
        $data['GLType'] = trim($gl_code[3]);
        $data['segmentID'] = trim($segment[0]);
        $data['segmentCode'] = trim($segment[1]);
        $data['transactionCurrency'] = $master_recode['transactionCurrency'];
        $data['transactionExchangeRate'] = $master_recode['transactionExchangeRate'];
        $data['transactionAmount'] = trim($this->input->post('amount'));
        $data['companyLocalCurrency'] = $master_recode['companyLocalCurrency'];
        $data['companyLocalExchangeRate'] = $master_recode['companyLocalExchangeRate'];
        $data['companyLocalAmount'] = ($data['transactionAmount'] / $master_recode['companyLocalExchangeRate']);
        $data['companyReportingCurrency'] = $master_recode['companyReportingCurrency'];
        $data['companyReportingExchangeRate'] = $master_recode['companyReportingExchangeRate'];
        $data['companyReportingAmount'] = ($data['transactionAmount'] / $master_recode['companyReportingExchangeRate']);
        //$data['partyCurrency'] = $master_recode['partyCurrency'];
        //$data['partyExchangeRate'] = $master_recode['partyExchangeRate'];
        //$data['partyAmount'] = ($data['transactionAmount'] / $master_recode['partyExchangeRate']);
        $data['comment'] = trim($this->input->post('description'));
        $data['type'] = 'Income';
        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedUserName'] = $this->common_data['current_user'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];

        if (trim($this->input->post('pvDetailID'))) {
            $this->db->where('pvDetailID', trim($this->input->post('pvDetailID')));
            $this->db->update('srp_erp_buyback_paymentvoucherdetail', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Receipt Voucher Income Detail : ' . $data['GLDescription'] . ' Update Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Receipt Voucher Income Detail : ' . $data['GLDescription'] . ' Updated Successfully.');
            }
        }
    }

    function save_receiptVoucher_single_income()
    {
        $this->db->trans_start();
        $depositType = trim($this->input->post('depositType'));
        $batchMasterID = trim($this->input->post('batchMasterID'));
        $pvDetailID = trim($this->input->post('pvDetailID'));
        $pvMasterAutoID = trim($this->input->post('pvMasterAutoID'));

        $this->db->select('transactionCurrency, transactionExchangeRate, transactionCurrencyID,companyLocalCurrencyID, companyLocalCurrency,companyLocalExchangeRate, companyReportingCurrency ,companyReportingExchangeRate ,partyCurrency,partyExchangeRate,partyCurrencyID,companyReportingCurrencyID,segmentID,segmentCode');
        $this->db->where('pvMasterAutoID', trim($this->input->post('pvMasterAutoID')));
        $master = $this->db->get('srp_erp_buyback_paymentvouchermaster')->row_array();

        $data['pvMasterAutoID'] = $pvMasterAutoID;
        $data['BatchID'] = $batchMasterID;
        $data['transactionAmount'] = trim($this->input->post('amount'));
        $data['segmentID'] = $master['segmentID'];
        $data['segmentCode'] = $master['segmentCode'];
        $data['transactionCurrencyID'] = $master['transactionCurrencyID'];
        $data['transactionCurrency'] = $master['transactionCurrency'];
        $data['transactionExchangeRate'] = $master['transactionExchangeRate'];
        $data['companyLocalCurrencyID'] = $master['companyLocalCurrencyID'];
        $data['companyLocalCurrency'] = $master['companyLocalCurrency'];
        $data['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
        $data['companyLocalAmount'] = ($data['transactionAmount'] / $master['companyLocalExchangeRate']);
        $data['companyReportingCurrencyID'] = $master['companyReportingCurrencyID'];
        $data['companyReportingCurrency'] = $master['companyReportingCurrency'];
        $data['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
        $data['companyReportingAmount'] = ($data['transactionAmount'] / $master['companyReportingExchangeRate']);
        //$data['partyAmount'] = ($data['transactionAmount'] / $data['partyExchangeRate']);
        //$data['payVoucherAutoId'] = trim($this->input->post('payVoucherAutoId'));
        $data['comment'] = trim($this->input->post('description'));
        $data['type'] = 'Deposit';
        if ($depositType == 1) {
            $data['isMatching'] = 0;
        } else {
            $data['isMatching'] = 1;
        }

        if ($pvDetailID) {
            $this->db->where('pvDetailID', $pvDetailID);
            $this->db->update('srp_erp_buyback_paymentvoucherdetail', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Receipt Voucher Deposit Update Failed' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Receipt Voucher Deposit Updated Successfully', $pvMasterAutoID);
            }
        } else {
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_paymentvoucherdetail', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Receipt Voucher Deposit Saved Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Receipt Voucher Deposit Saved Successfully.', $pvMasterAutoID);
            }
        }
    }

    function save_farmVisit_report_header()
    {
        $this->db->trans_start();
        $date_format_policy = date_format_policy();
        $farmerVisitID = trim($this->input->post('farmerVisitID'));

        $documentDate = $this->input->post('documentDate');
        $hatchDate = $this->input->post('hatchDate');
        $format_documentDate = null;
        $noofvisit = trim($this->input->post('noofvisit'));

        if (isset($documentDate) && !empty($documentDate)) {
            $format_documentDate = input_format_date($documentDate, $date_format_policy);
        }
        $format_hatchDate = null;
        if (isset($hatchDate) && !empty($hatchDate)) {
            $format_hatchDate = input_format_date($hatchDate, $date_format_policy);
        }

        $data['batchMasterID'] = trim($this->input->post('batchMasterID'));
        $data['farmID'] = trim($this->input->post('farmID'));
        $data['documentDate'] = $format_documentDate;
        $data['hatchDate'] = $format_hatchDate;
        $data['farmerAddress'] = trim($this->input->post('farmerAddress'));
        $data['numberOfBirds'] = trim($this->input->post('noofbirds'));
        $data['farmType'] = trim($this->input->post('farmType'));
        $data['breed'] = trim($this->input->post('breed'));
        $data['feed'] = trim($this->input->post('feed'));
        $data['detailFarmDescription'] = trim($this->input->post('detailFarmDescription'));
        $data['numberOfFeeders'] = trim($this->input->post('numberOfFeeders'));
        $data['numberOfDrinkers'] = trim($this->input->post('numberOfDrinkers'));
        $data['feederHeight'] = trim($this->input->post('feederHeight'));
        $data['drinkerHeight'] = trim($this->input->post('drinkerHeight'));
        $data['density'] = trim($this->input->post('density'));
        $data['litterQuality'] = trim($this->input->post('litterQuality'));
        $data['smellOfAmonia'] = trim($this->input->post('smellOfAmonia'));
        $data['ventilation'] = trim($this->input->post('ventilation'));
        $data['biosecurity'] = trim($this->input->post('biosecurity'));
        $data['recordKeeping'] = trim($this->input->post('recordKeeping'));

        if ($farmerVisitID) {
            $q = "SELECT
                    numberOfVisit
                FROM
                    srp_erp_buyback_farmervisitreport
                WHERE
                    farmerVisitID = '" . $farmerVisitID . "'";
            $result = $this->db->query($q)->row_array();

            if ($result['numberOfVisit'] > $noofvisit) {
                return array('e', 'Selected visit already exist!');
                exit();
            } else {
                $data['numberOfVisit'] = $noofvisit;
                $this->db->where('farmerVisitID', $farmerVisitID);
                $this->db->update('srp_erp_buyback_farmervisitreport', $data);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return array('e', 'Farmer Visit Report Update Failed' . $this->db->_error_message());
                } else {
                    $this->db->trans_commit();
                    return array('s', 'Farmer Visit Report Updated Successfully', $farmerVisitID);
                }
            }


        } else {
            $this->load->library('sequence');
            $farmVisitReportSystemCode = $this->sequence->sequence_generator('FVR');
            $data['numberOfVisit'] = $noofvisit;
            $data['documentSystemCode'] = $farmVisitReportSystemCode;
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_farmervisitreport', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Farmer Visit Report Saved Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Farmer Visit Report Saved Successfully.', $last_id);
            }
        }
    }

    function load_farmVisitReport_header()
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('*,DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate,DATE_FORMAT(hatchDate,\'' . $convertFormat . '\') AS hatchDate');
        $this->db->from('srp_erp_buyback_farmervisitreport');
        $this->db->where('farmerVisitID', trim($this->input->post('farmerVisitID')));
        return $this->db->get()->row_array();
    }

    function load_farmVisitReport_confirmation($farmerVisitID)
    {
        $convertFormat = convert_date_format_sql();

        $data['master'] = $this->db->query("select fvr.confirmedByEmpID ,fvr.confirmedByName ,DATE_FORMAT(fvr.confirmedDate,'.$convertFormat. %h:%i:%s') AS confirmedDate,DATE_FORMAT(documentDate,'{$convertFormat}') AS documentDate,DATE_FORMAT(hatchDate,'{$convertFormat}') AS hatchDate, fvr.confirmedYN,fm.description as farmerName,batch.batchCode,fm.farmSystemCode as farmerCode,fvr.farmerAddress as fvrFarmerAddress,fvr.numberOfVisit as numberOfVisit, fvr.numberOfBirds as fvrNumberOfBirds,fvr.farmType as fvrFarmType, fvr.breed as fvrBreed, fvr.feed as fvrFeed, fvr.detailFarmDescription as fvrDetailFarmDescription,numberOfFeeders,numberOfDrinkers,feederHeight,drinkerHeight,density,litterQuality,smellOfAmonia,ventilation,biosecurity,recordKeeping FROM srp_erp_buyback_farmervisitreport fvr LEFT JOIN srp_erp_buyback_farmmaster fm ON fm.farmID = fvr.farmID LEFT JOIN srp_erp_buyback_batch batch ON fvr.batchMasterID = batch.batchMasterID WHERE farmerVisitID = $farmerVisitID ")->row_array();

        $data['detail'] = $this->db->query("select * FROM srp_erp_buyback_farmervisitreportdetails WHERE farmerVisitMasterID = $farmerVisitID ORDER BY farmerVisitDetailID DESC")->result_array();

        return $data;

    }

    function save_farmVisitReport_detail()
    {
        $farmerVisitID = trim($this->input->post('farmerVisitID'));
        $ages = $this->input->post('age');
        $noOfBirds = $this->input->post('numberOfBirds');
        $mortalityNumber = $this->input->post('mortalityNumber');
        $mortalityPercent = $this->input->post('mortalityPercent');
        $totalFeed = $this->input->post('totalFeed');
        $avgFeedperBird = $this->input->post('avgFeedperBird');
        $avgBodyWeight = $this->input->post('avgBodyWeight');
        $fcr = $this->input->post('fcr');
        $remarks = $this->input->post('remarks');

        $this->db->trans_start();

        $this->db->select('farmerVisitDetailID');
        $this->db->from('srp_erp_buyback_farmervisitreportdetails');
        $this->db->where('farmerVisitMasterID', trim($this->input->post('farmerVisitID')));
        $detailexist = $this->db->get()->row_array();
    if(!empty($detailexist))
    {
        return array('e', 'Details alredy exist,please delete the existing detail to add a new detail');
    }else
    {
        foreach ($ages as $key => $age) {

            $data['farmerVisitMasterID'] = $farmerVisitID;
            $data['age'] = $age;
            $data['numberOfBirds'] = $noOfBirds[$key];
            $data['mortalityNumber'] = $mortalityNumber[$key];
            $data['mortalityPercent'] = $mortalityPercent[$key];
            $data['totalFeed'] = $totalFeed[$key];
            $data['avgFeedperBird'] = $avgFeedperBird[$key];
            $data['avgBodyWeight'] = $avgBodyWeight[$key];
            $data['fcr'] = $fcr[$key];
            $data['remarks'] = $remarks[$key];
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_farmervisitreportdetails', $data);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', ' Farm Visit Report Details :  Save Failed ' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', ' Farm Visit Report Details :  Added Successfully.', $farmerVisitID);
        }
    }



    }

    function delete_farmVisitReport_detail()
    {
        $farmerVisitDetailID = trim($this->input->post('farmerVisitDetailID'));
        $this->db->delete('srp_erp_buyback_farmervisitreportdetails', array('farmerVisitDetailID' => $farmerVisitDetailID));
        return true;
    }

    function delete_farmVisitReport_master()
    {
        $farmerVisitID = trim($this->input->post('farmerVisitID'));

        $visitDetails = $this->db->query("select numberOfVisit, batchMasterID FROM srp_erp_buyback_farmervisitreport WHERE farmerVisitID = $farmerVisitID")->row_array();
        $id = $visitDetails['batchMasterID'];
        $totalCount = $this->db->query("select COUNT(numberOfVisit) as visitNo FROM srp_erp_buyback_farmervisitreport WHERE batchMasterID = $id")->row_array();
        $noOfVisit = $totalCount['visitNo'];

        if($visitDetails['numberOfVisit'] == $noOfVisit ){
            $this->db->delete('srp_erp_buyback_farmervisitreportdetails', array('farmerVisitMasterID' => $farmerVisitID));
            $this->db->delete('srp_erp_buyback_farmervisitreport', array('farmerVisitID' => $farmerVisitID));

            return array('s', ' Farm Visit Report Deleted Successfully.');
        }
        else {
            $deleteInput = $this->db->query("select documentSystemCode FROM srp_erp_buyback_farmervisitreport WHERE batchMasterID = $id AND numberOfVisit = $noOfVisit")->row_array();
            $SysCode = $deleteInput['documentSystemCode'];
            return array('e', "Delete the $SysCode record to delete this Report.");
        }

    }

    function farmVisitReport_confirmation()
    {
        $farmerVisitID = trim($this->input->post('farmerVisitID'));

        $data = array('confirmedYN' => 1, 'confirmedDate' => $this->common_data['current_date'],
            'confirmedByEmpID' => $this->common_data['current_userID'],
            'confirmedByName' => $this->common_data['current_user']);
        $this->db->where('farmerVisitID', $farmerVisitID);
        $this->db->update('srp_erp_buyback_farmervisitreport', $data);

        return array('s', 'Confirmed Successfully. ');

    }

    function fetch_buyback_first_dispatchDate($batchMasterID)
    {
        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $this->db->select('DATE_FORMAT(dpm.documentDate,\'' . $convertFormat . '\') AS documentDate');
        $this->db->from("srp_erp_buyback_dispatchnote dpm");
        $this->db->join('srp_erp_buyback_dispatchnotedetails dpd', 'dpd.dispatchAutoID = dpm.dispatchAutoID AND buybackItemType = 1');
        $this->db->where("batchMasterID", $batchMasterID);
        $this->db->where("dpm.companyID", $companyID);
        $this->db->order_by("dpm.documentDate ASC");
        return $this->db->get()->row_array();
    }

    function save_paymentVoucher_loan()
    {
        $this->db->trans_start();
        $batchMasterID = trim($this->input->post('batchMasterID'));
        $loanType = trim($this->input->post('loanType'));
        $pvDetailID = trim($this->input->post('pvDetailID'));
        $pvMasterAutoID = trim($this->input->post('pvMasterAutoID'));

        $this->db->select('transactionCurrency, transactionExchangeRate, transactionCurrencyID,companyLocalCurrencyID, companyLocalCurrency,companyLocalExchangeRate, companyReportingCurrency ,companyReportingExchangeRate ,partyCurrency,partyExchangeRate,partyCurrencyID,companyReportingCurrencyID,segmentID,segmentCode');
        $this->db->where('pvMasterAutoID', trim($this->input->post('pvMasterAutoID')));
        $master = $this->db->get('srp_erp_buyback_paymentvouchermaster')->row_array();

        $data['pvMasterAutoID'] = $pvMasterAutoID;
        $data['BatchID'] = $batchMasterID;
        $data['transactionAmount'] = trim($this->input->post('amount'));
        $data['segmentID'] = $master['segmentID'];
        $data['segmentCode'] = $master['segmentCode'];
        $data['transactionCurrencyID'] = $master['transactionCurrencyID'];
        $data['transactionCurrency'] = $master['transactionCurrency'];
        $data['transactionExchangeRate'] = $master['transactionExchangeRate'];
        $data['companyLocalCurrencyID'] = $master['companyLocalCurrencyID'];
        $data['companyLocalCurrency'] = $master['companyLocalCurrency'];
        $data['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
        $data['companyLocalAmount'] = ($data['transactionAmount'] / $master['companyLocalExchangeRate']);
        $data['companyReportingCurrencyID'] = $master['companyReportingCurrencyID'];
        $data['companyReportingCurrency'] = $master['companyReportingCurrency'];
        $data['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
        $data['companyReportingAmount'] = ($data['transactionAmount'] / $master['companyReportingExchangeRate']);
        //$data['partyAmount'] = ($data['transactionAmount'] / $data['partyExchangeRate']);
        //$data['payVoucherAutoId'] = trim($this->input->post('payVoucherAutoId'));
        $data['comment'] = trim($this->input->post('description'));
        $data['type'] = 'Loan';
        if ($loanType == 1) {
            $data['isMatching'] = 0;
        } else {
            $data['isMatching'] = 1;
        }
        if ($pvDetailID) {
            $this->db->where('pvDetailID', $pvDetailID);
            $this->db->update('srp_erp_buyback_paymentvoucherdetail', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Payment Voucher Loan Update Failed' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Payment Voucher Loan Updated Successfully', $pvMasterAutoID);
            }
        } else {
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_paymentvoucherdetail', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Payment Voucher Loan Saved Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Payment Voucher Loan Saved Successfully.', $pvMasterAutoID);
            }
        }
    }

    function fetch_buyback_farmer_pendingLoanAmount($farmID)
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $this->db->select("(sum( IF ( isMatching = 0, pvd.transactionAmount, 0 ) ) - sum( IF ( isMatching = 1, pvd.transactionAmount, 0 ) ) ) AS amount");
        $this->db->from("srp_erp_buyback_paymentvouchermaster pvm");
        $this->db->join("srp_erp_buyback_paymentvoucherdetail pvd", "pvd.pvMasterAutoID = pvm.pvMasterAutoID AND type = 'Loan'");
        $this->db->where("farmID", $farmID);
        $this->db->where("pvm.companyID", $companyID);
        return $this->db->get()->row_array();
        //echo $this->db->last_query();
    }

    function fetch_buyback_farmer_paidDepositAmount()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $farmID = trim($this->input->post('farmID'));
        $totalDepositAmount = 0;

        $farmMasterDepositAmount = $this->fetch_farmer_depositAmount($farmID);

        $farmMasterDepositAmountPV = $this->db->query("SELECT COALESCE(SUM(pvd.depositAmount),0) as depositAmount FROM
	srp_erp_buyback_paymentvouchermaster pvm LEFT JOIN ( SELECT pvMasterAutoID, type, SUM(transactionAmount) AS depositAmount FROM srp_erp_buyback_paymentvoucherdetail WHERE type = 'Deposit' GROUP BY pvMasterAutoID) pvd ON pvm.pvMasterAutoID = pvd.pvMasterAutoID WHERE farmID = {$farmID} AND (PVtype = 3 OR PVtype = 2) AND approvedYN = 1")->row_array();

        $data['amount'] = $farmMasterDepositAmount['depositAmount'] - $farmMasterDepositAmountPV['depositAmount'];

        return $data;
    }

    function fetch_buyback_batch_outStandingPayableAmount($batchMasterID)
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $this->db->select("batchPayableAmount,batchCode");
        $this->db->from("srp_erp_buyback_batch");
        $this->db->where("batchMasterID", $batchMasterID);
        $this->db->where("companyID", $companyID);
        return $this->db->get()->row_array();
        //echo $this->db->last_query();
    }

    function add_farm_fieldOfficer()
    {
        $this->db->trans_start();
        $companyID = $this->common_data['company_data']['company_id'];
        $active = $this->input->post('isActive');
        $farmID = trim($this->input->post('farmID'));
        $fieldOfficerID = trim($this->input->post('fieldOfficerID'));
        $employeeid = trim($this->input->post('employeeID'));
        if (!$fieldOfficerID) {
            $this->db->select('farmID,empID');
            $this->db->from('srp_erp_buyback_farmfieldofficers');
            $this->db->where('empID', trim($this->input->post('employeeID')));
            $this->db->where('farmID', $farmID);
            $this->db->where('companyID', $companyID);
            $party_detail = $this->db->get()->row_array();
            if (!empty($party_detail)) {
                return array('w', 'Field Officer is Already added');
            }
        }
        $fieldofficername = $this->db->query("SELECT Ename2 FROM srp_employeesdetails where EIdNo = $employeeid ")->row_array();
        $isActive = 0;
        if (!empty($this->input->post('isActive'))) {
            $isActive = 1;
        }
        $data['empID'] = trim($this->input->post('employeeID'));
        $data['fieldOfficerName'] = $fieldofficername['Ename2'];
        $data['farmID'] = $farmID;
        $data['isActive'] = $isActive;
        if ($fieldOfficerID) {
            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];

            $this->db->where('fieldOfficerID', $fieldOfficerID);
            $this->db->update('srp_erp_buyback_farmfieldofficers', $data);

            $isactivestatus = "SELECT
	isActive
FROM
	srp_erp_buyback_farmfieldofficers 
WHERE
	companyID = '" . $companyID . "'
	AND isActive = '1' 
	AND farmID = '" . $farmID . "' AND fieldOfficerID != '" . $fieldOfficerID . "' ";
            $result = $this->db->query($isactivestatus)->row_array();
            if ($result && $active == 1) {
                return array('e', 'There is already Active Field Office');
            }
            $fiedofficer = "SELECT
	farmID,empID
FROM
	srp_erp_buyback_farmfieldofficers 
WHERE
	companyID = '" . $companyID . "'
	AND isActive = '1' 
	AND empID = '" . $employeeid . "'
	AND farmID = '" . $farmID . "' AND fieldOfficerID != '" . $fieldOfficerID . "' ";
            $result = $this->db->query($fiedofficer)->row_array();
            if ($result) {
                return array('w', 'Field Officer is Already added');
            } else {
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return array('e', 'Error in Field Officer Update ' . $this->db->_error_message());

                } else {
                    $this->db->trans_commit();
                    return array('s', 'Field Officer Updated Successfully.');
                }
            }
        } else {
            $this->db->select('isActive');
            $this->db->from('srp_erp_buyback_farmfieldofficers');
            $this->db->where('companyID', current_companyID());
            $this->db->where('isActive', 1);
            $this->db->where('farmID', $farmID);
            $result = $this->db->get()->row_array();
            if (!empty($result) && $active == 1) {
                return array('e', 'There is already Active Field Office');
            }
            $data['companyID'] = $companyID;
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_farmfieldofficers', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Error in Field Officer Creating' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Field Officer is created successfully.');

            }


        }
    }


    function load_farm_fieldOfficer_header()
    {
        $this->db->select('fieldOfficerID,empID,isActive');
        $this->db->where('fieldOfficerID', $this->input->post('fieldOfficerID'));
        $this->db->from('srp_erp_buyback_farmfieldofficers');
        return $this->db->get()->row_array();
    }

    function delete_farm_Dealers()
    {
        $farmDealerID = trim($this->input->post('farmDealerID'));
        $this->db->delete('srp_erp_buyback_farmdealers', array('farmDealerID' => $farmDealerID));
        return true;
    }

    function delete_farm_fieldOfficer()
    {
        $fieldOfficerID = trim($this->input->post('fieldOfficerID'));
        $this->db->delete('srp_erp_buyback_farmfieldofficers', array('fieldOfficerID' => $fieldOfficerID));
        return true;
    }

    function farm_image_upload()
    {
        $this->db->trans_start();
        $output_dir = "uploads/buyback/farmMaster/";
        if (!file_exists($output_dir)) {
            mkdir("uploads/buyback", 007);
            mkdir("uploads/buyback/farmMaster", 007);
        }
        $attachment_file = $_FILES["files"];
        $info = new SplFileInfo($_FILES["files"]["name"]);
        $fileName = 'Farm_' . trim($this->input->post('farmID')) . '.' . $info->getExtension();
        move_uploaded_file($_FILES["files"]["tmp_name"], $output_dir . $fileName);

        $data['farmImage'] = $fileName;

        $this->db->where('farmID', trim($this->input->post('farmID')));
        $this->db->update('srp_erp_buyback_farmmaster', $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', "Image Upload Failed." . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Image uploaded  Successfully.');
        }
    }

    function fetch_farmer_depositAmount($farmID)
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $this->db->select("depositAmount");
        $this->db->from("srp_erp_buyback_farmmaster");
        $this->db->where("farmID", $farmID);
        $this->db->where("companyID", $companyID);
        return $this->db->get()->row_array();
        //echo $this->db->last_query();
    }

    function save_paymentVoucher_batch_settlement()
    {
        $this->db->trans_start();
        $batchMasterID = trim($this->input->post('BatchID'));
        $pvMasterAutoID = trim($this->input->post('pvMasterAutoID'));

        $loanAmount = trim($this->input->post('loan_deductionAmount'));
        $depositAmount = trim($this->input->post('deposit_deductionAmount'));
        $advanceAmount = trim($this->input->post('advance_deductionAmount'));
        $lostAmount = $this->input->post('lost_deductionAmount');
        $lossedbatchID = $this->input->post('lossedbatchID');

        $this->db->select('transactionCurrency, transactionExchangeRate, transactionCurrencyID,companyLocalCurrencyID, companyLocalCurrency,companyLocalExchangeRate, companyReportingCurrency ,companyReportingExchangeRate ,partyCurrency,partyExchangeRate,partyCurrencyID,companyReportingCurrencyID,segmentID,segmentCode');
        $this->db->where('pvMasterAutoID', trim($this->input->post('pvMasterAutoID')));
        $master = $this->db->get('srp_erp_buyback_paymentvouchermaster')->row_array();

        $this->db->select('pvDetailID');
        $this->db->where('pvMasterAutoID', trim($this->input->post('pvMasterAutoID')));
        $this->db->where('type', 'Loan');
        $loanExist = $this->db->get('srp_erp_buyback_paymentvoucherdetail')->row_array();

        $this->db->select('pvDetailID');
        $this->db->where('pvMasterAutoID', trim($this->input->post('pvMasterAutoID')));
        $this->db->where('type', 'Deposit');
        $depositExist = $this->db->get('srp_erp_buyback_paymentvoucherdetail')->row_array();

        $this->db->select('pvDetailID');
        $this->db->where('pvMasterAutoID', trim($this->input->post('pvMasterAutoID')));
        $this->db->where('type', 'Advance');
        $advanceExist = $this->db->get('srp_erp_buyback_paymentvoucherdetail')->row_array();

        $this->db->select('pvDetailID');
        $this->db->where('pvMasterAutoID', trim($this->input->post('pvMasterAutoID')));
        $this->db->where('type', 'Loss');
        $lossExist = $this->db->get('srp_erp_buyback_paymentvoucherdetail')->row_array();

        $this->db->where('pvMasterAutoID', $this->input->post('pvMasterAutoID'));
        $this->db->where('type', 'Loss');
        $this->db->delete('srp_erp_buyback_paymentvoucherdetail');

        $this->db->where('pvMasterAutoID', $pvMasterAutoID);
        $this->db->where('BatchID', $batchMasterID);
        $results = $this->db->get('srp_erp_buyback_paymentvoucherdetail')->result_array();

        if (!empty($loanAmount)) {
            $data['pvMasterAutoID'] = $pvMasterAutoID;
            $data['BatchID'] = $batchMasterID;
            $data['type'] = 'Loan';
            $data['transactionAmount'] = $loanAmount;
            $data['segmentID'] = $master['segmentID'];
            $data['segmentCode'] = $master['segmentCode'];
            $data['transactionCurrencyID'] = $master['transactionCurrencyID'];
            $data['transactionCurrency'] = $master['transactionCurrency'];
            $data['transactionExchangeRate'] = $master['transactionExchangeRate'];
            $data['companyLocalCurrencyID'] = $master['companyLocalCurrencyID'];
            $data['companyLocalCurrency'] = $master['companyLocalCurrency'];
            $data['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
            $data['companyLocalAmount'] = ($data['transactionAmount'] / $master['companyLocalExchangeRate']);
            $data['companyReportingCurrencyID'] = $master['companyReportingCurrencyID'];
            $data['companyReportingCurrency'] = $master['companyReportingCurrency'];
            $data['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
            $data['companyReportingAmount'] = ($data['transactionAmount'] / $master['companyReportingExchangeRate']);
            //$data['partyAmount'] = ($data['transactionAmount'] / $data['partyExchangeRate']);
            //$data['payVoucherAutoId'] = trim($this->input->post('payVoucherAutoId'));
            $data['comment'] = trim($this->input->post('description'));
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            if (empty($loanExist)) {
                $this->db->insert('srp_erp_buyback_paymentvoucherdetail', $data);
            } else {
                $this->db->where('pvDetailID', trim($loanExist['pvDetailID']));
                $this->db->update('srp_erp_buyback_paymentvoucherdetail', $data);
            }
        }

        if (!empty($depositAmount)) {
            $data['pvMasterAutoID'] = $pvMasterAutoID;
            $data['BatchID'] = $batchMasterID;
            $data['type'] = 'Deposit';
            $data['transactionAmount'] = $depositAmount;
            $data['segmentID'] = $master['segmentID'];
            $data['segmentCode'] = $master['segmentCode'];
            $data['transactionCurrencyID'] = $master['transactionCurrencyID'];
            $data['transactionCurrency'] = $master['transactionCurrency'];
            $data['transactionExchangeRate'] = $master['transactionExchangeRate'];
            $data['companyLocalCurrencyID'] = $master['companyLocalCurrencyID'];
            $data['companyLocalCurrency'] = $master['companyLocalCurrency'];
            $data['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
            $data['companyLocalAmount'] = ($data['transactionAmount'] / $master['companyLocalExchangeRate']);
            $data['companyReportingCurrencyID'] = $master['companyReportingCurrencyID'];
            $data['companyReportingCurrency'] = $master['companyReportingCurrency'];
            $data['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
            $data['companyReportingAmount'] = ($data['transactionAmount'] / $master['companyReportingExchangeRate']);
            //$data['partyAmount'] = ($data['transactionAmount'] / $data['partyExchangeRate']);
            //$data['payVoucherAutoId'] = trim($this->input->post('payVoucherAutoId'));
            $data['comment'] = trim($this->input->post('description'));
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];

            if (empty($depositExist)) {
                $this->db->insert('srp_erp_buyback_paymentvoucherdetail', $data);
            } else {
                $this->db->where('pvDetailID', trim($depositExist['pvDetailID']));
                $this->db->update('srp_erp_buyback_paymentvoucherdetail', $data);
            }

        }
        if (!empty($advanceAmount)) {
            $data['pvMasterAutoID'] = $pvMasterAutoID;
            $data['BatchID'] = $batchMasterID;
            $data['type'] = 'Advance';
            $data['transactionAmount'] = $advanceAmount;
            $data['segmentID'] = $master['segmentID'];
            $data['segmentCode'] = $master['segmentCode'];
            $data['transactionCurrencyID'] = $master['transactionCurrencyID'];
            $data['transactionCurrency'] = $master['transactionCurrency'];
            $data['transactionExchangeRate'] = $master['transactionExchangeRate'];
            $data['companyLocalCurrencyID'] = $master['companyLocalCurrencyID'];
            $data['companyLocalCurrency'] = $master['companyLocalCurrency'];
            $data['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
            $data['companyLocalAmount'] = ($data['transactionAmount'] / $master['companyLocalExchangeRate']);
            $data['companyReportingCurrencyID'] = $master['companyReportingCurrencyID'];
            $data['companyReportingCurrency'] = $master['companyReportingCurrency'];
            $data['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
            $data['companyReportingAmount'] = ($data['transactionAmount'] / $master['companyReportingExchangeRate']);
            $data['comment'] = trim($this->input->post('description'));
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            if (empty($advanceExist)) {
                $this->db->insert('srp_erp_buyback_paymentvoucherdetail', $data);
            } else {
                $this->db->where('pvDetailID', trim($advanceExist['pvDetailID']));
                $this->db->update('srp_erp_buyback_paymentvoucherdetail', $data);
            }
        }
        if (!empty($lostAmount)) {
            foreach ($lostAmount as $key => $val) {
                $dataLoss['pvMasterAutoID'] = $pvMasterAutoID;
                $dataLoss['BatchID'] = $batchMasterID;
                $dataLoss['lossedBatchID'] = $lossedbatchID[$key];
                $dataLoss['type'] = 'Loss';
                $dataLoss['transactionAmount'] = $val;
                $dataLoss['segmentID'] = $master['segmentID'];
                $dataLoss['segmentCode'] = $master['segmentCode'];
                $dataLoss['transactionCurrencyID'] = $master['transactionCurrencyID'];
                $dataLoss['transactionCurrency'] = $master['transactionCurrency'];
                $dataLoss['transactionExchangeRate'] = $master['transactionExchangeRate'];
                $dataLoss['companyLocalCurrencyID'] = $master['companyLocalCurrencyID'];
                $dataLoss['companyLocalCurrency'] = $master['companyLocalCurrency'];
                $dataLoss['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                $dataLoss['companyLocalAmount'] = ($dataLoss['transactionAmount'] / $master['companyLocalExchangeRate']);
                $dataLoss['companyReportingCurrencyID'] = $master['companyReportingCurrencyID'];
                $dataLoss['companyReportingCurrency'] = $master['companyReportingCurrency'];
                $dataLoss['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                $dataLoss['companyReportingAmount'] = ($dataLoss['transactionAmount'] / $master['companyReportingExchangeRate']);
                $dataLoss['comment'] = trim($this->input->post('description'));
                $dataLoss['companyCode'] = $this->common_data['company_data']['company_code'];
                $dataLoss['companyID'] = $this->common_data['company_data']['company_id'];
                $dataLoss['createdUserGroup'] = $this->common_data['user_group'];
                $dataLoss['createdPCID'] = $this->common_data['current_pc'];
                $dataLoss['createdUserID'] = $this->common_data['current_userID'];
                $dataLoss['createdUserName'] = $this->common_data['current_user'];
                $dataLoss['createdDateTime'] = $this->common_data['current_date'];
                $this->db->insert('srp_erp_buyback_paymentvoucherdetail', $dataLoss);
            }
        }
        $last_id = $this->db->insert_id();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Settlement Voucher Saved Failed ' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Settlement Voucher Saved Successfully.');

        }

    }

    function fetch_buyback_subArea()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $locationID = trim($this->input->post('locationID'));
        $this->db->select('locationID,description');
        $this->db->where('companyID', $companyID);
        $this->db->where('masterID', $locationID);
        return $this->db->get('srp_erp_buyback_locations')->result_array();
    }

    function delete_buyback_item_master()
    {
        $buybackItemID = trim($this->input->post('buybackItemID'));
        $this->db->delete('srp_erp_buyback_itemmaster', array('buybackItemID' => $buybackItemID));
        return true;
    }

    function getColumnsByReport($reportCode)
    {
        $this->db->select("fieldName,caption,isDefault,textAlign,isMandatory,isCalculate");
        $this->db->from("srp_erp_reporttemplate rt");
        $this->db->join('srp_erp_reporttemplatefields rf', 'rt.reportID = rf.reportID', 'INNER');
        $this->db->where("rt.documentCode", $reportCode);
        $this->db->where("rf.isVisible", 1);
        $this->db->order_by("rf.sortOrder ASC");
        $result = $this->db->get()->result_array();
        return $result;
    }

    function get_farm_ledger_report()
    {
        $companyID = $this->common_data['company_data']['company_id'];
        $date_format_policy = date_format_policy();
        $convertFormat = convert_date_format_sql();
        $farmer = $this->input->post("farmerTo");
        $documentCode = $this->input->post("documentCode");
        $batchClosingDate = trim($this->input->post('from'));

        $format_batchClosingDate = null;
        if (isset($batchClosingDate) && !empty($batchClosingDate)) {
            $format_batchClosingDate = input_format_date($batchClosingDate, $date_format_policy);
        }
        $i = 1;
        $farmerOR = ' AND (';
        if (!empty($farmer)) { /*generate the query according to selected vendor*/
            foreach ($farmer as $farmer_val) {
                if ($i != 1) {
                    $farmerOR .= ' OR ';
                }
                $farmerOR .= "fmaster.farmID = '" . $farmer_val . "' ";
                $i++;
            }
        }
        $farmerOR .= ')';

        $x = 1;
        $documentCodeOR = ' AND (';
        if (!empty($documentCode)) { /*generate the query according to selected vendor*/
            foreach ($documentCode as $documentCode_val) {
                if ($x != 1) {
                    $documentCodeOR .= ' OR ';
                }
                $documentCodeOR .= "ledger.documentCode = '" . $documentCode_val . "' ";
                $x++;
            }
        }
        $documentCodeOR .= ')';

        $result = $this->db->query("SELECT 	batch.batchMasterID, batch.batchCode, ledger.documentSystemCode, CASE ledger.documentCode WHEN 'BBDPN' THEN 'Dispatch Note' WHEN 'BBGRN' THEN
	'Goods Received Note' WHEN 'BBRV' THEN 'Receipt Voucher' WHEN 'BBPV' THEN 'Pay ment Voucher' WHEN 'BBSV' THEN 'Settlement'  WHEN 'BBDR' THEN 'Dispatch Return' END AS documentName,ledger.transactionCurrencyDecimalPlaces as transactionAmountDecimalPlaces, ledger.companyLocalCurrencyDecimalPlaces AS companyLocalAmountDecimalPlaces,ledger.companyReportingCurrencyDecimalPlaces AS companyReportingAmountDecimalPlaces,DATE_FORMAT(ledger.documentDate, '{$convertFormat}') AS documentDate, ledger.documentCode, ledger.transactionCurrency,  SUM(ledger.transactionAmount) as transactionAmount, SUM(ledger.companyLocalAmount) as companyLocalAmount, SUM(ledger.companyReportingAmount) as companyReportingAmount,ledger.documentNarration,fmaster.farmID,fmaster.description AS farmName,fmaster.GLAutoID,fmaster.GLDescription,fmaster.GLSecondaryCode,fmaster.farmSystemCode, ledger.documentMasterAutoID FROM srp_erp_generalledger ledger LEFT JOIN (SELECT farmID,farmSystemCode,description,GLAutoID,systemAccountCode,GLSecondaryCode,GLDescription,subCategory FROM srp_erp_buyback_farmmaster fm LEFT JOIN ( SELECT GLAutoID, systemAccountCode, GLSecondaryCode,
			GLDescription,
			subCategory
		FROM
			srp_erp_chartofaccounts
	) lb ON (
		fm.farmersLiabilityGLautoID = lb.GLAutoID
	)
) fmaster ON fmaster.farmID = ledger.partyAutoID
	LEFT JOIN srp_erp_buyback_batch batch on batch.farmID = fmaster.farmID
	LEFT JOIN srp_erp_buyback_paymentvouchermaster pvm on pvm.pvMasterAutoID = ledger.documentMasterAutoID
	LEFT JOIN srp_erp_buyback_grn grn on grn.grnAutoID = ledger.documentMasterAutoID
	LEFT JOIN srp_erp_buyback_dispatchnote dpn on dpn.dispatchAutoID = ledger.documentMasterAutoID
	LEFT JOIN srp_erp_buyback_dispatchreturn dpr on dpr.returnAutoID = ledger.documentMasterAutoID
WHERE
   1 = CASE ledger.documentCode WHEN 'BBDPN' THEN dpn.approvedYN WHEN 'BBGRN' THEN grn.approvedYN  WHEN 'BBPV' THEN pvm.approvedYN  WHEN 'BBDR' THEN dpr.approvedYN WHEN 'BBRV' THEN pvm.approvedYN WHEN 'BBSV' THEN pvm.approvedYN END 
   AND
	ledger.companyID = {$companyID} AND ledger.transactionAmount > 0 AND ledger.documentDate <= '{$format_batchClosingDate}' $farmerOR $documentCodeOR
 GROUP BY ledger.documentMasterAutoID ORDER BY ledger.documentDate ASC ")->result_array();
        //echo $this->db->last_query();
        //exit();
        return $result;
    }


    function delete_batch_master()
    {
        $batchmasterid = trim($this->input->post('batchMasterID'));
        $this->db->delete('srp_erp_buyback_batch', array('batchMasterID' => $batchmasterid));
        return true;
    }

    function delete_buyback_notes()
    {
        $notesid = trim($this->input->post('notesID'));
        $this->db->delete('srp_erp_buyback_notes', array('notesID' => $notesid));
        return true;
    }

    function save_paymentVoucher_batch()
    {
        $this->db->trans_start();
        $batchMasterID = trim($this->input->post('batchMasterID'));
        $pvDetailID = trim($this->input->post('pvDetailID'));
        $pvMasterAutoID = trim($this->input->post('pvMasterAutoID'));

        $this->db->select('transactionCurrency, transactionExchangeRate, transactionCurrencyID,companyLocalCurrencyID, companyLocalCurrency,companyLocalExchangeRate, companyReportingCurrency ,companyReportingExchangeRate ,partyCurrency,partyExchangeRate,partyCurrencyID,companyReportingCurrencyID,segmentID,segmentCode');
        $this->db->where('pvMasterAutoID', trim($this->input->post('pvMasterAutoID')));
        $master = $this->db->get('srp_erp_buyback_paymentvouchermaster')->row_array();

        $data['pvMasterAutoID'] = $pvMasterAutoID;
        $data['BatchID'] = $batchMasterID;
        $data['transactionAmount'] = trim($this->input->post('amount'));
        $data['segmentID'] = $master['segmentID'];
        $data['segmentCode'] = $master['segmentCode'];
        $data['transactionCurrencyID'] = $master['transactionCurrencyID'];
        $data['transactionCurrency'] = $master['transactionCurrency'];
        $data['transactionExchangeRate'] = $master['transactionExchangeRate'];
        $data['companyLocalCurrencyID'] = $master['companyLocalCurrencyID'];
        $data['companyLocalCurrency'] = $master['companyLocalCurrency'];
        $data['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
        $data['companyLocalAmount'] = ($data['transactionAmount'] / $master['companyLocalExchangeRate']);
        $data['companyReportingCurrencyID'] = $master['companyReportingCurrencyID'];
        $data['companyReportingCurrency'] = $master['companyReportingCurrency'];
        $data['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
        $data['companyReportingAmount'] = ($data['transactionAmount'] / $master['companyReportingExchangeRate']);
        //$data['partyAmount'] = ($data['transactionAmount'] / $data['partyExchangeRate']);
        //$data['payVoucherAutoId'] = trim($this->input->post('payVoucherAutoId'));
        $data['comment'] = trim($this->input->post('description'));
        $data['type'] = 'Batch';

        if ($pvDetailID) {
            $this->db->where('pvDetailID', $pvDetailID);
            $this->db->update('srp_erp_buyback_paymentvoucherdetail', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Payment Voucher Batch Update Failed' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Payment Voucher Batch Updated Successfully', $pvMasterAutoID);
            }
        } else {
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_paymentvoucherdetail', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Payment Voucher Batch Saved Failed ' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Payment Voucher Batch Saved Successfully.', $pvMasterAutoID);
            }
        }
    }

    function get_buyback_preformance_rpt($dateto, $datefrom, $locationid, $sublocationid, $farmer)
    {
        $convertFormat = convert_date_format_sql();
        $companyID = current_companyID();
        $date_format_policy = date_format_policy();
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $datetoconvert = input_format_date($dateto, $date_format_policy);
        $date = "";
        $where_location = '';
        $where_sub_location = '';

        if (!empty($locationid)) {
            $locationidset = join($locationid, ",");
            $where_location = " AND fm.locationID IN ($locationidset)";
        }

        if (!empty($sublocationid)) {
            $sublocationidset = join($sublocationid, ",");
            $where_sub_location = " AND fm.subLocationID IN ($sublocationidset)";
        }
        if (!empty($farmer)) {
            $farmerset = join($farmer, ",");
            $where_sub_location = " AND fm.farmID IN ($farmerset)";
        }


        $qry = "SELECT
	batch.batchMasterID,
	batchCode,
	md.totalBirds,
	itemchickstot.chicksTotal,
	DATE_FORMAT( batchStartDate, '  %d-%m-%Y  ' ) AS batchStartDate,
	fm.description AS farmerName,
	fm.locationID,
	fm.subLocationID,
	buyback.noOfBirds AS birdstotalcount,
	feed.feedTotal,
	 (IFNULL(	dispatch.totalTransferAmountTransaction+expences.transactionAmount,dispatch.totalTransferAmountTransaction)) as grandTotalrptAmount,
	dispatch.totalTransferAmountTransaction AS grandTotalrptAmount1,
	buyback.transactionQTY AS birdskgsweight,
	(buyback.totalTransferAmountLocal + returnqty.totalcost )AS grandTotalBuybackAmount1,
	 (IFNULL(buyback.totalTransferAmountLocal+returnqty.totalcost,buyback.totalTransferAmountLocal)) as grandTotalBuybackAmount,
	batch.isclosed as isclosed
FROM
	srp_erp_buyback_batch batch
	LEFT JOIN srp_erp_buyback_mortalitymaster mm ON mm.batchMasterID = batch.batchMasterID
	LEFT JOIN ( SELECT sum( noOfBirds ) AS totalBirds, mortalityAutoID FROM srp_erp_buyback_mortalitydetails GROUP BY mortalityDetailID ) md ON md.mortalityAutoID = mm.mortalityAutoID
	LEFT JOIN srp_erp_buyback_dispatchnote dpm ON dpm.batchMasterID = batch.batchMasterID
	LEFT JOIN (
SELECT
	sum( transactionQTY ) AS chicksTotal,
	batchID 
FROM
	srp_erp_buyback_itemledger 
WHERE
	`companyID` = $companyID 
	AND `documentCode` = 'BBDPN' 
	AND `buybackItemType` = 1 
GROUP BY
	batchID 
	) itemchickstot ON itemchickstot.batchID = mm.batchMasterID
	LEFT JOIN (
SELECT
	sum( transactionQTY ) AS transactionQTY,
	DATE_FORMAT( documentDate, '%d-%m-%Y' ) AS documentDates,
	sum( noOfBirds ) AS noOfBirds,
	batchID,
	sum( totalTransferAmountLocal ) AS totalTransferAmountLocal 
FROM
	`srp_erp_buyback_itemledger` 
WHERE
	`companyID` = $companyID
	AND `documentCode` = 'BBGRN' 
GROUP BY
	batchID 
	) buyback ON buyback.batchID = batch.batchMasterID
	LEFT JOIN (
SELECT
	sum( transactionQTY ) AS feedTotal,
	batchID 
FROM
	`srp_erp_buyback_itemledger` 
WHERE
	`companyID` = $companyID
	AND `documentCode` = 'BBDPN' 
	AND `buybackItemType` = 2 
GROUP BY
	batchID 
	) feed ON feed.batchID = batch.batchMasterID
	LEFT JOIN (
SELECT
	sum( dispatch.totalTransferAmountTransaction ) AS totalTransferAmountTransaction,
	DATE_FORMAT( dispatch.documentDate, '%d-%m-%Y' ) AS dispatchdocumentDate,
	batchID 
FROM
	srp_erp_buyback_itemledger dispatch 
WHERE
	`companyID` = $companyID
	AND `documentCode` = 'BBDPN' 
GROUP BY
	batchID 
	) dispatch ON dispatch.batchID = batch.batchMasterID
	
		LEFT JOIN(SELECT
	`returnmaster`.`batchMasterID`,
	SUM(`disreturn`.`totalTransferCost`) as totalcost
FROM
	`srp_erp_buyback_dispatchreturn` `returnmaster`
	LEFT JOIN `srp_erp_buyback_dispatchreturndetails` `disreturn` ON `disreturn`.`returnAutoID` = `returnmaster`.`returnAutoID`
	LEFT JOIN `srp_erp_buyback_dispatchnote` `dismaster` ON `dismaster`.`dispatchAutoID` = `disreturn`.`dispatchAutoID` 


WHERE
	 returnmaster.companyID = $companyID
	AND returnmaster.approvedYN = 1 
	AND returnmaster.confirmedYN = 1

GROUP BY
	`returnmaster`.`batchMasterID`) returnqty on returnqty.batchMasterID =  batch.batchMasterID
	
	
	
		LEFT JOIN (SELECT
	`pvd`.`BatchID`,
	SUM(`pvd`.`transactionAmount`) as transactionAmount
FROM
	`srp_erp_buyback_paymentvoucherdetail` `pvd`
	LEFT JOIN `srp_erp_buyback_paymentvouchermaster` `pvm` ON `pvd`.`pvMasterAutoID` = `pvm`.`pvMasterAutoID` 
WHERE
	
	`pvd`.`companyID` = $companyID
	AND `pvd`.`type` = 'Expense' 
	AND `pvm`.`approvedYN` = 1 
GROUP BY
`pvd`.`BatchID`)expences on expences.BatchID =  batch.batchMasterID
	
	LEFT JOIN srp_erp_buyback_farmmaster fm ON fm.farmID = batch.farmID 
WHERE
	batch.companyID = $companyID $where_location $where_sub_location AND batchStartDate BETWEEN '$datetoconvert' AND '$datefromconvert'
GROUP BY
	batchMasterID
ORDER BY
	batchCode
	ASC
	";
        $buybackpreformance = $this->db->query($qry)->result_array();
        return $buybackpreformance;

    }

    function fetchbuybackbirdstot()
    {
        $buybacktotalchicks = 0;

        $batchid = trim($this->input->post('batchMasterID'));
        $companyID = current_companyID();

        $chicksTotalbatch = $this->db->query("SELECT COALESCE(sum(qty), 0) AS chicksTotal FROM srp_erp_buyback_dispatchnote dpm INNER JOIN srp_erp_buyback_dispatchnotedetails dpd ON dpd.dispatchAutoID = dpm.dispatchAutoID AND buybackItemType = 1 WHERE batchMasterID = $batchid")->row_array();
        $balancechicksTotal = $this->db->query("SELECT COALESCE(sum(grnd.noOfBirds), 0) AS balanceChicksTotal FROM srp_erp_buyback_grn grn INNER JOIN srp_erp_buyback_grndetails grnd ON grnd.grnAutoID = grn.grnAutoID WHERE batchMasterID =$batchid")->row_array();
        $mortalityChicks = $this->db->query("SELECT COALESCE(sum(noOfBirds), 0) AS deadChicksTotal FROM srp_erp_buyback_mortalitymaster mm INNER JOIN srp_erp_buyback_mortalitydetails md ON mm.mortalityAutoID = md.mortalityAutoID WHERE batchMasterID =$batchid")->row_array();

        $buybacktotalchicks = ($chicksTotalbatch['chicksTotal'] - ($balancechicksTotal['balanceChicksTotal'] + $mortalityChicks['deadChicksTotal']));
        return $buybacktotalchicks;

    }

    function birds_weight($farmid)
    {
        $companyID = current_companyID();
        $birdsweightcal = 0;
        $birdsweight = "SELECT SUM(transactionQTY) as trans,batch.batchMasterID, SUM(noOfBirds) as birds,batch.batchCode FROM srp_erp_buyback_itemledger item LEft JOIN srp_erp_buyback_batch batch on batch.batchMasterID = item.batchID
WHERE
	item.farmID = $farmid
	AND item.companyID = $companyID 
	AND `documentCode` = 'BBGRN' 
Group by
batch.batchCode";

        $result = $this->db->query($birdsweight)->result_array();
        return $result;
    }

    function birds_fcr($farmid)
    {
        $companyID = current_companyID();
        $feedtot = 0;
        $feedPercentage = 0;

        $chicksfcr = $this->db->query("SELECT
	sum(item.transactionQTY)  AS chicksTotal,
	 batch.batchCode,
	 item2.feed,
	sum(buyback.noOfBirds) as noOfBirds ,
	 sum(buyback.transactionQTY) as birdsweight
	 
	 
FROM
	srp_erp_buyback_itemledger item
	LEft JOIN srp_erp_buyback_batch batch on batch.batchMasterID = item.batchID
	LEFT JOIN (SELECT sum( transactionQTY ) AS feed,batchID FROM `srp_erp_buyback_itemledger` WHERE `farmid` = $farmid AND `companyID` = $companyID
	AND `documentCode` = 'BBDPN' AND `buybackItemType` = 2 GROUP BY batchID) item2 on item2.batchID = batch.batchMasterID
	
	LEFT JOIN (SELECT
	buyback.*
	FROM
	srp_erp_buyback_itemledger buyback
WHERE `farmid` = $farmid
	AND `companyID` = $companyID
	AND `documentCode` = 'BBGRN' 

) buyback on buyback.batchID = batch.batchMasterID

WHERE
	item.farmid = $farmid
	AND item.companyID = $companyID 
	AND item.documentCode = 'BBDPN' 
	AND item.buybackItemType = 1
	GROUP BY
	item.batchID")->result_array();


        /* foreach ($chicksfcr as $val)
         {
             $feedtot = ($val['chicksTotal'] + $val['noOfBirds'])/2 ;
         $feedPercentage = ($val['feed'] * 50) / $feedtot;

         }*/
        return $chicksfcr;

    }

    function fetch_feed_details($batchid)
    {
        $companyID = current_companyID();
        //$batchid = trim($this->input->post('batchid'));
        $feed = $this->db->query("SELECT
	`feedScheduleID`,
	`feedAmount`,
	`ft`.`description` AS `feedName`,
	CONCAT( startDay, ' - ', endDay ) AS changedDate,
	`buybackFeedtypeID`,
	sum(amount.qty) as qtyD
FROM
	`srp_erp_buyback_feedschedulemaster` `fsm`
	LEFT JOIN `srp_erp_buyback_feedtypes` `ft` ON `fsm`.`feedTypeID` = `ft`.`buybackFeedtypeID` 
	LEFT JOIN (SELECT
	item.itemDescription,
	item.feedType,
	details.unitOfMeasureID,
	details.unitOfMeasure,
	SUM( details.qty ) AS qty 
FROM
	srp_erp_buyback_dispatchnotedetails details
	LEFT JOIN srp_erp_buyback_itemmaster item ON item.itemAutoID = details.itemAutoID
	LEFT JOIN srp_erp_buyback_dispatchnote dn ON details.dispatchAutoID = dn.dispatchAutoID 
	
WHERE
	dn.batchMasterID = $batchid
	AND details.buybackItemType = 2 
GROUP BY
	details.itemAutoID,
	details.unitOfMeasureID) amount on amount.feedType = fsm.feedTypeID
GROUP BY
fsm.feedTypeID")->result_array();


        return $feed;
    }

    function save_gradings()
    {
        $companyID = current_companyID();
        $batchmasterid = $this->input->post('id');
        $grading = $this->input->post('Grading');

        $data['grade'] = $grading;
        $this->db->where('companyID', $companyID);
        $this->db->where('batchMasterID', $batchmasterid);
        $this->db->update('srp_erp_buyback_batch', $data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Grading Updated Failed' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Grading Updated Successfully');
        }

    }

    function save_collection_header_detail()
    {
        $date_format_policy = date_format_policy();
        $convertFormat = convert_date_format_sql();
        $companyID = $this->common_data['company_data']['company_id'];
        $company_code = $this->common_data['company_data']['company_code'];
        $farmer = $this->input->post('farmer');
        $location = $this->input->post('locationID');
        $subarea = $this->input->post('subLocationID');
        $dateto = $this->input->post('batchmasterDateto');
        $datefrom = $this->input->post('batchmasterDatefrom');
        $datefromconvert = input_format_date($datefrom, $date_format_policy);
        $datetoconvert = input_format_date($dateto, $date_format_policy);
        $collectionid = $this->input->post('collectionid');

        $where_location = '';
        if (!empty($location)) {
            $location_filter = join($location, ",");
            $where_location = " AND fm.locationID IN ($location_filter)";
        }
        $where_sub_location = '';
        if (!empty($subarea)) {
            $sublocationidset = join($subarea, ",");
            $where_sub_location = " AND fm.subLocationID IN ($sublocationidset)";
        }
        $where_farmer = '';
        if (!empty($farmer)) {
            $farmerset = join($farmer, ",");
            $where_farmer = " AND fm.farmID IN ($farmerset)";
        }
        $date = '';
        if (!empty($datefrom) && !empty($dateto)) {
            $date .= " AND ( batchStartDate >= '" . $datefromconvert . " 00:00:00' AND batchClosingDate <= '" . $datetoconvert . " 23:59:00')";
        }
        $where_admin = "Where batch.companyID = " . $companyID . $where_farmer . $where_location . $where_sub_location . $date;

        $batch = $this->db->query("SELECT batchMasterID,batchCode,batch.isclosed,DATE_FORMAT(batchStartDate,' . $convertFormat . ') AS batchStartDate,DATE_FORMAT(batchClosingDate,' . $convertFormat . ') AS batchClosingDate,isclosed,c1.systemAccountCode AS wip_SystemCode,c1.GLDescription AS wip_description,c2.systemAccountCode AS dw_SystemCode,c2.GLDescription AS dw_description,fm.description as farmerName,fm.farmID as farmid,fm.locationID as locationID ,fm.subLocationID as subLocationID,fi.empID,isclosed,batch.confirmedYN,batch.approvedYN FROM srp_erp_buyback_batch batch LEFT JOIN srp_erp_chartofaccounts c1 ON c1.GLAutoID = batch.WIPGLAutoID LEFT JOIN srp_erp_chartofaccounts c2 ON c2.GLAutoID = batch.DirectWagesGLAutoID LEFT JOIN srp_erp_buyback_farmmaster fm ON fm.farmID = batch.farmID LEFT JOIN srp_erp_buyback_farmfieldofficers fi ON fi.farmID = fm.farmID $where_admin AND batch.isclosed = 0  GROUP BY batchMasterID ORDER BY batchClosingDate ASC ")->result_array();

        if (!empty($batch)) {
            $balance = 0;
            if (!empty($collectionid)) {
                $batchupdate = $this->db->query("SELECT batchMasterID,batchCode,batch.isclosed,DATE_FORMAT(batchStartDate,' . $convertFormat . ') AS batchStartDate,DATE_FORMAT(batchClosingDate,' . $convertFormat . ') AS batchClosingDate,isclosed,c1.systemAccountCode AS wip_SystemCode,c1.GLDescription AS wip_description,c2.systemAccountCode AS dw_SystemCode,c2.GLDescription AS dw_description,fm.description as farmerName,fm.farmID as farmid,fm.locationID as locationID ,fm.subLocationID as subLocationID,fi.empID,isclosed,batch.confirmedYN,batch.approvedYN FROM srp_erp_buyback_batch batch LEFT JOIN srp_erp_chartofaccounts c1 ON c1.GLAutoID = batch.WIPGLAutoID LEFT JOIN srp_erp_chartofaccounts c2 ON c2.GLAutoID = batch.DirectWagesGLAutoID LEFT JOIN srp_erp_buyback_farmmaster fm ON fm.farmID = batch.farmID LEFT JOIN srp_erp_buyback_farmfieldofficers fi ON fi.farmID = fm.farmID $where_admin AND batch.isclosed = 0 AND batch.batchMasterID  NOT IN (SELECT batchID from srp_erp_buyback_collectionreportdetails WHERE collectionID = $collectionid GROUP BY batchID)  GROUP BY batchMasterID ORDER BY batchClosingDate ASC ")->result_array();


                if ($this->input->post('employeeID')) {
                    $Requested = explode('|', trim($this->input->post('requested')));
                    $data['driverName'] = trim($Requested[1]);
                    $data['driverID'] = trim($this->input->post('employeeID'));
                } else {
                    $data['driverName'] = trim($this->input->post('employeeName'));
                    $data['driverID'] = NULL;
                }

                if ($this->input->post('helperID')) {
                    $Requested = explode('|', trim($this->input->post('helpername')));
                    $data['helperName'] = trim($Requested[1]);
                    $data['helperID'] = trim($this->input->post('helperID'));
                } else {
                    $data['helperName'] = trim($this->input->post('helpername_n'));
                    $data['helperID'] = NULL;
                }

                $data['dateFrom'] = $datefromconvert;
                $data['dateTo'] = $datetoconvert;

                $data['Narration'] = $this->input->post('comment');
                $this->db->where('companyID', $companyID);
                $this->db->where('collectionID', $collectionid);
                $this->db->update('srp_erp_buyback_collectionreport', $data);
                $this->db->trans_complete();

                foreach ($batchupdate as $key => $val) {

                    $chicksTotal = $this->db->query("SELECT COALESCE ( sum( qty ), 0 ) AS chicksTotal 
FROM
	srp_erp_buyback_dispatchnotedetails dpd
	INNER JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID  = dpd.dispatchAutoID
	AND buybackItemType = 1 
WHERE
	batchMasterID = {$val['batchMasterID']} 
	And dpm.confirmedYN = 1 
	AND dpm.approvedYN = 1")->row_array();

                    $balancechicksTotal = $this->db->query("SELECT COALESCE ( sum( grnd.noOfBirds ), 0 ) AS receivedtotal FROM 	srp_erp_buyback_grndetails grnd INNER JOIN srp_erp_buyback_grn grn ON  grn.grnAutoID = grnd.grnAutoID WHERE batchMasterID = {$val['batchMasterID']} AND confirmedYN = 1 ANd approvedYN =1 ")->row_array();

                    $mortalityChicks = $this->db->query("SELECT COALESCE(sum(noOfBirds), 0) AS deadChicksTotal FROM srp_erp_buyback_mortalitymaster mm INNER JOIN srp_erp_buyback_mortalitydetails md ON mm.mortalityAutoID = md.mortalityAutoID WHERE batchMasterID ={$val['batchMasterID']} AND confirmedYN = 1")->row_array();

                    $chicksAge = $this->db->query("SELECT dpm.dispatchedDate,batch.closedDate FROM srp_erp_buyback_dispatchnote dpm INNER JOIN srp_erp_buyback_dispatchnotedetails dpd ON dpd.dispatchAutoID = dpm.dispatchAutoID AND buybackItemType = 1 LEFT JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = dpm.batchMasterID WHERE dpm.batchMasterID ={$val['batchMasterID']} ORDER BY dpm.dispatchAutoID ASC")->row_array();

                    $return = $this->db->query("SELECT COALESCE ( sum( dpdr.qty ), 0 ) AS returnqty,dispatchAutoID,dpdr.returnAutoID FROM srp_erp_buyback_dispatchreturndetails dpdr 
LEFT JOIN srp_erp_buyback_dispatchreturn retun on retun.returnAutoID = dpdr.returnAutoID WHERE approvedYN  = 1 AND confirmedYN = 1 AND retun.batchMasterID = {$val['batchMasterID']} GROUP BY dispatchAutoID ")->row_array();

                    $lastid = $this->db->query("select farmerVisitID from  srp_erp_buyback_farmervisitreport where batchMasterID = {$val['batchMasterID']} AND confirmedYN =1 ORDER BY farmerVisitID DESC LIMIT 1")->row_array();

            if(!empty($lastid))
            {
                $avgbodavr = $this->db->query("select IFNULL(fcr,0) as fcr,IFNULL(avgBodyWeight,0) as avgBodyWeight from srp_erp_buyback_farmervisitreportdetails where 
farmerVisitMasterID = {$lastid['farmerVisitID']}")->row_array();
            }


                    $balance = $chicksTotal['chicksTotal'] - ($balancechicksTotal['receivedtotal'] + $mortalityChicks['deadChicksTotal'] + $return['returnqty']);

                    if ($balance > 0) {
                        $datas = [];
                        $datas['collectionID'] = $collectionid;
                        $datas['farmerID'] = $val['farmid'];
                        $datas['subLocationID'] = $val['subLocationID'];
                        $datas['locationID'] = $val['locationID'];
                        $datas['batchID'] = $val['batchMasterID'];
                        $datas['inputQty'] = $chicksTotal['chicksTotal'];
                        $datas['receivedQty'] = $balancechicksTotal['receivedtotal'];
                        $datas['balanceQty'] = $chicksTotal['chicksTotal'] - ($balancechicksTotal['receivedtotal'] + $mortalityChicks['deadChicksTotal']+ $return['returnqty']);
                        if(!empty($avgbodavr))
                        {
                            $datas['fvr'] = $avgbodavr['fcr'];
                            $datas['avgBodyWeight'] = $avgbodavr['avgBodyWeight'];
                        }else
                        {
                            $datas['fvr'] = 0;
                            $datas['avgBodyWeight'] = 0;
                        }
                        $datas['companyID'] = $this->common_data['company_data']['company_id'];
                        $datas['companyCode'] = $this->common_data['company_data']['company_code'];
                        $datas['createdUserGroup'] = $this->common_data['user_group'];
                        $datas['createdPCID'] = $this->common_data['current_pc'];
                        $datas['createdUserID'] = $this->common_data['current_pc'];
                        $datas['createdDateTime'] = $this->common_data['current_date'];
                        $datas['createdUserName'] = $this->common_data['current_user'];

                        if (!empty($chicksAge)) {
                            $dStart = new DateTime($chicksAge['dispatchedDate']);
                            if ($chicksAge['closedDate'] != ' ') {
                                $dEnd = new DateTime($chicksAge['closedDate']);
                            } else {
                                $dEnd = new DateTime(current_date());
                            }
                            $dDiff = $dStart->diff($dEnd);
                            $newFormattedDate = $dDiff->days + 1;
                            $datas['age'] = $newFormattedDate;
                        } else {
                            $datas['age'] = 0;
                        }
                        $datas['mortalityQty'] = $mortalityChicks['deadChicksTotal'];
                        $this->db->insert('srp_erp_buyback_collectionreportdetails', $datas);
                        $this->db->trans_complete();
                    }

                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return array('e', 'Collection Update Failed' . $this->db->_error_message());
                } else {
                    $this->db->trans_commit();
                    return array('s', 'Collection Updated Successfully', $collectionid);
                }


            } else {
                $serial = $this->db->query("select IF ( isnull(MAX(serialNo)), 1, (MAX(serialNo) + 1) ) AS serialNo FROM `srp_erp_buyback_collectionreport` WHERE companyID={$companyID}")->row_array();
                $data['serialNo'] = $serial['serialNo'];
                $data['documentCode'] = 'BBCR';
                $data['dateFrom'] = $datefromconvert;
                $data['dateTo'] = $datetoconvert;
                $data['companyID'] = $companyID;
                $data['companyCode'] = $company_code;
                $data['createdUserGroup'] = $this->common_data['user_group'];
                $data['createdPCID'] = $this->common_data['current_pc'];
                $data['createdUserID'] = $this->common_data['current_userID'];
                $data['createdDateTime'] = $this->common_data['current_date'];
                $data['createdUserName'] = $this->common_data['current_user'];
                $data['Narration'] = $this->input->post('comment');

                if ($this->input->post('employeeID')) {
                    $Requested = explode('|', trim($this->input->post('requested')));
                    $data['driverName'] = trim($Requested[1]);
                    $data['driverID'] = trim($this->input->post('employeeID'));
                } else {
                    $data['driverName'] = trim($this->input->post('employeeName'));
                    $data['driverID'] = NULL;
                }

                if ($this->input->post('helperID')) {
                    $Requested = explode('|', trim($this->input->post('helpername')));
                    $data['helperName'] = trim($Requested[1]);
                    $data['helperID'] = trim($this->input->post('helperID'));
                } else {
                    $data['helperName'] = trim($this->input->post('helpername_n'));
                    $data['helperID'] = NULL;
                }

                $data['timestamp'] = current_date();
                $data['collectionCode'] = ($company_code . '/' . 'BBCR' . str_pad($data['serialNo'], 6, '0', STR_PAD_LEFT));
                $this->db->insert('srp_erp_buyback_collectionreport', $data);
                $collection_id = $this->db->insert_id();

                foreach ($batch as $key => $val) {

                    $chicksTotal = $this->db->query("SELECT COALESCE ( sum( qty ), 0 ) AS chicksTotal 
FROM
	srp_erp_buyback_dispatchnotedetails dpd
	INNER JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID  = dpd.dispatchAutoID
	AND buybackItemType = 1 
WHERE
	batchMasterID = {$val['batchMasterID']} 
	And dpm.confirmedYN = 1 
	AND dpm.approvedYN = 1")->row_array();

                    $balancechicksTotal = $this->db->query("SELECT COALESCE ( sum( grnd.noOfBirds ), 0 ) AS receivedtotal FROM 	srp_erp_buyback_grndetails grnd INNER JOIN srp_erp_buyback_grn grn ON  grn.grnAutoID = grnd.grnAutoID WHERE batchMasterID = {$val['batchMasterID']} AND confirmedYN = 1 ANd approvedYN =1 ")->row_array();

                    $mortalityChicks = $this->db->query("SELECT COALESCE(sum(noOfBirds), 0) AS deadChicksTotal FROM srp_erp_buyback_mortalitymaster mm INNER JOIN srp_erp_buyback_mortalitydetails md ON mm.mortalityAutoID = md.mortalityAutoID WHERE batchMasterID ={$val['batchMasterID']}  AND confirmedYN = 1")->row_array();

                    $chicksAge = $this->db->query("SELECT dpm.dispatchedDate,batch.closedDate FROM srp_erp_buyback_dispatchnote dpm INNER JOIN srp_erp_buyback_dispatchnotedetails dpd ON dpd.dispatchAutoID = dpm.dispatchAutoID AND buybackItemType = 1 LEFT JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = dpm.batchMasterID WHERE dpm.batchMasterID ={$val['batchMasterID']} ORDER BY dpm.dispatchAutoID ASC")->row_array();

                    $return = $this->db->query("SELECT COALESCE ( sum( dpdr.qty ), 0 ) AS returnqty,dispatchAutoID,dpdr.returnAutoID FROM srp_erp_buyback_dispatchreturndetails dpdr 
LEFT JOIN srp_erp_buyback_dispatchreturn retun on retun.returnAutoID = dpdr.returnAutoID WHERE approvedYN  = 1 AND confirmedYN = 1 AND retun.batchMasterID = {$val['batchMasterID']} GROUP BY dispatchAutoID ")->row_array();


                    $lastid = $this->db->query("select farmerVisitID from  srp_erp_buyback_farmervisitreport where batchMasterID = {$val['batchMasterID']} AND confirmedYN =1 ORDER BY farmerVisitID DESC LIMIT 1")->row_array();


                    if(!empty($lastid))
                    {
                        $avgbodavr = $this->db->query("select IFNULL(fcr,0) as fcr,IFNULL(avgBodyWeight,0) as avgBodyWeight from srp_erp_buyback_farmervisitreportdetails where 
farmerVisitMasterID = {$lastid['farmerVisitID']}")->row_array();
                    }

                    $balance = $chicksTotal['chicksTotal'] - ($balancechicksTotal['receivedtotal'] + $mortalityChicks['deadChicksTotal']+ $return['returnqty']);

                    if ($balance > 0) {
                        $datas = [];
                        $datas['collectionID'] = $collection_id;
                        $datas['farmerID'] = $val['farmid'];
                        $datas['subLocationID'] = $val['subLocationID'];
                        $datas['locationID'] = $val['locationID'];
                        $datas['batchID'] = $val['batchMasterID'];
                        if(!empty($avgbodavr))
                        {
                            $datas['fvr'] = $avgbodavr['fcr'];
                            $datas['avgBodyWeight'] = $avgbodavr['avgBodyWeight'];
                        }else
                        {
                            $datas['fvr'] = 0;
                            $datas['avgBodyWeight'] = 0;
                        }

                        $datas['inputQty'] = $chicksTotal['chicksTotal'];
                        $datas['receivedQty'] = $balancechicksTotal['receivedtotal'];
                        $datas['balanceQty'] = $chicksTotal['chicksTotal'] - ($balancechicksTotal['receivedtotal'] + $mortalityChicks['deadChicksTotal'] + $return['returnqty']);
                        $datas['companyID'] = $this->common_data['company_data']['company_id'];
                        $datas['companyCode'] = $this->common_data['company_data']['company_code'];
                        $datas['createdUserGroup'] = $this->common_data['user_group'];
                        $datas['createdPCID'] = $this->common_data['current_pc'];
                        $datas['createdUserID'] = $this->common_data['current_pc'];
                        $datas['createdDateTime'] = $this->common_data['current_date'];
                        $datas['createdUserName'] = $this->common_data['current_user'];

                        if (!empty($chicksAge)) {
                            $dStart = new DateTime($chicksAge['dispatchedDate']);
                            if ($chicksAge['closedDate'] != ' ') {
                                $dEnd = new DateTime($chicksAge['closedDate']);
                            } else {
                                $dEnd = new DateTime(current_date());
                            }
                            $dDiff = $dStart->diff($dEnd);
                            $newFormattedDate = $dDiff->days + 1;
                            $datas['age'] = $newFormattedDate;
                        } else {
                            $datas['age'] = 0;
                        }
                        $datas['mortalityQty'] = $mortalityChicks['deadChicksTotal'];
                        $this->db->insert('srp_erp_buyback_collectionreportdetails', $datas);
                    }
                }


                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return array('e', 'Collection Saved Successfully' . $this->db->_error_message());
                } else {
                    $this->db->trans_commit();
                    return array('s', 'Collection Saved Successfully.', $collection_id);
                }
            }
        } else {
            return array('w', 'There are no records!.');
        }

    }

    function update_collection_header_detail(){
        $companyID = $this->common_data['company_data']['company_id'];
        $company_code = $this->common_data['company_data']['company_code'];
        $collectionid = $this->input->post('collectionid');

        if (!empty($collectionid)) {

            if ($this->input->post('employeeID')) {
                $Requested = explode('|', trim($this->input->post('requested')));
                $data['driverName'] = trim($Requested[1]);
                $data['driverID'] = trim($this->input->post('employeeID'));
            } else {
                $data['driverName'] = trim($this->input->post('employeeName'));
                $data['driverID'] = NULL;
            }

            if ($this->input->post('helperID')) {
                $Requested = explode('|', trim($this->input->post('helpername')));
                $data['helperName'] = trim($Requested[1]);
                $data['helperID'] = trim($this->input->post('helperID'));
            } else {
                $data['helperName'] = trim($this->input->post('helpername_n'));
                $data['helperID'] = NULL;
            }

            $data['modifiedPCID'] = $this->common_data['current_pc'];
            $data['modifiedUserID'] = $this->common_data['current_userID'];
            $data['modifiedUserName'] = $this->common_data['current_user'];
            $data['modifiedDateTime'] = $this->common_data['current_date'];

            $data['Narration'] = $this->input->post('comment');
            $this->db->where('companyID', $companyID);
            $this->db->where('collectionID', $collectionid);
            $this->db->update('srp_erp_buyback_collectionreport', $data);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return array('e', 'Collection Update Failed' . $this->db->_error_message());
            } else {
                $this->db->trans_commit();
                return array('s', 'Collection Updated Successfully', $collectionid);
            }


        }
    }

    function add_collection_amt()
    {
        $amount = trim($this->input->post('amount'));
        $batchid = trim($this->input->post('batchid'));
        $collectionid = trim($this->input->post('collectionautoid'));
        $isupdate = trim($this->input->post('updatedvalue'));

        $data['collectionQty'] = $amount;
        $data['isUpdated'] = $isupdate;
        $this->db->where('collectionID', $collectionid);
        $this->db->where('batchID', $batchid);
        $this->db->update('srp_erp_buyback_collectionreportdetails', $data);

        $datas['modifiedPCID'] = $this->common_data['current_pc'];;
        $datas['modifiedUserID'] = $this->common_data['current_userID'];
        $datas['modifiedDateTime'] = $this->common_data['current_date'];
        $datas['modifiedUserName'] = $this->common_data['current_user'];
        $this->db->where('collectionID', $collectionid);
        $this->db->update('srp_erp_buyback_collectionreport', $datas);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Collection Amount Update Failed' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('s', 'Collection Amount Updated Successfully');
        }
    }

    function get_collection_details($collectionautoid)
    {
        $data['collectiondetail'] = $this->db->query("SELECT 
*,
fm.description as farmname,
CONCAT_WS(',',IF(LENGTH(fm.address),fm.address,NULL),IF(LENGTH(fm.city),fm.city,NULL))as farmeradd,
batchCode as batchsystemcode,
CONCAT_WS(' / ',IF(LENGTH(fm.phoneHome),fm.phoneHome,NULL),IF(LENGTH(fm.phoneMobile),fm.phoneMobile,NULL))as phonemobilefarmer,
farmlocation.description as farmlocation,
  farmlocationsub.description as subarea
FROM 
srp_erp_buyback_collectionreportdetails cldetails
LEFT JOIN srp_erp_buyback_farmmaster fm on fm.farmID = cldetails.farmerID
LEFT JOIN srp_erp_buyback_batch batch on batch.batchMasterID = cldetails.batchID
LEFT JOIN srp_erp_buyback_locations farmlocation on farmlocation.locationID = cldetails.locationID
LEFT JOIN srp_erp_buyback_locations farmlocationsub on farmlocationsub.locationID = cldetails.subLocationID 
where 
collectionID = $collectionautoid
AND isUpdated = 1 ")->result_array();
        return $data;
    }

    function load_collection_header()
    {
        $convertFormat = convert_date_format_sql();
        $collectionautoid = $this->input->post('collectionautoid');
        $this->db->select('det.* ,colrep.driverID as driverID,colrep.driverName as driverName,DATE_FORMAT(colrep.dateFrom,"' . $convertFormat . '") as dateFrom ,DATE_FORMAT(colrep.dateTo,"' . $convertFormat . '") as dateTo,colrep.collectionID as collectionid,det.batchID as batchID');
        $this->db->from('srp_erp_buyback_collectionreport as colrep');
        $this->db->join(' srp_erp_buyback_collectionreportdetails as det', 'det.collectionID = colrep.collectionID', 'LEFT');
        $this->db->where('colrep.collectionID ', $collectionautoid);
        $data = $this->db->get()->result_array();

        $this->db->select('colrep.driverID as driverID,colrep.driverName as driverName,colrep.helperID as helperID,colrep.helperName as helperName,colrep.Narration as Narration');
        $this->db->from('srp_erp_buyback_collectionreport as colrep');
        $this->db->where('colrep.collectionID ', $collectionautoid);
        $master = $this->db->get()->row_array();


        $this->db->query("UPDATE srp_erp_buyback_collectionreportdetails details
    LEFT JOIN (
SELECT
    batchMasterID,
    sum( qty ) as chickstotal
FROM
		srp_erp_buyback_dispatchnote disnote  
    LEFT JOIN  srp_erp_buyback_dispatchnotedetails disdetails ON disdetails.dispatchAutoID = disnote.dispatchAutoID 
    AND disnote.approvedYN = 1 
    AND disdetails.buybackItemType = 1
GROUP BY
    batchMasterID 
    ) dispatch ON dispatch.batchMasterID = details.batchID
    LEFT JOIN  (
SELECT
    grn.batchMasterID,
    COALESCE ( sum( grnd.noOfBirds ), 0 ) AS receivedChicksTotal 
FROM
srp_erp_buyback_grn grn
    
    LEFT JOIN  srp_erp_buyback_grndetails grnd  ON grnd.grnAutoID = grn.grnAutoID
    AND confirmedYN = 1 
    AND approvedYN = 1 
GROUP BY
    batchMasterID 
    ) balance ON balance.batchMasterID = details.batchID
    LEFT JOIN  (
SELECT
    mm.batchMasterID,
    COALESCE ( sum( noOfBirds ), 0 ) AS deadChicksTotal 
FROM
    srp_erp_buyback_mortalitymaster mm
    INNER JOIN srp_erp_buyback_mortalitydetails md ON mm.mortalityAutoID = md.mortalityAutoID 
GROUP BY
    batchMasterID 
    ) mortality ON mortality.batchMasterID = details.batchID
    LEFT JOIN  (
SELECT
    dpm.batchMasterID,
    dpm.dispatchedDate,
    batch.closedDate 
FROM
    srp_erp_buyback_dispatchnote dpm
    INNER JOIN srp_erp_buyback_dispatchnotedetails dpd ON dpd.dispatchAutoID = dpm.dispatchAutoID 
    AND buybackItemType = 1
    AND confirmedYN = 1
    LEFT JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = dpm.batchMasterID 
GROUP BY
    dpm.batchMasterID 
    ) age ON age.batchMasterID = details.batchID
    
    	LEFT  JOIN (
SELECT
    dprm.batchMasterID,
		 dprm.returnAutoID,
		 dprd.returnAutoID as re,
		 SUM(qty )as qtynew
FROM
    srp_erp_buyback_dispatchreturn dprm
    INNER JOIN srp_erp_buyback_dispatchreturndetails dprd ON dprm.returnAutoID = dprd.returnAutoID 
    AND confirmedYN = 1
		AND approvedYN = 1
    LEFT JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = dprm.batchMasterID 
GROUP BY
    dprm.batchMasterID 
    ) dprm ON dprm.batchMasterID = details.batchID
  
      
   SET 
	 details.inputQty = IFNULL(dispatch.chickstotal,0),
    details.mortalityQty = IFNULL(mortality.deadChicksTotal,0),
    details.receivedQty = IFNULL(balance.receivedChicksTotal,0),
    details.balanceQty = dispatch.chickstotal - ( IFNULL(balance.receivedChicksTotal,0) + IFNULL(mortality.deadChicksTotal,0)+ IFNULL(dprm.qtynew,0))
	WHERE 
    details.collectionID = $collectionautoid");


        $this->db->query("UPDATE srp_erp_buyback_collectionreportdetails details
JOIN (
SELECT
	dpm.dispatchedDate,
	batch.closedDate AS closingdate,
	dpm.batchMasterID,
CASE
	
	WHEN batch.closedDate = ' ' THEN
	DATEDIFF( batch.closedDate, documentDate ) + 1 ELSE DATEDIFF( CURRENT_DATE, documentDate ) + 1 
	END AS age 
FROM
	srp_erp_buyback_dispatchnote dpm
	INNER JOIN srp_erp_buyback_dispatchnotedetails dpd ON dpd.dispatchAutoID = dpm.dispatchAutoID 
	AND buybackItemType = 1
	LEFT JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = dpm.batchMasterID 
GROUP BY
	dpm.batchMasterID 
	) age ON age.batchMasterID = details.batchID 
	SET details.age = age.age
WHERE
details.collectionID = $collectionautoid");


        $this->db->query("UPDATE srp_erp_buyback_collectionreportdetails details
    LEFT JOIN (
SELECT
    details.batchID,
    farmvisitrep.batchMasterID,
    farmvisitrep.farmerVisitID,
    detailsfmr.fcr,
    detailsfmr.avgBodyWeight
    
FROM
    srp_erp_buyback_collectionreportdetails details
    INNER JOIN (SELECT batchMasterID,max(farmerVisitID)as farmerVisitID  ,farmID FROM srp_erp_buyback_farmervisitreport  group by batchMasterID ORDER BY farmerVisitID DESC ) farmvisitrep ON farmvisitrep.batchMasterID = details.batchID 
LEFT JOIN srp_erp_buyback_farmervisitreportdetails detailsfmr on detailsfmr.farmerVisitMasterID = farmvisitrep.farmerVisitID
    ) farmvisitrep ON farmvisitrep.batchMasterID = details.batchID   
   SET 
   
	 details.fvr = IFNULL(farmvisitrep.fcr,0), 
	  details.avgBodyWeight = IFNULL(farmvisitrep.avgBodyWeight,0)
	 
	WHERE 
    details.collectionID = $collectionautoid");


        $location = [];
        $sublocation = [];
        $farmer = [];
        $datefrom = [];
        $dateto = [];
        $driverid = $master['driverID'];
        $drivername = $master['driverName'];

        $helperid = $master['helperID'];
        $helpername = $master['helperName'];
        $narration = $master['Narration'];

        foreach ($data as $row) {
            if (!in_array($row['locationID'], $location)) {
                $location[] = $row['locationID'];
            }
            if (!in_array($row['subLocationID'], $sublocation)) {
                $sublocation[] = $row['subLocationID'];
            }

            if (!in_array($row['farmerID'], $farmer)) {
                $farmer[] = $row['farmerID'];
            }
            if (!in_array($row['dateFrom'], $datefrom)) {
                $datefrom[] = $row['dateFrom'];
            }
            if (!in_array($row['dateTo'], $dateto)) {
                $dateto[] = $row['dateTo'];
            }
            /*if(!in_array($row['driverID'], $dateto)){
                $driverid[] = $row['driverID'];
            }*/


        }


        return [
            'location' => $location,
            'sublocation' => $sublocation,
            'farmer' => $farmer,
            'datefrom' => $datefrom,
            'dateto' => $dateto,
            'collectionid' => $collectionautoid,
            'driverID' => $driverid,
            'drivername' => $drivername,
            'helperid' => $helperid,
            'helpername' => $helpername,
            'narration' => $narration,
        ];
    }

    function load_collection_header_bkp()
    {
        $convertFormat = convert_date_format_sql();
        $collectionautoid = $this->input->post('collectionautoid');
        $this->db->select('det.* ,DATE_FORMAT(colrep.dateFrom,' . $convertFormat . ') as dateFrom ,DATE_FORMAT(colrep.dateTo,' . $convertFormat . ') as dateTo,colrep.collectionID as collectionid');
        $this->db->from('srp_erp_buyback_collectionreport as colrep');
        $this->db->join(' srp_erp_buyback_collectionreportdetails as det', 'det.collectionID = colrep.collectionID', 'LEFT');
        $this->db->where('colrep.collectionID ', $collectionautoid);
        return $this->db->get()->result_array();
    }

    function getcollectionmaster($collectionautoidpost)
    {
        $convertFormat = convert_date_format_sql();
        $data['collectionmaster'] = $this->db->query("SELECT 
*,CONCAT_WS(' | ',IF(LENGTH(driverName),driverName,NULL),IF(LENGTH(helperName),helperName,NULL))as driverhelper,DATE_FORMAT(createdDateTime,'$convertFormat') as createdDate,
DATE_FORMAT(createdDateTime,  '$convertFormat' ' %h:%i:%s') as createdatetime,
DATE_FORMAT(modifiedDateTime,  '$convertFormat' ' %h:%i:%s') as updatedatetime
FROM 
srp_erp_buyback_collectionreport 

where 
collectionID = $collectionautoidpost ")->row_array();
        return $data;
    }

    function collection_confirmation()
    {
        $this->db->select('collectionID,isUpdated');
        $this->db->where('collectionID', trim($this->input->post('collectionautoid')));
        $this->db->where('isUpdated', 1);
        $this->db->from('srp_erp_buyback_collectionreportdetails');
        $record = $this->db->get()->result_array();
        if (empty($record)) {
            $this->session->set_flashdata('w', 'There are no records to confirm this document!');
            return false;
        } else {
            $this->db->select('collectionID');
            $this->db->where('collectionID', trim($this->input->post('collectionautoid')));
            $this->db->where('confirmedYN', 1);
            $this->db->from('srp_erp_buyback_collectionreport');
            $Confirmed = $this->db->get()->row_array();

            $this->db->select('*');
            $this->db->where('collectionID', trim($this->input->post('collectionautoid')));
            $this->db->from('srp_erp_buyback_collectionreport');
            $collection_detail = $this->db->get()->row_array();

            if (!empty($Confirmed)) {
                $this->session->set_flashdata('w', 'Document already confirmed ');
                return false;
            } else {

                $this->db->where('collectionID', trim($this->input->post('collectionautoid')));
                $this->db->where('isUpdated', 0);
                $this->db->delete('srp_erp_buyback_collectionreportdetails');

                $data = array(
                    'confirmedYN' => 1,
                    'confirmedDate' => $this->common_data['current_date'],
                    'confirmedByEmpID' => $this->common_data['current_userID'],
                    'confirmedByName' => $this->common_data['current_user'],
                );
                $this->db->where('collectionID', trim($this->input->post('collectionautoid')));
                $this->db->update('srp_erp_buyback_collectionreport', $data);
                $this->session->set_flashdata('s', $collection_detail['collectionCode'] . ' Confirmed Successfully');
                return true;
            }
        }
    }

    function fetch_balance_chicks()
    {
        $companyID = current_companyID();
        $batchID = trim($this->input->post('batchID'));


        $chicks = $this->db->query("SELECT
	(
		ifnull(sum(dpd.qty), 0) - ifnull(ggrn.noOfBirds, 0)
	) AS chicksTotal
FROM
	`srp_erp_buyback_dispatchnotedetails` `dpd`
INNER JOIN `srp_erp_buyback_dispatchnote` `dpm` ON `dpm`.`dispatchAutoID` = `dpd`.`dispatchAutoID`
LEFT JOIN (
	SELECT
		ifnull(SUM(grd.noOfBirds),0) as noOfBirds,
		grn.batchMasterID,
		grnDetailsID
	FROM
		srp_erp_buyback_grn grn
	INNER JOIN srp_erp_buyback_grndetails grd ON grd.grnAutoID = grn.grnAutoID
	WHERE
		`grn`.`batchMasterID` = '$batchID'
	GROUP BY
		batchMasterID
) ggrn ON `ggrn`.`batchMasterID` = `dpm`.`batchMasterID`
WHERE
	`dpm`.`companyID` = '$companyID'
AND `dpm`.`batchMasterID` = '$batchID'
AND `dpd`.`buybackItemType` = 1
AND `dpm`.`approvedYN` = 1")->row_array();


        $mortalityChicks = $this->db->query("SELECT COALESCE(sum(noOfBirds), 0) AS deadChicksTotal FROM srp_erp_buyback_mortalitymaster mm INNER JOIN srp_erp_buyback_mortalitydetails md ON mm.mortalityAutoID = md.mortalityAutoID WHERE batchMasterID = $batchID  AND confirmedYN = 1")->row_array();

        $this->db->select(" SUM(qty )as qtynew");
        $this->db->from(' srp_erp_buyback_dispatchreturn dprm');
        $this->db->join('srp_erp_buyback_dispatchreturndetails dprd ', 'dprm.returnAutoID = dprd.returnAutoID ', 'INNER');
        $this->db->join('srp_erp_buyback_batch batch ', ' batch.batchMasterID = dprm.batchMasterID  ', 'LEFT');
        $this->db->where('dprm.confirmedYN', 1);
        $this->db->where('dprm.batchMasterID', $batchID);
        $this->db->where('dprm.approvedYN', 1);
        $return = $this->db->get()->row_array();


        $balance = ($chicks['chicksTotal'] - ($mortalityChicks['deadChicksTotal']+$return['qtynew']));
        return $balance;
    }

    function save_return_header()
    {
        $companyID = current_companyID();
        $company_code = $this->common_data['company_data']['company_code'];
        $this->db->trans_start();
        $date_format_policy = date_format_policy();
        $rtrnDate = $this->input->post('returnDate');
        $returnDate = input_format_date($rtrnDate, $date_format_policy);
        $docdate = $this->input->post('documentdate');
        $documetdate = input_format_date($docdate, $date_format_policy);
        $batchid = $this->input->post('batchMasterID');
        $financeyear_period = $this->input->post('financeyear_period');
        $this->db->select('*');
        $this->db->from('srp_erp_companyfinanceperiod');
        $this->db->where('companyFinancePeriodID', $financeyear_period);
        $companyFinancePeriod = $this->db->get()->row_array();
        $location = explode('|', trim($this->input->post('location_dec')));
        $year = explode(' - ', trim($this->input->post('companyFinanceYear')));
        $FYBegin = input_format_date($year[0], $date_format_policy);
        $FYEnd = input_format_date($year[1], $date_format_policy);
        $farm_det = $this->fetch_farmer_detail($this->input->post('farmID'));
        $farm_curreny_det = $this->fetch_farmercurreny($farm_det['farmerCurrencyID']);
        $farmid = $this->input->post('farmID');
        $currency_code = explode('|', trim($this->input->post('currency_code')));
        $segment = explode('|', trim($this->input->post('segment')));
        $serial = $this->db->query("select IF ( isnull(MAX(serialNo)), 1, (MAX(serialNo) + 1) ) AS serialNo FROM srp_erp_buyback_dispatchreturn WHERE companyID={$companyID}")->row_array();
        $gldetails = $this->fetch_glaccountdet($farm_det['farmersLiabilityGLautoID']);


        $data['batchMasterID'] = trim($batchid);
        $data['farmID'] = trim($farmid);
        $data['documentID'] = 'BBDR';
        $data['documentDate'] = trim($documetdate);
        $data['returnedDate'] = trim($returnDate);
        $data['referenceNo'] = trim($this->input->post('referenceNo'));
        $data['Narration'] = trim($this->input->post('narration'));
        $data['companyFinanceYearID'] = trim($this->input->post('financeyear'));
        $data['companyFinanceYear'] = trim($this->input->post('companyFinanceYear'));
        $data['FYBegin'] = trim($FYBegin);
        $data['FYEnd'] = trim($FYEnd);
        $data['FYPeriodDateFrom'] = $companyFinancePeriod['dateFrom'];
        $data['FYPeriodDateTo'] = $companyFinancePeriod['dateTo'];
        $data['companyFinancePeriodID'] = trim($this->input->post('financeyear_period'));
        $data['wareHouseAutoID'] = trim($this->input->post('location'));
        $data['wareHouseCode'] = trim($location[0]);
        $data['wareHouseLocation'] = trim($location[1]);
        $data['wareHouseDescription'] = trim($location[2]);
        $data['contactPersonName'] = $farm_det['contactPerson'];
        $data['contactPersonNumber'] = $farm_det['phoneMobile'];
        $data['farmerliabilityAutoID'] = $farm_det['farmersLiabilityGLautoID'];
        $data['farmerliabilitySystemGLCode'] = $gldetails['systemAccountCode'];
        $data['farmerliabilityGLAccount'] = $gldetails['GLSecondaryCode'];
        $data['farmerliabilityDescription'] = $gldetails['GLDescription'];
        $data['farmerliabilityType'] = $gldetails['subCategory'];
        $data['transactionCurrencyID'] = trim($this->input->post('transactionCurrencyID'));
        $data['transactionCurrency'] = trim($currency_code[0]);
        $data['transactionExchangeRate'] = 1;
        $data['transactionCurrencyDecimalPlaces'] = fetch_currency_desimal_by_id($data['transactionCurrencyID']);
        $data['companyLocalCurrencyID'] = $this->common_data['company_data']['company_default_currencyID'];
        $data['companyLocalCurrency'] = $this->common_data['company_data']['company_default_currency'];
        $default_currency = currency_conversionID($data['transactionCurrencyID'], $data['companyLocalCurrencyID']);
        $data['companyLocalExchangeRate'] = $default_currency['conversion'];
        $data['companyLocalCurrencyDecimalPlaces'] = $default_currency['DecimalPlaces'];
        $data['companyReportingCurrency'] = $this->common_data['company_data']['company_reporting_currency'];
        $data['companyReportingCurrencyID'] = $this->common_data['company_data']['company_reporting_currencyID'];
        $reporting_currency = currency_conversionID($data['transactionCurrencyID'], $data['companyReportingCurrencyID']);
        $data['companyReportingExchangeRate'] = $reporting_currency['conversion'];
        $data['companyReportingCurrencyDecimalPlaces'] = $reporting_currency['DecimalPlaces'];
        $data['farmerCurrencyID'] = $farm_det['farmerCurrencyID'];
        $data['farmerCurrency'] = $farm_curreny_det['CurrencyCode'];
        $farmercurrecy = currency_conversionID($data['transactionCurrencyID'], $data['farmerCurrencyID']);
        $data['farmerCurrencyExchangeRate'] = $farmercurrecy['conversion'];
        $data['farmerCurrencyDecimalPlaces'] = $farmercurrecy['DecimalPlaces'];
        $data['segmentID'] = $segment[0];
        $data['segmentCode'] = $segment[1];
        $data['modifiedPCID'] = $this->common_data['current_pc'];
        $data['modifiedUserID'] = $this->common_data['current_userID'];
        $data['modifiedUserName'] = $this->common_data['current_user'];
        $data['modifiedDateTime'] = $this->common_data['current_date'];

        if (trim($this->input->post('Returnautoid'))) {
            $this->db->where('returnAutoID', trim($this->input->post('Returnautoid')));
            $this->db->update('srp_erp_buyback_dispatchreturn', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('e', 'Return : ' . $data['wareHouseDescription'] . ' Update Failed ' . $this->db->_error_message());
                $this->db->trans_rollback();
                return array('status' => false);
            } else {
                $this->session->set_flashdata('s', 'Return : ' . $data['wareHouseDescription'] . ' Updated Successfully.');
                $this->db->trans_commit();
                return array('status' => true, 'last_id' => $this->input->post('Returnautoid'), 'batchid' => $this->input->post('batchMasterID'));
            }
        } else {

            $data['serialNo'] = $serial['serialNo'];
            $data['documentSystemCode'] = ($company_code . '/' . 'BBDR' . str_pad($data['serialNo'], 6, '0', STR_PAD_LEFT));
            $data['companyCode'] = $this->common_data['company_data']['company_code'];
            $data['companyID'] = $this->common_data['company_data']['company_id'];
            $data['createdUserGroup'] = $this->common_data['user_group'];
            $data['createdPCID'] = $this->common_data['current_pc'];
            $data['createdUserID'] = $this->common_data['current_userID'];
            $data['createdUserName'] = $this->common_data['current_user'];
            $data['createdDateTime'] = $this->common_data['current_date'];
            $this->db->insert('srp_erp_buyback_dispatchreturn', $data);
            $last_id = $this->db->insert_id();
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('e', 'Return : ' . $data['wareHouseDescription'] . '  Saved Failed ' . $this->db->_error_message());
                $this->db->trans_rollback();
                return array('status' => false);
            } else {
                $this->session->set_flashdata('s', 'Return : ' . $data['wareHouseDescription'] . ' Saved Successfully.');
                $this->db->trans_commit();
                return array('status' => true, 'last_id' => $last_id, 'batchid' => $data['batchMasterID']);
            }
        }
    }

    function fetch_farmer_detail($farm_id)
    {
        $this->db->select('*');
        $this->db->from('srp_erp_buyback_farmmaster');
        $this->db->where('farmID', $farm_id);
        return $this->db->get()->row_array();
    }

    function fetch_glaccountdet($glautoid)
    {
        $this->db->select('*');
        $this->db->from('srp_erp_chartofaccounts');
        $this->db->where('GLAutoID', $glautoid);
        return $this->db->get()->row_array();


    }

    function fetch_farmercurreny($currencyid)
    {
        $this->db->select('*');
        $this->db->from('srp_erp_currencymaster');
        $this->db->where('currencyID', $currencyid);
        return $this->db->get()->row_array();


    }

    function fetch_dispatch_code()
    {
        $batchid = $this->input->post('batchid');

        $data = $this->db->query('SELECT
	dismaster.dispatchAutoID as dispatchAutoID,
	documentSystemCode,
	documentDate,
	dispatchdet.transfercos,
	disreturn.requestedm,
	dispatchdet.dispatchDetailsID as detailid 
FROM
	srp_erp_buyback_dispatchnote dismaster
	LEFT JOIN ( SELECT sum( qty ) AS transfercos, dispatchAutoID, dispatchDetailsID FROM srp_erp_buyback_dispatchnotedetails GROUP BY dispatchAutoID ) dispatchdet ON dismaster.dispatchAutoID = dispatchdet.dispatchAutoID
	LEFT JOIN ( SELECT SUM(qty) AS requestedm, dispatchAutoID FROM srp_erp_buyback_dispatchreturndetails GROUP BY dispatchAutoID ) disreturn ON dispatchdet.dispatchAutoID = disreturn.dispatchAutoID 
WHERE
	dismaster.approvedYN = 1 
	AND dismaster.batchMasterID = ' . $batchid . ' ')->result_array();

        return $data;
    }

    function fetch_dispatchdetails()
    {
        $dispatchautoid = $this->input->post('dispatchAutoID');
        $data = $this->db->query("select
disdetail.*, 	grndet.noOfBirds,
disreturn.qty as returnqty,
disreturn.dispatchDetailID,
	dispatchnote.batchMasterID,
IF(buybackItemType = '1' || buybackItemType = '4' , disdetail.qty-grndet.noOfBirds, disdetail.qty) as qtygrn

from 
 srp_erp_buyback_dispatchnotedetails disdetail
 LEFT JOIN (SELECT SUM(qty) as qty,dispatchDetailID,dispatchAutoID from srp_erp_buyback_dispatchreturndetails GROUP BY dispatchDetailID)
 disreturn on disdetail.dispatchDetailsID = disreturn.dispatchDetailID 
 	LEFT JOIN srp_erp_buyback_dispatchnote dispatchnote ON dispatchnote.dispatchAutoID = disdetail.dispatchAutoID
 LEFT JOIN (
SELECT
	sum( noOfBirds ) AS noOfBirds,
	grnmaster.batchMasterID,
	grndetails.itemAutoID
FROM
	srp_erp_buyback_grndetails grndetails
	LEFT JOIN srp_erp_buyback_grn grnmaster ON grnmaster.grnAutoID = grndetails.grnAutoID 
	LEFT JOIN srp_erp_buyback_itemmaster itemtype on itemtype.itemAutoID = grndetails.itemAutoID

where 
itemtype.buybackItemType = 4
GROUP BY
batchMasterID
	) grndet ON grndet.batchMasterID = dispatchnote.batchMasterID
where 
disdetail.dispatchAutoID = $dispatchautoid
GROUP BY
disdetail.dispatchDetailsID
")->result_array();

        return $data;
    }

    function save_return_details()
    {

        $this->db->trans_start();
        $items_arr = array();
        $returnautoid = $this->input->post('returnAutoID');
        $this->db->select('disdetail.*,disreturn.qty AS returnqty,disreturn.dispatchDetailID');
        $this->db->from('	srp_erp_buyback_dispatchnotedetails disdetail');
        $this->db->where_in('disdetail.dispatchDetailsID', $this->input->post('DetailsID'));
        $this->db->where('dismaster.approvedYN', 1);
        $this->db->join('srp_erp_buyback_dispatchnote dismaster', 'disdetail.dispatchAutoID = dismaster.dispatchAutoID', 'left');
        $this->db->join('srp_erp_buyback_dispatchreturndetails disreturn', 'disdetail.dispatchDetailsID = disreturn.dispatchDetailID ', 'left');
        $this->db->join('srp_erp_buyback_dispatchreturn dispatchmaster', 'dispatchmaster.returnAutoID = disreturn.returnAutoID ', 'left');
        $this->db->group_by("disdetail.dispatchDetailsID");
        $query = $this->db->get()->result_array();

        $returnmasterdetails = $this->db->query("select dispatchreturnmaster.companyLocalExchangeRate as companyLocalExchangeRate,dispatchreturnmaster.companyReportingExchangeRate from srp_erp_buyback_dispatchreturn dispatchreturnmaster where dispatchreturnmaster.returnAutoID = $returnautoid")->row_array();

        $qty = $this->input->post('qty');


        for ($i = 0; $i < count($query); $i++) {
            $this->db->select('returnAutoID');
            $this->db->from('srp_erp_buyback_dispatchreturndetails');
            $this->db->where('dispatchAutoID', $query[$i]['dispatchAutoID']);
            $this->db->where('returnAutoID', trim($this->input->post('returnAutoID')));
            $this->db->where('itemAutoID', $query[$i]['itemAutoID']);
            $order_detail = $this->db->get()->result_array();


            if (!empty($order_detail)) {
                $this->session->set_flashdata('w', 'Return Item added already.');
            } else {
                $data[$i]['returnAutoID'] = trim($this->input->post('returnAutoID'));
                $data[$i]['dispatchAutoID'] = $query[$i]['dispatchAutoID'];
                $data[$i]['dispatchDetailID'] = $query[$i]['dispatchDetailsID'];
                $data[$i]['itemAutoID'] = $query[$i]['itemAutoID'];
                $data[$i]['buybackItemType'] = $query[$i]['buybackItemType'];
                $data[$i]['feedType'] = $query[$i]['feedType'];
                $data[$i]['itemSystemCode'] = $query[$i]['itemSystemCode'];
                $data[$i]['itemDescription'] = $query[$i]['itemDescription'];
                $data[$i]['itemCategory'] = $query[$i]['itemCategory'];
                $data[$i]['financeCategory'] = $query[$i]['financeCategory'];
                $data[$i]['itemFinanceCategory'] = $query[$i]['itemFinanceCategory'];
                $data[$i]['itemFinanceCategorySub'] = $query[$i]['itemFinanceCategorySub'];
                $data[$i]['costGLAutoID'] = $query[$i]['costGLAutoID'];
                $data[$i]['costSystemGLCode'] = $query[$i]['costSystemGLCode'];
                $data[$i]['costGLCode'] = $query[$i]['costGLCode'];
                $data[$i]['costGLDescription'] = $query[$i]['costGLDescription'];
                $data[$i]['costGLType'] = $query[$i]['costGLType'];
                $data[$i]['assetGLAutoID'] = $query[$i]['assetGLAutoID'];
                $data[$i]['assetSystemGLCode'] = $query[$i]['assetSystemGLCode'];
                $data[$i]['assetGLCode'] = $query[$i]['assetGLCode'];
                $data[$i]['assetGLDescription'] = $query[$i]['assetGLDescription'];
                $data[$i]['assetGLType'] = $query[$i]['assetGLType'];
                $data[$i]['revenueGLAutoID'] = $query[$i]['revenueGLAutoID'];
                $data[$i]['revenueSystemGLCode'] = $query[$i]['revenueSystemGLCode'];
                $data[$i]['revenueGLCode'] = $query[$i]['revenueGLCode'];
                $data[$i]['revenueGLDescription'] = $query[$i]['revenueGLDescription'];
                $data[$i]['revenueGLType'] = $query[$i]['revenueGLType'];
                $data[$i]['defaultUOMID'] = $query[$i]['defaultUOMID'];
                $data[$i]['defaultUOM'] = $query[$i]['defaultUOM'];
                $data[$i]['unitOfMeasureID'] = $query[$i]['unitOfMeasureID'];
                $data[$i]['unitOfMeasure'] = $query[$i]['unitOfMeasure'];
                $data[$i]['conversionRateUOM'] = $query[$i]['conversionRateUOM'];
                $data[$i]['qty'] = $qty[$i];

                $data[$i]['unitTransferCost'] = $query[$i]['unitTransferCost'];
                $data[$i]['unitActualCost'] = $query[$i]['unitActualCost'] ;
                $data[$i]['unitTransferCostLocal'] = $query[$i]['unitTransferCostLocal'];
                $data[$i]['unitTransferCostReporting'] = $query[$i]['unitTransferCostReporting'];
                $data[$i]['totalTransferCost'] = $qty[$i] * $query[$i]['unitTransferCost'];
                $data[$i]['totalTransferCostLocal'] = $qty[$i] * $query[$i]['unitTransferCostLocal'];
                $data[$i]['totalTransferCostReporting'] =  $qty[$i] * $query[$i]['totalTransferCostReporting'];
                $data[$i]['totalActualCost'] = $qty[$i] * $query[$i]['unitActualCost'];
                $data[$i]['totalActualCostLocal'] = $data[$i]['totalActualCost'] / $returnmasterdetails['companyLocalExchangeRate'];
                $data[$i]['totalActualCostReporting'] = $data[$i]['totalActualCost'] /$returnmasterdetails['companyReportingExchangeRate'];


                $data[$i]['comment'] = $query[$i]['comment'];
                $data[$i]['remarks'] = $query[$i]['remarks'];
                $data[$i]['isReceived'] = $query[$i]['isReceived'];
                $data[$i]['companyCode'] = $this->common_data['company_data']['company_code'];
                $data[$i]['companyID'] = $this->common_data['company_data']['company_id'];
                $data[$i]['modifiedPCID'] = $this->common_data['current_pc'];
                $data[$i]['modifiedUserID'] = $this->common_data['current_userID'];
                $data[$i]['modifiedUserName'] = $this->common_data['current_user'];
                $data[$i]['modifiedDateTime'] = $this->common_data['current_date'];
                $data[$i]['createdUserGroup'] = $this->common_data['user_group'];
                $data[$i]['createdPCID'] = $this->common_data['current_pc'];
                $data[$i]['createdUserID'] = $this->common_data['current_userID'];
                $data[$i]['createdUserName'] = $this->common_data['current_user'];
                $data[$i]['createdDateTime'] = $this->common_data['current_date'];

            }
        }


        if (!empty($data)) {

            $this->db->insert_batch('srp_erp_buyback_dispatchreturndetails', $data);

            $this->db->select('Sum( unitActualCost) AS actualcost,dispatchreturnmaster.companyLocalExchangeRate AS companyLocalExchangeRate,dispatchreturndetail.returnAutoID AS returnAutoID,dispatchreturnmaster.companyReportingExchangeRate as companyReportingExchangeRate');
            $this->db->from('srp_erp_buyback_dispatchreturndetails dispatchreturndetail');
            $this->db->where('dispatchreturndetail.returnAutoID', $returnautoid);
            $this->db->join('srp_erp_buyback_dispatchreturn dispatchreturnmaster', 'dispatchreturndetail.returnAutoID = dispatchreturnmaster.returnAutoID', 'left');
            $transactionAmount = $this->db->get()->row_array();


            $company_loc = ($transactionAmount['actualcost'] / $transactionAmount['companyLocalExchangeRate']);
            $company_rep = ($transactionAmount['actualcost'] / $transactionAmount['companyReportingExchangeRate']);
            $transamount = ($transactionAmount['actualcost']);

            $datas['companyLocalAmount'] = $company_loc;
            $datas['transactionAmount'] = $transamount;
            $datas['companyReportingAmount'] = $company_rep;
            $this->db->where('returnAutoID', $returnautoid);
            $this->db->update('srp_erp_buyback_dispatchreturn', $datas);

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('e', 'Return : Details Save Failed ' . $this->db->_error_message());
                $this->db->trans_rollback();
                return array('status' => false);
            } else {
                $this->session->set_flashdata('s', 'Return: Item Details Saved Successfully.');
                $this->db->trans_commit();
                return array('status' => true);
            }
        }
    }

    function fetch_return_table_detail()
    {
        $this->db->select('*,note.documentSystemCode as documentSystemCodedipatch,CONCAT(itemDescription,\' - \',`note`.`documentSystemCode`) as description');
        $this->db->where('returnAutoID', trim($this->input->post('returnAutoID')));
        $this->db->from('srp_erp_buyback_dispatchreturndetails');
        $this->db->join('srp_erp_buyback_dispatchnote note', 'srp_erp_buyback_dispatchreturndetails.dispatchAutoID = note.dispatchAutoID', 'left');
        $data['detail'] = $this->db->get()->result_array();

        $this->db->select('transactionCurrency,transactionCurrencyDecimalPlaces');
        $this->db->where('returnAutoID', trim($this->input->post('returnAutoID')));
        $this->db->from('srp_erp_buyback_dispatchreturn');
        $data['currency'] = $this->db->get()->row_array();


        return $data;
    }

    function delete_return_detail()
    {

        $this->db->delete('srp_erp_buyback_dispatchreturndetails', array('returnDetailsID' => trim($this->input->post('returnDetailsID'))));
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Error while deleting!');
        } else {
            $this->db->trans_commit();
            return array('s', 'Return detail deleted successfully');
        }
    }

    function load_bubyack_return_header()
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('*,DATE_FORMAT(returnedDate,\'' . $convertFormat . '\') AS returnedDate,DATE_FORMAT(documentDate,\'' . $convertFormat . '\') AS documentDate');
        $this->db->where('returnAutoID', $this->input->post('returnAutoID'));
        return $this->db->get('srp_erp_buyback_dispatchreturn')->row_array();
    }

    function fetch_template_buyback_return_data($buybackreturn)
    {
        $convertFormat = convert_date_format_sql();
        $this->db->select('srp_erp_buyback_dispatchreturn.*,DATE_FORMAT(returnedDate,\'' . $convertFormat . '\') AS returnedDate,DATE_FORMAT(srp_erp_buyback_dispatchreturn.approvedDate,\'' . $convertFormat . ' %h:%i:%s\') AS approvedDate,DATE_FORMAT(documentDate,\'' . $convertFormat . ' \') AS documentDate,farm.description as farmdescription,batch.batchCode as batchCode,CONCAT(curency.CurrencyDes,\' ( \',curency.CurrencyShortCode,\' ) \') as currency,srp_erp_buyback_dispatchreturn.segmentCode as segmentCode');
        $this->db->where('returnAutoID', $buybackreturn);
        $this->db->from('srp_erp_buyback_dispatchreturn');
        $this->db->join('srp_erp_buyback_farmmaster farm', 'srp_erp_buyback_dispatchreturn.farmID = farm.farmID', 'left');
        $this->db->join('srp_erp_buyback_batch batch', 'srp_erp_buyback_dispatchreturn.batchMasterID = batch.batchMasterID', 'left');
        $this->db->join('srp_currencymaster curency', 'srp_erp_buyback_dispatchreturn.farmerCurrencyID = curency.CurrencyID', 'left');
        $data['master'] = $this->db->get()->row_array();

        $this->db->select('*,note.documentSystemCode as documentSystemCodedipatch,CONCAT(itemDescription,\' - \',`note`.`documentSystemCode`) as description');
        $this->db->where('returnAutoID', $buybackreturn);
        $this->db->from('srp_erp_buyback_dispatchreturndetails');
        $this->db->join('srp_erp_buyback_dispatchnote note', 'srp_erp_buyback_dispatchreturndetails.dispatchAutoID = note.dispatchAutoID', 'left');
        $data['detail'] = $this->db->get()->result_array();
        return $data;
    }

    function buyback_return_confirmation()
    {
        $this->db->select('returnDetailsID');
        $this->db->where('returnAutoID', trim($this->input->post('returnAutoID')));
        $this->db->from('srp_erp_buyback_dispatchreturndetails');
        $results = $this->db->get()->row_array();
        if (empty($results)) {
            return array('error' => 2, 'message' => 'There are no records to confirm this document!');
        } else {
            $this->db->select('returnAutoID');
            $this->db->where('returnAutoID', trim($this->input->post('returnAutoID')));
            $this->db->where('confirmedYN', 1);
            $this->db->from('srp_erp_buyback_dispatchreturn');
            $Confirmed = $this->db->get()->row_array();
            if (!empty($Confirmed)) {
                $this->session->set_flashdata('w', 'Document already confirmed ');
                return false;
            } else {
                $masterID = trim($this->input->post('returnAutoID'));
                $this->load->library('approvals');
                $this->db->select('returnAutoID, documentID,documentSystemCode');
                $this->db->where('returnAutoID', $masterID);
                $this->db->from('srp_erp_buyback_dispatchreturn');
                $app_data = $this->db->get()->row_array();


                $approvals_status = $this->approvals->CreateApproval('BBDR', $app_data['returnAutoID'], $app_data['documentSystemCode'], 'Return', 'srp_erp_buyback_dispatchreturn', 'returnAutoID');


                if ($approvals_status == 1) {
                    $data = array(
                        'confirmedYN' => 1,
                        'confirmedDate' => $this->common_data['current_date'],
                        'confirmedByEmpID' => $this->common_data['current_userID'],
                        'confirmedByName' => $this->common_data['current_user']
                    );

                    $this->db->where('returnAutoID', trim($this->input->post('returnAutoID')));
                    $this->db->update('srp_erp_buyback_dispatchreturn', $data);

                    return array('error' => 0, 'message' => 'document successfully confirmed');

                } else {
                    return array('error' => 1, 'message' => 'Approval setting are not configured!, please contact your system team.');
                }


            }
        }
    }

    function delete_return()
    {
        $this->db->select('*');
        $this->db->from('srp_erp_buyback_dispatchreturndetails');
        $this->db->where('returnAutoID', trim($this->input->post('returnAutoID')));
        $datas = $this->db->get()->row_array();
        if ($datas) {
            $this->session->set_flashdata('e', 'please delete all detail records before delete this document.');
            return true;
        } else {
            $data = array(
                'isDeleted' => 1,
                'deletedEmpID' => current_userID(),
                'deletedDate' => current_date(),
            );
            $this->db->where('returnAutoID', trim($this->input->post('returnAutoID')));
            $this->db->update('srp_erp_buyback_dispatchreturn', $data);
            $this->session->set_flashdata('s', 'Deleted Successfully.');
            return true;
        }
    }

    function re_open_buyback()
    {

        $data = array(
            'isDeleted' => 0,
        );
        $this->db->where('returnAutoID', trim($this->input->post('returnAutoID')));
        $this->db->update('srp_erp_buyback_dispatchreturn', $data);
        $this->session->set_flashdata('s', 'Re Opened Successfully.');
        return true;

    }

    function delete_farm_master()
    {

        $this->db->delete('srp_erp_buyback_farmmaster', array('farmID' => trim($this->input->post('farmID'))));
        return array('s', 'Farm deleted successfully.');

    }

    function get_wip_report()
    {
        $convertFormat = convert_date_format_sql();
        $farmid = $this->input->post('famerid');
        $search = $this->input->post('search');
        $date_format_policy = date_format_policy();
        $convertFormat = convert_date_format_sql();
        $datecurrent = $this->input->post('asdateof');
        $dateconvert = input_format_date($datecurrent, $date_format_policy);
        $date = "";
        $datedispatch = "";
        $datedispatchwip = "";
        $wipdocumentdate = "";
        $returndocumentdate = "";
        if (!empty($dateconvert)) {
            $date .= " AND ( disnotemaster.dispatchedDate <= '" . $dateconvert . " 00:00:00')";
        }
        if (!empty($dateconvert)) {
            $datedispatch .= " AND ( dpm.dispatchedDate <= '" . $dateconvert . " 00:00:00')";

        }

        if (!empty($dateconvert)) {
            $datedispatchwip .= " ( dpm.dispatchedDate <= '" . $dateconvert . " 00:00:00')";

        }
        if (!empty($dateconvert)) {
            $wipdocumentdate .= " ( grnm.documentDate <= '" . $dateconvert . " 00:00:00')";
        }
        $companyid = current_companyID();
        if ($search) {
            $search = " AND fm.description LIKE '%" . $search . "%' OR batchCode LIKE '%" . $search . "%'";
        } else {
            $search = "";
        }
        if (!empty($dateconvert)) {
            $returndocumentdate .= " ( documentDate <= '" . $dateconvert . " 00:00:00')";
        }



        $qry = "SELECT returnqty.returnvalue as returnvalue, grnval.creditTotal as creditTotal,DATE_FORMAT( disnotemaster.dispatchedDate, ' . %d-%m-%Y . ' ) AS dispatchedDate,wipamt.workinprogressamount as workinprogressamount,disnotemaster.dispatchAutoID as dispatchAutoID, batch.batchMasterID,receivedtotaltbl.receivedtotal AS receivedtotal,batchvalue.batchvalue as batchvalue,chicksTotaltbl.chicksTotal as chicksTotal,receivedtotaltbl.receivedtotal as receivedtotal,IFNULL(deadChicksTotal,'-') mortality,age.age as chicksage,currency.CurrencyCode as CurrencyCode,fm.farmerCurrencyID,batchCode,batch.farmID as farmid,DATE_FORMAT( batchStartDate, ' . %d-%m-%Y . ' ) AS batchStartDate,DATE_FORMAT( batchClosingDate, ' . %d-%m-%Y . ' ) AS batchClosingDate,isclosed,c1.systemAccountCode AS wip_SystemCode,c1.GLDescription AS wip_description,c2.systemAccountCode AS dw_SystemCode,c2.GLDescription AS dw_description,fm.description AS farmerName,fm.locationID,fm.subLocationID,fi.empID,isclosed,batch.confirmedYN,batch.approvedYN FROM srp_erp_buyback_batch batch LEFT JOIN srp_erp_chartofaccounts c1 ON c1.GLAutoID = batch.WIPGLAutoID  LEFT JOIN srp_erp_chartofaccounts c2 ON c2.GLAutoID = batch.DirectWagesGLAutoID LEFT JOIN srp_erp_buyback_farmmaster fm ON fm.farmID = batch.farmID LEFT JOIN srp_erp_currencymaster currency ON currency.currencyID = fm.farmerCurrencyID LEFT JOIN srp_erp_buyback_dispatchnote disnotemaster ON disnotemaster.batchMasterID = batch.batchMasterID LEFT JOIN srp_erp_buyback_farmfieldofficers fi ON fi.farmID = fm.farmID LEFT JOIN (SELECT COALESCE( sum( qty ), 0 ) AS chicksTotal,batchMasterID,confirmedYN,approvedYN FROM srp_erp_buyback_dispatchnotedetails dpd INNER JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID = dpd.dispatchAutoID AND buybackItemType = 1 WHERE dpm.confirmedYN = 1 AND dpm.approvedYN = 1 GROUP BY batchMasterID) chicksTotaltbl ON chicksTotaltbl.batchMasterID = batch.batchMasterID LEFT JOIN (SELECT COALESCE( sum( grnd.noOfBirds ), 0 ) AS receivedtotal,batchMasterID,confirmedYN,approvedYN FROM srp_erp_buyback_grndetails grnd INNER JOIN srp_erp_buyback_grn grn ON grn.grnAutoID = grnd.grnAutoID WHERE confirmedYN = 1 AND approvedYN = 1 GROUP BY batchMasterID) receivedtotaltbl ON receivedtotaltbl.batchMasterID = batch.batchMasterID LEFT JOIN (SELECT COALESCE( sum( noOfBirds ), 0 ) AS deadChicksTotal,batchMasterID,confirmedYN FROM srp_erp_buyback_mortalitymaster mm INNER JOIN srp_erp_buyback_mortalitydetails md ON mm.mortalityAutoID = md.mortalityAutoID  WHERE confirmedYN = 1 GROUP BY batchMasterID) deadChicksTotal ON deadChicksTotal.batchMasterID = batch.batchMasterID LEFT JOIN(SELECT dpm.dispatchedDate,batch.closedDate AS closingdate,dpm.batchMasterID,CASE WHEN batch.closedDate = ' ' THEN DATEDIFF( batch.closedDate, documentDate ) + 1 ELSE DATEDIFF( CURRENT_DATE, documentDate ) + 1 END AS age FROM srp_erp_buyback_dispatchnote dpm INNER JOIN srp_erp_buyback_dispatchnotedetails dpd ON dpd.dispatchAutoID = dpm.dispatchAutoID AND buybackItemType = 1 LEFT JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = dpm.batchMasterID GROUP BY dpm.batchMasterID) age ON age.batchMasterID = batch.batchMasterID LEFT JOIN (SELECT COALESCE( sum( totalTransferCost ), 0 ) AS batchvalue,batchMasterID FROM srp_erp_buyback_dispatchnotedetails dpd LEFT JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID = dpd.dispatchAutoID WHERE confirmedYN = 1 AND approvedYN = 1 $datedispatch GROUP BY batchMasterID )batchvalue on batchvalue.batchMasterID = batch.batchMasterID LEFT JOIN (SELECT sum( dpd.totalActualCost ) AS workinprogressamount,dpm.dispatchAutoID,batchMasterID FROM srp_erp_buyback_dispatchnotedetails dpd INNER JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID = dpd.dispatchAutoID WHERE $datedispatchwip GROUP BY batchMasterID) wipamt ON wipamt.batchMasterID = batch.batchMasterID LEFT JOIN (SELECT sum( grnd.totalCost ) AS creditTotal,batchMasterID FROM srp_erp_buyback_grndetails grnd INNER JOIN srp_erp_buyback_grn grnm ON grnm.grnAutoID = grnd.grnAutoID WHERE $wipdocumentdate GROUP BY grnm.batchMasterID) grnval ON grnval.batchMasterID = batch.batchMasterID
 LEFT JOIN(SELECT COALESCE ( sum( dpdr.totalTransferCost ), 0 ) AS returnvalue,dispatchAutoID,dpdr.returnAutoID,retun.batchMasterID FROM srp_erp_buyback_dispatchreturndetails dpdr INNER JOIN srp_erp_buyback_dispatchreturn retun on retun.returnAutoID = dpdr.returnAutoID WHERE approvedYN  = 1 AND confirmedYN = 1 AND $returndocumentdate  GROUP BY batchMasterID)returnqty on returnqty.batchMasterID = batch.batchMasterID WHERE batch.companyID = $companyid AND chicksTotaltbl.confirmedYN = 1 AND chicksTotaltbl.approvedYN = 1 AND batch.isclosed = 0 AND batch.farmID IN (" . join(',', $farmid) . ") $search $date GROUP BY batch.batchMasterID";

        $output = $this->db->query($qry)->result_array();
        return $output;
    }

    function get_dispatchnote_drilldown()
    {
        $batchid = $this->input->post('batchid');
        $companyid = current_companyID();

        $date_format_policy = date_format_policy();
        $convertFormat = convert_date_format_sql();
        $datecurrent = $this->input->post('date');
        $dateconvert = input_format_date($datecurrent, $date_format_policy);
        $date = '';
        if (!empty($dateconvert)) {
            $date .= " AND ( dpn.dispatchedDate <= '" . $dateconvert . " 00:00:00')";
        }

        $details = $this->db->query("SELECT batch.batchCode as batchCode, dpn.dispatchAutoID as dispatchAutoID,dpn.documentID as documentID,dispatchdetail.TransferTotal,dpn.batchMasterID as batchMasterID,dpn.documentSystemCode as documentSystemCode,segmentCode,fm.description AS farmName,dpn.confirmedYN,dpn.approvedYN,DATE_FORMAT( dispatchedDate, '%d-%m-%Y' ) AS dispatchedDate,DATE_FORMAT( documentDate, '%d-%m-%Y' ) AS documentDate,dpn.Narration,dpn.transactionCurrency AS detailCurrency,dpn.createdUserID,batch.batchCode,fm.farmType AS farmerType FROM srp_erp_buyback_dispatchnote dpn LEFT JOIN (SELECT sum(totalTransferCost) as TransferTotal,dispatchAutoID from srp_erp_buyback_dispatchnotedetails GROUP BY dispatchAutoID)dispatchdetail on dispatchdetail.dispatchAutoID = dpn.dispatchAutoID
LEFT JOIN srp_erp_buyback_farmmaster fm ON fm.farmID = dpn.farmID LEFT JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = dpn.batchMasterID WHERE dpn.companyID = $companyid $date AND dpn.confirmedYN = 1 AND dpn.approvedYN = 1  AND dpn.batchMasterID = $batchid ORDER BY dispatchAutoID DESC")->result_array();

        return $details;
    }
    function fetch_double_entry_buyback_return($returnautoid, $code = null)
    {
        $gl_array = array();
        $inv_total = 0;
        $dr_total = 0;
        $party_total = 0;
        $companyLocal_total = 0;
        $companyReporting_total = 0;
        $tax_total = 0;
        $gl_array['gl_detail'] = array();
        $this->db->select('*');
        $this->db->where('returnAutoID', $returnautoid);
        $master = $this->db->get('srp_erp_buyback_dispatchreturn')->row_array();

        $creditGL = $this->db->query("SELECT drd.returnAutoID,sum( drd.totalActualCost ) AS workinprogressamount,( sum( drd.totalTransferCost ) - sum( drd.totalActualCost ) ) AS wagesamount,drm.batchMasterID,segmentID,segmentCode,wp.GLAutoID,wp.GLSecondaryCode,wp.GLDescription,wp.systemAccountCode,wp.subCategory AS subCategory,apc.GLAutoID AS DWGLAutoID,apc.GLSecondaryCode AS DWGLSecondaryCode,apc.GLDescription AS DWGLDescription,apc.systemAccountCode AS DWsystemAccountCode,apc.subCategory AS DWsubCategory FROM
srp_erp_buyback_dispatchreturndetails drd INNER JOIN srp_erp_buyback_dispatchreturn drm ON drm.returnAutoID = drd.returnAutoID INNER JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = drm.batchMasterID INNER JOIN srp_erp_buyback_farmmaster fm ON fm.farmID = drm.farmID LEFT JOIN ( SELECT * FROM srp_erp_chartofaccounts ) wp ON ( batch.WIPGLAutoID = wp.GLAutoID )LEFT JOIN ( SELECT * FROM srp_erp_chartofaccounts ) apc ON ( fm.farmersLiabilityGLautoID = apc.GLAutoID ) WHERE drd.returnAutoID = $returnautoid")->row_array();
        $this->db->select('*');
        $this->db->where('farmID', $master['farmID']);
        $farmerDetail = $this->db->get('srp_erp_buyback_farmmaster')->row_array();

        $globalArray = array();
        /*creditGL*/
        if ($creditGL) {
            $data_arr['auto_id'] = $creditGL['returnAutoID'];
            $data_arr['gl_auto_id'] = $creditGL['GLAutoID'];
            $data_arr['gl_code'] = $creditGL['systemAccountCode'];
            $data_arr['secondary'] = $creditGL['GLSecondaryCode'];
            $data_arr['gl_desc'] = $creditGL['GLDescription'];
            $data_arr['gl_type'] = $creditGL['subCategory'];
            $data_arr['segment_id'] = $creditGL['segmentID'];
            $data_arr['segment'] = $creditGL['segmentCode'];
            $data_arr['projectID'] = NULL;
            $data_arr['projectExchangeRate'] = NULL;
            $data_arr['isAddon'] = 0;
            $data_arr['subLedgerType'] = 0;
            $data_arr['subLedgerDesc'] = null;
            $data_arr['partyContractID'] = null;
            $data_arr['partyType'] = 'Farmer';
            $data_arr['partyAutoID'] = $master['farmID'];
            $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
            $data_arr['partyName'] = $farmerDetail['description'];
            $data_arr['partyCurrencyID'] = $master['farmerCurrencyID'];
            $data_arr['partyCurrency'] = $master['farmerCurrency'];
            $data_arr['transactionExchangeRate'] = null;
            $data_arr['companyLocalExchangeRate'] = null;
            $data_arr['companyReportingExchangeRate'] = null;
            $data_arr['transactionExchangeRate'] = 1;
            $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
            $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
            $data_arr['partyExchangeRate'] = $master['farmerCurrencyExchangeRate'];
            $data_arr['partyCurrencyAmount'] = 0;
            $data_arr['partyCurrencyDecimalPlaces'] = $master['farmerCurrencyDecimalPlaces'];
            $data_arr['gl_cr'] = $creditGL['workinprogressamount'];
            $data_arr['gl_dr'] = '';
            $data_arr['amount_type'] = 'dr';
            array_push($globalArray, $data_arr);

            $data_arr['auto_id'] = $creditGL['returnAutoID'];
            $data_arr['gl_auto_id'] = $creditGL['DWGLAutoID'];
            $data_arr['gl_code'] = $creditGL['DWsystemAccountCode'];
            $data_arr['secondary'] = $creditGL['DWGLSecondaryCode'];
            $data_arr['gl_desc'] = $creditGL['DWGLDescription'];
            $data_arr['gl_type'] = $creditGL['DWsubCategory'];
            $data_arr['segment_id'] = $creditGL['segmentID'];
            $data_arr['segment'] = $creditGL['segmentCode'];
            $data_arr['projectID'] = NULL;
            $data_arr['projectExchangeRate'] = NULL;
            $data_arr['isAddon'] = 0;
            $data_arr['subLedgerType'] = 0;
            $data_arr['subLedgerDesc'] = null;
            $data_arr['partyContractID'] = null;
            $data_arr['partyType'] = 'Farmer';
            $data_arr['partyAutoID'] = $master['farmID'];
            $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
            $data_arr['partyName'] = $farmerDetail['description'];
            $data_arr['partyCurrencyID'] = $master['farmerCurrencyID'];
            $data_arr['partyCurrency'] = $master['farmerCurrency'];
            $data_arr['transactionExchangeRate'] = null;
            $data_arr['companyLocalExchangeRate'] = null;
            $data_arr['companyReportingExchangeRate'] = null;
            $data_arr['transactionExchangeRate'] = 1;
            $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
            $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
            $data_arr['partyExchangeRate'] = $master['farmerCurrencyExchangeRate'];
            $data_arr['partyCurrencyAmount'] = 0;
            $data_arr['partyCurrencyDecimalPlaces'] = $master['farmerCurrencyDecimalPlaces'];
            if ($creditGL['wagesamount'] < 0) {
                $data_arr['gl_dr'] =  $creditGL['wagesamount'];
                $data_arr['gl_cr'] ='';
            } else {
                $data_arr['gl_dr'] = '';
                $data_arr['gl_cr'] = $creditGL['wagesamount'];
            }
            $data_arr['amount_type'] = 'dr';
            array_push($globalArray, $data_arr);
        }

        /*item GL Asset*/
        $assetGL = $this->db->query("SELECT dpd.returnAutoID,sum( dpd.totalActualCost ) AS assetGLTotal,dpm.batchMasterID,segmentID,segmentCode,assetGLAutoID,assetSystemGLCode,assetGLCode,assetGLDescription,assetGLType FROM srp_erp_buyback_dispatchreturndetails dpd INNER JOIN srp_erp_buyback_dispatchreturn dpm ON dpm.returnAutoID = dpd.returnAutoID INNER JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = dpm.batchMasterID WHERE dpd.returnAutoID = $returnautoid GROUP BY assetGLAutoID")->result_array();
        if ($assetGL) {
            foreach ($assetGL as $asset) {
                $data_arr['auto_id'] = $asset['returnAutoID'];
                $data_arr['gl_auto_id'] = $asset['assetGLAutoID'];
                $data_arr['gl_code'] = $asset['assetSystemGLCode'];
                $data_arr['secondary'] = $asset['assetGLCode'];
                $data_arr['gl_desc'] = $asset['assetGLDescription'];
                $data_arr['gl_type'] = $asset['assetGLType'];
                $data_arr['segment_id'] = $asset['segmentID'];
                $data_arr['segment'] = $asset['segmentCode'];
                $data_arr['projectID'] = NULL;
                $data_arr['projectExchangeRate'] = NULL;
                $data_arr['isAddon'] = 0;
                $data_arr['subLedgerType'] = 0;
                $data_arr['subLedgerDesc'] = null;
                $data_arr['partyContractID'] = null;
                $data_arr['partyType'] = 'Farmer';
                $data_arr['partyAutoID'] = $master['farmID'];
                $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
                $data_arr['partyName'] = $farmerDetail['description'];
                $data_arr['partyCurrencyID'] = $master['farmerCurrencyID'];
                $data_arr['partyCurrency'] = $master['farmerCurrency'];
                $data_arr['transactionExchangeRate'] = null;
                $data_arr['companyLocalExchangeRate'] = null;
                $data_arr['companyReportingExchangeRate'] = null;
                $data_arr['transactionExchangeRate'] = 1;
                $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                $data_arr['partyExchangeRate'] = $master['farmerCurrencyExchangeRate'];
                $data_arr['partyCurrencyAmount'] = 0;
                $data_arr['partyCurrencyDecimalPlaces'] = $master['farmerCurrencyDecimalPlaces'];;
                $data_arr['gl_dr'] = $asset['assetGLTotal'];
                $data_arr['gl_cr'] = '';
                $data_arr['amount_type'] = 'cr';
                array_push($globalArray, $data_arr);
            }
        }

        /*item GL Revenue*/
        $revenueGL = $this->db->query("SELECT dpd.returnAutoID,( sum( dpd.totalTransferCost ) - sum( dpd.totalActualCost ) ) AS revenueGLTotal,dpm.batchMasterID,segmentID,segmentCode,revenueGLAutoID,revenueSystemGLCode,revenueGLCode,revenueGLDescription,revenueGLType FROM srp_erp_buyback_dispatchreturndetails dpd INNER JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID = dpd.dispatchAutoID INNER JOIN srp_erp_buyback_batch batch ON batch.batchMasterID = dpm.batchMasterID WHERE dpd.returnAutoID = $returnautoid GROUP BY revenueGLAutoID,batchMasterID")->result_array();
        if ($revenueGL) {
            foreach ($revenueGL as $revenue) {
                $data_arr['auto_id'] = $revenue['returnAutoID'];
                $data_arr['gl_auto_id'] = $revenue['revenueGLAutoID'];
                $data_arr['gl_code'] = $revenue['revenueSystemGLCode'];
                $data_arr['secondary'] = $revenue['revenueGLCode'];
                $data_arr['gl_desc'] = $revenue['revenueGLDescription'];
                $data_arr['gl_type'] = $revenue['revenueGLType'];
                $data_arr['segment_id'] = $revenue['segmentID'];
                $data_arr['segment'] = $revenue['segmentCode'];
                $data_arr['projectID'] = NULL;
                $data_arr['projectExchangeRate'] = NULL;
                $data_arr['isAddon'] = 0;
                $data_arr['subLedgerType'] = 0;
                $data_arr['subLedgerDesc'] = null;
                $data_arr['partyContractID'] = null;
                $data_arr['partyType'] = 'Farmer';
                $data_arr['partyAutoID'] = $master['farmID'];
                $data_arr['partySystemCode'] = $farmerDetail['farmSystemCode'];
                $data_arr['partyName'] = $farmerDetail['description'];
                $data_arr['partyCurrencyID'] = $master['farmerCurrencyID'];
                $data_arr['partyCurrency'] = $master['farmerCurrency'];
                $data_arr['transactionExchangeRate'] = null;
                $data_arr['companyLocalExchangeRate'] = null;
                $data_arr['companyReportingExchangeRate'] = null;
                $data_arr['transactionExchangeRate'] = 1;
                $data_arr['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                $data_arr['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                $data_arr['partyExchangeRate'] = $master['farmerCurrencyExchangeRate'];
                $data_arr['partyCurrencyAmount'] = 0;
                $data_arr['partyCurrencyDecimalPlaces'] = $master['farmerCurrencyDecimalPlaces'];
                if ($revenue['revenueGLTotal'] < 0) {
                    $data_arr['gl_dr'] =  '';
                    $data_arr['gl_cr'] =$revenue['revenueGLTotal'];
                } else {
                    $data_arr['gl_dr'] = $revenue['revenueGLTotal'];
                    $data_arr['gl_cr'] = '';
                }
                $data_arr['amount_type'] = 'cr';
                array_push($globalArray, $data_arr);
            }
        }

        $gl_array['currency'] = $master['transactionCurrency'];
        $gl_array['decimal_places'] = $master['transactionCurrencyDecimalPlaces'];
        $gl_array['code'] = 'BBDR';
        $gl_array['name'] = 'Return';
        $gl_array['primary_Code'] = $master['documentSystemCode'];
        $gl_array['date'] = $master['documentDate'];
        $gl_array['finance_year'] = $master['companyFinanceYear'];
        $gl_array['finance_period'] = $master['FYPeriodDateFrom'] . ' - ' . $master['FYPeriodDateTo'];
        $gl_array['master_data'] = $master;
        $gl_array['farmer'] = $farmerDetail;
        $gl_array['gl_detail'] = $globalArray;

        return $gl_array;
    }
    function save_return_approval()
    {

        $this->db->trans_start();
        $this->load->library('approvals');
        $system_id = trim($this->input->post('ReturnAutoID'));
        $level_id = trim($this->input->post('Level'));
        $status = trim($this->input->post('status'));
        $comments = trim($this->input->post('comments'));
        $approvals_status = $this->approvals->approve_document($system_id, $level_id, $status, $comments, 'BBDR');

        if ($approvals_status == 1) {
            $master = $this->db->query("select * from srp_erp_buyback_dispatchreturn WHERE returnAutoID ={$system_id} ")->row_array();

            /*            $erp_item_detail = $this->db->query("select dpd.*,dpd.dispatchAutoID,dpd.itemAutoID,conversionRateUOM,itemSystemCode,itemDescription, defaultUOMID,defaultUOM,unitOfMeasureID,unitOfMeasure,sum(qty) as qty,sum(totalActualCost) as transactionAmount,sum(totalActualCostLocal)as companyLocalAmount,sum(totalActualCostReporting) as companyReportingAmount,wareHouseAutoID,wareHouseCode,wareHouseLocation,wareHouseDescription FROM srp_erp_buyback_dispatchnotedetails dpd INNER JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID = dpd.dispatchAutoID WHERE dpd.dispatchAutoID = {$system_id} GROUP BY itemAutoID,buybackItemType")->result_array();*/

            $erp_item_detail = $this->db->query("SELECT dpd.returnAutoID,dpd.itemAutoID,conversionRateUOM,itemSystemCode,itemDescription,defaultUOMID,defaultUOM,unitOfMeasureID,
unitOfMeasure,sum( qty ) AS qty,sum( totalTransferCost ) AS transactionAmount,sum( totalTransferCostLocal ) AS companyLocalAmount,sum( totalTransferCostReporting ) AS companyReportingAmount,wareHouseAutoID,wareHouseCode,wareHouseLocation,wareHouseDescription FROM srp_erp_buyback_dispatchreturndetails dpd INNER JOIN srp_erp_buyback_dispatchreturn dpm ON dpm.returnAutoID = dpd.returnAutoID WHERE
dpd.returnAutoID = {$system_id} GROUP BY itemAutoID")->result_array();

            $erp_itemDetail_bb_ledger = $this->db->query("SELECT dpd.*,dpd.returnAutoID,dpd.itemAutoID,conversionRateUOM,itemSystemCode,itemDescription,defaultUOMID,defaultUOM,unitOfMeasureID,unitOfMeasure,wareHouseAutoID,wareHouseCode,wareHouseLocation,wareHouseDescription FROM srp_erp_buyback_dispatchreturndetails dpd INNER JOIN srp_erp_buyback_dispatchreturn dpm ON dpm.returnAutoID = dpd.returnAutoID WHERE dpd.returnAutoID = {$system_id} ORDER BY buybackItemType")->result_array();

            for ($a = 0; $a < count($erp_itemDetail_bb_ledger); $a++) {
                $item = fetch_item_data($erp_itemDetail_bb_ledger[$a]['itemAutoID']);
                if ($item['mainCategory'] == 'Inventory' or $item['mainCategory'] == 'Non Inventory') {

                    $itemAutoID = $erp_itemDetail_bb_ledger[$a]['itemAutoID'];
                    $qty = $erp_itemDetail_bb_ledger[$a]['qty'] / $erp_itemDetail_bb_ledger[$a]['conversionRateUOM'];
                    $wareHouseAutoID = $erp_itemDetail_bb_ledger[$a]['wareHouseAutoID'];

                    $this->db->select('*');
                    $this->db->from('srp_erp_warehouseitems');
                    $this->db->where('wareHouseAutoID', $master['wareHouseAutoID']);
                    $this->db->where('itemAutoID',$erp_itemDetail_bb_ledger[$a]['itemAutoID']);
                    $warehouseItem = $this->db->get()->row_array();
                    $newStock_warehouse = $warehouseItem['currentStock'] + $qty;

                    $warehouseItemData[$a]['warehouseItemsAutoID'] = $warehouseItem['warehouseItemsAutoID'];
                    $warehouseItemData[$a]['currentStock'] = $newStock_warehouse;


                    $item_arr[$a]['itemAutoID'] = $erp_itemDetail_bb_ledger[$a]['itemAutoID'];
                    $item_arr[$a]['currentStock'] = ($item['currentStock'] + $qty);

                   /* $item_arr[$a]['companyLocalWacAmount'] = round(((($item['currentStock'] * $item['companyLocalWacAmount']) - ($item['companyLocalWacAmount'] * $qty)) / $item_arr[$a]['currentStock']), $master['companyLocalCurrencyDecimalPlaces']);*/


                    $item_arr[$a]['companyLocalWacAmount'] =   $this->calculateNewWAC_Return($item['currentStock'], $item['companyLocalWacAmount'], $erp_itemDetail_bb_ledger[$a]['qty'],$erp_itemDetail_bb_ledger[$a]['unitTransferCost'], $master['companyLocalCurrencyDecimalPlaces']);

                    /* $item_arr[$a]['companyReportingWacAmount'] = round(((($item['currentStock'] * $item['companyReportingWacAmount']) - ($item['companyReportingWacAmount'] * $qty)) / $item_arr[$a]['currentStock']), $master['companyReportingCurrencyDecimalPlaces']);*/

                    $item_arr[$a]['companyLocalWacAmount'] =   $this->calculateNewWAC_Return($item['currentStock'], $item['companyReportingWacAmount'], $erp_itemDetail_bb_ledger[$a]['qty'],$erp_itemDetail_bb_ledger[$a]['unitTransferCost'], $master['companyLocalCurrencyDecimalPlaces']);

                    $itemledger_arr_buyback[$a]['documentCode'] = $master['documentID'];
                    $itemledger_arr_buyback[$a]['documentAutoID'] = $master['returnAutoID'];
                    $itemledger_arr_buyback[$a]['documentSystemCode'] = $master['documentSystemCode'];
                    $itemledger_arr_buyback[$a]['documentDate'] = $master['documentDate'];
                    $itemledger_arr_buyback[$a]['batchID'] = $master['batchMasterID'];
                    $itemledger_arr_buyback[$a]['farmID'] = $master['farmID'];
                    $itemledger_arr_buyback[$a]['referenceNumber'] = $master['referenceNo'];
                    $itemledger_arr_buyback[$a]['companyFinanceYearID'] = $master['companyFinanceYearID'];
                    $itemledger_arr_buyback[$a]['companyFinanceYear'] = $master['companyFinanceYear'];
                    $itemledger_arr_buyback[$a]['FYBegin'] = $master['FYBegin'];
                    $itemledger_arr_buyback[$a]['FYEnd'] = $master['FYEnd'];
                    $itemledger_arr_buyback[$a]['FYPeriodDateFrom'] = $master['FYPeriodDateFrom'];
                    $itemledger_arr_buyback[$a]['FYPeriodDateTo'] = $master['FYPeriodDateTo'];
                    $itemledger_arr_buyback[$a]['wareHouseAutoID'] = $erp_itemDetail_bb_ledger[$a]['wareHouseAutoID'];
                    $itemledger_arr_buyback[$a]['wareHouseCode'] = $erp_itemDetail_bb_ledger[$a]['wareHouseCode'];
                    $itemledger_arr_buyback[$a]['wareHouseLocation'] = $erp_itemDetail_bb_ledger[$a]['wareHouseLocation'];
                    $itemledger_arr_buyback[$a]['wareHouseDescription'] = $erp_itemDetail_bb_ledger[$a]['wareHouseDescription'];
                    $itemledger_arr_buyback[$a]['itemAutoID'] = $erp_itemDetail_bb_ledger[$a]['itemAutoID'];
                    $itemledger_arr_buyback[$a]['itemSystemCode'] = $erp_itemDetail_bb_ledger[$a]['itemSystemCode'];
                    $itemledger_arr_buyback[$a]['buybackItemType'] = $erp_itemDetail_bb_ledger[$a]['buybackItemType'];
                    $itemledger_arr_buyback[$a]['itemDescription'] = $erp_itemDetail_bb_ledger[$a]['itemDescription'];
                    $itemledger_arr_buyback[$a]['defaultUOMID'] = $erp_itemDetail_bb_ledger[$a]['defaultUOMID'];
                    $itemledger_arr_buyback[$a]['defaultUOM'] = $erp_itemDetail_bb_ledger[$a]['defaultUOM'];
                    $itemledger_arr_buyback[$a]['transactionUOMID'] = $erp_itemDetail_bb_ledger[$a]['unitOfMeasureID'];
                    $itemledger_arr_buyback[$a]['transactionUOM'] = $erp_itemDetail_bb_ledger[$a]['unitOfMeasure'];
                    $itemledger_arr_buyback[$a]['transactionQTY'] = $erp_itemDetail_bb_ledger[$a]['qty'];
                    $itemledger_arr_buyback[$a]['convertionRate'] = $erp_itemDetail_bb_ledger[$a]['conversionRateUOM'];
                    $itemledger_arr_buyback[$a]['currentStock'] = $item_arr[$a]['currentStock'];

                    $itemledger_arr_buyback[$a]['expenseGLAutoID'] = $item['costGLAutoID'];
                    $itemledger_arr_buyback[$a]['expenseGLCode'] = $item['costGLCode'];
                    $itemledger_arr_buyback[$a]['expenseSystemGLCode'] = $item['costSystemGLCode'];
                    $itemledger_arr_buyback[$a]['expenseGLDescription'] = $item['costDescription'];
                    $itemledger_arr_buyback[$a]['expenseGLType'] = $item['costType'];
                    $itemledger_arr_buyback[$a]['revenueGLAutoID'] = $item['revanueGLAutoID'];
                    $itemledger_arr_buyback[$a]['revenueGLCode'] = $item['revanueGLCode'];
                    $itemledger_arr_buyback[$a]['revenueSystemGLCode'] = $item['revanueSystemGLCode'];
                    $itemledger_arr_buyback[$a]['revenueGLDescription'] = $item['revanueDescription'];
                    $itemledger_arr_buyback[$a]['revenueGLType'] = $item['revanueType'];
                    $itemledger_arr_buyback[$a]['assetGLAutoID'] = $item['assteGLAutoID'];
                    $itemledger_arr_buyback[$a]['assetGLCode'] = $item['assteGLCode'];
                    $itemledger_arr_buyback[$a]['assetSystemGLCode'] = $item['assteSystemGLCode'];
                    $itemledger_arr_buyback[$a]['assetGLDescription'] = $item['assteDescription'];
                    $itemledger_arr_buyback[$a]['assetGLType'] = $item['assteType'];
                    $itemledger_arr_buyback[$a]['transactionCurrencyDecimalPlaces'] = $master['transactionCurrencyDecimalPlaces'];

                    // item price with wac cost (actual cost)
                    $unitTransactionAmount = $erp_itemDetail_bb_ledger[$a]['unitActualCost'] / $master['transactionExchangeRate'];
                    $itemledger_arr_buyback[$a]['unitTransactionAmount'] = round($unitTransactionAmount, $master['transactionCurrencyDecimalPlaces']);
                    $itemledger_arr_buyback[$a]['totalTransactionAmount'] = $itemledger_arr_buyback[$a]['unitTransactionAmount'] * $erp_itemDetail_bb_ledger[$a]['qty'];
                    $unitLocalAmount = $erp_itemDetail_bb_ledger[$a]['unitActualCost'] / $master['companyLocalExchangeRate'];
                    $itemledger_arr_buyback[$a]['unitLocalAmount'] = round($unitLocalAmount, $master['companyLocalCurrencyDecimalPlaces']);;
                    $itemledger_arr_buyback[$a]['totalLocalAmount'] = ($erp_itemDetail_bb_ledger[$a]['qty'] * $itemledger_arr_buyback[$a]['unitLocalAmount']);

                    $unitReportingAmount = $erp_itemDetail_bb_ledger[$a]['unitActualCost'] / $master['companyReportingExchangeRate'];
                    $itemledger_arr_buyback[$a]['unitReportingAmount'] = round($unitReportingAmount, $master['companyReportingCurrencyDecimalPlaces']);
                    $itemledger_arr_buyback[$a]['totalReportingAmount'] = $itemledger_arr_buyback[$a]['unitReportingAmount'] * $erp_itemDetail_bb_ledger[$a]['qty'];

                    // item price with transaction price (transaction cost)
                    $itemledger_arr_buyback[$a]['unitTransferAmountTransaction'] = $erp_itemDetail_bb_ledger[$a]['unitTransferCost'];
                    $itemledger_arr_buyback[$a]['totalTransferAmountTransaction'] = $erp_itemDetail_bb_ledger[$a]['totalTransferCost'];

                    $itemledger_arr_buyback[$a]['unitTransferAmountLocal'] = $erp_itemDetail_bb_ledger[$a]['unitTransferCostLocal'];
                    $itemledger_arr_buyback[$a]['totalTransferAmountLocal'] = $erp_itemDetail_bb_ledger[$a]['totalTransferCostLocal'];

                    $itemledger_arr_buyback[$a]['unitTransferAmountReporting'] = $erp_itemDetail_bb_ledger[$a]['unitTransferCostReporting'];
                    $itemledger_arr_buyback[$a]['totalTranferAmountReporting'] = ($erp_itemDetail_bb_ledger[$a]['totalTransferCostReporting']);

                    //$ex_rate_wac = (1 / $master['companyLocalExchangeRate']);
                    //$itemledger_arr_buyback[$a]['transactionAmount'] = round((($item_detail[$a]['companyLocalWacAmount'] / $ex_rate_wac) * ($itemledger_arr_buyback[$a]['transactionQTY'] / $item_detail[$a]['conversionRateUOM'])), $itemledger_arr_buyback[$a]['transactionCurrencyDecimalPlaces']);
                    //$itemledger_arr_buyback[$a]['salesPrice'] = (($item_detail[$a]['transactionAmount'] / ($itemledger_arr_buyback[$a]['transactionQTY'] / $item_detail[$a]['conversionRateUOM'])) * -1);
                    $itemledger_arr_buyback[$a]['transactionCurrencyID'] = $master['transactionCurrencyID'];
                    $itemledger_arr_buyback[$a]['transactionCurrency'] = $master['transactionCurrency'];
                    $itemledger_arr_buyback[$a]['transactionExchangeRate'] = $master['transactionExchangeRate'];

                    $itemledger_arr_buyback[$a]['companyLocalCurrencyID'] = $master['companyLocalCurrencyID'];
                    $itemledger_arr_buyback[$a]['companyLocalCurrency'] = $master['companyLocalCurrency'];
                    $itemledger_arr_buyback[$a]['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                    $itemledger_arr_buyback[$a]['companyLocalCurrencyDecimalPlaces'] = $master['companyLocalCurrencyDecimalPlaces'];
                    //$itemledger_arr_buyback[$a]['companyLocalAmount'] = round(($itemledger_arr_buyback[$a]['transactionAmount'] / $itemledger_arr_buyback[$a]['companyLocalExchangeRate']), $itemledger_arr_buyback[$a]['companyLocalCurrencyDecimalPlaces']);
                    $itemledger_arr_buyback[$a]['companyLocalWacAmount'] = $item['companyLocalWacAmount'];
                    $itemledger_arr_buyback[$a]['companyReportingCurrencyID'] = $master['companyReportingCurrencyID'];
                    $itemledger_arr_buyback[$a]['companyReportingCurrency'] = $master['companyReportingCurrency'];
                    $itemledger_arr_buyback[$a]['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                    $itemledger_arr_buyback[$a]['companyReportingCurrencyDecimalPlaces'] = $master['companyReportingCurrencyDecimalPlaces'];
                    //$itemledger_arr_buyback[$a]['companyReportingAmount'] = round(($itemledger_arr_buyback[$a]['transactionAmount'] / $itemledger_arr_buyback[$a]['companyReportingExchangeRate']), $itemledger_arr_buyback[$a]['companyReportingCurrencyDecimalPlaces']);
                    $itemledger_arr_buyback[$a]['companyReportingWacAmount'] = $item['companyReportingWacAmount'];

                    $itemledger_arr_buyback[$a]['partyCurrencyID'] = $master['farmerCurrencyID'];
                    $itemledger_arr_buyback[$a]['partyCurrency'] = $master['farmerCurrency'];
                    $itemledger_arr_buyback[$a]['partyCurrencyExchangeRate'] = $master['farmerCurrencyExchangeRate'];
                    $itemledger_arr_buyback[$a]['partyCurrencyDecimalPlaces'] = $master['farmerCurrencyDecimalPlaces'];
                    //$itemledger_arr_buyback[$a]['partyCurrencyAmount'] = round(($itemledger_arr_buyback[$a]['transactionAmount'] / $itemledger_arr_buyback[$a]['partyCurrencyExchangeRate']), $itemledger_arr_buyback[$a]['partyCurrencyDecimalPlaces']);

                    $itemledger_arr_buyback[$a]['confirmedYN'] = $master['confirmedYN'];
                    $itemledger_arr_buyback[$a]['confirmedByEmpID'] = $master['confirmedByEmpID'];
                    $itemledger_arr_buyback[$a]['confirmedByName'] = $master['confirmedByName'];
                    $itemledger_arr_buyback[$a]['confirmedDate'] = $master['confirmedDate'];
                    $itemledger_arr_buyback[$a]['approvedYN'] = $master['approvedYN'];
                    $itemledger_arr_buyback[$a]['approvedDate'] = $master['approvedDate'];
                    $itemledger_arr_buyback[$a]['approvedbyEmpID'] = $master['approvedbyEmpID'];
                    $itemledger_arr_buyback[$a]['approvedbyEmpName'] = $master['approvedbyEmpName'];
                    $itemledger_arr_buyback[$a]['segmentID'] = $master['segmentID'];
                    $itemledger_arr_buyback[$a]['segmentCode'] = $master['segmentCode'];
                    $itemledger_arr_buyback[$a]['companyID'] = $master['companyID'];
                    $itemledger_arr_buyback[$a]['companyCode'] = $master['companyCode'];
                    $itemledger_arr_buyback[$a]['createdUserGroup'] = $master['createdUserGroup'];
                    $itemledger_arr_buyback[$a]['createdPCID'] = $master['createdPCID'];
                    $itemledger_arr_buyback[$a]['createdUserID'] = $master['createdUserID'];
                    $itemledger_arr_buyback[$a]['createdDateTime'] = $master['createdDateTime'];
                    $itemledger_arr_buyback[$a]['createdUserName'] = $master['createdUserName'];
                    $itemledger_arr_buyback[$a]['modifiedPCID'] = $master['modifiedPCID'];
                    $itemledger_arr_buyback[$a]['modifiedUserID'] = $master['modifiedUserID'];
                    $itemledger_arr_buyback[$a]['modifiedDateTime'] = $master['modifiedDateTime'];
                    $itemledger_arr_buyback[$a]['modifiedUserName'] = $master['modifiedUserName'];
                }

            }

            for ($a = 0; $a < count($erp_item_detail); $a++) {

                $item = fetch_item_data($erp_item_detail[$a]['itemAutoID']);
                $itemAutoID = $erp_item_detail[$a]['itemAutoID'];

                $qty = $erp_item_detail[$a]['qty'] / $erp_item_detail[$a]['conversionRateUOM'];
                $wareHouseAutoID = $erp_item_detail[$a]['wareHouseAutoID'];
                $this->db->query("UPDATE srp_erp_warehouseitems SET currentStock = (currentStock -{$qty})  WHERE wareHouseAutoID='{$wareHouseAutoID}' and itemAutoID='{$itemAutoID}'");

                $item_arr_erp[$a]['itemAutoID'] = $erp_item_detail[$a]['itemAutoID'];
                $item_arr_erp[$a]['currentStock'] = ($item['currentStock'] - $qty);
                $item_arr_erps[$a]['companyLocalWacAmount'] = round(((($item['currentStock'] * $item['companyLocalWacAmount']) + ($erp_item_detail[$a]['companyLocalAmount'])) / $item_arr_erp[$a]['currentStock']), $master['companyLocalCurrencyDecimalPlaces']);
                $item_arr_erps[$a]['companyReportingWacAmount'] = round(((($item['currentStock'] * $item['companyReportingWacAmount']) + ($erp_item_detail[$a]['companyReportingAmount'] / $master['companyReportingExchangeRate'])) / $item_arr_erp[$a]['currentStock']), $master['companyReportingCurrencyDecimalPlaces']);

                $itemledger_arr_erp[$a]['documentID'] = $master['documentID'];
                $itemledger_arr_erp[$a]['documentCode'] = $master['documentID'];
                $itemledger_arr_erp[$a]['documentAutoID'] = $master['returnAutoID'];
                $itemledger_arr_erp[$a]['documentSystemCode'] = $master['documentSystemCode'];
                $itemledger_arr_erp[$a]['documentDate'] = $master['documentDate'];
                $itemledger_arr_erp[$a]['referenceNumber'] = $master['referenceNo'];
                $itemledger_arr_erp[$a]['companyFinanceYearID'] = $master['companyFinanceYearID'];
                $itemledger_arr_erp[$a]['companyFinanceYear'] = $master['companyFinanceYear'];
                $itemledger_arr_erp[$a]['FYBegin'] = $master['FYBegin'];
                $itemledger_arr_erp[$a]['FYEnd'] = $master['FYEnd'];
                $itemledger_arr_erp[$a]['FYPeriodDateFrom'] = $master['FYPeriodDateFrom'];
                $itemledger_arr_erp[$a]['FYPeriodDateTo'] = $master['FYPeriodDateTo'];
                $itemledger_arr_erp[$a]['wareHouseAutoID'] = $erp_item_detail[$a]['wareHouseAutoID'];
                $itemledger_arr_erp[$a]['wareHouseCode'] = $erp_item_detail[$a]['wareHouseCode'];
                $itemledger_arr_erp[$a]['wareHouseLocation'] = $erp_item_detail[$a]['wareHouseLocation'];
                $itemledger_arr_erp[$a]['wareHouseDescription'] = $erp_item_detail[$a]['wareHouseDescription'];
                $itemledger_arr_erp[$a]['itemAutoID'] = $erp_item_detail[$a]['itemAutoID'];
                $itemledger_arr_erp[$a]['itemSystemCode'] = $erp_item_detail[$a]['itemSystemCode'];
                $itemledger_arr_erp[$a]['itemDescription'] = $erp_item_detail[$a]['itemDescription'];
                $itemledger_arr_erp[$a]['defaultUOMID'] = $erp_item_detail[$a]['defaultUOMID'];
                $itemledger_arr_erp[$a]['defaultUOM'] = $erp_item_detail[$a]['defaultUOM'];
                $itemledger_arr_erp[$a]['transactionUOMID'] = $erp_item_detail[$a]['unitOfMeasureID'];
                $itemledger_arr_erp[$a]['transactionUOM'] = $erp_item_detail[$a]['unitOfMeasure'];
                $itemledger_arr_erp[$a]['transactionQTY'] = ($erp_item_detail[$a]['qty'] * -1);
                $itemledger_arr_erp[$a]['convertionRate'] = $erp_item_detail[$a]['conversionRateUOM'];
                $itemledger_arr_erp[$a]['currentStock'] = $item_arr_erp[$a]['currentStock'];
                $itemledger_arr_erp[$a]['PLGLAutoID'] = $item['revanueGLAutoID'];
                $itemledger_arr_erp[$a]['PLSystemGLCode'] = $item['revanueSystemGLCode'];
                $itemledger_arr_erp[$a]['PLGLCode'] = $item['revanueGLCode'];
                $itemledger_arr_erp[$a]['PLDescription'] = $item['revanueDescription'];
                $itemledger_arr_erp[$a]['PLType'] = $item['revanueType'];
                $itemledger_arr_erp[$a]['BLGLAutoID'] = $item['assteGLAutoID'];
                $itemledger_arr_erp[$a]['BLSystemGLCode'] = $item['assteSystemGLCode'];
                $itemledger_arr_erp[$a]['BLGLCode'] = $item['assteGLCode'];
                $itemledger_arr_erp[$a]['BLDescription'] = $item['assteDescription'];
                $itemledger_arr_erp[$a]['BLType'] = $item['assteType'];
                $itemledger_arr_erp[$a]['transactionCurrencyDecimalPlaces'] = $master['transactionCurrencyDecimalPlaces'];

                $itemledger_arr_erp[$a]['transactionAmount'] = round($erp_item_detail[$a]['transactionAmount'], $itemledger_arr_erp[$a]['transactionCurrencyDecimalPlaces']);
                $itemledger_arr_erp[$a]['salesPrice'] = NULL;
                $itemledger_arr_erp[$a]['transactionCurrencyID'] = $master['transactionCurrencyID'];
                $itemledger_arr_erp[$a]['transactionCurrency'] = $master['transactionCurrency'];
                $itemledger_arr_erp[$a]['transactionExchangeRate'] = $master['transactionExchangeRate'];

                $itemledger_arr_erp[$a]['companyLocalCurrencyID'] = $master['companyLocalCurrencyID'];
                $itemledger_arr_erp[$a]['companyLocalCurrency'] = $master['companyLocalCurrency'];
                $itemledger_arr_erp[$a]['companyLocalExchangeRate'] = $master['companyLocalExchangeRate'];
                $itemledger_arr_erp[$a]['companyLocalCurrencyDecimalPlaces'] = $master['companyLocalCurrencyDecimalPlaces'];
                $itemledger_arr_erp[$a]['companyLocalAmount'] = round(($itemledger_arr_erp[$a]['transactionAmount'] / $itemledger_arr_erp[$a]['companyLocalExchangeRate']), $itemledger_arr_erp[$a]['companyLocalCurrencyDecimalPlaces']);
                $itemledger_arr_erp[$a]['companyLocalWacAmount'] = $item['companyLocalWacAmount'];
                $itemledger_arr_erp[$a]['companyReportingCurrencyID'] = $master['companyReportingCurrencyID'];
                $itemledger_arr_erp[$a]['companyReportingCurrency'] = $master['companyReportingCurrency'];
                $itemledger_arr_erp[$a]['companyReportingExchangeRate'] = $master['companyReportingExchangeRate'];
                $itemledger_arr_erp[$a]['companyReportingCurrencyDecimalPlaces'] = $master['companyReportingCurrencyDecimalPlaces'];
                $itemledger_arr_erp[$a]['companyReportingAmount'] = round(($itemledger_arr_erp[$a]['transactionAmount'] / $itemledger_arr_erp[$a]['companyReportingExchangeRate']), $itemledger_arr_erp[$a]['companyReportingCurrencyDecimalPlaces']);
                $itemledger_arr_erp[$a]['companyReportingWacAmount'] = $item['companyReportingWacAmount'];

                $itemledger_arr_erp[$a]['partyCurrencyID'] = $master['farmerCurrencyID'];
                $itemledger_arr_erp[$a]['partyCurrency'] = $master['farmerCurrency'];
                $itemledger_arr_erp[$a]['partyCurrencyExchangeRate'] = $master['farmerCurrencyExchangeRate'];
                $itemledger_arr_erp[$a]['partyCurrencyDecimalPlaces'] = $master['farmerCurrencyDecimalPlaces'];
                $itemledger_arr_erp[$a]['partyCurrencyAmount'] = NULL;
                $itemledger_arr_erp[$a]['confirmedYN'] = $master['confirmedYN'];
                $itemledger_arr_erp[$a]['confirmedByEmpID'] = $master['confirmedByEmpID'];
                $itemledger_arr_erp[$a]['confirmedByName'] = $master['confirmedByName'];
                $itemledger_arr_erp[$a]['confirmedDate'] = $master['confirmedDate'];
                $itemledger_arr_erp[$a]['approvedYN'] = $master['approvedYN'];
                $itemledger_arr_erp[$a]['approvedDate'] = $master['approvedDate'];
                $itemledger_arr_erp[$a]['approvedbyEmpID'] = $master['approvedbyEmpID'];
                $itemledger_arr_erp[$a]['approvedbyEmpName'] = $master['approvedbyEmpName'];

                $itemledger_arr_erp[$a]['segmentID'] = $master['segmentID'];
                $itemledger_arr_erp[$a]['segmentCode'] = $master['segmentCode'];
                $itemledger_arr_erp[$a]['companyID'] = $master['companyID'];
                $itemledger_arr_erp[$a]['companyCode'] = $master['companyCode'];
                $itemledger_arr_erp[$a]['createdUserGroup'] = $master['createdUserGroup'];
                $itemledger_arr_erp[$a]['createdPCID'] = $master['createdPCID'];
                $itemledger_arr_erp[$a]['createdUserID'] = $master['createdUserID'];
                $itemledger_arr_erp[$a]['createdDateTime'] = $master['createdDateTime'];
                $itemledger_arr_erp[$a]['createdUserName'] = $master['createdUserName'];
                $itemledger_arr_erp[$a]['modifiedPCID'] = $master['modifiedPCID'];
                $itemledger_arr_erp[$a]['modifiedUserID'] = $master['modifiedUserID'];
                $itemledger_arr_erp[$a]['modifiedDateTime'] = $master['modifiedDateTime'];
                $itemledger_arr_erp[$a]['modifiedUserName'] = $master['modifiedUserName'];

            }

            if (!empty($item_arr_erp)) {
                $item_arr_erp = array_values($item_arr_erp);
                $this->db->update_batch('srp_erp_itemmaster', $item_arr_erp, 'itemAutoID');
            }

            if (!empty($itemledger_arr_erp)) {
                $itemledger_arr_erp = array_values($itemledger_arr_erp);
                $this->db->insert_batch('srp_erp_itemledger', $itemledger_arr_erp);
            }

            if (!empty($itemledger_arr_buyback)) {
                $itemledger_arr_buyback = array_values($itemledger_arr_buyback);
                $this->db->insert_batch('srp_erp_buyback_itemledger', $itemledger_arr_buyback);
            }


            if (!empty($warehouseItemData)) {
                $this->db->update_batch('srp_erp_warehouseitems', $warehouseItemData, 'warehouseItemsAutoID');
            }


            $double_entry = $this->fetch_double_entry_buyback_return($system_id, 'BBDR');

            for ($i = 0; $i < count($double_entry['gl_detail']); $i++) {
                $generalledger_arr[$i]['documentMasterAutoID'] = $double_entry['master_data']['returnAutoID'];
                $generalledger_arr[$i]['documentCode'] = $double_entry['code'];
                $generalledger_arr[$i]['documentSystemCode'] = $double_entry['master_data']['documentSystemCode'];
                $generalledger_arr[$i]['documentDate'] = $double_entry['master_data']['documentDate'];
                $generalledger_arr[$i]['documentType'] = NULL;
                $generalledger_arr[$i]['documentYear'] = $double_entry['master_data']['documentDate'];
                $generalledger_arr[$i]['documentMonth'] = date("m", strtotime($double_entry['master_data']['documentDate']));
                $generalledger_arr[$i]['documentNarration'] = $double_entry['master_data']['referenceNo'];
                $generalledger_arr[$i]['chequeNumber'] = NULL;
                $generalledger_arr[$i]['transactionCurrencyID'] = $double_entry['master_data']['transactionCurrencyID'];
                $generalledger_arr[$i]['transactionCurrency'] = $double_entry['master_data']['transactionCurrency'];
                $generalledger_arr[$i]['transactionExchangeRate'] = $double_entry['gl_detail'][$i]['transactionExchangeRate'];
                $generalledger_arr[$i]['transactionCurrencyDecimalPlaces'] = $double_entry['master_data']['transactionCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['companyLocalCurrencyID'] = $double_entry['master_data']['companyLocalCurrencyID'];
                $generalledger_arr[$i]['companyLocalCurrency'] = $double_entry['master_data']['companyLocalCurrency'];
                $generalledger_arr[$i]['companyLocalExchangeRate'] = $double_entry['gl_detail'][$i]['companyLocalExchangeRate'];
                $generalledger_arr[$i]['companyLocalCurrencyDecimalPlaces'] = $double_entry['master_data']['companyLocalCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['companyReportingCurrencyID'] = $double_entry['master_data']['companyReportingCurrencyID'];
                $generalledger_arr[$i]['companyReportingCurrency'] = $double_entry['master_data']['companyReportingCurrency'];
                $generalledger_arr[$i]['companyReportingExchangeRate'] = $double_entry['gl_detail'][$i]['companyReportingExchangeRate'];
                $generalledger_arr[$i]['companyReportingCurrencyDecimalPlaces'] = $double_entry['master_data']['companyReportingCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['partyContractID'] = '';
                $generalledger_arr[$i]['partyType'] = $double_entry['gl_detail'][$i]['partyType'];
                $generalledger_arr[$i]['partyAutoID'] = $double_entry['gl_detail'][$i]['partyAutoID'];
                $generalledger_arr[$i]['partySystemCode'] = $double_entry['gl_detail'][$i]['partySystemCode'];
                $generalledger_arr[$i]['partyName'] = $double_entry['gl_detail'][$i]['partyName'];
                $generalledger_arr[$i]['partyCurrencyID'] = $double_entry['gl_detail'][$i]['partyCurrencyID'];
                $generalledger_arr[$i]['partyCurrency'] = $double_entry['gl_detail'][$i]['partyCurrency'];
                $generalledger_arr[$i]['partyExchangeRate'] = $double_entry['gl_detail'][$i]['partyExchangeRate'];
                $generalledger_arr[$i]['partyCurrencyDecimalPlaces'] = $double_entry['gl_detail'][$i]['partyCurrencyDecimalPlaces'];
                $generalledger_arr[$i]['confirmedByEmpID'] = $double_entry['master_data']['confirmedByEmpID'];
                $generalledger_arr[$i]['confirmedByName'] = $double_entry['master_data']['confirmedByName'];
                $generalledger_arr[$i]['confirmedDate'] = $double_entry['master_data']['confirmedDate'];
                $generalledger_arr[$i]['approvedDate'] = $double_entry['master_data']['approvedDate'];
                $generalledger_arr[$i]['approvedbyEmpID'] = $double_entry['master_data']['approvedbyEmpID'];
                $generalledger_arr[$i]['approvedbyEmpName'] = $double_entry['master_data']['approvedbyEmpName'];
                $generalledger_arr[$i]['companyID'] = $double_entry['master_data']['companyID'];
                $generalledger_arr[$i]['companyCode'] = $double_entry['master_data']['companyCode'];
                $amount = $double_entry['gl_detail'][$i]['gl_dr'];
                if ($double_entry['gl_detail'][$i]['amount_type'] == 'cr') {
                    $amount = ($double_entry['gl_detail'][$i]['gl_cr'] * -1);
                }
                $generalledger_arr[$i]['transactionAmount'] = round($amount, $generalledger_arr[$i]['transactionCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['companyLocalAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['companyLocalExchangeRate']), $generalledger_arr[$i]['companyLocalCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['companyReportingAmount'] = round(($generalledger_arr[$i]['transactionAmount'] / $generalledger_arr[$i]['companyReportingExchangeRate']), $generalledger_arr[$i]['companyReportingCurrencyDecimalPlaces']);
                $generalledger_arr[$i]['partyCurrencyAmount'] = NULL;
                $generalledger_arr[$i]['amount_type'] = $double_entry['gl_detail'][$i]['amount_type'];
                $generalledger_arr[$i]['documentDetailAutoID'] = $double_entry['gl_detail'][$i]['auto_id'];
                $generalledger_arr[$i]['GLAutoID'] = $double_entry['gl_detail'][$i]['gl_auto_id'];
                $generalledger_arr[$i]['systemGLCode'] = $double_entry['gl_detail'][$i]['gl_code'];
                $generalledger_arr[$i]['GLCode'] = $double_entry['gl_detail'][$i]['secondary'];
                $generalledger_arr[$i]['GLDescription'] = $double_entry['gl_detail'][$i]['gl_desc'];
                $generalledger_arr[$i]['GLType'] = $double_entry['gl_detail'][$i]['gl_type'];
                $generalledger_arr[$i]['segmentID'] = $double_entry['gl_detail'][$i]['segment_id'];
                $generalledger_arr[$i]['segmentCode'] = $double_entry['gl_detail'][$i]['segment'];
                $generalledger_arr[$i]['projectID'] = isset($double_entry['gl_detail'][$i]['projectID']) ? $double_entry['gl_detail'][$i]['projectID'] : null;
                $generalledger_arr[$i]['projectExchangeRate'] = isset($double_entry['gl_detail'][$i]['projectExchangeRate']) ? $double_entry['gl_detail'][$i]['projectExchangeRate'] : null;
                $generalledger_arr[$i]['subLedgerType'] = $double_entry['gl_detail'][$i]['subLedgerType'];
                $generalledger_arr[$i]['subLedgerDesc'] = $double_entry['gl_detail'][$i]['subLedgerDesc'];
                $generalledger_arr[$i]['isAddon'] = $double_entry['gl_detail'][$i]['isAddon'];
                $generalledger_arr[$i]['createdUserGroup'] = $this->common_data['user_group'];
                $generalledger_arr[$i]['createdPCID'] = $this->common_data['current_pc'];
                $generalledger_arr[$i]['createdUserID'] = $this->common_data['current_userID'];
                $generalledger_arr[$i]['createdDateTime'] = $this->common_data['current_date'];
                $generalledger_arr[$i]['createdUserName'] = $this->common_data['current_user'];
                $generalledger_arr[$i]['modifiedPCID'] = $this->common_data['current_pc'];
                $generalledger_arr[$i]['modifiedUserID'] = $this->common_data['current_userID'];
                $generalledger_arr[$i]['modifiedDateTime'] = $this->common_data['current_date'];
                $generalledger_arr[$i]['modifiedUserName'] = $this->common_data['current_user'];
            }
            $this->db->insert_batch('srp_erp_generalledger', $generalledger_arr);

            $data['approvedYN'] = $status;
            $data['approvedbyEmpID'] = $this->common_data['current_userID'];
            $data['approvedbyEmpName'] = $this->common_data['current_user'];
            $data['approvedDate'] = $this->common_data['current_date'];
            /* $data['transactionAmount'] = $total;*/
            $this->db->where('returnAutoID', $system_id);
            $this->db->update('srp_erp_buyback_dispatchreturn', $data);

        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('error'=>'1','message'=> 'Return Approved Failed ' . $this->db->_error_message());
        } else {
            $this->db->trans_commit();
            return array('error'=>'0','message'=> 'Return Approved Successfully.');
        }
    }
    function calculateNewWAC_Return($oldStock, $WACAmount, $qty, $cost, $decimal = 2)
    {
        $newStock = $oldStock + $qty;
        $newWACAmount = round(((($oldStock * $WACAmount) + ($cost * $qty)) / $newStock), $decimal);
        return $newWACAmount;
    }
    function returnqtychicks()
    {
        $companyID = current_companyID();
        $batchID = trim($this->input->post('batchID'));

        $this->db->select(" SUM(qty )as qtynew");
        $this->db->from(' srp_erp_buyback_dispatchreturn dprm');
        $this->db->join('srp_erp_buyback_dispatchreturndetails dprd ', 'dprm.returnAutoID = dprd.returnAutoID ', 'INNER');
        $this->db->join('srp_erp_buyback_batch batch ', ' batch.batchMasterID = dprm.batchMasterID  ', 'LEFT');
        $this->db->where('dprm.confirmedYN', 1);
        $this->db->where('dprm.batchMasterID', $batchID);
        $this->db->where('dprm.approvedYN', 1);
        return $this->db->get()->row_array();
    }
    function fetch_goodReciptNote_batch_chicks_farmvisit()
    {
        $companyID = current_companyID();
        $batchID = trim($this->input->post('batchID'));

        $chicks = $this->db->query("SELECT
	(
		ifnull(sum(dpd.qty), 0) - ifnull(ggrn.noOfBirds, 0)
	) AS chicksTotal
FROM
	`srp_erp_buyback_dispatchnotedetails` `dpd`
INNER JOIN `srp_erp_buyback_dispatchnote` `dpm` ON `dpm`.`dispatchAutoID` = `dpd`.`dispatchAutoID`
LEFT JOIN (
	SELECT
		ifnull(SUM(grd.noOfBirds),0) as noOfBirds,
		grn.batchMasterID,
		grnDetailsID
	FROM
		srp_erp_buyback_grn grn
	INNER JOIN srp_erp_buyback_grndetails grd ON grd.grnAutoID = grn.grnAutoID
	WHERE
		`grn`.`batchMasterID` = '$batchID'
	GROUP BY
		batchMasterID
) ggrn ON `ggrn`.`batchMasterID` = `dpm`.`batchMasterID`
WHERE
	`dpm`.`companyID` = '$companyID'
AND `dpm`.`batchMasterID` = '$batchID'
AND `dpd`.`buybackItemType` = 1
AND `dpm`.`approvedYN` = 1")->row_array();


        $mortalityChicks = $this->db->query("SELECT COALESCE(sum(noOfBirds), 0) AS deadChicksTotal FROM srp_erp_buyback_mortalitymaster mm INNER JOIN srp_erp_buyback_mortalitydetails md ON mm.mortalityAutoID = md.mortalityAutoID WHERE batchMasterID = $batchID  AND confirmedYN = 1")->row_array();

        $this->db->select(" SUM(qty )as qtynew");
        $this->db->from(' srp_erp_buyback_dispatchreturn dprm');
        $this->db->join('srp_erp_buyback_dispatchreturndetails dprd ', 'dprm.returnAutoID = dprd.returnAutoID ', 'INNER');
        $this->db->join('srp_erp_buyback_batch batch ', ' batch.batchMasterID = dprm.batchMasterID  ', 'LEFT');
        $this->db->where('dprm.confirmedYN', 1);
        $this->db->where('dprm.batchMasterID', $batchID);
        $this->db->where('dprm.approvedYN', 1);
        $return = $this->db->get()->row_array();



        $balance = ($chicks['chicksTotal'] - ($mortalityChicks['deadChicksTotal']+$return['qtynew']));
        return $balance;

    }

    /* =============================== Production Report ======================================== */
    function get_Production_Report_Details()
    {
        $companyID = current_companyID();
        $yearID = $this->db->query("SELECT YEAR(beginingDate) AS beginingDate
                                       FROM srp_erp_companyfinanceyear 
                                       WHERE companyID = $companyID AND isCurrent = 1")->row_array();
        $year = $yearID['beginingDate'];

        $ids = $this->db->query("SELECT defaultUnitOfMeasure, itemSystemCode, itemName, itemAutoID
                                      FROM srp_erp_buyback_itemmaster 
                                      WHERE companyID = $companyID AND buybackItemType = 4")->result_array();

        $output = array();
        $idCount = 0;
        foreach ($ids as $var){
            $itemId = $var['itemAutoID'];
            $JanData = $this->db->query("SELECT sum( qty ) AS qty
                                       FROM srp_erp_buyback_grn
                                        INNER JOIN srp_erp_buyback_grndetails ON srp_erp_buyback_grndetails.grnAutoID = srp_erp_buyback_grn.grnAutoID
                                       WHERE itemAutoID = $itemId AND MONTH(documentDate) = 1 AND YEAR(documentDate) = $year")->row_array();

            $FebData = $this->db->query("SELECT sum( qty ) AS qty
                                       FROM srp_erp_buyback_grn
                                        INNER JOIN srp_erp_buyback_grndetails ON srp_erp_buyback_grndetails.grnAutoID = srp_erp_buyback_grn.grnAutoID
                                       WHERE itemAutoID = $itemId AND MONTH(documentDate) = 2 AND YEAR(documentDate) = $year")->row_array();

            $MarData = $this->db->query("SELECT sum( qty ) AS qty
                                       FROM srp_erp_buyback_grn
                                        INNER JOIN srp_erp_buyback_grndetails ON srp_erp_buyback_grndetails.grnAutoID = srp_erp_buyback_grn.grnAutoID
          
                                     WHERE itemAutoID = $itemId AND MONTH(documentDate) = 3 AND YEAR(documentDate) = $year")->row_array();

            $AprData = $this->db->query("SELECT sum( qty ) AS qty
                                       FROM srp_erp_buyback_grn
                                        INNER JOIN srp_erp_buyback_grndetails ON srp_erp_buyback_grndetails.grnAutoID = srp_erp_buyback_grn.grnAutoID
          
                                     WHERE itemAutoID = $itemId AND MONTH(documentDate) = 4 AND YEAR(documentDate) = $year")->row_array();

            $MayData = $this->db->query("SELECT sum( qty ) AS qty
                                       FROM srp_erp_buyback_grn
                                        INNER JOIN srp_erp_buyback_grndetails ON srp_erp_buyback_grndetails.grnAutoID = srp_erp_buyback_grn.grnAutoID
                                       WHERE itemAutoID = $itemId AND MONTH(documentDate) = 5 AND YEAR(documentDate) = $year")->row_array();

            $JuneData = $this->db->query("SELECT sum( qty ) AS qty
                                       FROM srp_erp_buyback_grn
                                        INNER JOIN srp_erp_buyback_grndetails ON srp_erp_buyback_grndetails.grnAutoID = srp_erp_buyback_grn.grnAutoID
                                       WHERE itemAutoID = $itemId AND MONTH(documentDate) = 6 AND YEAR(documentDate) = $year")->row_array();

            $JulyData = $this->db->query("SELECT sum( qty ) AS qty
                                       FROM srp_erp_buyback_grn
                                        INNER JOIN srp_erp_buyback_grndetails ON srp_erp_buyback_grndetails.grnAutoID = srp_erp_buyback_grn.grnAutoID
                                       WHERE itemAutoID = $itemId AND MONTH(documentDate) = 7 AND YEAR(documentDate) = $year")->row_array();

            $AugData = $this->db->query("SELECT sum( qty ) AS qty
                                       FROM srp_erp_buyback_grn
                                        INNER JOIN srp_erp_buyback_grndetails ON srp_erp_buyback_grndetails.grnAutoID = srp_erp_buyback_grn.grnAutoID
                                       WHERE itemAutoID = $itemId AND MONTH(documentDate) = 8 AND YEAR(documentDate) = $year")->row_array();

            $SepData = $this->db->query("SELECT sum( qty ) AS qty
                                       FROM srp_erp_buyback_grn
                                        INNER JOIN srp_erp_buyback_grndetails ON srp_erp_buyback_grndetails.grnAutoID = srp_erp_buyback_grn.grnAutoID
                                       WHERE itemAutoID = $itemId AND MONTH(documentDate) = 9 AND YEAR(documentDate) = $year")->row_array();

            $OctData = $this->db->query("SELECT sum( qty ) AS qty
                                       FROM srp_erp_buyback_grn
                                        INNER JOIN srp_erp_buyback_grndetails ON srp_erp_buyback_grndetails.grnAutoID = srp_erp_buyback_grn.grnAutoID
                                       WHERE itemAutoID = $itemId AND MONTH(documentDate) = 10 AND YEAR(documentDate) = $year")->row_array();

            $NovData = $this->db->query("SELECT sum( qty ) AS qty
                                       FROM srp_erp_buyback_grn
                                        INNER JOIN srp_erp_buyback_grndetails ON srp_erp_buyback_grndetails.grnAutoID = srp_erp_buyback_grn.grnAutoID
                                       WHERE itemAutoID = $itemId AND MONTH(documentDate) = 11 AND YEAR(documentDate) = $year")->row_array();

            $DecData = $this->db->query("SELECT sum( qty ) AS qty
                                       FROM srp_erp_buyback_grn
                                        INNER JOIN srp_erp_buyback_grndetails ON srp_erp_buyback_grndetails.grnAutoID = srp_erp_buyback_grn.grnAutoID
                                       WHERE itemAutoID = $itemId AND MONTH(documentDate) = 12 AND YEAR(documentDate) = $year")->row_array();


            $data['itemName'] = $var['itemName'];
            $data['itemSystemCode'] = $var['itemSystemCode'];
            $data['unitOfMeasure'] = $var['defaultUnitOfMeasure'];
            $data['jan'] = $JanData['qty'];
            $data['feb'] = $FebData['qty'];
            $data['mar'] = $MarData['qty'];
            $data['apr'] = $AprData['qty'];
            $data['may'] = $MayData['qty'];
            $data['june'] = $JuneData['qty'];
            $data['july'] = $JulyData['qty'];
            $data['aug'] = $AugData['qty'];
            $data['sep'] = $SepData['qty'];
            $data['oct'] = $OctData['qty'];
            $data['nov'] = $NovData['qty'];
            $data['dec'] = $DecData['qty'];
            $data['tot'] = $JanData['qty'] +  $FebData['qty'] + $MarData['qty'] + $AprData['qty'] + $MayData['qty'] + $JuneData['qty'] + $JulyData['qty'] + $AugData['qty'] + $SepData['qty']+ $OctData['qty']+ $NovData['qty'] + $DecData['qty'];

            $output[$idCount++] = $data;

        }

        return $output;
    }


    function farmManagement_editView_wip($farmID){

        $companyID = current_companyID();
        $qry = $this->db->query("SELECT returnqty.returnvalue as returnvalue, grnval.creditTotal as creditTotal,wipamt.workinprogressamount as workinprogressamount
                      FROM srp_erp_buyback_batch batch 
                       LEFT JOIN (SELECT COALESCE( sum( qty ), 0 ) AS chicksTotal,batchMasterID,confirmedYN,approvedYN FROM srp_erp_buyback_dispatchnotedetails dpd INNER JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID = dpd.dispatchAutoID AND buybackItemType = 1 WHERE dpm.confirmedYN = 1 AND dpm.approvedYN = 1 GROUP BY batchMasterID) chicksTotaltbl ON chicksTotaltbl.batchMasterID = batch.batchMasterID
                      LEFT JOIN (SELECT COALESCE( sum( totalTransferCost ), 0 ) AS batchvalue,batchMasterID FROM srp_erp_buyback_dispatchnotedetails dpd LEFT JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID = dpd.dispatchAutoID WHERE confirmedYN = 1 AND approvedYN = 1 GROUP BY batchMasterID )batchvalue on batchvalue.batchMasterID = batch.batchMasterID 
                      LEFT JOIN (SELECT sum( dpd.totalActualCost ) AS workinprogressamount,dpm.dispatchAutoID,batchMasterID FROM srp_erp_buyback_dispatchnotedetails dpd INNER JOIN srp_erp_buyback_dispatchnote dpm ON dpm.dispatchAutoID = dpd.dispatchAutoID GROUP BY batchMasterID) wipamt ON wipamt.batchMasterID = batch.batchMasterID 
                      LEFT JOIN (SELECT sum( grnd.totalCost ) AS creditTotal,batchMasterID FROM srp_erp_buyback_grndetails grnd INNER JOIN srp_erp_buyback_grn grnm ON grnm.grnAutoID = grnd.grnAutoID GROUP BY grnm.batchMasterID) grnval ON grnval.batchMasterID = batch.batchMasterID
                      LEFT JOIN(SELECT COALESCE ( sum( dpdr.totalTransferCost ), 0 ) AS returnvalue,dispatchAutoID,dpdr.returnAutoID,retun.batchMasterID FROM srp_erp_buyback_dispatchreturndetails dpdr INNER JOIN srp_erp_buyback_dispatchreturn retun on retun.returnAutoID = dpdr.returnAutoID WHERE approvedYN  = 1 AND confirmedYN = 1 GROUP BY batchMasterID)returnqty on returnqty.batchMasterID = batch.batchMasterID 
                      WHERE batch.companyID = $companyID AND chicksTotaltbl.confirmedYN = 1 AND chicksTotaltbl.approvedYN = 1 AND batch.isclosed = 0 AND batch.farmID = $farmID
                      GROUP BY batch.batchMasterID")->result_array();

        $wipAmount = 0;
        foreach ($qry as $value){
            $blance_value = $value['workinprogressamount'] - ($value['creditTotal']) - ($value['returnvalue']);
            $wipAmount += $blance_value;
        }

         $wip = number_format($wipAmount, 2);
         return $wip;
    }

    function get_outstanding_report(){
        $companyID = $this->common_data['company_data']['company_id'];
        $date_format_policy = date_format_policy();
        $convertFormat = convert_date_format_sql();
        $farmer = $this->input->post("farmerTo");
        $batchClosingDate = trim($this->input->post('from'));

        $format_batchClosingDate = null;
        if (isset($batchClosingDate) && !empty($batchClosingDate)) {
            $format_batchClosingDate = input_format_date($batchClosingDate, $date_format_policy);
        }
        $i = 1;
        $farmerOR = ' AND (';
        if (!empty($farmer)) { /*generate the query according to selected vendor*/
            foreach ($farmer as $farmer_val) {
                if ($i != 1) {
                    $farmerOR .= ' OR ';
                }
                $farmerOR .= "fm.farmID = '" . $farmer_val . "' ";
                $i++;
            }
        }
        $farmerOR .= ')';

        $result = $this->db->query("SELECT 	fm.farmSystemCode, fm.farmID,fm.description AS farmName,batch.batchClosingDate, batchCode, batch.description,CurrencyCode, batch.batchMasterID FROM  srp_erp_buyback_farmmaster fm
	LEFT JOIN srp_erp_currencymaster currency on currency.currencyID = fm.farmerCurrencyID
	LEFT JOIN srp_erp_buyback_batch batch on batch.farmID = fm.farmID
WHERE
	 batch.batchClosingDate <= '{$format_batchClosingDate}' AND isclosed = 1 AND fm.companyID = $companyID $farmerOR
 GROUP BY batch.batchMasterID ORDER BY fm.farmID ASC ")->result_array();

        return $result;
    }

    function save_grn_transportDetails(){
        $grnAutoID = trim($this->input->post('grnAutoID'));
        $VehicleName = trim($this->input->post('VehicleName'));
        $vehicleID = trim($this->input->post('VehicleID'));
        $driverName = trim($this->input->post('driverName'));
        $DriverID = trim($this->input->post('DriverID'));
        $helperName = trim($this->input->post('helperName'));
        $HelperID = trim($this->input->post('HelperID'));
       // $JourneyFrom = trim($this->input->post('JourneyFrom'));
       // $JourneyTo = trim($this->input->post('JourneyTo'));
        $TransportComment = trim($this->input->post('TransportComment'));
        $companyID = current_companyID();
        $dateTimeFormate = 'Y-m-d H:i:s';
        $JourneyFrom = format_date_mysql_datetime($this->input->post('JourneyFrom'), $dateTimeFormate);
        $JourneyTo = format_date_mysql_datetime($this->input->post('JourneyTo'), $dateTimeFormate);

        if(!empty($vehicleID)){
            $vehicle = $this->db->query("SELECT VehicleNo FROM fleet_vehiclemaster WHERE companyID = $companyID AND vehicleMasterID = $vehicleID")->row_array();
            $VehicleName = $vehicle['VehicleNo'];
        }
        if(!empty($DriverID)){
            $driver = $this->db->query("SELECT Ename2 FROM srp_employeesdetails WHERE Erp_companyID = $companyID AND EIdNo = $DriverID")->row_array();
            $driverName = $driver['Ename2'];
        }
        if(!empty($HelperID)){
            $helper = $this->db->query("SELECT Ename2 FROM srp_employeesdetails WHERE Erp_companyID = $companyID AND EIdNo = $HelperID")->row_array();
            $helperName = $helper['Ename2'];
        }
            $data = array(
                'vehicleID' => $vehicleID,
                'vehicleNo' => $VehicleName,
                'driverID' => $DriverID,
                'DriverName' => $driverName,
                'helperID' => $HelperID,
                'HelperName' => $helperName,
                'JourneyStartTime' => $JourneyFrom,
                'JourneyEndTime' => $JourneyTo,
                'transportComment' => $TransportComment,

            );

        $this->db->where('grnAutoID', $grnAutoID);
        $this->db->update('srp_erp_buyback_grn', $data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('e', 'Error in Updating ' . $this->db->_error_message());

        } else {
            $this->db->trans_commit();
            return array('s', 'Updated Successfully.');
        }
    }
}