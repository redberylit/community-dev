<?php
echo head_page('Terms and Conditions', true);
$date_format_policy = date_format_policy();
$current_date = current_format_date();
$supplier_arr = all_supplier_drop(false); ?>
<link rel="stylesheet" href="<?php echo base_url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'); ?>" />
<div id="filter-panel" class="collapse filter-panel">
    <div class="row">
        <div class="form-group col-sm-4">
            <select class="form-control" id="documentIDfltr" name="documentIDfltr" onchange="Otable.draw()">
                <option value="all">Select All</option>
                <option value="CINV">Customer Invoice</option>
                <option value="QUT">Quotation</option>
                <option value="CNT">Contract</option>
                <option value="SO">Sales Order</option>
            </select>
        </div>

        <div class="form-group col-sm-4">
            <br>
            <button type="button" class="btn btn-primary"
                    onclick="clear_all_filters()" style="margin-top: -10%;"><i class="fa fa-paint-brush"></i><?php echo $this->lang->line('common_clear');?> <!--Clear-->
            </button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-7">

    </div>
    <div class="col-md-2 text-center">
        &nbsp;
    </div>
    <div class="col-md-3 text-right">

        <!--Add Expense Claim-->
        <button type="button" class="btn btn-primary pull-right"
                onclick="openNotesModal()">
            <i class="fa fa-plus"></i>
           Create
        </button>
    </div>
</div>
<hr>
<div class="table-responsive">
    <table id="termsandconditions_table" class="<?php echo table_class() ?>">
        <thead>
        <tr>
            <th style="min-width: 2%">#</th>
            <th style="min-width: 10%">Document</th>
            <th style="min-width: 50%">Description</th>
            <th style="min-width: 5%">Is Default</th>
            <th style="min-width: 5%">Action</th>
        </tr>
        </thead>
    </table>
</div>
<?php echo footer_page('Right foot', 'Left foot', false); ?>


<div aria-hidden="true" role="dialog" id="note_modal" class="modal fade"
     style="display: none;">
    <div class="modal-dialog modal-lg" style="width: 60%;">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Add Notes</h5>
            </div>
            <div class="modal-body">
                <form role="form" id="notes_form" class="form-group">
                    <input type="hidden" id="autoIDhn" name="autoIDhn">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group col-sm-4">
                                <label for="">
                                    Document ID <?php required_mark(); ?>
                                </label>
                                <select class="form-control" id="documentID" name="documentID">
                                    <option value="">Please Select</option>
                                    <option value="CINV">Customer Invoice</option>
                                    <option value="QUT">Quotation</option>
                                    <option value="CNT">Contract</option>
                                    <option value="SO">Sales Order</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="">
                                    Is Default
                                </label>
                                <div class="skin-section extraColumns"><input id="isDefault" type="checkbox" data-caption="" class="columnSelected" name="isDefault" value="1" ><label for="checkbox">&nbsp;</label></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group col-sm-12">
                                <label><?php echo $this->lang->line('common_notes');?> <?php required_mark(); ?></label><!--Notes-->
                                <textarea class="form-control" rows="3" name="description" id="description"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                <button class="btn btn-primary" type="button" onclick="saveNotes()">Save changes</button>
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

<script src="<?php echo base_url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'); ?>"></script>
<script type="text/javascript">
    var Otable;
    $(document).ready(function () {
        $('.headerclose').click(function () {
            fetchPage('system/terms_conditions/terms_condition_management', 'Test', 'Terms and conditions');
        });
        terms_and_condition_table();
        $('.extraColumns input').iCheck({
            checkboxClass: 'icheckbox_square_relative-blue',
            radioClass: 'iradio_square_relative-blue',
            increaseArea: '20%'
        });
    });
    $("#description").wysihtml5();
    function terms_and_condition_table(selectedID=null) {
        Otable = $('#termsandconditions_table').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "StateSave": true,
            "sAjaxSource": "<?php echo site_url('TermsAndConditions/fetch_terms_and_condition'); ?>",
            "aaSorting": [[5, 'desc']],
            "columnDefs": [
                {
                    "targets": [3,4],
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
                    if (parseInt(oSettings.aoData[x]._aData['autoID']) == selectedRowID) {
                        var thisRow = oSettings.aoData[oSettings.aiDisplay[x]].nTr;
                        $(thisRow).addClass('dataTable_selectedTr');
                    }
                    x++;
                }
                $('.extraColumns input').iCheck({
                    checkboxClass: 'icheckbox_square_relative-blue',
                    radioClass: 'iradio_square_relative-blue',
                    increaseArea: '20%'
                });

                $('input').on('ifChecked', function (event) {
                    change_isDefault(this.value,0);
                });

                $('input').on('ifUnchecked', function (event) {
                    change_isDefault(this.value,1);
                });
            },
            "aoColumns": [
                {"mData": "autoID"},
                {"mData": "docdescription"},
                {"mData": "description"},
                {"mData": "isDefaultChk"},
                {"mData": "edit"},
                {"mData": "documentID"}
            ],
            "columnDefs": [{"visible":false,"targets": [5] }],
            "fnServerData": function (sSource, aoData, fnCallback) {
                aoData.push({"name": "documentID", "value": $("#documentIDfltr").val()});
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

    function openNotesModal(){
        $('#note_modal').modal('show');
        $('#autoIDhn').val('');
        $('#documentID').val('');
        $('#description ~ iframe').contents().find('.wysihtml5-editor').html('');
        $('#isDefault').iCheck('uncheck');
    }

    function saveNotes(){
        var isDefault=0;
        if ($('#isDefault').is(':checked')){
            isDefault=1;
        }
        var data = $("#notes_form").serializeArray();
        data.push({'name': 'isDefault', 'value': isDefault});
        $.ajax({
            async: true,
            type: 'post',
            data: data,
            dataType: "json",
            url: "<?php echo site_url('TermsAndConditions/save_notes'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0],data[1]);
                if(data[0]=='s'){
                    $('#note_modal').modal('hide');
                    Otable.draw();
                }
            }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                stopLoad();

            }
        });
    }

    function openNoteEdit(autoID){
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'autoID': autoID},
            url: "<?php echo site_url('TermsAndConditions/get_notes_edit'); ?>",
            beforeSend: function () {
                startLoad();

            },
            success: function (data) {
                stopLoad();
                $('#autoIDhn').val(autoID);
                $('#documentID').val(data['documentID']);
                if(data['isDefault']==1){
                    $('#isDefault').iCheck('check');
                }else{
                    $('#isDefault').iCheck('uncheck');
                }
                //$('#description').val(data['description']);
                $('#description ~ iframe').contents().find('.wysihtml5-editor').html(data['description']);
                $('#note_modal').modal('show');

            }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                stopLoad();

            }
        });
    }



    function delete_notes(autoID) {
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
                    data: {'autoID': autoID},
                    url: "<?php echo site_url('TermsAndConditions/delete_notes'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert(data[0],data[1]);
                        Otable.draw();
                    }, error: function () {
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    function change_isDefault(autoID,isdefault){
        $.ajax({
            async: true,
            type: 'post',
            data: {'autoID': autoID,'isdefault': isdefault},
            dataType: "json",
            url: "<?php echo site_url('TermsAndConditions/change_isDefault'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0],data[1]);
                if(data[0]=='s'){
                    Otable.draw();
                }
            }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                stopLoad();

            }
        });
    }

    function clear_all_filters(){
        $("#documentIDfltr").val('all')
        Otable.draw();
    }



</script>