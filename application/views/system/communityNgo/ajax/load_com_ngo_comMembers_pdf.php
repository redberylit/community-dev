<?php
$primaryLanguage = getPrimaryLanguage();

$this->lang->load('communityngo', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);

?>
    <div class="table-responsive">
        <table style="width: 100%">
            <tbody>
            <tr>
                <td style="width:55%;">
                    <table>
                        <tr>
                            <td style="font-size: 12px;">
                                <img alt="Logo" style="height: 130px" src="<?php
                                echo mPDFImage.$this->common_data['company_data']['company_logo']; ?>">
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:45%;">
                    <table>
                        <tr>
                            <td colspan="3">
                                <h4><strong><?php echo $this->common_data['company_data']['company_name'].' ('.$this->common_data['company_data']['company_code'].').'; ?></strong></h4>
                                <p><?php echo $this->common_data['company_data']['company_address1'].' '.$this->common_data['company_data']['company_address2'].' '.$this->common_data['company_data']['company_city'].' '.$this->common_data['company_data']['company_country']; ?></p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
<?php
if (!empty($comMemberMas)) {
    ?>
    <br>
    <div class="table-responsive mailbox-messages">
        <table class="table table-hover table-striped" style="background-color: #ffffff; width: 100%;font-size: 15px;">
            <tbody>
            <tr>
                <td style="border-top: 1px solid #ffffff;font-size:11px;text-transform: uppercase;">#</td>
                <td style="border-top: 1px solid #ffffff;font-size:11px;text-transform: uppercase;"></td>
                <td style="border-top: 1px solid #ffffff;font-size:11px;text-transform: uppercase;"><?php echo $this->lang->line('communityngo_MemberCode'); ?></td>
                <td style="border-top: 1px solid #ffffff;font-size:11px;text-transform: uppercase;"><?php echo $this->lang->line('communityngo_member_name_with_int'); ?><!--Member Name--></td>
                <td style="border-top: 1px solid #ffffff;font-size:11px;text-transform: uppercase;"><?php echo $this->lang->line('communityngo_nic'); ?><!--NIC--></td>
                <td style="border-top: 1px solid #ffffff;font-size:11px;text-transform: uppercase;"><?php echo $this->lang->line('communityngo_gender'); ?><!--Gender--></td>
                <td style="border-top: 1px solid #ffffff;font-size:11px;text-transform: uppercase;"><?php echo $this->lang->line('communityngo_TP_Mobile'); ?><!--Mobile--></td>
                <td style="border-top: 1px solid #ffffff;font-size:11px;text-transform: uppercase;"><?php echo $this->lang->line('communityngo_region'); ?><!--Region--></td>
                <td style="border-top: 1px solid #ffffff;font-size:11px;text-transform: uppercase;"><?php echo $this->lang->line('communityngo_GS_Division'); ?><!--GS Division--></td>
                <td style="border-top: 1px solid #ffffff;font-size:11px;text-transform: uppercase;">
                    <?php echo $this->lang->line('communityngo_com_member_header_Status'); ?><!--Status--></td>
            </tr>
            <?php
            $x = 1;

            foreach ($comMemberMas as $val) {
                $communityimage = get_all_community_images($val['CImage'],'Community/'.$companyCode.'/MemberImages/','communityNoImg');

                ?>
                <tr>
                    <td class="mailbox-name" style="font-weight: 600; color: saddlebrown;"><?php echo $x; ?></td>
                    <td class="mailbox-name" style="color: #469bda;"><img alt="Logo" style="height:45px;width:50px;" src="<?php echo $communityimage; ?>"></td>
                    <td class="mailbox-name" style="color: #469bda;"><?php echo $val['MemberCode']; ?></td>
                    <td class="mailbox-name" style="color: #469bda;"><?php echo $val['CName_with_initials']; ?></td>
                    <td class="mailbox-name" style="color: #469bda;"><?php echo $val['CNIC_No']; ?></td>
                    <td class="mailbox-name" style="color: #469bda;"><?php echo $val['Gender']; ?></td>
                    <td class="mailbox-name" style="color: #469bda;"><?php echo $val['TP_Mobile']; ?></td>
                    <td class="mailbox-name" style="color: #469bda;"><?php echo $val['Region']; ?></td>
                    <td class="mailbox-name" style="color: #469bda;"><?php echo $val['GS_Division']; ?></td>
                    <td class="mailbox-name"><a href="#">
                            <?php if($val['isActive'] == '1'){
                                ?>
                                <span data-toggle="tooltip" title="Enrolled" style="background-color: #009688; color: #f1f0f0;font-size:10px;" class="badge">Yes</span>
                                <?php
                            }else{
                                ?>
                                <span data-toggle="tooltip" title="Not Enrolled" style="background-color: #ff0000; color: #f1f0f0;font-size:10px;" class="badge">No</span>
                                <?php
                            }
                            ?>

                    </td>
                </tr>
                <?php
                $x++;
            }
            ?>
            </tbody>

        </table><!-- /.table -->
    </div>
    <?php

}
else { ?>
    <br>

    <div><?php echo $this->lang->line('community_there_are_no_rec_to_display');?><!--THERE ARE NO RECORDS TO DISPLAY-->.</div>
    <?php
}

?>





<?php
/**
 * Created by PhpStorm.
 * User: Moufiya
 * Date: 7/17/2020
 * Time: 13:37 PM
 */
