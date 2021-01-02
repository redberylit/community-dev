<?php
echo head_page('Attribute Assign', false);
$date_format_policy = date_format_policy();
$current_date = current_format_date();
$supplier_arr = all_supplier_drop(false); ?>
<div class="row">
    <div class="col-md-7">

    </div>
    <div class="col-md-2 text-center">
        &nbsp;
    </div>
    <div class="col-md-3 text-right">

        <!--Add Expense Claim-->
        <button type="button" class="btn btn-primary pull-right"
                onclick="openAttributeAssignModal()">
            <i class="fa fa-plus"></i>
           Create Attribute
        </button>
    </div>
</div>
<hr>
<div class="table-responsive">
    <table id="assignedattributes" class="<?php echo table_class() ?>">
        <thead>
        <tr>
            <th style="min-width: 4%">#</th>
            <th style="min-width: 15%">Attribute</th>
            <th style="min-width: 5%">Is Mandatory</th>
            <th style="min-width: 5%">Action</th>
        </tr>
        </thead>
    </table>
</div>
<?php echo footer_page('Right foot', 'Left foot', false); ?>


<div aria-hidden="true" role="dialog" id="attribute_assign_modal" class="modal fade"
     style="display: none;">
    <div class="modal-dialog modal-lg" style="width: 60%;">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Assign Attributes</h5>
            </div>
            <div class="modal-body">
                <form role="form" id="attribute_assign_form" class="form-horizontal">

                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                <button class="btn btn-primary" type="button" onclick="saveAssignedAttributes()">Save changes</button>
            </div>
        </div>
    </div>
</div>


<div aria-hidden="true" role="dialog" id="attribute_assign_modal_edit" class="modal fade"
     style="display: none;">
    <div class="modal-dialog modal-lg" style="width: 60%;">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Edit Attribute</h5>
            </div>
            <div class="modal-body">
                <form role="form" id="attribute_assign_form_edit" class="form-horizontal">

                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                <button class="btn btn-primary" type="button" onclick="updateAssignedAttributes()">Save changes</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    var Otable;
    $(document).ready(function () {
        $('.headerclose').click(function () {
            fetchPage('system/attribute_assign/attribute_assign_management', 'Test', 'Attribute Assign');
        });
        attribute_assign_table();
    });

    function attribute_assign_table(selectedID=null) {
        Otable = $('#assignedattributes').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "StateSave": true,
            "sAjaxSource": "<?php echo site_url('AttributeAssign/fetch_attribute_assign'); ?>",
            "aaSorting": [[0, 'desc']],
            "columnDefs": [
                {
                    "targets": [3],
                    "searchable": false
                }
            ],
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
                    if (parseInt(oSettings.aoData[x]._aData['companyAttributeID']) == selectedRowID) {
                        var thisRow = oSettings.aoData[oSettings.aiDisplay[x]].nTr;
                        $(thisRow).addClass('dataTable_selectedTr');
                    }
                    x++;
                }
            },
            "aoColumns": [
                {"mData": "companyAttributeID"},
                {"mData": "attributeDescription"},
                {"mData": "ismandatory"},
                {"mData": "edit"}
            ],
            "fnServerData": function (sSource, aoData, fnCallback) {
                //aoData.push({"name": "supplierPrimaryCode", "value": $("#supplierPrimaryCode").val()});
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

    function openAttributeAssignModal(){
        var id=0;
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {'expenseClaimDetailsID': id},
            url: "<?php echo site_url('AttributeAssign/get_attributes'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $('#attribute_assign_form').empty();
                $('#attribute_assign_form').html(data);
                $('#attribute_assign_modal').modal('show');
            }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                stopLoad();

            }
        });
    }

    function saveAssignedAttributes(){
        $.ajax({
            async: true,
            type: 'post',
            data: $("#attribute_assign_form").serialize(),
            dataType: "json",
            url: "<?php echo site_url('AttributeAssign/save_assigned_attributes'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $('#attribute_assign_modal').modal('hide');
                attribute_assign_table();
            }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                stopLoad();

            }
        });
    }

    function openAttributeAssignEdit(companyAttributeID){
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {'companyAttributeID': companyAttributeID},
            url: "<?php echo site_url('AttributeAssign/get_attributes_edit'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $('#attribute_assign_form_edit').empty();
                $('#attribute_assign_form_edit').html(data);
                $('#attribute_assign_modal_edit').modal('show');
            }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                stopLoad();

            }
        });
    }

    function updateAssignedAttributes(){
        $.ajax({
            async: true,
            type: 'post',
            data: $("#attribute_assign_form_edit").serialize(),
            dataType: "json",
            url: "<?php echo site_url('AttributeAssign/update_assigned_attributes'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                $('#attribute_assign_modal_edit').modal('hide');
                attribute_assign_table();
            }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                stopLoad();

            }
        });
    }


    function delete_attribute(companyAttributeID) {
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
                    data: {'companyAttributeID': companyAttributeID},
                    url: "<?php echo site_url('AttributeAssign/delete_attribute'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0],data[1]);
                        attribute_assign_table();
                    }, error: function () {
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }



</script>