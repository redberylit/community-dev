<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MFQ_Dashboard_modal extends ERP_Model
{

    function __contruct()
    {
        parent::__contruct();
    }

    function fetch_jobs()
    {
        $year = date('Y');
        $sql = "SELECT a.description,a.jan,a.feb,a.mar,a.apr,a.may,a.jun,a.jul,a.aug,a.sept,a.oct,a.nov,a.dece FROM ((SELECT 'Ongoing' as description,SUM(CASE WHEN MONTH (startDate) = 1 THEN 1 ELSE 0 END) as jan,SUM(CASE WHEN MONTH (startDate) = 2 THEN 1 ELSE 0 END) as feb,SUM(CASE WHEN MONTH (startDate) = 3 THEN 1 ELSE 0 END) as mar,SUM(CASE WHEN MONTH (startDate) = 4 THEN 1 ELSE 0 END) as apr,SUM(CASE WHEN MONTH (startDate) = 5 THEN 1 ELSE 0 END) as may,SUM(CASE WHEN MONTH (startDate) = 6 THEN 1 ELSE 0 END) as jun,SUM(CASE WHEN MONTH (startDate) = 7 THEN 1 ELSE 0 END) as jul,SUM(CASE WHEN MONTH (startDate) = 8 THEN 1 ELSE 0 END) as aug,SUM(CASE WHEN MONTH (startDate) = 9 THEN 1 ELSE 0 END) as sept,SUM(CASE WHEN MONTH (startDate) = 10 THEN 1 ELSE 0 END) as oct,SUM(CASE WHEN MONTH (startDate) = 11 THEN 1 ELSE 0 END) as nov,SUM(CASE WHEN MONTH (startDate) = 12 THEN 1 ELSE 0 END) as dece FROM srp_erp_mfq_job LEFT JOIN (SELECT jobID,COUNT(*) as totCount,SUM(if(status = 1,1,0)) as completedCount,(SUM(if(status = 1,1,0))/COUNT(*)) * 100 as percentage FROM srp_erp_mfq_workflowstatus WHERE companyID = " . current_companyID() . "  GROUP BY jobID) ws ON ws.jobID = srp_erp_mfq_job.workProcessID WHERE ws.percentage < 100 AND YEAR(startDate) = $year AND srp_erp_mfq_job.companyID = " . current_companyID() . " ) UNION ALL (SELECT 'Completed' as description,SUM(CASE WHEN MONTH (startDate) = 1 THEN 1 ELSE 0 END) as jan,SUM(CASE WHEN MONTH (startDate) = 2 THEN 1 ELSE 0 END) as feb,SUM(CASE WHEN MONTH (startDate) = 3 THEN 1 ELSE 0 END) as mar,SUM(CASE WHEN MONTH (startDate) = 4 THEN 1 ELSE 0 END) as apr,SUM(CASE WHEN MONTH (startDate) = 5 THEN 1 ELSE 0 END) as may,SUM(CASE WHEN MONTH (startDate) = 6 THEN 1 ELSE 0 END) as jun,SUM(CASE WHEN MONTH (startDate) = 7 THEN 1 ELSE 0 END) as jul,SUM(CASE WHEN MONTH (startDate) = 8 THEN 1 ELSE 0 END) as aug,SUM(CASE WHEN MONTH (startDate) = 9 THEN 1 ELSE 0 END) as sept,SUM(CASE WHEN MONTH (startDate) = 10 THEN 1 ELSE 0 END) as oct,SUM(CASE WHEN MONTH (startDate) = 11 THEN 1 ELSE 0 END) as nov,SUM(CASE WHEN MONTH (startDate) = 12 THEN 1 ELSE 0 END) as dece FROM srp_erp_mfq_job LEFT JOIN (SELECT jobID,COUNT(*) as totCount,SUM(if(status = 1,1,0)) as completedCount,(SUM(if(status = 1,1,0))/COUNT(*)) * 100 as percentage FROM srp_erp_mfq_workflowstatus WHERE companyID = " . current_companyID() . "  GROUP BY jobID) ws ON ws.jobID = srp_erp_mfq_job.workProcessID WHERE ws.percentage = 100 AND YEAR(startDate) = $year AND srp_erp_mfq_job.companyID = " . current_companyID() . ")) as a";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }


    function pull_from_erp()
    {
        $currentDB = $this->db->database;
        $gearsDB = $this->load->database('gearserp',true);
        $gearsDB->trans_start();
        /*link chartofaccount*/
        $gearsDB->query('INSERT INTO ' . $currentDB . '.srp_erp_chartofaccounts (GLAutoID, systemAccountCode, GLSecondaryCode,GLDescription,masterAutoID,masterAccount,masterAccountDescription,masterCategory,accountCategoryTypeID,CategoryTypeDescription,subCategory,controllAccountYN,isActive,companyID) SELECT AccountCode as GLAutoID,null as systemAccountCode,AccountCode as GLSecondaryCode,AccountDescription as GLDescription,masterAccount as masterAutoID,NULL as masterAccount,null as masterAccountDescription,catogaryBLorPL as masterCategory,null as accountCategoryTypeID,null as CategoryTypeDescription,controlAccounts as subCategory,controllAccountYN,isActive,' . current_companyID() . ' FROM gearserp.chartofaccountsassigned WHERE NOT EXISTS(SELECT * FROM ' . $currentDB . '.srp_erp_chartofaccounts WHERE ' . $currentDB . '.srp_erp_chartofaccounts.GLAutoID = gearserp.chartofaccountsassigned.AccountCode AND companyID = ' . current_companyID() . ') AND companyID="HEMT"');

        /*link customers*/
        $gearsDB->query('INSERT INTO ' . $currentDB . '.srp_erp_customermaster (customerAutoID, customerSystemCode, customerName,receivableAutoID,customerAddress1,customerAddress2,customerCountry,customerCreditLimit,isActive,companyID) SELECT customerCodeSystem as customerAutoID,CutomerCode as customerSystemCode,CustomerName as customerName,custGLaccount AS receivableAutoID,customerAddress1,customerAddress2,countryName as customerCountry,creditLimit as customerCreditLimit,isActive,' . current_companyID() . ' FROM gearserp.customerassigned LEFT JOIN gearserp.countrymaster ON countrycode = customerCountry WHERE NOT EXISTS(SELECT * FROM ' . $currentDB . '.srp_erp_customermaster WHERE ' . $currentDB . '.srp_erp_customermaster.customerAutoID = gearserp.customerassigned.customerCodeSystem  AND companyID = ' . current_companyID() . ') AND companyID="HEMT"');

        /*link mfq customers*/
        $gearsDB->query('INSERT INTO ' . $currentDB . '.srp_erp_mfq_customermaster (CustomerAutoID, customerSystemCode, customerName,customerAddress1,customerAddress2,customerCountry,isActive,companyID,isFromERP) SELECT customerAutoID, customerSystemCode, customerName,customerAddress1,customerAddress2,customerCountry,isActive,companyID,1 FROM ' . $currentDB . '.srp_erp_customermaster WHERE NOT EXISTS(SELECT * FROM ' . $currentDB . '.srp_erp_mfq_customermaster WHERE ' . $currentDB . '.srp_erp_customermaster.customerAutoID = ' . $currentDB . '.srp_erp_mfq_customermaster.CustomerAutoID  AND companyID = ' . current_companyID() . ') AND companyID = ' . current_companyID());

        /*link unit of measure*/
        $gearsDB->query('INSERT INTO ' . $currentDB . '.srp_erp_unit_of_measure (UnitID, UnitShortCode, UnitDes,companyID) SELECT UnitID, UnitShortCode, UnitDes,' . current_companyID() . ' FROM gearserp.units WHERE NOT EXISTS(SELECT * FROM ' . $currentDB . '.srp_erp_unit_of_measure WHERE ' . $currentDB . '.srp_erp_unit_of_measure.UnitID = gearserp.units.UnitID  AND companyID = ' . current_companyID() . ')');

        /*link warehouse*/
        $gearsDB->query('INSERT INTO ' . $currentDB . '.srp_erp_warehousemaster (wareHouseAutoID, wareHouseCode, wareHouseDescription,wareHouseLocation,companyID) SELECT wareHouseSystemCode, wareHouseCode, wareHouseDescription,locationName,' . current_companyID() . ' FROM gearserp.warehousemaster LEFT JOIN gearserp.erp_location ON gearserp.erp_location.locationID = gearserp.warehousemaster.wareHouseLocation WHERE NOT EXISTS(SELECT * FROM ' . $currentDB . '.srp_erp_warehousemaster WHERE ' . $currentDB . '.srp_erp_warehousemaster.wareHouseAutoID = gearserp.warehousemaster.wareHouseSystemCode  AND companyID = ' . current_companyID() . ')');

        /*link mfq warehouse*/
        $gearsDB->query('INSERT INTO ' . $currentDB . '.srp_erp_mfq_warehousemaster (wareHouseAutoID, wareHouseCode, wareHouseDescription,wareHouseLocation,companyID,isFromERP) SELECT wareHouseAutoID, wareHouseCode, wareHouseDescription,wareHouseLocation,companyID,1 FROM ' . $currentDB . '.srp_erp_warehousemaster WHERE NOT EXISTS(SELECT * FROM ' . $currentDB . '.srp_erp_mfq_warehousemaster WHERE ' . $currentDB . '.srp_erp_warehousemaster.wareHouseAutoID = ' . $currentDB . '.srp_erp_mfq_warehousemaster.wareHouseAutoID  AND companyID = ' . current_companyID() . ')');

        /*link serviceline*/
        $gearsDB->query('INSERT INTO ' . $currentDB . '.srp_erp_segment (segmentCode, description,companyID) SELECT ServiceLineCode, ServiceLineDes,' . current_companyID() . ' FROM gearserp.serviceline WHERE NOT EXISTS(SELECT * FROM ' . $currentDB . '.srp_erp_segment WHERE ' . $currentDB . '.srp_erp_segment.segmentCode = gearserp.serviceline.ServiceLineCode AND companyID = ' . current_companyID() . ') AND companyID="HEMT"');

        /*link mfq serviceline*/
        $gearsDB->query('INSERT INTO ' . $currentDB . '.srp_erp_mfq_segment (segmentID,segmentCode, description,companyID,isFromERP) SELECT segmentID,segmentCode, description,companyID,1 FROM ' . $currentDB . '.srp_erp_segment WHERE NOT EXISTS(SELECT * FROM ' . $currentDB . '.srp_erp_mfq_segment WHERE ' . $currentDB . '.srp_erp_mfq_segment.segmentID = ' . $currentDB . '.srp_erp_segment.segmentID AND companyID = ' . current_companyID() . ') AND companyID=' . current_companyID());

        /*link financecategory*/
        /* $gearsDB->query('INSERT INTO srp_erp_mfq_itemcategory (itemCategoryID, description,companyID,categoryType) SELECT itemCategoryID, categoryDescription,' . current_companyID() . ',1 FROM gearserp.financeitemcategorymaster WHERE NOT EXISTS(SELECT * FROM srp_erp_mfq_itemcategory WHERE srp_erp_mfq_itemcategory.itemCategoryID = gearserp.financeitemcategorymaster.itemCategoryID AND companyID = ' . current_companyID() . ') AND itemCategoryID = 1');*/

        /*link fixed asset*/
        /*$gearsDB->query('INSERT INTO srp_erp_fa_asset_master (faID, segmentID,segmentCode,docOriginSystemCode,docOrigin,docOriginDetailID,documentID,faAssetDept,serialNo,faCode,assetCodeS,faUnitSerialNo,assetDescription,comments,groupTO,dateAQ,dateDEP,depMonth,DEPpercentage,faCatID,faSubCatID,faSubCatID2,faSubCatID3,transactionAmount,companyLocalAmount,companyReportingAmount,auditCategory,partNumber,manufacture,unitAssign,unitAssignHistory,image,usedBy,usedByHistory,location,currentLocation,locationHistory,costGLAutoID,costGLCodeDes,ACCDEPGLAutoID,ACCDEPGLCODEdes,DEPGLAutoID,DEPGLCODEdes,companyID) SELECT faID,srp_erp_segment.segmentID,serviceLineCode,docOriginSystemCode,docOrigin,docOriginDetailID,documentID,faAssetDept,serialNo,faCode,assetCodeS,faUnitSerialNo,assetDescription,COMMENTS,groupTO,dateAQ,dateDEP,depMonth,DEPpercentage,faCatID,faSubCatID,faSubCatID2,faSubCatID3,COSTUNIT,COSTUNIT,costUnitRpt,AUDITCATOGARY,PARTNUMBER,MANUFACTURE,UNITASSIGN,UHITASSHISTORY,IMAGE,USEDBY,USEBYHISTRY,LOCATION,currentLocation,LOCATIONHISTORY,COSTGLCODE,COSTGLCODEdes,ACCDEPGLCODE,ACCDEPGLCODEdes,DEPGLCODE,DEPGLCODEdes,' . current_companyID() . ' FROM gearserp.erp_fa_asset_master LEFT JOIN srp_erp_segment ON srp_erp_segment.segmentCode = gearserp.erp_fa_asset_master.serviceLineCode WHERE NOT EXISTS(SELECT * FROM srp_erp_fa_asset_master WHERE srp_erp_fa_asset_master.faID = gearserp.erp_fa_asset_master.faID AND companyID = ' . current_companyID() . ') AND itemCategoryID = 1');*/


        /*link itemmaster*/
        $gearsDB->query('INSERT INTO ' . $currentDB . '.srp_erp_itemmaster (itemAutoID, itemSystemCode, seconeryItemCode,itemImage,itemName,itemDescription,mainCategoryID,mainCategory,subcategoryID,subSubCategoryID,itemUrl,barcode,financeCategory,partNo,defaultUnitOfMeasureID,defaultUnitOfMeasure,currentStock,reorderPoint,maximunQty,minimumQty,costGLAutoID,costSystemGLCode,costGLCode,costDescription,assteGLAutoID,assteSystemGLCode,assteGLCode,assteDescription,companyLocalWacAmount,companyReportingWacAmount,isActive,comments,companyID,companyCode) SELECT itmass.itemCodeSystem as itemAutoID,itmass.itemPrimaryCode as itemSystemCode,itmass.secondaryItemCode as seconeryItemCode,master.itemPicture as itemImage,master.itemShortDescription as itemName,itmass.itemDescription as itemDescription,itmass.financeCategoryMaster as mainCategoryID,financeitemcategorymaster.categoryDescription as mainCategory,NULL as subcategoryID,NULL as subSubCategoryID,NULL as itemUrl,itmass.barcode,(CASE itmass.financeCategoryMaster WHEN 1 then 1 WHEN 2 OR 4 then 2 ELSE 3 END) as financeCategory,itmass.secondaryItemCode as partNo,itmass.itemUnitOfMeasure as defaultUnitOfMeasureID,unit.UnitDes as defaultUnitOfMeasure,itmled.currentStock,itmass.rolQuantity as reorderPoint,itmass.maximunQty,itmass.minimumQty,financeGLcodePL as costGLAutoID,financeGLcodePL as costSystemGLCode,financeGLcodePL as costGLCode,financeitemcategorysub.categoryDescription as costDescription,financeGLcodePL as assteGLAutoID,financeGLcodePL as assteSystemGLCode,financeGLcodePL as assteGLCode,financeitemcategorysub.categoryDescription as assteDescription, itmass.wacValueLocal as companyLocalWacAmount,itmass.wacValueReporting as companyReportingWacAmount,itmass.isActive,master.itemShortDescription as comments,' . current_companyID() . ',"'.current_companyCode().'" FROM gearserp.itemassigned itmass LEFT JOIN gearserp.itemmaster master ON master.itemCodeSystem = itmass.itemCodeSystem LEFT JOIN gearserp.units unit ON itmass.itemUnitOfMeasure = unit.UnitID LEFT JOIN (SELECT SUM(inOutQty) as currentStock,itemSystemCode FROM gearserp.erp_itemledger WHERE companyID = "HEMT" GROUP BY itemSystemCode) itmled ON itmled.itemSystemCode = itmass.itemCodeSystem LEFT JOIN gearserp.financeitemcategorymaster ON financeitemcategorymaster.itemCategoryID = master.financeCategoryMaster LEFT JOIN gearserp.financeitemcategorysub ON financeitemcategorysub.itemCategorySubID = itmass.financeCategorySub  WHERE NOT EXISTS(SELECT * FROM ' . $currentDB . '.srp_erp_itemmaster WHERE ' . $currentDB . '.srp_erp_itemmaster.itemAutoID = itmass.itemCodeSystem AND companyID = ' . current_companyID() . ') AND itmass.companyID="HEMT" AND (itmass.financeCategoryMaster = 1 OR itmass.financeCategoryMaster = 2)');

        /*link mfq itemmaster*/
        $gearsDB->query('INSERT INTO ' . $currentDB . '.srp_erp_mfq_itemmaster (itemAutoID, itemSystemCode, secondaryItemCode,itemImage,itemName,itemDescription,mainCategoryID,mainCategory,subcategoryID,subSubCategoryID,itemUrl,barcode,financeCategory,partNo,defaultUnitOfMeasureID,defaultUnitOfMeasure,currentStock,reorderPoint,maximunQty,minimumQty,costGLAutoID,costSystemGLCode,costGLCode,costDescription,assetGLAutoID,assetSystemGLCode,assetGLCode,assetDescription,companyLocalWacAmount,companyReportingWacAmount,isActive,comments,companyID,companyCode,isFromERP) SELECT itemAutoID, itemSystemCode, seconeryItemCode,itemImage,itemName,itemDescription,mainCategoryID,mainCategory,subcategoryID,subSubCategoryID,itemUrl,barcode,financeCategory,partNo,defaultUnitOfMeasureID,defaultUnitOfMeasure,currentStock,reorderPoint,maximunQty,minimumQty,costGLAutoID,costSystemGLCode,costGLCode,costDescription,assteGLAutoID,assteSystemGLCode,assteGLCode,assteDescription,companyLocalWacAmount,companyReportingWacAmount,isActive,comments,companyID,companyCode,1 FROM ' . $currentDB . '.srp_erp_itemmaster WHERE NOT EXISTS(SELECT * FROM ' . $currentDB . '.srp_erp_mfq_itemmaster WHERE ' . $currentDB . '.srp_erp_mfq_itemmaster.itemAutoID = ' . $currentDB . '.srp_erp_itemmaster.itemAutoID AND companyID = ' . current_companyID() . ')');

        /*link uom conversion*/
        $gearsDB->query('INSERT INTO ' . $currentDB . '.srp_erp_unitsconversion (unitsConversionAutoID,masterUnitID, subUnitID,conversion,companyID) SELECT unitsConversionAutoID,masterUnitID, subUnitID,conversion,' . current_companyID() . ' FROM gearserp.erp_unitsconversion WHERE NOT EXISTS(SELECT * FROM ' . $currentDB . '.srp_erp_unitsconversion WHERE ' . $currentDB . '.srp_erp_unitsconversion.unitsConversionAutoID = gearserp.erp_unitsconversion.unitsConversionAutoID AND companyID = ' . current_companyID() . ')');

        $gearsDB->trans_complete();
        if ($gearsDB->trans_status() === FALSE) {
            $gearsDB->trans_rollback();
            return array('e', "Error Occurred");
        } else {
            $gearsDB->trans_commit();
            return array('s', "Successfully data pulled");
        }
    }

    function update_wac_from_erp()
    {
        $currentDB = $this->db->database;
        $gearsDB = $this->load->database('gearserp', TRUE);
        $gearsDB->trans_start();
        $gearsDB->query("UPDATE $currentDB.srp_erp_itemmaster 
    LEFT JOIN (SELECT itmled.currentStock,itmass.wacValueLocal,itmass.wacValueReporting,itmled.itemSystemCode FROM gearserp.itemassigned as itmass LEFT JOIN (SELECT SUM(inOutQty) as currentStock,itemSystemCode FROM gearserp.erp_itemledger WHERE companyID = 'HEMT'  GROUP BY itemSystemCode) itmled ON itmled.itemSystemCode = itmass.itemCodeSystem WHERE companyID='HEMT') master ON master.itemSystemCode = $currentDB.srp_erp_itemmaster.itemAutoID
SET $currentDB.srp_erp_itemmaster.companyLocalWacAmount = master.wacValueLocal,$currentDB.srp_erp_itemmaster.companyReportingWacAmount = master.wacValueReporting,$currentDB.srp_erp_itemmaster.currentStock = master.currentStock WHERE companyID = " . current_companyID());

        $gearsDB->query("UPDATE $currentDB.srp_erp_mfq_itemmaster 
    LEFT JOIN (SELECT itmled.currentStock,itmass.wacValueLocal,itmass.wacValueReporting,itmled.itemSystemCode FROM gearserp.itemassigned as itmass LEFT JOIN (SELECT SUM(inOutQty) as currentStock,itemSystemCode FROM gearserp.erp_itemledger WHERE companyID = 'HEMT'  GROUP BY itemSystemCode) itmled ON itmled.itemSystemCode = itmass.itemCodeSystem WHERE companyID='HEMT') master ON master.itemSystemCode = $currentDB.srp_erp_mfq_itemmaster.itemAutoID
SET $currentDB.srp_erp_mfq_itemmaster.companyLocalWacAmount = master.wacValueLocal,$currentDB.srp_erp_mfq_itemmaster.companyReportingWacAmount = master.wacValueReporting,$currentDB.srp_erp_mfq_itemmaster.currentStock = master.currentStock WHERE companyID = " . current_companyID());

        $gearsDB->trans_complete();
        if ($gearsDB->trans_status() === FALSE) {
            $gearsDB->trans_rollback();
            return array('e', "Error Occurred");
        } else {
            $gearsDB->trans_commit();
            return array('s', "Successfully data pulled");
        }
    }

    function load_erp_warehouse()
    {
        $result = $this->db->query("SELECT
	wareHouseAutoID,companyID,wareHouseCode,wareHouseDescription
FROM
	srp_erp_warehousemaster
WHERE companyID =" . current_companyID())->result_array();
        return $result;
    }

    function pull_from_erp_warehouse()
    {
        $wareHouseAutoID = $this->input->post("warehouseAutoID");
        $currentDB = $this->db->database;
        $gearsDB = $this->load->database('gearserp', TRUE);
        $gearsDB->trans_start();
        /*link itemmaster*/
        $gearsDB->query('INSERT INTO ' . $currentDB . '.srp_erp_warehouseitems (wareHouseAutoID,itemAutoID,unitOfMeasureID,currentStock,companyID) SELECT  wareHouseSystemCode,itemSystemCode,unitOfMeasure,SUM(inOutQty) as currentStock,'. current_companyID() . ' FROM gearserp.erp_itemledger ledg  WHERE NOT EXISTS(SELECT * FROM ' . $currentDB . '.srp_erp_warehouseitems WHERE ' . $currentDB . '.srp_erp_warehouseitems.itemAutoID = ledg.itemSystemCode AND companyID = ' . current_companyID() . ' AND wareHouseAutoID = '.$wareHouseAutoID.') AND ledg.companyID="HEMT" AND ledg.wareHouseSystemCode = '.$wareHouseAutoID.' GROUP BY itemSystemCode');

        $gearsDB->query("UPDATE $currentDB.srp_erp_warehouseitems
    LEFT JOIN (SELECT SUM(inOutQty) as currentStock,itemSystemCode FROM gearserp.erp_itemledger WHERE companyID = 'HEMT' AND wareHouseSystemCode='.$wareHouseAutoID.' GROUP BY itemSystemCode) master ON master.itemSystemCode = $currentDB.srp_erp_warehouseitems.itemAutoID
SET $currentDB.srp_erp_warehouseitems.currentStock = master.currentStock WHERE wareHouseAutoID = '.$wareHouseAutoID.' AND companyID = " . current_companyID());

        $gearsDB->trans_complete();
        if ($gearsDB->trans_status() === FALSE) {
            $gearsDB->trans_rollback();
            return array('e', "Error Occurred");
        } else {
            $gearsDB->trans_commit();
            return array('s', "Successfully data pulled");
        }
    }

}