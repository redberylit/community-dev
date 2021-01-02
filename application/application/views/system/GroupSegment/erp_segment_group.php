<?php echo head_page('Segment Group', false); ?>
<div id="filter-panel" class="collapse filter-panel"></div>
<div class="row table-responsive">
    <div class="col-md-5">

    </div>
    <div class="col-md-7 text-right">
        <button type="button" class="btn btn-primary pull-right" onclick="openaddsegementmodel()"><i
                class="fa fa-plus"></i> Create Segment
        </button>
    </div>
</div>
<hr>
<div class="table-responsive">
    <table id="group_segment_table" class="<?php echo table_class(); ?>">
        <thead>
        <tr>
            <th style="min-width: 2%">#</th>
            <th style="min-width: 40%">Description</th>
            <th style="min-width: 20%">Code</th>
            <th style="min-width: 2%">Action</th>
        </tr>
        </thead>
    </table>
</div>
<?php echo footer_page('Right foot', 'Left foot', false); ?>
<div aria-hidden="true" role="dialog" tabindex="-1" id="segment_group_modal" class=" modal fade bs-example-modal-lg"
     style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="categoryHead"></h5>
            </div>
            <?php echo form_open('', 'role="form" id="segment_group_form"'); ?>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="segmentID" name="segmentID">
                    <div class="form-group col-md-12">
                        <label>Description</label>
                        <input type="text" name="description" id="description" class="form-control">
                    </div>
                    <div class="form-group col-md-12">
                        <label>Segment Code</label>
                        <input type="text" name="segmentCode" id="segmentCode" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary " onclick="saveSegment()"><i
                            class="fa fa-plus"></i> Save
                    </button>
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="SegmentLinkModal" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width: 60%">
        <div class="modal-content" id="">
            <?php echo form_open('', 'role="form" id="segment_link_form"'); ?>
            <input type="hidden" name="groupSegmentID" id="groupSegmentID">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Segment Link <span id=""></span></h4>
            </div>
            <div class="modal-body" style="margin-left: 10px">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="customerName"><h4>Segment Name :- </h4></label>
                        <label style="color: cornflowerblue;font-size: 1.2em;" id="segemntName"></label>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row" id="loadComapnySegments">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default btn-sm" type="button">Close</button>
                <button type="submit" class="btn btn-primary btn-sm" id="btnSave">Add Link
                </button>
            </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        group_segment_table();

        $('#segment_link_form').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                //companyID: {validators: {notEmpty: {message: 'Company is required.'}}}
            },
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            $.ajax(
                {
                    async: false,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    url: "<?php echo site_url('GroupSegement/save_segment_link'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        HoldOn.close();
                        myAlert(data[0], data[1]);
                        $('#btnSave').attr('disabled', false);
                        if (data[0] == 's') {
                            /*load_segment_details_table();
                             load_company($('#groupSegmentID').val());
                             $('#companyID').val('').change();*/
                            load_all_companies_segment();
                            $('#SegmentLinkModal').modal('hide');
                        }

                    },
                    error: function () {
                        alert('An Error Occurred! Please Try Again.');
                        HoldOn.close();
                        refreshNotifications(true);
                    }
                });
        });
    });

    function group_segment_table() {
        var Otable = $('#group_segment_table').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "StateSave": true,
            "sAjaxSource": "<?php echo site_url('GroupSegement/fetch_segment_group'); ?>",
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
                {"mData": "description"},
                {"mData": "segmentCode"},
                {"mData": "edit"}
            ],
            "fnServerData": function (sSource, aoData, fnCallback) {
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

    function openaddsegementmodel() {
        $('#segmentID').val('');
        $('#categoryHead').html('Add New Segment');
        $('#segment_group_form')[0].reset();
        $('#segment_group_modal').modal('show');
    }


    function edit_group_segment(id) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'segmentID': id},
            url: "<?php echo site_url('GroupSegement/edit_group_segment'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                if (data) {
                    $('#segmentID').val(id);
                    $('#segmentCode').val(data['segmentCode']);
                    $('#description').val(data['description']);
                    $('#categoryHead').html('Edit Segment');
                    $('#segment_group_modal').modal('show');
                }
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
            }
        });
    }

    function saveSegment() {
        var data = $("#segment_group_form").serializeArray();
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('GroupSegement/saveSegment'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    group_segment_table();
                    $('#segment_group_modal').modal('hide');
                    $('#description').val('');
                    $('#segmentCode').val('');
                    $('#segmentID').val('');
                }
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                myAlert(data[0], data[1]);
            }
        });
    }

    function link_group_segment(groupSegmentID) {
        $('#SegmentLinkModal').modal({backdrop: "static"});
        $('#companyID').val('').change();
        $('#groupSegmentID').val(groupSegmentID);
        $('#btnSave').attr('disabled', false);
        /*load_company(groupSegmentID);
         load_segment_details_table();*/
        load_all_companies_segment();
        load_segment_header();
    }

    function load_company(groupSegmentID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {groupSegmentID: groupSegmentID, All: 'true'},
            url: "<?php echo site_url('GroupSegement/load_company'); ?>",
            beforeSend: function () {

            },
            success: function (data) {
                $('#loadComapny').html(data);
                $('.select2').select2();
                load_comapny_segment();
            }, error: function () {

            }
        });
    }
    function load_comapny_segment() {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {companyID: $('#companyID').val(), groupSegmentID: $('#groupSegmentID').val(), All: 'true'},
            url: "<?php echo site_url('GroupSegement/load_segment'); ?>",
            beforeSend: function () {

            },
            success: function (data) {
                $('#loadComapnySegments').html(data);
                $('.select2').select2();
            }, error: function () {

            }
        });
    }

    function load_segment_details_table() {
        Otable = $('#segment_group_details').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "StateSave": true,
            "sAjaxSource": "<?php echo site_url('GroupSegement/fetch_segment_Details'); ?>",
            "aaSorting": [[0, 'desc']],
            "searching": false,
            "bLengthChange": false,
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
            "aoColumns": [
                {"mData": "groupSegmentDetailID"},
                {"mData": "company_name"},
                {"mData": "segmentCode"},
                {"mData": "description"},
                {"mData": "edit"}
            ],
            "columnDefs": [{"targets": [4], "orderable": false}, {}],
            "fnServerData": function (sSource, aoData, fnCallback) {
                aoData.push({"name": "groupSegmentID", "value": $('#groupSegmentID').val()});
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

    function delete_segment_link(id) {
        swal({
                title: "Are you sure?",
                text: "You want to delete this link!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete"
            },
            function () {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {'groupSegmentDetailID': id},
                    url: "<?php echo site_url('GroupSegement/delete_segment_link'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1]);
                        if (data[0] == 's') {
                            load_segment_details_table();
                            load_company($('#groupSegmentID').val());
                        }

                    }, error: function () {
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    function load_all_companies_segment() {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {groupSegmentID: $('#groupSegmentID').val()},
            url: "<?php echo site_url('GroupSegement/load_all_companies_segment'); ?>",
            beforeSend: function () {

            },
            success: function (data) {
                $('#loadComapnySegments').removeClass('hidden');
                $('#loadComapnySegments').html(data);
                $('.select2').select2();
            }, error: function () {

            }
        });
    }

    function clearcustomer(id) {
        $('#segmentID_' + id).val('').change();
    }

    function load_segment_header() {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'groupSegmentID': $('#groupSegmentID').val()},
            url: "<?php echo site_url('GroupSegement/load_segment_header'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                if (!jQuery.isEmptyObject(data)) {
                    $('#segemntName').html(data['description']);
                }
                stopLoad();
                refreshNotifications(true);
            }, error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });
    }

</script>