<?php echo head_page('Manage Warehouse', false);
$mfqWarehouseAutoID = isset($page_id) && !empty($page_id) ? $page_id : 0;
?>
<link href="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/datatables/customer-style-datatable.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/mfq/custom-mfq.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('plugins/buttons/button.css'); ?>" rel="stylesheet">
<script src="<?php echo base_url('plugins/bootstrap-switch/bootstrap-switch.min.js'); ?>"></script>

<form method="post" id="form_warehouse">
    <input type="hidden" id="mfqWarehouseAutoID" name="mfqWarehouseAutoID" value="<?php echo $mfqWarehouseAutoID; ?>"/>
    <div class="row">
        <div class="col-md-12 animated zoomIn">
            <header class="head-title">
                <h2>Warehouse Detail </h2>
            </header>
            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Code </label>
                </div>
                <div class="form-group col-sm-4">
                <span class="input-req" title="Required Field">
                    <input type="text" name="warehouseCode" id="warehouseCode" class="form-control"
                           placeholder="Warehouse Code"
                           required>
                    <span class="input-req-inner"></span>
                </span>
                </div>
            </div>

            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Description </label>
                </div>
                <div class="form-group col-sm-4">
                <span class="input-req" title="Required Field">
                    <input type="text" name="warehouseDescription" id="warehouseDescription" class="form-control"
                           placeholder="Description"
                           required>
                    <span class="input-req-inner"></span>
                </span>
                </div>
            </div>

            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Location </label>
                </div>
                <div class="form-group col-sm-4">
                <span class="input-req" title="Required Field">
                    <input type="text" name="warehouseLocation" id="warehouseLocation" class="form-control"
                           placeholder="Location"
                           required>
                    <span class="input-req-inner"></span>
                </span>
                </div>
            </div>

            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Address </label>
                </div>
                <div class="form-group col-sm-4">
                    <input type="text" name="warehouseAddress" id="warehouseAddress" class="form-control"
                           placeholder="Address">
                </span>
                </div>
            </div>

            <div class="row" style="margin-top: 10px;">
                <div class="form-group col-sm-1">
                    &nbsp;
                </div>
                <div class="form-group col-sm-2">
                    <label class="title">Telephone </label>
                </div>
                <div class="form-group col-sm-4">
                    <input type="text" name="warehouseTel" id="warehouseTel" class="form-control"
                           placeholder="Telephone">
                </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 animated zoomIn">
        <div class="row" style="margin-top: 10px;">
            <div class="col-sm-7">
                <div class="pull-right">
                    <button class="btn btn-primary" type="submit" id="submitSegmentBtn"><i class="fa fa-plus"></i> Add
                        Warehouse
                    </button>
                </div>
            </div>
        </div>
    </div>

</form>

<?php echo footer_page('Right foot', 'Left foot', false); ?>
<script type="text/javascript">
    var mfqWarehouseAutoID;
    $(document).ready(function () {
        $('.headerclose').click(function () {
            fetchPage('system/mfq/mfq_warehouse', '', 'Warehouse')
        });
        $("#form_warehouse").submit(function (e) {
            save_warehouse();
            return false;
        });
        mfqWarehouseAutoID = '<?php echo $mfqWarehouseAutoID ?>';
        if (mfqWarehouseAutoID) {
            load_warehouse_detail();
        }
    });

    function save_warehouse() {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("MFQ_warehouse/save_warehouse"); ?>',
            dataType: 'json',
            data: $("#form_warehouse").serialize(),
            async: false,
            success: function (data) {
                if (data['error'] == 1) {
                    myAlert('e', data['message']);
                }
                else if (data['error'] == 0) {
                    if (data['code'] == 1) {
                        $("#form_warehouse")[0].reset();
                    }
                    myAlert('s', data['message']);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                myAlert('e', xhr.responseText);
            }
        });
    }

    function load_warehouse_detail() {
        if (mfqWarehouseAutoID > 0) {
            $.ajax({
                type: 'POST',
                url: '<?php echo site_url("MFQ_warehouse/edit_warehouse"); ?>',
                dataType: 'json',
                data: {mfqWarehouseAutoID: mfqWarehouseAutoID},
                async: false,
                success: function (data) {
                    myAlert('s', data['message']);
                    $("#submitSegmentBtn").html('<i class="fa fa-pencil"></i> Save Warehouse');
                    $("#mfqWarehouseAutoID").val(mfqWarehouseAutoID);
                    $("#warehouseCode").val(data['warehouseCode']);
                    $("#warehouseDescription").val(data['warehouseDescription']);
                    $("#warehouseLocation").val(data['warehouseLocation']);
                    $("#warehouseAddress").val(data['warehouseAddress']);
                    $("#warehouseTel").val(data['warehouseTel']);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    myAlert('e', xhr.responseText);
                }
            });
        }
    }
</script>