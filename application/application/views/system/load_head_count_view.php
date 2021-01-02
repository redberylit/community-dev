<?php
$compid = current_companyID();
//$year = $this->db->query("select companyFinanceYearID,beginingDate,endingDate from srp_erp_companyfinanceyear WHERE companyID=$compid ")->result_array();
/*$curryear = $this->db->query("select companyFinanceYearID,beginingDate,endingDate from srp_erp_companyfinanceyear WHERE companyID=$compid  AND companyFinanceYearID = $financeyearid ")->row_array();*/
/*echo $this->db->last_query();
exit;*/
/*$companyFinanceYearID = $financeyearid;
$beginingDate = $curryear['beginingDate'];
$currperiod = $this->db->query("select companyFinancePeriodID,companyFinanceYearID,dateFrom,dateTo from srp_erp_companyfinanceperiod WHERE companyFinanceYearID= $companyFinanceYearID")->result_array();
*/

$companyFinanceYearID = $financeyearid;

if($companyFinanceYearID == 'currentYear'){
    $yearStart = date('Y-01-01');
    $yearEnd = date('Y-12-31');

    $currperiod = getPeriods($yearStart, $yearEnd);

    $previousemp = $this->db->query("select COUNT(EDOJ) as previoystotjoined from srp_employeesdetails WHERE Erp_companyID=$compid AND  EDOJ < '$yearStart' and (dischargedDate is NULL or dischargedDate >= '$yearStart') and isSystemAdmin=0")->row_array();
    $curremptot = $previousemp['previoystotjoined'];
}
else{
    $d = date('Y-01-01');
    $date = strtotime($d.' -1 year');
    $yearStart = date('Y-m-d', $date);
    $yearEnd = date('Y-12-31', $date);

    $currperiod = getPeriods($yearStart, $yearEnd);
    $previousemp = $this->db->query("select COUNT(EDOJ) as previoystotjoined from srp_employeesdetails WHERE Erp_companyID=$compid AND  EDOJ < '$yearStart' and (dischargedDate is NULL or dischargedDate >= '$yearStart') and isSystemAdmin=0")->row_array();
    $curremptot = $previousemp['previoystotjoined'];
}


foreach ($currperiod as $values) {

        $dateFrom = $values['dateFrom'];
        $dateTo = $values['dateTo'];
        $currjoindemp = $this->db->query("select COUNT(EDOJ) as joined from srp_employeesdetails WHERE Erp_companyID=$compid AND  EDOJ BETWEEN '$dateFrom' AND '$dateTo' and isSystemAdmin=0")->row_array();
        $currdischaredemp = $this->db->query("select COUNT(dischargedDate) as joined from srp_employeesdetails WHERE Erp_companyID=$compid AND  dischargedDate BETWEEN '$dateFrom' AND '$dateTo' AND isDischarged=1 and isSystemAdmin=0")->row_array();
        $currempjoind[] = $currjoindemp['joined'];
        $currempdischarged[] = $currdischaredemp['joined'];

        $totalemployees = $this->db->query("SELECT
    COUNT(EDOJ) AS previoystotjoined
FROM
    srp_employeesdetails
WHERE
    Erp_companyID = $compid
AND EDOJ <= '$dateTo' and (dischargedDate is NULL or dischargedDate >= '$dateTo') and isSystemAdmin=0")->row_array();

        $curremptotal[] = $totalemployees['previoystotjoined'];



}


/*print_r($performanceSummaryArr);
exit;*/

?>

<div id="headcountview_<?php echo $userDashboardID ?>"></div>


<script>





    Highcharts.chart('headcountview_<?php echo $userDashboardID ?>', {
        chart: {
            zoomType: 'xy',
            height: '350'
        },
        title: false,
        subtitle: false,
        xAxis: [{
            categories: ['CF','Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            crosshair: true
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: false,
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            title: {
                text: false,
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }
        }, { // Secondary yAxis
            title: {
                text: false,
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            labels: {
                format: false,
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            opposite: true
        }],
        tooltip: {
            shared: true
        },
        legend: {
            itemDistance: 50
        },
        series: [{
            name: 'Joined',
            type: 'column',
            data: [0,<?php echo join($currempjoind, ",") ?>]
        }, {
            name: 'Discharges',
            type: 'column',
            data: [0,<?php echo join($currempdischarged, ",") ?>]
        }, {
            name: 'Total',
            type: 'spline',
            yAxis: 1,
            data: [<?php echo $curremptot ?>,<?php echo join($curremptotal, ",") ?>]
        }]
    });





</script>
