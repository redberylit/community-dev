<?php

$primaryLanguage = getPrimaryLanguage();
$this->lang->load('hrms_leave_management', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
$this->lang->load('calendar', $primaryLanguage);
$title =$this->lang->line('hrms_leave_management_leave_plan');
echo head_page($title, FALSE);
$empID = current_userID();
$leavePlanData = fetch_leavePlan($empID);

if( !empty($leavePlanData)) {
    $leavePlanData = array_group_by($leavePlanData, 'empID');

    $planArr = [];
    foreach ($leavePlanData as $row) {
        $parentID = $row[0]['id'];

        foreach ($row as $key => $emp) {
            /*if($key == 0 ){
                $emp1 = $emp;
                $emp1['id'] = $emp['id'].'01';
                $emp1['duration'] = 0;
                $emp1['parent'] = 0;
                array_push($planArr, $emp1);
            }*/
            // $emp['parent'] = $parentID;

            $emp['parent'] = ($key != 0) ? $parentID : 0;
            if ($key > 0) {
                $emp['text'] = $emp['documentCode'];
            }
            array_push($planArr, $emp);
        }
    }
    //echo '<pre>'; print_r($planArr); echo '</pre>';
    $leavePlanData = $planArr;
}
?>


<script type="text/javascript" src="<?php echo base_url('plugins/dhtmlxGantt/codebase/dhtmlxgantt.js'); ?>"></script>
<link href="<?php echo base_url('plugins/dhtmlxGantt/codebase/dhtmlxgantt.css'); ?>" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url('plugins/dhtmlxGantt/codebase/ext/dhtmlxgantt_tooltip.js'); ?>"></script>


<style>
    .child_preview{
        box-sizing: border-box;
        margin-top: 2px;
        position: absolute;
        z-index: 1;
        color: white;
        text-align: center;
        font-size: 12px;
    }

    .gantt_task_line.task-collapsed{
        height: 4px;
        opacity: 0.25;
    }

    .gantt_task_line.gantt_project.task-collapsed .gantt_task_content{ display: none; }

    .gantt_row.task-parent{ font-weight: bold; }

    .gantt_task_line{ color: rgba(255, 255, 255, 0) !important; }

    .approved-cls{ background: #166123 !important; }

    .confirmed-cls{ background: #13f358 !important; }

    .draft-cls{ background: #61cde2 !important; }

    .plan-cls{ background: #fda70a !important; }
</style>
<div class="row">
    <div class="panel-body">
        <div class="well" style="padding: 10px; margin-bottom: 0px">
            <label for="scale1" class="radio-inline"><input type="radio" id="scale1" name="scale" value="1" /><strong><?php echo $this->lang->line('hrms_leave_management_day_scale');?></strong></label><!--Day scale-->
            <label for="scale2" class="radio-inline"><input type="radio" id="scale2" name="scale" value="2" checked/><strong><?php echo $this->lang->line('hrms_leave_management_week_scale');?><strong></strong></label><!--Week scale-->
            <label for="scale3" class="radio-inline"><input type="radio" id="scale3" name="scale" value="3" /><strong><?php echo $this->lang->line('hrms_leave_management_month_scale');?></strong></label><!--Month scale-->

            <div class="pull-right">
                <label class="radio-inline">
                    <span style="background-color: #166123; border-radius: 2px; border: 1px solid #ccc;">&nbsp;&nbsp; &nbsp;&nbsp;</span> <strong><?php echo $this->lang->line('common_approved');?></strong><!--Approved-->
                </label>
                <label class="radio-inline">
                    <span style="background-color: #13f358; border-radius: 2px; border: 1px solid #ccc;">&nbsp;&nbsp; &nbsp;&nbsp;</span> <strong><?php echo $this->lang->line('common_confirmed');?></strong><!--Confirmed-->
                </label>
                <label class="radio-inline">
                    <span style="background-color: #61cde2; border-radius: 2px; border: 1px solid #ccc;">&nbsp;&nbsp; &nbsp;&nbsp;</span> <strong><?php echo $this->lang->line('common_draft');?></strong><!--Draft-->
                </label>
                <label class="radio-inline">
                    <span style="background-color: #fda70a; border-radius: 2px; border: 1px solid #ccc;">&nbsp;&nbsp; &nbsp;&nbsp;</span> <strong><?php echo $this->lang->line('hrms_leave_management_planned');?></strong><!--Planned-->
                </label>
            </div>
        </div>
    </div>
    <div class="panel-body" style="height: 420px;">
        <div id="leave-plan-gant" style="width:100%; height:100%;"></div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.headerclose').click(function(){
            fetchPage('system/hrm/report/profile-leave-plan','Test','HRMS');
        });

        loadGant();
    });

    function setScaleConfig(value) {
        switch (value) {
            case "1":
                gantt.config.scale_unit = "year";
                gantt.config.step = 1;
                gantt.config.subscales = [{unit: "day", step: 1, date: "%d, %M"}];
                gantt.config.scale_height = 50;
                gantt.config.min_column_width = 60;
                gantt.templates.date_scale = null;
                break;
            case "2":
                var weekScaleTemplate = function (date) {
                    var dateToStr = gantt.date.date_to_str("%d %M, %Y");
                    var endDate = gantt.date.add(gantt.date.add(date, 1, "week"), -1, "day");
                    return dateToStr(date) + " - " + dateToStr(endDate);
                };
                gantt.config.scale_unit = "year";
                gantt.config.date_scale = "%Y";
                gantt.config.step = 1;
                gantt.config.subscales = [
                    {unit: "week", step: 1, date: "%d, %M"}
                ];
                gantt.config.scale_height = 50;
                gantt.config.min_column_width = 60;
                break;
            case "3":
                gantt.config.scale_unit = "year";
                gantt.config.step = 1;
                gantt.config.date_scale = "%Y";
                gantt.config.min_column_width = 50;

                gantt.config.scale_height = 50;
                gantt.templates.date_scale = null;


                gantt.config.subscales = [
                    {unit: "month", step: 1, date: "%M"}
                ];
                break;
        }
    }

    function loadGant() {

        setTimeout(function () {
            gantt.config.readonly = true;
            gantt.config.row_height = 24;
            gantt.config.scale_height = 50;
            gantt.config.details_on_dblclick = false;

            gantt.templates.tooltip_text = function (start, end, task) {
                var applicationType = parseInt(task.applicationType);
                var statusText = '';
                switch (applicationType){
                    case 1:
                        if(parseInt(task.approvedYN) == 1){ statusText = "<div style='text-align: left'><b><?php echo $this->lang->line('common_status');?>:</b><?php echo $this->lang->line('common_approved');?>  </div>"; }/*Approved*/
                        else if(parseInt(task.confirmedYN) == 1){ statusText = "<div style='text-align: left'><b><?php echo $this->lang->line('common_status');?>:</b><?php echo $this->lang->line('common_confirmed');?>  </div>"; }/*Confirmed*/
                        else{ statusText = "<div style='text-align: left'><b><?php echo $this->lang->line('common_status');?>:</b><?php echo $this->lang->line('common_draft');?> </div>";}/*Draft*/
                    break;
                    <!--Status-->    <!--Status-->    <!--Status-->

                    case 2:
                        statusText = "<div style='text-align: left'><b><?php echo $this->lang->line('common_status');?>:</b><?php echo $this->lang->line('hrms_leave_management_planned');?>  </div>";/*Planned*/
                    break;

                    default:
                        statusText = "<div style='text-align: left'><b><?php echo $this->lang->line('common_status');?>:</b> <?php echo $this->lang->line('common_draft');?> </div>";/*Draft*/
                }
                <!--Status--><!--Status-->
                return "<div style='text-align: left;margin-bottom: 0px'><b><?php echo $this->lang->line('common_document');?>:</b> " + task.documentCode + "</div>" +
                    "<div style='text-align: left'><b><?php echo $this->lang->line('common_start_date');?>:</b> " + gantt.templates.tooltip_date_format(start) + "</div>" +
                    "<div style='text-align: left'><b><?php echo $this->lang->line('common_end_date');?>:</b> " + task.endDate2 + "</div>" +
                    "<div style='text-align: left'><b><?php echo $this->lang->line('common_type');?>:</b> " + task.typeText + "</div>" +
                    ""+ statusText +
                    "<div style='text-align: left'><b><?php echo $this->lang->line('common_comments');?>:</b> " + task.levComment + "</div>";
            };
            <!--Document-->/*Start date*//*End date*//*Type*//*Comments*/
            gantt.templates.task_text = function(start, end, task){
                return " ";
            };

            gantt.templates.task_class=function(start, end, task){
                return "child_preview";
            };

            gantt.templates.grid_folder = function(item) {
                var icon;
                if(item.assigned){
                    icon = (item.$open ? "openAssigned.gif" : "closedAssigned.gif")
                }
                else{
                    icon = (item.$open ? "open.gif" : "closed.gif")
                }
                return "<div class='gantt_tree_icon'><span class='glyphicon glyphicon-user'></span></div>";
            };

            gantt.config.columns = [
                {name: "text", label: "Employee", align: "left", tree: true, width: 200}
            ];


            setScaleConfig('2');

            gantt.init("leave-plan-gant");

            function createBox(sizes, class_name){
                var box = document.createElement('div');
                box.style.cssText = [
                    "height:" + sizes.height + "px",
                    "line-height:" + sizes.height + "px",
                    "width:" + sizes.width + "px",
                    "top:" + sizes.top + 'px',
                    "left:" + sizes.left + "px",
                    "position:absolute"
                ].join(";");
                box.className = class_name;
                return box;
            }



            gantt.templates.grid_row_class = gantt.templates.task_class=function(start, end, task){
                var css = [];
                if(gantt.hasChild(task.id)){
                    //css.push("task-parent");
                }
                if (!task.$open && gantt.hasChild(task.id)) {
                    //css.push("task-collapsed");
                }

                return css.join(" ");
            };

            gantt.addTaskLayer(function show_hidden(task) {
                if (!task.$open && gantt.hasChild(task.id)) {
                    var sub_height = gantt.config.row_height - 5,
                        el = document.createElement('div'),
                        sizes = gantt.getTaskPosition(task);

                    var sub_tasks = gantt.getChildren(task.id);
                    var child_el;

                    for (var i = 0; i < sub_tasks.length; i++){
                        var child = gantt.getTask(sub_tasks[i]);
                        var child_sizes = gantt.getTaskPosition(child);
                        var applicationType = parseInt(child.applicationType);

                        var subTaskColor = '';
                        switch (applicationType){
                            case 1:
                                if(parseInt(child.approvedYN) == 1){ subTaskColor = 'approved-cls'; }
                                else if(parseInt(child.confirmedYN) == 1){ subTaskColor = 'confirmed-cls'; }
                                else{ subTaskColor = 'draft-cls';}
                                break;

                            case 2:
                                subTaskColor = 'plan-cls';
                                break;

                            default:
                                subTaskColor = 'draft-cls';
                        }

                        child_el = createBox({
                            height: sub_height,
                            top:sizes.top,
                            left:child_sizes.left,
                            width: child_sizes.width
                        }, "child_preview gantt_task_line "+subTaskColor);
                        child_el.innerHTML =  child.text;
                        el.appendChild(child_el);
                    }
                    return el;
                }
                return false;
            });

            var func = function (e) {
                e = e || window.event;
                var el = e.target || e.srcElement;
                var value = el.value;
                setScaleConfig(value);
                gantt.render();
            };

            var els = document.getElementsByName("scale");
            for (var i = 0; i < els.length; i++) {
                els[i].onclick = func;
            }

            var demo_tasks = { "data": <?php echo json_encode($leavePlanData); ?> };
            gantt.parse(demo_tasks);
        }, 100);
    }
</script>

<?php
/**
 * Created by PhpStorm.
 * User: Nasik
 * Date: 9/28/2017
 * Time: 2:53 PM
 */