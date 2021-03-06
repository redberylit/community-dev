<?php echo head_page('CUSTOMER INQUIRY', false);
?>
<link href="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/datatables/customer-style-datatable.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/mfq/custom-mfq.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/buttons/button.css'); ?>" rel="stylesheet">
<script src="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.js'); ?>"></script>

<div id="filter-panel" class="collapse filter-panel"></div>
<div class="row" style="margin-top: 2%;">
    <div class="col-sm-7">
        <div class="col-sm-3">
            <?php echo form_dropdown('mfqCustomerAutoID', all_mfq_customer_drop(), '', 'class="form-control select2 filter" id="mfqCustomerAutoID"');
            ?>
        </div>
        <div class="col-sm-3">
            <?php echo form_dropdown('statusID', all_mfq_status(1), '', 'class="form-control filter" id="statusID"'); ?>
        </div>
        <div class="col-sm-1" id="search_cancel">
            <span class="tipped-top"><a id="cancelSearch" href="#"><img src="/community/images/crm/cancel-search.gif"></a></span>
        </div>
    </div>
    <div class="col-md-5 text-right">
        <button type="button" style="margin-right: 17px;" class="btn btn-primary pull-right"
                onclick="fetchPage('system/mfq/mfq_add_new_mfq',null,'Add Customer Inquiry','CI');"><i
                    class="fa fa-plus"></i> New Customer Inquiry
        </button>
    </div>
</div>
<div id="" style="margin-top: 10px">
    <div class="table-responsive">
        <table id="customer_inquiry_table" class="table table-striped table-condensed" width="100%">
            <thead>
            <tr>
                <th style="min-width: 2%">#</th>
                <th style="min-width: 12%">INQUIRY CODE</th>
                <th style="min-width: 12%">INQUIRY DATE</th>
                <th style="min-width: 12%">CLIENT</th>
                <th style="min-width: 12%">CLIENT REF NO</th>
                <th style="min-width: 12%">ACTUAL SUBMISSION DATE</th>
                <th style="min-width: 12%">PLANNED SUBMISSION DATE</th>
                <th style="min-width: 10%">INQUIRY STATUS</th>
                <th style="min-width: 12%">QUOTE STATUS</th>
                <th style="min-width: 10%">&nbsp;</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
<div class="modal fade" id="customer_inquiry_print_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     data-width="95%" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 85%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Customer Inquiry</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="print">

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
<?php echo footer_page('Right foot', 'Left foot', false); ?>


<script type="text/javascript">
    var oTable;
    $(document).ready(function () {
        $('.headerclose').click(function () {
            fetchPage('system/mfq/mfq_rfq', 'Test', 'Bill Of Material');
        });
        $("#search_cancel").hide();
        customer_inquiry_table();
        $(".filter").change(function () {
            oTable.draw();
            $("#search_cancel").show();
        });

        $("#search_cancel").click(function () {
            $(".filter").val('');
            oTable.draw();
            $(this).hide();
        });

    });

    function customer_inquiry_table() {
        oTable = $('#customer_inquiry_table').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            /*"bStateSave": true,*/
            "sAjaxSource": "<?php echo site_url('MFQ_CustomerInquiry/fetch_customerInquiry'); ?>",
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
            "columnDefs": [{"targets": [9], "orderable": false}],
            "aoColumns": [
                {"mData": "ciMasterID"},
                {"mData": "ciCode"},
                {"mData": "documentDate"},
                {"mData": "CustomerName"},
                {"mData": "referenceNo"},
                {"mData": "deliveryDate"},
                {"mData": "dueDate"},
                {"mData": "statusID"},
                {"mData": "status"},
                {"mData": "edit"}
            ],
            "fnServerData": function (sSource, aoData, fnCallback) {
                aoData.push({name: 'customerID', value: $('#mfqCustomerAutoID').val()});
                aoData.push({name: 'statusID', value: $('#statusID').val()});
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

    function viewDocument(ciMasterID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {
                ciMasterID: ciMasterID
            },
            url: "<?php echo site_url('MFQ_CustomerInquiry/fetch_customer_inquiry_print'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $("#print").html(data);
                $("#customer_inquiry_print_modal").modal();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function createEstimate(ciMasterID) {
        swal({
                title: "Are you sure?",
                text: "You want to generate estimate for this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Generate",
                closeOnConfirm: true
            },
            function () {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        ciMasterID: ciMasterID
                    },
                    url: "<?php echo site_url('MFQ_CustomerInquiry/generateEstimate'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1]);
                        if (data[0] == 's') {
                            fetchPage('system/mfq/mfq_add_new_estimate', data[2], 'Edit Estimate', 'EST');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        myAlert('e', '<br>Message: ' + errorThrown);
                    }
                });
            });
    }

    function referbackCustomerInquiry(ciMasterID) {
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
                    data: {'ciMasterID': ciMasterID},
                    url: "<?php echo site_url('MFQ_CustomerInquiry/referback_customer_inquiry'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1]);
                        if (data[0] == 's') {
                            customer_inquiry_table()
                        }
                    }, error: function () {
                        stopLoad();
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

</script>