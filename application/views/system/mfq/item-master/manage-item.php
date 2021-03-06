<?php echo head_page('Manage Item', false);
$mfqItemID = isset($page_id) && !empty($page_id) ? $page_id : 0;
$main_category_arr = all_main_category_drop();
?>

<input type="hidden" id="tmp_mainCatID" value="0">
<input type="hidden" id="tmp_mainSubCatID" value="0">
<input type="hidden" id="tmp_mainSubSubCatID" value="0">

<link href="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/datatables/customer-style-datatable.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/mfq/custom-mfq.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/buttons/button.css'); ?>" rel="stylesheet">
<script src="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.js'); ?>"></script>


<form method="post" id="from_add_edit_crew">
    <input type="hidden" value="<?php echo $mfqItemID ?>" id="mfqItemID" name="mfqItemID"/>

    <div class="row">
        <div class="col-md-12 animated zoomIn">
            <header class="head-title">
                <h2>Item Information </h2>
            </header>
            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Category </label>
                </div>

                <div class="form-group col-sm-4">
                <span class="input-req" title="Required Field">
                    <select name="itemType" class="form-control" id="itemType">
                        <option value="">Select</option>
                        <option value="1">Raw material</option>
                        <option value="2">Finish good</option>
                        <option value="3">Semi finish good</option>
                    </select>
                    <span class="input-req-inner"></span>
                </span>

                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Finance Category </label>
                </div>

                <div class="form-group col-sm-4">
                <span class="input-req" title="Required Field">
                     <?php echo form_dropdown('mainCategoryID', $main_category_arr, '', 'class="form-control select2" id="mainCategoryID"  onchange="load_sub_cat()"'); ?>
                    <span class="input-req-inner"></span>
                </span>

                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Sub Category </label>
                </div>

                <div class="form-group col-sm-4">
                <span class="input-req" title="Required Field">
                     <select name="subcategoryID" id="subcategoryID" class="form-control searchbox select2"
                             onchange="load_sub_sub_cat()">
                            <option value="">Select Category</option>
                        </select>
                    <span class="input-req-inner"></span>
                </span>

                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Sub sub Category </label>
                </div>

                <div class="form-group col-sm-4">
                    <select name="subSubCategoryID" id="subSubCategoryID" class="form-control searchbox select2">
                        <option value="">Select Category</option>
                    </select>
                    </span>
                </div>
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Item Description </label>
                </div>

                <div class="form-group col-sm-4">
                <span class="input-req" title="Required Field">
                    <input type="text" name="itemName" id="itemName" class="form-control" placeholder="Item Name"
                    >
                    <span class="input-req-inner"></span>
                </span>
                </div>

            </div>

            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Secondary Code</label>
                </div>

                <div class="form-group col-sm-2">
                <span class="input-req" title="Required Field">
                    <input type="text" name="secondaryItemCode" id="secondaryItemCode" class="form-control"
                           placeholder="Item Code"
                    >
                    <span class="input-req-inner"></span>
                </span>
                </div>
            </div>

            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Units of measure</label>
                </div>

                <div class="form-group col-sm-2">
                <span class="input-req" title="Required Field">
                    <?php echo form_dropdown('defaultUnitOfMeasureID', all_umo_new_drop(), '', 'class="form-control select2" id="defaultUnitOfMeasureID" '); ?>
                    <!--  --><?php /*echo form_dropdown('defaultUnitOfMeasureID', all_umo_new_drop(), '', 'class="form-control select2" id="defaultUnitOfMeasureID" '); */ ?>
                    <span class="input-req-inner"></span>
                </span>
                </div>
            </div>

            <div class="row hide unbilledservice" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">unbilled Services Gl Code</label>
                </div>

                <div class="form-group col-sm-4">
                <span class="input-req" title="Required Field">
                    <?php echo form_dropdown('unbilledServicesGLAutoID', fetch_all_gl_codes(), '', 'class="form-control select2" id="unbilledServicesGLAutoID" '); ?>
                    <!--  --><?php /*echo form_dropdown('defaultUnitOfMeasureID', all_umo_new_drop(), '', 'class="form-control select2" id="defaultUnitOfMeasureID" '); */ ?>
                    <span class="input-req-inner"></span>
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

                    <?php echo form_dropdown('mfqCategoryID', get_mfq_category_drop(), '', 'class="form-control" id="mfqCategoryID" '); ?>
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
                <span class="input-req" title="Required Field">
                    <select name="mfqSubCategoryID" class="form-control" id="frm_subCategory">
                        <option value=""></option>
                    </select>
                    <span class="input-req-inner"></span>
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
                    <select name="mfqSubSubCategoryID" class="form-control" id="frm_subSubCategory">
                        <option value=""></option>
                    </select>
                    <!-- <span class="input-req" title="Required Field">

                         <span class="input-req-inner"></span>
                     </span>-->

                </div>
            </div>


        </div>
    </div>

    <div class="col-md-12 animated zoomIn">
        <div class="row" style="margin-top: 10px;">
            <div class="col-sm-7">
                <div class="pull-right">
                    <button class="btn btn-primary" type="submit" id="submitItemBtn"><i class="fa fa-plus"></i> Add Item
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
            fetchPage('system/mfq/mfq_item_master', '', 'Item Master');
        });

        $("#from_add_edit_crew").submit(function (e) {
            addEditItem();
            return false;
        });
        loadItemDetail();
    });

    function load_sub_cat(select_val) {
        $('#subcategoryID').val("");
        $('#subcategoryID option').remove();
        $('#subSubCategoryID').val("");
        $('#subSubCategoryID option').remove();
        var subid = $('#mainCategoryID').val();
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("ItemMaster/load_subcat"); ?>',
            dataType: 'json',
            data: {'subid': subid},
            async: false,
            success: function (data) {
                if (!jQuery.isEmptyObject(data)) {
                    $('#subcategoryID').empty();
                    var mySelect = $('#subcategoryID');
                    mySelect.append($('<option></option>').val('').html('Select Option'));
                    $.each(data, function (val, text) {
                        mySelect.append($('<option></option>').val(text['itemCategoryID']).html(text['description']));
                    });
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
        });
    }

    function load_sub_sub_cat() {
        $('#subSubCategoryID option').remove();
        $('#subSubCategoryID').val("");
        var subsubid = $('#subcategoryID').val();
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("ItemMaster/load_subsubcat"); ?>',
            dataType: 'json',
            data: {'subsubid': subsubid},
            async: false,
            success: function (data) {
                if (!jQuery.isEmptyObject(data)) {
                    $('#subSubCategoryID').empty();
                    var mySelect = $('#subSubCategoryID');
                    mySelect.append($('<option></option>').val('').html('Select Option'));
                    $.each(data, function (val, text) {
                        mySelect.append($('<option></option>').val(text['itemCategoryID']).html(text['description']));
                    });
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
        });
    }


    function addEditItem() {
        var data = $("#from_add_edit_crew").serializeArray();
        $('select[name="defaultUnitOfMeasureID"] option:selected').each(function () {
            data.push({'name': 'defaultUnitOfMeasure', 'value': $(this).text()})
        });
        data.push({'name': 'mainCategory', 'value': $('#mainCategoryID option:selected').text()});
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("MFQ_ItemMaster/add_edit_mfq_item"); ?>',
            dataType: 'json',
            data: data,
            async: false,
            success: function (data) {
                if (data['error'] == 1) {
                    myAlert('e', data['message']);
                }
                else if (data['error'] == 0) {
                    if (data['code'] == 1) {
                        $("#from_add_edit_crew")[0].reset();
                        $("#mainCategoryID").val('').change();
                        $("#subcategoryID").val('').change();
                        $("#subSubCategoryID").val('').change();
                        $("#defaultUnitOfMeasureID").val('').change();
                        $("#unbilledServicesGLAutoID").val('').change();
                    }
                    myAlert('s', data['message']);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                myAlert('e', xhr.responseText);
            }
        });
    }

    function loadItemDetail() {
        var mfqItemID = '<?php echo $mfqItemID ?>';
        if (mfqItemID > 0) {
            $.ajax({
                type: 'POST',
                url: '<?php echo site_url("MFQ_ItemMaster/load_mfq_itemMaster"); ?>',
                dataType: 'json',
                data: {mfqItemID: mfqItemID},
                async: false,
                success: function (data) {
                    if (data['error'] == 0) {
                        //myAlert('s', data['message']);
                        $("#submitItemBtn").html('<i class="fa fa-pencil"></i> Save Item');

                        $("#itemName").val(data['itemName']);
                        $("#secondaryItemCode").val(data['secondaryItemCode']);
                        $("#itemType").val(data['itemType']);
                        $("#mfqCategoryID").val(data['mfqCategoryID']);
                        $("#mfqCategoryID").change();
                        $("#mainCategoryID").val(data['mainCategoryID']);
                        $("#mainCategoryID").change();
                        $("#subcategoryID").val(data['subcategoryID']);
                        $("#subcategoryID").change();
                        $("#subSubCategoryID").val(data['subSubCategoryID']);
                        $("#subSubCategoryID").change();
                        $("#tmp_mainSubCatID").val(data['mfqSubCategoryID']);
                        $("#tmp_mainSubSubCatID").val(data['mfqSubSubCategoryID']);
                        $("#defaultUnitOfMeasureID").val(data['defaultUnitOfMeasureID']);
                        $("#defaultUnitOfMeasureID").change();
                        $('#unbilledServicesGLAutoID').val(data['unbilledServicesGLAutoID']);
                        $("#unbilledServicesGLAutoID").change();
                        if (data['categoryTypeID'] == 2) {
                            $('.unbilledservice').removeClass('hide');
                        } else {
                            $('.unbilledservice').addClass('hide');
                        }

                        /*$("#designation").val(data['designation']);
                         $("#EEmail").val(data['EEmail']);
                         $("#EpTelephone").val(data['EpTelephone']);
                         $("#EpTelephone").val(data['EpTelephone']);*/
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    myAlert('e', xhr.responseText);
                }
            });
        }
    }
    $("#mainCategoryID").change(function () {
        unbilledServices(this.value)
    });


    function unbilledServices(mainCategoryID) {
        if(mainCategoryID)
        {
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: {mainCategoryID: mainCategoryID},
                url: "<?php echo site_url('MFQ_ItemMaster/hideshownoninventory'); ?>",
                beforeSend: function () {

                    startLoad();

                },
                success: function (data) {
                    stopLoad();
                    if (!jQuery.isEmptyObject(data)) {
                        if (data['categoryTypeID']== 2) {
                            $('.unbilledservice').removeClass('hide')
                        }else {
                            $('.unbilledservice').addClass('hide')
                        }
                    }
                },
                error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        }

    }

    $('.select2').select2();


</script>