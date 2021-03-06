<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('inventory', $primaryLanguage);

$this->lang->load('common', $primaryLanguage);
$date_format_policy = date_format_policy();
if ($type == 1) {
    $from = convert_date_format($this->common_data['company_data']['FYPeriodDateFrom']);
    $to = convert_date_format($this->common_data['company_data']['FYPeriodDateTo']);
} else {
    $from = convert_date_format($this->session->userdata("FYBeginingDate"));
    $to = convert_date_format($this->session->userdata("FYEndingDate"));
}
$main_category_arr = all_main_category_report_drop();
$main_category_group_arr = all_main_category_group_report_drop();
?>
<ul class="nav nav-tabs" xmlns="http://www.w3.org/1999/html">
    <li class="active"><a href="#display" data-toggle="tab"><i class="fa fa-television"></i>
            <?php echo $this->lang->line('transaction_display'); ?></a></li><!--Display-->
    <li>
</ul>
<input type="hidden" name="reportID" value="<?php echo $reportID ?>">
<div class="tab-content">
    <div class="tab-pane active" id="display">
        <div class="row">
            <div class="col-md-12 animated zoomIn">
                <?php if ($reportID != "INV_IIQ") { ?>
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border"><?php echo $this->lang->line('transaction_date_range'); ?></legend>
                        <!--Date Range-->
                        <div class="form-group col-sm-4" style="margin-bottom: 0px">
                            <?php if ($reportID == "INV_UBG" || $reportID == "ITM_CNT" || $reportID == "INV_VAL") { ?>
                                <label class="col-md-3 control-label text-left"
                                       for="employeeID"><?php echo $this->lang->line('transaction_as_of'); ?>
                                    :</label><!--As of-->
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class='input-group date filterDate' id="">
                                            <input type='text' class="form-control" name="from"
                                                   value="<?php echo $to; ?>"
                                                   data-inputmask="'alias': '<?php echo $date_format_policy ?>'"/>
                                            <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <label class="col-md-3 control-label text-left"
                                       for="employeeID"><?php echo $this->lang->line('common_from'); ?>
                                    :</label><!--From-->
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class='input-group date filterDate' id="">
                                            <input type='text' class="form-control" value="<?php echo $from; ?>"
                                                   name="from"
                                                   data-inputmask="'alias': '<?php echo $date_format_policy ?>'"/>
                                            <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <?php if ($reportID != "INV_VAL" && $reportID != "INV_UBG" && $reportID != "ITM_CNT" && $reportID != "INV_IIQ") { ?>
                            <div class="form-group col-sm-4" style="margin-bottom: 0px">
                                <label class="col-md-2 control-label text-left"
                                       for="employeeID"><?php echo $this->lang->line('common_to'); ?>:</label><!--To-->
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class='input-group date filterDate' id="">
                                            <input type='text' class="form-control" value="<?php echo $to; ?>" name="to"
                                                   data-inputmask="'alias': '<?php echo $date_format_policy ?>'"/>
                                            <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-sm-4" style="margin-bottom: 0px;">
                            <?php echo $this->lang->line('transaction_from_current_financial_period'); ?>
                        </div><!--From current financial period-->
                    </fieldset>
                <?php } ?>
                <?php if ($reportID == "ITM_FM") { ?>
                    <fieldset class="scheduler-border" style="margin-top: 10px">
                        <legend class="scheduler-border">   <?php echo $this->lang->line('common_segment');?><!--Segment--></legend>
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
                <?php } ?>
                <?php if ($reportID == "ITM_FM") { ?>
                    <fieldset class="scheduler-border" style="margin-top: 10px">
                        <legend class="scheduler-border"> <?php echo $this->lang->line('transaction_report_type'); ?> </legend>
                        <!--Report Type-->
                        <div class="col-sm-12" style="margin-bottom: 0px;margin-top:10px">
                            <div class="skin skin-square">
                                <div class="skin-section">
                                    <ul class="list" style="list-style: none;">
                                        <li><input tabindex="1" type="radio" id="square-radio-1" value="1"
                                                   name="rptType" checked>
                                            <label for="square-radio-1"><?php echo $this->lang->line('common_all'); ?> </label>
                                        </li><!--All-->
                                        <li><input tabindex="2" type="radio" id="square-radio-2" value="2"
                                                   name="rptType">
                                            <label for="square-radio-2"><?php echo $this->lang->line('transaction_top_ten'); ?> </label>
                                        </li><!--Top 10-->
                                        <li><input tabindex="3" type="radio" id="square-radio-3" value="3"
                                                   name="rptType">
                                            <label for="square-radio-3"><?php echo $this->lang->line('transaction_top_twenty'); ?> </label>
                                            <!--Top 20-->
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                <?php }
                if ($reportID == "ITM_CNT" || $reportID == "INV_VAL" || $reportID == "ITM_LG") { ?>
                    <fieldset class="scheduler-border" style="margin-top: 10px">
                        <legend class="scheduler-border"><?php echo $this->lang->line('common_warehouse'); ?></legend>
                        <!--Warehouse-->
                        <div class="col-sm-12" style="margin-bottom: 0px;margin-top:10px">
                            <?php
                            $location = "";
                            if ($type == 1) {
                                $location = array_filter(all_delivery_location_drop(true));
                            } else {
                                $location = array_filter(all_group_warehouse_drop(true));
                            }

                            unset($location['']);
                            echo form_dropdown('location[]', $location, '', 'class="location" id="location" multiple="multiple"'); ?>
                        </div>
                    </fieldset>
                <?php }
                if ($reportID != "ITM_FM" && $reportID != "INV_UBG") { ?>
                    <fieldset class="scheduler-border" style="margin-top: 10px">
                        <legend class="scheduler-border"><?php echo $this->lang->line('transaction_items'); ?> </legend>
                        <!--Items-->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-sm-3">
                                    <label> Main Category </label>
                                    <!--Main Category-->
                                    <?php if ($type == 1) {
                                        echo form_dropdown('mainCategoryID', $main_category_arr, 'Each', 'class="form-control" id="mainCategoryID" onchange="loadSub()"  multiple="multiple"');
                                    } else {
                                        echo form_dropdown('mainCategoryID', $main_category_group_arr, 'Each', 'class="form-control" id="mainCategoryID" onchange="loadSub()"  multiple="multiple"');
                                    }
                                    ?>
                                </div>
                                <div class="col-sm-3">
                                    <label>Sub Category </label>
                                    <!--Sub Category-->
                                    <select name="subcategoryID" id="subcategoryID" class="form-control searchbox"
                                            onchange="loadSubSub()" multiple="multiple">
                                        <!--Select Category-->
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label>Sub Sub Category </label>
                                    <!--Sub Category-->
                                    <select name="subsubcategoryID" id="subsubcategoryID"
                                            class="form-control searchbox" multiple="multiple">
                                        <!--Select Category-->
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" style="margin-bottom: 0px;margin-top:10px">
                                <div class="col-sm-5">
                                    <select name="itemFrom[]" id="search" class="form-control" size="8"
                                            multiple="multiple">
                                        <?php
                                        $items = "";
                                        if ($type == 1) {
                                            $items = fetch_item_data_by_company();
                                        } else {
                                            $items = fetch_group_item_data_by_company();
                                        }
                                        if (!empty($items)) {
                                            foreach ($items as $val) {
                                                echo '<option value="' . $val["itemAutoID"] . '">' . $val["itemSystemCode"] . ' | ' . $val["itemDescription"] . '</option>';
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
                                    <select name="itemTo[]" id="search_to" class="form-control" size="8"
                                            multiple="multiple">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                <?php }
                if ($reportID == "INV_UBG") { ?>
                    <fieldset class="scheduler-border" style="margin-top: 10px">
                        <legend class="scheduler-border"><?php echo $this->lang->line('transaction_vendor'); ?></legend>
                        <!--Vendor-->
                        <div class="col-sm-12" style="margin-bottom: 0px;margin-top:10px">
                            <div class="col-sm-5">
                                <select name="vendorFrom[]" id="search" class="form-control" size="8"
                                        multiple="multiple">
                                    <?php
                                    $supplier = "";
                                    if ($type == 1) {
                                        $supplier = all_supplier_drop();
                                    } else {
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
                <?php } ?>
                <?php if ($reportID != "INV_IIQ") { ?>
                    <fieldset class="scheduler-border" style="margin-top: 10px">
                        <legend class="scheduler-border"><?php echo $this->lang->line('common_extra_columns'); ?> </legend>
                        <!--Extra Columns-->
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
                            <?php echo $this->lang->line('transaction_put_a_cheack_mark'); ?>
                        </div><!-- Put a check mark next to each column that you want to appear in the report-->
                    </fieldset>


                <?php } ?>

                <?php
                if ($reportID == "ITM_CNT") {
                    ?>
                    <fieldset class="scheduler-border" style="margin-top: 10px">
                        <legend class="scheduler-border"> <?php echo $this->lang->line('transaction_sub_items'); ?> </legend>
                        <!--Sub Items-->
                        <div class="col-sm-4" style="margin-bottom: 0px;margin-top:10px">
                            <table class="<?php echo table_class(); ?>" id="extraColumns">
                                <tr>
                                    <td style="vertical-align: middle"><?php echo $this->lang->line('transaction_is_sub_item_required'); ?> </td>
                                    <!--Is Sub Item Required in the Report-->
                                    <td>
                                        <div class="skin skin-square">
                                            <div class="skin-section">
                                                <input tabindex="500"
                                                       id="checkbox500" type="checkbox"
                                                       data-caption="isSubItemRequired"
                                                       class="columnSelected" name="isSubItemRequired"
                                                       value="1">
                                                <label for="checkbox500">
                                                    &nbsp;
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-8" style="margin-bottom: 0px;margin-top:10px">
                            &nbsp;
                        </div>
                    </fieldset>
                    <?php
                }
                ?>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="margin-top: 10px">
                <button type="button" class="btn btn-primary pull-right"
                        onclick="generateReport('<?php echo $formName; ?>')" name="filtersubmit"
                        id="filtersubmit"><i
                            class="fa fa-plus"></i> <?php echo $this->lang->line('common_generate'); ?>
                </button><!--Generate-->
            </div>
        </div>
    </div>
</div>
<script>
    Inputmask().mask(document.querySelectorAll("input"));
    var date_format_policy = '<?php echo strtoupper($date_format_policy) ?>';
    /*$('.filterDate').datepicker({
     autoclose: true,
     forceParse: true,
     format: 'yyyy-mm-dd'
     });*/
    $('.filterDate').datetimepicker({
        useCurrent: false,
        format: date_format_policy,
    });
    $('#search').multiselect({
        search: {
            left: '<input type="text" name="q" class="form-control" placeholder="<?php echo $this->lang->line('common_search');?>..." />', <!--Search-->
            right: '<input type="text" name="q" class="form-control" placeholder="<?php echo $this->lang->line('common_search');?>..." />', <!--Search-->
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
    $("#location").multiselect2({
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
        numberDisplayed: 1,
        buttonWidth: '180px',
        maxHeight: '30px'
    });

    $("#mainCategoryID").multiselect2({
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
        numberDisplayed: 1,
        buttonWidth: '180px',
        maxHeight: '30px'
    });

    $("#subcategoryID").multiselect2({
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
        numberDisplayed: 1,
        buttonWidth: '180px',
        maxHeight: '30px'
    });

    /* $("#subcategoryID").multiselect2('selectAll', false);
     $("#subcategoryID").multiselect2('updateButtonText');*/

    $("#subsubcategoryID").multiselect2({
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
        numberDisplayed: 1,
        buttonWidth: '180px',
        maxHeight: '30px'
    });

    $("#subsubcategoryID").change(function () {
        loadItems();
    });

    /* $("#subsubcategoryID").multiselect2('selectAll', false);
     $("#subsubcategoryID").multiselect2('updateButtonText');

     $("#mainCategoryID").multiselect2('selectAll', false);
     $("#mainCategoryID").multiselect2('updateButtonText');*/

    $("#location").multiselect2('selectAll', false);
    $("#location").multiselect2('updateButtonText');
    /*$('#search_rightAll').trigger('click');*/

    function loadSub() {
        $("#search_to").empty();
        loadSubCategory();
        loadItems();
    }

    function loadSubSub() {
        $("#search_to").empty();
        loadSubSubCategory();
        loadItems();
    }

    function loadSubCategory() {
        $('#subcategoryID option').remove();
        var mainCategoryID = $('#mainCategoryID').val();
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("Report/load_subcat"); ?>',
            dataType: 'json',
            data: {'mainCategoryID': mainCategoryID,type:<?php echo $type; ?>},
            async: false,
            success: function (data) {
                if (!jQuery.isEmptyObject(data)) {
                    $('#subcategoryID').empty();
                    var mySelect = $('#subcategoryID');
                    $.each(data, function (val, text) {
                        mySelect.append($('<option></option>').val(text['itemCategoryID']).html(text['description']));
                    });
                }
                $('#subcategoryID').multiselect2('rebuild');
                /* $("#subcategoryID").multiselect2('selectAll', false);
                 $("#subcategoryID").multiselect2('updateButtonText');*/
            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
        });
    }

    function loadSubSubCategory() {
        $('#subsubcategoryID option').remove();
        var subCategoryID = $('#subcategoryID').val();
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("Report/load_subsubcat"); ?>',
            dataType: 'json',
            data: {'subCategoryID': subCategoryID, type:<?php echo $type; ?>},
            async: false,
            success: function (data) {
                if (!jQuery.isEmptyObject(data)) {
                    $('#subsubcategoryID').empty();
                    var mySelect = $('#subsubcategoryID');
                    $.each(data, function (val, text) {
                        mySelect.append($('<option></option>').val(text['itemCategoryID']).html(text['description']));
                    });
                }
                $('#subsubcategoryID').multiselect2('rebuild');
                /*$("#subsubcategoryID").multiselect2('selectAll', false);
                 $("#subsubcategoryID").multiselect2('updateButtonText');*/
            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
        });
    }


    function loadItems() {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("Report/loadItems"); ?>',
            dataType: 'json',
            data: {
                subSubCategoryID: $('#subsubcategoryID').val(),
                mainCategoryID: $('#mainCategoryID').val(),
                subCategoryID: $('#subcategoryID').val(),
                type:<?php echo $type; ?>
            },
            async: false,
            success: function (data) {
                if (!jQuery.isEmptyObject(data)) {
                    $('#search').empty();
                    var mySelect = $('#search');
                    $.each(data, function (val, text) {
                        mySelect.append($('<option></option>').val(text['itemAutoID']).html(text['itemSystemCode'] + ' | ' + text['itemDescription']));
                    });
                } else {
                    $('#search').empty();
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
        });
    }

    $('#segment').multiselect2({
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
        maxHeight: '30px',
        allSelectedText: 'All Selected'
    });
    $("#segment").multiselect2('selectAll', false);
    $("#segment").multiselect2('updateButtonText');
</script>