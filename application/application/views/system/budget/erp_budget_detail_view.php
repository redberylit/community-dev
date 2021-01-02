<style>
    .tdIn {
        width: 140px;
        padding: 2px;

    }

    .form-control {
        display: inline;
        height: 24px;
        padding: 0px;
        padding-right: 2px;
        padding-left: 2px;
        font-size: 11px;
        width: 70px;
    }

    tr:hover, tr.selected {
        background-color: #E3E1E7;
        opacity: 1;
        z-index: -1;
    }

    .table-striped tbody tr.highlight td {
        background-color: #E3E1E7;
    }

    body table tr.selectedRow {
        background-color: #fff2e1;
    }

    tfoot {
        font-size: 11px;
    }

    .amount {

        text-align: right;
        width: 60px;

    }

    a.hoverbtn {
        margin-left: 5px;
    }

</style>
<div class="col-md-8" style="margin-bottom: 10px">
    <label>Document Code: &nbsp;&nbsp;&nbsp;</label> <?php echo $master['documentSystemCode']; ?></td>
    <label> &nbsp;&nbsp;&nbsp;Financial Year:
        &nbsp;&nbsp;&nbsp; </label><?php $finance = get_financial_from_to($master['companyFinanceYearID']);
    echo $finance['beginingDate'] . ' | ' . $finance['endingDate'] ?></td>
    <label> &nbsp;&nbsp;&nbsp;Segment:&nbsp;&nbsp;&nbsp; </label><?php echo $master['description']; ?>
    <label> &nbsp;&nbsp;&nbsp;Currency: &nbsp;&nbsp;&nbsp; </label><?php echo $master['transactionCurrency']; ?>

</div>
<?php

?>
<div class="col-md-12" style="">


    <table id="budget_view" class="table-striped" style="width: 100%">
        <thead>
        <tr>
            <th style="width: 20%">Category</th>
            <!--<th style="width: 120px">Master Account</th>
            <th>GL</th>-->
            <?php
            if ($financialperiod) {
                foreach ($financialperiod as $month) {
                    ?>
                    <th><?php echo $month; ?></th>
                    <?php
                }
            } ?>
        </tr>
        </thead>

        <tbody>
        <?php

        if ($detail) {
            $finaPeriod[] = '';
            foreach ($activeFP as $actvFP) {
                $dtfrm = explode('-', $actvFP['dateFrom']);
                $month = $dtfrm[1];
                array_push($finaPeriod, $month);
            }
            $accountCategoryDesc = "";
            $masterAccount = "";
            $category = array_group_by($detail, 'mainCategory', 'subCategory');
            foreach ($category as $key => $maincategory) {
                /* if ($accountCategoryDesc != $value['accountCategoryDesc']) {
                     $accountCategoryDesc = $value['accountCategoryDesc'];
                 } else {
                     $accountCategoryDesc = "";
                 }
                 if ($masterAccount != $value['masterAccount']) {
                     $masterAccount = $value['masterAccount'];
                 } else {
                     $masterAccount = "";
                 }*/
                echo "<tr style='line-height: 24px;
    font-weight: bold;'><td colspan='13'><div style='color: darkblue'><strong>" . $key . "</strong></div></td></tr>";

                $totoalmyFeb = 0;
                $totoalmyJan = 0;
                $totoalmyMar = 0;
                $totoalmyApr = 0;
                $totoalmyMay = 0;
                $totoalmyJun = 0;
                $totoalmyJul = 0;
                $totoalmyAug = 0;
                $totoalmySep = 0;
                $totoalmyOct = 0;
                $totoalmyNov = 0;
                $totoalmyDec = 0;

                foreach ($maincategory as $key2 => $subcategory) {
                    $subtotal = array();
                    echo "<tr><td colspan='13'><div style='margin-left:15px;color: blue'><strong>" . $key2 . "</strong></div></td></tr>";
                    foreach ($subcategory as $value) {
                        $totoalmyFeb += $value['myFeb'];
                        $totoalmyJan += $value['myJan'];
                        $totoalmyMar += $value['myMar'];
                        $totoalmyApr += $value['myApr'];
                        $totoalmyMay += $value['myMay'];
                        $totoalmyJun += $value['myJun'];
                        $totoalmyJul += $value['myJul'];
                        $totoalmyAug += $value['myAug'];
                        $totoalmySep += $value['mySep'];
                        $totoalmyOct += $value['myOct'];
                        $totoalmyNov += $value['myNov'];
                        $totoalmyDec += $value['myDec'];
                        //$subtotal[""] = ;
                        ?>
                        <tr>
                            <!--<td><?php /*echo $accountCategoryDesc */ ?></td>
                            <td><?php /*echo $masterAccount */ ?></td>-->
                            <td>
                                <div style='margin-left:25px'><?php echo $value['GLDescription'] ?></div>
                            </td>
                            <td class="tdIn">
                                <input class="form-control amount" type="text" name="amount"
                                       data-name="<?php echo $key?>" data-budgetYear="<?php echo $value['budgetYear'] ?>"
                                       data-budgetMonth="1"
                                       data-GLAutoID="<?php echo $value['GLAutoID'] ?>"
                                       value="<?php echo number_format($value['myJan'],2,'.', '') ?>"><a class="hoverbtn"
                                                                                onclick="applybtn(this)"><i
                                            class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
                            </td>
                            <td class="tdIn">
                                <input class="form-control amount" type="text" name="amount"
                                       data-name="<?php echo $key?>" data-budgetYear="<?php echo $value['budgetYear'] ?>"
                                       data-budgetMonth="2"
                                       data-GLAutoID="<?php echo $value['GLAutoID'] ?>"
                                       value="<?php echo number_format($value['myFeb'],2,'.', '') ?>"><a class="hoverbtn"
                                                                                onclick="applybtn(this)"><i
                                            class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
                            </td>
                            <td class="tdIn">
                                <input class="form-control amount" type="text" name="amount"
                                       data-name="<?php echo $key?>" data-budgetYear="<?php echo $value['budgetYear'] ?>"
                                       data-budgetMonth="3"
                                       data-GLAutoID="<?php echo $value['GLAutoID'] ?>"
                                       value="<?php echo number_format($value['myMar'],2,'.', '') ?>"><a class="hoverbtn"
                                                                                onclick="applybtn(this)"><i
                                            class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
                            </td>
                            <td class="tdIn">
                                <input class="form-control amount" type="text" name="amount"
                                       data-name="<?php echo $key?>" data-budgetYear="<?php echo $value['budgetYear'] ?>"
                                       data-budgetMonth="4"
                                       data-GLAutoID="<?php echo $value['GLAutoID'] ?>"
                                       value="<?php echo number_format($value['myApr'],2,'.', '') ?>"><a class="hoverbtn"
                                                                                onclick="applybtn(this)"><i
                                            class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
                            </td>
                            <td class="tdIn">
                                <input class="form-control amount" type="text" name="amount"
                                       data-name="<?php echo $key?>" data-budgetYear="<?php echo $value['budgetYear'] ?>"
                                       data-budgetMonth="5"
                                       data-GLAutoID="<?php echo $value['GLAutoID'] ?>"
                                       value="<?php echo number_format($value['myMay'],2,'.', '') ?>"><a class="hoverbtn"
                                                                                onclick="applybtn(this)"><i
                                            class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
                            </td>
                            <td class="tdIn">
                                <input class="form-control amount" type="text" name="amount"
                                       data-name="<?php echo $key?>" data-budgetYear="<?php echo $value['budgetYear'] ?>"
                                       data-budgetMonth="6"
                                       data-GLAutoID="<?php echo $value['GLAutoID'] ?>"
                                       value="<?php echo number_format($value['myJun'],2,'.', '') ?>"><a class="hoverbtn"
                                                                                onclick="applybtn(this)"><i
                                            class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
                            </td>
                            <td class="tdIn">
                                <input class="form-control amount" type="text" name="amount"
                                       data-name="<?php echo $key?>" data-budgetYear="<?php echo $value['budgetYear'] ?>"
                                       data-budgetMonth="7"
                                       data-GLAutoID="<?php echo $value['GLAutoID'] ?>"
                                       value="<?php echo number_format($value['myJul'],2,'.', '') ?>"><a class="hoverbtn"
                                                                                onclick="applybtn(this)"><i
                                            class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
                            </td>
                            <td class="tdIn">
                                <input class="form-control amount" type="text" name="amount"
                                       data-name="<?php echo $key?>" data-budgetYear="<?php echo $value['budgetYear'] ?>"
                                       data-budgetMonth="8"
                                       data-GLAutoID="<?php echo $value['GLAutoID'] ?>"
                                       value="<?php echo number_format($value['myAug'],2,'.', '') ?>"><a class="hoverbtn"
                                                                                onclick="applybtn(this)"><i
                                            class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
                            </td>
                            <td class="tdIn">
                                <input class="form-control amount" type="text" name="amount"
                                       data-name="<?php echo $key?>" data-budgetYear="<?php echo $value['budgetYear'] ?>"
                                       data-budgetMonth="9"
                                       data-GLAutoID="<?php echo $value['GLAutoID'] ?>"
                                       value="<?php echo number_format($value['mySep'],2,'.', '') ?>"><a class="hoverbtn"
                                                                                onclick="applybtn(this)"><i
                                            class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
                            </td>
                            <td class="tdIn">
                                <input class="form-control amount" type="text" name="amount"
                                       data-name="<?php echo $key?>" data-budgetYear="<?php echo $value['budgetYear'] ?>"
                                       data-budgetMonth="10"
                                       data-GLAutoID="<?php echo $value['GLAutoID'] ?>"
                                       value="<?php echo number_format($value['myOct'],2,'.', '') ?>"><a class="hoverbtn"
                                                                                onclick="applybtn(this)"><i
                                            class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
                            </td>
                            <td class="tdIn">
                                <input class="form-control amount" type="text" name="amount"
                                       data-name="<?php echo $key?>" data-budgetYear="<?php echo $value['budgetYear'] ?>"
                                       data-budgetMonth="11"
                                       data-GLAutoID="<?php echo $value['GLAutoID'] ?>"
                                       value="<?php echo number_format($value['myNov'],2,'.', '') ?>"><a class="hoverbtn"
                                                                                onclick="applybtn(this)"><i
                                            class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
                            </td>
                            <td class="tdIn">
                                <input class="form-control amount" type="text" name="amount"
                                       data-name="<?php echo $key?>" data-budgetYear="<?php echo $value['budgetYear'] ?>"
                                       data-budgetMonth="12"
                                       data-GLAutoID="<?php echo $value['GLAutoID'] ?>"
                                       value="<?php echo number_format($value['myDec'],2,'.', '') ?>"><i
                                        class="hoverbtn" aria-hidden="true">&nbsp;</i>
                            </td>
                        </tr>
                        <?php
                        /* $accountCategoryDesc = $value['accountCategoryDesc'];
                         $masterAccount = $value['masterAccount'];*/
                    }

                }
                ?>
                <tr style="margin: 10px;line-height: 24px;
    font-weight: bold;background-color: #d4d2d2">
                    <td>TOTAL <?php echo $key ?></td>
                         <td style="text-align: right;padding-right: 29px; font-size: 11px" id="<?php echo $key ?>_1"><?php echo number_format($totoalmyJan,2)  ?></td>
                    <td style="text-align: right;padding-right: 29px; font-size: 11px" id="<?php echo $key ?>_2"><?php echo number_format($totoalmyFeb,2)  ?></td>

                    <td style="text-align: right;padding-right: 29px; font-size: 11px" id="<?php echo $key ?>_3"><?php echo number_format($totoalmyMar,2)  ?></td>
                    <td style="text-align: right;padding-right: 29px; font-size: 11px" id="<?php echo $key ?>_4"><?php echo number_format($totoalmyApr,2)  ?></td>
                    <td style="text-align: right;padding-right: 29px; font-size: 11px" id="<?php echo $key ?>_5"><?php echo number_format($totoalmyMay,2)  ?></td>
                    <td style="text-align: right;padding-right: 29px; font-size: 11px" id="<?php echo $key ?>_6"><?php echo number_format($totoalmyJun,2)  ?></td>
                    <td style="text-align: right;padding-right: 29px; font-size: 11px" id="<?php echo $key ?>_7"><?php echo number_format($totoalmyJul,2)  ?></td>
                    <td style="text-align: right;padding-right: 29px; font-size: 11px" id="<?php echo $key ?>_8"><?php echo number_format($totoalmyAug,2)  ?></td>
                    <td style="text-align: right;padding-right: 29px; font-size: 11px" id="<?php echo $key ?>_9"><?php echo number_format($totoalmySep,2)  ?></td>
                    <td style="text-align: right;padding-right: 29px; font-size: 11px" id="<?php echo $key ?>_10"><?php echo number_format($totoalmyOct,2)  ?></td>
                    <td style="text-align: right;padding-right: 29px; font-size: 11px" id="<?php echo $key ?>_11"><?php echo number_format($totoalmyNov,2)  ?></td>
                    <td style="text-align: right;padding-right: 29px; font-size: 11px" id="<?php echo $key ?>_12"><?php echo number_format($totoalmyDec,2) ?></td>
                </tr>
                <?php
            }
        } ?>

        </tbody>
        <tfoot>
        <tr style="line-height: 24px;
    font-weight: bold;background-color: black;color: white">
            <td>Net Profit / (Loss)</td>
            <td style="text-align: right;padding-right: 29px; font-size: 11px" id="1"></td>
            <td style="text-align: right;padding-right: 29px; font-size: 11px" id="2"></td>
            <td style="text-align: right;padding-right: 29px; font-size: 11px" id="3"></td>
            <td style="text-align: right;padding-right: 29px; font-size: 11px" id="4"></td>
            <td style="text-align: right;padding-right: 29px; font-size: 11px" id="5"></td>
            <td style="text-align: right;padding-right: 29px; font-size: 11px" id="6"></td>
            <td style="text-align: right;padding-right: 29px; font-size: 11px" id="7"></td>
            <td style="text-align: right;padding-right: 29px; font-size: 11px" id="8"></td>
            <td style="text-align: right;padding-right: 29px; font-size: 11px" id="9"></td>
            <td style="text-align: right;padding-right: 29px; font-size: 11px" id="10"></td>
            <td style="text-align: right;padding-right: 29px; font-size: 11px" id="11"></td>
            <td style="text-align: right;padding-right: 29px; font-size: 11px" id="12"></td>
        </tr>
        </tfoot>
    </table>
    <br>
    <div class="text-right m-t-xs">

        <button class="btn btn-warning" onclick="load_missing_gl_tobudget()">Load</button>
        <button class="btn btn-primary" onclick="save_draft()">Save &amp; Draft</button>
        <button class="btn btn-success submitWizard" onclick="confirmation()">Confirm</button>
    </div>
    <br>
</div>

<br>
<script>
    $('#budget_view').tableHeadFixer({
     head: true,
     foot: true,
     left: 0,
     right: 0,
     'z-index': 10
     });
    $(".amount").keydown(function (event) {
        if (event.shiftKey == true) {
            event.preventDefault();
        }
        if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190 || event.keyCode == 110 || event.keyCode == 109 || event.keyCode == 109 || event.keyCode == 189
        ) {
        } else {
            event.preventDefault();
        }
        if ($(this).val().indexOf('.') !== -1 && (event.keyCode == 190 || event.keyCode == 110))
            event.preventDefault();
    });

    var budgetAutoID = <?php echo json_encode($this->input->post('budgetAutoID'))?>;
    get_budget_footer_total(budgetAutoID);
    /*$('hoverbtn').addClasses('hide');*/
    $('.hoverbtn').hide();
    $('table').on('click', 'tbody tr', function (event) {
        $(this).addClass('highlight').siblings().removeClass('highlight');
        $('.hoverbtn').hide();
        $(this).find(".hoverbtn").show();
    });


    $('.amount').change(function () {
        if ($(this).val() == "") {
            $(this).val(0);
        }

        var glAutoID = $(this).attr('data-GLAutoID');
        var budgetyear = $(this).attr('data-budgetYear');
        var budgetmonth = $(this).attr('data-budgetmonth');
        var amount = $(this).val();
        $(this).val(parseFloat(amount).toFixed(2));

        update_budget_row(glAutoID, budgetyear, budgetmonth, amount, budgetAutoID);
    });

    function applybtn(id) {
        var myArray = [];
        $(id).closest('td').find("input").each(function () {

            baseAmount = this.value;//this.value
        });


        xi = 0;
        $(id).closest('td').nextAll().find('input').each(function (n) {
            $(this).val(baseAmount);
            myArray[xi] = {};
            myArray[xi]['GLAutoID'] = $(this).attr('data-GLAutoID');
            myArray[xi]['budgetYear'] = $(this).attr('data-budgetYear');
            myArray[xi]['budgetMonth'] = $(this).attr('data-budgetmonth');
            myArray[xi]['amount'] = baseAmount;

            xi++;
        });
        console.log(myArray);

        update_apply_all_row(myArray);


    }

    function update_apply_all_row(myArray) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {
                myArray: myArray, budgetAutoID: budgetAutoID

            },
            url: "<?php echo site_url('Budget/update_apply_all_row'); ?>",
            beforeSend: function () {
                /*startLoad();*/
            },
            success: function (data) {
                get_budget_footer_total(budgetAutoID);

            },
            error: function () {

            }
        });
    }

    function update_budget_row(glAutoID, budgetyear, budgetmonth, amount, budgetAutoID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {
                glAutoID: glAutoID,
                budgetyear: budgetyear,
                budgetmonth: budgetmonth,
                amount: amount,
                budgetAutoID: budgetAutoID
            },
            url: "<?php echo site_url('Budget/update_budget_row'); ?>",
            beforeSend: function () {
                /*startLoad();*/
            },
            success: function (data) {
                get_budget_footer_total(budgetAutoID);

            },
            error: function () {

            }
        });
    }

    function save_draft() {
        fetchPage('system/finance/Budget_management', 'Test', 'Budget');
    }

    function confirmation() {
        if (budgetAutoID) {
            swal({
                    title: "Are you sure?",
                    text: "You want to confirm this document !",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Confirm"
                },
                function () {
                    $.ajax({
                        async: true,
                        type: 'post',
                        dataType: 'json',
                        data: {'budgetAutoID': budgetAutoID},
                        url: "<?php echo site_url('Budget/budget_confirmation'); ?>",
                        beforeSend: function () {
                            startLoad();
                        },
                        success: function (data) {
                            refreshNotifications(true);
                            stopLoad();
                            fetchPage('system/finance/Budget_management', 'Test', 'Budget');
                        }, error: function () {
                            swal("Cancelled", "Your file is safe :)", "error");
                        }
                    });
                });
        }
        ;
    }

    function get_budget_footer_total(budgetAutoID) {
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {budgetAutoID: budgetAutoID},
            url: "<?php echo site_url('Budget/get_budget_footer_total'); ?>",
            beforeSend: function () {
                /*startLoad();*/
            },
            success: function (data) {
                /*    $('#detailData').html(data);*/
                /*     stopLoad();
                 refreshNotifications(true);*/
                myJan=0;
                myFeb=0;
                myMar=0;
                myApr=0;
                myMay=0;
                myJun=0;
                myJul=0;
                myAug=0;
                mySep=0;
                myOct=0;
                myNov=0;
                myDec=0;
                for (val of data) {
                    subMyJan =parseFloat(val['myJan']);
                    subMyFeb =parseFloat(val['myFeb']);
                    subMyMar =parseFloat(val['myMar']);
                    subMyApr =parseFloat(val['myApr']);
                    subMyMay =parseFloat(val['myMay']);
                    subMyJun =parseFloat(val['myJun']);
                    subMyJul =parseFloat(val['myJul']);
                    subMyAug =parseFloat(val['myAug']);
                    subMySep =parseFloat(val['mySep']);
                    subMyOct =parseFloat(val['myOct']);
                    subMyNov =parseFloat(val['myNov']);
                    subMyDec =parseFloat(val['myDec']);

                    $('#'+val['mainCategory']+'_1').html(commaSeparateNumber(subMyJan, 2));
                    $('#'+val['mainCategory']+'_2').html(commaSeparateNumber(subMyFeb, 2));
                    $('#'+val['mainCategory']+'_3').html(commaSeparateNumber(subMyMar, 2));
                    $('#'+val['mainCategory']+'_4').html(commaSeparateNumber(subMyApr, 2));
                    $('#'+val['mainCategory']+'_5').html(commaSeparateNumber(subMyMay, 2));
                    $('#'+val['mainCategory']+'_6').html(commaSeparateNumber(subMyJun, 2));
                    $('#'+val['mainCategory']+'_7').html(commaSeparateNumber(subMyJul, 2));
                    $('#'+val['mainCategory']+'_8').html(commaSeparateNumber(subMyAug, 2));
                    $('#'+val['mainCategory']+'_9').html(commaSeparateNumber(subMySep, 2));
                    $('#'+val['mainCategory']+'_10').html(commaSeparateNumber(subMyOct, 2));
                    $('#'+val['mainCategory']+'_11').html(commaSeparateNumber(subMyNov, 2));
                    $('#'+val['mainCategory']+'_12').html(commaSeparateNumber(subMyDec, 2));


                    myJan += subMyJan;
                    myFeb += subMyFeb;
                    myMar += subMyMar;
                    myApr += subMyApr;
                    myMay += subMyMay;
                    myJun += subMyJun;
                    myJul += subMyJul;
                    myAug += subMyAug;
                    mySep += subMySep;
                    myOct += subMyOct;
                    myNov += subMyNov;
                    myDec += subMyDec;

                }


                $('#1').html(commaSeparateNumber(myJan, 2));
                $('#2').html(commaSeparateNumber(myFeb, 2));
                $('#3').html(commaSeparateNumber(myMar, 2));
                $('#4').html(commaSeparateNumber(myApr, 2));
                $('#5').html(commaSeparateNumber(myMay, 2));
                $('#6').html(commaSeparateNumber(myJun, 2));
                $('#7').html(commaSeparateNumber(myJul, 2));
                $('#8').html(commaSeparateNumber(myAug, 2));
                $('#9').html(commaSeparateNumber(mySep, 2));
                $('#10').html(commaSeparateNumber(myOct, 2));
                $('#11').html(commaSeparateNumber(myNov, 2));
                $('#12').html(commaSeparateNumber(myDec, 2));

            },
            error: function () {

            }
        });
    }


    function load_missing_gl_tobudget(){
        $.ajax({
            async: true,
            type: 'post',
            dataType: 'json',
            data: {'budgetAutoID': <?php echo $master['budgetAutoID'] ?>},
            url: "<?php echo site_url('Budget/load_missing_gl_tobudget'); ?>",
            beforeSend: function () {
                startLoad();
            },
            success: function (data) {
                stopLoad();
                myAlert(data[0],data[1]);
                if(data[0]=='s'){
                    fetchPage("system/budget/erp_budget_detail_page","<?php echo $master['budgetAutoID'] ?>","Budget Detail ","Budget Detail");
                }

            }, error: function () {
                stopLoad();
                swal("Cancelled", "Your file is safe :)", "error");
            }
        });
    }


</script>



