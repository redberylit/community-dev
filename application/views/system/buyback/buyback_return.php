<?php
$this->load->helper('buyback_helper');
$farmer_arr = load_all_farms(false);
$date_format_policy = date_format_policy();
?>
<div id="salesReturnManagement_div">
    <?php

    echo head_page('Return', true);



    /*echo head_page('Sales Return', true);*/ ?>

    <div id="filter-panel" class="collapse filter-panel">
        <div class="row">
            <div class="form-group col-sm-4">
                <div class="custom_padding">
                    <label for="customerPrimaryCode"><?php echo $this->lang->line('common_date');?> </label><br><!--Date-->
                    <label for="customerPrimaryCode"><?php echo $this->lang->line('common_from');?></label><!--From-->
                    <input type="text" name="IncidateDateFrom"
                           data-inputmask="'alias': '<?php echo $date_format_policy ?>'" size="16"
                           onchange="Otable.draw()" value="" id="IncidateDateFrom"
                           class="input-small">
                    <label for="customerPrimaryCode">&nbsp&nbsp<?php echo $this->lang->line('sales_markating_transaction_to');?> &nbsp&nbsp</label><!--To-->
                    <input type="text" name="IncidateDateTo"
                           data-inputmask="'alias': '<?php echo $date_format_policy ?>'" size="16"
                           onchange="Otable.draw()" value="" id="IncidateDateTo"
                           class="input-small">

                </div>

            </div>
            <div class="form-group col-sm-4">
                <label for="customerPrimaryCode">Farmer Name </label><br><!--Customer Name-->
                <?php echo form_dropdown('farmer[]', $farmer_arr, '', 'class="form-control" id="farmer" onchange="Otable.draw()" multiple="multiple" style="height: 30px"'); ?>
            </div>
            <div class="form-group col-sm-4">
                <label for="customerPrimaryCode"><?php echo $this->lang->line('common_status');?>  </label><br><!--Status-->

                <div style="width: 60%;">
                    <?php echo form_dropdown('status', array('all' => 'All', '1' => 'Draft', '2' => 'Confirmed', '3' =>'Approved'), '', 'class="form-control" id="status" onchange="Otable.draw()"'); ?></div>
                <button type="button" class="btn btn-primary pull-right"
                        onclick="clear_all_filters()" style="margin-top: -10%;"><i class="fa fa-paint-brush"></i>Clear
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <table class="<?php echo table_class(); ?>">
                <tr>
                    <td><span class="label label-success">&nbsp;</span> <?php echo $this->lang->line('common_confirmed');?> / <?php echo $this->lang->line('common_approved');?>

                    </td><!--Confirmed--><!--Approved-->
                    <td><span class="label label-danger">&nbsp;</span> <?php echo $this->lang->line('common_not_confirmed');?>
                        / <?php echo $this->lang->line('common_not_approved');?>
                    </td><!--Not Confirmed--><!-- Not Approved-->
                    <td><span class="label label-warning">&nbsp;</span> <?php echo $this->lang->line('common_refer_back');?>
                    </td><!--Refer-back-->
                </tr>
            </table>
        </div>
        <div class="col-md-4 text-center">
            &nbsp;
        </div>
        <div class="col-md-3 text-right">
            <button type="button" class="btn btn-primary pull-right"
                    onclick="fetchPage('system/buyback/create_buyback_return',null,'Add New Return','BSR');"><i
                    class="fa fa-plus"></i> Create Return
            </button><!--Create Sales Return-->
        </div>
    </div>
    <hr>
    <div class="table-responsive">
        <table id="buyback_return_table" class="<?php echo table_class(); ?>">
            <thead>
            <tr>
                <th style="min-width: 5%">#</th>
                <th style="min-width: 15%">Code</th>
                <th>Farm</th>
                <th style="min-width: 15%">Warehouse</th>
                <th style="min-width: 10%">Date</th>
                <th style="min-width: 5%">Confirmed </th>
                <th style="min-width: 5%">Approved</th>
                <th style="width:120px;">Action </th>
            </tr>
            </thead>
        </table>
    </div>
    <?php echo footer_page('Right foot', 'Left foot', false); ?>
</div>

<div id="salesReturnCreateNew_div">

</div>


<script type="text/javascript">
    /**

     function hideAllDiv() {
        $("#salesReturnCreateNew_div").hide();
        $("#salesReturnManagement_div").hide();
    }
     function createNewSalesReturn(id) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {'salesReturnID': id},
            url: "<?php echo site_url('Inventory/createNewSalesReturn'); ?>",
            beforeSend: function () {
                startLoad();
                hideAllDiv();
            },
            success: function (data) {
                stopLoad();
                $("#salesReturnManagement_div").show();
                $("#salesReturnManagement_div").html(data);

            }, error: function () {
                $("#salesReturnManagement_div").html('<div class="alert alert-danger">An error has occured</div>');
            }
        });
    }*/

    var grvAutoID;
    var Otable;
    $(document).ready(function () {
        $('.headerclose').click(function () {
            /* fetchPage('system/inventory/stock_return_management', 'Test', 'Purchase Return');*/
            fetchPage('system/invoices/sales_return', '', 'Sales Return ')
        });
        grvAutoID = null;
        number_validation();
        buyback_return_table();

        $('#farmer').multiselect2({
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            numberDisplayed: 1,
            buttonWidth: '180px',
            maxHeight: '30px'
        });

        Inputmask().mask(document.querySelectorAll("input"));
    });

    function buyback_return_table(selectedID=null) {

        Otable = $('#buyback_return_table').DataTable({

            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "bStateSave": true,
            "sAjaxSource": "<?php echo site_url('Buyback/fetch_buyback_return_table'); ?>",
            "aaSorting": [[0, 'desc']],
            "fnInitComplete": function () {

            },
            "fnDrawCallback": function (oSettings) {
                $("[rel=tooltip]").tooltip();
                var selectedRowID = (selectedID == null) ? parseInt('<?php echo $this->input->post('page_id'); ?>') : parseInt(selectedID);
                var tmp_i = oSettings._iDisplayStart;
                var iLen = oSettings.aiDisplay.length;
                var x = 0;
                for (var i = tmp_i; (iLen + tmp_i) > i; i++) {
                    $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[x]].nTr).html(i + 1);
                    if (parseInt(oSettings.aoData[x]._aData['salesReturnAutoID']) == selectedRowID) {
                        var thisRow = oSettings.aoData[oSettings.aiDisplay[x]].nTr;
                        $(thisRow).addClass('dataTable_selectedTr');
                    }
                    x++;
                }
                $('.deleted').css('text-decoration', 'line-through');
                $('.deleted div').css('text-decoration', 'line-through');
            },
            "aoColumns": [
                {"mData": "returnAutoID"},
                {"mData": "documentSystemCode"},
                {"mData": "description"},
                {"mData": "returnwarehouse"},
                /*{"mData": "transactionCurrency"},*/
                {"mData": "documentDate"},
                {"mData": "confirmed"},
                {"mData": "approved"},
                {"mData": "edit"},
                {"mData": "wareHouseLocation"}
                //{"mData": "edit"},
            ],
            //"columnDefs": [{"targets": [2], "orderable": false}],
            "columnDefs": [{"targets": [7], "orderable": false}, {
                "visible": false,
                "searchable": true,
                "targets": [8]
            }],
            "fnServerData": function (sSource, aoData, fnCallback) {
                aoData.push({"name": "datefrom", "value": $("#IncidateDateFrom").val()});
                aoData.push({"name": "dateto", "value": $("#IncidateDateTo").val()});
                aoData.push({"name": "status", "value": $("#status").val()});
                aoData.push({"name": "customerPrimaryCode", "value": $("#customerPrimaryCode").val()});
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

    $('.table-row-select tbody').on('click', 'tr', function () {
        $('.table-row-select tr').removeClass('dataTable_selectedTr');
        $(this).toggleClass('dataTable_selectedTr');
    });


    function delete_item(id, value) {
        swal({
                title: "Are you sure?",
                text: "You want to delete this record!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel"
            },
            function () {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {'returnAutoID': id},
                    url: "<?php echo site_url('Buyback/delete_return'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        Otable.draw();
                        stopLoad();
                        refreshNotifications(true);
                    }, error: function () {
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }



    function clear_all_filters() {
        $('#IncidateDateFrom').val("");
        $('#IncidateDateTo').val("");
        $('#status').val("all");
        $('#customerPrimaryCode').multiselect2('deselectAll', false);
        $('#customerPrimaryCode').multiselect2('updateButtonText');
        Otable.draw();
    }

    function reOpen_contract(id){
        swal({
                title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                text: "<?php echo $this->lang->line('sales_markating_transaction_you_want_to_re_open');?>",/*You want to re open!*/
                type: "warning",/*warning*/
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<?php echo $this->lang->line('common_yes');?>",/*Yes*/
                cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
            },
            function () {
                $.ajax({
                    async : true,
                    type : 'post',
                    dataType : 'json',
                    data : {'returnAutoID':id},
                    url :"<?php echo site_url('Buyback/re_open_buyback'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success : function(data){
                        Otable.draw();
                        stopLoad();
                        refreshNotifications(true);
                    },error : function(){
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }
    function referback_buyback_return(id) {
        swal({
                title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                text: "<?php echo $this->lang->line('common_you_want_to_refer_back');?>",/*You want to refer back!*/
                type: "warning",/*warning*/
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<?php echo $this->lang->line('common_yes');?>",/*Yes*/
                cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
            },
            function () {
                $.ajax({
                    async: true,
                    type: 'post',
                    dataType: 'json',
                    data: {'returnAutoID': id},
                    url: "<?php echo site_url('Buyback/referback_buyback_return'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0], data[1]);
                        if (data[0] == 's') {
                            Otable.draw();
                        }
                    }, error: function () {
                        stopLoad();
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }
</script>