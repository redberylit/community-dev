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
                            <i class="fa fa-graduation-cap" aria-hidden="true"></i> &nbsp;<?php echo $this->lang->line('communityngo_QualificationType'); ?>
                        </div>
                        <div class="btn-toolbar btn-toolbar-small pull-right">
                            <button class="btn btn-primary btn-xs bottom10" data-toggle="modal" onclick="get_popupForQualification();"><?php echo $this->lang->line('communityngo_Qualification_add'); ?>
                            </button>
                        </div>
                        <!--Qualification-->
                        <div class="btn-toolbar btn-toolbar-small pull-right">

                        </div>
                    </div>
                    <div class="post-area">
                        <article class="page-content">

                            <div class="system-settings">

                                <table id="qualificationsTable" class="table ">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo $this->lang->line('common_description'); ?> </th>
                                            <!--Description-->
                                            <th></th>
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

    <div id="add-comQualification-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="comQualificationtitle"> <?php echo $this->lang->line('communityngo_Qualification'); ?></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="mo_comQualification">
                        <input type="hidden" class="form-control " id="DegreeID" name="DegreeID">
                        <div class="row" style="margin-top: 10px;">
                            <div class="form-group col-sm-4 col-md-offset-1">
                                <label class="title"> <?php echo $this->lang->line('communityngo_Qualification'); ?></label>
                            </div>
                            <div class="form-group col-sm-6">
                                <span class="input-req" title="Required Field">
                                    <input type="text" class="form-control " id="comQualification" name="comQualification" placeholder="<?php echo $this->lang->line('communityngo_Qualification'); ?>">
                                    <span class="input-req-inner"></span>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary" onclick="submitcomQualification();"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> <?php echo $this->lang->line('common_save'); ?>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        fetch_qualifications();

        function get_popupForQualification() {
            $('#mo_comQualification')[0].reset();
            $('#mo_comQualification').bootstrapValidator('resetForm', true);
            $('#comQualificationtitle').text('<?php echo $this->lang->line('communityngo_Qualification_add'); ?>');
            $('#comQualification').val('');
            $('#DegreeID').val('');
            $('#add-comQualification-modal').modal('show');
        }

        function deletecomQualification(DegreeID) {
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
                            'DegreeID': DegreeID
                        },
                        url: "<?php echo site_url('CommunityNgo/delete_comQualification'); ?>",
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
                                myAlert('e', 'Can not delete! Qualification already exists with member details.');
                            }
                            fetch_qualifications();

                        },
                        error: function() {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }

        function fetch_qualifications() {
            var Otable = $('#qualificationsTable').DataTable({
                "bProcessing": true,
                "bServerSide": true,
                "bDestroy": true,
                "bStateSave": true,
                "sAjaxSource": "<?php echo site_url('CommunityNgo/fetch_comQualification'); ?>",
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
                ],
                "aoColumns": [{
                        "mData": "DegreeID"
                    },
                    {
                        "mData": "DegreeDescription"
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


        function submitcomQualification() {
            var data = $('#mo_comQualification').serializeArray();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('CommunityNgo/save_comQualification'); ?>",
                beforeSend: function() {
                    startLoad();
                },
                success: function(data) {
                    myAlert(data[0], data[1]);
                    stopLoad();

                    if (data[0] == 's') {
                        $('#comQualification').val('');
                        fetch_qualifications();
                        $('#add-comQualification-modal').modal('hide');
                    }


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function editcomQualification(DegreeID) {
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: {
                    DegreeID: DegreeID
                },
                url: "<?php echo site_url('CommunityNgo/edit_comQualification'); ?>",
                beforeSend: function() {
                    startLoad();
                    $('#comQualificationtitle').text('<?php echo $this->lang->line('communityngo_Qualification_edit'); ?>');
                },
                success: function(data) {
                    stopLoad();
                    if (!jQuery.isEmptyObject(data)) {
                        $('#mo_comQualification').bootstrapValidator('resetForm', true);
                        $('#comQualification').val(data['DegreeDescription']);
                        $('#DegreeID').val(data['DegreeID']);
                        $('#add-comQualification-modal').modal('show');
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