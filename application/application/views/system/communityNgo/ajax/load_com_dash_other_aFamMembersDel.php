<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('communityngo', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);

$this->load->helper('community_ngo_helper');

$companyCode = current_companyCode();
?>

<?php
$date_format_policy = date_format_policy();
if (!empty($comFamMembers)) { ?>
    <div class="row" style="margin-top: 5px">
        <div class="col-md-12">
            <?php
            if ($type == 'html') {
                echo export_buttons('commFamMembersRprt', 'Community Family Detail', True, false);
            } ?>
        </div>
    </div>
    <br>
    <div class="col-md-12 " id="commFamMembersRprt">
            <div class="row">
                <div class="col-md-12 animated zoomIn">
                    <header class="head-title">
                        <h4>HEAD OF THE FAMILY AND FAMILY MEMBERS DETAIL</h4>
                    </header>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-9">
                    <table class="table table-striped" id="profileInfoTable"
                           style="background-color: #ffffff;width: 100%">
                        <tbody>

                        <tr>
                            <td>
                                <strong class="textColor">Head Of The Family: </strong>
                            </td>
                            <td style="font-weight: bold;">
                                <?php echo $master['CName_with_initials']; ?>
                            </td>
                            <td>
                                <strong class="textColor">Full Name:</strong>
                            </td>
                            <td>
                                <?php echo $master['CFullName']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="textColor">Area :</strong>
                            </td>
                            <td style="font-weight: bold;">
                                <?php echo $master['arDescription'] ?>
                            </td>
                            <td>
                                <strong class="textColor">Gender:</strong>
                            </td>
                            <td>
                                <?php echo $master['name'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="textColor">Date of Birth :</strong>
                            </td>
                            <td>
                                <?php echo $master['CDOB'] ?>
                            </td>
                            <td>
                                <strong class="textColor">N.I.C :</strong>
                            </td>
                            <td>
                                <?php echo $master['CNIC_No']; ?>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <strong class="textColor">Phone (Primary) :</strong>
                            </td>
                            <td>
                                <?php echo $master['TP_Mobile']; ?>
                            </td>
                            <td>
                                <strong class="textColor">Phone (Secondary) :</strong>
                            </td>
                            <td>
                                <?php echo $master['TP_home']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="textColor">Contact Address :</strong>
                            </td>
                            <td>
                                <?php echo $master['C_Address'] ?>
                            </td>
                            <td>
                                <strong class="textColor">House No :</strong>
                            </td>
                            <td>
                                <?php echo $master['HouseNo'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong class="textColor">Email :</strong></td>
                            <td>
                                <?php echo $master['EmailID'] ?>
                            </td>
                            <td>
                                <strong class="textColor">Permanent Address :</strong>
                            </td>
                            <td>
                                <?php echo $master['P_Address'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="textColor">Marital Status :</strong>
                            </td>
                            <td>
                                <?php
                                echo $master['maritalstatus'];
                                ?>
                            </td>
                            <td>
                                <strong class="textColor">GS Division :</strong>
                            </td>
                            <td>
                                <?php echo $master['diviDescription']. ' - ' .$master['GS_No'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="textColor">Reference No :</strong>
                            </td>
                            <td>
                                <?php echo $master['LedgerNo'] ?>
                            </td>
                            <td>
                                <strong class="textColor">Ledger No :</strong>
                            </td>
                            <td>
                                <?php echo $master['FamilySystemCode'] ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-3">
                    <div class="fileinput-new thumbnail">
                        <?php
                        $communityimage = get_all_community_images($master['CImage'],'Community/'.$companyCode.'/MemberImages/','communityNoImg');

                        if ($master['CImage'] != '') { ?>
                            <img src="<?php echo $communityimage; ?>"
                                 id="changeImg" style="width: 200px; height: 145px;">
                            <?php
                        } else { ?>
                            <img src="<?php echo $communityimage; ?>" id="changeImg"
                                 style="width: 200px; height: 145px;">
                        <?php } ?>
                    </div>
                </div>
            </div>
            <br>
            <br>
            <?php
            if (!empty($comFamMembers)) { ?>
                <div class="table-responsive mailbox-messages">
                    <table class="table table-hover table-striped">
                        <tbody>
                        <tr class="task-cat-upcoming" style="color: black;font-weight: bold;">
                            <td class="headrowtitle" style="border-bottom: solid 1px #50749f;">#</td>
                            <td class="headrowtitle" style="border-bottom: solid 1px #50749f;">MEMBER/S</td>
                            <td class="headrowtitle" style="border-bottom: solid 1px #50749f;">GENDER</td>
                            <td class="headrowtitle" style="border-bottom: solid 1px #50749f;">DATE OF BIRTH</td>
                            <td class="headrowtitle" style="border-bottom: solid 1px #50749f;">RELATIONSHIP</td>
                            <td class="headrowtitle" style="border-bottom: solid 1px #50749f;">MARITAL STATUS</td>
                            <td class="headrowtitle" style="border-bottom: solid 1px #50749f;">ADDED DATE</td>
                        </tr>
                        <?php
                        $x = 1;
                        $totalMem = 1;
                        $rowColor='';
                        $rowUserImg='';
                        foreach ($comFamMembers as $val) {

                            if($val['isMove'] ==1 ){ $moveStatus='<span onclick="get_memMoved_history('.$val['Com_MasterID'].','.$val['FamMasterID'].', \'' . $val['CName_with_initials']. '\');" style="width:8px;height:8px;font-size: 0.73em;float: right;background-color: #00a5e6; display:inline-block;color: #00a5e6;" title="Moved To Another Family">m</span>'; } else{ $moveStatus=''; }
                            if($val['isActive'] ==1 ){ $activeState=''; } else{
                                if($val['DeactivatedFor']==2){ $INactReson='Migrate';} else{$INactReson='Death';}
                                $activeState='<span style="width:8px;height:8px;font-size: 0.73em;float: right;background-color:red; display:inline-block;color: red;" title="The Member Is Inactive :'.$INactReson.'">a</span>';}

                            if($val['isLeader'] == '1'){
                                $rowColor = 'style="background-color:lightsteelblue;"';
                                $rowUserImg = '<i class="fa fa-user" style="float: right;" title="Head Of The Family"></i>';
                            }
                            else{
                                $rowColor='';
                                $rowUserImg='';
                            }
                            ?>
                            <tr <?php echo $rowColor; ?>>
                                <td class="mailbox-star" width=""><?php echo $x .' '.$rowUserImg; ?></td>
                                <td class="mailbox-star" width=""><?php echo $val['CName_with_initials'] ."&nbsp;". $moveStatus ."&nbsp;&nbsp;".$activeState ?></td>
                                <td class="mailbox-star" width=""><?php echo $val['name'] ?></td>
                                <td class="mailbox-star" width=""><?php echo $val['CDOB'] ?> &nbsp;(<?php echo $val['Age'] ?>)</td>
                                <td class="mailbox-star" width=""><?php echo $val['relationship'] ?></td>
                                <td class="mailbox-star" width="">
                                    <?php
                                    echo $val['maritalstatus'];
                                    ?>
                                <td class="mailbox-star"><?php echo $val['FamMemAddedDate'] ?></td>
                            </tr>
                            <?php

                            $totMem = $totalMem++;
                            $x++;
                        }
                        ?>
                        </tbody>
                        <tfoot >
                        <tr>
                            <td style="" class="" colspan="7">Total Members : <?php echo $totMem; ?></td>
                        </tr>
                        </tfoot>
                    </table><!-- /.table -->
                </div>
                <?php
            } else { ?>
                <br>
                <div class="search-no-results">THERE ARE NO RECORDS TO DISPLAY.</div>
                <?php
            }
            ?>
            <br>
            <div class="row">
                <div class="col-md-12 animated zoomIn">
                    <header class="head-title">
                        <h4>RECORD DETAILS</h4>
                    </header>
                </div>
            </div>
            <table class="table table-striped" id="recordInfoTable"
                   style="background-color: #ffffff;width: 100%">
                <tbody>
                <tr>
                    <td>
                        <strong class="textColor">Created Date :</strong>
                    </td>
                    <td>
                        <?php echo $master['createdDateTime'] ?>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <strong class="textColor">Family Created By :</strong>
                    </td>
                    <td>
                        <?php echo $master['createdUserName'] ?>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <strong class="textColor">Last Updated :</strong>
                    </td>
                    <td>
                        <?php echo $master['modifiedDateTime'] ?>
                    </td>
                    <td></td>
                </tr>

                </tbody>
            </table>

    </div>
    <?php
} else { ?>
    <br>
    <div class="search-no-results">THERE ARE NO RECORDS TO DISPLAY.</div>
    <?php
}
?>
    <script type="text/javascript">
        $('.extraColumns input').iCheck({
            checkboxClass: 'icheckbox_square_relative-blue',
            radioClass: 'iradio_square_relative-blue',
            increaseArea: '20%'
        });
    </script>


<?php
/**
 * Created by PhpStorm.
 * User: Moufiya
 * Date: 1/24/2019
 * Time: 3:46 PM
 */