<?php echo head_page('OVER HEAD', false);
$gl_code_arr = dropdown_all_overHead_gl();
$unit_of_messure = all_umo_new_drop();
$segment_arr = fetch_mfq_segment(true);
?>
<link href="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/datatables/customer-style-datatable.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/mfq/custom-mfq.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/buttons/button.css'); ?>" rel="stylesheet">
<script src="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.js'); ?>"></script>
<div id="filter-panel" class="collapse filter-panel"></div>
<div class="row">
    <div class="col-md-5">

    </div>
    <div class="col-md-4 text-center">
        &nbsp;
    </div>
    <div class="col-md-3 text-right">
        <button type="button" style="margin-right: 17px;" class="btn btn-primary pull-right"
                onclick="openOverHeadModal()"><i
                class="fa fa-plus"></i> New Over Head
        </button>
    </div>
</div>
<hr style="margin-top: 5px;margin-bottom: 5px;">
<div id="itemmaster">
    <div class="table-responsive">
        <table id="over_head_table" class="table table-striped table-condensed">
            <thead>
            <tr>
                <th style="min-width: 2%">#</th>
                <th style="min-width: 12%">DESCRIPTION</th>
                <th style="min-width: 12%">SEGMENT</th>
                <th style="min-width: 12%">UNIT</th>
                <th style="min-width: 12%">RATE</th>
                <th style="min-width: 12%">GL DESCRIPTION</th>
                <th style="min-width: 3%">&nbsp;</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
<?php echo footer_page('Right foot', 'Left foot', false); ?>
<div class="modal fade bs-example-modal-sm" role="dialog" aria-labelledby="myLargeModalLabel"
     id="overHeadModal">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Over Head </h4>
            </div>
            <?php echo form_open('', 'role="form" id="overhead_form"'); ?>
            <div class="modal-body">
                <input type="hidden" id="overHeadID" name="overHeadID">
                <input type="hidden" id="overHeadCategoryID" name="overHeadCategoryID" value="1">

                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-3 col-md-offset-1">
                        <label class="title">Description</label>
                    </div>

                    <div class="form-group col-sm-6">
                <span class="input-req" title="Required Field">
                    <input type="text" class="form-control " id="description" name="description" required>
                    <!--<input type="text" name="partNumber" id="partNumber" class="form-control" placeholder="Part No" >-->
                    <span class="input-req-inner"></span>
                </span>
                    </div>
                </div>

               <!-- <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-3 col-md-offset-1">
                        <label class="title">Category </label>
                    </div>

                    <div class="form-group col-sm-6">
                        <span class="input-req" title="Required Field">
                            <?php /*echo form_dropdown('overHeadCategoryID', get_overhead_categoryDrop(), '', 'class="form-control select2" id="overHeadCategoryID" required'); */?>
                            <span class="input-req-inner"></span>
                        </span>
                    </div>
                </div>-->

                <div class="row" style="margin-top: 10px;">

                    <div class="form-group col-sm-3 col-md-offset-1">
                        <label class="title">Unit Of Measure</label>
                    </div>

                    <div class="form-group col-sm-6">
                        <span class="input-req" title="Required Field">
                            <?php echo form_dropdown('unitOfMeasureID', $unit_of_messure, '', 'class="form-control select2" id="unitOfMeasureID" required'); ?>

                            <span class="input-req-inner"></span>
                        </span>
                    </div>
                </div>

                <div class="row" style="margin-top: 10px;">

                    <div class="form-group col-sm-3 col-md-offset-1">
                        <label class="title">GL Code</label>
                    </div>

                    <div class="form-group col-sm-6">
                        <span class="input-req" title="Required Field">
                            <?php echo form_dropdown('financeGLAutoID', $gl_code_arr, '', 'class="form-control select2" id="financeGLAutoID" required'); ?>
                            <span class="input-req-inner"></span>
                        </span>
                    </div>
                </div>

                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-3 col-md-offset-1">
                        <label class="title">Segment</label>
                    </div>

                    <div class="form-group col-sm-6">
                        <span class="input-req" title="Required Field">
                            <?php echo form_dropdown('mfqSegmentID', $segment_arr, '', 'class="form-control select2" id="mfqSegmentID" required'); ?>
                            <span class="input-req-inner"></span>
                        </span>
                    </div>
                </div>

                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-3 col-md-offset-1">
                        <label class="title">Rate</label>
                    </div>

                    <div class="form-group col-sm-6">
                <span class="input-req" title="Required Field">
                    <input type="text" class="number form-control" onkeypress="validateFloatKeyPress(this,event)" id="rate" name="rate" required>
                    <span class="input-req-inner"></span>
                </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-floppy-disk"
                                                                           aria-hidden="true"></span> Save
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    var oTable;
    $(document).ready(function () {
        $('.headerclose').click(function () {
            fetchPage('system/mfq/mfq_over_head', 'Test', 'Over Head');
        });
        over_head_table();

        $('#overhead_form').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                description: {validators: {notEmpty: {message: 'Description is required.'}}},
                unitOfMeasureID: {validators: {notEmpty: {message: 'Unit Of Measure is required.'}}},
                financeGLAutoID: {validators: {notEmpty: {message: 'GL Code is required.'}}},
                mfqSegmentID: {validators: {notEmpty: {message: 'Segment is required.'}}},
                rate: {validators: {notEmpty: {message: 'Rate is required.'}}}
                //overHeadCategoryID: {validators: {notEmpty: {message: 'Category is required.'}}}
            },
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            $.ajax({
                async: true,
                type: 'post',
                dataType: 'json',
                data: data,
                url: "<?php echo site_url('MFQ_OverHead/save_over_head'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1]);
                    if (data[0] == 's') {
                        $('#overHeadModal').modal('hide');
                        over_head_table();
                    } else {
                        $('#overHeadModal').modal('hide');
                        over_head_table();
                    }

                },
                error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        });

        $('.select2').select2();

    });


    function over_head_table() {
        oTable = $('#over_head_table').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "bStateSave": true,
            "sAjaxSource": "<?php echo site_url('MFQ_OverHead/fetch_over_head'); ?>",
            //"aaSorting": [[1, 'desc']],
            "fnInitComplete": function () {

            },
            "fnDrawCallback": function (oSettings) {
                var tmp_i = oSettings._iDisplayStart;
                var iLen = oSettings.aiDisplay.length;
                var x = 0;
                for (var i = tmp_i; (iLen + tmp_i) > i; i++) {
                    $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[x]].nTr).html(i + 1);
                    x++;
                }
            },
            "aoColumns": [
                {"mData": "overHeadID"},
                {"mData": "item_description"},
                {"mData": "segmentDesc"},
                {"mData": "UnitDes"},
                {"mData": "rate"},
                {"mData": "GLDescription"},
                {"mData": "edit"}
            ],
            "columnDefs": [{"targets": [5], "orderable": false}],
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

    function openOverHeadModal() {
        $('#overhead_form')[0].reset();
        $('#overhead_form').bootstrapValidator('resetForm', true);
        $('#overHeadID').val('');
        $('#overHeadModal').modal('show');
    }

    function editOverHead($overHeadID) {
        $('#overHeadID').val($overHeadID);
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {overHeadID: $overHeadID},
            url: "<?php echo site_url('MFQ_OverHead/editOverHead'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                if (data) {
                    $('#overhead_form').bootstrapValidator('resetForm', true);
                    $('#description').val(data['description']);
                    $('#unitOfMeasureID').val(data['unitOfMeasureID']).change();
                    $('#financeGLAutoID').val(data['financeGLAutoID']).change();
                    $('#overHeadCategoryID').val(data['overHeadCategoryID']).change();
                    $('#mfqSegmentID').val(data['mfqSegmentID']).change();
                    $('#rate').val(data['rate']);
                    $('#overHeadModal').modal('show');
                }

            },
            error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }
    function validateFloatKeyPress(el, evt) {
        //alert(currency_decimal);
        var charCode = (evt.which) ? evt.which : event.keyCode;
        var number = el.value.split('.');

        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        //just one dot
        if (number.length > 1 && charCode == 46) {
            return false;
        }
        //get the carat position
        var caratPos = getSelectionStart(el);
        var dotPos = el.value.indexOf(".");
        if (caratPos > dotPos && dotPos > -(currency_decimal - 1) && (number[1].length > (currency_decimal - 1))) {
            return false;
        }
        return true;
    }
</script>