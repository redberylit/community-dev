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
?>

<div class="row">
    <div class="width100p">
        <section class="past-posts">
            <div class="posts-holder settings">
                <div class="past-info">
                    <div id="toolbar">
                        <div class="toolbar-title">
                            <i class="fa fa-university" aria-hidden="true"></i> &nbsp;<?php echo $this->lang->line('communityngo_University'); ?>
                        </div>
                        <div class="btn-toolbar btn-toolbar-small pull-right">
                            <button class="btn btn-primary btn-xs bottom10" data-toggle="modal" onclick="get_popupForInstitute();"><?php echo $this->lang->line('communityngo_institute_add'); ?>
                            </button>
                        </div>
                        <!--Institute-->
                        <div class="btn-toolbar btn-toolbar-small pull-right">

                        </div>
                    </div>
                    <div class="post-area">
                        <article class="page-content">

                            <div class="system-settings">

                                <table id="InstitutesTable" class="table ">
                                    <thead>
                                        <tr>
                                            <th style="width:5%;">#</th>
                                            <th style="width:45%;"><?php echo $this->lang->line('common_description'); ?> </th>
                                            <th style="width:10%;"><?php echo $this->lang->line('common_address'); ?> </th>
                                            <th style="width:10%;"><?php echo $this->lang->line('common_email'); ?> </th>
                                            <th style="width:10%;"><?php echo $this->lang->line('common_telephone'); ?> </th>
                                            <th style="width:10%;"><?php echo $this->lang->line('common_web'); ?> </th>
                                            <th style="width:10%;"></th>
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

    <div id="add-comInstitute-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="comInstitutetitle"> <?php echo $this->lang->line('communityngo_University'); ?></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="mo_comInstitute">
                        <input type="hidden" class="form-control " id="UniversityID" name="UniversityID">
                        <div class="row" style="margin-top: 10px;">
                            <div class="form-group col-sm-4 col-md-offset-1">
                                <label class="title"> <?php echo $this->lang->line('communityngo_University'); ?></label>
                            </div>
                            <div class="form-group col-sm-6">
                                <span class="input-req" title="Required Field">
                                    <input type="text" class="form-control " id="comInstitute" name="comInstitute" placeholder="<?php echo $this->lang->line('communityngo_University'); ?>">
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
                                    <input type="text" class="form-control " id="comInsAddress" name="comInsAddress" placeholder="<?php echo $this->lang->line('common_address'); ?>">
                                    <span class="input-req-inner"></span>
                                </span>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="form-group col-sm-4 col-md-offset-1">
                                <label class="title"> <?php echo $this->lang->line('common_email'); ?></label>
                            </div>
                            <div class="form-group col-sm-6">
                                <span class="input-req" title="Required Field">
                                    <input type="email" class="form-control " id="comInsMail" name="comInsMail" placeholder="<?php echo $this->lang->line('common_email'); ?>">
                                    <span class="input-req-inner"></span>
                                </span>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="form-group col-sm-4 col-md-offset-1">
                                <label class="title"> <?php echo $this->lang->line('common_telephone'); ?></label>
                            </div>
                            <div class="form-group col-sm-6">
                                <span class="input-req" title="Required Field">
                                    <input type="number" class="form-control " id="comInsPhone" name="comInsPhone" placeholder="<?php echo $this->lang->line('common_telephone'); ?>">
                                    <span class="input-req-inner"></span>
                                </span>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="form-group col-sm-4 col-md-offset-1">
                                <label class="title"> <?php echo $this->lang->line('common_web'); ?></label>
                            </div>
                            <div class="form-group col-sm-6">
                                <span class="input-req" title="Required Field">
                                    <input type="text" class="form-control " id="comInsWebSite" name="comInsWebSite" placeholder="<?php echo $this->lang->line('common_web'); ?>">
                                    <span class="input-req-inner"></span>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary" onclick="submitcomInstitute();"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> <?php echo $this->lang->line('common_save'); ?>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        fetch_Institutes();

        function get_popupForInstitute() {
            $('#mo_comInstitute')[0].reset();
            $('#mo_comInstitute').bootstrapValidator('resetForm', true);
            $('#comInstitutetitle').text('<?php echo $this->lang->line('communityngo_institute_add'); ?>');
            $('#comInstitute').val('');
            $('#UniversityID').val('');
            $('#comInsAddress').val('');
            $('#comInsMail').val('');
            $('#comInsPhone').val('');
            $('#comInsWebSite').val('');
            $('#add-comInstitute-modal').modal('show');
        }

        function deletecomInstitute(UniversityID) {
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
                            'UniversityID': UniversityID
                        },
                        url: "<?php echo site_url('CommunityNgo/delete_comInstitute'); ?>",
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
                                myAlert('e', 'Can not delete! Institute already exists with member details.');
                            }
                            fetch_Institutes();

                        },
                        error: function() {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }

        function fetch_Institutes() {
            var Otable = $('#InstitutesTable').DataTable({
                "bProcessing": true,
                "bServerSide": true,
                "bDestroy": true,
                "bStateSave": true,
                "sAjaxSource": "<?php echo site_url('CommunityNgo/fetch_comInstitute'); ?>",
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
                        "width": "7%",
                        "targets": 1
                    },
                    {
                        "width": "1%",
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
                ],
                "aoColumns": [{
                        "mData": "UniversityID"
                    },
                    {
                        "mData": "UniversityDescription"
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


        function submitcomInstitute() {
            var data = $('#mo_comInstitute').serializeArray();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('CommunityNgo/save_comInstitute'); ?>",
                beforeSend: function() {
                    startLoad();
                },
                success: function(data) {
                    myAlert(data[0], data[1]);
                    stopLoad();

                    if (data[0] == 's') {
                        $('#comInstitute').val('');
                        $('#comInsAddress').val('');
                        $('#comInsMail').val('');
                        $('#comInsPhone').val('');
                        $('#comInsWebSite').val('');
                        fetch_Institutes();
                        $('#add-comInstitute-modal').modal('hide');
                    }


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function editcomInstitute(UniversityID) {
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: {
                    UniversityID: UniversityID
                },
                url: "<?php echo site_url('CommunityNgo/edit_comInstitute'); ?>",
                beforeSend: function() {
                    startLoad();
                    $('#comInstitutetitle').text('<?php echo $this->lang->line('communityngo_institute_edit'); ?>');
                },
                success: function(data) {
                    stopLoad();
                    if (!jQuery.isEmptyObject(data)) {
                        $('#mo_comInstitute').bootstrapValidator('resetForm', true);
                        $('#comInstitute').val(data['UniversityDescription']);
                        $('#UniversityID').val(data['UniversityID']);
                        $('#comInsAddress').val(data['address']);
                        $('#comInsMail').val(data['email']);
                        $('#comInsPhone').val(data['telephoneNo']);
                        $('#comInsWebSite').val(data['website']);
                        $('#add-comInstitute-modal').modal('show');
                    }
                },
                error: function() {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });

        }
    </script>