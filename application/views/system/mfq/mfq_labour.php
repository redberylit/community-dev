<?php echo head_page('LABOUR', false);
$gl_code_arr = dropdown_all_overHead_gl();
$segment_arr = fetch_mfq_segment(true);
$unit_of_messure = all_umo_new_drop();
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
                onclick="openLabourModal()"><i
                class="fa fa-plus"></i> New Labour
        </button>
    </div>
</div>
<hr style="margin-top: 5px;margin-bottom: 5px;">
<div id="itemmaster">
    <div class="table-responsive">
        <table id="labour_table" class="table table-striped table-condensed">
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
     id="labourModal">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Labour </h4>
            </div>
            <?php echo form_open('', 'role="form" id="labour_form"'); ?>
            <div class="modal-body">
                <input type="hidden" id="overHeadID" name="overHeadID">
                <input type="hidden" id="overHeadCategoryID" name="overHeadCategoryID" value="2">

                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-3 col-md-offset-1">
                        <label class="title">Description</label>
                    </div>

                    <div class="form-group col-sm-6">
                <span class="input-req" title="Required Field">
                    <input type="text" class="form-control " id="description" name="description" required>
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
    var currency_decimal = 1;
    $(document).ready(function () {
        $('.headerclose').click(function () {
            fetchPage('system/mfq/mfq_labour', 'Test', 'Labour');
        });
        labour_table();

        $('#labour_form').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                description: {validators: {notEmpty: {message: 'Description is required.'}}},
                unitOfMeasureID: {validators: {notEmpty: {message: 'Unit Of Measure is required.'}}},
                rate: {validators: {notEmpty: {message: 'Rate is required.'}}},
                financeGLAutoID: {validators: {notEmpty: {message: 'GL Code is required.'}}},
                mfqSegmentID: {validators: {notEmpty: {message: 'Segment is required.'}}}
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
                url: "<?php echo site_url('MFQ_OverHead/save_labour'); ?>",
                beforeSend: function () {
                    startLoad();
                },
                success: function (data) {
                    stopLoad();
                    myAlert(data[0], data[1]);
                    if (data[0] == 's') {
                        $('#labourModal').modal('hide');
                        labour_table();
                    } else {
                        //$('#labourModal').modal('hide');
                        //labour_table();
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


    function labour_table() {
        oTable = $('#labour_table').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "bStateSave": true,
            "sAjaxSource": "<?php echo site_url('MFQ_OverHead/fetch_labour'); ?>",
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

    function openLabourModal() {
        $('#labour_form')[0].reset();
        $('#overHeadID').val('');
        $('#mfqSegmentID').val('').change();
        $('#unitOfMeasureID').val('').change();
        $('#financeGLAutoID').val('').change();
        $('#labour_form').bootstrapValidator('resetForm', true);
        $('#labourModal').modal('show');
    }

    function editLabour(overHeadID) {
        $('#overHeadID').val(overHeadID);
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {overHeadID: overHeadID},
            url: "<?php echo site_url('MFQ_OverHead/editOverHead'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                if (data) {
                    $('#labour_form').bootstrapValidator('resetForm', true);
                    $('#description').val(data['description']);
                    $('#unitOfMeasureID').val(data['unitOfMeasureID']).change();
                    $('#mfqSegmentID').val(data['mfqSegmentID']).change();
                    $('#rate').val(data['rate']);
                    $('#overHeadCategoryID').val(data['overHeadCategoryID']);
                    $('#financeGLAutoID').val(data['financeGLAutoID']).change();
                    $('#labourModal').modal('show');
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

    function getSelectionStart(o) {
        if (o.createTextRange) {
            var r = document.selection.createRange().duplicate()
            r.moveEnd('character', o.value.length)
            if (r.text == '') return o.value.length
            return o.value.lastIndexOf(r.text)
        } else return o.selectionStart
    }

</script>