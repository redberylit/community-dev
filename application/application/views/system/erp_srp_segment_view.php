<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('finance', $primaryLanguage);

$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('common_segment');
echo head_page($title, false);

/*echo head_page('Segment', false);*/

?>
<link href="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.css'); ?>" rel="stylesheet">
<script src="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.js'); ?>"></script>
<div id="filter-panel" class="collapse filter-panel"></div>
<div class="row">

    <div class="col-md-9 text-center">
        &nbsp;
    </div>
    <div class="col-md-3 text-right">
        <button type="button" class="btn btn-primary pull-right" data-toggle="modal" onclick="resetfrm()"
                data-target="#segment_model"><i class="fa fa-plus"></i> <?php echo $this->lang->line('common_create_new');?><!--Create New-->
        </button>
    </div>
</div>
<hr>
<div class="table-responsive">
    <table id="segment_table" class="<?php echo table_class() ?>">
        <thead>
        <tr>
            <th style="min-width: 10%">#</th>
            <!--<th>Company ID</th>-->
            <!--<th>#</th>-->
            <th><?php echo $this->lang->line('finance_ms_segment_code');?><!--Segment Code--></th>
            <th><?php echo $this->lang->line('common_description');?><!--Description--></th>
            <th style="min-width: 7%"><?php echo $this->lang->line('common_action');?><!--Action--></th>
            <th style="min-width: 5%"><?php echo $this->lang->line('common_status');?><!--Status--></th>
        </tr>
        </thead>
    </table>
</div>
<?php echo footer_page('Right foot', 'Left foot', false); ?>
<div aria-hidden="true" role="dialog" tabindex="-1" id="segment_model" class=" modal fade bs-example-modal-lg"
     style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="segmentHead"></h5>
            </div>
            <form role="form" id="segment_form" class="form-horizontal">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-sm-4" style="margin-left: 0px;">
                            <label for="paymentTerms"><?php echo $this->lang->line('common_description');?><!--Description--></label>
                            <textarea class="form-control" id="description" name="description" style="width:255px;"
                                      rows="2"></textarea>
                            <input type="hidden" class="form-control" id="segmentID" name="segmentID">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6" style="margin-left: 0px;">
                            <label for="paymentTerms"><?php echo $this->lang->line('finance_ms_segment_code');?><!--Segment Code--></label>
                            <input type="text" class="form-control" id="segmentcode" name="segmentcode">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary"><?php echo $this->lang->line('common_save');?><!--Save--> <span
                                class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></button>
                        <button data-dismiss="modal" class="btn btn-default" type="button"><?php echo $this->lang->line('common_Close');?><!--Close--></button>
                    </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.headerclose').click(function(){
            fetchPage('system/erp_srp_segment_view','','Segment');
        });

        segmentview();

        $('#segment_form').bootstrapValidator({
            live: 'enabled',
            message: '<?php echo $this->lang->line('common_this_value_is_not_valid');?>.',/*This value is not valid*/
            excluded: [':disabled'],
            fields: {
                segmentcode: {
                    validators: {
                        notEmpty: {
                            message: '<?php echo $this->lang->line('finance_ms_segment_code_is_required');?>'/*Segment Code is required*/
                        },
                        stringLength: {
                            max: 10,
                            message: '<?php echo $this->lang->line('finance_ms_character_must_be');?>'/*Character must be below 6 character*/
                        }

                    }
                },
                description: {validators: {notEmpty: {message: '<?php echo $this->lang->line('common_description_is_required');?>.'}}},/*Description is required*/
            },
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('Segment/save_segment'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    refreshNotifications(true);
                    if (data) {
                        $("#segment_model").modal("hide");
                        segmentview();
                    }
                }, error: function () {
                    alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        });

    });

    function segmentview() {
        var Otable = $('#segment_table').DataTable({
            "language": {
                "url": "<?php echo base_url("plugins/datatables/i18n/$primaryLanguage.json") ?>"
            },
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "sAjaxSource": "<?php echo site_url('Segment/load_segment'); ?>",
            "aaSorting": [[0, 'desc']],
            "fnInitComplete": function () {
            },
            "fnDrawCallback": function (oSettings) {
                $("[rel=tooltip]").tooltip();
                var tmp_i = oSettings._iDisplayStart;
                var iLen = oSettings.aiDisplay.length;
                var x = 0;
                for (var i = tmp_i; (iLen + tmp_i) > i; i++) {
                    $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[x]].nTr).html(i + 1);
                    x++;
                }
            },
            "aoColumns": [
                {"mData": "segmentID"},
                //{"mData": "companyID"},
                {"mData": "segmentCode"},
                {"mData": "description"},
                {"mData": "action"},
                {"mData": "status"}
            ],
            "fnServerData": function (sSource, aoData, fnCallback) {
                $.ajax
                ({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            }
        });
    }

    function edit_segmrnt(id) {
        $('#segment_form').bootstrapValidator('resetForm', true);
        $("#segment_model").modal("show");
        $('#segmentID').val(id);
        $('#segmentHead').html('Edit Segment');
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {segmentID: id},
            url: "<?php echo site_url('Segment/edit_segment'); ?>",
            success: function (data) {
                $('#description').val(data['description']);
                $('#segmentcode').val(data['segmentCode']);

            },
            error: function () {
                alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
            }
        });
    }

    function changesegmentsatus(id) {
        var compchecked = 0;
        if ($('#statusactivate_' + id).is(":checked")) {
            compchecked = 1;
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: {segmentID: id, chkedvalue: compchecked},
                url: "<?php echo site_url('Segment/update_segmentstatus'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    refreshNotifications(true);
                    if (data) {
                        segmentview();
                    }
                }, error: function () {
                    alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                    stopLoad();
                    refreshNotifications(true);
                }
            });

        }
        else if (!$('#statusactivate_' + id).is(":checked")) {

            $.ajax({
                type: 'post',
                dataType: 'json',
                data: {segmentID: id, chkedvalue: 0},
                url: "<?php echo site_url('Segment/update_segmentstatus'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    refreshNotifications(true);
                    if (data) {
                        segmentview();
                    }
                }, error: function () {
                    alert('<?php echo $this->lang->line('common_an_error_occurred_Please_try_again');?>.');/*An Error Occurred! Please Try Again*/
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        }
    }

    function resetfrm() {
        $('#segmentID').val('');
        $('#segmentHead').html('<?php echo $this->lang->line('finance_ms_add_new_segment');?>');/*Add New Segment*/
        $('#segment_form')[0].reset();
        $('#segment_form').bootstrapValidator('resetForm', true);

    }


</script>