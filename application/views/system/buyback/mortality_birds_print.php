<?php if($type==true){?>
    <style>
        .bgcolour {
            background-color: #00a65a;
            margin-top: 3%;
        }
        .bgcolourconfirm {
            background-color: #f9ac38;
            margin-top: 3%;
        }
        .item-labellabelbuyback {
            color: #fff;
            height: 21px;
            width: 90px;
            position: absolute;
            font-weight: bold;
            padding-left: 10px;
            padding-top: 0px;
            top: 10px;
            right: -59px;
            margin-right: 0;
            border-radius: 3px 3px 0 3px;
            box-shadow: 0 3px 3px -2px #ccc;
            text-transform: capitalize;
        }
        .item-labellabelbuyback:after {
            top: 20px;
            right: 0;
            border-top: 4px solid #1f1d1d;
            border-right: 4px solid rgba(0, 0, 0, 0);
            content: "";
            position: absolute;
        }
    </style>
<?php }?>
<!--<div class="table-responsive">
    <table style="width: 100%">
        <tbody>
        <tr>
            <td style="width:60%;">
                <table>
                    <tr>
                        <td>
                            <img alt="Logo" style="height: 130px" src="<?php
/*                            echo mPDFImage.$this->common_data['company_data']['company_logo']; */ ?>">
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:40%;">
                <table>
                    <tr>
                        <td colspan="3">
                            <h3><strong><?php /*echo $this->common_data['company_data']['company_name'].' ('.$this->common_data['company_data']['company_code'].').'; */ ?></strong></h3>
                            <p><?php /*echo $this->common_data['company_data']['company_address1'].' '.$this->common_data['company_data']['company_address2'].' '.$this->common_data['company_data']['company_city'].' '.$this->common_data['company_data']['company_country']; */ ?></p>
                            <h4>Mortality Details</h4>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Farmer ID</strong></td>
                        <td><strong>:</strong></td>
                        <td><?php /*echo $extra['master']['farmerCode']; */ ?></td>
                    </tr>
                    <tr>
                        <td><strong>Farmer Name</strong></td>
                        <td><strong>:</strong></td>
                        <td><?php /*echo $extra['master']['farmerName']; */ ?></td>
                    </tr>
                    <tr>
                        <td><strong>Batch Code</strong></td>
                        <td><strong>:</strong></td>
                        <td><?php /*echo $extra['master']['batchCode']; */ ?></td>
                    </tr>
                    <tr>
                        <td><strong>Document Date</strong></td>
                        <td><strong>:</strong></td>
                        <td><?php /*echo $extra['master']['documentDate']; */ ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>-->
<div class="table-responsive">
    <table style="width: 100%">
        <tbody>
        <tr>
            <!-- <td style="width:50%;">
                <table>
                    <tr>
                        <td>
                            <img alt="Logo" style="height: 130px" src="<?php /*echo mPDFImage.$this->common_data['company_data']['company_logo']; */ ?>">
                        </td>
                    </tr>
                </table>
            </td>-->
            <td>
                <table>
                    <tr>
                        <td style="text-align: center;">
                            <!--<h3><strong><?php /*echo $this->common_data['company_data']['company_name']; */ ?>.</strong></h3>
                            <p><?php /*echo $this->common_data['company_data']['company_address1'].' '.$this->common_data['company_data']['company_address2'].' '.$this->common_data['company_data']['company_city'].' '.$this->common_data['company_data']['company_country']; */ ?></p>
                            <br>-->
                            <h4>Mortality Details</h4>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<br>
<br>
<hr style="margin-top: 0%">
<?php if($type==true){
    $class = 'theadtr';
    ?>
<?php if($extra['master']['confirmedYN']==1) {
    echo '<div class="post-area" >
    <article class="post" style="padding-bottom: 2%">
        <div class="item-labellabelbuyback file bgcolour">Confirmed</div>
    </article>';
}?>
<?php } else {
    $class = ' ';
} ?>
<div class="table-responsive">
    <table style="width: 100%">
        <tbody>
        <tr>
            <td><strong>Farmer ID</strong></td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['farmerCode']; ?></td>

            <td><strong>Farmer Name</td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['farmerName']; ?></td>
        </tr>
        <tr>
            <td><strong>Batch Code</strong></td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['batchCode']; ?></td>

            <td><strong>Document Date</td>
            <td><strong>:</strong></td>
            <td><?php echo $extra['master']['documentDate']; ?></td>
        </tr>

        </tbody>
    </table>
</div>
<hr>
<div class="table-responsive">
    <br>
    <table id="add_new_grv_table" class="<?php echo table_class(); ?>">
        <thead>
        <tr>
            <th class= <?php echo $class?> style="width: 10%">#</th>
            <th class= <?php echo $class?> style="width: 50%">Mortality Cause</th>
            <th class= <?php echo $class?> style="width: 20%">No of Birds</th>
            <th class= <?php echo $class?> style="width: 20%">Remarks</th>
        </tr>
        </thead>
        <tbody id="grv_table_body">
        <?php $requested_total = 0;
        $received_total = 0;
        if (!empty($extra['bird_detail'])) {
            for ($i = 0; $i < count($extra['bird_detail']); $i++) {
                echo '<tr>';
                echo '<td>' . ($i + 1) . '</td>';
                echo '<td>' . $extra['bird_detail'][$i]['mortalityCause'] . '</td>';
                echo '<td>' . $extra['bird_detail'][$i]['noOfBirds'] . '</td>';
                echo '<td>' . $extra['bird_detail'][$i]['remarks'] . '</td>';
            }
        } else {
            echo '<tr class="danger"><td colspan="5" class="text-center"><b>No Records Found</b></td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>
<br>
<div class="table-responsive">
    <?php if ($extra['master']['confirmedYN']) { ?>
        <table style="width: 500px !important;">
            <tbody>
            <tr>
                <td><b> Confirmed By </b></td>
                <td><strong>:</strong></td>
                <td><?php echo $extra['master']['confirmedByName']; ?> / <?php echo $extra['master']['confirmedDate']; ?></td>
            </tr>
            </tbody>
        </table>
    <?php } ?>

</div>
<script>
    $('.review').removeClass('hide');

</script>