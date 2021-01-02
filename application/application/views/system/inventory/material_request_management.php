<?php

$primaryLanguage = getPrimaryLanguage();
$this->lang->load('inventory', $primaryLanguage);

$this->lang->load('common', $primaryLanguage);
$title = $this->lang->line('transaction_material_issue');
echo head_page('Material Request', true);
$location_arr       = all_delivery_location_drop(false);
$date_format_policy = date_format_policy();
?>
<div id="filter-panel" class="collapse filter-panel">
    <div class="row">
        <div class="form-group col-sm-4">
            <div class="custom_padding">
                <label for="supplierPrimaryCode">Date </label><br><!--Date-->
                <label for="supplierPrimaryCode">From </label>
                <input type="text" name="IncidateDateFrom" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" size="16" onchange="Otable.draw()" value="" id="IncidateDateFrom"
                       class="input-small">
                <label for="supplierPrimaryCode">&nbsp&nbsp<?php echo $this->lang->line('common_to');?> &nbsp&nbsp</label><!--To-->
                <input type="text" name="IncidateDateTo" data-inputmask="'alias': '<?php echo $date_format_policy ?>'" size="16" onchange="Otable.draw()" value="" id="IncidateDateTo"
                       class="input-small">
            </div>

        </div>
        <div class="form-group col-sm-4">
            <label for="supplierPrimaryCode">Warehouse Location </label><br>
            <?php echo form_dropdown('location[]', $location_arr, '', 'class="form-control" id="location" onchange="Otable.draw()" multiple="multiple"'); ?>
        </div>
        <div class="form-group col-sm-4">
            <label for="supplierPrimaryCode">Status </label><br>

            <div style="width: 60%;">
                <?php echo form_dropdown('status', array('all' =>'All', '1' => 'Draft', '2' =>'Confirmed', '3' =>'Approved'), '', 'class="form-control" id="status" onchange="Otable.draw()"'); ?></div>
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
                    <td><span class="label label-success">&nbsp;</span> Confirmed / Approved

                    </td>
                    <td><span class="label label-danger">&nbsp;</span> Not Confirmed
                        /  Not Approved
                    </td>
                    <td><span class="label label-warning">&nbsp;</span>  Refer-back
                    </td>
                </tr>
            </table>
    </div>
    <div class="col-md-4 text-center">
        &nbsp; 
    </div>
    <div class="col-md-3 text-right">
        <button type="button" class="btn btn-primary pull-right" onclick="fetchPage('system/inventory/erp_material_request',null,'Add New Material Request','MR');"><i class="fa fa-plus"></i> Create Material Request</button>
    </div>
</div><hr>
<div class="table-responsive">
    <table id="material_request_master_table" class="<?php echo table_class(); ?>">
        <thead>
            <tr>
                <th style="min-width: 5%">#</th>
                <th style="min-width: 15%">MR Code</th>
                <th style="min-width: 40%">Details </th>
                <!--<th style="min-width: 15%">Total Value </th>-->
                <th style="min-width: 5%">Confirmed </th>
                <th style="min-width: 5%">Approved </th>
                <th style="min-width: 120px;">Action </th>
            </tr>
        </thead>
    </table>
</div>
<?php echo footer_page('Right foot','Left foot',false); ?>
<script type="text/javascript">
var grvAutoID;
var Otable;
$(document).ready(function() {
    $('.headerclose').click(function(){
        fetchPage('system/inventory/material_request_management','Test','Material Request');
    });
    grvAutoID = null;
    number_validation();
    material_request_table();

    $('#location').multiselect2({
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true,
        numberDisplayed: 1,
        buttonWidth: '180px',
        maxHeight: '30px'
    });

    Inputmask().mask(document.querySelectorAll("input"));
});

function material_request_table(selectedID=null){
     Otable = $('#material_request_master_table').DataTable({
         "language": {
             "url": "<?php echo base_url("plugins/datatables/i18n/$primaryLanguage.json") ?>"
         },
        "bProcessing": true,
        "bServerSide": true,
        "bDestroy": true,
        "bStateSave": true,
        "sAjaxSource": "<?php echo site_url('Inventory/fetch_material_request'); ?>",
        "aaSorting": [[0, 'desc']],
        "fnInitComplete": function () {

        },
        "fnDrawCallback": function (oSettings) {
            $("[rel=tooltip]").tooltip();
            var selectedRowID = (selectedID == null)? parseInt('<?php echo $this->input->post('page_id'); ?>') : parseInt(selectedID);
            var tmp_i   = oSettings._iDisplayStart;
            var iLen    = oSettings.aiDisplay.length;
            var x = 0;
            for (var i = tmp_i; (iLen + tmp_i) > i; i++) {
                $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[x]].nTr).html(i + 1);
                if( parseInt(oSettings.aoData[x]._aData['itemIssueAutoID']) == selectedRowID ){
                    var thisRow = oSettings.aoData[oSettings.aiDisplay[x]].nTr;
                    $(thisRow).addClass('dataTable_selectedTr');
                }
                x++;
            }
            $('.deleted').css('text-decoration', 'line-through');
            $('.deleted div').css('text-decoration', 'line-through');
        },
        "aoColumns": [
            {"mData": "mrAutoID"},
            {"mData": "MRCode"},
            {"mData": "MR_detail"},
            {"mData": "confirmed"},
            {"mData": "approved"},
            {"mData": "edit"},
            {"mData": "wareHouseDescription"},
            {"mData": "employeeName"},
            {"mData": "requestedDate"},
            {"mData": "itemType"},
            {"mData": "tot_value"}
        ],
        //"columnDefs": [{"targets": [2], "orderable": false}],
        "columnDefs": [{"targets": [5], "orderable": false},{"visible":false,"searchable": true,"targets": [6,7,8,9,10] }],
        "fnServerData": function (sSource, aoData, fnCallback) {
            aoData.push({"name": "datefrom", "value": $("#IncidateDateFrom").val()});
            aoData.push({"name": "dateto", "value": $("#IncidateDateTo").val()});
            aoData.push({"name": "status", "value": $("#status").val()});
            aoData.push({"name": "location", "value": $("#location").val()});
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

function delete_item(id,value){
        swal({
                title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                text: "<?php echo $this->lang->line('common_you_want_to_delete');?>",/*You want to delete this record!*/
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "<?php echo $this->lang->line('common_delete');?>",/*Delete*/
                cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
        },
        function () {
            $.ajax({
                async : true,
                type : 'post',
                dataType : 'json',
                data : {'mrAutoID':id},
                url :"<?php echo site_url('Inventory/delete_material_request_header'); ?>",
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

// function show_addon(id){
//     //$('#addon_form')[0].reset();
//     //$('#addon_form').bootstrapValidator('resetForm', true);
//     grvAutoID = id;
//     fetch_addons(id);
// }

// function fetch_addons(id){
    
// }

// function addon_form_reset(){
//     $('#description').val('');
//     $('#uom').val('Each');
//     $('#qty').val(0);
//     $('#supplier').val('');
//     $('#unit_cost').val(0);
//     $('#sub_total').html(0.00);
// }

// $(".number").keyup(function(){
//     var qty         = parseFloat($('#qty').val());
//     var unit_cost   = parseFloat($('#unit_cost').val());
//     $('#sub_total').html(parseFloat(qty*unit_cost).toFixed(2));
// });

    function referbackgrv(id){
        swal({
                title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
                text: "<?php echo $this->lang->line('common_you_want_to_refer_back');?>",/*You want to refer back!*/
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<?php echo $this->lang->line('common_yes');?>",/*Yes!*/
                cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
            },
            function () {
                $.ajax({
                    async : true,
                    type : 'post',
                    dataType : 'json',
                    data : {'mrAutoID':id},
                    url :"<?php echo site_url('Inventory/referback_materialrequest'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success : function(data){
                        stopLoad();
                        myAlert(data[0], data[1]);
                        if (data[0] == 's') {
                            Otable.draw();
                        }
                    },error : function(){
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

function clear_all_filters(){
    $('#IncidateDateFrom').val("");
    $('#IncidateDateTo').val("");
    $('#status').val("all");
    $('#location').multiselect2('deselectAll', false);
    $('#location').multiselect2('updateButtonText');
    Otable.draw();
}

function reOpen_contract(id){
    swal({
            title: "<?php echo $this->lang->line('common_are_you_sure');?>",/*Are you sure?*/
            text: "<?php echo $this->lang->line('common_you_want_to_re_open');?>",/*You want to re open!*/
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "<?php echo $this->lang->line('common_yes');?>",/*Yes!*/
            cancelButtonText: "<?php echo $this->lang->line('common_cancel');?>"
        },
        function () {
            $.ajax({
                async : true,
                type : 'post',
                dataType : 'json',
                data : {'mrAutoID':id},
                url :"<?php echo site_url('Inventory/re_open_material_request'); ?>",
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
</script>