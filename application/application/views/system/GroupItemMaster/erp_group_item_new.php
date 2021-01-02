<?php echo head_page($_POST['page_name'], false);
$group_main_category_arr = all_group_main_category_drop();
$revenue_gl_arr = all_revenue_gl_drop();
$cost_gl_arr = all_cost_gl_drop();
$asset_gl_arr = all_asset_gl_drop();
$uom_arr = all_group_umo_new_drop();

$fetch_cost_account = fetch_cost_account();
$fetch_dep_gl_code = fetch_gl_code(array('masterCategory' => 'PL', 'subCategory' => 'PLE'));
$fetch_disposal_gl_code = fetch_gl_code(array('masterCategory' => 'PL'));
?>
<style>
    @font-face {
        font-family: barCodeFont;
        src: url(<?php echo base_url('font/fre3of9x.ttf') ?>);
    }

</style>
<div id="filter-panel" class="collapse filter-panel"></div>
<div class="m-b-md" id="wizardControl">
    <a class="btn btn-primary" href="#step1" data-toggle="tab">Step 1 - Item Header</a>
    <!--<a class="btn btn-default btn-wizard" href="#step2" data-toggle="tab">Step 2 - Item Attachments</a>-->
</div>
<hr>
<div class="tab-content">
    <div id="step1" class="tab-pane active">
        <?php echo form_open('', 'role="form" id="itemmaster_form"'); ?>
        <div class="row modal-body" style="padding-bottom: 0px;">
            <div class="row">
                <div class="form-group col-sm-4">
                    <label>Main Category <?php required_mark(); ?></label>
                    <?php echo form_dropdown('mainCategoryID', $group_main_category_arr, 'Each', 'class="form-control" id="mainCategoryID" onchange="load_sub_cat()"'); ?>
                </div>
                <div class="form-group col-sm-4">
                    <label>Category <?php required_mark(); ?></label>
                    <select name="subcategoryID" id="subcategoryID" class="form-control searchbox"
                            onchange="load_sub_sub_cat()">
                        <option value="">Select Category</option>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label>Sub Category </label>
                    <select name="subSubCategoryID" id="subSubCategoryID" class="form-control searchbox">
                        <option value="">Select Category</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-4">
                    <label>Short Description <?php required_mark(); ?></label>
                    <input type="text" class="form-control" id="itemName" name="itemName">
                </div>
                <div class="form-group col-sm-8">
                    <label>Long Description <?php required_mark(); ?></label>
                    <input type="text" class="form-control" id="itemDescription" name="itemDescription">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-4">
                    <label for="">Secondary Code <?php required_mark(); ?></label>
                    <input type="text" class="form-control" id="seconeryItemCode" name="seconeryItemCode">
                </div>
                <div class="form-group col-sm-4">
                    <label for="">Unit of Measure <?php required_mark(); ?></label>
                    <?php echo form_dropdown('defaultUnitOfMeasureID', $uom_arr, 'Each', 'class="form-control" id="defaultUnitOfMeasureID" required'); ?>
                </div>
                <div class="form-group col-sm-4">
                    <label for="">Selling Price <?php required_mark(); ?></label>

                    <div class="input-group">
                        <div
                            class="input-group-addon"><?php echo $this->common_data['company_data']['company_default_currency']; ?></div>
                        <input type="text" step="any" class="form-control number" id="companyLocalSellingPrice"
                               name="companyLocalSellingPrice" value="0">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">isActive</label>

                    <div class="skin skin-square">
                        <div class="skin-section" id="extraColumns">
                            <input id="checkbox_isActive" type="checkbox"
                                   data-caption="" class="columnSelected" name="isActive" value="1" checked>
                            <label for="checkbox">
                                &nbsp;
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="text-right m-t-xs">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
        </form>
    </div>
</div>
<?php echo footer_page('Right foot', 'Left foot', false); ?>
<script type="text/javascript">
    var itemAutoID;
    $(document).ready(function () {
        $('.headerclose').click(function () {
            fetchPage('system/GroupItemMaster/erp_item_master', 'Test', 'Item Master');
        });
        $('.select2').select2();
        itemAutoID = null;
        number_validation();
        load_sub_cat();

        $('#extraColumns input').iCheck({
            checkboxClass: 'icheckbox_square_relative-blue',
            radioClass: 'iradio_square_relative-blue',
            increaseArea: '20%'
        });
        p_id = <?php echo json_encode(trim($this->input->post('page_id'))); ?>;
        if (p_id) {
            itemAutoID = p_id;
            load_item_header();
            //changeFormCode();
            $('.btn-wizard').removeClass('disabled');
        } else {
            $('.btn-wizard').addClass('disabled');
        }
        $('#itemmaster_form').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                seconeryItemCode: {validators: {notEmpty: {message: 'Item Code is required.'}}},
                itemName: {validators: {notEmpty: {message: 'Item Name is required.'}}},
                itemDescription: {validators: {notEmpty: {message: 'Item Description is required.'}}},
                mainCategoryID: {validators: {notEmpty: {message: 'Main category is required.'}}},
                subcategoryID: {validators: {notEmpty: {message: 'Sub category is required.'}}},
                defaultUnitOfMeasureID: {validators: {notEmpty: {message: 'Unit of measure is required.'}}},
            },
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            data.push({'name': 'itemAutoID', 'value': itemAutoID});
            data.push({'name': 'mainCategory', 'value': $('#mainCategoryID option:selected').text()});
            data.push({'name': 'uom', 'value': $('#defaultUnitOfMeasureID option:selected').text()});
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('GroupItemMaster/save_itemmaster'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    refreshNotifications(true);
                    if (data) {
                        fetchPage('system/GroupItemMaster/erp_item_master', 'Test', 'Item Master');
                    }
                }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $('a[data-toggle="tab"]').removeClass('btn-primary');
            $('a[data-toggle="tab"]').addClass('btn-default');
            $(this).removeClass('btn-default');
            $(this).addClass('btn-primary');
        });

        $('.next').click(function () {
            var nextId = $(this).parents('.tab-pane').next().attr("id");
            $('[href=#' + nextId + ']').tab('show');
        });

        $('.prev').click(function () {
            var prevId = $(this).parents('.tab-pane').prev().attr("id");
            $('[href=#' + prevId + ']').tab('show');
        });
    });

    function load_item_header() {
        if (itemAutoID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {'itemAutoID': itemAutoID},
                url: "<?php echo site_url('GroupItemMaster/load_item_header'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    if (!jQuery.isEmptyObject(data)) {

                        $('#itemName').val(data['itemName']);
                        //$('#edit_systemCode').text(data['itemSystemCode']);
                        //$('#edit_shortDescription').text(data['itemName']);
                        $('#itemDescription').val(data['itemDescription']);
                        $('#mainCategoryID').val(data['mainCategoryID']);
                        $('#mainCategoryID option:not(:selected)').prop('disabled', true);

                        $('#defaultUnitOfMeasureID').val(data['defaultUnitOfMeasureID']);
                        $('#defaultUnitOfMeasureID option:not(:selected)').prop('disabled', true);
                        $('#companyLocalSellingPrice').val(data['companyLocalSellingPrice']);
                        $('#seconeryItemCode').val(data['secondaryItemCode']);
                        load_sub_cat(data['subcategoryID']);
                        $('#subcategoryID').val(data['subcategoryID']);
                        load_sub_sub_cat(data['subSubCategoryID']);
                        $('#subSubCategoryID').val(data['subSubCategoryID']);
                        $('#subcategoryID option:not(:selected)').prop('disabled', true);
                        if (data['isActive'] == 1) {
                            $('#checkbox_isActive').iCheck('check');
                        } else {
                            $('#checkbox_isActive').iCheck('uncheck');
                        }
                    }
                    stopLoad();
                    refreshNotifications(true);
                }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        }
    }

    function load_sub_cat(select_val) {
        //changeFormCode();
        $('#subcategoryID').val("");
        $('#subcategoryID option').remove();
        $('#subSubCategoryID').val("");
        $('#subSubCategoryID option').remove();
        var subid = $('#mainCategoryID').val();
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("GroupItemMaster/load_subcat"); ?>',
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
            url: '<?php echo site_url("GroupItemMaster/load_subsubcat"); ?>',
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

    /*function changeFormCode() {
        itemCategoryID = $('#mainCategoryID').val();
        $.ajax({
            type: 'POST',
            url: '<?php //echo site_url("ItemMaster/load_category_type_id"); ?>',
            dataType: 'json',
            data: {'itemCategoryID': itemCategoryID},
            async: false,
            success: function (data) {
                if (!jQuery.isEmptyObject(data)) {
                    if ((data['categoryTypeID'] == 2) || (data['categoryTypeID'] == 4)) {
                        $("#assetGlCode_div").addClass("hide");
                        $("#cls_maximunQty").addClass("hide");
                        $("#cls_minimumQty").addClass("hide");
                        $("#cls_reorderPoint").addClass("hide");
                    } else {
                        $("#assetGlCode_div").removeClass("hide");
                        $("#cls_maximunQty").removeClass("hide");
                        $("#cls_minimumQty").removeClass("hide");
                        $("#cls_reorderPoint").removeClass("hide");
                    }
                    if (data['categoryTypeID'] == 3) {
                        $("#inventry_row_div").addClass("hide");
                        $("#fixed_row_div").removeClass("hide");
                    } else {
                        $("#inventry_row_div").removeClass("hide");
                        $("#fixed_row_div").addClass("hide");
                    }
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
        });
    }*/

    /*$('#changeImg').click(function () {
        $('#itemImage').click();
    });*/

    /*$('#empImage').change(function(){

     });*/

    /*function loadImage(obj) {
        if (obj.files && obj.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#changeImg').attr('src', e.target.result);
            };

            reader.readAsDataURL(obj.files[0]);
        }
    }*/
</script>