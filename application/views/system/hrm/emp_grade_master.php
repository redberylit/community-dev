<!--Translation added by Naseek-->
<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('bank_master', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);

$title = $this->lang->line('bank_employees_bank_master');
echo head_page($this->lang->line('new_bank_employees_employeegrademaster'), false);
?>
<div id="filter-panel" class="collapse filter-panel"></div>
<div class="row">
    <div class="col-md-7 pull-right">
        <button type="button" class="btn btn-primary btn-sm pull-right" onclick="newGrade()"><i
                    class="fa fa-plus-square"></i>&nbsp; <?php echo $this->lang->line('common_add'); ?><!-- Add -->
        </button>
    </div>
</div>
<hr>
<div class="table-responsive">
    <table id="empGradeTB" class="<?php echo table_class(); ?>">
        <thead>
        <tr>
            <th style="min-width: 5%">#</th>
            <th style="min-width: 25%"><?php echo $this->lang->line('common_grade') ?></th>
            <th style="min-width: 5%"></th>
        </tr>
        </thead>
    </table>
</div>
<?php echo footer_page('Right foot', 'Left foot', false); ?>


<div class="modal fade" id="gradeModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title gradeMaster-title" id="myModalLabel">New Grade</h4>
            </div>
            <?php echo form_open('', 'role="form" class="form-horizontal" id="gradeMaster_form"'); ?>
            <input type="hidden" name="gradeID" id="gradeID"/>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="gradeDescription"><?php echo $this->lang->line('common_grade') ?> <?php required_mark(); ?></label>
                    <div class="col-sm-6">
                        <input type="text" name="gradeDescription" id="gradeDescription" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm" id="saveBtn">
                    <?php echo $this->lang->line('common_save'); ?><!--Save--></button>
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
                    <?php echo $this->lang->line('common_Close'); ?><!--Close--></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>


<script type="text/javascript">
    var gradeMaster_form = $('#gradeMaster_form');
    var oTable;
    $(document).ready(function () {
        $('.headerclose').click(function () {
            fetchPage('system/hrm/emp_bank_master', 'Test', 'HRMS');
        });

        gradeMaster_form.bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                gradeDescription: {validators: {notEmpty: {message: 'Grade is required.'}}},
            },
        }).on('success.form.bv', function (e) {
            $('.submitBtn').prop('disabled', false);
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var postData = $form.serialize();
            $.ajax({
                type: 'post',
                url: "<?php echo site_url('Employee/saveGrade') ?>",
                data: postData,
                dataType: 'json',
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    if (data['error'] == 1) {
                        myAlert('e', data['message']);
                    }
                    else if (data['error'] == 0) {
                        oTable.draw();
                        $('#gradeModal').modal('hide');
                        myAlert('s', data['message']);
                    }
                },
                error: function () {
                    stopLoad();
                    myAlert('e', 'An Error Occurred! Please Try Again.');
                }
            });
        });
        empGradeTB();
    });

    function empGradeTB() {
        oTable = $('#empGradeTB').DataTable({
            "language": {
                "url": "<?php echo base_url("plugins/datatables/i18n/$primaryLanguage.json") ?>"
            },
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "bStateSave": true,
            "sAjaxSource": "<?php echo site_url('Employee/fetch_grade'); ?>",
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
                {"mData": "gradeID"},
                {"mData": "gradeDescription"},
                {"mData": "edit"}
            ],
            "columnDefs": [{"searchable": false, "targets": [0]}],
            "fnServerData": function (sSource, aoData, fnCallback) {
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

    function newGrade() {
        $('.gradeMaster-title').text('<?php echo $this->lang->line('new_bank_employees_newgrademaster') ?>');
        gradeMaster_form[0].reset();
        gradeMaster_form.bootstrapValidator('resetForm', true);
        $("#gradeID").val('');
        $('#gradeModal').modal({backdrop: "static"});
    }

    function editGrade(gradeID,element) {
        $('.gradeMaster-title').text('<?php echo $this->lang->line('new_bank_employees_editgrademaster') ?>');
        $('#gradeID').val(gradeID);
        $('#gradeDescription').val($(element).data('description'));
        $('#gradeModal').modal({backdrop: "static"});
    }

    function deleteGrade(gradeID) {
        swal(
            {
                title: "Are you sure?",
                text: "You want to delete this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete"
            },
            function () {
                $.ajax({
                    async: true,
                    url: "<?php echo site_url('Employee/deleteGrade'); ?>",
                    type: 'post',
                    dataType: 'json',
                    data: {'gradeID': gradeID},
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        if (data['error'] == 1) {
                            myAlert('e', data['message']);
                        }
                        else if (data['error'] == 0) {
                            oTable.draw();
                            myAlert('s', data['message']);
                        }
                    }, error: function () {
                        stopLoad();
                        myAlert('e', 'error');

                    }
                });
            }
        );
    }

    $('.table-row-select tbody').on('click', 'tr', function () {
        $('.table-row-select tr').removeClass('dataTable_selectedTr');
        $(this).toggleClass('dataTable_selectedTr');
    });
</script>


<?php
/**
 * Created by PhpStorm.
 * User: Nasik
 * Date: 2017-01-23
 * Time: 3:37 PM
 */