<?php

/*advertisement  Approval*/

if (!function_exists('addApprovalsPagination')) {
    function addApprovalsPagination()
    {
        $CI =& get_instance();
        $CI->load->library("pagination");
        //$CI->load->library("s3");

        $data_pagination = $CI->input->post('data_pagination');
        $per_page = 10;
        $companyID = current_companyID();

        $count = $CI->db->query("SELECT COUNT(advertise.FamMasterID) AS comAdCount FROM srp_erp_ngo_com_advertisments advertise INNER JOIN srp_erp_ngo_com_familymaster femMas ON femMas.FamMasterID = advertise.FamMasterID WHERE companyID={$companyID}
                                 AND femMas.isDeleted=0 AND (advertise.is_deleted =0 OR advertise.is_deleted IS NULL)")->row('comAdCount');


        $isFiltered = 0;
        $searchKey_filter = '';
        $alpha_filter = '';
        $area_filter = '';
        $gender_filter = '';
        $apprvlStatus_filter = '';

        $searchKey = $CI->input->post('searchKey');
        $letter = $CI->input->post('letter');
        $categoryApr = $CI->input->post('categoryApr');
        $regionAr = $CI->input->post('regionAr');
        $apprvlStatus = $CI->input->post('apprvlStatus');


        if ($apprvlStatus != '' && $apprvlStatus != 'null') {
            if($apprvlStatus == 3){
                $apprvlStatus_filter = " AND is_approved = 0 ";
            }
            else{
                $apprvlStatus_filter = " AND is_approved = " . $apprvlStatus;
            }
            $isFiltered = 1;
        }

        if (!empty($categoryApr) && $categoryApr != 'null') {
            $categoryApr = array($CI->input->post('categoryApr'));
            $whereIN = "( " . join("' , '", $categoryApr) . " )";
            $gender_filter = " AND ad_category.id IN " . $whereIN;
            $isFiltered = 1;
        }

        if (!empty($regionAr) && $regionAr != 'null') {
            $regionAr = array($CI->input->post('regionAr'));
            $whereIN = "( " . join("' , '", $regionAr) . " )";
            $area_filter = " AND stateAr.stateID IN " . $whereIN;
            $isFiltered = 1;
        }

        if($letter != null){
            $alpha_filter = ' AND (CName_with_initials LIKE \''.$letter.'%\') ';
            $isFiltered = 1;
        }

        if($searchKey != ''){

            $searchKey_filter = " WHERE ((MemberCode Like '%" . $searchKey . "%') OR (CName_with_initials Like '%" . $searchKey . "%') OR (provinceAr Like '%" . $searchKey . "%') OR (regionAr Like '%" . $searchKey . "%') OR (category_name = '" . $searchKey . "') OR (CNIC_No Like '%" . $searchKey . "%') OR (PrimaryNumber Like '%" . $searchKey . "%') OR (familyNm Like '%" . $searchKey . "%'))";
            $isFiltered = 1;
        }

        $countFilter = 0;

        if($isFiltered == 1){
            $countFilterWhere = $gender_filter . $area_filter . $alpha_filter. $apprvlStatus_filter;
            $convertFormat = convert_date_format_sql();
            $countFilter = $CI->db->query("SELECT COUNT(advertise.FamMasterID) AS comAdCount FROM(
                                                   SELECT comMas.Com_MasterID,comMas.MemberCode,comMas.CName_with_initials,adSub_category.advertisement_category_id AS Gender,comMas.TP_Mobile AS PrimaryNumber,stateAr.Description AS regionAr ,stateProvince.Description AS provinceAr,
                                    comMas.C_Address, TitleDescription,comMas.EmailID, comMas.CNIC_No, IFNULL(femMas.LeaderID,0) AS pendingData,
                                    DATE_FORMAT(CDOB, '{$convertFormat}') AS CDOBs,ad_category.id,ad_category.category_name,stateProvince.Description, CImage, advertise.FamMasterID, CONCAT(FamilySystemCode,' - ', FamilyName )AS familyNm,
                                    adSub_category.sub_category_name,is_approved,advertise.type_id
                                    FROM srp_erp_ngo_com_advertisments advertise
                                     INNER JOIN srp_erp_ngo_com_familymaster femMas ON femMas.FamMasterID = advertise.FamMasterID
                                      LEFT JOIN advertisement_sub_category adSub_category ON adSub_category.id =advertise.advertisement_sub_category_id LEFT JOIN advertisement_category ad_category ON adSub_category.advertisement_category_id=ad_category.id
                                     INNER JOIN srp_erp_ngo_com_communitymaster comMas ON comMas.Com_MasterID=femMas.LeaderID
                                    JOIN srp_titlemaster ON srp_titlemaster.TitleID=comMas.TitleID
                                    LEFT JOIN srp_erp_statemaster AS stateAr ON stateAr.stateID=comMas.RegionID
                                    LEFT JOIN srp_erp_statemaster AS stateCoun ON stateCoun.stateID=comMas.countyID
                                    LEFT JOIN srp_erp_statemaster AS stateProvince ON stateProvince.stateID=comMas.provinceID
                   WHERE comMas.companyID={$companyID} AND comMas.isDeleted = 0 AND femMas.isDeleted=0 AND (advertise.is_deleted =0 OR advertise.is_deleted IS NULL) {$countFilterWhere}
                                           ) AS advertise {$searchKey_filter} ")->row('comAdCount');

        }

        // var_dump($countFilter);
        //  exit;

        $config = array();
        $config["base_url"] = "#adApprovals_list";
        $config["total_rows"] =  ($isFiltered == 1) ? $countFilter : $count;
        $config["per_page"] = $per_page;
        $config["data_page_attr"] = 'data-emp-pagination';
        $config["uri_segment"] = 3;

        $CI->pagination->initialize($config);

        $page = (!empty($data_pagination)) ? (($data_pagination -1) * $per_page): 0;
        $employeeData = load_approvalAdvertisement_data($page, $per_page);
        $dataCount = $employeeData['dataCount'];

        $data["comAdCount"] = $count;
        $data["adApprovals_list"] = $employeeData['adApprovals_list'];
        $data["pagination"] = $CI->pagination->create_links_addApproval_master();
        $data["per_page"] = $per_page;
        $thisPageStartNumber = ($page+1);
        $thisPageEndNumber = $page+$dataCount;

        if($isFiltered == 1){
            $data["filterDisplay"] = "Showing {$thisPageStartNumber} to {$thisPageEndNumber} of {$countFilter} entries (filtered from {$count} total entries)";
        }else{
            $data["filterDisplay"] = "Showing {$thisPageStartNumber} to {$thisPageEndNumber} of {$count} entries";
        }

        return $data;

    }
}

if (!function_exists('load_approvalAdvertisement_data')) {
    function load_approvalAdvertisement_data($page, $per_page)
    {

        //var_dump($page,$per_page);

        $searchKey_filter = '';
        $alpha_filter = '';
        $area_filter = '';
        $designation_filter = '';
        $apprvlStatus_filter = '';

        $CI =& get_instance();
        $letter = $CI->input->post('letter');
        $searchKey = $CI->input->post('searchKey');
        $categoryApr = $CI->input->post('categoryApr');
        $regionAr = $CI->input->post('regionAr');
        $apprvlStatus = $CI->input->post('apprvlStatus');

        if ($apprvlStatus != '' && $apprvlStatus != 'null') {
            if($apprvlStatus == 3){
                $apprvlStatus_filter = " AND is_approved = 0 ";
            }
            else{
                $apprvlStatus_filter = " AND is_approved = " . $apprvlStatus;
            }
        }

        if (!empty($categoryApr) && $categoryApr != 'null') {
            $categoryApr = array($CI->input->post('categoryApr'));
            $whereIN = "( " . join("' , '", $categoryApr) . " )";
            $designation_filter = " AND ad_category.id IN " . $whereIN;
        }

        if (!empty($regionAr) && $regionAr != 'null') {
            $regionAr = array($CI->input->post('regionAr'));
            $whereIN = "( " . join("' , '", $regionAr) . " )";
            $area_filter = " AND stateAr.stateID IN " . $whereIN;
        }

        if($letter != null){
            $alpha_filter = ' AND ( CName_with_initials LIKE \''.$letter.'%\') ';
        }

        if($searchKey != ''){
            $searchKey_filter = " WHERE ((MemberCode Like '%" . $searchKey . "%') OR (CName_with_initials Like '%" . $searchKey . "%') OR (provinceAr Like '%" . $searchKey . "%') OR (regionAr Like '%" . $searchKey . "%') OR (category_name = '" . $searchKey . "') OR (CNIC_No Like '%" . $searchKey . "%') OR (PrimaryNumber Like '%" . $searchKey . "%') OR (familyNm Like '%" . $searchKey . "%'))";

        }

        $companyID = current_companyID();
        $convertFormat = convert_date_format_sql();
        $where = "comMas.isDeleted = 0 AND femMas.isDeleted=0 AND (advertise.is_deleted =0 OR advertise.is_deleted IS NULL) AND comMas.companyID = " . $companyID . $designation_filter . $area_filter . $apprvlStatus_filter . $alpha_filter;

        $data = $CI->db->query("SELECT * FROM(
                                    SELECT  advertise.FamMasterID,comMas.Com_MasterID,comMas.MemberCode,comMas.CName_with_initials,adSub_category.advertisement_category_id AS Gender,comMas.CountryCodePrimary,comMas.TP_Mobile AS PrimaryNumber,stateAr.Description AS regionAr ,stateProvince.Description AS provinceAr,
                                    comMas.C_Address, TitleDescription,comMas.EmailID, comMas.CNIC_No,DATE_FORMAT(CDOB, '{$convertFormat}') AS CDOBs,ad_category.id,ad_category.category_name,DATE_FORMAT(FamilyAddedDate, '{$convertFormat}') AS FamilyAddedDates,stateProvince.Description, CImage, CONCAT( FamilySystemCode,' - ', FamilyName )AS familyNm,
                                    adSub_category.sub_category_name,is_approved,advertise.type_id
                                    FROM srp_erp_ngo_com_advertisments advertise
                                     INNER JOIN srp_erp_ngo_com_familymaster femMas ON femMas.FamMasterID = advertise.FamMasterID
                                    INNER JOIN srp_erp_ngo_com_communitymaster comMas ON comMas.Com_MasterID=femMas.LeaderID
                                     LEFT JOIN advertisement_sub_category adSub_category ON adSub_category.id =advertise.advertisement_sub_category_id LEFT JOIN advertisement_category ad_category ON adSub_category.advertisement_category_id=ad_category.id
                                    JOIN srp_titlemaster ON srp_titlemaster.TitleID=comMas.TitleID
                                    LEFT JOIN srp_erp_statemaster AS stateAr ON stateAr.stateID=comMas.RegionID
                                    LEFT JOIN srp_erp_statemaster AS stateCoun ON stateCoun.stateID=comMas.countyID
                                    LEFT JOIN srp_erp_statemaster AS stateProvince ON stateProvince.stateID=comMas.provinceID
                                    WHERE {$where}
                                ) comMas {$searchKey_filter} ORDER BY comMas.MemberCode LIMIT {$page}, {$per_page}")->result_array();
        //echo $CI->db->last_query();
        //  var_dump($data);
        $adApprovals_list = $data;
        $returnData = '';
        $color = "#FF0";
        if(!empty($adApprovals_list)){

            $CI->load->library('s3');
            $male_img = $CI->s3->getMyAuthenticatedURL('images/users/male.png', 3600);
            $female_img = $CI->s3->getMyAuthenticatedURL('images/users/female.png', 3600);

            foreach($adApprovals_list as $key=>$appMemData){
                $appMemID = $appMemData['Com_MasterID'];

                //$CImage = CImageCheck($appMemData['CImage'], $appMemData['Gender']);
                $CImage = trim($appMemData['CImage']);
                if($CImage == ''){
                    $CImage = ($appMemData['Gender'] == 1)? $male_img: $female_img;
                }
                elseif ($CImage == 'images/users/male.png'){
                    $CImage = $male_img;
                }
                elseif ($CImage == 'images/users/female.png'){
                    $CImage = $female_img;
                }
                else{
                    $CImage = $CI->s3->getMyAuthenticatedURL($CImage, 3600);
                    /*if( $CI->s3->getMyObjectInfo($CImage) ){
                        $CImage = $CI->s3->getMyAuthenticatedURL($CImage, 3600);
                    }
                    else{
                        $CImage = ($appMemData['Gender'] == 1)? $male_img: $female_img;
                    }*/
                }


                $firstDivStyle = ($key==0)? ' style="margin-top: 1px;"' : '';
                $firstDivInput = ($key==0)? '<input id="first-in-emp-list" />' : '';


                $mailID = $appMemData['EmailID'];
                $appMemName = $appMemData['CName_with_initials'];
                $empCode = $appMemData['MemberCode'];
                $DOJ = $appMemData['doj'];
                $familyNm = $appMemData['familyNm'];
                $provinceAr = $appMemData['provinceAr'];
                $regionAr = $appMemData['regionAr'];
                $categoryStr = $appMemData['category_name'];
                $CountryCode = $appMemData['CountryCodePrimary'];
                $mobileNo = $appMemData['PrimaryNumber'];
                $CNIC_No = $appMemData['CNIC_No'];
                $C_Address = $appMemData['C_Address'];
                $FamilyAddedDate = $appMemData['FamilyAddedDates'];
                $sub_category_name = $appMemData['sub_category_name'];
                $type_id = $appMemData['type_id'];
                $is_approved = $appMemData['is_approved'];
                $phone_no = preg_replace('/[^0-9]/', '', ($CountryCode.'|'.$mobileNo));

                if($is_approved == '1'){
                    $label = 'success';
                    $apprvlStatus ='Approved';
                }
                elseif($is_approved == '4'){
                    $label = 'danger';
                    $apprvlStatus ='Cancelled';
                }
                elseif($is_approved == '5'){
                    $label = 'warning';
                    $apprvlStatus ='Pending For Clarification';
                }
                else{
                    $label = 'info';
                    $apprvlStatus ='Remaining';

                }

                $returnData .= $firstDivInput;
                $returnData .= '<div class="candidate-description client-description applicants-content" '.$firstDivStyle.'>
                                    <div class="language-print client-des clearfix">
                                        <div class="aplicants-pic pull-left">
                                            <img src="'.$CImage.'" alt="">
                                            <ul class="list-inline">

                                            </ul>
                                        </div>

                                        <div class="clearfix">
                                            <div class="pull-left">
                                                <h5 class="memAppNameLink" onclick="openVerify_docs(' . $appMemID . ', \'' . $appMemName . '\', ' . $type_id . ',\''.$sub_category_name.'\','.$phone_no.');"> <a href="#" onclick="openVerify_docs(' . $appMemID . ', \'' . $appMemName . '\', ' . $type_id . ',\''.$sub_category_name.'\','.$phone_no.');">'.$empCode.' |</a>'.$appMemName.'</h5>
                                               
                                            </div>
                                            <span class="pull-right label label-'.$label.' emp-status-label">'.$apprvlStatus.'</span>
                                            <span class="pull-right label notfi-label" onclick="openPersonal_notifiyModal('.$appMemID.')"> <i class="fa fa-bell" aria-hidden="true"></i> </span>
                                        </div>

                                        <div class="aplicant-details-show clearfix">
                                         
                                            <ul class="list-unstyled pull-left">
                                             
                                                <li><span>        <div class="row-fluid" id="albums">
     <div id="defDiv" style="display: block;">
        <div style="width: 180px;" class="col-sm-2">
             <!-- small box -->
             <div class="small-box bg-teal bs">
                 <div style="background-color: grey;text-align: center;">Document Verification</div>
                 <a href="#" onclick="openVerify_docs(' . $appMemID . ', \'' . $appMemName . '\', ' . $type_id . ',\''.$sub_category_name.'\','.$phone_no.');">
                     <div style="background-color: grey;height: 90px;text-align: center;">
                         <i class="fa fa-file-image-o fa-5x" style="color: white;margin-top: 10px;"></i>
                   
                     </div>
                 </a>
                 <div style="padding: 3px 0;background: rgba(0,0,0,0.2);text-align: center;" data-toggle="tooltip" data-placement="bottom" title =""> <!--Assigned Classes : php foreach ($title2 as $tit){echo $tit.\', \';} ? -->
                     <a href="#" onclick="openVerify_docs(' . $appMemID . ', \'' . $appMemName . '\', ' . $type_id . ',\''.$sub_category_name.'\','.$phone_no.');" style="color: #fff;">
                         '.$sub_category_name.'</a>
                 </div>

             </div>
         </div>
     </div>
     </div></span></li>
                                              
                                            </ul>
                                        </div>
                                 
                                       
                                        
                                    </div>
                                </div>';
            }
        }
        else{
            $returnData .= '<div class="candidate-description client-description applicants-content">No records</div>';
        }
        return [
            'dataCount' => count($adApprovals_list),
            'adApprovals_list' => $returnData
        ];
    }
}

if (!function_exists('fetch_adApproval_status')) {
    function fetch_adApproval_status()
    {
        $companyID = current_companyID();
        $CI =& get_instance();
        $statusCount = $CI->db->query("SELECT * FROM (
                                          SELECT  (SELECT COUNT(advertise.FamMasterID) AS comAdCount FROM srp_erp_ngo_com_advertisments advertise INNER JOIN srp_erp_ngo_com_familymaster femMas ON femMas.FamMasterID = advertise.FamMasterID WHERE femMas.companyID={$companyID} AND femMas.isDeleted=0 AND (advertise.is_deleted =0 OR advertise.is_deleted IS NULL)
                                          AND advertise.is_approved=1) AS adApproved,(SELECT COUNT(advertise.FamMasterID) AS comAdCount FROM srp_erp_ngo_com_advertisments advertise INNER JOIN srp_erp_ngo_com_familymaster femMas ON femMas.FamMasterID = advertise.FamMasterID WHERE femMas.companyID={$companyID}
                                          AND femMas.isDeleted=0 AND (advertise.is_deleted =0 OR advertise.is_deleted IS NULL) AND advertise.is_approved = 4) AS noAdApproved,(SELECT COUNT(advertise.FamMasterID) AS comAdCount FROM srp_erp_ngo_com_advertisments advertise INNER JOIN srp_erp_ngo_com_familymaster femMas ON femMas.FamMasterID = advertise.FamMasterID WHERE femMas.companyID={$companyID}
                                          AND femMas.isDeleted=0 AND (advertise.is_deleted =0 OR advertise.is_deleted IS NULL) AND advertise.is_approved = 5) AS adAppClarify, (SELECT COUNT(advertise.FamMasterID) AS comAdCount FROM
                                           srp_erp_ngo_com_advertisments advertise INNER JOIN srp_erp_ngo_com_familymaster femMas ON femMas.FamMasterID = advertise.FamMasterID WHERE femMas.companyID={$companyID} AND femMas.isDeleted=0 AND (advertise.is_deleted =0 OR advertise.is_deleted IS NULL) AND (advertise.is_approved =0 OR advertise.is_approved IS NULL)) AS pendingAdApprovals
                                       ) AS t1")->row_array();
        return $statusCount;
    }
}

/*advertisement category dropdown*/
if (!function_exists('fetch_advertisement_cat_approval')) {
    function fetch_advertisement_cat_approval()
    {
        $CI =& get_instance();
        $companyID = current_companyID();
        $data = $CI->db->query("SELECT ad_category.id,ad_category.category_name as categoryName, count(advertise.FamMasterID) AS comAdCount FROM srp_erp_ngo_com_advertisments advertise LEFT JOIN advertisement_sub_category adSub_category ON adSub_category.id =advertise.advertisement_sub_category_id LEFT JOIN advertisement_category ad_category ON adSub_category.advertisement_category_id=ad_category.id INNER JOIN srp_erp_ngo_com_familymaster femMas ON femMas.FamMasterID = advertise.FamMasterID LEFT JOIN srp_erp_ngo_com_communitymaster comMas ON comMas.Com_MasterID=femMas.LeaderID WHERE femMas.companyID={$companyID} AND femMas.isDeleted = 0 AND advertise.is_deleted=0 GROUP BY adSub_category.advertisement_category_id")->result_array();
        return $data;
    }
}

?>