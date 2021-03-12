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
                            <i class="fa fa-language" aria-hidden="true"></i> &nbsp;<?php echo $this->lang->line('communityngo_Language'); ?>
                        </div>
                        <div class="btn-toolbar btn-toolbar-small pull-right">
                            <button class="btn btn-primary btn-xs bottom10" data-toggle="modal" onclick="get_popupForComLanguage();"><?php echo $this->lang->line('communityngo_addLanguage'); ?>
                            </button>
                        </div>
                        <!--ComLanguage-->
                        <div class="btn-toolbar btn-toolbar-small pull-right">

                        </div>
                    </div>
                    <div class="post-area">
                        <article class="page-content">

                            <div class="system-settings">

                                <table id="ComLanguagesTable" class="table ">
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

    <div id="add-comLanguage-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="comLanguagetitle"> <?php echo $this->lang->line('communityngo_Language'); ?></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="mo_comLanguage">
                        <input type="hidden" class="form-control " id="languageID" name="languageID">
                        <div class="row" style="margin-top: 10px;">
                            <div class="form-group col-sm-4 col-md-offset-1">
                                <label class="title"> <?php echo $this->lang->line('communityngo_Language'); ?></label>
                            </div>
                            <div class="form-group col-sm-6">
                                <span class="input-req" title="Required Field">
                                    <input type="text" class="form-control " id="comLanguage" name="comLanguage" placeholder="<?php echo $this->lang->line('communityngo_Language'); ?>">
                                    <span class="input-req-inner"></span>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary" onclick="submitcomLanguage();"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> <?php echo $this->lang->line('common_save'); ?>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        fetch_ComLanguages();

        function get_popupForComLanguage() {
            $('#mo_comLanguage')[0].reset();
            $('#mo_comLanguage').bootstrapValidator('resetForm', true);
            $('#comLanguagetitle').text('<?php echo $this->lang->line('communityngo_addLanguage'); ?>');
            $('#comLanguage').val('');
            $('#languageID').val('');
            $('#add-comLanguage-modal').modal('show');
        }

        function deletecomLanguage(languageID) {
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
                            'languageID': languageID
                        },
                        url: "<?php echo site_url('CommunityNgo/delete_communityLanguge'); ?>",
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
                                myAlert('e', 'Can not delete! Language already exists in community.');
                            }
                            fetch_ComLanguages();

                        },
                        error: function() {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }

        function fetch_ComLanguages() {
            var Otable = $('#ComLanguagesTable').DataTable({
                "bProcessing": true,
                "bServerSide": true,
                "bDestroy": true,
                "bStateSave": true,
                "sAjaxSource": "<?php echo site_url('CommunityNgo/fetch_communityLanguge'); ?>",
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
                        "mData": "languageID"
                    },
                    {
                        "mData": "description"
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


        function submitcomLanguage() {
            var data = $('#mo_comLanguage').serializeArray();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('CommunityNgo/save_communityLanguge'); ?>",
                beforeSend: function() {
                    startLoad();
                },
                success: function(data) {
                    myAlert(data[0], data[1]);
                    stopLoad();

                    if (data[0] == 's') {
                        $('#comLanguage').val('');
                        fetch_ComLanguages();
                        $('#add-comLanguage-modal').modal('hide');
                    }


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function editcomLanguage(languageID) {
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: {
                    languageID: languageID
                },
                url: "<?php echo site_url('CommunityNgo/edit_communityLanguge'); ?>",
                beforeSend: function() {
                    startLoad();
                    $('#comLanguagetitle').text('<?php echo $this->lang->line('communityngo_Language_edit'); ?>');
                },
                success: function(data) {
                    stopLoad();
                    if (!jQuery.isEmptyObject(data)) {
                        $('#mo_comLanguage').bootstrapValidator('resetForm', true);
                        $('#comLanguage').val(data['description']);
                        $('#languageID').val(data['languageID']);
                        $('#add-comLanguage-modal').modal('show');
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