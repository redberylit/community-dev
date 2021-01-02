<style>
    .caption p {
        color: #999;
    }

    /* Carousel Control */
    .control-box {
        text-align: right;
        width: 99%;
    }

    .carousel-control {
        background: #666;
        border: 0px;
        border-radius: 0px;
        display: inline-block;
        font-size: 34px;
        font-weight: 200;
        line-height: 18px;
        opacity: 0.5;
        padding: 4px 10px 0px;
        position: static;
        height: 30px;
        width: 15px;
    }

    li {
        list-style-type: none;
    }

    p {
        margin: 0 0 0px;
    }

</style>
<div class="row">
    <div class="col-md-12 animated zoomIn">
        <header class="head-title">
            <h2> ORDER REVIEW HEADER </h2>
        </header>
    </div>
</div>
<?php
if (!empty($item)) {
    foreach ($item as $row) { ?>
        <div class="row">
            <div class="col-sm-2">
                <div class="fff">
                    <div class="thumbnail">
                        <img class="align-left" src="<?php echo base_url("images/srm/item.png") ?>"
                             alt="" width="40" height="40">
                    </div>
                    <div class="caption">
                        <h4><?php echo $row['itemName'] ?></h4>

                        <p><?php echo $row['itemSystemCode'] ?></p>

                        <p>QTY : <?php echo $row['requestedQty'] . " (" . $row['UnitShortCode'] . ")" ?></p>
                    </div>
                </div>
            </div>
            <?php
            $supplers = $this->db->query("SELECT *,supplierName,supplierImage from srp_erp_srm_orderinquirydetails JOIN srp_erp_srm_suppliermaster  ON srp_erp_srm_suppliermaster.supplierAutoID = srp_erp_srm_orderinquirydetails.supplierID where inquiryMasterID = " . $row['inquiryMasterID'] . " AND itemAutoID = " . $row['itemAutoID'] . "")->result_array();
            ?>
            <div class="col-sm-10" style="margin-top: -1%">
                <div class="carousel slide" id="myCarousel_<?php echo $row['inquiryDetailID'] ?>">
                    <div class="carousel-inner">
                        <?php
                        if (!empty($supplers)) {
                            $y = 0;
                            $tot = 0;
                            $active = "active";
                            foreach ($supplers as $sup) {
                                if ($y == 0) {
                                    echo "<div class='item $active'>";
                                    echo "<ul class='thumbnails'>";
                                }
                                ?>
                                <li class="col-sm-2">
                                    <div class="fff">
                                        <div class="thumbnail">
                                            <?php if ($sup['supplierImage'] != '') { ?>
                                                <img class="align-left"
                                                     src="<?php echo base_url('uploads/srm/supplierimage/' . $sup['supplierImage']); ?>"
                                                     alt="" width="40" height="40">
                                                <?php
                                            } else { ?>
                                                <img class="align-left"
                                                     src="<?php echo base_url("images/crm/icon-list-contact.png") ?>"
                                                     alt="" width="40" height="40">
                                            <?php } ?>

                                        </div>
                                        <div class="caption">
                                            <h5><?php echo $sup['supplierName']; ?></h5>

                                            <p>QTY : <?php echo $sup['supplierQty']; ?></p>

                                            <p>Unit Price
                                                : <?php echo number_format($sup['supplierPrice'], 2); ?></p>

                                            <p>Total : <?php $tot = $sup['supplierQty'] * $sup['supplierPrice'];
                                                echo "<span style='color: blue;'>" . number_format($tot, 2) . "</span>";
                                                ?></p>

                                            <div class="skin skin-square" style="margin-left: 35%">
                                                <div class="skin-section extraColumns"><input
                                                        id="isSupplier_<?php echo $sup['supplierID'] ?>"
                                                        type="checkbox"
                                                        <?php if ($sup['isSelectedForPO'] == 1) {
                                                            echo "checked";
                                                        } ?>
                                                        data-caption="" class="columnSelected supplier_checkbox"
                                                        name="isSuppliers"
                                                        onclick="orderItem_selected_check(this)"
                                                        value="<?php echo $sup['itemAutoID'] . "_" . $sup['supplierID'] ?>"><label
                                                        for="checkbox">&nbsp;</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php
                                $y++;
                                if ($y == 6) {
                                    $active = '';
                                    echo " </ul>";
                                    echo "</div>";
                                    $y = 0;
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 10px">
            <div class="col-sm-12">
                <nav>
                    <ul class="control-box pager">
                        <li><a data-slide="prev" href="#myCarousel_<?php echo $row['inquiryDetailID'] ?>" class=""><i
                                    class="glyphicon glyphicon-chevron-left"></i></a></li>
                        <li><a data-slide="next" href="#myCarousel_<?php echo $row['inquiryDetailID'] ?>" class=""><i
                                    class="glyphicon glyphicon-chevron-right"></i></a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="row" style="margin-top: 10px;">
        <div class="col-sm-12">
            <div class="text-right m-t-xs">
                <button class="btn btn-primary" onclick="generate_review_supplier()">Generate PO</button>
            </div>
        </div>
    </div>
    <?php
}
?>
<script>// Carousel Auto-Cycle
    $(document).ready(function () {
        $('.carousel').carousel({
            interval: false
        });

        $('.extraColumns input').iCheck({
            checkboxClass: 'icheckbox_square_relative-blue',
            radioClass: 'iradio_square_relative-blue',
            increaseArea: '20%'
        });

        $('.supplier_checkbox').on('ifChecked', function (event) {
            orderItem_selected_check(this);
        });
        $('.supplier_checkbox').on('ifUnchecked', function (event) {
            orderItem_selected_check(this);
        });
    });
</script>