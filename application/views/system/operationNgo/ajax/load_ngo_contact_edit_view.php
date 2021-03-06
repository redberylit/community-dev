<?php $this->load->helper('operation_ngo_helper'); ?>

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
        <div class="col-md-5">
            &nbsp;
        </div>
        <div class="col-md-4 text-center">
            &nbsp;
        </div>
        <div class="col-md-3 text-right">
            <button type="button" class="btn btn-primary pull-right"
                    onclick="fetchPage('system/operationNgo/create_donor',<?php echo $header['contactID'] ?>,'Edit Donor','CRM');">
                <span title="" rel="tooltip" class="glyphicon glyphicon-pencil" data-original-title="Edit"></span>
                Edit
            </button>
        </div>
    </div>
    <br>
    <ul class="nav nav-tabs" id="main-tabs">
        <li class="active"><a href="#about" data-toggle="tab"><i class="fa fa-television"></i>About</a></li>
        <!--<li><a href="#emails" data-toggle="tab"><i class="fa fa-television"></i>Emails </a></li>-->
        <li><a href="#notes" onclick="contact_notes()" data-toggle="tab"><i class="fa fa-television"></i>Notes </a></li>
        <li><a href="#files" onclick="contact_attachments()" data-toggle="tab"><i class="fa fa-television"></i>Files
            </a></li>
        <!--<li><a href="#tasks" onclick="contact_tasks()" data-toggle="tab"><i class="fa fa-television"></i>Tasks </a></li>-->
        <li><a href="#donors" onclick="fetch_donor_donationsview()" data-toggle="tab"><i class="fa fa-television"></i>Donations</a></li>
    </ul>
    <input type="hidden" id="editcontactID" value="<?php echo $header['contactID'] ?>">
    <div class="tab-content">
        <div class="tab-pane active" id="about">
            <br>

            <div class="row">
                <div class="col-md-12 animated zoomIn">
                    <header class="head-title">
                        <h2>DONOR NAME AND DETAIL</h2>
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
                                    class="tddata"><?php echo $header['name']; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Email</span></td>

                            <td><span class="tddata"><?php echo $header['contactEmail'] ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Currency</span></td>

                            <td><span class="tddata"><?php echo $header['CurrencyName']." ( ".$header['CurrencyCode']." )" ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Phone (Primary)</span></td>
                            <td><span class="tddata"><?php echo $header['phoneCountryCodePrimary']." - ".$header['phoneAreaCodePrimary'].$header['contactPhonePrimary'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Phone (Secondary)</span></td>
                            <td><span class="tddata"><?php echo $header['phoneCountryCodeSecondary']." - ".$header['phoneAreaCodeSecondary'].$header['phoneSecondary'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Fax</span></td>
                            <td><span class="tddata"><?php echo $header['contactFax'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Website</span></td>
                            <td><span class="tddata">
                                    <a class="link-person noselect" target="_blank" href="http://<?php echo $header['website'] ?>"><?php echo $header['website'] ?></a>
                                    </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Address</span></td>
                            <td><span class="tddata"><?php echo $header['address'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">City</span></td>
                            <td><span class="tddata"><?php echo $header['city'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Province</span></td>
                            <td><span class="tddata"><?php echo $header['province'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Postal Code</span></td>

                            <td><span class="tddata"><?php echo $header['postalCode'] ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ralign"><span class="title">Country</span></td>
                            <td><span class="tddata"><?php echo $header['CountryDes'] ?></span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-3">
                    <div class="fileinput-new thumbnail">
                        <?php if ($header['contactImage'] != '') {
                            $donorImg = get_all_operationngo_images($header['contactImage'],'uploads/ngo/donorsImage/','donorImg');
                            ?>
                            <img src="<?php echo $donorImg; ?>"
                                 id="changeImg" style="width: 200px; height: 145px;">
                            <?php
                        } else { ?>
                            <img src="<?php echo base_url('images/item/no-image.png'); ?>" id="changeImg"
                                 style="width: 200px; height: 145px;">
                        <?php } ?>
                        <input type="file" name="contactImage" id="itemImage" style="display: none;"
                               onchange="loadImage(this)"/>
                    </div>
                </div>
            </div>
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
                    <td class="ralign"><span class="title">Donor Created By</span></td>
                    <td><span class="tddata"><?php echo $header['contactCreadtedUser'] ?></span></td>
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
                            <div class="toolbar-title">Donor Emails</div>
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
                <div class="col-md-8"><h4><i class="fa fa-hand-o-right"></i> Donor Notes </h4></div>
                <div class="col-md-4">
                    <button type="button" onclick="show_add_note()" class="btn btn-primary pull-right"><i
                            class="fa fa-plus"></i> Add Note
                    </button>
                </div>
            </div>
            <br>
            <?php echo form_open('', 'role="form" id="frm_contact_add_notes"'); ?>
            <input type="hidden" name="contactID" value="<?php echo $header['contactID']; ?>">

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
                <div class="col-md-8"><h4><i class="fa fa-hand-o-right"></i> Donor Files </h4></div>
                <div class="col-md-4">
                    <button type="button" onclick="show_add_file()" class="btn btn-primary pull-right"><i
                            class="fa fa-plus"></i> Add Files
                    </button>
                </div>
            </div>
            <div class="row hide" id="add_attachemnt_show">
                <?php echo form_open_multipart('', 'id="contact_attachment_uplode_form" class="form-inline"'); ?>
                <div class="col-sm-10" style="margin-left: 3%">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <input type="text" class="form-control" id="contactattachmentDescription"
                                   name="attachmentDescription" placeholder="Description..." style="width: 240%;">
                            <input type="hidden" class="form-control" id="documentID" name="documentID" value="1">
                            <input type="hidden" class="form-control" id="campaign_document_name" name="document_name"
                                   value="Donor">
                            <input type="hidden" class="form-control" id="contact_documentAutoID" name="documentAutoID"
                                   value="<?php echo $header['contactID']; ?>">
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

        <div class="tab-pane" id ="donors">
            <br>
            <div class="row">
                <div class="col-md-12"><h4><i class="fa fa-hand-o-right"></i>Donations</h4></div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="donation_view"></div>
                </div>
            </div>
        </div>
    </div>



    <?php
}
?>
<script type="text/javascript">
    var Otable;
    $(document).ready(function () {

        $("#description").wysihtml5();

        $('#frm_contact_add_notes').bootstrapValidator({
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
                url: "<?php echo site_url('OperationNgo/add_donor_notes'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1], data[2]);
                    if (data[0] == 's') {
                        close_add_note();
                        contact_notes();
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

    function contact_notes() {
        var contactID = $('#editcontactID').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {contactID: contactID},
            url: "<?php echo site_url('OperationNgo/load_donor_all_notes'); ?>",
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
        $('#frm_contact_add_notes')[0].reset();
        $('#frm_contact_add_notes').bootstrapValidator('resetForm', true);
    }

    function close_add_note() {
        $('#show_add_notes').addClass('hide');
        $('#show_all_notes').removeClass('hide');
        $('#show_add_notes_button').removeClass('hide');
    }

    function document_uplode() {
        var formData = new FormData($("#contact_attachment_uplode_form")[0]);
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: "<?php echo site_url('OperationNgo/ngo_attachement_upload'); ?>",
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
                    $('#contactattachmentDescription').val('');
                    contact_attachments();
                }
            },
            error: function (data) {
                stopLoad();
                swal("Cancelled", "No File Selected :)", "error");
            }
        });
        return false;
    }

    function contact_attachments() {
        var contactID = $('#editcontactID').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {contactID: contactID},
            url: "<?php echo site_url('OperationNgo/load_donor_all_attachments'); ?>",
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

    function delete_donor_attachment(id, fileName) {
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
                    url: "<?php echo site_url('OperationNgo/delete_donor_attachment'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        if (data == true) {
                            myAlert('s', 'Deleted Successfully');
                            contact_attachments();
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
            profileImageUploadDonor();
        }
    }

    function profileImageUploadDonor() {
        var imgageVal = new FormData();
        imgageVal.append('contactID', $('#editcontactID').val());

        var files = $("#itemImage")[0].files[0];
        imgageVal.append('files', files);
        // var formData = new FormData($("#contact_profile_image_uplode_form")[0]);
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            data: imgageVal,
            contentType: false,
            cache: false,
            processData: false,
            url: "<?php echo site_url('OperationNgo/donor_image_upload'); ?>",
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
    function fetch_donor_donationsview() {
        var contactID = $('#editcontactID').val();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {'contactID':contactID},
            url: '<?php echo site_url("OperationNgo/donor_donations_view"); ?>',
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $('#donation_view').html(data);
            }, error: function () {
                myAlert('e', 'An Error Occurred! Please Try Again.');
                stopLoad();
            }
        });
    }


</script>


