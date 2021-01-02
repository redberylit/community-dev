<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('dashboard', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$priority_arr = all_priority_new_drop();
$current_date = format_date($this->common_data['current_date']);
?>
<ul class="todo-list">
    <?php
    if (!empty($todolist)) {
        foreach ($todolist as $val) {
            ?>
            <li class="" style="padding: 5px;" id="list_<?php echo $val['autoId'] ?>_<?php echo $userDashboardID ?>">
                  <span class="">
                    <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                  </span>
                &nbsp;

                <?php
                if ($val['priority'] == 1) {
                    ?>
                    <lable class="label label-success" style="padding-top: 2%;"><input type="checkbox"
                                                                                       id="donechk_<?php echo $val['autoId'] ?>_<?php echo $userDashboardID ?>"
                                                                                       name="donechk"
                                                                                       onclick="changeDone<?php echo $userDashboardID ?>(<?php echo $val['autoId'] ?>,<?php echo $userDashboardID ?>)"
                                                                                       value="" <?php if ($val['isCompleated'] == -1) {
                            echo 'checked';
                        } ?>></lable>
                    <?php
                } else if ($val['priority'] == 2) {
                    ?>
                    <lable class="label label-warning" style="padding-top: 2%;"><input type="checkbox"
                                                                                       id="donechk_<?php echo $val['autoId'] ?>_<?php echo $userDashboardID ?>"
                                                                                       name="donechk"
                                                                                       onclick="changeDone<?php echo $userDashboardID ?>(<?php echo $val['autoId'] ?>,<?php echo $userDashboardID ?>)"
                                                                                       value="" <?php if ($val['isCompleated'] == -1) {
                            echo 'checked';
                        } ?>></lable>
                    <?php
                } else if ($val['priority'] == 3) {
                    ?>
                    <lable class="label label-danger" style="padding-top: 2%;"><input type="checkbox"
                                                                                      id="donechk_<?php echo $val['autoId'] ?>_<?php echo $userDashboardID ?>"
                                                                                      name="donechk"
                                                                                      onclick="changeDone<?php echo $userDashboardID ?>(<?php echo $val['autoId'] ?>,<?php echo $userDashboardID ?>)"
                                                                                      value="" <?php if ($val['isCompleated'] == -1) {
                            echo 'checked';
                        } ?>></lable>
                    <?php

                }
                ?>

                <span class="text" style="cursor: pointer; font-size: 13px;"><?php echo trim_value($val['description'],40)  ?></span>

                <small class="label label-info "
                       style="font-size: 11px;"><?php echo $val['startTime'] ?></small>
                <div class="tools">
                    <!--<i class="fa fa-edit"></i>-->
                    <i class="fa fa-trash-o" onclick="deletetodoList<?php echo $userDashboardID ?>(<?php echo $val['autoId'] ?>,<?php echo $userDashboardID ?>)"></i>
                </div>
            </li>
            <?php
        }
    }else{
        ?>
       <?php echo $this->lang->line('dashboard_record_not_found');?><!-- Record Not Found-->
    <?php
    }
    ?>
</ul>






<script>






</script>
