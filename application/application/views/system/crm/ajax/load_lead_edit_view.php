<?php
$currency_arr = crm_all_currency_new_drop();
$product_arr = all_crm_product_master();
$admin = crm_isSuperAdmin();
?>
<style>
    .search-no-results {
        text-align: center;
        background-color: #f6f6f6;
        border: solid 1px #ddd;
        margin-top: 10px;
        padding: 1px;
    }

    .entity-detail .ralign, .property-table .ralign {
        text-align: right;
        color: gray;
        padding: 3px 10px 4px 0;
        width: 150px;
        max-width: 200px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .title {
        color: #aaa;
        padding: 4px 10px 0 0;
        font-size: 13px;
    }

    .tddata {
        color: #333;
        padding: 4px 10px 0 0;
        font-size: 13px;
    }

    .nav-tabs > li > a {
        font-size: 11px;
        line-height: 30px;
        height: 30px;
        position: relative;
        padding: 0 25px;
        float: left;
        display: block;
        /*color: rgb(44, 83, 158);*/
        letter-spacing: 1px;
        text-transform: uppercase;
        font-weight: bold;
        text-align: center;
        text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.3);
        color: rgb(130, 130, 130);
    }

    .nav-tabs > li > a:hover {
        background: rgb(230, 231, 234);
        font-size: 12px;
        line-height: 30px;
        height: 30px;
        position: relative;
        padding: 0 25px;
        float: left;
        display: block;
        /*color: rgb(44, 83, 158);*/
        letter-spacing: 1px;
        text-transform: uppercase;
        font-weight: bold;
        text-align: center;
        border-radius: 3px 3px 0 0;
        border-color: transparent;
    }

    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover,
    .nav-tabs > li.active > a:focus {
        color: #c0392b;
        cursor: default;
        background-color: #fff;
        font-weight: bold;
        border-bottom: 3px solid #f15727;
    }
</style>
<?php
if (!empty($header)) {
//print_r($header);
    ?>
    <div class="row">
        <?php if($header['isClosed'] == 0){ ?>
            <div class="col-sm-1">
                <button type="button" class="btn btn-info"
                        onclick="check_edit_approval()">
                    Edit
                </button>
            </div>

            <div class="col-sm-2">
                <button type="button" class="btn btn-info" onclick="convertToOpportunity(<?php echo $header['leadID']; ?>)">
                    Convert to Opportunity
                </button>
            </div>

        <?php } ?>

        <div class="col-sm-1 text-center">
            &nbsp;
        </div>
        <div class="col-sm-5">
            &nbsp;
        </div>
        <div class="col-sm-4 text-center">
            &nbsp;
        </div>
    </div>
    <br>
    <ul class="nav nav-tabs" id="main-tabs">
        <li class="active"><a href="#about" data-toggle="tab"><i class="fa fa-television"></i>About</a></li>
        <li><a href="#emails" data-toggle="tab"><i class="fa fa-television"></i>Emails </a></li>
        <li><a href="#notes" onclick="lead_notes()" data-toggle="tab"><i class="fa fa-television"></i>Notes </a></li>
        <li><a href="#files" onclick="lead_attachments()" data-toggle="tab"><i class="fa fa-television"></i>Files
            </a></li>
        <li><a href="#tasks" onclick="lead_tasks()" data-toggle="tab"><i class="fa fa-television"></i>Tasks </a></li>
        <li><a href="#products" onclick="lead_products()" data-toggle="tab"><i class="fa fa-television"></i>Products
            </a></li>
    </ul>
    <input type="hidden" id="editleadID" value="<?php echo $header['leadID'] ?>">
    <div class="tab-content">
        <div class="tab-pane active" id="about">
            <br>
            <div class="row">
                <div class="col-md-12 animated zoomIn">
                    <header class="head-title">
                        <h2>LEAD INFORMATION</h2>
                    </header>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-9">
                    <table class="property-table">
                        <tbody>
                        <tr>
                            <td class="ralign"><span class="title">Full Name</span></td>
                            <td><span
                                    class="tddata"><?php echo $header['firstName'] . " " . $header['lastName']; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Title</span></td>

                            <td><span class="tddata"><?php echo $header['title']; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Organization</span></td>
                            <td><span class="tddata"><?php
                                    if ($header['organization'] == '') { ?>
                                        <div class="link-box"><strong class="contacttitle"><a class="link-person noselect" href="#"  onclick="fetchPage('system/crm/organization_edit_view','<?php echo $header['linkedorganizationID'] ?>','View Organization','<?php echo $header['leadID'] ?>','Lead')"><?php echo $header['linkedOrganizationName'] ?></a></strong></div>
                                        <?php
                                    } else {
                                        echo $header['organization'];
                                    }
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Status</span></td>
                            <td><span class="tddata"><?php echo $header['statusdescription'] ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">User Responsible</span></td>
                            <td><span class="tddata"><?php echo $header['responsiblePerson'] ?></span>
                            </td>
                        </tr>
                        <!--                        <tr>
                            <td class="ralign"><span class="title">Lead Rating</span></td>
                            <td><span class="tddata"><?php /*//echo $header['statusdescription'] */ ?></span>
                            </td>
                        </tr>-->
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-3">
                    <div class="fileinput-new">
                        <?php if ($header['leadImage'] != '') { ?>
                            <img src="<?php echo base_url('uploads/crm/lead/' . $header['leadImage']. '?' . time()); ?>"
                                 id="changeImg" class="img-responsive" style="width: 200px; height: 200px;border-radius: 100%;">
                            <?php
                        } else { ?>
                            <img src="<?php echo base_url('images/item/no-image.png'); ?>" id="changeImg"
                                 style="width: 200px; height: 200px;border-radius: 100%;">
                           <!-- <div style="width: 200px; height: 200px; background-color: <?php /*echo $color = getColor()*/?>; border-radius: 100%; padding-top: 25px " id="changeImg"><span style="font-size:100px; color: white;"><center><?php /*$str = $header['firstName']; echo $str[0];*/?></center></span></div>-->
                        <?php } ?>
                        <input type="file" name="leadImage" id="itemImage" style="display: none;"
                               onchange="loadImage(this)"/>
                    </div>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-12 animated zoomIn">
                    <header class="head-title">
                        <h2>ADDITIONAL INFORMATION</h2>
                    </header>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-9">
                    <table class="property-table">
                        <tbody>
                        <tr>
                            <td class="ralign"><span class="title">Email</span></td>

                            <td><span class="tddata"><?php echo $header['email'] ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Phone (Mobile)</span></td>
                            <td><span class="tddata"><?php echo $header['phoneMobile'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Phone (Home)</span></td>
                            <td><span class="tddata"><?php echo $header['phoneHome'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Fax</span></td>
                            <td><span class="tddata"><?php echo $header['fax'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Website</span></td>
                            <td><span class="tddata">
                                    <a class="link-person noselect" target="_blank" href="http://<?php echo $header['leadWebsite'] ?>"><?php echo $header['leadWebsite'] ?></a>
                                    </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Industry</span></td>
                            <td><span class="tddata"><?php echo $header['industry'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Number of Employees</span></td>
                            <td><span class="tddata"><?php echo $header['numberofEmployees'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Lead Source</span></td>
                            <td><span class="tddata"><?php echo $header['sourceDescription'] ?></span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-12 animated zoomIn">
                    <header class="head-title">
                        <h2>ADDRESS</h2>
                    </header>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-9">
                    <table class="property-table">
                        <tbody>
                        <tr>
                            <td class="ralign"><span class="title">Postal Code</span></td>

                            <td><span class="tddata"><?php echo $header['postalCode'] ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">City</span></td>
                            <td><span class="tddata"><?php echo $header['city'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">State</span></td>
                            <td><span class="tddata"><?php echo $header['state'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Country</span></td>
                            <td><span class="tddata"><?php echo $header['CountryDes'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Address</span></td>
                            <td><span class="tddata"><?php echo $header['address'] ?></span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-12 animated zoomIn">
                    <header class="head-title">
                        <h2>LEAD DESCRIPTION</h2>
                    </header>
                </div>
            </div>
            <table class="property-table">
                <tbody>
                <tr>
                    <td style="padding-left: 5%;"><span class="tddata"><?php echo $header['leadDescription'] ?></span>
                    </td>
                </tr>
                </tbody>
            </table>
            <br>

            <div class="row">
                <div class="col-md-12 animated zoomIn">
                    <header class="head-title">
                        <h2>RECORD DETAILS</h2>
                    </header>
                </div>
            </div>
            <table class="property-table">
                <tbody>
                <tr>
                    <td class="ralign"><span class="title">Created Date</span></td>
                    <td><span class="tddata"><?php echo $header['createdDate'] ?></span></td>
                </tr>
                <tr>
                    <td class="ralign"><span class="title">Last Updated</span></td>
                    <td><span class="tddata"><?php echo $header['modifydate'] ?></span></td>
                </tr>
                <tr>
                    <td class="ralign"><span class="title">Lead Created By</span></td>
                    <td><span class="tddata"><?php echo $header['leadCreatedUser'] ?></span></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="tab-pane" id="emails">
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="past-info">
                        <div id="toolbar">
                            <div class="toolbar-title">Lead Emails</div>
                        </div>
                        <div class="post-area">
                            <article class="post">
                                <header class="infoarea">
                                    <strong class="attachemnt_title">
                                <span
                                    style="text-align: center;font-size: 15px;font-weight: 800;">Email Not Configured </span>
                                    </strong>
                                </header>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="notes">
            <br>

            <div class="row" id="show_add_notes_button">
                <div class="col-md-8"><h4><i class="fa fa-hand-o-right"></i> Lead Notes </h4></div>
                <div class="col-md-4">
                    <button type="button" onclick="show_add_note()" class="btn btn-primary pull-right"><i
                            class="fa fa-plus"></i> Add Note
                    </button>
                </div>
            </div>
            <br>
            <?php echo form_open('', 'role="form" id="frm_lead_add_notes"'); ?>
            <input type="hidden" name="leadID" value="<?php echo $header['leadID']; ?>">

            <div id="show_add_notes" class="hide">
                <div class="row">
                    <div class="form-group col-sm-8">
                                <span class="input-req" title="Required Field"><textarea class="form-control" rows="5"
                                                                                         name="description"
                                                                                         id="description"></textarea><span
                                        class="input-req-inner" style="top: 25px;"></span></span>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <button class="btn btn-primary" type="submit">Add</button>
                        <button class="btn btn-danger" type="button" onclick="close_add_note()">Close</button>
                    </div>
                    <div class="form-group col-sm-6" style="margin-top: 10px;">
                        &nbsp
                    </div>
                </div>
            </div>
            </form>
            <div id="show_all_notes"></div>
        </div>
        <div class="tab-pane" id="files">
            <br>

            <div class="row" id="show_add_files_button">
                <div class="col-md-8"><h4><i class="fa fa-hand-o-right"></i> Lead Files </h4></div>
                <div class="col-md-4">
                    <button type="button" onclick="show_add_file()" class="btn btn-primary pull-right"><i
                            class="fa fa-plus"></i> Add Files
                    </button>
                </div>
            </div>
            <div class="row hide" id="add_attachemnt_show">
                <?php echo form_open_multipart('', 'id="lead_attachment_uplode_form" class="form-inline"'); ?>
                <div class="col-sm-10" style="margin-left: 3%">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <input type="text" class="form-control" id="leadattachmentDescription"
                                   name="attachmentDescription" placeholder="Description..." style="width: 240%;">
                            <input type="hidden" class="form-control" id="documentID" name="documentID" value="5">
                            <input type="hidden" class="form-control" id="campaign_document_name" name="document_name"
                                   value="Lead">
                            <input type="hidden" class="form-control" id="lead_documentAutoID" name="documentAutoID"
                                   value="<?php echo $header['leadID']; ?>">
                        </div>
                    </div>
                    <div class="col-sm-8" style="margin-top: -8px;">
                        <div class="form-group">
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput"
                                 style="margin-top: 8px;">
                                <div class="form-control" data-trigger="fileinput"><i
                                        class="glyphicon glyphicon-file color fileinput-exists"></i> <span
                                        class="fileinput-filename"></span></div>
                                  <span class="input-group-addon btn btn-default btn-file"><span
                                          class="fileinput-new"><span class="glyphicon glyphicon-plus"
                                                                      aria-hidden="true"></span></span><span
                                          class="fileinput-exists"><span class="glyphicon glyphicon-repeat"
                                                                         aria-hidden="true"></span></span><input
                                          type="file" name="document_file" id="document_file"></span>
                                <a class="input-group-addon btn btn-default fileinput-exists" id="remove_id"
                                   data-dismiss="fileinput"><span class="glyphicon glyphicon-remove"
                                                                  aria-hidden="true"></span></a>
                            </div>
                        </div>
                        <button type="button" class="btn btn-default" onclick="document_uplode()"><span
                                class="glyphicon glyphicon-floppy-open color" aria-hidden="true"></span></button>
                        </form>
                    </div>
                </div>

            </div>
            <br>

            <div id="show_all_attachments"></div>
        </div>
        <div class="tab-pane" id="tasks">
            <br>

            <div class="row">
                <div class="col-md-8"><h4><i class="fa fa-hand-o-right"></i> Lead Tasks </h4></div>
                <div class="col-md-4">
                    <button type="button"
                            onclick="fetchPage('system/crm/create_new_task','','Create Task',5, <?php echo $header['leadID']; ?>);"
                            class="btn btn-primary pull-right"><i
                            class="fa fa-plus"></i> Add Task
                    </button>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-12">

                    <div id="show_all_tasks">

                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="products">
            <br>

            <div class="row" id="show_add_product_button">
                <div class="col-md-8"><h4><i class="fa fa-hand-o-right"></i> Lead Products </h4></div>
                <div class="col-md-4">
                    <button type="button" onclick="show_add_product()" class="btn btn-primary pull-right"><i
                            class="fa fa-plus"></i> Add Products
                    </button>
                </div>
            </div>
            <br>
            <?php echo form_open('', 'role="form" id="frm_lead_add_product"'); ?>
            <input type="hidden" name="leadID" value="<?php echo $header['leadID']; ?>">
            <input type="hidden" name="leadProductID" id="leadProductID_edit">

            <div id="show_add_product" class="hide">
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Product Name</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <?php echo form_dropdown('productID', $product_arr, '', 'class="form-control select2" id="productID" required'); ?>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Description</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <textarea name="description" id="product_description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Transaction Currency</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <?php echo form_dropdown('transactionCurrencyID', $currency_arr, '', 'class="form-control select2" id="transactionCurrencyID" required'); ?>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-2">
                        <label class="title">Price</label>
                    </div>
                    <div class="form-group col-sm-4">
                        <input type="text" name="price" id="price" class="form-control number">
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-6">
                        <button class="btn btn-danger pull-right" type="button" onclick="close_add_product()">Close
                        </button>
                        <button class="btn btn-primary pull-right" type="submit">Add</button>
                    </div>
                </div>
            </div>
            </form>
            <div id="show_all_product"></div>
        </div>
    </div>
    <?php
}
?>
<script type="text/javascript">
    var Otable;
    $(document).ready(function () {

        $("#description").wysihtml5();

        $('#frm_lead_add_notes').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                //campaign_name: {validators: {notEmpty: {message: 'Campaign Name is required.'}}},
            },
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('CrmLead/add_lead_notes'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1], data[2]);
                    if (data[0] == 's') {
                        close_add_note();
                        lead_notes();
                    } else {
                        $('.btn-primary').prop('disabled', false);
                    }
                },
                error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        });

        $('#frm_lead_add_product').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                productID: {validators: {notEmpty: {message: 'Product Name is required.'}}},
                description: {validators: {notEmpty: {message: 'Description is required.'}}},
                transactionCurrencyID: {validators: {notEmpty: {message: 'Transaction Currency is required.'}}},
                price: {validators: {notEmpty: {message: 'Price is required.'}}}
            },
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('CrmLead/add_lead_product'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1], data[2]);
                    if (data[0] == 's') {
                        close_add_product();
                        lead_products();
                    } else {
                        $('.btn-primary').prop('disabled', false);
                    }
                },
                error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        });

        number_validation();

    });

    function lead_notes() {
        var leadID = $('#editleadID').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {leadID: leadID},
            url: "<?php echo site_url('CrmLead/load_lead_all_notes'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#show_all_notes').html(data);
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }
    function show_add_note() {
        $('#show_all_notes').addClass('hide');
        $('#show_add_notes_button').addClass('hide');
        $('#show_add_notes').removeClass('hide');
        $('#frm_lead_add_notes')[0].reset();
        $('#frm_lead_add_notes').bootstrapValidator('resetForm', true);
    }

    function close_add_note() {
        $('#show_add_notes').addClass('hide');
        $('#show_all_notes').removeClass('hide');
        $('#show_add_notes_button').removeClass('hide');
    }

    function document_uplode() {
        var formData = new FormData($("#lead_attachment_uplode_form")[0]);
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: "<?php echo site_url('crm/attachement_upload'); ?>",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data['type'], data['message'], 1000);
                if (data['status']) {
                    $('#add_attachemnt_show').addClass('hide');
                    $('#remove_id').click();
                    $('#leadattachmentDescription').val('');
                    lead_attachments();
                }
            },
            error: function (data) {
                stopLoad();
                swal("Cancelled", "No File Selected :)", "error");
            }
        });
        return false;
    }

    function lead_attachments() {
        var leadID = $('#editleadID').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {leadID: leadID},
            url: "<?php echo site_url('CrmLead/load_lead_all_attachments'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#show_all_attachments').html(data);
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function show_add_file() {
        $('#add_attachemnt_show').removeClass('hide');
    }

    function delete_crm_attachment(id, fileName) {
        swal({
                title: "Are you sure?",
                text: "You want to Delete!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes!"
            },
            function () {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {'attachmentID': id, 'myFileName': fileName},
                    url: "<?php echo site_url('crm/delete_crm_attachment'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        if (data == true) {
                            myAlert('s', 'Deleted Successfully');
                            lead_attachments();
                        } else {
                            myAlert('e', 'Deletion Failed');
                        }
                    },
                    error: function () {
                        stopLoad();
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });

    }

    function lead_tasks() {
        var leadID = $('#editleadID').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {leadID: leadID},
            url: "<?php echo site_url('CrmLead/load_lead_all_tasks'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#show_all_tasks').html(data);
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    $('#changeImg').click(function () {
        $('#itemImage').click();
    });

    function loadImage(obj) {
        if (obj.files && obj.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#changeImg').attr('src', e.target.result);
            };
            reader.readAsDataURL(obj.files[0]);
            profileImageUploadLead();
        }
    }

    function profileImageUploadLead() {
        var imgageVal = new FormData();
        imgageVal.append('leadID', $('#editleadID').val());

        var files = $("#itemImage")[0].files[0];
        imgageVal.append('files', files);
        // var formData = new FormData($("#lead_profile_image_uplode_form")[0]);
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            data: imgageVal,
            contentType: false,
            cache: false,
            processData: false,
            url: "<?php echo site_url('CrmLead/lead_image_upload'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1], data[2]);
                if (data[0] == 's') {

                }
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

    function show_add_product() {
        $('#show_all_product').addClass('hide');
        $('#show_add_product_button').addClass('hide');
        $('#show_add_product').removeClass('hide');
        $('#frm_lead_add_product')[0].reset();
        $('#frm_lead_add_product').bootstrapValidator('resetForm', true);
    }

    function close_add_product() {
        $('#show_add_product').addClass('hide');
        $('#show_all_product').removeClass('hide');
        $('#show_add_product_button').removeClass('hide');
    }

    function lead_products() {
        var leadID = $('#editleadID').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {leadID: leadID},
            url: "<?php echo site_url('CrmLead/load_leads_all_product'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#show_all_product').html(data);
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function convertToOpportunity(id) {
        swal({
                title: "Are you sure?",
                text: "You want to convert this Lead to Opportunity !",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Convert"
            },
            function () {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {'leadID': id},
                    url: "<?php echo site_url('CrmLead/convert_leadToOpportunity'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1], data[2]);
                        if (data[0] == 's') {
                            fetchPage('system/crm/lead_management', '', 'Leads');
                        }
                    }, error: function () {
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    function edit_lead_product(leadProductID){
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {'leadProductID': leadProductID},
                url: "<?php echo site_url('CrmLead/load_lead_productsEdit'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    show_add_product();
                    if (!jQuery.isEmptyObject(data)) {
                        $('#leadProductID_edit').val(data['leadProductID']);
                        $('#productID').val(data['productID']);
                        $('#product_description').val(data['productDescription']);
                        $('#transactionCurrencyID').val(data['transactionCurrencyID']);
                        $('#price').val(data['price']);
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

    function delete_lead_product(leadProductID){
        swal({
                title: "Are you sure?",
                text: "You want to Delete!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes!"
            },
            function () {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {leadProductID: leadProductID},
                    url: "<?php echo site_url('CrmLead/load_lead_productsDelete'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        if (data == true) {
                            myAlert('s', 'Deleted Successfully');
                            lead_products();
                        } else {
                            myAlert('e', 'Deletion Failed');
                        }
                    },
                    error: function () {
                        stopLoad();
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    function delete_note(notesID) {
        swal({
                title: "Are you sure?",
                text: "You want to Delete!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes!"
            },
            function () {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {notesID: notesID},
                    url: "<?php echo site_url('crm/delete_master_notes_allDocuments'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        if (data == true) {
                            myAlert('s', 'Note Deleted Successfully');
                            lead_notes();
                        } else {
                            myAlert('e', 'Deletion Failed');
                        }
                    },
                    error: function () {
                        stopLoad();
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });

    }

    function check_edit_approval(){
        if(('<?php echo $admin['isSuperAdmin']?>' == 1)){

            fetchPage('system/crm/create_lead',<?php echo $header['leadID'] ?>,'Edit Lead','CRM');
        }else if(<?php echo $this->common_data['current_userID']?>==<?php echo $header['crtduser'] ?>){
            fetchPage('system/crm/create_lead',<?php echo $header['leadID'] ?>,'Edit Lead','CRM');
        }else if(<?php echo $this->common_data['current_userID']?>==<?php echo $header['responsiblePersonEmpID'] ?>){
            fetchPage('system/crm/create_lead',<?php echo $header['leadID'] ?>,'Edit Lead','CRM');
        }else{
            myAlert('w','You do not have permission to edit this lead')
        }
    }

</script>


