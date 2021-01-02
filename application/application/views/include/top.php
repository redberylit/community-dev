<?php
$companyInfo = get_companyInfo();
$productID = $companyInfo['productID'];
$emplangid = $this->common_data['emplangid'];
$companyID = current_companyID();
$curentuser = current_userID();
$locationwisecodegenerate = getPolicyValues('LDG', 'All');
$thirdpartapp = getPolicyValues('TPA','ALL');

?>

    <style>

        .headStyle2 {
            color: #000000 !important;
        }
        .headStyle1 {
            color: #fff7f7 !important;
        }

        .headStyle2:hover {
            text-decoration: underline !important;
            color: #3c8dbc !important;;
        }

        .clsGold {
            color:green;
        }
        .clswhite {
            color:#ffffff;
        }

        .head1 {
            font-size: 14px;
            font-weight: 700;
        }


        /*thirdparty start*/


        .slick-slide {
            margin: 0px 20px;
        }

        .slick-slide img {
            width: 20%;
        }

        .slick-slider
        {
            position: relative;
            display: block;
            box-sizing: border-box;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-touch-callout: none;
            -khtml-user-select: none;
            -ms-touch-action: pan-y;
            touch-action: pan-y;
            -webkit-tap-highlight-color: transparent;
        }

        .slick-list
        {
            position: relative;
            display: block;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }
        .slick-list:focus
        {
            outline: none;
        }
        .slick-list.dragging
        {
            cursor: pointer;
            cursor: hand;
        }

        .slick-slider .slick-track,
        .slick-slider .slick-list
        {
            -webkit-transform: translate3d(0, 0, 0);
            -moz-transform: translate3d(0, 0, 0);
            -ms-transform: translate3d(0, 0, 0);
            -o-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
        }

        .slick-track
        {
            position: relative;
            top: 0;
            left: 0;
            display: block;
        }
        .slick-track:before,
        .slick-track:after
        {
            display: table;
            content: '';
        }
        .slick-track:after
        {
            clear: both;
        }
        .slick-loading .slick-track
        {
            visibility: hidden;
        }

        .slick-slide
        {
            display: none;
            float: left;
            height: 100%;
            min-height: 1px;
        }
        [dir='rtl'] .slick-slide
        {
            float: right;
        }
        .slick-slide img
        {
            display: block;
        }
        .slick-slide.slick-loading img
        {
            display: none;
        }
        .slick-slide.dragging img
        {
            pointer-events: none;
        }
        .slick-initialized .slick-slide
        {
            display: block;
        }
        .slick-loading .slick-slide
        {
            visibility: hidden;
        }
        .slick-vertical .slick-slide
        {
            display: block;
            height: auto;
            border: 1px solid transparent;
        }
        .slick-arrow.slick-hidden {
            display: none;
        }


        /*thirdparty end*/


    </style>

    <div class="wrapper">
    <header class="main-header">

        <!-- Logo -->
        <a href="<?php echo site_url('dashboard'); ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>Community</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">
                <?php
                if ($productID == 2) {
                    echo '<img style="max-height:20px;"  src="' . base_url() . 'images/' . LOGO_GEARS . '"/>';
                } else {
                    echo '<img style="max-height:40px"  src="' . base_url() . 'images/' . LOGO . '"/>';
                }
                ?>
            </span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <div id=""></div>
            <!-- Sidebar toggle button-->

            <?php
            if ($extra == 'sidebar-mini') {
                echo
                '<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" onclick="set_navbar_cookie()">
                <span class="sr-only">Toggle navigation</span>
            </a>';
            }
            ?>

            <?php

            $language;
            $query = "SELECT
	`primarylanguageemp`.`description` AS `language`,

	CASE WHEN primarylanguageemp.description = \"Arabic\" THEN \"ع\" WHEN primarylanguageemp.description = \"English\" THEN \"ENG\" WHEN primarylanguageemp.description = \"Tamil\" THEN
	\"தமிழ்\" WHEN primarylanguageemp.description = \"Sinhala\" THEN \"සිංහල\" END languageview 
FROM
	srp_employeesdetails
	INNER JOIN srp_erp_lang_languages AS primarylanguageemp ON primarylanguageemp.languageID = srp_employeesdetails.languageID 
WHERE
	EIdNo = $curentuser ";

            $result_employee = $this->db->query($query)->row_array();


            /*$this->db->select('primarylanguageemp`.`description` AS `language');
            $this->db->from('srp_employeesdetails');
            $this->db->join('srp_erp_lang_languages as primarylanguageemp', 'primarylanguageemp.languageID = srp_employeesdetails.languageID', 'INNER');
            $this->db->where('EIdNo', current_userID());
            $result_employee = $this->db->get()->row_array();*/
            if (!empty($result_employee)) {
                $language = $result_employee['languageview'];
            } else {

                /* $this->db->select('primary.description as language');
                 $this->db->from('srp_erp_lang_companylanguages');
                 $this->db->join('srp_erp_lang_languages as primary', 'primary.languageID = srp_erp_lang_companylanguages.primaryLanguageID', 'INNER');
                 $this->db->where('companyID', current_companyID());
                 $result = $this->db->get()->row_array();*/

                $q = "SELECT primarylang.description AS LANGUAGE,

CASE
    WHEN primarylang.description = \"Arabic\" THEN \"ع\"
	WHEN primarylang.description = \"English\" THEN \"ENG\"
	WHEN primarylang.description = \"Tamil\" THEN \"தமிழ்\" 
	WHEN primarylang.description = \"Sinhala\" THEN \"සිංහල\"
ELSE 
	\"\"
END
languageprimary

FROM
	srp_erp_lang_companylanguages
	INNER JOIN srp_erp_lang_languages primarylang ON primarylang.languageID = srp_erp_lang_companylanguages.primaryLanguageID 
WHERE
companyID = $companyID ";
                $result = $this->db->query($q)->row_array();
                if (!empty($result)) {
                    $language = $result['languageprimary'];
                } else {
                    $language = 'ENG';
                }
            }

            ?>

            <?php
            $location = null;
            $companyID = current_companyID();
            $currentuserid = current_userID();

            $locationassign = $this->db->query("SELECT emp.locationID,IFNULL(location.locationCode,'-') as locationCode FROM srp_employeesdetails emp Left join srp_erp_location location on location.locationID = emp.locationID where Erp_companyID  = $companyID And EIdNo = $currentuserid")->row_array();

            if(!empty($locationassign['locationCode']))
            {
                $location = $locationassign['locationCode'];
            }
            ?>

            <div class="col-md-3 pull-left" id="master-time-div" style="">
                <ul class="nav navbar-nav hidden-xs">
                    <li>
                        <a style="border: none" id="timeBox_style">

                            <div class="hidden-md hidden-sm hidden-xs">
                                <span class="" id="timeBox" style="font-size: 15px; font-weight: bolder"></span> &nbsp;&nbsp;&nbsp;
                                <span class="" id="dateBox"></span>
                            </div>

                            <div class="hidden-lg">
                                <span
                                        class="hidden-sm hidden-xs">Date : </span><strong><?php echo date('d/m/Y') ?></strong>
                            </div>


                        </a>
                    </li>
                </ul>
            </div>

            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu" id="posPreLoader" style="display: none;">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                           style="background-color: rgba(244, 244, 244, 0.3);">
                            <i class="fa fa-refresh fa-spin" style="color:#0b0803; font-size:18px;"></i> Loading
                        </a>
                    </li>
                    <?php if (!empty($posData['wareHouseLocation'])) { ?>
                        <li class="dropdown user user-menu hidden-sm hidden-xs" id="">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <label style="margin-bottom: 0px"><?php echo $posData['wareHouseLocation']; ?></label>
                            </a>
                        </li>
                    <?php }
                    if (!empty($posData['counterDet'])) { ?>
                        <li class="dropdown user user-menu" id="">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <label style="margin-bottom: 0px">&nbsp;<?php echo $posData['counterDet']; ?>
                                    &nbsp;</label>
                            </a>
                        </li>
                    <?php } ?>
                    <?php
                    $this->db->select("EPassportExpiryDate,EVisaExpiryDate");
                    $this->db->from('srp_employeesdetails');
                    $this->db->where('EIdNo', current_userID());
                    $visapassdetails = $this->db->get()->row_array();
                    /*$visapassdetails=get_passport_visa_details()*/
                    ?>
                    <?php
                    if (current_companyID() == 108) {
                        ?>
                        <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-warning">2</span>
                            </a>
                            <ul class="dropdown-menu" style="height: 120px;">
                                <li class="header">You have 2 notifications</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                        <?php
                                        if ($visapassdetails['EPassportExpiryDate']) {
                                            ?>
                                            <li>
                                                <a href="#">
                                                    <span class="glyphicon glyphicon-credit-card text-primary"></span>
                                                    You Passport expires
                                                    on <?php echo $visapassdetails['EPassportExpiryDate'] ?>
                                                </a>
                                            </li>
                                            <?php
                                        } else {
                                            ?>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-exclamation-circle text-red"></i> Your Passport
                                                    expiry date is not set
                                                </a>
                                            </li>
                                            <?php
                                        }
                                        ?>

                                        <?php
                                        if ($visapassdetails['EVisaExpiryDate']) {
                                            ?>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-file-text-o text-green"></i> You Visa expires
                                                    on <?php echo $visapassdetails['EVisaExpiryDate'] ?>
                                                </a>
                                            </li>
                                            <?php
                                        } else {
                                            ?>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-exclamation-circle text-red"></i> Your Visa expiry
                                                    date is not set
                                                </a>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <?php
                    }
                    ?>
                    <?php
                    if ($productID == 2) {
                       $style = 'clswhite';
                       $font = 'headStyle1';
                    } else {
                        $style = 'clsGold';
                        $font = 'headStyle2';
                    }

                    ?>


                    <?php
                    if($thirdpartapp==1){
                        $thirdpartyexs=$this->db->query('SELECT * FROM srp_erp_thirdpartyapplications WHERE companyID =' . $companyID . ' AND isActive=1 ')->result_array();

                        ?>
                        <li class="support support-menu">
                            <a class="dropdown-toggle" onclick="openthirdpartymodal()" target="_blank"
                               style="padding: 12px; " title="Third Party applications">
                            <span class="hidden-xs"><i style="font-size:  18px;"
                                                       class="fa fa-external-link"
                                                       aria-hidden="true"></i></span></a>
                        </li>
                        <?php
                    }
                    ?>



                    <li class="support support-menu" style="margin-top: 2%">
                    <span class="head1" style="padding-left: 3px;" onclick="openlanguagemodel()">
            <i class="fa fa-language <?php echo $style?> " aria-hidden="true"></i>
                <a href="#" class="<?php echo $font ?>">
                 <?php echo $language ?>
                </a>&nbsp;&nbsp;
                 </span>
                    </li>

                    <?php if($locationwisecodegenerate == 1) {?>
                        <li class="support support-menu" style="margin-top: -2px">
                            <a title="Location" onclick="employee_location();"> <i class="fa fa-map-marker"  style="color: #e20000;" aria-hidden="true">
                        <span class="head1 <?php echo $font ?>">
                           <?php echo $location ?>
                        </span>
                                </i>


                            </a>
                        </li>
                    <?php }?>




                    <?php
                    if (current_companyID() != 16) {
                        ?>

                        <li class="support support-menu">
                            <a title="Modules" href="<?php echo site_url('modules') ?>"> <i style="font-size:  18px;"
                                                                                            class="fa fa-puzzle-piece"
                                                                                            aria-hidden="true"></i> </a>
                        </li>


                        <li class="support support-menu">
                            <a class="dropdown-toggle" id="chatUrl" href="" target="_blank" style="padding: 12px;"
                               title="">
                                <div class="hidden-xs" id="chat"></div>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                    <?php
                    $this->db->select("supportURL");
                    $this->db->from('srp_erp_company');
                    $this->db->where('company_id', $companyID);
                    $supportURL = $this->db->get()->row_array();
                    ?>
                    <li class="support support-menu">
                        <a class="dropdown-toggle" href="<?php echo $supportURL['supportURL']  ?>" target="_blank"
                           style="padding: 12px;" title="Help Desk">
                            <span class="hidden-xs"><img src="<?php echo base_url('images/support.png') ?>" width="25px"
                                                         height="25px" class="user-image" alt="User Image"></span></a>
                    </li>
                    <li class="user user-menu">
                        <a class="dropdown-toggle" data-toggle="dropdown">
                            <?php
                            if ($this->session->userdata("companyType") == 1) {
                                ?>
                                <span
                                        class="hidden-xs"><?php echo '( ' . current_companyCode() . ' ) ' . ucwords(trim_value($this->common_data['company_data']['company_name'], 10)); ?></span>
                            <?php } else { ?>
                                <span
                                        class="hidden-xs"><?php echo ucwords($this->session->userdata("company_name")); ?></span>
                            <?php } ?>
                        </a>
                    </li>
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <?php
                            $filePath = imagePath() . $this->session->EmpImage;
                            $currentEmp_img = checkIsFileExists($filePath);
                            ?>
                            <img src="<?php echo $currentEmp_img; ?>" class="user-image" alt="User Image">
                            <span
                                    class="hidden-xs"><?php echo $name = ucwords($this->session->loginusername); ?> </br></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="<?php echo $currentEmp_img; ?>" class="img-circle" alt="User Image">
                                <p>
                                    <?php echo $name = ucwords($this->session->username); ?>
                                    <!-- <small><?php //$company = $this->cache->get('company_11'); var_dump($company); ?></small> -->
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <!--<div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat navdisabl"
                                       onclick="fetchPage('system/profile/profile_information','','Profile')">Change Password</a>
                                </div>-->
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat navdisabl"
                                       onclick="openChangePassowrdModel()">Change Password</a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo site_url('Login/logout'); ?>"
                                       class="btn btn-default btn-flat navdisabl">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <?php if (strtolower(SETTINGS_BAR) == 'on') { ?>
                        <li class="hidden-xs">
                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>

        </nav>
    </header>
    <div aria-hidden="true" role="dialog" id="language_select_modal" class="modal" data-keyboard="true"
         data-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form class="form-horizontal">
                    <div class="modal-header languageModalHeader">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="fa fa-close text-red"></i></button>
                        <h4> Language </h4>
                    </div>
                    <div class="modal-body">
                        <?php
                        $language = drill_down_emp_language();
                        if (!empty($language)) {
                            foreach ($language as $val) {
                                $val['languageID'];
                                ?>
                                <button
                                        class="btn btn-lg btn-default  btn-block"
                                        onclick="change_emp_language(<?php echo $val['languageID'] ?>)" type="button">
                                    <i class="fa fa-language text-red"></i> <?php echo $val['description'] ?><br>
                                    <small> <?php echo $val['languageshortcode'] ?></small>
                                </button>
                                <?php
                            }
                        } ?>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <div aria-hidden="true" role="dialog" id="employee_location_select_modal" class="modal" data-keyboard="true"
         data-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form class="form-horizontal">
                    <div class="modal-header languageModalHeader">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="fa fa-close text-red"></i></button>
                        <h4>  Location </h4>
                    </div>
                    <div class="modal-body">
                        <?php
                        $location = drilldown_emp_location_drop();
                        if (!empty($location)) {
                            foreach ($location as $val) {
                                $val['locationID']; ?>
                                <button
                                        class="btn btn-lg btn-default  btn-block"
                                        onclick="change_emp_location(<?php echo $val['locationID'] ?>)" type="button">
                                    <i class="fa fa-map-marker text-red"></i><small> <?php echo $val['locationCode'] ?> </small>- <small> <?php echo $val['locationName'] ?></small>
                                </button>
                                <?php
                            }
                        } ?>

                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
/**
 * Created by PhpStorm.
 * User: NSK
 * Date: 2016-09-18
 * Time: 2:49 PM
 */