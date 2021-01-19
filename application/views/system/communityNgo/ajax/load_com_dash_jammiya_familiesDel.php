<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('communityngo', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
?>

<?php
$date_format_policy = date_format_policy();
if (!empty($comuFamilies)) { ?>
    <div class="row" style="margin-top: 5px">
        <div class="col-md-12">
            <?php
            if ($type == 'html') {
                echo export_buttons('communityFamiliesRprt', 'Community Families Detail', True, false);
            } ?>
        </div>
    </div>
    <br>
    <div class="col-md-12 " id="communityFamiliesRprt">
        <div class="table-responsive mailbox-messages">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th style="width: 10px;">#</th>
                    <th style="width: 15px;"><?php echo $this->lang->line('CommunityNgo_ledger_no');?><!--Ledger No--></th>
                    <th style="width: 10px;"><?php echo $this->lang->line('CommunityNgo_ref_no');?><!--Reference No--></th>
                    <th style="width: 18px;"><?php echo $this->lang->line('CommunityNgo_famName');?><!--Family Name--></th>
                    <th style="width: 18px;"><?php echo $this->lang->line('CommunityNgo_leader');?><!--Leader--></th>
                    <th style="width: 15px;"><?php echo $this->lang->line('CommunityNgo_fam_ancestry');?><!--Ancestory--></th>
                    <th style="width: 13px;"><?php echo $this->lang->line('CommunityNgo_famAddedDate');?><!--Added Date--></th>
                    <th style="width: 10px;"title="Total Members"><?php echo $this->lang->line('CommunityNgo_famTotMem');?><!--Total Members--></th>
                    <th style="width:10px;" title="Is Enroll To House Count"><?php echo $this->lang->line('communityngo_famHusEnrl');?><!-- House Enrolled --></th>
                    <th style="width: 10px;"><?php echo $this->lang->line('common_status');?><!--Status--></th>
                </tr>
                </thead>
                <tbody>
                <?php

                $x = 1;
                $totFm =0;
                foreach ($comuFamilies as $val) {

                    if($val['FamAncestory']==0){ $FamAnces ="Local"; }else{ $FamAnces ="Outside"; }

                    $qmEMin = $this->db->query("SELECT Com_MasterID FROM srp_erp_ngo_com_familydetails WHERE FamMasterID='" . $val['FamMasterID'] . "'");
                    $datMemIn= $qmEMin->row();

                    $queryFM4 = $this->db->query("SELECT Com_MasterID FROM srp_erp_ngo_com_familydetails WHERE FamMasterID='" . $val['FamMasterID'] . "'");
                    $rowFM4 = $queryFM4->result();
                    $femMem2 = array();
                    $totalMm=1;
                    foreach ($rowFM4 as $resFM4) {
                        $femMem2[] = $resFM4->Com_MasterID;

                        $totMm = $totalMm++;

                    }
                    if(empty($rowFM4)){
                        $totMms = '0';
                    }
                    else{
                        $totMms = $totMm;
                    }
                    $in_femMem = "'".implode("', '", $femMem2)."'";

                    $qmEMOtrin = $this->db->query("SELECT Com_MasterID FROM srp_erp_ngo_com_familydetails WHERE FamMasterID !='" . $val['FamMasterID'] . "' AND Com_MasterID IN($in_femMem)");
                    $datMemOtrin= $qmEMOtrin->row();

                    $qmHousing = $this->db->query("SELECT FamMasterID FROM srp_erp_ngo_com_house_enrolling WHERE FamMasterID ='" . $val['FamMasterID'] . "'");
                    $datHousing= $qmHousing->row();
                    ?>
                    <tr>
                        <td class="mailbox-name"><?php echo $x; ?></td>
                        <td class="mailbox-name"><?php echo $val['FamilySystemCode']; ?></td>
                        <td class="mailbox-name"><?php echo $val['LedgerNo']; ?></td>
                        <td class="mailbox-name"><?php echo $val['FamilyName']; ?></td>
                        <td class="mailbox-name"><?php echo $val['CName_with_initials']; ?></td>
                        <td class="mailbox-name"><?php echo $FamAnces; ?></td>
                        <td class="mailbox-name"><?php echo $val['FamilyAddedDate']; ?></td>
                        <td class="mailbox-name"><span data-toggle="tooltip" title="Total Members Of The Family" style="background-color: #33b5e5; color: #fff;font-size: 11px;" class="badge"><b><?php echo $totMms; ?></b></span></td>
                        <td class="mailbox-name">
                                <?php if(!empty($datHousing)){

                                    ?>

                                    <a href="#" style="font-size:14px;"><span title="Enrolled" style="color: green;" rel="tooltip" class="fa fa-home" data-original-title="Enrolled"></span></a> &nbsp;

                                    <?php
                                }else{

                                    ?>

                                    <a href="#" style="font-size:14px;"><span title="Not Enrolled" style="color: red;" rel="tooltip" class="fa fa-home" data-original-title="Not Enrolled"></span></a> &nbsp;

                                <?php
                                }
                                ?>

                        </td>
                        <td class="mailbox-name">
                            <?php if($val['confirmedYN']==0){
                                ?>
                                <span class="label label-danger">&nbsp;</span>
                                <?php
                            }else{
                                ?>
                                <span class="label label-success">&nbsp;</span>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    $totFm += 1;
                    $x++;
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <td class="text-left" colspan="10">
                        Total Families : <?php echo $totFm; ?>

                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
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
 * Date: 1/22/2019
 * Time: 9:22 AM
 */