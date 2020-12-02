<?php
$primaryLanguage = getPrimaryLanguage();

$this->lang->load('communityngo', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);

$this->load->helper('community_ngo_helper');
$date_format_policy = date_format_policy();

$com_relation = fetch_ngo_relationType_drop();
$memMale_drop = fetch_maleMember_drop();
$memFemale_drop = fetch_femaleMember_drop();
?>
        <div class="row">
            <div class="col-sm-12 table-responsive mailbox-messages">
             <table class="table table-hover table-striped">
                 <thead><tr class="task-cat-upcoming" style="font-size:12px;color: black;font-weight: bold;">  <td style="border-bottom: solid 1px #f76f01;">#</td><td style="border-bottom: solid 1px #f76f01;"><?php echo $this->lang->line('communityngo_name_of_member'); ?></td><td style="border-bottom: solid 1px #f76f01;"><?php echo $this->lang->line('communityngo_com_member_father'); ?></td><td style="border-bottom: solid 1px #f76f01;"><?php echo $this->lang->line('communityngo_com_member_mother'); ?></td><td style="border-bottom: solid 1px #f76f01;"><?php echo $this->lang->line('communityngo_relationship'); ?></td></tr></thead><tbody>
                 <?php
                 if (!empty($Com_MemID)) {
                     $r=1;
                 foreach ($Com_MemID as $row) {

                 ?>
                 <tr>
                     <td class="mailbox-star"><?php echo $r; ?></td>
                     <td><?php echo $row["CName_with_initials"]; ?></td>
                     <td>    <select id="cfComMasID" class="form-control select2"
                                     name="cfComMasID[]">
                             <option data-currency=""
                                     value=""><?php echo $this->lang->line('communityngo_select_member'); ?></option>
                             <?php

                             if (!empty($memMale_drop)) {
                                 foreach ($memMale_drop as $val) {

                                     if($row['Com_MasterID'] != $val['Com_MasterID']){
                                         if($row['cfComMasID'] == $val['Com_MasterID']){
                                             ?>
                                             <option value="<?php echo $val['Com_MasterID']; ?>" selected="selected"><?php echo $val['CName_with_initials']. ' | ' . trim($val['CNIC_No']); ?></option>
                                         <?php } else{ ?>
                                             <option value="<?php echo $val['Com_MasterID']; ?>"><?php echo $val['CName_with_initials']. ' | ' . trim($val['CNIC_No']); ?></option>
                                             <?php
                                         }
                                     }
                                 }
                             }
                             ?>
                         </select>
                     </td>
                     <td>        <select id="cmComMasID" class="form-control select2"
                                         name="cmComMasID[]">
                             <option data-currency=""
                                     value=""><?php echo $this->lang->line('communityngo_select_member'); ?></option>
                             <?php
                             if (!empty($memFemale_drop)) {
                                 foreach ($memFemale_drop as $val) {

                                     if($row['Com_MasterID'] != $val['Com_MasterID']){
                                         if($row['cmComMasID'] == $val['Com_MasterID']){
                                             ?>
                                             <option value="<?php echo $val['Com_MasterID']; ?>" selected="selected"><?php echo $val['CName_with_initials']. ' | ' . trim($val['CNIC_No']); ?></option>
                                         <?php } else{ ?>
                                             <option value="<?php echo $val['Com_MasterID']; ?>"><?php echo $val['CName_with_initials']. ' | ' . trim($val['CNIC_No']); ?></option>
                                             <?php
                                         }
                                     }
                                 }
                             }
                             ?>
                         </select></td>
                     <td>

                         <select id="memRelaID" class="form-control select2"
                                 name="memRelaID[]" data-placeholder="Select Relationship Type" style="height:30px;width:180px;font-size: 13px;" required>
                             <option value="">Select Relationship</option>
                             <?php
                             if (!empty($com_relation)) {
                                 foreach ($com_relation as $val) {
                                     if($row['GenderID'] == $val['genderID']) {
                                         ?>
                                         <option
                                             value="<?php echo $val['relationshipID'] ?>"><?php echo $val['relationship'] ?></option>
                                         <?php
                                     }
                                 }
                             }
                             ?>
                         </select>

                     </td>
                 </tr>
                     <?php
                     $r++;
                 }
                 } else {
                     ?>

                   <tr><td colspan="3">No data available.</td></tr>

                 <?php } ?>
                 </tbody></table>
            </div>
        </div>


