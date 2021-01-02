<?php echo head_page('Item Category', false);
$revenue_gl = all_revenue_gl_drop();
$cost_gl = all_cost_gl_drop();
$asset_gl = all_asset_gl_drop();

$fetch_cost_account = fetch_cost_account();
$fetch_dep_gl_code = fetch_gl_code(array('masterCategory' => 'PL', 'subCategory' => 'PLE'));
$fetch_disposal_gl_code = fetch_gl_code(array('masterCategory' => 'PL'));
// $pl_arr     = fetch_gl_codes('PL');
// $bl_arr     = fetch_gl_codes('BS');
$pcid = 1;
$userid = current_userID();
$company = current_companyCode();
$username = current_user();
$currentdate = current_date();

$categoryID = $this->input->post('page_id');
$LoadCategory = $this->db->query("SELECT itemCategoryID,categoryTypeID FROM `srp_erp_itemcategory` WHERE `itemCategoryID` = '$categoryID'")->row_array();

?>
<div id="filter-panel" class="collapse filter-panel"></div>
<style>
    .form1 {
        width: 250px !important;
    }

    .btn-primary {
        background-color: #34495e;
        border-color: #34495e;
        color: #FFFFFF;
    }

    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
        padding: 5px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #ddd;
    }

    .header {
        color: #000080;
        font-weight: bolder;
        font-size: 13px;
        background-color: #E8F1F4;
    }
</style>
<?php echo form_open('', 'role="form" class="form-group" id="subcategory_form"') ?>
<input type="hidden" class="form-control" id="subcatregoryedit" name="subcatregoryedit">
<div class="row">
    <input type="hidden" class="form-control" id="createdpcid" name="createdpcid" value="<?php echo $pcid; ?>">
    <input type="hidden" class="form-control" id="createduserid" name="createduserid" value="<?php echo $userid; ?>">
    <input type="hidden" class="form-control" id="schoolid" name="schoolid" value="<?php echo $company; ?>">
    <input type="hidden" class="form-control" id="createdusername" name="createdusername"
           value="<?php echo $username; ?>">
    <input type="hidden" class="form-control" id="createddate" name="createddate" value="<?php echo $currentdate; ?>">
    <?php if ($LoadCategory['categoryTypeID'] != 3) { ?>
        <div class="col-sm-3">
            <div class="form-group">
                <label for=""> Sub Category </label>
                <input type="text" class="form-control " id="subcategory" name="subcategory">
            </div>
        </div>
        <!--<div class="col-sm-3">
            <div class="form-group">
                <label for="">Revenue GL Code</label>
                <?php /*echo form_dropdown('revnugl', $revenue_gl, '', 'class="form-control form1 select2" id="revnugl" '); */?>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label for="">Cost GL Code</label>
                <?php /*echo form_dropdown('costgl', $cost_gl, '', 'class="form-control form1 select2" id="costgl" required'); */?>
            </div>
        </div>
        <?php /*if ($LoadCategory['categoryTypeID'] != 2) { */?>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="">Asset GL Code</label>
                    <?php /*echo form_dropdown('assetgl', $asset_gl, '', 'class="form-control form1 select2" id="assetgl" required'); */?>
                </div>
            </div>
        --><?php /*}*/
    } else { ?>
        <div class="col-sm-3">
            <div class="form-group">
                <label for=""> Sub Category <?php required_mark(); ?></label>
                <input type="text" class="form-control  " id="subcategory" name="subcategory" required>
            </div>
        </div>
        <!--<div class="col-md-3">
            <div class="form-group">
                <label for="">Cost Account <?php /*required_mark(); */?></label>
                <?php /*echo form_dropdown('COSTGLCODEdes', $fetch_cost_account, '', 'class="form-control form1 select2" id = "COSTGLCODEdes" required'); */?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Acc Dep GL Code <?php /*required_mark(); */?></label>
                <?php /*echo form_dropdown('ACCDEPGLCODEdes', $fetch_cost_account, '', 'class="form-control form1 select2" id = "ACCDEPGLCODEdes" required'); */?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Dep GL Code <?php /*required_mark(); */?></label>
                <?php /*echo form_dropdown('DEPGLCODEdes', $fetch_dep_gl_code, '', 'class="form-control form1 select2" id = "DEPGLCODEdes" required'); */?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Disposal GL Code <?php /*required_mark(); */?></label>
                <?php /*echo form_dropdown('DISPOGLCODEdes', $fetch_disposal_gl_code, '', 'class="form-control form1 select2" id = "DISPOGLCODEdes" required'); */?>
            </div>
        </div>-->
    <?php } ?>
</div>
<hr>
<div class="text-right m-t-xs">
    <button type="submit" class="btn btn-sm btn-primary">Add <span class="glyphicon glyphicon-floppy-disk"
                                                                   aria-hidden="true"></span></button>
    <a onclick="fetchPage('system/GroupItemCategory/srp_group_itemcategory_view','','Item Category');">
        <button type="button" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-arrow-left"
                                                                   aria-hidden="true"></span> Back
        </button>
    </a>
</div>

</form>
<br>
<!--<div class="table-responsive">
    <table id="subcategory_table" class="<?php /*echo table_class() */ ?>">
        <thead>
        <tr>
            <th style="min-width: 5%">#</th>
            <th>Description</th>
            <th style="min-width:13%">Sub Category</th>
            <th style="width:20px"></th>
        </tr>
        </thead>
    </table>
</div>-->
<div id="subcategory_tableDiv">
    <!--Sub Category Table-->
</div>
<?php echo footer_page('Right foot', 'Left foot', false); ?>
<div class="modal fade" id="subsubcategory_model" role="dialog">
    <div class="modal-dialog" style="width:40%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Sub Sub Category</h4>
            </div>
            <?php echo form_open('', 'role="form" class="form-horizontal" id="subsubcategory_form"') ?>
            <div class="modal-body">
                <input type="hidden" class="form-control" id="subsubcategoryedit" name="subsubcategoryedit">
                <input type="hidden" class="form-control" id="subsubedit" name="subsubedit">

                <input type="hidden" class="form-control" id="rvgl" name="rvgl">
                <input type="hidden" class="form-control" id="cstgl" name="cstgl">
                <input type="hidden" class="form-control" id="astgl" name="astgl">

                <div class="row" style="margin-left:0px !important;">
                    <div class="form-group col-sm-4">
                        <label for="">Sub Sub category</label>
                        <input type="text" class="form-control form1" id="subsubcategory" name="subsubcategory">
                    </div>

                </div>
                <div class="row" style="margin-left:0px !important;">
                    <div class="form-group col-sm-4">
                        <button type="submit" class="btn btn-sm btn-primary">Add <span
                                class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></button>
                    </div>
                </div>
                </form>

                <!--<div class="table-responsive">
                    <table id="subsubcategory_table" class="<?php /*echo table_class() */ ?>">
                        <thead>
                        <tr>
                            <th style="min-width: 5%">#</th>
                            <th>Description</th>
                            <th style="min-width: 5%">Edit</th>
                        </tr>
                        </thead>
                    </table>
                </div>-->

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="itemsubcategoryedit_model" role="dialog">
    <div class="modal-dialog" style="width:70%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Item Sub Category Edit</h4>
            </div>
            <?php echo form_open('', 'role="form" class="form-group" id="itemsubcategoryedit_form"') ?>
            <div class="modal-body">
                <input type="hidden" class="form-control" id="subcatregoryeditfrm" name="subcatregoryeditfrm"
                       value="<?php if (isset($_POST["page_id"])) echo $_POST["page_id"]; ?>">

                <div class="row" style="margin-left:0px !important;">
                    <div class="form-group col-sm-4">
                        <label for="">Sub category</label>
                        <input type="text" class="form-control form1" id="description" name="description">
                    </div>
                </div>
                <!--<div class="row" style="margin-left:0px !important;">
                    <div id="inventry_row_div">
                        <div class="form-group col-sm-4">
                            <label for="">Revenue GL Code</label>
                            <?php /*echo form_dropdown('revnugledit', $revenue_gl, '', 'class="form-control form1 select2" id="revnugledit" '); */?>
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="">Cost GL Code</label>
                            <?php /*echo form_dropdown('costgledit', $cost_gl, '', 'class="form-control form1 select2" id="costgledit" '); */?>
                        </div>
                    </div>
                    <div id="assetGlCode_div">
                        <div class="form-group col-sm-4">
                            <label for="">Asset GL Code</label>
                            <?php /*echo form_dropdown('assetgledit', $asset_gl, '', 'class="form-control form1 select2" id="assetgledit" '); */?>
                        </div>
                    </div>
                </div>-->
                <!--<div class="row hide" id="fixed_row_div" style="margin-left:0px !important;">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Cost Account <?php /*required_mark(); */?></label>
                            <?php /*echo form_dropdown('COSTGLCODEdes_edit', $fetch_cost_account, '', 'class="form-control form1 select2" id = "COSTGLCODEdes_edit"'); */?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Acc Dep GL Code <?php /*required_mark(); */?></label>
                            <?php /*echo form_dropdown('ACCDEPGLCODEdes_edit', $fetch_cost_account, '', 'class="form-control form1 select2" id = "ACCDEPGLCODEdes_edit"'); */?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Dep GL Code <?php /*required_mark(); */?></label>
                            <?php /*echo form_dropdown('DEPGLCODEdes_edit', $fetch_dep_gl_code, '', 'class="form-control form1 select2" id = "DEPGLCODEdes_edit" '); */?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Disposal GL Code <?php /*required_mark(); */?></label>
                            <?php /*echo form_dropdown('DISPOGLCODEdes_edit', $fetch_disposal_gl_code, '', 'class="form-control form1 select2" id = "DISPOGLCODEdes_edit"'); */?>
                        </div>
                    </div>
                </div>-->
            </div>
            <div class="modal-footer">
                <button type="submit" id="subcategoryeditsave" class="btn btn-sm btn-primary">Save <span
                        class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></button>
                <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="itemsubsubcategoryedit_model" role="dialog">
    <div class="modal-dialog" style="width:40%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Item Sub Sub Category Edit</h4>
            </div>
            <?php echo form_open('', 'role="form" class="form-horizontal" id="itemsubsubcategoryedit_form"') ?>
            <div class="modal-body">
                <input type="hidden" class="form-control" id="subsubcatregoryeditfrm" name="subsubcatregoryeditfrm"
                       value="<?php if (isset($_POST["page_id"])) echo $_POST["page_id"]; ?>">

                <div class="row" style="margin-left:0px !important;">
                    <div class="form-group col-sm-4">
                        <label for="">Sub Sub category</label>
                        <input type="text" class="form-control form1" id="descriptionsubsub" name="descriptionsubsub">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="subsubcategoryeditsave" class="btn btn-sm btn-primary">Save <span
                        class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></button>
                <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="subitemCategoryLinkModal" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width: 60%">
        <div class="modal-content" id="">
            <?php echo form_open('', 'role="form" id="sub_item_category_link_form"'); ?>
            <input type="hidden" name="subitemCategoryIDhn" id="subitemCategoryIDhn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Item Category Link <span id=""></span></h4>
            </div>
            <div class="modal-body" style="margin-left: 10px">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="customerName" ><h4><span id="sublinkhead">Sub</span> Item Category :- </h4></label>
                        <label style="color: cornflowerblue;font-size: 1.2em;" id="categoryName"></label>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row" id="loadComapnyCategories">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default btn-sm" type="button">Close</button>
                <button type="submit" class="btn btn-primary btn-sm" id="btnSave">Add Link
                </button>
            </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    var pagename;
    $(document).ready(function () {
        $('.headerclose').click(function(){
            fetchPage('system/GroupItemCategory/srp_group_itemcategory_view','','Item Category');
        });
        $('.select2').select2();
        pagename = '<?php echo $_POST["page_name"]; ?>';
        subcategoryview();
        /*        subcategory_view_master();*/
        $('#subcategory_form').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {},
        }).on('success.form.bv', function (e) {
            masterid = <?php echo $this->input->post('page_id') ?>;
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            data.push({'name': 'master', 'value': masterid});
            data.push({'name': 'pagename', 'value': pagename});
            $.ajax(
                {
                    async: false,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    url: "<?php echo site_url('ItemCategoryGroup/save_subcategory'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        HoldOn.close();
                        refreshNotifications(true);
                        if (data) {
                            subcategoryview();
                            $('#revnugl').val('').change();
                            $('#costgl').val('').change();
                            document.getElementById('subcategory_form').reset();
                            $('#subcategory_form').bootstrapValidator('resetForm', true);
                        } else {
                            $('#subcategory_form').bootstrapValidator('resetForm', true);
                        }
                    }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    HoldOn.close();
                    refreshNotifications(true);
                }
                });
        });


        $('#subsubcategory_form').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                subsubcategory: {validators: {notEmpty: {message: 'Sub Sub Category is required.'}}}
            },
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            data.push({"name": "subsubcategoryedit", "value": $('#subsubcategoryedit').val()});
            $.ajax(
                {
                    async: false,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    url: "<?php echo site_url('ItemCategoryGroup/save_subsubcategory'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        //fetchPage('system/srp_mu_itemcategory_view','Test','Item Category');
                        refreshNotifications(true);
                        $("#subsubcategory_model").modal("hide");
                        subcategoryview();
                    }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    HoldOn.close();
                    refreshNotifications(true);
                }
                });
        });

        $('#itemsubcategoryedit_form').bootstrapValidator({
            live            : 'enabled',
            message         : 'This value is not valid.',
            excluded        : [':disabled'],
            fields          : {
                description          : {validators : {notEmpty:{message:'description is required.'}}},

            },
        }).on('success.form.bv', function(e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();

            $.ajax({
                async : true,
                type : 'post',
                dataType : 'json',
                data : data,
                url :"<?php echo site_url('ItemCategoryGroup/update_subcategory'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success : function(data){
                    stopLoad();
                    //fetchPage('system/srp_mu_itemcategory_view','Test','Item Category');
                    refreshNotifications(true);
                    $("#itemsubcategoryedit_model").modal("hide");
                    subcategoryview();
                    $('#subcategoryeditsave').attr('disabled', false)
                },error : function(){
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        });




        $('#itemsubsubcategoryedit_form').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {},
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();

            $.ajax(
                {
                    async: false,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    url: "<?php echo site_url('ItemCategoryGroup/update_subsubcategory'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        //fetchPage('system/srp_mu_itemcategory_view','Test','Item Category');
                        myAlert(data[0],data[1]);
                        $('#subsubcategoryeditsave').attr('disabled', false)
                        if(data[0]=='s'){
                            $("#itemsubsubcategoryedit_model").modal("hide");
                            $("#subsubcategory_model").modal("hide");
                            subcategoryview();
                        }
                    }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    HoldOn.close();
                    refreshNotifications(true);
                }
                });
        });


        $('#sub_item_category_link_form').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                //companyID: {validators: {notEmpty: {message: 'Company is required.'}}}
            },
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            $.ajax(
                {
                    async: false,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    url: "<?php echo site_url('ItemCategoryGroup/save_sub_item_category_link'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        HoldOn.close();
                        myAlert(data[0], data[1]);
                        $('#btnSave').attr('disabled', false);
                        if (data[0] == 's') {
                            /*load_item_category_details_table();
                             load_company($('#itemCategoryIDhn').val());
                             $('#companyID').val('').change();*/
                            $('#subitemCategoryLinkModal').modal('hide');
                            load_all_companies_subcategory();
                        }

                    },
                    error: function () {
                        alert('An Error Occurred! Please Try Again.');
                        HoldOn.close();
                        refreshNotifications(true);
                    }
                });
        });


    });

    function subcategoryview() {
        $.ajax(
            {
                async: false,
                type: 'post',
                dataType: 'html',
                data: {idedit: <?php echo $this->input->post('page_id'); ?>},
                url: "<?php echo site_url('ItemCategoryGroup/load_subcategoryMaster'); ?>",
                beforeSend: function () {

                },
                success: function (data) {
                    $('#subcategory_tableDiv').html(data);
                    $("[rel=tooltip]").tooltip();

                }, error: function () {
                alert('An Error Occurred! Please Try Again.');

            }
            });
    }

    function subcategoryviewMaster() {
        editid = <?php echo $this->input->post('page_id'); ?>;
        var Otable = $('#subcategory_table').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "sAjaxSource": "<?php echo site_url('Sub_category/load_subcategory'); ?>",
            //"bJQueryUI": true,
            //"iDisplayStart ": 8,
            //"sEcho": 1,
            ///"sAjaxDataProp": "aaData",
            "aaSorting": [[0, 'desc']],
            "fnDrawCallback": function () {
                $("[rel=tooltip]").tooltip();
            },
            "fnDrawCallback": function (oSettings) {
                if (oSettings.bSorted || oSettings.bFiltered) {
                    for (var i = 0, iLen = oSettings.aiDisplay.length; i < iLen; i++) {
                        $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html(i + 1);
                    }
                }
            },
            "aoColumns": [
                {"mData": "itemCategoryID"},
                {"mData": "description"},
                {"mData": "addsubsub"},
                {"mData": "action"}
            ],
            "columnDefs": [{
                "targets": [2, 3],
                "orderable": false
            }],
            "fnServerData": function (sSource, aoData, fnCallback) {
                aoData.push({"name": "idedit", "value": editid});
                $.ajax
                ({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            }
        });
    }

    function subsubcategory(id) {
        $("#subsubcategory_model").modal("show");
        $('#subsubcategoryedit').val(id);
        //$('#rvgl').val(revanuegl);
        //$('#cstgl').val(costgl);
        //$('#astgl').val(assetgl);
        //subsubcategoryview();

    }
    function resetform() {
        document.getElementById('subsubcategory_form').reset();
        //subcategoryview();
    }

    /*$("#subsubcategory_model").on("hidden.bs.modal", function () {
     subcategoryview();

     });*/

    function subsubcategoryview() {
        var Otable = $('#subsubcategory_table').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "sAjaxSource": "<?php echo site_url('Sub_category/load_subsubcategory'); ?>",
            //"bJQueryUI": true,
            //"iDisplayStart ": 8,
            //"sEcho": 1,
            ///"sAjaxDataProp": "aaData",
            "aaSorting": [[0, 'desc']],
            "fnDrawCallback": function () {
                $("[rel=tooltip]").tooltip();
            },
            "fnDrawCallback": function (oSettings) {
                if (oSettings.bSorted || oSettings.bFiltered) {
                    for (var i = 0, iLen = oSettings.aiDisplay.length; i < iLen; i++) {
                        $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html(i + 1);
                    }
                }
            },
            "aoColumns": [
                {"mData": "itemCategoryID"},
                {"mData": "description"},
                {"mData": "action"}
            ],
            "columnDefs": [{
                "targets": [2],
                "orderable": false
            }],
            "fnServerData": function (sSource, aoData, fnCallback) {
                aoData.push({"name": "subsubcategoryedit", "value": $('#subsubcategoryedit').val()});
                $.ajax
                ({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            }
        });
    }


    function opensubcategoryedit(id) {
        changeFormCode();
        $("#itemsubcategoryedit_model").modal("show");
        $('#subcatregoryedit').val(id);
        $('#subcatregoryeditfrm').val(id);
        $.ajax(
            {
                async: false,
                type: 'post',
                dataType: 'json',
                data: {id: id},
                url: "<?php echo site_url('ItemCategoryGroup/edit_itemsubcategory'); ?>",
                beforeSend: function () {

                },
                success: function (data) {
                    $('#description').val(data['description']);
                    $('#revnugledit').val(data['revenueGL']).change();
                    $('#costgledit').val(data['costGL']).change();
                    $('#assetgledit').val(data['assetGL']).change();
                    $('#COSTGLCODEdes_edit').val(data['faCostGLAutoID']).change();
                    $('#ACCDEPGLCODEdes_edit').val(data['faACCDEPGLAutoID']).change();
                    $('#DEPGLCODEdes_edit').val(data['faDEPGLAutoID']).change();
                    $('#DISPOGLCODEdes_edit').val(data['faDISPOGLAutoID']).change();

                }, error: function () {
                alert('An Error Occurred! Please Try Again.');

            }
            });
    }

    function opensubsubcategoryedit(id) {
        $("#itemsubsubcategoryedit_model").modal("show");
        $('#subsubedit').val(id);
        $('#subsubcatregoryeditfrm').val(id);
        $.ajax(
            {
                async: false,
                type: 'post',
                dataType: 'json',
                data: {id: id},
                url: "<?php echo site_url('ItemCategoryGroup/edit_itemsubsubcategory'); ?>",
                beforeSend: function () {

                },
                success: function (data) {

                    $('#descriptionsubsub').val(data['description']);

                }, error: function () {
                alert('An Error Occurred! Please Try Again.');

            }
            });
    }

    function reset_form() {
        document.getElementById('subcategory_form').reset();
    }
    function reset_form1() {
        document.getElementById('subsubcategory_form').reset();
    }


    function changeFormCode() {
        itemCategoryID = <?php echo $categoryID ?>;
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("ItemMaster/load_category_type_id"); ?>',
            dataType: 'json',
            data: {'itemCategoryID': itemCategoryID},
            async: false,
            success: function (data) {
                if (!jQuery.isEmptyObject(data)) {
                    if (data['categoryTypeID'] == 1) {
                        $("#inventry_row_div").removeClass("hide");
                        $("#assetGlCode_div").removeClass("hide");
                        $("#fixed_row_div").addClass("hide");
                    }
                    if (data['categoryTypeID'] == 2) {
                        $("#assetGlCode_div").addClass("hide");
                    }
                    if (data['categoryTypeID'] == 3) {
                        $("#inventry_row_div").addClass("hide");
                        $("#assetGlCode_div").addClass("hide");
                        $("#fixed_row_div").removeClass("hide");
                    }
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
        });

    }

    function link_group_sub_itemcategory(itemCategoryID) {
        $('#subitemCategoryLinkModal').modal({backdrop: "static"});
        //$('#companyID').val('').change();
        $('#subitemCategoryIDhn').val(itemCategoryID);
        $('#btnSave').attr('disabled', false);
        $('#sublinkhead').html('Sub');
        load_all_companies_subcategory();
        load_category_header();
    }

    function load_all_companies_subcategory() {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {groupItemCategoryID: $('#subitemCategoryIDhn').val()},
            url: "<?php echo site_url('ItemCategoryGroup/load_all_companies_item_subcategories'); ?>",
            beforeSend: function () {

            },
            success: function (data) {
                $('#loadComapnyCategories').removeClass('hidden');
                $('#loadComapnyCategories').html(data);
                $('.select2').select2();
            }, error: function () {

            }
        });
    }

    function clearcategory(id){
        $('#itemCategoryID_'+id).val('').change();
    }

    function load_category_header() {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'groupItemCategoryID': $('#subitemCategoryIDhn').val()},
            url: "<?php echo site_url('ItemCategoryGroup/load_category_header'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                if (!jQuery.isEmptyObject(data)) {
                    $('#categoryName').html(data['description']);
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

    function link_group_sub_sub_itemcategory(itemCategoryID) {
        $('#subitemCategoryLinkModal').modal({backdrop: "static"});
        //$('#companyID').val('').change();
        $('#subitemCategoryIDhn').val(itemCategoryID);
        $('#btnSave').attr('disabled', false);
        $('#sublinkhead').html('Sub Sub');
        load_all_companies_subcategory();
        load_category_header();
    }




</script>
