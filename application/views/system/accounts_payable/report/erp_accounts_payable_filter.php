<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('accounts_payable', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);

$date_format_policy = date_format_policy();
if($type == 1) {
    $from = convert_date_format($this->common_data['company_data']['FYBegin']);
    $to = convert_date_format($this->common_data['company_data']['FYEnd']);
}
else{
    $from = convert_date_format($this->session->userdata("FYBeginingDate"));
    $to = convert_date_format($this->session->userdata("FYEndingDate"));
}
?>
<ul class="nav nav-tabs" xmlns="http://www.w3.org/1999/html">
    <li class="active"><a href="#display" data-toggle="tab"><i class="fa fa-television"></i>
           <?php echo $this->lang->line('accounts_payable_reports_vl_display');?> <!--Display--> </a></li>
    <li>
</ul>
<input type="hidden" name="reportID" value="<?php echo $reportID ?>">
<div class="tab-content">
    <div class="tab-pane active" id="display">
        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?php echo $this->lang->line('accounts_payable_reports_vl_date_range');?> <!--Date Range--></legend>
                    <?php if ($reportID == 'AP_VL') { ?>
                        <div class="col-sm-8" style="">
                            <div class="input-daterange input-group col-sm-12" id="datepicker">
                                <div class="form-group col-sm-6" style="margin-bottom: 0px">
                                    <label class="col-md-3 control-label text-left"
                                           for="employeeID"><?php echo $this->lang->line('common_from');?><!--From-->:</label>
                                    <div class="form-group col-md-8">
                                        <div class='input-group date filterDate' id="">
                                            <input type="text" name="from" id="from" data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                                                   value="<?php echo $from; ?>"
                                                   class="form-control" required>
                                            <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6" style="margin-bottom: 0px">
                                    <label class="col-md-3 control-label text-left"
                                           for="employeeID"><?php echo $this->lang->line('common_to');?><!--To-->:</label>
                                    <div class="form-group col-md-8">
                                        <div class='input-group date filterDate' id="">
                                            <input type="text" name="to" data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                                                   value="<?php echo current_format_date() ?>"
                                                   class="form-control " required>
                                            <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="form-group col-sm-4" style="margin-bottom: 0px">
                            <label class="col-md-3 control-label text-left"
                                   for="employeeID"><?php echo $this->lang->line('accounts_payable_reports_vl_as_of');?><!--As of-->:</label>
                            <div class="form-group col-md-8">
                                <div class='input-group date filterDate' id="">
                                    <input type="text" name="from" data-inputmask="'alias': '<?php echo $date_format_policy ?>'"
                                           value="<?php echo current_format_date() ?>"
                                           class="form-control " required>
                                            <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="col-sm-4" style="margin-bottom: 0px;">
                        <?php echo $this->lang->line('accounts_payable_reports_vl_from_current_financial_period');?><!--From current financial period-->
                    </div>
                </fieldset>
                <fieldset class="scheduler-border" style="margin-top: 10px">
                    <legend class="scheduler-border"><?php echo $this->lang->line('accounts_payable_reports_vl_vendor');?><!--Vendor--></legend>
                    <div class="col-sm-12" style="margin-bottom: 0px;margin-top:10px">
                        <div class="col-sm-5">
                            <select name="vendorFrom[]" id="search" class="form-control" size="8"
                                    multiple="multiple">
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
                <?php if ($reportID == 'AP_VAS' || $reportID == 'AP_VAD') { ?>
                    <fieldset class="scheduler-border" style="margin-top: 10px">
                        <legend class="scheduler-border"><?php echo $this->lang->line('common_days');?><!--Days--></legend>
                        <div class="col-sm-12" style="margin-bottom: 0px;margin-top:10px">
                            <div class="form-group col-sm-4" style="margin-bottom: 0px">
                                <label class="col-md-6 control-label text-left"
                                       for="employeeID"><?php echo $this->lang->line('accounts_payable_reports_vl_intervel');?><!--Interval--> (<?php echo $this->lang->line('accounts_payable_reports_vl_days');?><!--days-->)</label>
                                <div class="input-group col-md-3">
                                    <input type="number" name="interval"
                                           value="30" max="99" min="10" onchange="maxMinInput(this,10,99)"
                                           class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group col-sm-5" style="margin-bottom: 0px">
                                <label class="col-md-6 control-label text-left"
                                       for="employeeID"><?php echo $this->lang->line('accounts_payable_reports_vl_through');?><!--Through--> (<?php echo $this->lang->line('accounts_payable_reports_vl_days_past_due');?><!--days past due-->)</label>
                                <div class="input-group col-md-3">
                                    <input type="number" name="through"
                                           value="100" max="1000" onchange="maxMinInput(this,11,1000)"
                                           class="form-control input-xs" required>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                <?php } ?>
                <fieldset class="scheduler-border" style="margin-top: 10px">
                    <legend class="scheduler-border"><?php echo $this->lang->line('accounts_payable_reports_vl_extra_columns');?><!--Extra Columns--></legend>
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
                        <?php echo $this->lang->line('accounts_payable_reports_vl_put_a_cheack_mark');?> <!-- Put a check mark next to each column that you want to appear in the report-->
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="margin-top: 10px">
                <button type="button" class="btn btn-primary pull-right"
                        onclick="generateReport('<?php echo $formName; ?>')" name="filtersubmit"
                        id="filtersubmit"><i
                        class="fa fa-plus"></i> <?php echo $this->lang->line('common_generate');?><!--Generate-->
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    Inputmask().mask(document.querySelectorAll("input"));
    var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';
    /*$('.input-daterange').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });
    $('.filterDate').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });*/

    $('.filterDate').datetimepicker({
        useCurrent: false,
        format: date_format_policy,
    });

    $('#search').multiselect({
        search: {
            left: '<input type="text" name="q" class="form-control" placeholder="<?php echo $this->lang->line('common_search');?> ..." />',<!--Search-->
            right: '<input type="text" name="q" class="form-control" placeholder="<?php echo $this->lang->line('common_search');?> ..." />',<!--Search-->
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

    function maxMinInput(input, min, max) {
        if (input.value < min) input.value = min;
        if (input.value > max) input.value = max;
    }
    /*$('#search_rightAll').trigger('click');*/
</script>