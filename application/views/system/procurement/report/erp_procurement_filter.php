<?php
$date_format_policy = date_format_policy();
if($type == 1) {
    $from = convert_date_format($this->common_data['company_data']['FYPeriodDateFrom']);
    $to = current_format_date();
}
else{
    $from = convert_date_format($this->session->userdata("FYBeginingDate"));
    $to = convert_date_format($this->session->userdata("FYEndingDate"));
}
?>
<ul class="nav nav-tabs" xmlns="http://www.w3.org/1999/html">
    <li class="active"><a href="#display" data-toggle="tab"><i class="fa fa-television"></i>
            Display </a></li>
    <li>
</ul>
<input type="hidden" name="reportID" value="<?php echo $reportID ?>">
<div class="tab-content">
    <div class="tab-pane active" id="display">
        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Date</legend>
                    <div class="col-sm-6" style="">
                        <div class="input-daterange input-group" id="datepicker">
                            <div class="form-group col-sm-6" style="margin-bottom: 0px">
                                <label class="col-md-4 control-label text-left"
                                       for="employeeID">From:</label>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class='input-group date filterDate' id="">
                                            <input type='text' class="form-control" name="from" value="<?php echo $from; ?>" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" />
                                            <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-6" style="margin-bottom: 0px">
                                <label class="col-md-4 control-label text-left"
                                       for="employeeID">To:</label>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class='input-group date filterDate' id="">
                                            <input type='text' class="form-control" name="to" value="<?php echo $to; ?>" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" />
                                            <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4" style="margin-bottom: 0px;">
                        From current financial period
                    </div>
                </fieldset>
                <fieldset class="scheduler-border" style="margin-top: 10px">
                    <legend class="scheduler-border">   Segment</legend>
                    <div class="col-sm-12" style="margin-bottom: 0px;margin-top:10px">
                        <?php
                        if($type == 1){
                            //$segment = array_filter(fetch_segment(true));
                            $segment = array_filter_reports(fetch_segment(true));
                        }else{
                            $segment = array_filter(fetch_group_segment(true));
                        }
                        unset($segment['']);
                        echo form_dropdown('segment[]', $segment, '', 'class="segment" id="segment" multiple="multiple"'); ?>
                    </div>
                </fieldset>
                <fieldset class="scheduler-border" style="margin-top: 10px">
                    <legend class="scheduler-border">Vendor</legend>
                    <div class="col-sm-12" style="margin-bottom: 0px;margin-top:10px">
                        <div class="col-sm-5">
                            <select name="vendorFrom[]" id="search" class="form-control" size="8" multiple="multiple">
                                <?php
                                if($type == 1){
                                    $supplier = all_supplier_drop();
                                }else{
                                    $supplier = all_group_supplier_drop();
                                }
                                unset($supplier[""]);
                                if (!empty($supplier)) {
                                    foreach ($supplier as $key => $val) {
                                        echo '<option value="' . $key . '">' . $val . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <!--<button type="button" id="undo_redo_undo" class="btn btn-primary btn-block">undo</button>-->
                            <button type="button" id="search_rightAll" class="btn btn-block btn-sm"
                            ><i class="fa fa-forward"></i></button>
                            <button type="button" id="search_rightSelected" class="btn btn-block btn-sm"><i
                                    class="fa fa-chevron-right"></i></button>
                            <button type="button" id="search_leftSelected" class="btn btn-block btn-sm"><i
                                    class="fa fa-chevron-left"></i></button>
                            <button type="button" id="search_leftAll" class="btn btn-block btn-sm"><i
                                    class="fa fa-backward"></i></button>
                            <!--<button type="button" id="undo_redo_redo" class="btn btn-warning btn-block">redo</button>-->
                        </div>
                        <div class="col-sm-5">
                            <select name="vendorTo[]" id="search_to" class="form-control" size="8"
                                    multiple="multiple">

                            </select>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="scheduler-border" style="margin-top: 10px">
                    <legend class="scheduler-border">Status</legend>
                    <div class="col-sm-12" style="margin-bottom: 0px;margin-top:10px">
                        <div class="skin skin-square">
                            <div class="skin-section">
                                <div class="col-sm-6">
                                    <ul class="list" style="list-style: none;">
                                        <li><input tabindex="1" type="radio" id="square-radio-1" value="0"
                                                   name="status">
                                            <label for="square-radio-1">Not Received</label></li>
                                        <li><input tabindex="2" type="radio" id="square-radio-2" value="1"
                                                   name="status">
                                            <label for="square-radio-2">Partially Received</label></li>
                                    </ul>
                                </div>
                                <div class="col-sm-6">
                                    <ul class="list" style="list-style: none;">
                                        <li><input tabindex="3" type="radio" id="square-radio-3" value="2"
                                                   name="status">
                                            <label for="square-radio-3">Fully Received</label></li>
                                        <li><input tabindex="4" type="radio" id="square-radio-4" value="3"
                                                   name="status" checked>
                                            <label for="square-radio-4">All</label></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="scheduler-border" style="margin-top: 10px">
                    <legend class="scheduler-border">Extra Columns</legend>
                    <div class="col-sm-4" style="margin-bottom: 0px;margin-top:10px">
                        <table class="<?php echo table_class(); ?>" id="extraColumns">
                            <?php
                            if (!empty($columns)) {
                                $i = 1;
                                foreach ($columns as $val) {
                                    $checked = "";
                                    if ($val["isDefault"] == 1) {
                                        $checked = "checked";
                                    }
                                    if ($val["isMandatory"] == 0) {
                                        ?>
                                        <tr>
                                            <td style="vertical-align: middle"><?php echo $val["caption"] ?></td>
                                            <td>
                                                <div class="skin skin-square">
                                                    <div class="skin-section">
                                                        <input tabindex="<?php echo $i; ?>"
                                                               id="checkbox<?php echo $i; ?>" type="checkbox"
                                                               data-caption="<?php echo $val["caption"] ?>"
                                                               class="columnSelected" name="fieldName"
                                                               value="<?php echo $val["fieldName"] ?>" <?php echo $checked ?>>
                                                        <label for="checkbox<?php echo $i; ?>">
                                                            &nbsp;
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    } else {
                                        ?>
                                        <tr class="hide">
                                            <td style="vertical-align: middle"><?php echo $val["caption"] ?></td>
                                            <td>
                                                <div class="checkbox checkbox-primary">
                                                    <input id="checkbox<?php echo $i; ?>" type="checkbox"
                                                           data-caption="<?php echo $val["caption"] ?>"
                                                           class="columnSelected" name="fieldName"
                                                           value="<?php echo $val["fieldName"] ?>" <?php echo $checked ?>>
                                                    <label for="checkbox<?php echo $i; ?>">
                                                        &nbsp;
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    $i++;
                                }
                            } ?>
                        </table>
                    </div>
                    <div class="col-sm-8" style="margin-bottom: 0px;margin-top:10px">
                        Put a check mark next to each column that you want to appear in the report
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="margin-top: 10px">
                <button type="button" class="btn btn-primary pull-right"
                        onclick="generateReport('<?php echo $formName; ?>')" name="filtersubmit"
                        id="filtersubmit"><i
                        class="fa fa-plus"></i> Generate
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        Inputmask().mask(document.querySelectorAll("input"));
        var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';
        /*$('.input-daterange').datepicker({
         format: date_format_policy,
         autoclose:true
         });
         $('.filterDate').datepicker({
         format: date_format_policy,
         autoclose:true
         });*/
        $('.filterDate').datetimepicker({
            useCurrent: false,
            format: date_format_policy,
        });
        $('#search').multiselect({
            search: {
                left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
            },
            afterMoveToLeft: function ($left, $right, $options) {
                $("#search_to option").prop("selected", "selected");
            }
        });
        $('.skin-square input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%'
        });

        $('#extraColumns input').iCheck({
            checkboxClass: 'icheckbox_square_relative-blue',
            radioClass: 'iradio_square_relative-blue',
            increaseArea: '20%'
        });

        $('#segment').multiselect2({
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            allSelectedText: 'All Selected'
        });
        $("#segment").multiselect2('selectAll', false);
        $("#segment").multiselect2('updateButtonText');
    });

</script>