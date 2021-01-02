<style>
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

    .contact-box .align-left {
        float: left;
        margin: 0 7px 0 0;
        padding: 2px;
        border: 1px solid #ccc;
    }

    img {
        vertical-align: middle;
        border: 0;
        -ms-interpolation-mode: bicubic;
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
</style>
<?php
if (!empty($header)) {
    ?>
    <div class="table-responsive mailbox-messages">
        <table class="table table-hover table-striped">
            <tbody>
            <tr>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;">#</td>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff;">Name</td>
                <td class="headrowtitle" style="border-top: 1px solid #ffffff; text-align: center">
                    <div class="skin skin-square">
                        <div class="skin-section extraColumns">
                            <input id="orderItem_MasterCheck" type="checkbox"
                                   data-caption="" class="columnSelected"
                                   name="isActive" onclick=""
                                   value="">
                        </div>
                    </div>
                </td>
            </tr>
            <?php
            $x = 1;
            foreach ($header as $val) {
                ?>
                <tr>
                    <td class="mailbox-name">
                        <?php echo $x ?>
                    </td>
                    <td class="mailbox-name" style="min-width: 150px">
                        <div class="contact-box">
                            <img class="align-left" src="<?php echo base_url("images/srm/item.png") ?>"
                                 alt="" width="40" height="40">

                            <div class="link-box"><strong class="contacttitle"><a class="link-person noselect"
                                                                                  href="#"><?php echo $val['itemName'] ?><br><?php echo $val['itemSystemCode'] ?><br><?php echo $val['customerOrderCode'] ?>
                                </strong></div>
                        </div>
                    </td>
                    <td width="5%">
                        <?php
                        $orderValue = $this->db->query("SELECT isChecked FROM srp_erp_srm_inquiryitem where itemAutoID = ".$val['itemAutoID']." AND orderMasterID = ".$val['customerOrderID']."")->row_array();
                        $disabled ='';
                        if($orderValue['isChecked']){
                           $disabled = "disabled";
                        }
                        ?>
                        <div class="skin skin-square">
                            <div class="skin-section extraColumns"><input
                                    id="isAttended_<?php echo $val['itemAutoID'] ?>" type="checkbox" <?php echo $disabled ?>
                                    data-caption="" class="columnSelected isitem_checkbox"
                                    name="isActive" onclick="orderItem_selected_check(this)"
                                    value="<?php echo $val['itemAutoID']."_".$val['customerOrderID'] ?>"><label for="checkbox">&nbsp;</label>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php
                $x++;
            }
            ?>

            </tbody>
        </table><!-- /.table -->
    </div>
    <?php
} else { ?>
    <br>
    <div class="search-no-results">THERE ARE NO CUSTOMER ORDER ITEMS TO DISPLAY.</div>
    <?php
}
?>
<script type="text/javascript">
    $(document).ready(function () {

        $('.extraColumns input').iCheck({
            checkboxClass: 'icheckbox_square_relative-blue',
            radioClass: 'iradio_square_relative-blue',
            increaseArea: '20%'
        });

        $('.isitem_checkbox').on('ifChecked', function (event) {
            orderItem_selected_check(this);
        });
        $('.isitem_checkbox').on('ifUnchecked', function (event) {
            orderItem_selected_check(this);
        });

        $('#orderItem_MasterCheck').on('ifChecked', function (event) {
            $('.isitem_checkbox').iCheck('check');
        });

        $('#orderItem_MasterCheck').on('ifUnchecked', function (event) {
            $('.isitem_checkbox').iCheck('uncheck');
        });

    });
</script>