<?php echo head_page('IOU Category', false);
$date_format_policy = date_format_policy();
$gl_code =fetch_glcode_claim_category();
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/crm/css/crm_style.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugins/crm/css/custom_style_web.css'); ?>">
<style>
    #search_cancel img {
        background-color: #f3f3f3;
        border: solid 1px #dcdcdc;
        vertical-align: middle;
        padding: 4px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
    }

    .alpha-box {
        font-size: 14px;
        line-height: 25px;
        list-style: none outside none;
        margin: 0 0 0 12px;
        padding: 0 0 0;
        text-align: center;
        text-transform: uppercase;
        width: 24px;
    }

    ul, ol {
        padding: 0;
        margin: 0 0 10px 25px;
    }

    .alpha-box li a {
        text-decoration: none;
        color: #555;
        padding: 4px 8px 4px 8px;
    }

    .alpha-box li a.selected {
        color: #fff;
        font-weight: bold;
        background-color: #4b8cf7;
    }

    .alpha-box li a:hover {
        color: #000;
        font-weight: bold;
        background-color: #ddd;
    }
</style>
<div id="filter-panel" class="collapse filter-panel">
</div>
<div class="row">
    <div class="col-md-5">
    </div>
    <div class="col-md-4 text-center">
        &nbsp;
    </div>
    <div class="col-md-3 text-right">
        <button type="button" class="btn btn-primary pull-right"
                onclick="add_iou_catergory()"><i class="fa fa-plus"></i> IOU Category
        </button>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="box-body no-padding">
            <div class="row">
                <div class="col-sm-12">
                    <div id="iou_catergory_view"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="ioucategorymodal"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">IOU Category</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('', 'role="form" id="iou_category_form"'); ?>

                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-3">
                        <label class="title">Description</label>
                    </div>
                    <div class="form-group col-sm-8">
                        <input type="text" name="Description" id="Description" class="form-control"
                               placeholder="Description">
                        <input type="hidden" class="form-control" name="expenseClaimCategoriesAutoID" id="expenseClaimCategoriesAutoID">
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="form-group col-sm-3">
                        <label class="title">GL Code</label>
                    </div>
                    <div class="form-group col-sm-6">
                        <?php echo form_dropdown('glcode', $gl_code, '', 'class="form-control select2" id="glcode"'); ?>
                    </div>
                </div>
            </div>
            </form>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" onclick="save_iou_category()" id="save_btn">Save</button>
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php echo footer_page('Right foot', 'Left foot', false); ?>
<script type="text/javascript">
    var Otable;
    $(document).ready(function () {
        $('.select2').select2();
        $('.headerclose').click(function () {
            fetchPage('system/iou/iou_category', '', 'IOU Category');
        });
        getioucategory_tableView();

    });

    function getioucategory_tableView() {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'html',
            data: {},
            url: "<?php echo site_url('iou/iou_categorymaster_view'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                $('#iou_catergory_view').html(data);
                stopLoad();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }


    function delete_iou_category(id) {
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
                    data: {'expenseClaimCategoriesAutoID': id},
                    url: "<?php echo site_url('iou/delete_ioucategory'); ?>",
                    beforeSend: function () {
                        startLoad();
                    },
                    success: function (data) {
                        stopLoad();
                        myAlert('s', 'IOU Category Deleted Successfully');
                        getioucategory_tableView();
                    }, error: function () {
                        swal("Cancelled", "Your file is safe :)", "error");
                    }
                });
            });
    }

    function save_iou_category() {

        var data = $('#iou_category_form').serializeArray();
        data.push({'name': 'gldes', 'value': $('#glcode option:selected').text()});
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: data,
            url: "<?php echo site_url('iou/save_iou_category'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0], data[1]);
                if (data[0] == 's') {
                    getioucategory_tableView();
                    $('#ioucategorymodal').modal('hide');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                myAlert('e', '<br>Message: ' + errorThrown);
            }
        });
    }

    function edit_iou_catergory(expenseClaimCategoriesAutoID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'expenseClaimCategoriesAutoID': expenseClaimCategoriesAutoID},
            url: "<?php echo site_url('iou/iou_categoryheader'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {

                if (!jQuery.isEmptyObject(data)) {
                    $('#Description').val(data['claimcategoriesDescription']);
                    $('#expenseClaimCategoriesAutoID').val(data['expenseClaimCategoriesAutoID']);
                    $('#glcode').val(data['glAutoID']).change();
                    $("#ioucategorymodal").modal({backdrop: "static"});
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

    function add_iou_catergory(){
        $('#Description').val('');
        $('#glcode').val(null).trigger("change");
        $('#expenseClaimCategoriesAutoID').val('');
        $("#ioucategorymodal").modal({backdrop: "static"});
    }


</script>