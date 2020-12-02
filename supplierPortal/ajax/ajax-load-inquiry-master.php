<?php
include('../includes/medoo/Medoo.php');
include('../includes/database.php');

if(isset($_POST['inquiryID']) && !empty($_POST['inquiryID'])){
    $result = $database_sup->query("SELECT CurrencyCode,narration FROM srp_erp_srm_orderinquirymaster INNER JOIN srp_erp_currencymaster ON srp_erp_srm_orderinquirymaster.transactionCurrencyID = srp_erp_currencymaster.currencyID WHERE inquiryID = ".$_POST['inquiryID']."")->fetch();
    echo json_encode($result);
    //return $result;
}

?>