<?php $all_crm_status_arr = all_crm_status(); ?>

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

    .flex {
        display:
    }
</style>
<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('crm', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
?>

<div class="row">

    <div class="width100p">
        <section class="past-posts">
            <div class="posts-holder settings">
                <div class="past-info">
                    <div id="toolbar">
                        <div class="toolbar-title">
                            <i class="fa fa-flag" aria-hidden="true"></i> <?php echo $this->lang->line('crm_document_status');?>
                        </div><!--Document Status-->
                        <div class="btn-toolbar btn-toolbar-small pull-right">
                            <button class="btn btn-primary btn-xs bottom10" onclick="open_document_status()"><?php echo $this->lang->line('crm_add_new_status');?>
                            </button><!--Add New Status-->
                        </div>
                    </div>


                    <div class="post-area">
                        <article class="page-content">
                            <div class="system-settings">
                                <form class="form-horizontal" role="form">
                                    <div class="col-md-9">
                                        <label for="inputStatus" class="col-md-2 control-label"><b><i
                                                        class="fa fa-filter"></i>Filter</b></label><!--Document-->
                                        <div class="col-md-4">
                                            <?php echo form_dropdown('id', $all_crm_status_arr, '', 'onchange="fetch_doc_status()" class="form-control" id="id"'); ?>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                </form>


                                <table id="usersTable" class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo $this->lang->line('common_document');?></th><!--Document-->
                                        <th><?php echo $this->lang->line('common_status');?></th><!--Status-->
                                        <th><?php echo $this->lang->line('crm_backgroud_color');?></th><!--Background Color-->
                                        <th><?php echo $this->lang->line('crm_text_color');?></th><!--Text Color-->
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

    <!-- Modal -->
    <div id="add-user-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add New Status </h4><!--Add New User-->
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="crm_documentStatus">

<input type="hidden" id="statusID" name="statusID">
                        <!-- Select Basic -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="selectbasic"><?php echo $this->lang->line('common_document');?> </label><!--Document-->
                            <div class="col-md-6" id="">
                                <?php echo form_dropdown('documentID', $all_crm_status_arr, '', 'class="form-control" id="documentID""'); ?>


                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="selectbasic"><?php echo $this->lang->line('common_status');?> </label><!--Status-->
                            <div class="col-md-6" id="">
                                <input type="text" id="status" name="status" class="form-control">


                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="selectbasic"><?php echo $this->lang->line('crm_backgroud_color');?> </label><!--Background Color-->
                            <div class="col-md-6" id="">
                                <div id="cp2" class="input-group colorpicker-component">
                                    <input type="text" readonly id="backgroundColor"  name="backgroundColor" value="#000000" class="form-control"/>
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="selectbasic"><?php echo $this->lang->line('crm_status_color');?>  </label><!--Status color-->
                            <div class="col-md-6" id="">
                                <div id="cp3" class="input-group colorpicker-component">
                                    <input type="text" readonly id="color"  name="color" value="#000000" class="form-control"/>
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </div>
                        </div>


                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="singlebutton"></label>
                            <div class="col-md-4">
                                <button type="button" id="singlebutton" onclick="submitstatus()" name="singlebutton"
                                        class="btn btn-primary btn-xs"> <?php echo $this->lang->line('common_submit');?>
                                </button><!--Submit-->
                            </div>
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('common_Close');?></button><!--Close-->
                </div>
            </div>

        </div>
    </div>

    <script>
        $('#cp2').colorpicker();
        $('#cp3').colorpicker();
        fetch_doc_status();
        function open_document_status(){
            $('#add-user-modal').modal('show');

            $('#documentID').val('');
            $('#status').val('');
            $('#statusID').val('');
            $('#color').colorpicker('setValue', '')
        }

        function editDocumentStatus(statusID) {
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: {'statusID': statusID},
                url: "<?php echo site_url('Crm/get_alldocumentStatus'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    $('#add-user-modal').modal('show');
                    $('#documentID').val(data['documentID']);
                    $('#status').val(data['description']);
                    if(data['color'] !=''){
                        $('#color').colorpicker('setValue', data['statusColor'])
                    }else{
                        $('#color').colorpicker('setValue', '')
                    }
                    if(data['backgroundColor'] !=''){
                        $('#backgroundColor').colorpicker('setValue', data['statusBackgroundColor'])
                    }else{
                        $('#backgroundColor').colorpicker('setValue', '')
                    }
                    $('#statusID').val(data['statusID']);


                }, error: function () {
                    swal("Cancelled", "Your file is safe :)", "error");
                }
            });
        }

        function deleteDocumentStatus(statusID) {
            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                    text: "<?php echo $this->lang->line('common_you_want_to_delete');?>",/*You want to delete this record!*/
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $this->lang->line('common_delete');?>",/*Delete*/
                    cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
                },
                function () {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {'statusID': statusID},
                        url: "<?php echo site_url('Crm/deleteDocumentStatus'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            refreshNotifications(true);
                            stopLoad();
                            myAlert('s', 'Deleted Successfully');
                            fetch_doc_status();
                           // loaduserDropDown();
                        }, error: function () {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }


        function fetch_doc_status() {
            var Otable = $('#usersTable').DataTable({
                "bProcessing": true,
                "bServerSide": true,
                "bDestroy": true,
                "bStateSave": true,
                "sAjaxSource": "<?php echo site_url('Crm/fetch_doc_status'); ?>",
                "aaSorting": [[0, 'desc']],
                "fnInitComplete": function () {

                },
                "fnDrawCallback": function (oSettings) {
                    $("[rel=tooltip]").tooltip();
                    if (oSettings.bSorted || oSettings.bFiltered) {
                        for (var i = 0, iLen = oSettings.aiDisplay.length; i < iLen; i++) {
                            $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html(i + 1);
                        }
                    }
                },

                "columnDefs": [
                    {"width": "2%", "targets": 0},
                    {"width": "6%", "targets": 1},
                    {"width": "6%", "targets": 2},
                    {"width": "2%", "targets": 3},
                    {"width": "2%", "targets": 4},
                    {"width": "2%", "targets": 5}
                ],
                "aoColumns": [
                    {"mData": "statusID"},
                    {"mData": "document"},
                    {"mData": "description"},
                    {"mData": "backgroundColor"},
                    {"mData": "color"},
                    {"mData": "edit"}

                ],
                //"columnDefs": [{"targets": [2], "orderable": false}],
                "fnServerData": function (sSource, aoData, fnCallback) {
                    //aoData.push({ "name": "filter","value": $(".pr_Filter:checked").val()});
                    aoData.push({"name": "masterID", "value": $('#id').val()});
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


        function submitstatus() {
            var data = $('#crm_documentStatus').serializeArray();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('crm/create_document_status'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    if (data[0] == 's') {
                        $('#add-user-modal').modal('hide');
                        $('#documentID').val('');
                        $('#status').val('');
                        $('#statusID').val('');
                        $('#color').colorpicker('setValue', '')
                    }
                    myAlert(data[0], data[1]);

                    fetch_doc_status();
                    stopLoad();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    myAlert('e', '<br>Message: ' + errorThrown);
                }
            });
        }
    </script>