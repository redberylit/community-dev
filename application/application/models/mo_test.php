<tr>
    <td style="font-size: 12px;color:#095db3;">
        <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityNgo_is_alive'); ?>: </strong>
    </td>
    <td style="font-size: 12px;">
        <?php if($extra['master']['cf_grt_GrandFIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
    </td>
    <td style="font-size: 12px;">
        <?php if($extra['master']['cf_grt_GrandMIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
    </td>
    <td style="font-size: 12px;">
        <?php if($extra['master']['cm_grt_GrandFIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
    </td>
    <td style="font-size: 12px;">
        <?php if($extra['master']['cm_grt_GrandMIsAlive'] == '0'){ echo '<span class="label label-warning">No</span>'; } else{ echo '<span class="label label-success">Yes</span>';} ?>
    </td>
</tr>
<tr>
    <td style="font-size: 12px;color:#095db3;">
        <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityngo_name'); ?>: </strong>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['cf_grt_GrandFFullName']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['cf_grt_GrandMFullName']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['cm_grt_GrandFFullName']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['cm_grt_GrandMFullName']; ?>
    </td>
</tr>
<tr>
    <td style="font-size: 12px;color:#095db3;">
        <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityNgo_born_country'); ?>: </strong>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['fgptCountry']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['fgmtCountry']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['mgptCountry']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['mgmtCountry']; ?>
    </td>
</tr>
<tr>
    <td style="font-size: 12px;color:#095db3;">
        <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityNgo_born_area'); ?>: </strong>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['fgptArea']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['fgmtArea']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['mgptArea']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['mgmtArea']; ?>
    </td>
</tr>
<tr>
    <td style="font-size: 12px;color:#095db3;">
        <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityngo_dob'); ?>: </strong>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['cf_grt_GrandFDOB']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['cf_grt_GrandMDOB']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['cm_grt_GrandFDOB']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['cm_grt_GrandMDOB']; ?>
    </td>
</tr>
<tr>
    <td style="font-size: 12px;color:#095db3;">
        <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityNgo_Sbc_no'); ?>/<?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['cf_grt_GrandFBC_No']; ?> / <?php echo $extra['master']['cf_grt_GrandFBCDate']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['cf_grt_GrandMBC_No']; ?> / <?php echo $extra['master']['cf_grt_GrandMBCDate']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['cm_grt_GrandFBC_No']; ?> / <?php echo $extra['master']['cm_grt_GrandFBCDate']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['cm_grt_GrandMBC_No']; ?> / <?php echo $extra['master']['cm_grt_GrandMBCDate']; ?>
    </td>
</tr>
<tr>
    <td style="font-size: 12px;color:#095db3;">
        <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityngo_nic'); ?>: </strong>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['cf_grt_GrandFNIC_No']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['cf_grt_GrandMNIC_No']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['cm_grt_GrandFNIC_No']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['cm_grt_GrandMNIC_No']; ?>
    </td>
</tr>
<tr>
    <td style="font-size: 12px;color:#095db3;">
        <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityNgo_dateOfDeath'); ?>: </strong>
    </td>
    <td style="font-size: 12px;">
        <?php if($extra['master']['cf_grt_GrandFIsAlive'] == '0'){ echo $extra['master']['cf_grt_GrandFDateOfDeath']; } else { echo '-'; } ?>
    </td>
    <td style="font-size: 12px;">
        <?php if($extra['master']['cf_grt_GrandMIsAlive'] == '0'){ echo $extra['master']['cf_grt_GrandMDateOfDeath']; } else { echo '-'; } ?>
    </td>
    <td style="font-size: 12px;">
        <?php if($extra['master']['cm_grt_GrandFIsAlive'] == '0'){ echo $extra['master']['cm_grt_GrandFDateOfDeath']; } else { echo '-'; } ?>
    </td>
    <td style="font-size: 12px;">
        <?php if($extra['master']['cm_grt_GrandMIsAlive'] == '0'){ echo $extra['master']['cm_grt_GrandMDateOfDeath']; } else { echo '-'; } ?>
    </td>
</tr>
<tr>
    <td style="font-size: 12px;color:#095db3;">
        <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityNgo_Sdc_no'); ?>/<?php echo $this->lang->line('communityNgo_regDate'); ?>: </strong>
    </td>
    <td style="font-size: 12px;">
        <?php if($extra['master']['cf_grt_GrandFIsAlive'] == '0'){echo $extra['master']['cf_grt_GrandFDC_No']; ?> / <?php echo $extra['master']['cf_grt_GrandMDCDate'];} else { echo '-';}?>
    </td>
    <td style="font-size: 12px;">
        <?php if($extra['master']['cf_grt_GrandMIsAlive'] == '0'){echo $extra['master']['cf_grt_GrandMDC_No']; ?> / <?php echo $extra['master']['cf_grt_GrandMDCDate'];} else { echo '-';}?>
    </td>
    <td style="font-size: 12px;">
        <?php if($extra['master']['cm_grt_GrandFIsAlive'] == '0'){ echo $extra['master']['cm_grt_GrandFDC_No']; ?> / <?php echo $extra['master']['cm_grt_GrandFDCDate'];} else { echo '-';}?>
    </td>
    <td style="font-size: 12px;">
        <?php if($extra['master']['cm_grt_GrandMIsAlive'] == '0'){ echo $extra['master']['cm_grt_GrandMDC_No']; ?> / <?php echo $extra['master']['cm_grt_GrandMDCDate'];} else { echo '-';}?>
    </td>
</tr>
<tr>
    <td style="font-size: 12px;color:#095db3;">
        <strong id="_grt_GrandPrts"><?php echo $this->lang->line('communityngo_Job'); ?>: </strong>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['fgptJobDes']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['fgmtJobDes']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['mgptJobDes']; ?>
    </td>
    <td style="font-size: 12px;">
        <?php echo $extra['master']['mgmtJobDes']; ?>
    </td>
</tr>