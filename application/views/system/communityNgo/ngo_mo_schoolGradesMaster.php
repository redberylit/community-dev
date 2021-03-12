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
                            <i class="fa fa-google-plus" aria-hidden="true"></i> &nbsp;<?php echo $this->lang->line('communityngo_SchoolGrade'); ?>
                        </div>
                        <div class="btn-toolbar btn-toolbar-small pull-right">
                            <button class="btn btn-primary btn-xs bottom10" data-toggle="modal" onclick="get_popupForSchlGrade();"><?php echo $this->lang->line('communityngo_schoolGrade_add'); ?>
                            </button>
                        </div>
                        <!--Schl Grade-->
                        <div class="btn-toolbar btn-toolbar-small pull-right">

                        </div>
                    </div>
                    <div class="post-area">
                        <article class="page-content">

                            <div class="system-settings">

                                <table id="SchlGradesTable" class="table ">
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

    <div id="add-comSchlGrade-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="comSchlGradetitle"> <?php echo $this->lang->line('communityngo_SchoolGrade'); ?></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="mo_comSchlGrade">
                        <input type="hidden" class="form-control " id="gradeComID" name="gradeComID">
                        <div class="row" style="margin-top: 10px;">
                            <div class="form-group col-sm-4 col-md-offset-1">
                                <label class="title"> <?php echo $this->lang->line('communityngo_SchoolGrade'); ?></label>
                            </div>
                            <div class="form-group col-sm-6">
                                <span class="input-req" title="Required Field">
                                    <input type="text" class="form-control " id="comSchlGrade" name="comSchlGrade" placeholder="<?php echo $this->lang->line('communityngo_SchoolGrade'); ?>">
                                    <span class="input-req-inner"></span>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary" onclick="submitcomSchlGrade();"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> <?php echo $this->lang->line('common_save'); ?>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        fetch_SchlGrades();

        function get_popupForSchlGrade() {
            $('#mo_comSchlGrade')[0].reset();
            $('#mo_comSchlGrade').bootstrapValidator('resetForm', true);
            $('#comSchlGradetitle').text('<?php echo $this->lang->line('communityngo_schoolGrade_add'); ?>');
            $('#comSchlGrade').val('');
            $('#gradeComID').val('');
            $('#add-comSchlGrade-modal').modal('show');
        }

        function deleteComSchlGrade(gradeComID) {
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
                            'gradeComID': gradeComID
                        },
                        url: "<?php echo site_url('CommunityNgo/delete_comSchlGrade'); ?>",
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
                                myAlert('e', 'Can not delete! Grade already exists in member job.');
                            }
                            fetch_SchlGrades();

                        },
                        error: function() {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }

        function fetch_SchlGrades() {
            var Otable = $('#SchlGradesTable').DataTable({
                "bProcessing": true,
                "bServerSide": true,
                "bDestroy": true,
                "bStateSave": true,
                "sAjaxSource": "<?php echo site_url('CommunityNgo/fetch_comSchlGrade'); ?>",
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
                        "mData": "gradeComID"
                    },
                    {
                        "mData": "gradeComDes"
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


        function submitcomSchlGrade() {
            var data = $('#mo_comSchlGrade').serializeArray();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('CommunityNgo/save_comSchlGrade'); ?>",
                beforeSend: function() {
                    startLoad();
                },
                success: function(data) {
                    myAlert(data[0], data[1]);
                    stopLoad();

                    if (data[0] == 's') {
                        $('#comSchlGrade').val('');
                        fetch_SchlGrades();
                        $('#add-comSchlGrade-modal').modal('hide');
                    }


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function editcomSchlGrade(gradeComID) {
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: {
                    gradeComID: gradeComID
                },
                url: "<?php echo site_url('CommunityNgo/edit_comSchlGrade'); ?>",
                beforeSend: function() {
                    startLoad();
                    $('#comSchlGradetitle').text('<?php echo $this->lang->line('communityngo_schoolGrade_edit'); ?>');
                },
                success: function(data) {
                    stopLoad();
                    if (!jQuery.isEmptyObject(data)) {
                        $('#mo_comSchlGrade').bootstrapValidator('resetForm', true);
                        $('#comSchlGrade').val(data['gradeComDes']);
                        $('#gradeComID').val(data['gradeComID']);
                        $('#add-comSchlGrade-modal').modal('show');
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