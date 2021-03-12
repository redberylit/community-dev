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
                            <i class="fa fa-list" aria-hidden="true"></i> &nbsp;<?php echo $this->lang->line('communityngo_memHelp_details'); ?>
                        </div>
                        <div class="btn-toolbar btn-toolbar-small pull-right">
                            <button class="btn btn-primary btn-xs bottom10" data-toggle="modal" onclick="get_popupForHelpDetail();"><?php echo $this->lang->line('communityngo_memHelp_detail_add'); ?>
                            </button>
                        </div>
                        <!--HelpDetail-->
                        <div class="btn-toolbar btn-toolbar-small pull-right">

                        </div>
                    </div>
                    <div class="post-area">
                        <article class="page-content">

                            <div class="system-settings">

                                <table id="HelpDetailsTable" class="table ">
                                    <thead>
                                        <tr>
                                            <th style="width:8%;">#</th>
                                            <th style="width:50%;"><?php echo $this->lang->line('common_description'); ?> </th>
                                            <th style="width:32%;"><?php echo $this->lang->line('communityngo_memHelp_type'); ?> </th>
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

    <div id="add-comHelpDetail-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="comHelpDetailtitle"> <?php echo $this->lang->line('communityngo_memHelp_details'); ?></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="mo_comHelpDetail">
                        <input type="hidden" class="form-control " id="helpRequireID" name="helpRequireID">
                        <div class="row" style="margin-top: 10px;">
                            <div class="form-group col-sm-4 col-md-offset-1">
                                <label class="title"> <?php echo $this->lang->line('communityngo_memHelp_details'); ?></label>
                            </div>
                            <div class="form-group col-sm-6">
                                <span class="input-req" title="Required Field">
                                    <input type="text" class="form-control " id="comHelpDetail" name="comHelpDetail" placeholder="<?php echo $this->lang->line('communityngo_memHelp_details'); ?>">
                                    <span class="input-req-inner"></span>
                                </span>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="form-group col-sm-4 col-md-offset-1">
                                <label class="title"> <?php echo $this->lang->line('communityngo_memHelp_type'); ?></label>
                            </div>
                            <div class="form-group col-sm-6">
                                <select id="helpRequireType" class="form-control select2" data-placeholder="<?php echo $this->lang->line('communityngo_memHelp_type'); ?>" name="helpRequireType">
                                    <option value=""></option>
                                    <option value="GOV">Government Help</option>
                                    <option value="PVT">Private Help</option>
                                    <option value="CONS">Consultancy</option>
                                    <option value="OTHER">Other (Specify)</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary" onclick="submitcommHelpDetail();"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> <?php echo $this->lang->line('common_save'); ?>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        fetch_HelpDetails();

        $(document).ready(function() {

            $('.select2').select2();

        });

        function get_popupForHelpDetail() {
            $('#mo_comHelpDetail')[0].reset();
            $('#mo_comHelpDetail').bootstrapValidator('resetForm', true);
            $('#comHelpDetailtitle').text('<?php echo $this->lang->line('communityngo_memHelp_detail_add'); ?>');
            $('#comHelpDetail').val('');
            $('#helpRequireID').val('');
            $('#helpRequireType').val('').change();
            $('#add-comHelpDetail-modal').modal('show');
        }

        function deletecommHelpDetail(helpRequireID) {
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
                            'helpRequireID': helpRequireID
                        },
                        url: "<?php echo site_url('CommunityNgo/delete_commHelpDetail'); ?>",
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
                                myAlert('e', 'Can not delete! Help Detail already exists in member help requirement.');
                            }
                            fetch_HelpDetails();

                        },
                        error: function() {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }

        function fetch_HelpDetails() {
            var Otable = $('#HelpDetailsTable').DataTable({
                "bProcessing": true,
                "bServerSide": true,
                "bDestroy": true,
                "bStateSave": true,
                "sAjaxSource": "<?php echo site_url('CommunityNgo/fetch_commHelpDetail'); ?>",
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
                        "width": "2%",
                        "targets": 2
                    },
                    {
                        "width": "1%",
                        "targets": 3
                    },
                ],
                "aoColumns": [{
                        "mData": "helpRequireID"
                    },
                    {
                        "mData": "helpRequireDesc"
                    },
                    {
                        "mData": "HelpDetailType"
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


        function submitcommHelpDetail() {
            var data = $('#mo_comHelpDetail').serializeArray();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('CommunityNgo/save_commHelpDetail'); ?>",
                beforeSend: function() {
                    startLoad();
                },
                success: function(data) {
                    myAlert(data[0], data[1]);
                    stopLoad();

                    if (data[0] == 's') {
                        $('#comHelpDetail').val('');
                        $('#helpRequireType').val('').change();
                        fetch_HelpDetails();
                        $('#add-comHelpDetail-modal').modal('hide');
                    }


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }

        function editcommHelpDetail(helpRequireID) {
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: {
                    helpRequireID: helpRequireID
                },
                url: "<?php echo site_url('CommunityNgo/edit_commHelpDetail'); ?>",
                beforeSend: function() {
                    startLoad();
                    $('#comHelpDetailtitle').text('<?php echo $this->lang->line('communityngo_memHelp_detail_edit'); ?>');
                },
                success: function(data) {
                    stopLoad();
                    if (!jQuery.isEmptyObject(data)) {
                        $('#mo_comHelpDetail').bootstrapValidator('resetForm', true);
                        $('#comHelpDetail').val(data['helpRequireDesc']);
                        $('#helpRequireID').val(data['helpRequireID']);
                        $('#helpRequireType').val(data['helpRequireType']).change();
                        $('#add-comHelpDetail-modal').modal('show');
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