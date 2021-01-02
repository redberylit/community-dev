<!--Translation added by Naseek-->

<?php $policyID = $master['isMonthly'];
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('hrms_leave_management', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$this->lang->load('calendar', $primaryLanguage);



?>
<button class="btn btn-primary btn-xs pull-right" onclick="modalleaveDetail()"><?php echo $this->lang->line('common_add');?><!--Add--></button>

<table class="<?php echo table_class() ?>">
    <thead>
    <tr style="white-space: nowrap">
        <th><?php echo $this->lang->line('hrms_leave_management_leave_type');?><!--Leave Type--></th>
        <th><?php echo $this->lang->line('hrms_leave_management_policy');?><!--Policy--></th>

            <th style="text-align: right"><?php echo $this->lang->line('hrms_leave_management_hours_to_be_completed');?><!--Hours to be completed--></th>
            <th style="text-align: right"><?php echo $this->lang->line('hrms_leave_management_no_of_hours');?><!--No of Hours--></th>

        <th style="text-align: right"><?php echo $this->lang->line('hrms_leave_management_no_of_day');?><!--No of days--></th>
        <th style="text-align: right"><?php echo $this->lang->line('hrms_leave_management_is_calender_days');?><!--Is Calender Days--></th>

        <th style="text-align: right"><?php echo $this->lang->line('hrms_leave_management_is_allow_minus');?><!--Is Allow minus--></th>
        <th style="text-align: right"><?php echo $this->lang->line('hrms_leave_management_is_carry_forward');?><!--Is carry forward--></th>
        <th style="text-align: right"><?php echo $this->lang->line('hrms_leave_management_max_carry_forward')?></th>
        <?php if (empty($set)) { ?>
            <th style="text-align: right"></th>
        <?php } ?>

    </tr>
    </thead>
    <tbody>

    <?php if ($details) {
        foreach ($details as $val) {


            $CI =& get_instance();
            $set = $CI->db->query("SELECT * FROM srp_employeesdetails WHERE leaveGroupID={$val['leaveGroupID']}")->row_array();

            ?>
            <tr id="row_<?php echo $val['leaveGroupDetailID'] ?>">
                <td><?php echo $val['description'] ?></td>
                <td><?php echo $val['policyDescription'] ?></td>


                    <td style="text-align: right;width: 100px"><?php echo $val['noOfHourscompleted'] ?></td>
                    <td style="text-align: right;width: 100px"><?php echo $val['noOfHours'] ?></td>


                    <td style="text-align: right;width: 100px"><?php echo $val['noOfDays'] ?></td>
                    <td style="text-align: right;width: 100px"><?php echo($val['isCalenderDays'] == 1 ? 'Yes' : 'No') ?></td>


                <td style="text-align: right;width: 100px"><?php echo($val['isAllowminus'] == 1 ? 'Yes' : 'No') ?></td>
                <td style="text-align: right;width: 100px"><?php echo($val['isCarryForward'] == 1 ? 'Yes' : 'No') ?></td>
                <td style="text-align: right;width: 100px"><?php echo $val['maxCarryForward']  ?></td>

                <td>
                    <?php if (empty($set)) { ?><a
                        onclick="deleteLeavedeltails(<?php echo $val['leaveGroupDetailID'] ?>);"><span
                                    class="glyphicon glyphicon-trash" style="color:rgb(209, 91, 71);"></span>
                        </a><?php } ?></td>

            </tr>
            <?php

        }
    }
    ?>

    </tbody>
</table>


