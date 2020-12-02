<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('crm', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
?>
<style>
    .width100p {
        width: 100%;
    }

    .user-table {
        width: 100%;
    }

    .bottom10 {
        margin-bottom: 10px !important;
    }

    .btn-toolbar {
        margin-top: -2px;
    }

    table {
        max-width: 100%;
        background-color: transparent;
        border-collapse: collapse;
        border-spacing: 0;
    }
    .flex {
         display:
}
</style>


<div class="row">

    <div class="width100p">
        <section class="past-posts">
            <div class="posts-holder settings">
                <div class="past-info">
                    <div id="toolbar">
                        <div class="toolbar-title">
                            <i class="fa fa-building" aria-hidden="true"></i> Products
                        </div><!--Product Management-->
                        <div class="btn-toolbar btn-toolbar-small pull-right">
                            <button class="btn btn-primary btn-xs bottom10" data-toggle="modal"
                                   onclick="product_modal();">Add Product
                            </button>
                        </div>
                    </div>



                    <div class="post-area">
                        <article class="page-content">

                            <div class="system-settings">

                                <table id="usersTable" class="table ">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo $this->lang->line('common_description');?> </th><!--Description-->
                                        <th></th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>
    </div>

<!-- Modal -->
<div id="add-user-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('crm_add_new_user');?> </h4><!--Add New User-->
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="crm_employee">


                        <!-- Select Basic -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="selectbasic"><?php echo $this->lang->line('crm_select_an_employee');?> </label><!--Select an Employee-->
                            <div class="col-md-6" id="div_loaduser">


                            </div>
                        </div>

                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="singlebutton"></label>
                            <div class="col-md-4">
                                <button type="button" id="singlebutton" onclick="submitusers()" name="singlebutton" class="btn btn-primary btn-xs"><?php echo $this->lang->line('common_submit');?> </button><!--Submit-->
                            </div>
                        </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('common_Close');?></button><!--Close-->
            </div>
        </div>

    </div>
</div>

    <div id="add-product-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="product_title">Add Products</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="crm_product">
                        <input type="hidden" id="productid" name="productid">
                        <div class="row"  style="margin-top: 10px;">
                            <div class="form-group col-sm-4 col-md-offset-1">
                                <label class="title">Product</label>
                            </div>
                            <div class="form-group col-sm-6">
                            <span class="input-req" title="Required Field">
                    <input type="text" class="form-control " id="product" name="product" placeholder="Product" required>
                    <span class="input-req-inner"></span>
                            </span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary" onclick="submitLeadStatus();"><span class="glyphicon glyphicon-floppy-disk"
                                                                                                             aria-hidden="true"></span> Save
                    </button>
                </div>
            </div>

        </div>
    </div>

<script>

    fetch_users();

    function product_modal()
    {
        $('#product_status_heading').text('Add Products');
        $('#crm_product')[0].reset();
        $('#crm_product').bootstrapValidator('resetForm', true);
        $('#product_title').html('Add Products')
        $('#product').val('');
        $('#productid').val('');
        $('#add-product-modal').modal('show');
    }

    function deleteproduct(productID){
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
                    data : {'productID':productID},
                    url :"<?php echo site_url('Crm/delete_product'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success : function(data){
                        refreshNotifications(true);
                        stopLoad();
                        myAlert('s','Deleted Successfully');
                        fetch_users();

                    },error : function(){
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }



    function fetch_users() {
        var Otable = $('#usersTable').DataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bDestroy": true,
            "bStateSave": true,
            "sAjaxSource": "<?php echo site_url('Crm/fetch_product'); ?>",
            "aaSorting": [[1, 'desc']],
            "fnInitComplete": function () {

            },
            "fnDrawCallback": function (oSettings) {
                $("[rel=tooltip]").tooltip();
                if (oSettings.bSorted || oSettings.bFiltered) {
                    for (var i = 0, iLen = oSettings.aiDisplay.length; i < iLen; i++) {
                        $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html(i + 1);
                    }
                }
                $('.xeditable').editable();
            },

                "columnDefs": [
                {"width": "2%", "targets": 0},
                {"width": "7%", "targets": 1},
                {"width": "1%", "targets": 2},
            ],
            "aoColumns": [
                {"mData": "productID"},
                {"mData": "productName"},
                {"mData": "edit"}

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


    function submitLeadStatus() {
        var data = $('#crm_product').serializeArray();
        var product = $('#product').val();

        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('crm/srp_erp_save_product'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
              myAlert(data[0],data[1]);
                if(data[0]=='s')
                {
                    $('#product').val('');
                    $('#add-product-modal').modal('hide');
                    fetch_users();

                }

                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }
    function editproducts(productID) {
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {productID: productID},
            url: "<?php echo site_url('Crm/fetch_product_details'); ?>",
            beforeSend: function () {
                startLoad();
                $('#product_status_heading').text('Edit Add Products');
            },
            success: function (data) {
                stopLoad();
                if (!jQuery.isEmptyObject(data)) {
                    $('#crm_product').bootstrapValidator('resetForm', true);
                    $('#product').val(data['productName']);
                    $('#productid').val(data['productID']);
                    $('#add-product-modal').modal('show');
                }
            },
            error: function () {
                alert('An Error Occurred! Please Try Again.');
                stopLoad();
                refreshNotifications(true);
            }
        });

    }
</script>