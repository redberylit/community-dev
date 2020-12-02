<?php
$primaryLanguage = getPrimaryLanguage();
echo head_page('Purchasing Address',false);
$address=load_addresstype_drop();
?>
<div id="filter-panel" class="collapse filter-panel"></div>
<div class="row">
    <!-- <div class="col-md-5">
        <table class="<?php //echo table_class(); ?>">
                <tr>
                    <td><span class="label label-success">&nbsp;</span> Confirmed /
                        Approved
                    </td>
                    <td><span class="label label-danger">&nbsp;</span> Not Confirmed
                        / Not Approved
                    </td>
                    <td><span class="label label-warning">&nbsp;</span> Refer-back
                    </td>
                </tr>
            </table>
    </div> -->
    <div class="col-md-9 text-center">
        &nbsp; 
    </div>
    <div class="col-md-3 text-right">
        <button type="button" onclick="open_address_model();" class="btn btn-primary pull-right" ><i class="fa fa-plus"></i> Create Purchasing Address </button>
    </div>
</div><hr>
<div class="table-responsive">
    <table id="address_table" class="<?php echo table_class(); ?>">
        <thead>
            <tr>
                <th style="min-width: 5%">#</th>
                <th style="min-width: 10%">Type</th>
                <th style="min-width: 30%">Address Description</th>
                <th style="min-width: 15%">Contact Person</th>
                <th style="min-width: 10%">Telephone</th>
                <th style="min-width: 11%">Fax No</th>
                <th style="min-width: 11%">E-mail</th>
                <th style="min-width: 8%">Action</th>
            </tr>
        </thead>
    </table>
</div>
<?php echo footer_page('Right foot','Left foot',false); ?>
<div aria-hidden="true" role="dialog" tabindex="-1" id="address_model" class="modal fade" style="display: none;">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="purchasingAddressHead"></h3>
            </div>
            <form role="form" id="address_form" class="form-horizontal">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" class="form-control" id="addressedit" name="addressedit">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Address Type <?php required_mark(); ?></label>
                            <div class="col-sm-5">
                                <select name="addresstypeid" id="addresstypeid" class="form-control">
                                    <option value="">Select Address Type</option>
                                    <?php foreach ($address as $addres) { ?>
                                        <option value="<?php echo $addres['addressTypeID']; ?>"><?php echo $addres['addressTypeDescription'] ?></option>
                                    <?php }; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Address <?php required_mark(); ?></label>
                            <div class="col-sm-6">
                                <textarea class="form-control" rows="3" id="addressdescription" name="addressdescription"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Contact Person <?php required_mark(); ?></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="contactpersonid" name="contactpersonid">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Telephone</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-phone" aria-hidden="true"></i></div>
                                        <input type="text" class="form-control number" id="contactpersontelephone" name="contactpersontelephone">
                                    </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Fax</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-fax" aria-hidden="true"></i></div>
                                    <input type="text" class="form-control number" id="contactpersonfaxno" name="contactpersonfaxno">
                                    </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">E-mail</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                                    <input type="email" class="form-control" id="contactpersonemail" name="contactpersonemail">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                        <button type="submit" class="btn btn-primary">Save Address</button>
                    </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.headerclose').click(function(){
            fetchPage('system/address_view','Test','Purchasing Address');
        });
        fetch_address();
        number_validation();

        $('#address_form').bootstrapValidator({
            live: 'enabled',
            message: 'This value is not valid.',
            excluded: [':disabled'],
            fields: {
                addresstypeid: {validators: {notEmpty: {message: 'Address Type is required.'}}},
                addressdescription: {validators: {notEmpty: {message: 'Address Description is required.'}}},
                contactpersonid: {validators: {notEmpty: {message: 'Contact Person is required.'}}},
                //contactpersontelephone: {validators: {notEmpty: {message: 'Contact Person Telephone is required.'}}},
                //contactpersonfaxno: {validators: {notEmpty: {message: 'Person Fax No is required.'}}},
                //contactpersonemail: {validators: {notEmpty: {message: 'Person Email is required.'}}}
            },
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            var data = $form.serializeArray();
            data.push({'name' : 'addressType', 'value' : $('#addresstypeid option:selected').text() });
            $.ajax({
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    url: "<?php echo site_url('Address/save_address'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        refreshNotifications(true);
                        if(data){
                            $("#address_model").modal("hide");
                            fetch_address();
                        }
                    }, error: function () {
                    alert('An Error Occurred! Please Try Again.');
                    stopLoad();
                    refreshNotifications(true);
                }
            });
        });
    });

    function fetch_address() {
        var Otable = $('#address_table').DataTable({"language": {
            "url": "<?php echo base_url("plugins/datatables/i18n/$primaryLanguage.json") ?>"
        },
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "StateSave": true,
            "sAjaxSource": "<?php echo site_url('Address/load_address'); ?>",
            "aaSorting": [[0, 'desc']],
            "fnInitComplete": function () {

            },
            "fnDrawCallback": function (oSettings) {
                $("[rel=tooltip]").tooltip();
                var tmp_i   = oSettings._iDisplayStart;
                var iLen    = oSettings.aiDisplay.length;
                var x = 0;
                for (var i = tmp_i; (iLen + tmp_i) > i; i++) {
                    $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[x]].nTr).html(i + 1);
                    x++;
                }
            },
            "aoColumns": [
                {"mData": "addressID"},
                {"mData": "addressType"},
                {"mData": "addressDescription"},
                {"mData": "contactPerson"},
                {"mData": "contactPersonTelephone"},
                {"mData": "contactPersonFaxNo"},
                {"mData": "contactPersonEmail"},
                {"mData": "action"}
                //{"mData": "edit"},
            ],
            //"columnDefs": [{"targets": [2], "orderable": false}],
            "fnServerData": function (sSource, aoData, fnCallback) {
                //aoData.push({ "name": "filter","value": $(".pr_Filter:checked").val()});
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

    $('.table-row-select tbody').on('click', 'tr', function () {
        $('.table-row-select tr').removeClass('dataTable_selectedTr');
        $(this).toggleClass('dataTable_selectedTr');
    });

    function open_address_model() {
        $('#addressedit').val('');
        $('#addresstypeid').val('');
        $('#address_form')[0].reset();
        $('#purchasingAddressHead').html('Add Purchasing Address');
        $('#address_form').bootstrapValidator('resetForm', true);
        $("#address_model").modal({backdrop: "static"});
    }


    function openaddressmodel(id){
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {id:id},
            url: "<?php echo site_url('Address/edit_address'); ?>",
            success: function (data) {
                open_address_model();
                $('#purchasingAddressHead').html('Edit Purchasing Address');
                $('#addressedit').val(id);
                $('#addresstypeid').val(data['addressTypeID']);
                $('#addressdescription').val(data['addressDescription']);
                $('#contactpersonid').val(data['contactPerson']);
                $('#contactpersontelephone').val(data['contactPersonTelephone']);
                $('#contactpersonfaxno').val(data['contactPersonFaxNo']);
                $('#contactpersonemail').val(data['contactPersonEmail']);
            }, 
            error: function () {
                alert('An Error Occurred! Please Try Again.');
            }
        });
    }

    function deleteaddress(id){
        swal({   title: "Are you sure?",
            text: "You want to delete this file !",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Delete",
            closeOnConfirm: true },
            function(){
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    data: {id:id},
                    url: "<?php echo site_url('Address/delete_address'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        refreshNotifications(true);
                        if(data){
                            fetch_address();
                            //fetchPage('system/srp_address_view','Test','Address');
                        }
                    }, 
                    error: function () {
                        alert('An Error Occurred! Please Try Again.');
                        stopLoad();
                        refreshNotifications(true);
                    }
                });
            });
    }
</script>