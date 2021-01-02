<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('communityngo', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
?>

<?php
$date_format_policy = date_format_policy();
if (!empty($comMembers)) { ?>
    <div class="row" style="margin-top: 5px">
        <div class="col-md-12">
            <?php
            if ($type == 'html') {
                echo export_buttons('communityMemberRprt', 'Community Member Details', True, false);
            } ?>
        </div>
    </div>
    <br>
    <div class="col-md-12 " id="communityMemberRprt">
        <div class="table-responsive mailbox-messages">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th style="width: 30px"></th>
                    <th style="width: 120px;"><?php echo $this->lang->line('communityngo_MemberCode'); ?></th>
                    <th style="width: 220px;">
                        <?php echo $this->lang->line('communityngo_member_name_with_int'); ?><!--Member Name--></th>
                    <th style="width: 70px"><?php echo $this->lang->line('communityngo_nic'); ?><!--NIC--></th>
                    <th style="width: 70px"><?php echo $this->lang->line('communityngo_gender'); ?><!--Gender--></th>
                    <th style="width: 85px"><?php echo $this->lang->line('communityngo_TP_Mobile'); ?><!--Mobile--></th>
                    <th style="width: 150px"><?php echo $this->lang->line('communityngo_region'); ?><!--Region--></th>
                    <th style="width: 150px">
                        <?php echo $this->lang->line('communityngo_GS_Division'); ?><!--GS Division--></th>
                    <th style="width: 75px">
                        <?php echo $this->lang->line('communityngo_com_member_header_Status'); ?><!--Status--></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $x = 1;
                $totCmMembers = 0;
          
                foreach ($comMembers as $val) {

                    if($val['CImage']){

                        $comImage = '<img class="align-left"
                 src="' . base_url('uploads/NGO/communitymemberImage/' . $val['CImage']) . '"
                 width="32" height="32">';

                    }
                    else{
                        $comImage = '<img class="align-left" src="' . base_url("images/crm/icon-list-contact.png") . '"
                                     alt="" width="32" height="32">';

                    }

                    if($val['isActive']==1){

                        $status = '<span class="label" style="background-color:#8bc34a; color: #FFFFFF;">&nbsp;</span>';

                    }
                    else{
                        $status = '<span class="label" style="background-color: rgba(255, 72, 49, 0.96); color: #FFFFFF;">&nbsp;</span>';

                    }

                    ?>
                        <tr>
                            <td class="mailbox-star"><?php echo $x; ?></td>
                            <td class="mailbox-star"><?php echo $comImage; ?></td>
                            <td class="mailbox-star"><?php echo $val['MemberCode']; ?></td>
                            <td class="mailbox-star"><?php echo $val['CName_with_initials']; ?></td>
                            <td class="mailbox-star"><?php echo $val['CNIC_No']; ?></td>
                            <td class="mailbox-star"><?php echo $val['Gender']; ?></td>
                            <td class="mailbox-star"><?php echo $val['PrimaryNumber']; ?></td>
                            <td class="mailbox-star"><?php echo $val['Region']; ?></td>
                            <td class="mailbox-star"><?php echo $val['GS_Division']; ?></td>
                            <td class="mailbox-star"><?php echo $status; ?></td>
                        </tr>
                    
                        <?php
                        $x++;
                        $totCmMembers += 1;
                  
                }
                ?>
                </tbody>
                <tfoot>
                <tr>

                    <td class="text-left" colspan="10">
                        Total Community Members : <?php echo $totCmMembers; ?>

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
 * Date: 1/21/2019
 * Time: 9:54 AM
 */