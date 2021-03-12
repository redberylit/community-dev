<style>
    .width100p {
        width: 100%;
    }

    .user-table {
        width: 100%;
    }

    .bottom10 {
        margin-bottom: 10px !important;
    }

    .btn-toolbar {
        margin-top: -2px;
    }

    table {
        max-width: 100%;
        background-color: transparent;
        border-collapse: collapse;
        border-spacing: 0;
    }
</style>
<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('communityngo', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);

$this->load->helper('community_ngo_helper');
$schoolTypes = load_schoolTypes();
$language = load_language();

?>

<div class="row">
    <div class="width100p">
        <section class="past-posts">
            <div class="posts-holder settings">
                <div class="past-info">
                    <div id="toolbar">
                        <div class="toolbar-title">
                            <i class="glyphicon glyphicon-blackboard" style="color:#000;font-size:18px;" aria-hidden="true"></i> &nbsp;<?php echo $this->lang->line('communityngo_School'); ?>
                        </div>
                        <div class="btn-toolbar btn-toolbar-small pull-right">
                            <button class="btn btn-primary btn-xs bottom10" data-toggle="modal" onclick="get_popupForSchool();"><?php echo $this->lang->line('communityngo_School_add'); ?>
                            </button>
                        </div>
                        <!--School-->
                        <div class="btn-toolbar btn-toolbar-small pull-right">

                        </div>
                    </div>
                    <div class="post-area">
                        <article class="page-content">

                            <div class="system-settings">

                                <table id="schoolsTable" class="table ">
                                    <thead>
                                        <tr>
                                            <th style="width:5%;">#</th>
                                            <th style="width:25%;"><?php echo $this->lang->line('common_description'); ?> </th>
                                            <th style="width:15%;"><?php echo $this->lang->line('common_address'); ?> </th>
                                            <th style="width:10%;"><?php echo $this->lang->line('common_email'); ?> </th>
                                            <th style="width:10%;"><?php echo $this->lang->line('common_telephone'); ?> </th>
                                            <th style="width:10%;"><?php echo $this->lang->line('communityngo_SchoolType'); ?> </th>
                                            <th style="width:10%;"><?php echo $this->lang->line('common_web'); ?> </th>
                                            <th style="width:15%;"></th>
                                        </tr>
                                    </thead>

                                </table>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div id="add-comSchool-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="comSchooltitle"> <?php echo $this->lang->line('communityngo_School'); ?></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="mo_comSchool">
                        <input type="hidden" class="form-control " id="schoolComID" name="schoolComID">
                        <div class="row" style="margin-top: 10px;">
                            <div class="form-group col-sm-4 col-md-offset-1">
                                <label class="title"> <?php echo $this->lang->line('communityngo_School'); ?></label>
                            </div>
                            <div class="form-group col-sm-6">
                                <span class="input-req" title="Required Field">
                                    <input type="text" class="form-control " id="comSchool" name="comSchool" placeholder="<?php echo $this->lang->line('communityngo_School'); ?>">
                                    <span class="input-req-inner"></span>
                                </span>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="form-group col-sm-4 col-md-offset-1">
                                <label class="title"> <?php echo $this->lang->line('common_address'); ?></label>
                            </div>
                            <div class="form-group col-sm-6">
                                <span class="input-req" title="Required Field">
                                    <input type="text" class="form-control " id="comSchlAddress" name="comSchlAddress" placeholder="<?php echo $this->lang->line('common_address'); ?>">
                                    <span class="input-req-inner"></span>
                                </span>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="form-group col-sm-4 col-md-offset-1">
                                <label class="title"> <?php echo $this->lang->line('common_email'); ?></label>
                            </div>
                            <div class="form-group col-sm-6">
                                <input type="email" class="form-control " id="comSchlMail" name="comSchlMail" placeholder="<?php echo $this->lang->line('common_email'); ?>">
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="form-group col-sm-4 col-md-offset-1">
                                <label class="title"> <?php echo $this->lang->line('common_telephone'); ?></label>
                            </div>
                            <div class="form-group col-sm-6">
                                <input type="number" class="form-control " id="comSchlPhone" name="comSchlPhone" placeholder="<?php echo $this->lang->line('common_telephone'); ?>">
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="form-group col-sm-4 col-md-offset-1">
                                <label class="title"> <?php echo $this->lang->line('communityngo_SchoolType'); ?></label>
                            </div>
                            <div class="form-group col-sm-6">
                                <select id="comSchlTypeID" class="form-control select2" data-placeholder="<?php echo $this->lang->line('communityngo_SchoolType'); ?>" name="comSchlTypeID">
                                    <option value=""></option>
                                    <?php
                                    if (!empty($schoolTypes)) {
                                        foreach ($schoolTypes as $val) {
                                    ?>
                                            <option value="<?php echo $val['schoolTypeID'] ?>"><?php echo $val['schoolTypeDes'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="form-group col-sm-4 col-md-offset-1">
                                <label class="title"> <?php echo $this->lang->line('common_web'); ?></label>
                            </div>
                            <div class="form-group col-sm-6">
                                <input type="text" class="form-control " id="comSchlWebSite" name="comSchlWebSite" placeholder="<?php echo $this->lang->line('common_web'); ?>">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary" onclick="submitcommSchool();"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> <?php echo $this->lang->line('common_save'); ?>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="schlMedium_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
        <form id="schlMedium_form" method="post" enctype="multipart/form-data">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <table border="0">
                            <tr>
                                <td style="padding:2px; text-align: left; width: 95%;">
                                    <h4 class="modal-title" id="comSchlMediumTitle"><?php echo $this->lang->line('communityngo_addSchMedium'); ?></h4>
                                </td>
                                <td style="padding:2px; width: 5%;">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetSchlMediumMod();" id="closeAtt">
                                        <span aria-hidden="true">&times;</span></button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-body">
                        <div class="row-fluid">
                            <div class="form-group span4">
                                <input type="hidden" name="schoolComIDs" id="schoolComIDs">
                                <input type="hidden" name="schMediumID" id="schMediumID">

                                <label for="schlComMedium" class="control-label"><?php echo $this->lang->line('communityngo_medium'); ?>:</label>
                                <select class="form-control input-sm select2" id="schlComMedium" name="schlComMedium" style="width:50%; margin-bottom: 0px;" data-placeholder="<?php echo $this->lang->line('communityngo_medium'); ?>">
                                    <option value=""></option>
                                    <?php
                                    if (!empty($language)) {
                                        foreach ($language as $val) {
                                    ?>
                                            <option value="<?php echo $val['languageID'] ?>"><?php echo $val['description'] ?></option>
                                    <?php

                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                        </div>
                        <div class="row-fluid">
                            <div class="form-group" style="text-align:right;">
                                <button type="submit" value="submit" class="btn btn-primary btn-sm UA_Submit_btn"><i class="fa fa-chevron-circle-right"></i></button>
                            </div>
                        </div>


                        <div id="schlMediumDatasDiv"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="ResetSchlMediumMod();" id="closeAttd"><?php echo $this->lang->line('common_cancel'); ?>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        fetch_schools();

        $(document).ready(function() {

            $('.select2').select2();

        });

        function get_popupForSchool() {
            $('#mo_comSchool')[0].reset();
            $('#mo_comSchool').bootstrapValidator('resetForm', true);
            $('#comSchooltitle').text('<?php echo $this->lang->line('communityngo_School_add'); ?>');
            $('#comSchool').val('');
            $('#schoolComID').val('');
            $('#comSchlAddress').val('');
            $('#comSchlMail').val('');
            $('#comSchlPhone').val('');
            $('#comSchlTypeID').val('').change();
            $('#comSchlWebSite').val('');
            $('#add-comSchool-modal').modal('show');
        }

        function deletecommSchool(schoolComID) {
            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure'); ?>",
                    /*Are you sure?*/
                    text: "<?php echo $this->lang->line('common_you_want_to_delete'); ?>",
                    /*You want to delete this record!*/
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $this->lang->line('common_delete'); ?>",
                    /*Delete*/
                    cancelButtonText: "<?php echo $this->lang->line('common_cancel'); ?>"
                },
                function() {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            'schoolComID': schoolComID
                        },
                        url: "<?php echo site_url('CommunityNgo/delete_commSchool'); ?>",
                        beforeSend: function() {
                            startLoad();
                        },
                        success: function(data) {
                            refreshNotifications(true);
                            stopLoad();
                            if (data == "haveDeleted") {
                                myAlert('s', 'Deleted Successfully');
                            }
                            if (data == "alreadyExist") {
                                myAlert('e', 'Can not delete! School already exists in member job.');
                            }
                            fetch_schools();

                        },
                        error: function() {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }

        function fetch_schools() {
            var Otable = $('#schoolsTable').DataTable({
                "bProcessing": true,
                "bServerSide": true,
                "bDestroy": true,
                "bStateSave": true,
                "sAjaxSource": "<?php echo site_url('CommunityNgo/fetch_commSchool'); ?>",
                "aaSorting": [
                    [1, 'desc']
                ],
                "fnInitComplete": function() {

                },
                "fnDrawCallback": function(oSettings) {
                    $("[rel=tooltip]").tooltip();
                    if (oSettings.bSorted || oSettings.bFiltered) {
                        for (var i = 0, iLen = oSettings.aiDisplay.length; i < iLen; i++) {
                            $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html(i + 1);
                        }
                    }

                    $('.xeditable').editable();
                },

                "columnDefs": [{
                        "width": "2%",
                        "targets": 0
                    },
                    {
                        "width": "5%",
                        "targets": 1
                    },
                    {
                        "width": "3%",
                        "targets": 2
                    },
                    {
                        "width": "1%",
                        "targets": 3
                    },
                    {
                        "width": "1%",
                        "targets": 4
                    },
                    {
                        "width": "1%",
                        "targets": 5
                    },
                    {
                        "width": "1%",
                        "targets": 6
                    },
                    {
                        "width": "2%",
                        "targets": 7
                    },
                ],
                "aoColumns": [{
                        "mData": "schoolComID"
                    },
                    {
                        "mData": "schoolComDes"
                    },
                    {
                        "mData": "address"
                    },
                    {
                        "mData": "email"
                    },
                    {
                        "mData": "telephoneNo"
                    },
                    {
                        "mData": "schoolType"
                    },
                    {
                        "mData": "website"
                    },
                    {
                        "mData": "edit"
                    }

                ],
                //"columnDefs": [{"targets": [2], "orderable": false}],
                "fnServerData": function(sSource, aoData, fnCallback) {
                    //aoData.push({ "name": "filter","value": $(".pr_Filter:checked").val()});
                    //aoData.push({ "name": "subcategory","value": $("#subcategory").val()});
                    $.ajax({
                        'dataType': 'json',
                        'type': 'POST',
                        'url': sSource,
                        'data': aoData,
                        'success': fnCallback
                    });
                }
            });
        }

        function submitcommSchool() {
            var data = $('#mo_comSchool').serializeArray();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('CommunityNgo/save_commSchool'); ?>",
                beforeSend: function() {
                    startLoad();
                },
                success: function(data) {
                    myAlert(data[0], data[1]);
                    stopLoad();

                    if (data[0] == 's') {
                        $('#comSchool').val('');
                        $('#comSchlAddress').val('');
                        $('#comSchlMail').val('');
                        $('#comSchlPhone').val('');
                        $('#comSchlTypeID').val('').change();
                        $('#comSchlWebSite').val('');
                        fetch_schools();
                        $('#add-comSchool-modal').modal('hide');
                    }


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function editcommSchool(schoolComID) {
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: {
                    schoolComID: schoolComID
                },
                url: "<?php echo site_url('CommunityNgo/edit_commSchool'); ?>",
                beforeSend: function() {
                    startLoad();
                    $('#comSchooltitle').text('<?php echo $this->lang->line('communityngo_School_edit'); ?>');
                },
                success: function(data) {
                    stopLoad();
                    if (!jQuery.isEmptyObject(data)) {
                        $('#mo_comSchool').bootstrapValidator('resetForm', true);
                        $('#comSchool').val(data['schoolComDes']);
                        $('#schoolComID').val(data['schoolComID']);
                        $('#comSchlAddress').val(data['address']);
                        $('#comSchlMail').val(data['email']);
                        $('#comSchlPhone').val(data['telephoneNo']);
                        $('#comSchlTypeID').val(data['type']).change();
                        $('#comSchlWebSite').val(data['website']);
                        $('#add-comSchool-modal').modal('show');
                    }
                },
                error: function() {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });

        }

        function get_schoolMedium(schoolComID) {

            document.getElementById('schoolComIDs').value = schoolComID;

            $.ajax({
                type: 'POST',
                url: "CommunityNgo/get_schoolMediumsData",
                data: {
                    'schoolComID': schoolComID
                },
                success: function(data) {
                    $('#schlMedium_modal').modal('show');
                    $('#schlMediumDatasDiv').html(data);

                }
            });

        }


        function edit_schlMedium(x) {
            var eID = x.id;
            var numberPattern = /\d+/g;
            var e = eID.match(numberPattern);
            document.getElementById('schMediumID').value = e;

            $.ajax({
                type: 'POST',
                url: "CommunityNgo/edit_schlMedium",
                data: {
                    'id': e
                },
                dataType: 'json', // what type of data do we expect back from the server
                encode: true,

                success: function(data) {

                    $('#schlComMedium').val(data.schlComMedium).change();

                }
            });
        }


        function delete_schlMedium(d) {
            var id = d.id;
            var num = /\d+/g;
            var delat = id.match(num);

            var schoolComIDs = document.getElementById('schoolComIDs').value;
            $.ajax({
                type: 'POST',
                url: "CommunityNgo/delete_schlMedium",
                data: {
                    'delid': delat
                },

                success: function(data) {
                    $('#schlMedium_form').bootstrapValidator("resetForm", true);

                    $('#schMediumID').val("");
                    if ((data) == 'Deleted') {
                        myAlert('s', 'Deleted Successfully');
                    }
                    if ((data) == 'exitInMemJob') {
                        myAlert('e', 'Can not delete! School already exists in member job.');
                    }
                    get_schoolMedium(schoolComIDs);
                }
            });
        }


        $(document).ready(function() {

            $('#schlMedium_form')
                .bootstrapValidator({
                    framework: 'bootstrap',
                    fields: {
                        schlComMedium: {
                            validators: {
                                notEmpty: {
                                    message: 'Medium is required'
                                }
                            }
                        }
                    }
                })
                .on('success.form.bv', function(e) {
                    e.preventDefault();
                    var $form = $(e.target);
                    var bv = $form.data('bootstrapValidator');
                    var data = $form.serializeArray();

                    var that = $(this),
                        url = that.attr('action'),
                        type = that.attr('method'),
                        data = {};
                    that.find('[name]').each(function(index, value) {
                        var that = $(this),
                            name = that.attr('name'),
                            value = that.val();

                        data[name] = value;
                    });
                    var data = new window.FormData($('#schlMedium_form')[0]);
                    e.preventDefault();
                    $.ajax({
                        url: "CommunityNgo/post_schlMedium",
                        xhr: function() {
                            return $.ajaxSettings.xhr();
                        },
                        type: "POST",
                        data: data,
                        beforeSend: function() {
                            startLoad();
                        },
                        success: function(data) {
                            stopLoad();

                            var schoolComID = document.getElementById('schoolComIDs').value

                            get_schoolMedium(schoolComID);

                            $('#schMediumID').val("");
                            $('#schlComMedium').val('').change();

                            $('#schlMedium_form').bootstrapValidator("resetForm", true);

                            if ((data) == 'inserted') {
                                myAlert('s', 'inserted Successfully');
                            }
                            if ((data) == 'Updated') {
                                myAlert('s', 'Updated Successfully');
                            }
                            if ((data) == 'available') {
                                myAlert('e', 'The medium already available !');
                            }
                            if ((data) == 'exitInMemsJob') {
                                myAlert('e', 'Can not delete! School medium already exists in member job.');
                            }
                        },
                        error: function() {
                            myAlert('e', 'Failed to process your request !');

                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                });
        });

        function ResetSchlMediumMod() {

            $('#schlComMedium').val('').change();

            $('#schlMedium_form').bootstrapValidator("resetForm", true);
            document.getElementById('schlMedium_form').reset();
        }
    </script>