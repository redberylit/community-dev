<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('dashboard', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
?>
<div class="box box-success">
    <div class="box-header with-border">
        <h4 class="box-title"><?php echo $this->lang->line('dashboard_shortcut_links');?><!--Shortcut Links--></h4>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                    class="fa fa-minus"></i>
            </button>
            <button type="button" onclick="openPublicLinkModal<?php echo $userDashboardID; ?>()" title="Add Links" class="btn btn-box-tool"><i
                    class="fa fa-plus-square-o"></i>
            </button>
        </div>
        <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body" style="display: block;width: 100%">
        <ul class="todo-list">

            <?php
            if (!empty($publiclist)) {
                foreach ($publiclist as $val) {
                    ?>
                    <li id="pbLink_<?php echo $val['linkID'] ?>_<?php echo $userDashboardID; ?>" style="padding: 6px;">
                  <span class="">
                    <i class="fa fa-link"></i>
                  </span>
                <span class="text"><a onclick="fetchPage('<?php echo $val['hyperlink'] ?>','','<?php echo $val['description'] ?>')" style="cursor: pointer;"  title="<?php echo $val['title'] ?>"
                                      target=""><?php echo $val['description'] ?></a></span>
                        <div class="tools">
                            <!--<i class="fa fa-edit"></i>-->
                            <i class="fa fa-trash-o" onclick="deletePublicLink<?php echo $userDashboardID; ?>(<?php echo $val['linkID'] ?>,<?php echo $userDashboardID; ?>)"></i>
                        </div>
                    </li>
                    <?php
                }
            }
            ?>
        </ul>

    </div>
    <div class="overlay" id="overlay9<?php echo $userDashboardID; ?>"><i class="fa fa-refresh fa-spin"></i></div>
    <!-- /.box-body -->
</div>

<div class="modal fade" id="addPublicLinkModal<?php echo $userDashboardID; ?>" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width: 40%">
        <div class="modal-content" id="">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('dashboard_add_link');?><!--Add Link--></span></h4>
            </div>
            <div class="modal-body" style="margin-left: 10px">
                <?php echo form_open('', 'role="form" id="public_link_form'.$userDashboardID.'"'); ?>
                <?php
                foreach ($publiclinks as $val) {
                    $id= $val['linkID'];
                    if($val['linkID'] == $val['linkMasterID']){
                        echo '
           <div class="row">
          <div class="col-sm-12">
          <label>
              <input type="checkbox" value="' . $id . '" name="widgetCheck[]" class="minimal" checked >
              ' . $val['description'] . '
            </label>
            </div></div>';
                    }
                    else{
                        echo '
          <div class="row">
          <div class="col-sm-12">
          <label>
              <input type="checkbox" value="' . $id . '" name="widgetCheck[]" class="minimal" >
              ' . $val['description'] . '
            </label>
        </div></div>';
                    }
                }
                ?>
                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default btn-sm" type="button"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
                <button type="submit" class="btn btn-primary btn-sm" onclick="save_Public_link<?php echo $userDashboardID; ?>()" id="btnSave"><?php echo $this->lang->line('common_save');?><!--Save-->
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });
    function openPublicLinkModal<?php echo $userDashboardID; ?>() {
        //$('#public_link_form')[0].reset();
        $('#public_link_form<?php echo $userDashboardID; ?>').bootstrapValidator('resetForm', true);
        $('#addPublicLinkModal<?php echo $userDashboardID; ?>').modal("show");
    }

    function save_Public_link<?php echo $userDashboardID; ?>() {
        var data = $('#public_link_form<?php echo $userDashboardID; ?>').serializeArray();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: "<?php echo site_url('Finance_dashboard/save_public_link'); ?>",
            data: data,
            cache: false,
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                if (data[0] == 's') {
                    $('#addPublicLinkModal<?php echo $userDashboardID; ?>').modal('hide');
                    myAlert('s', 'Message: ' + data[1]);
                    location.reload();
                } else if (data[0] == 'e') {
                    myAlert('e', 'Message: ' + data[1]);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                stopLoad();
                myAlert('e', 'Message: ' + "Select Widget");
            }
        });

    }

    function deletePublicLink<?php echo $userDashboardID; ?>(id,userDashboardID) {
        if (id) {
            swal({
                    title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                    text: "<?php echo $this->lang->line('common_you_want_to_delete');?>",/*You want to delete this Record!*/
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo $this->lang->line('common_delete');?>",/*Delete*/
                    cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
                },
                function () {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: "<?php echo site_url('Finance_dashboard/deletePrivateLink'); ?>",
                        data: {linkID: id},
                        cache: false,
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            stopLoad();
                            if (data[0] == 's') {
                                $('#pbLink_' + id+'_'+userDashboardID).hide();
                                myAlert('s', 'Message: ' + data[1]);
                            } else if (data[0] == 'e') {
                                myAlert('e', 'Message: ' + data[1]);
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            stopLoad();
                            myAlert('e', 'Message: ' + "Select Widget");
                        }
                    });
                });
        };
    }


</script>
