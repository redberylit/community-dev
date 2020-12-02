<style>
    /* MENU-LEFT
-------------------------- */
    /* layout */
    #left ul.nav {
        margin-bottom: 2px;
        font-size: 12px; /* to change font-size, please change instead .lbl */
    }

    #left ul.nav ul,
    #left ul.nav ul li {
        list-style: none !important;
        list-style-type: none !important;
        margin-top: 1px;
        margin-bottom: 1px;
    }

    #left ul.nav ul {
        padding-left: 0;
        width: auto;
    }

    #left ul.nav ul.children {
        padding-left: 12px;
        width: auto;
    }

    #left ul.nav ul.children li {
        margin-left: 0px;
    }

    #left ul.nav li a:hover {
        text-decoration: none;
    }

    #left ul.nav li a:hover .lbl {
        color: #999 !important;
    }

    #left ul.nav li.current > a .lbl {
        background-color: #999;
        color: #fff !important;
    }

    /* parent item */
    #left ul.nav li.parent a {
        padding: 0px;
        color: #ccc;
    }

    #left ul.nav > li.parent > a {
        border: solid 1px #999;
        text-transform: uppercase;
    }

    #left ul.nav li.parent a:hover {
        background-color: #fff;
        -webkit-box-shadow: inset 0 3px 8px rgba(0, 0, 0, 0.125);
        -moz-box-shadow: inset 0 3px 8px rgba(0, 0, 0, 0.125);
        box-shadow: inset 0 3px 8px rgba(0, 0, 0, 0.125);
    }

    /* link tag (a)*/
    #left ul.nav li.parent ul li a {
        color: #222;
        border: none;
        display: block;
        padding-left: 5px;
    }

    #left ul.nav li.parent ul li a:hover {
        background-color: #fff;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
    }

    /* sign for parent item */
    #left ul.nav li .sign {
        display: inline-block;
        width: 28px;
        padding: 5px 8px;
        background-color: transparent;
        color: #fff;
    }

    #left ul.nav li.parent > a > .sign {
        margin-left: 0px;
        background-color: #999;
    }

    /* label */
    #left ul.nav li .lbl {
        padding: 5px 12px;
        display: inline-block;
    }

    #left ul.nav li.current > a > .lbl {
        color: #fff;
    }

    #left ul.nav li a .lbl {
        font-size: 12px;
    }

    /* THEMATIQUE
    ------------------------- */
    /* theme 1 */
    #left ul.nav > li.item-1.parent > a {
        border: solid 1px #3fbdf9;
    }

    #left ul.nav > li.item-1.parent > a > .sign,
    #left ul.nav > li.item-1 li.parent > a > .sign {
        margin-left: 0px;
        background-color: #3fbdf9;
    }

    #left ul.nav > li.item-1 .lbl {
        color: #24272d;
    }

    #left ul.nav > li.item-1 li.current > a .lbl {
        background-color: #24272d;
        color: #fff !important;
    }

    /* theme 2 */
    #left ul.nav > li.item-8.parent > a {
        border: solid 1px #51c3eb;
    }

    #left ul.nav > li.item-8.parent > a > .sign,
    #left ul.nav > li.item-8 li.parent > a > .sign {
        margin-left: 0px;
        background-color: #51c3eb;
    }

    #left ul.nav > li.item-8 .lbl {
        color: #51c3eb;
    }

    #left ul.nav > li.item-8 li.current > a .lbl {
        background-color: #51c3eb;
        color: #fff !important;
    }

    /* theme 3 */
    #left ul.nav > li.item-15.parent > a {
        border: solid 1px #94cf00;
    }

    #left ul.nav > li.item-15.parent > a > .sign,
    #left ul.nav > li.item-15 li.parent > a > .sign {
        margin-left: 0px;
        background-color: #94cf00;
    }

    #left ul.nav > li.item-15 .lbl {
        color: #94cf00;
    }

    #left ul.nav > li.item-15 li.current > a .lbl {
        background-color: #94cf00;
        color: #fff !important;
    }

    /* theme 4 */
    #left ul.nav > li.item-22.parent > a {
        border: solid 1px #ef409c;
    }

    #left ul.nav > li.item-22.parent > a > .sign,
    #left ul.nav > li.item-22 li.parent > a > .sign {
        margin-left: 0px;
        background-color: #ef409c;
    }

    #left ul.nav > li.item-22 .lbl {
        color: #ef409c;
    }

    #left ul.nav > li.item-22 li.current > a .lbl {
        background-color: #ef409c;
        color: #fff !important;
    }

    .search-no-results {
        text-align: center;
        background-color: #f6f6f6;
        border: solid 1px #ddd;
        margin-top: 10px;
        padding: 1px;
    }

    .label {
        display: inline;
        padding: .2em .8em .3em;
    }

    .actionicon {
        display: inline-block;
        font-weight: normal;
        font-size: 12px;
        background-color: #89e68d;
        -moz-border-radius: 2px;
        -khtml-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        padding: 2px 5px 2px 5px;
        line-height: 14px;
        vertical-align: text-bottom;
        box-shadow: inset 0 -1px 0 #ccc;
        color: #888;
    }

    .headrowtitle {
        font-size: 11px;
        line-height: 30px;
        height: 30px;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 0 25px;
        font-weight: bold;
        text-align: left;
        text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.3);
        color: rgb(130, 130, 130);
        background-color: white;
        border-top: 1px solid #ffffff;
    }

    .total {
        border-top: 1px double #8a7c1a !important;
        border-bottom: 3px double #b7a318 !important;
        font-weight: bold;
        font-size: 12px !important;
    }

    #left ul.nav li.parent a {
        padding: 0px;
        color: #4365a2;
    }
</style>
<?php //var_dump($header); ?>
<div class="row">
    <div class="col-sm-11">
        &nbsp
    </div>
    <div class="col-sm-1">
        <div class="skin skin-square">
            <div class="skin-section extraColumns">
                <input id="orderSupplier_MasterCheck" type="checkbox"
                       data-caption="" class="columnSelected"
                       name="isActive" onclick=""
                       value="">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div id="left" class="span3">
            <ul id="menu-group-1" class="nav menu">
                <?php if (!empty($header)) {
                    $x = 1;
                    foreach ($header as $val) {
                        ?>
                        <li class="item-1 deeper parent" style="margin-top: 1%;">
                            <a class="" href="#">
                                <span data-toggle="collapse" data-parent="#menu-group-1"
                                      href="#sub-item-<?php echo $val['inquiryMasterID']; ?>-<?php echo $val['itemAutoID']; ?>"
                                      class="sign"><i
                                        class="fa fa-plus" aria-hidden="true" style="color:white;font-size: 13px;"></i></span>
                                <span
                                    class="lbl"><strong><?php echo $val['itemSystemCode'] . " - " . $val['itemName']; ?></strong>&nbsp&nbsp&nbsp<div
                                        class="actionicon"
                                        onclick="view_supplierAssignModel(<?php echo $val['itemAutoID']; ?>)"><i
                                            class="fa fa-repeat" aria-hidden="true" style="color: white"
                                            title="Assign Supplier"></i></div></span>
                            </a>
                            <ul class="children nav-child unstyled small collapse"
                                id="sub-item-<?php echo $val['inquiryMasterID']; ?>-<?php echo $val['itemAutoID']; ?>">
                                <div class="table-responsive mailbox-messages">
                                    <table class="table table-hover table-striped">
                                        <tbody>
                                        <tr class="task-cat-upcoming">
                                            <td class="headrowtitle"
                                                style="border-bottom: solid 1px #f76f01;">#
                                            </td>
                                            <td class="headrowtitle"
                                                style="border-bottom: solid 1px #f76f01;">Supplier Name
                                            </td>
                                            <td class="headrowtitle"
                                                style="border-bottom: solid 1px #f76f01;">Supplier Code
                                            </td>
                                            <td class="headrowtitle"
                                                style="border-bottom: solid 1px #f76f01;">Qty
                                            </td>
                                            <td class="headrowtitle"
                                                style="border-bottom: solid 1px #f76f01;">Expected Delivery Date
                                            </td>
                                            <td class="headrowtitle"
                                                style="border-bottom: solid 1px #f76f01;">
                                            </td>
                                        </tr>
                                        <?php
                                        $companyID = current_companyID();
                                        $suppliers = $this->db->query("SELECT inquiryDetailID,supplierName,supplierSystemCode,srp_erp_srm_suppliermaster.supplierAutoID,srp_erp_srm_orderinquirydetails.isRfqCreated FROM srp_erp_srm_orderinquirydetails INNER JOIN srp_erp_srm_suppliermaster ON srp_erp_srm_orderinquirydetails.supplierID = srp_erp_srm_suppliermaster.supplierAutoID WHERE inquiryMasterID = {$val['inquiryMasterID']} AND srp_erp_srm_orderinquirydetails.itemAutoID = '{$val['itemAutoID']}'")->result_array();
                                        $x = 1;
                                        if (!empty($suppliers)) {
                                            foreach ($suppliers as $tar) {
                                                ?>
                                                <tr>
                                                    <td class="mailbox-star"><?php echo $x; ?></td>
                                                    <td class="mailbox-star"
                                                    ><?php echo $tar['supplierName']; ?></td>
                                                    <td class="mailbox-star"
                                                    ><?php echo $tar['supplierSystemCode'] ?></td>
                                                    <td>
                                                        <a href="#" data-type="text"
                                                           data-placement="bottom"
                                                           data-url="<?php echo site_url('Srm_master/ajax_update_orderInquiry_supplier') ?>"
                                                           data-pk="<?php echo $tar['inquiryDetailID'] ?>"
                                                           data-name="requestedQty"
                                                           data-title="Name"
                                                           class="xeditable"
                                                           data-value="<?php echo isset($val['requestedQty']) ? $val['requestedQty'] : ''; ?>">
                                                            <?php echo $val['requestedQty'] ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="#" data-type="combodate"
                                                           data-url="<?php echo site_url('Srm_master/ajax_update_orderInquiry_supplier') ?>"
                                                           data-pk="<?php echo $tar['inquiryDetailID'] ?>"
                                                           data-name="expectedDeliveryDate"
                                                           data-title="Date of Birth"
                                                           class="xeditableDate"
                                                           data-value="<?php if (!empty($val['expectedDeliveryDate']) && $val['expectedDeliveryDate'] != '0000-00-00 00:00:00') {
                                                               echo format_date($val['expectedDeliveryDate']);
                                                           } ?>">
                                                        </a>
                                                        &nbsp;
                                                    </td>
                                                    <td style="text-align: center">
                                                        <div class="skin skin-square">
                                                            <div class="skin-section extraColumns"><input
                                                                    id="isSupplier_<?php echo $tar['supplierAutoID'] ?>"
                                                                    type="checkbox"
                                                                    data-caption=""
                                                                    <?php if($tar['isRfqCreated'] == 1){
                                                                        echo "checked";
                                                                    } ?>
                                                                    class="columnSelected isSupplier_checkbox"
                                                                    name="supplierCheckbox"
                                                                    onclick="supplier_selected_check(this)"
                                                                    value="<?php echo $tar['inquiryDetailID'] ?>"><label
                                                                    for="checkbox">&nbsp;</label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                                $x++;
                                            }
                                        } else { ?>
                                        <tr>
                                            <td class="mailbox-star" colspan="5" style="text-align: center">No Suppliers Assigned</td>
                                        <tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </ul>
                        </li>
                        <?php
                    }
                } else { ?>
                    <strong class="attachemnt_title">
                                <span style="text-align: center;font-size: 15px;font-weight: 800;">NO SUPPLIERS ASSIGNED FOR THIS ITEM</span>
                    </strong>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {

        $('.children').addClass('in');

        $('.extraColumns input').iCheck({
            checkboxClass: 'icheckbox_square_relative-blue',
            radioClass: 'iradio_square_relative-blue',
            increaseArea: '20%'
        });

        $('.isSupplier_checkbox').on('ifChecked', function (event) {
            supplier_selected_check(this);
        });
        $('.isSupplier_checkbox').on('ifUnchecked', function (event) {
            supplier_selected_check(this);
        });

        $('.xeditable').editable();

        $('.xeditableDate').editable({
            format: 'YYYY-MM-DD',
            viewformat: 'DD.MM.YYYY',
            template: 'D / MMMM / YYYY',
            combodate: {
                minYear: <?php echo format_date_getYear() - 80 ?>,
                maxYear: <?php echo format_date_getYear() + 10 ?>,
                minuteStep: 1
            }
        });
        $('#orderSupplier_MasterCheck').on('ifChecked', function (event) {
            $('.isSupplier_checkbox').iCheck('check');
        });

        $('#orderSupplier_MasterCheck').on('ifUnchecked', function (event) {
            $('.isSupplier_checkbox').iCheck('uncheck');
        });

    });

</script>
