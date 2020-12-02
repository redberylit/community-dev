<?php echo head_page('DELIVERY NOTE', false); ?>
<link href="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/datatables/customer-style-datatable.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/mfq/custom-mfq.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/buttons/button.css'); ?>" rel="stylesheet">
<script src="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.js'); ?>"></script>

<div id="filter-panel" class="collapse filter-panel"></div>
<div class="row">
    <div class="col-md-12">
        <div class=" pull-right">
            <button type="button" data-text="Add" id="btnAdd"
                    onclick="fetchPage('system/mfq/mfq_delivery_note_create',null,'Add Delivery Note','MFQ');"
                    class="btn btn-sm btn-primary">
                <i class="fa fa-plus" aria-hidden="true"></i> Add
            </button>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="tbl_delivery_note" class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th style="min-width: 5%">&nbsp;</th>
                    <th style="min-width: 12%">DELIVERY NOTE CODE</th>
                    <th style="min-width: 12%">CUSTOMER</th>
                    <th style="min-width: 12%">JOB NO</th>
                    <th style="min-width: 12%">DOCUMENT DATE</th>
                    <th style="min-width: 3%">CONFIRMATION</th>
                    <th style="min-width: 5%">&nbsp;</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<?php echo footer_page('Right foot', 'Left foot', false); ?>

<div class="modal fade" id="delivery_note_view_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     data-width="70%" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 70%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Delivery Note</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="customizeTemplateBody">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var oTable;
    $(document).ready(function () {
        $('.headerclose').click(function () {
            fetchPage('system/mfq/mfq_delivery_note', '', 'Delivery Note');
        });
        template();
    });

    function template() {
        oTable = $('#tbl_delivery_note').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "bStateSave": false,
            "sAjaxSource": "<?php echo site_url('MFQ_DeliveryNote/fetch_delivery_note'); ?>",
            "aaSorting": [[0, 'desc']],
            language: {
                paginate: {
                    previous: '‹‹',
                    next: '››'
                }
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
                {"mData": "deliverNoteID"},
                {"mData": "deliveryNoteCode"},
                {"mData": "CustomerName"},
                {"mData": "jobCode"},
                {"mData": "deliveryDate"},
                {"mData": "status"},
                {"mData": "edit"}
            ],
            "columnDefs": [
                {"targets": [5,6], "orderable": false}
            ],
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


    function referBack_delivery_note(deliverNoteID) {
        swal({
                title: "Are you sure?",
                text: "You want to refer back!",
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
                    data: {'deliverNoteID': deliverNoteID},
                    url: "<?php echo site_url('MFQ_DeliveryNote/referback_delivery_note'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1]);
                        if (data[0] == 's') {
                            template();
                        }
                    }, error: function () {
                        stopLoad();
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    function view_delivery_note(deliverNoteID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {'deliverNoteID': deliverNoteID, 'html': true},
            url: "<?php echo site_url('MFQ_DeliveryNote/load_deliveryNote_confirmation'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $('#customizeTemplateBody').html(data);
                $("#delivery_note_view_modal").modal();
                refreshNotifications(true);
            }, error: function () {
                stopLoad();
                alert('An Error Occurred! Please Try Again.');
                refreshNotifications(true);
            }
        });
    }

    function delete_delivery_note(id) {
        swal({
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
                    type: 'post',
                    dataType: 'json',
                    data: {'deliverNoteID': id},
                    url: "<?php echo site_url('MFQ_DeliveryNote/delete_delivery_note'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert('s', 'Delivery Note Deleted Successfully');
                        template();
                    }, error: function () {
                        stopLoad();
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

</script>