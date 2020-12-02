<?php echo head_page('Manage Machine', false);
$mfq_faID = isset($page_id) && !empty($page_id) ? $page_id : 0;
$unit_of_messure = all_umo_new_drop();
?>

<input type="hidden" id="tmp_mainCatID" value="0">
<input type="hidden" id="tmp_mainSubCatID" value="0">
<input type="hidden" id="tmp_mainSubSubCatID" value="0">

<link href="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/datatables/customer-style-datatable.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/mfq/custom-mfq.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/buttons/button.css'); ?>" rel="stylesheet">
<script src="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.js'); ?>"></script>

<!--<button type="button" data-text="Add" id="btnAdd" onclick="fetchPage('system/mfq/master/manage-machine','','Machine')"
        class="btn btn-sm btn-default">
    <i class="fa fa-refresh" aria-hidden="true"></i> Refresh
</button>
<hr>-->

<form method="post" id="from_add_edit_machine">
    <input type="hidden" value="<?php echo $mfq_faID ?>" id="mfq_faID" name="mfq_faID"/>

    <div class="row">
        <div class="col-md-12 animated zoomIn">
            <header class="head-title">
                <h2>Machine Information </h2>
            </header>
            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Machine Name </label>
                </div>

                <div class="form-group col-sm-4">
                <span class="input-req" title="Required Field">
                    <input type="text" name="assetDescription" id="assetDescription" class="form-control"
                           placeholder="Machine Name"
                           required>
                    <span class="input-req-inner"></span>
                </span>
                </div>

            </div>

            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Part No</label>
                </div>

                <div class="form-group col-sm-4">
                <span class="input-req" title="Required Field">
                    <input type="text" name="partNumber" id="partNumber" class="form-control"
                           placeholder="Part No"
                           required>
                    <span class="input-req-inner"></span>
                </span>
                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Manufacture</label>
                </div>

                <div class="form-group col-sm-4">
                <span class="input-req" title="Required Field">
                    <input type="text" name="manufacture" id="manufacture" class="form-control"
                           placeholder="Manufacture">

                </span>
                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Unit Of Measure</label>
                </div>

                <div class="form-group col-sm-4">
                <span class="input-req" title="Required Field">
                     <?php echo form_dropdown('unitOfMeasureID', $unit_of_messure, '', 'class="form-control select2" id="unitOfMeasureID" required'); ?>
                    <span class="input-req-inner"></span>
                </span>
                </div>
            </div>

            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Rate</label>
                </div>

                <div class="form-group col-sm-4">
                <span class="input-req" title="Required Field">
                   <input type="text" class="number form-control" onkeypress="validateFloatKeyPress(this,event)" id="unitRate" name="unitRate" required>
                </span>
                </div>
            </div>
        </div>


        <div class="col-md-12 animated zoomIn">
            <header class="head-title">
                <h2>Categories </h2>
            </header>

            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Main </label>
                </div>

                <div class="form-group col-sm-4">
                <span class="input-req" title="Required Field">
                    <?php echo form_dropdown('mfq_faCatID', get_mfq_category_drop(0, 2), '', 'class="form-control" id="mfqCategoryID"  required'); ?>
                    <span class="input-req-inner"></span>
                </span>

                </div>
            </div>

            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Sub </label>
                </div>

                <div class="form-group col-sm-4">
                    <select name="mfq_faSubCatID" class="form-control" id="frm_subCategory" required>
                        <option value=""></option>
                    </select>
                </span>

                </div>
            </div>

            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Sub Sub </label>
                </div>

                <div class="form-group col-sm-4">
                    <select name="mfq_faSubSubCatID" class="form-control" id="frm_subSubCategory">
                                    <option value=""></option>
                                </select>
                </span>

                </div>
            </div>


        </div>
    </div>

    <div class="col-md-12 animated zoomIn">
        <div class="row" style="margin-top: 10px;">
            <div class="col-sm-7">
                <div class="pull-right">
                    <button class="btn btn-primary" type="submit" id="submitMachineBtn"><i class="fa fa-plus"></i> Add
                        Machine
                    </button>
                </div>
            </div>
        </div>
    </div>

</form>


<?php echo footer_page('Right foot', 'Left foot', false); ?>


<script type="text/javascript">

    $(document).ready(function () {
        $("#mfqCategoryID").change(function (e) {
            var mfqCategoryID = $("#mfqCategoryID").val();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {parentID: mfqCategoryID},
                url: "<?php echo site_url('MFQ_ItemMaster/get_mfq_subCategory'); ?>",
                beforeSend: function () {
                    $("#frm_subCategory").empty();
                    $("#frm_subSubCategory").empty();
                },

                success: function (data) {
                    if (data) {
                        $("#frm_subCategory").append('<option value="-1">Select</option>');
                        $.each(data, function (key, value) {
                            $("#frm_subCategory").append('<option value="' + value['itemCategoryID'] + '">' + value['description'] + '</option>');
                        });
                    }
                    var tmpSubCatID = $("#tmp_mainSubCatID").val();
                    if (tmpSubCatID > 0) {
                        $("#frm_subCategory").val(tmpSubCatID);
                        $("#frm_subCategory").change();
                        $("#tmp_mainSubCatID").val(0);
                    }
                    ;
                }, error: function (xhr, ajaxOptions, thrownError) {
                    myAlert('e', "Code" + xhr.status + " : Error : " + thrownError)
                }
            });
        });

        $("#frm_subCategory").change(function (e) {
            var subCategoryID = $("#frm_subCategory").val();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {parentID: subCategoryID},
                url: "<?php echo site_url('MFQ_ItemMaster/get_mfq_subCategory'); ?>",
                beforeSend: function () {
                    $("#frm_subSubCategory").empty();
                },

                success: function (data) {
                    if (data) {
                        //console.log(data);
                        $("#frm_subSubCategory").append('<option value="-1">Select</option>');
                        $.each(data, function (key, value) {
                            $("#frm_subSubCategory").append('<option value="' + value['itemCategoryID'] + '">' + value['description'] + '</option>');
                        });
                    }

                    var tmpSubCatID = $("#tmp_mainSubCatID").val();
                    var tmp_mainSubSubCatID = $("#tmp_mainSubSubCatID").val();

                    if (tmpSubCatID > 0) {
                        $("#frm_subCategory").val(tmpSubCatID);
                        //$("#frm_subCategory").change();
                        $("#tmp_mainSubCatID").val(0);

                    }
                    setTimeout(function () {
                        $("#frm_subSubCategory").val(tmp_mainSubSubCatID);
                    }, 500);

                }, error: function (xhr, ajaxOptions, thrownError) {
                    myAlert('e', "Code" + xhr.status + " : Error : " + thrownError)
                }
            });
        });


        $('.headerclose').click(function () {
            fetchPage('system/mfq/mfq_asset_master', '', 'Machine')
        });

        $("#from_add_edit_machine").submit(function (e) {
            addEditItem();
            return false;
        });
        loadMachineDetail();
        $('.select2').select2();
    });


    function addEditItem() {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("MFQ_AssetMaster/add_edit_mfq_machine"); ?>',
            dataType: 'json',
            data: $("#from_add_edit_machine").serialize(),
            async: false,
            success: function (data) {
                if (data['error'] == 1) {
                    myAlert('e', data['message']);
                }
                else if (data['error'] == 0) {
                    if (data['code'] == 1) {
                        $("#from_add_edit_machine")[0].reset();
                    }
                    myAlert('s', data['message']);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                myAlert('e', xhr.responseText);
            }
        });
    }

    function loadMachineDetail() {
        console.log('xx');
        var mfq_faID = '<?php echo $mfq_faID ?>';
        if (mfq_faID > 0) {
            $.ajax({
                type: 'POST',
                url: '<?php echo site_url("MFQ_AssetMaster/load_mfq_Machine"); ?>',
                dataType: 'json',
                data: {mfq_faID: mfq_faID},
                async: false,
                success: function (data) {
                    if (data['error'] == 0) {
                        console.log(data);
                        myAlert('s', data['message']);
                        $("#submitMachineBtn").html('<i class="fa fa-pencil"></i> Edit Machine');

                        $("#assetDescription").val(data['assetDescription']);
                        $("#partNumber").val(data['partNumber']);
                        $("#manufacture").val(data['manufacture']);
                        $("#unitOfMeasureID").val(data['unitOfmeasureID']);
                        $("#mfqCategoryID").val(data['mfq_faCatID']);
                        $("#mfqCategoryID").change();
                        $("#tmp_mainSubCatID").val(data['mfq_faSubCatID']);
                        $("#tmp_mainSubSubCatID").val(data['mfq_faSubSubCatID']);
                        $("#unitRate").val(data['unitRate']);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    myAlert('e', xhr.responseText);
                }
            });
        }
    }
    function validateFloatKeyPress(el, evt) {
        //alert(currency_decimal);
        var charCode = (evt.which) ? evt.which : event.keyCode;
        var number = el.value.split('.');

        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        //just one dot
        if (number.length > 1 && charCode == 46) {
            return false;
        }
        //get the carat position
        var caratPos = getSelectionStart(el);
        var dotPos = el.value.indexOf(".");
        if (caratPos > dotPos && dotPos > -(currency_decimal - 1) && (number[1].length > (currency_decimal - 1))) {
            return false;
        }
        return true;
    }


</script>