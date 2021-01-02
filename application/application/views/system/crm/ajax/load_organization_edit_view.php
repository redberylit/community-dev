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
    .profile-photo-create-edit img {
        width: 40px;
        height: 40px;
    }
    .profile-photo-create-edit {
        width: 48px;
        padding: 3px;
        background: #fff;
        border-radius: 3px;
        border: solid 1px #ddd;
        position: relative;
        behavior: url(css/PIE.htc);
        z-index: 2;
    }
</style>
<?php
if (!empty($header)) {
    //print_r($header);
    ?>
    <div class="row">

        <div class="col-md-1">
            <div>
                <?php if ($header['organizationLogo'] != '') { ?>
                    <img style="width: 40px; height: 40px;"  src="<?php echo base_url('uploads/crm/organizationLogo/' . $header['organizationLogo']); ?>" alt="Contact">
                    <?php
                } else { ?>
                    <img class="align-left" src="<?php echo base_url('images/crm/organization.PNG'); ?>" width="40" height="40" style="border-radius: 40px;">
                <?php } ?>
            </div>
        </div>
        <div class="col-md-8" style="padding: 0px;">
            <div id="obj-type">Organization</div>
            <h1 style="margin-top: 4px;font-size: 20px;"><?php echo $header['Name']; ?> </h1>
        </div>

        <div class="col-md-3 text-right">
            <button type="button" class="btn btn-primary pull-right"
                    onclick="fetchPage('system/crm/create_organization',<?php echo $header['organizationID'] ?>,'Edit Organization','CRM');">
                <span title="" rel="tooltip" class="glyphicon glyphicon-pencil" data-original-title="Edit"></span>
                Edit
            </button>
        </div>
    </div>
    <br>

    <ul class="nav nav-tabs" id="main-tabs">
        <li class="active"><a href="#about" data-toggle="tab"><i class="fa fa-television"></i>About</a></li>
        <li><a href="#emails" data-toggle="tab"><i class="fa fa-television"></i>Emails </a></li>
        <li><a href="#notes" onclick="organization_notes()" data-toggle="tab"><i class="fa fa-television"></i>Notes </a></li>
        <li><a href="#files" onclick="organization_attachments()" data-toggle="tab"><i class="fa fa-television"></i>Files
            </a></li>
        <li><a href="#tasks" onclick="organization_tasks()" data-toggle="tab"><i class="fa fa-television"></i>Tasks </a></li>
        <li><a href="#tasks" onclick="organization_contacts()" data-toggle="tab"><i class="fa fa-television"></i>Contacts </a></li>
    </ul>
    <input type="hidden" id="editorganizationID" value="<?php echo $header['organizationID'] ?>">
    <div class="tab-content">
        <div class="tab-pane active" id="about">
            <br>

            <div class="row">
                <div class="col-md-12 animated zoomIn">
                    <header class="head-title">
                        <h2>NAME AND DETAILS</h2>
                    </header>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-9">
                    <table class="property-table">
                        <tbody>
                        <tr>
                            <td class="ralign"><span class="title">Name</span></td>
                            <td><span
                                    class="tddata"><?php echo $header['Name']; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Industry</span></td>
                            <td><span
                                    class="tddata"><?php echo $header['industry']; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Number of Employees</span></td>
                            <td><span
                                    class="tddata"><?php echo $header['numberofEmployees']; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Email</span></td>

                            <td><span class="tddata"><?php echo $header['email']; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Phone</span></td>

                            <td><span class="tddata"><?php echo $header['telephoneNo']; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Fax</span></td>

                            <td><span class="tddata"><?php echo $header['fax'] ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Website</span></td>

                            <td><span class="tddata">
                                    <a class="link-person noselect" target="_blank" href="http://<?php echo $header['website'] ?>"><?php echo $header['website'] ?></a>
                                    </span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-3">
                    <div class="fileinput-new">
                        <?php if ($header['organizationLogo'] != '') { ?>

                            <img src="<?php echo base_url('uploads/crm/organizationLogo/' . $header['organizationLogo']); ?>"
                                 id="changeImg" style="width: 200px; height: 200px;border-radius: 100%;">
                            <?php
                        } else { ?>
                            <img src="<?php echo base_url('images/item/no-image.png'); ?>" id="changeImg"
                                 style="width: 200px; height: 200px;border-radius: 100%;">
                        <?php } ?>
                        <input type="file" name="organizationImage" id="itemImage" style="display: none;"
                               onchange="loadImage(this)"/>
                    </div>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-6 animated zoomIn">
                    <header class="head-title">
                        <h2>BILLING ADDRESS</h2>
                    </header>
                </div>
                <div class="col-md-6 animated zoomIn">
                    <header class="head-title">
                        <h2>SHIPPING ADDRESS</h2>
                    </header>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <table class="property-table">
                        <tbody>
                        <tr>
                            <td class="ralign"><span class="title">Address</span></td>
                            <td><span class="tddata"><?php echo $header['billingAddress'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">City</span></td>
                            <td><span class="tddata"><?php echo $header['billingCity'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">State</span></td>
                            <td><span class="tddata"><?php echo $header['billingState'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Postal Code</span></td>

                            <td><span class="tddata"><?php echo $header['billingPostalCode'] ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Country</span></td>
                            <td><span class="tddata"><?php echo $header['CountryDes'] ?></span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-6">
                    <table class="property-table">
                        <tbody>
                        <tr>
                            <td class="ralign"><span class="title">Address</span></td>
                            <td><span class="tddata"><?php echo $header['shippingAddress'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">City</span></td>
                            <td><span class="tddata"><?php echo $header['shippingCity'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">State</span></td>
                            <td><span class="tddata"><?php echo $header['shippingState'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Postal Code</span></td>

                            <td><span class="tddata"><?php echo $header['shippingPostalCode'] ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Country</span></td>
                            <td><span class="tddata"><?php echo $header['CountryDes'] ?></span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12 animated zoomIn">
                    <header class="head-title">
                        <h2>DESCRIPTION</h2>
                    </header>
                </div>
            </div>
            <table class="property-table">
                <tbody>
                <tr>
                    <td style="padding-left: 5%;"><span class="tddata"><?php echo $header['description'] ?></span></td>
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
                    <td class="ralign"><span class="title">Organization Created By</span></td>
                    <td><span class="tddata"><?php echo $header['createdUserName'] ?></span></td>
                </tr>
                </tbody>
            </table>

            <div class="row">
                <div class="col-md-12 animated zoomIn">
                    <header class="head-title">
                        <h2>PERMISSIONS</h2><!--PERMISSIONS-->
                    </header>
                    <div class="row" style="margin-top: 10px;">
                        <div class="form-group col-sm-1">
                            &nbsp
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="title">Visibility</label><!--Visibility-->
                        </div>
                        <div class="form-group col-sm-1">
                            <div class="iradio_square-blue">
                                <div class="skin-section extraColumns"><input id="isPermissionEveryone" type="radio"
                                                                              data-caption="" class="columnSelected"
                                                                              name="userPermission"
                                                                              value="1"><label for="checkbox">&nbsp;</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-2" style="margin-left: -6%;">
                            <label style="font-weight: 400">Everyone</label><!--Everyone-->
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-1">
                            &nbsp
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="title"></label>
                        </div>
                        <div class="form-group col-sm-1">
                            <div class="iradio_square-blue">
                                <div class="skin-section extraColumns"><input name="userPermission" id="isPermissionCreator" type="radio" data-caption="" class="columnSelected" value="2"><label for="checkbox">&nbsp;</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-2" style="margin-left: -6%;">
                            <label style="font-weight: 400">Only For Me</label><!--Only For Me-->
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-1">
                            &nbsp
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="title"></label>
                        </div>
                        <div class="form-group col-sm-1">
                            <div class="iradio_square-blue">
                                <div class="skin-section extraColumns"><input name="userPermission" id="isPermissionGroup" type="radio"
                                                                              data-caption="" class="columnSelected"
                                                                              onclick="leadPermission(3)"
                                                                              value="3"><label for="checkbox">&nbsp;</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-2" style="margin-left: -6%;">
                            <label style="font-weight: 400">Select a Group</label><!--Select a Group-->
                        </div>
                    </div>
                    <div class="row hide" id="show_groupPermission">
                        <div class="form-group col-sm-1">
                            &nbsp
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="title"></label>
                        </div>
                        <div class="form-group col-sm-4" style="margin-left: 2%;">
                            <?php echo form_dropdown('groupID', $groupmaster_arr, '', 'class="form-control" id="groupID"'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-1">
                            &nbsp
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="title"></label>
                        </div>
                        <div class="form-group col-sm-1">
                            <div class="iradio_square-blue">
                                <div class="skin-section extraColumns"><input name="userPermission" id="isPermissionMultiple"
                                                                              type="radio"
                                                                              data-caption="" class="columnSelected"
                                                                              onclick="leadPermission(4)"
                                                                              value="4"><label for="checkbox">&nbsp;</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-2" style="margin-left: -6%;">
                            <label style="font-weight: 400">Select Multiple People</label><!--Select Multiple People-->
                        </div>
                    </div>
                    <div class="row hide" id="show_multiplePermission">
                        <div class="form-group col-sm-1">
                            &nbsp
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="title"></label>
                        </div>
                        <div class="form-group col-sm-4" style="margin-left: 2%;">
                            <?php echo form_dropdown('employees[]', $employees_arr, '', 'class="form-control select2" id="employeesID"  multiple="" style="z-index: 0;"'); ?>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

        </div>
        <div class="tab-pane" id="emails">
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="past-info">
                        <div id="toolbar">
                            <div class="toolbar-title">Organization Emails</div>
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
                <div class="col-md-8"><h4><i class="fa fa-hand-o-right"></i> Organization Notes </h4></div>
                <div class="col-md-4">
                    <button type="button" onclick="show_add_note()" class="btn btn-primary pull-right"><i
                            class="fa fa-plus"></i> Add Note
                    </button>
                </div>
            </div>
            <br>
            <?php echo form_open('', 'role="form" id="frm_organization_add_notes"'); ?>
            <input type="hidden" name="organizationID" value="<?php echo $header['organizationID']; ?>">

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
                <div class="col-md-8"><h4><i class="fa fa-hand-o-right"></i> Organization Files </h4></div>
                <div class="col-md-4">
                    <button type="button" onclick="show_add_file()" class="btn btn-primary pull-right"><i
                            class="fa fa-plus"></i> Add Files
                    </button>
                </div>
            </div>
            <div class="row hide" id="add_attachemnt_show">
                <?php echo form_open_multipart('', 'id="organization_attachment_uplode_form" class="form-inline"'); ?>
                <div class="col-sm-10" style="margin-left: 3%">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <input type="text" class="form-control" id="organizationattachmentDescription"
                                   name="attachmentDescription" placeholder="Description..." style="width: 240%;">
                            <input type="hidden" class="form-control" id="documentID" name="documentID" value="8">
                            <input type="hidden" class="form-control" id="campaign_document_name" name="document_name"
                                   value="Organization">
                            <input type="hidden" class="form-control" id="organization_documentAutoID" name="documentAutoID"
                                   value="<?php echo $header['organizationID']; ?>">
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

            <div id="show_all_tasks"></div>
        </div>
    </div>

    <?php
}
?>
<script type="text/javascript">
    var Otable;
    $(document).ready(function () {
        loadorganizationheader();
        $('.extraColumns input').iCheck({
            checkboxClass: 'icheckbox_square_relative-purple',
            radioClass: 'iradio_square_relative-purple',
            increaseArea: '20%'
        });
        $("#description").wysihtml5();

        $('#frm_organization_add_notes').bootstrapValidator({
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
                url: "<?php echo site_url('Crm/add_organization_notes'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1], data[2]);
                    if (data[0] == 's') {
                        close_add_note();
                        organization_notes();
                    }
                },
                error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        });

    });

    function organization_notes() {
        var organizationID = $('#editorganizationID').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {organizationID: organizationID},
            url: "<?php echo site_url('crm/load_organization_all_notes'); ?>",
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
        $('#frm_organization_add_notes')[0].reset();
        $('#frm_organization_add_notes').bootstrapValidator('resetForm', true);
    }

    function close_add_note() {
        $('#show_add_notes').addClass('hide');
        $('#show_all_notes').removeClass('hide');
        $('#show_add_notes_button').removeClass('hide');
    }

    function document_uplode() {
        var formData = new FormData($("#organization_attachment_uplode_form")[0]);
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
                    $('#organizationattachmentDescription').val('');
                    organization_attachments();
                }
            },
            error: function (data) {
                stopLoad();
                swal("Cancelled", "No File Selected :)", "error");
            }
        });
        return false;
    }

    function organization_attachments() {
        var organizationID = $('#editorganizationID').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {organizationID: organizationID},
            url: "<?php echo site_url('crm/load_organization_all_attachments'); ?>",
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
                            organization_attachments();
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

    function organization_tasks() {
        var organizationID = $('#editorganizationID').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {organizationID: organizationID},
            url: "<?php echo site_url('crm/load_organization_all_tasks'); ?>",
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

    function organization_contacts() {
        var organizationID = $('#editorganizationID').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {organizationID: organizationID},
            url: "<?php echo site_url('crm/load_organization_all_contacts'); ?>",
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
            profileImageUploadOrganization();
        }
    }

    function profileImageUploadOrganization() {
        var imgageVal = new FormData();
        imgageVal.append('organizationID', $('#editorganizationID').val());

        var files = $("#itemImage")[0].files[0];
        imgageVal.append('files', files);
        // var formData = new FormData($("#organization_profile_image_uplode_form")[0]);
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            data: imgageVal,
            contentType: false,
            cache: false,
            processData: false,
            url: "<?php echo site_url('crm/organization_image_upload'); ?>",
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
                            organization_notes();
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
    function loadorganizationheader()
    {
        var organizationID = <?php echo $header['organizationID'] ?>;
        if (organizationID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {'organizationID': organizationID},
                url: "<?php echo site_url('Crm/load_organization_header'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    if (!jQuery.isEmptyObject(data['permission'])) {
                        var selectedItems = [];
                        $.each(data['permission'], function (key, value) {
                            if (value.permissionID == 1) {
                                $('#isPermissionEveryone').iCheck('check');
                                //$('#isPermissionEveryone').iCheck('disable');
                                $('#isPermissionCreator').iCheck('disable');
                                $('#isPermissionGroup').iCheck('disable');
                                $('#isPermissionMultiple').iCheck('disable');
                            } else if (value.permissionID == 2) {
                                $('#isPermissionCreator').iCheck('check');
                                $('#isPermissionEveryone').iCheck('disable');
                                //$('#isPermissionCreator').iCheck('disable');
                                $('#isPermissionGroup').iCheck('disable');
                                $('#isPermissionMultiple').iCheck('disable');
                            } else if (value.permissionID == 3) {
                                $('#isPermissionGroup').iCheck('check');
                                $('#isPermissionEveryone').iCheck('disable');
                                $('#isPermissionCreator').iCheck('disable');
                                //$('#isPermissionGroup').iCheck('disable');
                                $('#isPermissionMultiple').iCheck('disable');
                            } else if (value.permissionID == 4) {
                                $('#isPermissionMultiple').iCheck('check');
                                $('#isPermissionEveryone').iCheck('disable');
                                $('#isPermissionCreator').iCheck('disable');
                                $('#isPermissionGroup').iCheck('disable');
                                //$('#isPermissionMultiple').iCheck('disable');
                            }
                        });
                    }
                    stopLoad();
                    refreshNotifications(true);
                }, error: function () {
                    alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        }
    }

</script>


