<style>
    .hideTr {
        display: none
    }

    .oddTR td {
        background: #f9f9f9 !important;
    }

    .evenTR td {
        background: #ffffff !important;
    }
</style>
<?php
$documentID = $this->input->post('documentID');

switch ($documentID) {
    case "CINV":
        $qty = $detail['requestedQty'];
        break;

    case "RV":
        $qty = $detail['requestedQty'];
        break;

    case "SR":
        $qty = $detail['return_Qty'];
        break;

    case "MI":
        $qty = $detail['qtyIssued'];
        break;

    case "ST":
        $qty = $detail['transfer_QTY'];
        break;

    case "SA":
        $qty = abs($detail['adjustmentStock']);
        break;


    default:
        echo $documentID . ' Error: Code not configured!<br/>';
        echo 'File: ' . __FILE__ . '<br/>';
        echo 'Line No: ' . __LINE__ . '<br><br>';
}

?>

<h4><?php echo $detail['itemDescription'] ?>
    <br/><br/>Quantity: <strong><?php echo $qty ?>
    </strong> item/s</h4>
<div>
    <div class="btn-group">
        <button onclick="selectNItems(<?php echo $qty ?>)" type="button" class="btn btn-default">
            Select First <?php echo $qty ?> item/s.
        </button>
        <button onclick="unSelectAll()" type="button" class="btn btn-default">un-select all</button>

    </div>
</div>
<h4>
    <input type="text" id="searchItem" placeholder="Search">
    <span class="pull-right">
    <strong>
        <span
            id="subItemCount">0</span> </strong> item/s selected <!--out of --> <?php //echo $qty ?> </span>
</h4>

<input type="hidden" value="0" id="currentSubItemCount"/>
<input type="hidden" value="<?php echo $this->input->post('documentID') ?>" id="soldDocumentID"
       name="soldDocumentID"/>  <!--CINV / RV-->
<input type="hidden" value="0" id="soldDocumentAutoID" name="soldDocumentAutoID"/>
<input type="hidden" value="0" id="soldDocumentDetailID" name="soldDocumentDetailID"/>
<input type="hidden" value="<?php echo $qty ?>" name="qty"/>

<?php // print_r($detail) ?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div style="max-height: 300px; overflow: auto">
            <table class="table table-bordered table-condensed table-hover " id="subItemListTbl"
                   style="margin-top:-1px;">
                <thead>
                <tr>
                    <th>#</th>
                    <th style="width:13%">SubItem Code</th>
                    <?php
                    $i=1;
                    foreach($attributes as $valu){
                        ?>
                        <th><?php echo $valu['attributeDescription'] ?></th>
                    <?php
                        $i++;
                    }
                    ?>
                    <th>Select</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($subItems) {
                    $nthItem = 0;
                    $selected = 0;
                    $i = 1;
                    foreach ($subItems as $item) {
                        ?>
                        <tr style="cursor: hand !important;"
                            id="rowId_subItem_<?php echo $item['subItemAutoID'] ?>"
                            data-value="<?php echo $item['subItemCode'] ?> <?php echo $item['description'] ?> <?php echo $item['productReferenceNo'] ?>">
                            <td><?php echo $i; $i++; ?></td>
                            <td>
                                <label style="cursor: pointer !important;"
                                       for="checkBox<?php echo $item['subItemAutoID'] ?>">
                                    <?php echo $item['subItemCode'] ?>
                                </label>

                            </td>
                            <?php
                            foreach($attributes as $valu){
                                ?>
                                <td><?php echo $item[$valu['columnName']] ?></td>
                                <?php
                            }
                            ?>
                            <td class="text-center">
                                <input class="subItem <?php if ($nthItem < $qty) {
                                    echo ' nthItem ';
                                } ?>" type="checkbox"
                                    <?php if (!empty(trim($item['soldDocumentDetailID'])) && $item['soldDocumentDetailID'] > 0) {
                                        echo ' checked ';
                                        $selected++;
                                    } ?>

                                       id="checkBox<?php echo $item['subItemAutoID'] ?>"
                                       name="subItemCode[]" value="<?php echo $item['subItemAutoID'] ?>">
                            </td>
                            <td>
                                <!--<span id="selectedContainer"><i class="fa fa-check text-green"></i></span>--></td>
                        </tr>
                        <?php
                        $nthItem++;

                    }
                }
                ?>
                </tbody>
            </table>

        </div>

    </div>
</div>

<script>
    function unSelectAll() {
        $(".subItem").prop('checked', false);
        $("#currentSubItemCount").val(0);
        $("#subItemCount").html(0);
    }

    function selectNItems(n) {
        unSelectAll();
        $(".nthItem").prop('checked', true);
        var maxCount = '<?php echo $qty?>';
        $("#currentSubItemCount").val(maxCount);
        $("#subItemCount").html(maxCount);
    }

    function updateCount() {
        $("#currentSubItemCount").val('<?php echo $selected ?>');
        $("#subItemCount").html('<?php echo $selected ?>');
    }


    function setupValues() {

        <?php
        $documentID = $this->input->post('documentID');
        switch ($documentID) {
            case "CINV":
                $documentAutoID = $detail['invoiceAutoID'];
                $documentDetailsAutoID = $detail['invoiceDetailsAutoID'];
                break;

            case "RV":
                $documentAutoID = $detail['receiptVoucherAutoId'];
                $documentDetailsAutoID = $detail['receiptVoucherDetailAutoID'];
                break;

            case "SR":
                $documentAutoID = $detail['stockReturnAutoID'];
                $documentDetailsAutoID = $detail['stockReturnDetailsID'];
                break;

            case "MI":
                $documentAutoID = $detail['itemIssueAutoID'];
                $documentDetailsAutoID = $detail['itemIssueDetailID'];
                break;
            case "ST":
                $documentAutoID = $detail['stockTransferAutoID'];
                $documentDetailsAutoID = $detail['stockTransferDetailsID'];
                break;

            case "SA":
                $documentAutoID = $detail['stockAdjustmentAutoID'];
                $documentDetailsAutoID = $detail['stockAdjustmentDetailsAutoID'];
                break;

            default:
                echo 'alert("' . $documentID . ' Line No: ' . __LINE__ . ' in File: ' . __FILE__ . ' ")';
        }
        ?>

        $('#soldDocumentAutoID').val('<?php echo $documentAutoID ?>');
        $('#soldDocumentDetailID').val('<?php echo $documentDetailsAutoID ?>');
    }

    $(document).ready(function (e) {
        setupValues();

        $('#searchItem').keyup(function () {

            var searchKey = $.trim($(this).val()).toLowerCase();
            var tableTR = $('#subItemListTbl tbody>tr');
            tableTR.removeClass('hideTr evenTR oddTR');

            tableTR.each(function () {
                var dataValue = '' + $(this).attr('data-value') + '';
                dataValue = dataValue.toLocaleLowerCase();

                if (searchKey != '') {
                    if (dataValue.indexOf('' + searchKey + '') == -1) {
                        $(this).addClass('hideTr');
                    }
                }
                else {

                }
            });

            //applyRowNumbers();
        });

        function applyRowNumbers() {
            var m = 1;
            $('#details_table tbody>tr').each(function (i) {
                if (!$(this).hasClass('hideTr')) {
                    var isEvenRow = ( m % 2 );
                    if (isEvenRow == 0) {
                        $(this).addClass('evenTR');
                    } else {
                        $(this).addClass('oddTR');
                    }

                    $(this).find('td:eq(0)').html(m);
                    m += 1;
                }
            });

            $('#showingCount').text((m - 1));
        }

        //$("#subItemListTbl").dataTable();
        $('#subItemListTbl').tableHeadFixer({
            head: true,
            foot: true,
            left: 1,
            right: 0
        });

        $(".subItem").click(function (e) {
            var maxCount = '<?php echo $qty - 1?>';
            var currentCount = $("#currentSubItemCount").val();
            console.log(maxCount);
            console.log(currentCount);

            if (maxCount < currentCount) {
                if ($(this).is(':checked')) {
                    $(this).prop('checked', false);
                    newCount = parseInt(currentCount) + 1;
                    /*alert('You have selected maximum amount of item/s');*/
                    myAlert('w','You have selected maximum amount of item/s');
                    return false;

                } else {
                    newCount = parseInt(currentCount) - 1;

                    $("#currentSubItemCount").val(newCount);
                    $("#subItemCount").html(newCount);
                }


            } else {
                var newCount = 0;
                if ($(this).is(':checked')) {
                    newCount = parseInt(currentCount) + 1;
                } else {
                    newCount = parseInt(currentCount) - 1;
                }

                $("#currentSubItemCount").val(newCount);
                $("#subItemCount").html(newCount);
            }

        });
        updateCount();
    });


</script>
