<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('common', $primaryLanguage);
$this->lang->load('communityngo_lang', $primaryLanguage);

$convertFormat = convert_date_format_sql();
$companyCode = $this->common_data['company_data']['company_code'];
?>
    <script src="<?php echo base_url('plugins/daterangepicker/daterangepicker.js'); ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/community_ngo/css/ngo_web_style.css'); ?>">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/daterangepicker/daterangepicker-bs3.css'); ?>">

    <style>

        div.bhoechie-tab-menu div.list-group{
            margin-bottom: 0;
            text-shadow: 0 2px 6px rgba(0,0,0,.5);
        }
        div.bhoechie-tab-menu div.list-group>a{
            margin-bottom: 0;
        }
        div.bhoechie-tab-menu div.list-group>a .glyphicon,
        div.bhoechie-tab-menu div.list-group>a .fa {
            color: #5A55A3;
        }
        div.bhoechie-tab-menu div.list-group>a:first-child{
            border-top-right-radius: 0;
            -moz-border-top-right-radius: 0;
        }
        div.bhoechie-tab-menu div.list-group>a:last-child{
            border-bottom-right-radius: 0;
            -moz-border-bottom-right-radius: 0;
        }


        div.bhoechie-tab-menu div.list-group{
            margin-bottom: 0;
        }
        div.bhoechie-tab-menu div.list-group>a{
            margin-bottom: 0;
        }
        div.bhoechie-tab-menu div.list-group>a .glyphicon,
        div.bhoechie-tab-menu div.list-group>a .fa {
            color: #5A55A3;
        }
        div.bhoechie-tab-menu div.list-group>a:first-child{
            border-top-right-radius: 0;
            -moz-border-top-right-radius: 0;
        }
        div.bhoechie-tab-menu div.list-group>a:last-child{
            border-bottom-right-radius: 0;
            -moz-border-bottom-right-radius: 0;
        }

        div.bhoechie-tab-menu{
            padding-right: 0;
            padding-left: 0;
            padding-bottom: 0;
        }

        div.bhoechie-tab-content{
            background-color: #ffffff;
            /* border: 1px solid #eeeeee; */
            padding-left: 20px;
            padding-top: 10px;
        }



        div.bhoechie-tab-menu{
            padding-right: 0;
            padding-left: 0;
            padding-bottom: 0;
        }


        div.bhoechie-tab-content{
            background-color: #ffffff;

        }

    </style>

<?php
if (!empty($srchPeopleDel)) {

    foreach ($srchPeopleDel as $key => $resSrchPepl) {

        $srchPeople_img = get_all_community_images($resSrchPepl['CImage'],'Community/'.$companyCode.'/MemberImages/','communityNoImg');
        ?>

        <ul class="media-list">
            <li class="media">
                <a class="pull-left" href="#">
                    <img style="width:100px;height:100px;" class="media-object img-circle" src="<?php echo $srchPeople_img; ?>" alt="profile">
                </a>
                <div class="media-body">
                    <div class="well well-sm">

                        <h5 class="media-heading text-uppercase reviews"> <?php echo $resSrchPepl['MemberCode']." |".$resSrchPepl['CName_with_initials']; ?> </h5>
                        <ul class="media-date text-uppercase reviews list-inline">
                            <li style="color: #d1d1d1;float: right;" title="Member Created Date"><i class="fa fa-clock-o"></i> <?php echo $resSrchPepl['createdDateTime']; ?></li>
                        </ul>
                        <p class="media-comment">

                        <div class="row" style="margin-top:20px;">
                            <div class="col-md-12 col-sm-12 col-xs-12">


                                <div class="col-sm-2 bhoechie-tab-menu">

                                    <div class="list-group">
                                        <a href="#" id="memDiv<?php echo $resSrchPepl['Com_MasterID']; ?>" class="list-group-item text-center" style="background-color: #5A55A3;color: #ffffff;" onchange="load_member_attachments(<?php echo $resSrchPepl['Com_MasterID']; ?>);" onclick="load_member_attachments(<?php echo $resSrchPepl['Com_MasterID']; ?>);view_memAboutTab(<?php echo $resSrchPepl['Com_MasterID']; ?>);">
                                            <h4 id="memIconDiv<?php echo $resSrchPepl['Com_MasterID']; ?>" style="font-size:18px;color: #ffffff;" class="fa fa-user-plus"></h4><br/>About
                                        </a>
                                        <a href="#" id="contDiv<?php echo $resSrchPepl['Com_MasterID']; ?>" class="list-group-item text-center" onclick="view_contactTab(<?php echo $resSrchPepl['Com_MasterID']; ?>);">
                                            <h4 id="contIconDiv<?php echo $resSrchPepl['Com_MasterID']; ?>" style="font-size:18px;" class="fa fa-building"></h4><br/>Contact
                                        </a>
                                        <a href="#" id="sickDiv<?php echo $resSrchPepl['Com_MasterID']; ?>" class="list-group-item text-center" onclick="view_sicknessTab(<?php echo $resSrchPepl['Com_MasterID']; ?>);">
                                            <h4 id="sickIconDiv<?php echo $resSrchPepl['Com_MasterID']; ?>" style="font-size:18px;" class="fa fa-bed"></h4><br/>Sickness
                                        </a>
                                        <a href="#" id="helpDiv<?php echo $resSrchPepl['Com_MasterID']; ?>" class="list-group-item text-center" onclick="view_helpTab(<?php echo $resSrchPepl['Com_MasterID']; ?>);">
                                            <h4 id="helpIconDiv<?php echo $resSrchPepl['Com_MasterID']; ?>" style="font-size:22px;" class="fa fa-question"></h4><br/>Help Need
                                        </a>
                                        <a href="#" id="profDiv<?php echo $resSrchPepl['Com_MasterID']; ?>" class="list-group-item text-center" onclick="view_professionTab(<?php echo $resSrchPepl['Com_MasterID']; ?>);">
                                            <h4 id="profIconDiv<?php echo $resSrchPepl['Com_MasterID']; ?>" style="font-size:18px;" class="glyphicon glyphicon-briefcase"></h4><br/>Profession
                                        </a>
                                        <a href="#" id="familyDiv<?php echo $resSrchPepl['Com_MasterID']; ?>" class="list-group-item text-center" onclick="view_familyDetailsTab(<?php echo $resSrchPepl['Com_MasterID']; ?>);">
                                            <img id="familyIconDiv<?php echo $resSrchPepl['Com_MasterID']; ?>" style="margin-left:50px;height: 50px;width: 75px;" class="media-object img-circle" src="<?php echo base_url("images/community/familyView.jpg"); ?>" alt="Family">
                                            Family
                                        </a>
                                        <a href="#" id="locationDiv<?php echo $resSrchPepl['Com_MasterID']; ?>" class="list-group-item text-center" onclick="view_memLocationTab(<?php echo $resSrchPepl['Com_MasterID']; ?>);">
                                            <h4 id="locationIconDiv<?php echo $resSrchPepl['Com_MasterID']; ?>" style="font-size:25px;" class="fa fa-map-marker"></h4><br/>Location
                                        </a>
                                    </div>
                                </div>

                                <div class="col-sm-10 bhoechie-tab">
                                    <div id="memAbout_tab<?php echo $resSrchPepl['Com_MasterID']; ?>" class="bhoechie-tab-content" style="display: block;">
                                        <center>

                                            <form role="form" class="form-inline" style="color: Green;background-color:#FAFAFF;border-radius:0px 22px 22px 22px;">
                                                <h4>Personal Details</h4>


                                                <table style="margin-left:20px;width: 100%;border: none;background-color: transparent;">

                                                    <tr class="profileDb">
                                                        <td><div><h5 style="font-weight:bold;"><?php echo $this->lang->line('common_name');?></h5></div> <?php echo $resSrchPepl['MemberCode'].' |'.$resSrchPepl['CName_with_initials'];; ?></td>
                                                        <td><div><h5 style="font-weight:bold;"><?php echo $this->lang->line('communityngo_dob');?></h5></div> <?php echo $resSrchPepl['CDOBs']; ?></td>
                                                    </tr>
                                                    <tr class="profileDb">
                                                        <td><div><h5 style="font-weight:bold;"><?php echo $this->lang->line('communityngo_nic');?></h5></div> <?php echo $resSrchPepl['CNIC_No']; ?></td>
                                                        <td><div><h5 style="font-weight:bold;"><?php echo $this->lang->line('communityngo_status');?></h5></div> <?php echo $resSrchPepl['maritalstatus']; ?></td>
                                                    </tr>
                                                    <tr class="profileDb">
                                                        <td><div><h5 style="font-weight:bold;"><?php echo $this->lang->line('communityngo_contactAddress');?></h5></div> <?php echo $resSrchPepl['C_Address']; ?></td>
                                                        <td><div><h5 style="font-weight:bold;"><?php echo $this->lang->line('comNgo_dash_contactDel');?></h5></div> <?php echo $resSrchPepl['TP_Mobile']; ?></td>
                                                    </tr>
                                                    <tr class="profileDb">
                                                        <td><div><h5 style="font-weight:bold;"><?php echo $this->lang->line('communityngo_gender');?></h5></div> <?php echo $resSrchPepl['name']; ?></td>
                                                        <td><div><h5 style="font-weight:bold;"><?php echo $this->lang->line('communityngo_bloodGroup');?></h5></div> <?php echo $resSrchPepl['BloodDescription']; ?></td>
                                                    </tr>
                                                    <tr class="profileDb">
                                                        <td><div><h5 style="font-weight:bold;"><?php echo $this->lang->line('communityngo_region');?></h5></div> <?php echo $resSrchPepl['Region']; ?></td>
                                                        <td><div><h5 style="font-weight:bold;"><?php echo $this->lang->line('communityngo_GS_Division');?></h5></div> <?php echo $resSrchPepl['GS_Division']; ?></td>
                                                    </tr>
                                                    <tr> <td colspan="2" style="color: transparent;">.................</td></tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <div class="form-group col-sm-10">
                                                                <div class="zx-tab-pane" id="profile-v">
                                                                    <div id="loadPageViewAttachment">
                                                                        <div class="table-responsive">
                                            <span aria-hidden="true"
                                                  class="glyphicon glyphicon-hand-right color"></span>
                                                                            &nbsp; <strong>
                                                                                <?php echo $this->lang->line('common_attachments'); ?><!--Attachments--></strong>
                                                                            <br><br>

                                                                            <table class="table table-striped table-condensed table-hover">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>#</th>
                                                                                    <th><?php echo $this->lang->line('common_file_name'); ?><!--File Name--></th>
                                                                                    <th><?php echo $this->lang->line('common_description'); ?><!--Description--></th>
                                                                                    <th><?php echo $this->lang->line('common_type'); ?><!--Type--></th>
                                                                                    <th><?php echo $this->lang->line('common_action'); ?><!--Action--></th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody id="status_attachment_mem_body" class="no-padding">
                                                                                <tr class="danger">
                                                                                    <td colspan="5" class="text-center">
                                                                                        <?php echo $this->lang->line('common_no_attachment_found'); ?><!--No Attachment Found--></td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                </table>
                                                <br>
                                                <br>
                                            </form>
                                        </center>
                                        <br>
                                    </div>
                                    <!-- train section -->
                                    <div style="display: none" id="memContact_tab<?php echo $resSrchPepl['Com_MasterID']; ?>" class="bhoechie-tab-content">
                                        <center>
                                            <form role="form" class="form-inline" style="color: Green;background-color:#FAFAFF;border-radius:0px 22px 22px 22px;">
                                                <h4>Contact Details</h4><br/>
                                                <table style="margin-left:20px;width: 100%;border: none;background-color: transparent;">

                                                    <tr class="profileDb">
                                                        <td><div><h5 style="font-weight:bold;"><?php echo $this->lang->line('communityngo_region');?></h5></div> <?php echo $resSrchPepl['Region']; ?></td>
                                                        <td><div><h5 style="font-weight:bold;"><?php echo $this->lang->line('communityngo_GS_Division');?></h5></div> <?php echo $resSrchPepl['GS_Division']; ?></td>
                                                    </tr>
                                                    <tr class="profileDb">
                                                        <td><div><h5 style="font-weight:bold;"><?php echo $this->lang->line('communityngo_contactAddress');?></h5></div> <?php echo $resSrchPepl['C_Address']; ?></td>
                                                        <td><div><h5 style="font-weight:bold;"><?php echo $this->lang->line('comNgo_dash_contactDel');?></h5></div> <?php echo $resSrchPepl['TP_Mobile']; ?></td>
                                                    </tr>
                                                </table>
                                            </form>
                                        </center>
                                        <br>
                                    </div>
                                    <!-- sick search -->
                                    <div id="memSickness_tab<?php echo $resSrchPepl['Com_MasterID']; ?>" style="display: none;" class="bhoechie-tab-content">
                                        <center>
                                            <form role="form" class="form-inline" style="color: Green;background-color:#FAFAFF;border-radius:0px 22px 22px 22px;">
                                                <h4>Permanent Sickness</h4><br/>
                                                <?php

                                                $memPSick = $this->db->query("SELECT * FROM srp_erp_ngo_com_memberpersickness AS t1
                                        JOIN srp_erp_ngo_com_permanent_sickness AS t2 ON t2.sickAutoID = t1.sickAutoID
                                        WHERE Com_MasterID = {$resSrchPepl['Com_MasterID']}");
                                                $memPSickness = $memPSick->result();

                                                ?>
                                                <?php
                                                if(!empty($memPSickness)){
                                                    ?>
                                                    <ul class="list-group list-group-unbordered">

                                                        <?php
                                                        foreach ($memPSickness as $rowSick) {

                                                            ?>
                                                            <li class="list-group-item">
                                                                <b><?php echo $rowSick->sickDescription; ?></b>
                                                                <br>
                                                                <span>Started From :<?php echo $rowSick->startedFrom; ?></span><br>
                                                                <span>Medical Condition :<?php echo $rowSick->medicalCondition; ?></span><br>
                                                                <span>Monthly Expenses :<?php echo $rowSick->monthlyExpenses; ?></span><br>
                                                                <span>Remarks :<?php echo $rowSick->Remarks; ?></span>
                                                            </li>
                                                            <?php

                                                        }
                                                        ?>
                                                    </ul>
                                                    <?php
                                                }
                                                else {
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="past-info">
                                                                <div class="post-area">
                                                                    <header class="infoarea">
                                                                        <strong class="attachemnt_title">
                                                                            <span style="text-align: center;font-size: 15px;font-weight: 800;color: grey;">No Data Found !</span>
                                                                        </strong>
                                                                    </header>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </form>
                                        </center>
                                    </div>
                                    <div id="memHelp_tab<?php echo $resSrchPepl['Com_MasterID']; ?>" style="display: none;" class="bhoechie-tab-content">
                                        <center>
                                            <form role="form" class="form-inline" style="color: Green;background-color:#FAFAFF;border-radius:0px 22px 22px 22px;">
                                                <h4>Help</h4><br/>
                                                <?php

                                                $HelpmReqCon = $this->db->query("SELECT * FROM srp_erp_ngo_com_memberhelprequirements AS t1
                                        JOIN srp_erp_ngo_com_helprequirements AS t2 ON t2.helpRequireID = t1.helpRequireID
                                        WHERE Com_MasterID = '" . $resSrchPepl['Com_MasterID'] . "'")->result_array();

                                                $HelpmReqConGv = $this->db->query("SELECT * FROM srp_erp_ngo_com_memberhelprequirements AS t1
                                        JOIN srp_erp_ngo_com_helprequirements AS t2 ON t2.helpRequireID = t1.helpRequireID
                                        WHERE Com_MasterID = '" . $resSrchPepl['Com_MasterID'] . "' AND helpRequireType='GOV'")->result_array();

                                                $HelpmReqConPv = $this->db->query("SELECT * FROM srp_erp_ngo_com_memberhelprequirements AS t1
                                        JOIN srp_erp_ngo_com_helprequirements AS t2 ON t2.helpRequireID = t1.helpRequireID
                                        WHERE Com_MasterID = '" . $resSrchPepl['Com_MasterID'] . "' AND helpRequireType='PVT' ")->result_array();

                                                $HelpmReqConCs = $this->db->query("SELECT * FROM srp_erp_ngo_com_memberhelprequirements AS t1
                                        JOIN srp_erp_ngo_com_helprequirements AS t2 ON t2.helpRequireID = t1.helpRequireID
                                        WHERE Com_MasterID = '" . $resSrchPepl['Com_MasterID'] . "' AND helpRequireType='CONS' ")->result_array();


                                                ?>
                                                <?php
                                                if(!empty($HelpmReqCon)){
                                                    ?>
                                                    <?php

                                                    IF($HelpmReqConGv){
                                                        echo'<div style="color: #00a5e6;">Requirement Type : Government Help</div>';

                                                        $g=1;
                                                        foreach ($HelpmReqConGv as $key => $det) {

                                                            if ($det['hlprDescription'] == null) {
                                                                $helpDesc = '';
                                                            } else {
                                                                $helpDesc = 'Help Description :' . $det['hlprDescription'];
                                                            }


                                                            echo '
                           <table class="table table-condensed">
                                    <tr>
                                      <td style="width: 10px"><h5>' . $g . '</h5></td>
                                      <td><div><h5>' . $det['helpRequireDesc'] . '</h5></div>
                                      <div><small>' . $helpDesc . '</small></div>
                                      </td>
                                </tr>
                                  </table>';

                                                            $g++;
                                                        }
                                                    }

                                                    IF($HelpmReqConPv){

                                                        echo'<div style="color: #00a5e6;">Requirement Type : Private Help</div>';

                                                        $p =1;
                                                        foreach ($HelpmReqConPv as $key => $det) {

                                                            if ($det['hlprDescription'] == null) {
                                                                $helpDesc = '';
                                                            } else {
                                                                $helpDesc = 'Help Description :' . $det['hlprDescription'];
                                                            }


                                                            echo '
                           <table class="table table-condensed">
                                    <tr>
                                      <td style="width: 10px"><h5>' . $p . '</h5></td>
                                      <td><div><h5>' . $det['helpRequireDesc'] . '</h5></div>
                                      <div><small>' . $helpDesc . '</small></div>
                                      </td>
                                    
                                </tr>
                                  </table>';

                                                            $p++;
                                                        }
                                                    }

                                                    IF($HelpmReqConCs){

                                                        echo'<div style="color: #00a5e6;">Requirement Type : Consultancy</div>';

                                                        $c=1;
                                                        foreach ($HelpmReqConCs as $key => $det) {


                                                            if ($det['hlprDescription'] == null) {
                                                                $helpDesc = '';
                                                            } else {
                                                                $helpDesc = 'Help Description :' . $det['hlprDescription'];
                                                            }


                                                            echo '
                           <table class="table table-condensed">
                                    <tr>
                                      <td style="width: 10px"><h5>' . $c . '</h5></td>
                                      <td><div><h5>' . $det['helpRequireDesc'] . '</h5></div>
                                      <div><small>' . $helpDesc . '</small></div>
                                      </td>
                                   
                                </tr>
                                  </table>';

                                                            $c++;
                                                        }
                                                    }
                                                    ?>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="past-info">
                                                                <div class="post-area">
                                                                        <header class="infoarea">
                                                                            <strong class="attachemnt_title">
                                <span style="text-align: center;font-size: 15px;font-weight: 800;color: grey;">No Data Found !</span>
                                                                            </strong>
                                                                        </header>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </form>

                                            <form role="form" class="form-inline" style="color: orange;background-color:#FAFAFF;border-radius:0px 22px 22px 22px;">

                                                <?php

                                                $willingToHlp = $this->db->query("SELECT * FROM srp_erp_ngo_com_memberwillingtohelp AS t1
                                        JOIN srp_erp_ngo_com_helpcategories AS t2 ON t2.helpCategoryID = t1.helpCategoryID
                                        WHERE Com_MasterID = '" . $resSrchPepl['Com_MasterID'] . "'")->result_array();
                                                ?>
                                                <?php
                                                if(!empty($willingToHlp)){
                                                    ?>
                                                    <h4>Willing to help</h4><br/>
                                                    <?php

                                                    $g=1;
                                                    foreach ($willingToHlp as $key => $det) {

                                                        if ($det['helpComments'] == null) {
                                                            $helpDesc = '';
                                                        } else {
                                                            $helpDesc = 'Help Comment :' . $det['helpComments'];
                                                        }


                                                        echo '
                           <table class="table table-condensed">
                                    <tr>
                                      <td style="width: 10px"><h5>' . $g . '</h5></td>
                                      <td><div><h5>' . $det['helpCategoryDes'] . '</h5></div>
                                      <div><small>' . $helpDesc . '</small></div>
                                      </td>
                                </tr>
                                  </table>';

                                                        $g++;
                                                    }

                                                    ?>
                                                    <?php
                                                } else {
                                                } ?>
                                            </form>
                                        </center>
                                        <br>
                                    </div>
                                    <div id="memProfession_tab<?php echo $resSrchPepl['Com_MasterID']; ?>" style="display: none;" class="bhoechie-tab-content">
                                        <center>
                                            <form role="form" class="form-inline" style="color: green;background-color:#FAFAFF;border-radius:0px 22px 22px 22px;">

                                                <?php

                                                $memOccupation = $this->db->query("SELECT * FROM srp_erp_ngo_com_memjobs AS t1
                                        LEFT JOIN srp_erp_ngo_com_jobcategories AS t2 ON t2.JobCategoryID = t1.JobCategoryID
                                        LEFT JOIN srp_erp_ngo_com_occupationtypes AS t3 ON t3.OccTypeID = t1.OccTypeID
                                        LEFT JOIN srp_erp_ngo_com_grades AS t4 ON t4.gradeComID = t1.gradeComID
                                        LEFT JOIN srp_erp_ngo_com_schools AS t5 ON t1.schoolComID = t5.schoolComID
                                        LEFT JOIN srp_erp_lang_languages AS t6 ON t6.languageID = t1.LanguageID
                                        LEFT JOIN srp_erp_ngo_com_schooltypes AS t7 ON t1.schoolTypeID = t7.schoolTypeID
                                        WHERE t1.Com_MasterID = '" . $resSrchPepl['Com_MasterID'] . "'")->result_array();
                                                ?>
                                                <?php
                                                if(!empty($memOccupation)){
                                                    ?>
                                                    <h4>Occupation</h4><br/>
                                                    <?php
                                                    $o=1;
                                                    foreach ($memOccupation as $key => $det) {

                                                        if ($det['WorkingPlace'] == null) {
                                                            $occuPlace = '';
                                                        } else {
                                                            $occuPlace = $det['WorkingPlace'];
                                                        }


                                                        echo '
                           <table class="table table-condensed">
                                    <tr>
                                      <td style="width: 10px"><h5>' . $o . '</h5></td>
                                      <td><div><h5>' . $det['JobCatDescription'] . '</h5></div>
                                      <div><small><b>Working Place : </b>' . $occuPlace . '</small></div>
                                      <div><small><b>Address : </b>' . $det['Address'] . '</small></div>
                                      <div><small><b>Date From : </b>' . $det['DateFrom'] . '&nbsp;&nbsp;<b>Date To : </b>' . $det['DateTo'] . '</small></div>
                                      </td>
                                </tr>
                                  </table>';

                                                        $o++;
                                                    }

                                                    ?>
                                                    <?php
                                                }
                                                else {
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="past-info">
                                                                <div class="post-area">
                                                                    <header class="infoarea">
                                                                        <strong class="attachemnt_title">
                                                                            <span style="text-align: center;font-size: 15px;font-weight: 800;color: grey;">No Data Found !</span>
                                                                        </strong>
                                                                    </header>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                            </form>
                                        </center>
                                        <br>
                                    </div>
                                    <div id="memFamilyDel_tab<?php echo $resSrchPepl['Com_MasterID']; ?>" style="display: none;" class="bhoechie-tab-content">
                                        <center>
                                            <?php
                                            $row_famMasId = $this->db->query("SELECT FamMasterID FROM srp_erp_ngo_com_familydetails WHERE Com_MasterID = '" . $resSrchPepl['Com_MasterID'] . "' AND isMove='0'");
                                            $get_famMasId = $row_famMasId->row();

                                            if(!empty($get_famMasId)){
                                                $FamMasterID= $get_famMasId->FamMasterID;

                                                ?>
                                                <h4 style="margin-top: 4px;">Family Details</h4>

                                            <form role="form" class="form-inline" style="color: green;background-color:#FAFAFF;border-radius:0px 22px 22px 22px;">
                                                <h4 style="color: transparent">Family Details</h4>
                                                <h6 style=""><?php echo $this->lang->line('CommunityNgo_family_members');?><!--Family Member Details--></h6>

                                                <?php

                                                $family_details = $this->db->query("select FamDel_ID ,srp_erp_ngo_com_familydetails.FamMasterID,srp_erp_ngo_com_familydetails.isLeader,srp_erp_ngo_com_familydetails.Com_MasterID,srp_erp_ngo_com_familydetails.isMove,CFullName,DATE_FORMAT(CDOB,'{$convertFormat}') AS CDOB ,DATE_FORMAT(FamMemAddedDate,'{$convertFormat}') AS FamMemAddedDate,CName_with_initials,srp_erp_ngo_com_communitymaster.isActive,srp_erp_ngo_com_communitymaster.DeactivatedFor,relationship,srp_erp_gender.name,CurrentStatus,srp_erp_ngo_com_maritalstatus.maritalstatusID,srp_erp_ngo_com_maritalstatus.maritalstatus from `srp_erp_ngo_com_familydetails` LEFT JOIN srp_erp_ngo_com_communitymaster on srp_erp_ngo_com_communitymaster.Com_MasterID=srp_erp_ngo_com_familydetails.Com_MasterID LEFT JOIN srp_erp_family_relationship ON srp_erp_family_relationship.relationshipID=srp_erp_ngo_com_familydetails.relationshipID LEFT JOIN srp_erp_gender ON srp_erp_gender.genderID=srp_erp_ngo_com_communitymaster.GenderID LEFT JOIN srp_erp_ngo_com_maritalstatus ON srp_erp_ngo_com_maritalstatus.maritalstatusID = srp_erp_ngo_com_communitymaster.CurrentStatus WHERE FamMasterID='".$FamMasterID."' ORDER BY srp_erp_ngo_com_familydetails.isLeader DESC,srp_erp_ngo_com_communitymaster.CDOB ASC ")->result_array();
                                                ?>


                                                        <div class="table-responsive">
                                                            <table id="add_new_grv_table" class="<?php echo table_class(); ?>">
                                                                <thead>
                                                                <tr>
                                                                    <th class='theadtr' style="width: 5%">#</th>
                                                                    <th class='theadtr' style="width: 21%"><?php echo $this->lang->line('communityngo_name_of_member');?><!--Family Member--></th>
                                                                    <th class='theadtr' style="width: 15%"><?php echo $this->lang->line('communityngo_gender');?><!--Gender--></th>
                                                                    <th class='theadtr' style="width: 13%"><?php echo $this->lang->line('communityngo_dob');?><!--DOB--></th>
                                                                    <th class='theadtr' style="width: 15%"><?php echo $this->lang->line('communityngo_relationship');?><!--Relationship--></th>
                                                                    <th class='theadtr' style="width: 13%"><?php echo $this->lang->line('CommunityNgo_famMem_AddedDate');?><!--Member Added Date--></th>
                                                                    <th class='theadtr' style="width: 18%"><?php echo $this->lang->line('communityngo_status');?><!--Current Status--></th>

                                                                </tr>
                                                                </thead>
                                                                <tbody id="grv_table_body">
                                                                <?php
                                                                if (!empty($family_details)) {
                                                                    $f=1;
                                                                    foreach ($family_details as $key => $femDel) {
                                                                
                                                                        if($femDel['isMove']==1 ){ $moveStatus= '<span style="width:8px;height:8px;font-size: 0.73em;float: right;background-color: #00a5e6; display:inline-block;color: #00a5e6;" title="Moved To Another Family">m</span>'; } else{ $moveStatus=''; }
                                                                        if($femDel['isActive'] ==1){ $activeState=''; } else{
                                                                            if($femDel['DeactivatedFor']==2){ $INactReson='Migrate';} else{$INactReson='Death';}
                                                                            $activeState='<span style="width:8px;height:8px;font-size: 0.73em;float: right;background-color:red; display:inline-block;color: red;" title="The Member Is Inactive :'.$INactReson.'">a</span>';}
                                                                        if($femDel['isLeader'] == '1'){
                                                                            $rowColor = 'style="background-color:lightsteelblue;"';
                                                                            $rowUserImg = '<i class="fa fa-user" style="float: right;" title="Head Of The Family"></i>';
                                                                        }
                                                                        else{
                                                                            $rowColor='';
                                                                            $rowUserImg='';
                                                                        }

                                                                        echo '<tr '.$rowColor.'>';
                                                                        echo '<td style="text-align: center;">'.($f) .' '.$rowUserImg.'</td>';
                                                                        echo '<td>'.$femDel['CName_with_initials'].'&nbsp;&nbsp;' .$moveStatus.'&nbsp;&nbsp;'.$activeState.'</td>';
                                                                        echo '<td>'.$femDel['name'].'</td>';
                                                                        echo '<td>'.$femDel['CDOB'].'</td>';
                                                                        echo '<td>'.$femDel['relationship'].'</td>';
                                                                        echo '<td>'.$femDel['FamMemAddedDate'].'</td>';
                                                                        echo '<td>'.$femDel['maritalstatus'].'</td>';
                                                                      $f++;
                                                                    }
                                                                }else{
                                                                    $norec=$this->lang->line('common_no_records_found');
                                                                    echo '<tr class="danger"><td colspan="7" class="text-center"><b>'.$norec.'<!--No Records Found--></b></td></tr>';
                                                                }

                                                                ?>
                                                                </tbody>
                                                                <tfoot>
                                                                <tr>

                                                                </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                        <br>

                                            </form>

                                            <form role="form" class="form-inline" style="color: green;background-color:#FAFAFF;border-radius:0px 22px 22px 22px;">
                                                <h4 style="color: transparent">Family Details</h4>
                                                <h6 style="text-transform: uppercase:"><?php echo $this->lang->line('CommunityNgo_family_chain');?><!--Family Chain--></h6>

                                                <!--OrgChart-master-->
                                                <link rel="stylesheet" href="<?php echo base_url('plugins/OrgChart-master/dist/css/jquery.orgchart.css'); ?>"/>
                                                <style>

                                                    .link {
                                                        color: #ffffff;
                                                    }

                                                    /* visited link */
                                                    .link:visited {
                                                        color: #ffffff;
                                                    }

                                                    /* mouse over link */
                                                    .link:hover {
                                                        color: #ffffff;
                                                    }

                                                    /* selected link */
                                                    .link:active {
                                                        color: #ffffff;
                                                    }

                                                    .orgchart .node .content {
                                                        min-height: 0px;
                                                        padding: 0px;
                                                    }

                                                    .orgchart td.left {
                                                        border-left: 2px solid rgba(70, 155, 218, 0.8);
                                                    }

                                                    .orgchart td.top {
                                                        border-top: 2px solid rgba(70, 155, 218, 0.8);
                                                    }

                                                    .orgchart td {
                                                        text-align: center;
                                                        vertical-align: top;
                                                        padding: 0;
                                                    }

                                                    .orgchart td > .down {
                                                        background-color: rgba(70, 155, 218, 0.8);
                                                        margin: 0px auto;
                                                        height: 20px;
                                                        width: 2px;
                                                    }

                                                    .orgchart td.top {
                                                        border-top: 2px solid rgba(70, 155, 218, 0.8);
                                                    }

                                                    .orgchart .node.focused {
                                                        background-color: rgba(238, 217, 54, 0.5);
                                                    }

                                                    .orgchart .node {
                                                        display: inline-block;
                                                        position: relative;
                                                        margin: 0;
                                                        padding: 2px;
                                                        border: 2px dashed transparent;
                                                        text-align: center;
                                                        width: 130px;
                                                    }

                                                    .node {
                                                        transition: all 0.3s;
                                                        opacity: 1;
                                                        top: 0;
                                                        left: 0;
                                                    }

                                                    .orgchart .node .second-menu {
                                                        display: none;
                                                        position: absolute;
                                                        top: 0;
                                                        right: -10px;
                                                        border-radius: 35px;
                                                        box-shadow: 0 0 10px 1px #999;
                                                        background-color: #fff;
                                                        z-index: 1;
                                                    }

                                                    .hidden {
                                                        display: none !important;
                                                    }

                                                    .orgchart {
                                                        display: inline-block;
                                                        min-height: 202px;
                                                        min-width: 202px;
                                                        -webkit-touch-callout: none;
                                                        -webkit-user-select: none;
                                                        -khtml-user-select: none;
                                                        -moz-user-select: none;
                                                        -ms-user-select: none;
                                                        user-select: none;
                                                        background-image: linear-gradient(90deg, rgba(200, 0, 0, 0.15) 10%, rgba(0, 0, 0, 0) 10%), linear-gradient(rgba(200, 0, 0, 0.15) 10%, rgba(0, 0, 0, 0) 10%);
                                                        background-size: 10px 10px;
                                                        border: 1px dashed rgba(0, 0, 0, 0);
                                                        transition: border .3s;
                                                        padding: 20px;
                                                    }

                                                    .orgchart > .spinner {
                                                        font-size: 100px;
                                                        margin-top: 20px;
                                                        color: rgba(68, 157, 68, 0.8);
                                                    }

                                                    .orgchart table {
                                                        border-spacing: 0;
                                                    }

                                                    .orgchart > table:first-child {
                                                        margin: 20px auto;
                                                    }

                                                    .orgchart td {
                                                        text-align: center;
                                                        vertical-align: top;
                                                        padding: 0;
                                                    }

                                                    .orgchart td.top {
                                                        border-top: 2px solid rgba(70, 155, 218, 0.8);
                                                    }

                                                    .orgchart td.right {
                                                        border-right: 2px solid rgba(70, 155, 218, 0.8);
                                                    }

                                                    .orgchart td.left {
                                                        border-left: 2px solid rgba(70, 155, 218, 0.8);
                                                    }

                                                    .orgchart td > .down {
                                                        background-color: rgba(70, 155, 218, 0.8);
                                                        margin: 0px auto;
                                                        height: 30px;
                                                        width: 2px;
                                                    }

                                                    /* node styling */
                                                    .orgchart .node {
                                                        display: inline-block;
                                                        position: relative;
                                                        margin: 0;
                                                        padding: 3px;
                                                        border: 2px dashed transparent;
                                                        text-align: center;
                                                        width: 150px;
                                                    }

                                                    .orgchart .node > .spinner {
                                                        position: absolute;
                                                        top: calc(50% - 15px);
                                                        left: calc(50% - 15px);
                                                        vertical-align: middle;
                                                        font-size: 30px;
                                                        color: rgba(68, 157, 68, 0.8);
                                                    }

                                                    .orgchart .node:hover {
                                                        background-color: rgba(238, 217, 54, 0.5);
                                                        transition: .5s;
                                                        cursor: default;
                                                        z-index: 20;
                                                    }

                                                    .orgchart .node.focused {
                                                        background-color: rgba(238, 217, 54, 0.5);
                                                    }

                                                    .orgchart .node.allowedDrop {
                                                        border-color: rgba(68, 157, 68, 0.9);
                                                    }

                                                    .orgchart .node .title {
                                                        /*position: relative;*/
                                                        text-align: center;
                                                        font-size: 12px;
                                                        font-weight: bold;
                                                        height: 20px;
                                                        line-height: 20px;
                                                        overflow: hidden;
                                                        text-overflow: ellipsis;
                                                        white-space: nowrap;
                                                        background-color: rgba(70, 155, 218, 0.8);
                                                        color: #fff;
                                                        border-radius: 4px 4px 0 0;
                                                    }

                                                    .orgchart .node .title .symbol {
                                                        float: left;
                                                        margin-top: 4px;
                                                        margin-left: 2px;
                                                    }

                                                    .orgchart .node .content {
                                                        position: relative;
                                                        /*width: 100%;*/
                                                        font-size: 12px;
                                                        line-height: 10px;
                                                        padding: 2px;
                                                        border: 2px solid rgba(70, 155, 218, 0.8);
                                                        border-radius: 0 0 4px 4px;
                                                        text-align: center;
                                                        background-color: #fff;
                                                        color: #333;
                                                        overflow: hidden;
                                                    }

                                                    .orgchart .node .edge {
                                                        font-size: 15px;
                                                        position: absolute;
                                                        color: rgba(68, 157, 68, 0.5);
                                                        cursor: default;
                                                        transition: .2s;
                                                        -webkit-transition: .2s;
                                                    }

                                                    .orgchart .edge:hover {
                                                        color: #449d44;
                                                        cursor: pointer;
                                                    }

                                                    .orgchart .node .verticalEdge {
                                                        width: calc(100% - 10px);
                                                        width: -webkit-calc(100% - 10px);
                                                        width: -moz-calc(100% - 10px);
                                                        left: 5px;
                                                    }

                                                    .orgchart .node .topEdge {
                                                        top: -4px;
                                                    }

                                                    .orgchart .node .bottomEdge {
                                                        bottom: -4px;
                                                    }

                                                    .orgchart .node .horizontalEdge {
                                                        width: 15px;
                                                        height: calc(100% - 10px);
                                                        height: -webkit-calc(100% - 10px);
                                                        height: -moz-calc(100% - 10px);
                                                        top: 5px;
                                                    }

                                                    .orgchart .node .rightEdge {
                                                        right: -4px;
                                                    }

                                                    .orgchart .node .leftEdge {
                                                        left: -4px;
                                                    }

                                                    .orgchart .node .horizontalEdge::before {
                                                        position: absolute;
                                                        top: calc(50% - 7px);
                                                        top: -webkit-calc(50% - 7px);
                                                        top: -moz-calc(50% - 7px);
                                                    }

                                                    .orgchart .node .rightEdge::before {
                                                        right: 3px;
                                                    }

                                                    .orgchart .node .leftEdge::before {
                                                        left: 3px;
                                                    }

                                                    .orgchart .node .edge.fa-chevron-up:hover {
                                                        transform: translate(0, -4px);
                                                        -webkit-transform: translate(0, -4px);
                                                    }

                                                    .orgchart .node .edge.fa-chevron-down:hover {
                                                        transform: translate(0, 4px);
                                                        -webkit-transform: translate(0, 4px);
                                                    }

                                                    .orgchart .node .edge.fa-chevron-right:hover {
                                                        transform: translate(4px, 0);
                                                        -webkit-transform: translate(4px, 0);
                                                    }

                                                    .orgchart .node .edge.fa-chevron-left:hover {
                                                        transform: translate(-4px, 0);
                                                        -webkit-transform: translate(-4px, 0);
                                                    }

                                                    .orgchart .node .edge.fa-chevron-right:hover ~ .fa-chevron-left {
                                                        transform: translate(-4px, 0);
                                                        -webkit-transform: translate(-4px, 0);
                                                    }

                                                    .orgchart .node .edge.fa-chevron-left:hover ~ .fa-chevron-right {
                                                        transform: translate(4px, 0);
                                                        -webkit-transform: translate(4px, 0);
                                                    }

                                                    .orgchart ~ .mask {
                                                        position: absolute;
                                                        top: 0px;
                                                        right: 0px;
                                                        bottom: 0px;
                                                        left: 0px;
                                                        z-index: 999;
                                                        text-align: center;
                                                        background-color: rgba(0, 0, 0, 0.3);
                                                    }

                                                    .orgchart ~ .mask .spinner {
                                                        position: absolute;
                                                        top: calc(50% - 54px);
                                                        left: calc(50% - 54px);
                                                        color: rgba(255, 255, 255, 0.8);
                                                        font-size: 108px;
                                                    }

                                                    .orgchart .second-menu-icon {
                                                        transition: opacity .5s;
                                                        opacity: 0;
                                                        right: -5px;
                                                        top: -5px;
                                                        z-index: 2;
                                                        color: rgba(68, 157, 68, 0.5);
                                                        font-size: 18px;
                                                        position: absolute;
                                                    }

                                                    .orgchart .second-menu-icon:hover {
                                                        color: #449d44;
                                                    }

                                                    .orgchart .node:hover .second-menu-icon {
                                                        opacity: 1;
                                                    }

                                                    .orgchart .node .second-menu {
                                                        display: none;
                                                        position: absolute;
                                                        top: 0;
                                                        right: -70px;
                                                        border-radius: 35px;
                                                        box-shadow: 0 0 10px 1px #999;
                                                        background-color: #fff;
                                                        z-index: 1;
                                                    }

                                                    .orgchart .node .second-menu .avatar {
                                                        width: 60px;
                                                        height: 60px;
                                                        border-radius: 30px;
                                                    }

                                                    .orgchart {
                                                        background: #fff;
                                                    }

                                                    a:hover, a:focus {
                                                        color: #23527c;
                                                        text-decoration: underline;
                                                    }

                                                    #chart-container+<?php echo $resSrchPepl['Com_MasterID']; ?> {
                                                        max-height: 260px;
                                                        overflow-y: scroll;
                                                    }
                                                </style>

                                                <?php
                                                    $company_id = current_companyID();
                                                    $page = $this->db->query("SELECT createPageLink FROM srp_erp_templatemaster
                              LEFT JOIN srp_erp_templates ON srp_erp_templatemaster.TempMasterID = srp_erp_templates.TempMasterID
                              WHERE srp_erp_templates.FormCatID=533 AND companyID={$company_id}
      
                              ORDER BY srp_erp_templatemaster.FormCatID")->row('createPageLink');
                                                    $familyNames = array();

                                                    $outrootemp = $this->db->query('SELECT CName_with_initials,FamilyName,FamMasterID FROM srp_erp_ngo_com_communitymaster left join srp_erp_ngo_com_familymaster  ON srp_erp_ngo_com_communitymaster.Com_MasterID = srp_erp_ngo_com_familymaster.LeaderID  WHERE srp_erp_ngo_com_familymaster.FamMasterID = "' . $FamMasterID . '"')->row_array();

                                                    $famCom_MasterID = array();
                                                    $queryFP4 = $this->db->query("SELECT  Com_MasterID FROM srp_erp_ngo_com_familydetails WHERE srp_erp_ngo_com_familydetails.FamMasterID = '" . $FamMasterID . "'");
                                                    $rowFP4 = $queryFP4->result();
                                                    foreach ($rowFP4 as $resFP4) {
                                                        $famCom_MasterID[] = $resFP4->Com_MasterID;

                                                    }

                                                    $delFamilyMem = "'".implode("', '", $famCom_MasterID)."'";



                                                    //$delFamilyMem1 = $this->db->query("SELECT srp_erp_ngo_com_familydetails.FamMasterID FROM srp_erp_ngo_com_familydetails left join srp_erp_ngo_com_communitymaster  ON srp_erp_ngo_com_familydetails.Com_MasterID = srp_erp_ngo_com_communitymaster.Com_MasterID  WHERE srp_erp_ngo_com_familydetails.FamMasterID != '" . $FamMasterID . "' and Com_MasterID IN($delFamilyMem)")->result_array();

                                                    $output = $this->db->query("SELECT
		CONCAT(srp_erp_ngo_com_familymaster.FamilyName,'~',srp_erp_ngo_com_communitymaster.CName_with_initials,'~',srp_erp_ngo_com_familydetails.FamMasterID,'~',if(srp_erp_ngo_com_familydetails.FamMasterID != '',srp_erp_ngo_com_familydetails.FamMasterID,'-')) AS FamMasterID5
	FROM srp_erp_ngo_com_familydetails 
	
	left join srp_erp_ngo_com_communitymaster ON srp_erp_ngo_com_communitymaster.Com_MasterID = srp_erp_ngo_com_familydetails.Com_MasterID

	left join srp_erp_ngo_com_familymaster  ON srp_erp_ngo_com_familymaster.FamMasterID = srp_erp_ngo_com_familydetails.FamMasterID

	WHERE srp_erp_ngo_com_familydetails.FamMasterID != '" . $FamMasterID . "' and srp_erp_ngo_com_familydetails.Com_MasterID IN($delFamilyMem) and srp_erp_ngo_com_familydetails.isDeleted=0 ")->result_array();



                                                    if (!empty($output)) {
                                                        foreach ($output as $row) {

                                                            $familyNames[str_replace("'", "", $row['FamMasterID5'])][] = $row['FamMasterID5'];

                                                        }
                                                    }

                                                    $data = "";
                                                    foreach ($familyNames as $k1 => $v1) {

                                                        if (!empty($k1)) {
                                                            $val1 = explode("~", $k1);
                                                            $data .= "{ 'id': \"" . $val1[3] . "\",'name': '<a class=\'link\' href=\'#\' rel=\'tooltip\' title=\'" . htmlentities($val1[0], ENT_QUOTES) . "\' onClick=\"loadFamilyLinkInDel(\'" . $val1[2] . "\',\'" . htmlentities($val1[0], ENT_QUOTES) . "\',\'" . $FamMasterID . "\')\">" . htmlentities($val1[0], ENT_QUOTES) . "</a>', 'title': '" . htmlentities($val1[1]) . "',";
                                                            /*echo "<pre>";
                                                            print_r($v1);
                                                            echo "</pre>";*/
                                                            $test1 = array_values($v1);
                                                            unset($v1['']);
                                                            if (!empty($v1)) {
                                                                $data .= "'children': [";
                                                                foreach ($v1 as $k2 => $v2) {
                                                                    if (!empty($k2)) {
                                                                        $val2 = explode("~", $k2);
                                                                        $data .= "{ 'id': \"" . $val2[3] . "\",'name': '<a class=\'link\' href=\'#\' title=\'" . htmlentities($val2[0], ENT_QUOTES) . "\' rel=\'tooltip\' onClick=\"loadFamilyLinkInDel(\'" . $val2[2] . "\',\'" . htmlentities($val2[0], ENT_QUOTES) . "\',\'" . $FamMasterID . "\')\">" . htmlentities($val2[0], ENT_QUOTES) . "</a>', 'title': '" . htmlentities($val2[1]) . "',";
                                                                        $test2 = array_values($v2);
                                                                        unset($v2['']);

                                                                        if ($v2) {
                                                                            $data .= "'children': [";
                                                                            foreach ($v2 as $k3 => $v3) {
                                                                                if (!empty($k3)) {
                                                                                    $val3 = explode("~", $k3);
                                                                                    $data .= "{ 'id': \"" . $val3[3] . "\",'name': '<a class=\'link\' href=\'#\' title=\'" . htmlentities($val3[0], ENT_QUOTES) . "\' rel=\'tooltip\' onClick=\"loadFamilyLinkInDel(\'" . $val3[2] . "\',\'" . htmlentities($val3[0], ENT_QUOTES) . "\',\'" . $FamMasterID . "\')\">" . htmlentities($val3[0], ENT_QUOTES) . "</a>', 'title': '" . htmlentities($val3[1]) . "',";
                                                                                    $test3 = array_values($v2);
                                                                                    unset($v3['']);
                                                                                    if ($v3) {
                                                                                        $data .= "'children': [";
                                                                                        foreach ($v3 as $k4 => $v4) {
                                                                                            if (!empty($k4)) {
                                                                                                $val4 = explode("~", $k4);
                                                                                                $data .= "{ 'id': \"" . $val4[3] . "\",'name': '<a class=\'link\' href=\'#\' title=\'" . htmlentities($val4[0], ENT_QUOTES) . "\' rel=\'tooltip\' onClick=\"loadFamilyLinkInDel(\'" . $val4[2] . "\',\'" . htmlentities($val4[0], ENT_QUOTES) . "\',\'" . $FamMasterID . "\')\">" . htmlentities($val4[0], ENT_QUOTES) . "</a>', 'title': '" . htmlentities($val4[1]) . "'},";
                                                                                            }
                                                                                        }
                                                                                        $data .= "]";
                                                                                    }
                                                                                    $data .= "},";
                                                                                }
                                                                            }
                                                                            $data .= "]";
                                                                        }
                                                                        $data .= "},";
                                                                    }
                                                                }
                                                                $data .= "]";
                                                            }
                                                            $data .= "},";
                                                        }
                                                    }

                                                ?>
                                                <div id="chart-container<?php echo $resSrchPepl['Com_MasterID']; ?>" style="overflow: auto"></div>

                                                <!--OrgChart-master-->
                                                <script src="<?php echo base_url('plugins/OrgChart-master/dist/js/jquery.orgchart.js'); ?>"></script>
                                                <script>
                                                    $(document).ready(function () {
                                                        var datascource = {
                                                            'id': '<?php echo $outrootemp['FamMasterID'] ?>',
                                                            'name': '<?php echo str_replace("'", "", $outrootemp['FamilyName']) ?>',
                                                            'title': 'Head Of The Family : <?php echo $outrootemp['CName_with_initials'] ?>',
                                                            <?php
                                                            if($data)
                                                            {
                                                            ?>
                                                            'children': [
                                                                <?php echo $data ?>
                                                            ]
                                                            <?php
                                                            }
                                                            ?>
                                                        };

                                                        $('#chart-container'+<?php echo $resSrchPepl['Com_MasterID']; ?>).orgchart({
                                                            'data': datascource,
                                                            'depth': 5,
                                                            'nodeTitle': 'name',
                                                            'nodeContent': 'title',
                                                            'exportButton': true,
                                                            'exportFilename': 'Family Relationship List',
                                                            'nodeID': 'id',
                                                            'createNode': function ($node, data) {
                                                                //console.log(data);
                                                                var secondMenuIcon = $('<i>', {
                                                                    'class': 'fa fa-info-circle second-menu-icon',
                                                                    hover: function () {
                                                                        $(this).siblings('.second-menu').toggle();
                                                                    }
                                                                });
                                                                var secondMenu = '<div class="second-menu"><img class="avatar" src="../images/users/' + data.id + '"></div>';
                                                                $node.append(secondMenuIcon).append(secondMenu);

                                                            }
                                                        });
                                                        $('.oc-export-btn').addClass('hidden');
                                                        $('[rel="tooltip"]').tooltip();
                                                    });

                                                    function loadFamilyLinkInDel(FamMasterID, FamilyName,FamMasterIDs) {

                                                        popup_otrFamilyModel(FamMasterID, FamilyName);

                                                    }
                                                </script>
                                                    <br>
                                            </form>
                                            <?php
                                            }
                                            else {
                                                ?>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="past-info">
                                                            <div class="post-area">
                                                                <header class="infoarea">
                                                                    <strong class="attachemnt_title">
                                                                        <span style="text-align: center;font-size: 15px;font-weight: 800;color: grey;">No Data Found !</span>
                                                                    </strong>
                                                                </header>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </center>
                                        <br>
                                    </div>
                                    <!-- location search -->
                                    <div id="memLocation_tab<?php echo $resSrchPepl['Com_MasterID']; ?>" style="display: none;" class="bhoechie-tab-content">
                                        <center>
                                            <form role="form" class="form-inline" style="color: Green;background-color:#FAFAFF;border-radius:0px 22px 22px 22px;">
                                                <h4><?php echo $resSrchPepl['CName_with_initials']; ?>'s Location</h4><br/>

                                                <?php
                                                if(!empty($resSrchPepl['C_Latitude']) && !empty($resSrchPepl['C_Longitude'])){
                                                    ?>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                   <iframe width="90%;" height="500px" src="https://www.google.com/maps?q=<?php echo $resSrchPepl['C_Latitude']; ?>,<?php echo $resSrchPepl['C_Longitude']; ?>&output=embed"></iframe>
                                                    </div>
                                               </div>
                                                    <?php
                                                }
                                                else {
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="past-info">
                                                                <div class="post-area">
                                                                    <header class="infoarea">
                                                                        <strong class="attachemnt_title">
                                                                            <span style="text-align: center;font-size: 15px;font-weight: 800;color: grey;">No Data Found ! </span>
                                                                        </strong>
                                                                    </header>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </form>
                                        </center>
                                    </div>
                                </div>


                            </div>
                        </div>
                        </p>

                    </div>
                </div>
            </li>
        </ul>

        <?php
    }
    ?>

    <?php
}
else {
    ?>
    <ul class="media-list">
        <li class="media">
            <div class="media-body" style="text-align: center;font-size: 15px;font-weight: 800;color: grey;">
   No matching record found !
            </div>
        </li></ul>
<?php } ?>

    <div class="modal fade" id="otr_family_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document" style="width:50%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="otr_familyTitle"></h4>
                </div>
                <form method="post" class="form-horizontal" id="otr_family_form" name="otr_family_form">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12" id="load_comFamilies_div">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function view_contactTab(Com_MasterID) {

            var memAbout_tab =document.getElementById('memAbout_tab'+Com_MasterID);
            var memSickness_tab =document.getElementById('memSickness_tab'+Com_MasterID);
            var memHelp_tab =document.getElementById('memHelp_tab'+Com_MasterID);
            var memProfession_tab =document.getElementById('memProfession_tab'+Com_MasterID);
            var memContact_tab =document.getElementById('memContact_tab'+Com_MasterID);
            var memFamilyDel_tab =document.getElementById('memFamilyDel_tab'+Com_MasterID);
            var memLocation_tab =document.getElementById('memLocation_tab'+Com_MasterID);

            var contDiv =document.getElementById('contDiv'+Com_MasterID);
            var contIconDiv =document.getElementById('contIconDiv'+Com_MasterID);
            var memDiv =document.getElementById('memDiv'+Com_MasterID);
            var memIconDiv =document.getElementById('memIconDiv'+Com_MasterID);
            var sickDiv =document.getElementById('sickDiv'+Com_MasterID);
            var sickIconDiv =document.getElementById('sickIconDiv'+Com_MasterID);
            var helpDiv =document.getElementById('helpDiv'+Com_MasterID);
            var helpIconDiv =document.getElementById('helpIconDiv'+Com_MasterID);
            var profDiv =document.getElementById('profDiv'+Com_MasterID);
            var profIconDiv =document.getElementById('profIconDiv'+Com_MasterID);
            var familyDiv =document.getElementById('familyDiv'+Com_MasterID);
            var familyIconDiv =document.getElementById('familyIconDiv'+Com_MasterID);
            var locationDiv =document.getElementById('locationDiv'+Com_MasterID);
            var locationIconDiv =document.getElementById('locationIconDiv'+Com_MasterID);

            memAbout_tab.style.display = 'none';
            memSickness_tab.style.display = 'none';
            memHelp_tab.style.display = 'none';
            memProfession_tab.style.display = 'none';
            memFamilyDel_tab.style.display = 'none';
            memLocation_tab.style.display = 'none';
            memContact_tab.style.display = 'block';

            memDiv.style.backgroundColor = '#ffffff';
            memDiv.style.color = '#5A55A3';
            memIconDiv.style.color = '#5A55A3';
            sickDiv.style.backgroundColor = '#ffffff';
            sickDiv.style.color = '#5A55A3';
            sickIconDiv.style.color = '#5A55A3';
            helpDiv.style.backgroundColor = '#ffffff';
            helpDiv.style.color = '#5A55A3';
            helpIconDiv.style.color = '#5A55A3';
            profDiv.style.backgroundColor = '#ffffff';
            profDiv.style.color = '#5A55A3';
            profIconDiv.style.color = '#5A55A3';
            familyDiv.style.backgroundColor = '#ffffff';
            familyDiv.style.color = '#5A55A3';
            familyIconDiv.style.color = '#5A55A3';
            locationDiv.style.backgroundColor = '#ffffff';
            locationDiv.style.color = '#5A55A3';
            locationIconDiv.style.color = '#5A55A3';
            contDiv.style.backgroundColor = '#5A55A3';
            contDiv.style.color = '#ffffff';
            contIconDiv.style.color = '#ffffff';

        }

        function view_sicknessTab(Com_MasterID) {

            var memAbout_tab =document.getElementById('memAbout_tab'+Com_MasterID);
            var memContact_tab =document.getElementById('memContact_tab'+Com_MasterID);
            var memHelp_tab =document.getElementById('memHelp_tab'+Com_MasterID);
            var memProfession_tab =document.getElementById('memProfession_tab'+Com_MasterID);
            var memSickness_tab =document.getElementById('memSickness_tab'+Com_MasterID);
            var memFamilyDel_tab =document.getElementById('memFamilyDel_tab'+Com_MasterID);
            var memLocation_tab =document.getElementById('memLocation_tab'+Com_MasterID);

            var contDiv =document.getElementById('contDiv'+Com_MasterID);
            var contIconDiv =document.getElementById('contIconDiv'+Com_MasterID);
            var memDiv =document.getElementById('memDiv'+Com_MasterID);
            var memIconDiv =document.getElementById('memIconDiv'+Com_MasterID);
            var sickDiv =document.getElementById('sickDiv'+Com_MasterID);
            var sickIconDiv =document.getElementById('sickIconDiv'+Com_MasterID);
            var helpDiv =document.getElementById('helpDiv'+Com_MasterID);
            var helpIconDiv =document.getElementById('helpIconDiv'+Com_MasterID);
            var profDiv =document.getElementById('profDiv'+Com_MasterID);
            var profIconDiv =document.getElementById('profIconDiv'+Com_MasterID);
            var familyDiv =document.getElementById('familyDiv'+Com_MasterID);
            var familyIconDiv =document.getElementById('familyIconDiv'+Com_MasterID);
            var locationDiv =document.getElementById('locationDiv'+Com_MasterID);
            var locationIconDiv =document.getElementById('locationIconDiv'+Com_MasterID);

            memAbout_tab.style.display = 'none';
            memContact_tab.style.display = 'none';
            memHelp_tab.style.display = 'none';
            memProfession_tab.style.display = 'none';
            memFamilyDel_tab.style.display = 'none';
            memLocation_tab.style.display = 'none';
            memSickness_tab.style.display = 'block';

            contDiv.style.backgroundColor = '#ffffff';
            contDiv.style.color = '#5A55A3';
            contIconDiv.style.color = '#5A55A3';
            memDiv.style.backgroundColor = '#ffffff';
            memDiv.style.color = '#5A55A3';
            memIconDiv.style.color = '#5A55A3';
            helpDiv.style.backgroundColor = '#ffffff';
            helpDiv.style.color = '#5A55A3';
            helpIconDiv.style.color = '#5A55A3';
            profDiv.style.backgroundColor = '#ffffff';
            profDiv.style.color = '#5A55A3';
            profIconDiv.style.color = '#5A55A3';
            familyDiv.style.backgroundColor = '#ffffff';
            familyDiv.style.color = '#5A55A3';
            familyIconDiv.style.color = '#5A55A3';
            locationDiv.style.backgroundColor = '#ffffff';
            locationDiv.style.color = '#5A55A3';
            locationIconDiv.style.color = '#5A55A3';
            sickDiv.style.backgroundColor = '#5A55A3';
            sickDiv.style.color = '#ffffff';
            sickIconDiv.style.color = '#ffffff';

        }

        function view_helpTab(Com_MasterID) {

            var memAbout_tab =document.getElementById('memAbout_tab'+Com_MasterID);
            var memSickness_tab =document.getElementById('memSickness_tab'+Com_MasterID);
            var memHelp_tab =document.getElementById('memHelp_tab'+Com_MasterID);
            var memProfession_tab =document.getElementById('memProfession_tab'+Com_MasterID);
            var memContact_tab =document.getElementById('memContact_tab'+Com_MasterID);
            var memFamilyDel_tab =document.getElementById('memFamilyDel_tab'+Com_MasterID);
            var memLocation_tab =document.getElementById('memLocation_tab'+Com_MasterID);

            var contDiv =document.getElementById('contDiv'+Com_MasterID);
            var contIconDiv =document.getElementById('contIconDiv'+Com_MasterID);
            var memDiv =document.getElementById('memDiv'+Com_MasterID);
            var memIconDiv =document.getElementById('memIconDiv'+Com_MasterID);
            var sickDiv =document.getElementById('sickDiv'+Com_MasterID);
            var sickIconDiv =document.getElementById('sickIconDiv'+Com_MasterID);
            var helpDiv =document.getElementById('helpDiv'+Com_MasterID);
            var helpIconDiv =document.getElementById('helpIconDiv'+Com_MasterID);
            var profDiv =document.getElementById('profDiv'+Com_MasterID);
            var profIconDiv =document.getElementById('profIconDiv'+Com_MasterID);
            var familyDiv =document.getElementById('familyDiv'+Com_MasterID);
            var familyIconDiv =document.getElementById('familyIconDiv'+Com_MasterID);
            var locationDiv =document.getElementById('locationDiv'+Com_MasterID);
            var locationIconDiv =document.getElementById('locationIconDiv'+Com_MasterID);

            memAbout_tab.style.display = 'none';
            memSickness_tab.style.display = 'none';
            memContact_tab.style.display = 'none';
            memProfession_tab.style.display = 'none';
            memFamilyDel_tab.style.display = 'none';
            memLocation_tab.style.display = 'none';
            memHelp_tab.style.display = 'block';

            contDiv.style.backgroundColor = '#ffffff';
            contDiv.style.color = '#5A55A3';
            contIconDiv.style.color = '#5A55A3';
            memDiv.style.backgroundColor = '#ffffff';
            memDiv.style.color = '#5A55A3';
            memIconDiv.style.color = '#5A55A3';
            sickDiv.style.backgroundColor = '#ffffff';
            sickDiv.style.color = '#5A55A3';
            sickIconDiv.style.color = '#5A55A3';
            profDiv.style.backgroundColor = '#ffffff';
            profDiv.style.color = '#5A55A3';
            profIconDiv.style.color = '#5A55A3';
            familyDiv.style.backgroundColor = '#ffffff';
            familyDiv.style.color = '#5A55A3';
            familyIconDiv.style.color = '#5A55A3';
            locationDiv.style.backgroundColor = '#ffffff';
            locationDiv.style.color = '#5A55A3';
            locationIconDiv.style.color = '#5A55A3';
            helpDiv.style.backgroundColor = '#5A55A3';
            helpDiv.style.color = '#ffffff';
            helpIconDiv.style.color = '#ffffff';

        }

        function view_professionTab(Com_MasterID) {

            var memAbout_tab =document.getElementById('memAbout_tab'+Com_MasterID);
            var memSickness_tab =document.getElementById('memSickness_tab'+Com_MasterID);
            var memHelp_tab =document.getElementById('memHelp_tab'+Com_MasterID);
            var memProfession_tab =document.getElementById('memProfession_tab'+Com_MasterID);
            var memContact_tab =document.getElementById('memContact_tab'+Com_MasterID);
            var memFamilyDel_tab =document.getElementById('memFamilyDel_tab'+Com_MasterID);
            var memLocation_tab =document.getElementById('memLocation_tab'+Com_MasterID);

            var contDiv =document.getElementById('contDiv'+Com_MasterID);
            var contIconDiv =document.getElementById('contIconDiv'+Com_MasterID);
            var memDiv =document.getElementById('memDiv'+Com_MasterID);
            var memIconDiv =document.getElementById('memIconDiv'+Com_MasterID);
            var sickDiv =document.getElementById('sickDiv'+Com_MasterID);
            var sickIconDiv =document.getElementById('sickIconDiv'+Com_MasterID);
            var helpDiv =document.getElementById('helpDiv'+Com_MasterID);
            var helpIconDiv =document.getElementById('helpIconDiv'+Com_MasterID);
            var profDiv =document.getElementById('profDiv'+Com_MasterID);
            var profIconDiv =document.getElementById('profIconDiv'+Com_MasterID);
            var familyDiv =document.getElementById('familyDiv'+Com_MasterID);
            var familyIconDiv =document.getElementById('familyIconDiv'+Com_MasterID);
            var locationDiv =document.getElementById('locationDiv'+Com_MasterID);
            var locationIconDiv =document.getElementById('locationIconDiv'+Com_MasterID);

            memAbout_tab.style.display = 'none';
            memSickness_tab.style.display = 'none';
            memHelp_tab.style.display = 'none';
            memContact_tab.style.display = 'none';
            memFamilyDel_tab.style.display = 'none';
            memLocation_tab.style.display = 'none';
            memProfession_tab.style.display = 'block';

            contDiv.style.backgroundColor = '#ffffff';
            contDiv.style.color = '#5A55A3';
            contIconDiv.style.color = '#5A55A3';
            memDiv.style.backgroundColor = '#ffffff';
            memDiv.style.color = '#5A55A3';
            memIconDiv.style.color = '#5A55A3';
            sickDiv.style.backgroundColor = '#ffffff';
            sickDiv.style.color = '#5A55A3';
            sickIconDiv.style.color = '#5A55A3';
            helpDiv.style.backgroundColor = '#ffffff';
            helpDiv.style.color = '#5A55A3';
            helpIconDiv.style.color = '#5A55A3';
            familyDiv.style.backgroundColor = '#ffffff';
            familyDiv.style.color = '#5A55A3';
            familyIconDiv.style.color = '#5A55A3';
            locationDiv.style.backgroundColor = '#ffffff';
            locationDiv.style.color = '#5A55A3';
            locationIconDiv.style.color = '#5A55A3';
            profDiv.style.backgroundColor = '#5A55A3';
            profDiv.style.color = '#ffffff';
            profIconDiv.style.color = '#ffffff';

        }

        function view_memAboutTab(Com_MasterID) {

            var memAbout_tab =document.getElementById('memAbout_tab'+Com_MasterID);
            var memSickness_tab =document.getElementById('memSickness_tab'+Com_MasterID);
            var memHelp_tab =document.getElementById('memHelp_tab'+Com_MasterID);
            var memProfession_tab =document.getElementById('memProfession_tab'+Com_MasterID);
            var memContact_tab =document.getElementById('memContact_tab'+Com_MasterID);
            var memFamilyDel_tab =document.getElementById('memFamilyDel_tab'+Com_MasterID);
            var memLocation_tab =document.getElementById('memLocation_tab'+Com_MasterID);

            var contDiv =document.getElementById('contDiv'+Com_MasterID);
            var contIconDiv =document.getElementById('contIconDiv'+Com_MasterID);
            var memDiv =document.getElementById('memDiv'+Com_MasterID);
            var memIconDiv =document.getElementById('memIconDiv'+Com_MasterID);
            var sickDiv =document.getElementById('sickDiv'+Com_MasterID);
            var sickIconDiv =document.getElementById('sickIconDiv'+Com_MasterID);
            var helpDiv =document.getElementById('helpDiv'+Com_MasterID);
            var helpIconDiv =document.getElementById('helpIconDiv'+Com_MasterID);
            var profDiv =document.getElementById('profDiv'+Com_MasterID);
            var profIconDiv =document.getElementById('profIconDiv'+Com_MasterID);
            var familyDiv =document.getElementById('familyDiv'+Com_MasterID);
            var familyIconDiv =document.getElementById('familyIconDiv'+Com_MasterID);
            var locationDiv =document.getElementById('locationDiv'+Com_MasterID);
            var locationIconDiv =document.getElementById('locationIconDiv'+Com_MasterID);

            memSickness_tab.style.display = 'none';
            memHelp_tab.style.display = 'none';
            memProfession_tab.style.display = 'none';
            memContact_tab.style.display = 'none';
            memFamilyDel_tab.style.display = 'none';
            memLocation_tab.style.display = 'none';
            memAbout_tab.style.display = 'block';

            contDiv.style.backgroundColor = '#ffffff';
            contDiv.style.color = '#5A55A3';
            contIconDiv.style.color = '#5A55A3';
            sickDiv.style.backgroundColor = '#ffffff';
            sickDiv.style.color = '#5A55A3';
            sickIconDiv.style.color = '#5A55A3';
            helpDiv.style.backgroundColor = '#ffffff';
            helpDiv.style.color = '#5A55A3';
            helpIconDiv.style.color = '#5A55A3';
            profDiv.style.backgroundColor = '#ffffff';
            profDiv.style.color = '#5A55A3';
            profIconDiv.style.color = '#5A55A3';
            familyDiv.style.backgroundColor = '#ffffff';
            familyDiv.style.color = '#5A55A3';
            familyIconDiv.style.color = '#5A55A3';
            locationDiv.style.backgroundColor = '#ffffff';
            locationDiv.style.color = '#5A55A3';
            locationIconDiv.style.color = '#5A55A3';
            memDiv.style.backgroundColor = '#5A55A3';
            memDiv.style.color = '#ffffff';
            memIconDiv.style.color = '#ffffff';

        }

        function load_member_attachments(Com_MasterID) {

            $.ajax({
                type: 'POST',
                dataType: 'json',
                data: {'Com_MasterID': Com_MasterID,'documentID':7},
                url: "<?php echo site_url('CommunityJammiyaDashboard/load_people_attachments'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    $('#status_attachment_mem_body').empty();
                    $('#status_attachment_mem_body').append('' + data + '');

                    stopLoad();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function view_familyDetailsTab(Com_MasterID) {

            var memAbout_tab =document.getElementById('memAbout_tab'+Com_MasterID);
            var memSickness_tab =document.getElementById('memSickness_tab'+Com_MasterID);
            var memHelp_tab =document.getElementById('memHelp_tab'+Com_MasterID);
            var memProfession_tab =document.getElementById('memProfession_tab'+Com_MasterID);
            var memContact_tab =document.getElementById('memContact_tab'+Com_MasterID);
            var memFamilyDel_tab =document.getElementById('memFamilyDel_tab'+Com_MasterID);
            var memLocation_tab =document.getElementById('memLocation_tab'+Com_MasterID);

            var contDiv =document.getElementById('contDiv'+Com_MasterID);
            var contIconDiv =document.getElementById('contIconDiv'+Com_MasterID);
            var memDiv =document.getElementById('memDiv'+Com_MasterID);
            var memIconDiv =document.getElementById('memIconDiv'+Com_MasterID);
            var sickDiv =document.getElementById('sickDiv'+Com_MasterID);
            var sickIconDiv =document.getElementById('sickIconDiv'+Com_MasterID);
            var helpDiv =document.getElementById('helpDiv'+Com_MasterID);
            var helpIconDiv =document.getElementById('helpIconDiv'+Com_MasterID);
            var profDiv =document.getElementById('profDiv'+Com_MasterID);
            var profIconDiv =document.getElementById('profIconDiv'+Com_MasterID);
            var familyDiv =document.getElementById('familyDiv'+Com_MasterID);
            var familyIconDiv =document.getElementById('familyIconDiv'+Com_MasterID);
            var locationDiv =document.getElementById('locationDiv'+Com_MasterID);
            var locationIconDiv =document.getElementById('locationIconDiv'+Com_MasterID);

            memAbout_tab.style.display = 'none';
            memSickness_tab.style.display = 'none';
            memHelp_tab.style.display = 'none';
            memContact_tab.style.display = 'none';
            memProfession_tab.style.display = 'none';
            memLocation_tab.style.display = 'none';
            memFamilyDel_tab.style.display = 'block';

            contDiv.style.backgroundColor = '#ffffff';
            contDiv.style.color = '#5A55A3';
            contIconDiv.style.color = '#5A55A3';
            memDiv.style.backgroundColor = '#ffffff';
            memDiv.style.color = '#5A55A3';
            memIconDiv.style.color = '#5A55A3';
            sickDiv.style.backgroundColor = '#ffffff';
            sickDiv.style.color = '#5A55A3';
            sickIconDiv.style.color = '#5A55A3';
            helpDiv.style.backgroundColor = '#ffffff';
            helpDiv.style.color = '#5A55A3';
            helpIconDiv.style.color = '#5A55A3';
            profDiv.style.backgroundColor = '#ffffff';
            profDiv.style.color = '#5A55A3';
            profIconDiv.style.color = '#5A55A3';
            locationDiv.style.backgroundColor = '#ffffff';
            locationDiv.style.color = '#5A55A3';
            locationIconDiv.style.color = '#5A55A3';
            familyDiv.style.backgroundColor = '#5A55A3';
            familyDiv.style.color = '#ffffff';
            familyIconDiv.style.color = '#ffffff';

        }

        function view_memLocationTab(Com_MasterID) {

            var memAbout_tab =document.getElementById('memAbout_tab'+Com_MasterID);
            var memContact_tab =document.getElementById('memContact_tab'+Com_MasterID);
            var memHelp_tab =document.getElementById('memHelp_tab'+Com_MasterID);
            var memProfession_tab =document.getElementById('memProfession_tab'+Com_MasterID);
            var memSickness_tab =document.getElementById('memSickness_tab'+Com_MasterID);
            var memFamilyDel_tab =document.getElementById('memFamilyDel_tab'+Com_MasterID);
            var memLocation_tab =document.getElementById('memLocation_tab'+Com_MasterID);

            var contDiv =document.getElementById('contDiv'+Com_MasterID);
            var contIconDiv =document.getElementById('contIconDiv'+Com_MasterID);
            var memDiv =document.getElementById('memDiv'+Com_MasterID);
            var memIconDiv =document.getElementById('memIconDiv'+Com_MasterID);
            var sickDiv =document.getElementById('sickDiv'+Com_MasterID);
            var sickIconDiv =document.getElementById('sickIconDiv'+Com_MasterID);
            var helpDiv =document.getElementById('helpDiv'+Com_MasterID);
            var helpIconDiv =document.getElementById('helpIconDiv'+Com_MasterID);
            var profDiv =document.getElementById('profDiv'+Com_MasterID);
            var profIconDiv =document.getElementById('profIconDiv'+Com_MasterID);
            var familyDiv =document.getElementById('familyDiv'+Com_MasterID);
            var familyIconDiv =document.getElementById('familyIconDiv'+Com_MasterID);
            var locationDiv =document.getElementById('locationDiv'+Com_MasterID);
            var locationIconDiv =document.getElementById('locationIconDiv'+Com_MasterID);

            memAbout_tab.style.display = 'none';
            memContact_tab.style.display = 'none';
            memHelp_tab.style.display = 'none';
            memProfession_tab.style.display = 'none';
            memFamilyDel_tab.style.display = 'none';
            memSickness_tab.style.display = 'none';
            memLocation_tab.style.display = 'block';

            contDiv.style.backgroundColor = '#ffffff';
            contDiv.style.color = '#5A55A3';
            contIconDiv.style.color = '#5A55A3';
            memDiv.style.backgroundColor = '#ffffff';
            memDiv.style.color = '#5A55A3';
            memIconDiv.style.color = '#5A55A3';
            helpDiv.style.backgroundColor = '#ffffff';
            helpDiv.style.color = '#5A55A3';
            helpIconDiv.style.color = '#5A55A3';
            profDiv.style.backgroundColor = '#ffffff';
            profDiv.style.color = '#5A55A3';
            profIconDiv.style.color = '#5A55A3';
            familyDiv.style.backgroundColor = '#ffffff';
            familyDiv.style.color = '#5A55A3';
            familyIconDiv.style.color = '#5A55A3';
            sickDiv.style.backgroundColor = '#ffffff';
            sickDiv.style.color = '#5A55A3';
            sickIconDiv.style.color = '#5A55A3';
            locationDiv.style.backgroundColor = '#5A55A3';
            locationDiv.style.color = '#ffffff';
            locationIconDiv.style.color = '#ffffff';

        }

        function popup_otrFamilyModel(FamMasterID, FamilyName) {

            $('#load_comFamilies_div').html('');
            var titleForfamilies = FamilyName;
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'html',
                data: {FamMasterID:FamMasterID},
                url: "<?php echo site_url('CommunityJammiyaDashboard/load_otrFamily_del'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {

                    $('#otr_family_form')[0].reset();
                    $('#otr_family_form').bootstrapValidator('resetForm', true);

                    $('#load_comFamilies_div').html(data);
                    $('#otr_familyTitle').html(titleForfamilies);
                    $('#otr_family_modal').modal('show');

                    stopLoad();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    stopLoad();
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });

        }

    </script>

<?php
/**
 * Created by PhpStorm.
 * User: Moufiya
 * Date: 5/6/2019
 * Time: 12:20 PM
 */