<div class="tab-pane" id="attachments">
    <div class="row" id="show_add_files_button">
        <div class="col-md-8"><h4><i class="fa fa-hand-o-right"></i> Subtask Attachments </h4></div>
        <div class="col-md-4">
            <button type="button" onclick="show_add_file()" class="btn btn-primary pull-right"><i
                    class="fa fa-plus"></i> Add Attachments
            </button>
        </div>
    </div>
    <div class="row hide" id="add_attachemnt_show_subtask">
        <?php echo form_open_multipart('', 'id="subtask_attachment_uplode_form" class="form-inline"'); ?>
        <div class="col-sm-10" style="margin-left: 3%">
            <div class="col-sm-4">
                <div class="form-group">
                    <input type="text" class="form-control" id="subtaska_attachment_description"
                           name="subtaska_attachment_description" placeholder="Description..." style="width: 240%;">
                    <input type="hidden" class="form-control" id="documentID" name="documentID" value="10">
                    <input type="hidden" class="form-control" id="campaign_document_name" name="document_name"
                           value="SubTask">
                    <input type="hidden" class="form-control" id="subtaskid" name="documentAutoID"
                           value="<?php echo $subtaskid ?>">
                </div>
            </div>
            <div class="col-sm-8" style="margin-top: -8px;">
                <div class="form-group">
                    <div class="fileinput fileinput-new input-group" data-provides="fileinput" style="margin-top: 8px;">
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
                <button type="button" class="btn btn-default" onclick="document_uplode()"><span class="glyphicon glyphicon-floppy-open color" aria-hidden="true"></span></button>
                </form>
            </div>
        </div>
    </div>



    <?php
    if (!empty($attachment)) {
        foreach ($attachment as $row) {
            $file = base_url() . 'attachments/CRM/SubTask/'.$row['myFileName'];
            $link=generate_encrypt_link_only($file);
            ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="past-info">
                        <div id="toolbar">
                            <div class="toolbar-title">Attachments</div>
                        </div>
                        <div class="post-area">
                            <article class="post">
                                <a target="_blank" class="nopjax" href="<?php echo $link ?>">
                                    <div class="item-label file">Attachment</div>
                                </a>

                                <div class="time"><span class="hithighlight"></span></div>
                                <div class="icon">
                                    <img src="<?php echo base_url('images/crm/icon-file.png'); ?>" width="16"
                                         height="16"
                                         title="File">
                                </div>
                                <header class="infoarea">
                                    <strong class="attachemnt_title">
                                        <img src="<?php echo base_url('images/crm/icon_pic.gif'); ?>"
                                             style="vertical-align:top"> &nbsp;<a target="_blank" class="nopjax"
                                                                                  href="<?php echo $link ?>"><?php echo $row['myFileName']; ?></a>
                                        <span style="display: inline-block;"><?php echo $row['fileSize'] ?> KB</span>

                                        <div><span
                                                class="attachemnt_title"><?php echo $row['attachmentDescription'] ?></span>
                                        </div>
                                        <div><span class="attachemnt_title"
                                                   style="display: inline-block;">By: <?php echo $row['createdUserName'] ?></span>

                                           <!-- --><?php /*if($row['status'] !=3 ){*/?>
                                                <span class="deleteSpan" style="display: inline-block;"><a
                                                        onclick="delete_maintenace_attachment(<?php echo $row['attachmentID']; ?>,'<?php echo $row['myFileName']; ?>','<?php echo $subtaskid ?>');"><span
                                                            title="" rel="tooltip" class="glyphicon glyphicon-trash"
                                                            style="color:rgb(209, 91, 71);" data-original-title="Delete"></span></a></span>
                                            <?php /*}*/?>
                                        </div>
                                    </strong>
                                </header>
                            </article>
                        </div>
                    </div>
                </div>
            </div>

            <?php
        }
    } else {
        ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="past-info">
                    <div id="toolbar">
                        <div class="toolbar-title">Attachment</div>
                    </div>
                    <div class="post-area">
                        <article class="post">
                            <header class="infoarea">
                                <strong class="attachemnt_title">
                                    <span style="text-align: center;font-size: 15px;font-weight: 800;">No Attachments Found </span>
                                </strong>
                            </header>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <script>


        function document_uplode() {
            var subtaskid = $('#subtaskid').val();
            var formData = new FormData($("#subtask_attachment_uplode_form")[0]);
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: "<?php echo site_url('Crm/attachement_upload_subtask'); ?>",
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
                        $('#add_attachemnt_show_subtask').addClass('hide');
                        $('#remove_id').click();
                        $('#subtaska_attachment_description').val('');
                        sub_task_attachment_model(subtaskid);
                    }
                },
                error: function (data) {
                    stopLoad();
                    swal("Cancelled", "No File Selected :)", "error");
                }
            });
            return false;
        }
        function show_add_file() {
            $('#add_attachemnt_show_subtask').removeClass('hide');
        }
        function delete_maintenace_attachment(id, fileName,subtaskid) {
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
                        url: "<?php echo site_url('Crm/delete_subtask_attachments'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            if (data == true) {
                                myAlert('s', 'Deleted Successfully');
                                sub_task_attachment_model(subtaskid);
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

    </script>