<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('hrms_reports', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);


?>
<style>
    .class_1 {
        background-color: #ebebf9;
    }

    .class_2 {
        background-color: #F7FBFB;
    }

    .class_3 {
        background-color: #FDFEFD;
    }

    .class_4 {
        background-color: #EFEFF2;
    }

    .class_gross {
        background-color: #F6F3F4;
    }

    .class_tot {
        background-color: #d8d8e6;
    }
</style>


<?php
foreach ($gr_data as $gr_id=>$item){
    $cols_pan = count($item['slab_det']);
?>

<div class="row" style="margin-top: 15px">
    <div class="col-md-5">
        <b><?php echo $gratuityMaster[$gr_id][0]['gratuityDescription']; ?></b>
    </div>
    <div class="col-md-7">
        <?php echo export_buttons('content-tbl-'.$gr_id, 'Gratuity Salary', True, false); ?>
    </div>
</div>

<div id="content-tbl-<?=$gr_id?>">
    <div class="hide"><?php echo $this->lang->line('common_company'); ?><!--Company--> - <?php echo current_companyName(); ?></div>
    <div class="hide"><?php echo $this->lang->line('hrms_reports_gratuity_salary');?><?php echo $as_of_date_str ?></div>
    <div class="hide"><b><?php echo $gratuityMaster[$gr_id][0]['gratuityDescription']; ?></b></div>
    <div style="height: 450px; margin-top: 10px">
        <table id="" class="<?php echo table_class() ?> rpt-table">
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2"><?php echo $this->lang->line('common_emp_no');?></th>
                    <th rowspan="2"><?php echo $this->lang->line('common_employee_name');?></th>
                    <th rowspan="2"><?php echo $this->lang->line('common_designation');?></th>
                    <th rowspan="2"><?php echo $this->lang->line('common_joined_date');?></th>
                    <th rowspan="2"><?php echo $this->lang->line('common_no_of_years');?></th>
                    <th colspan="<?php echo $cols_pan + 2?>" style="text-align: center">
                        <?php echo $this->lang->line('common_reporting_currency');?> [ <?php echo $rpt_curr;?> ]
                    </th>
                    <th colspan="<?php echo $cols_pan + 2?>">
                        <?php echo $this->lang->line('common_local_currency');?> [ <?php echo $loc_curr;?> ]
                    </th>
                </tr>
                    <th class="class_gross"><?php echo $this->lang->line('common_fixed_gross_salary');?></th>
                <?php

                $local_str = ''; $n = 1;
                foreach ($item['slab_det'] as $title){
                    echo '<th class="class_'.$n.'">'.$title.'</th>';
                    $local_str .= '<th class="class_'.$n.'">'.$title.'</th>';
                    $n++;
                }

                echo '<th class="class_tot">'.$this->lang->line('common_total').'</th>
                      <th class="class_gross">'. $this->lang->line('common_fixed_gross_salary').'</th>
                      '.$local_str.'
                      <th class="class_tot">'.$this->lang->line('common_total').'</th>';
                ?>
                <tr>

                </tr>
            </thead>

            <tbody>
            <?php
            $dPlace = 2; $gr_tot_rpt = 0; $gr_tot_loc = 0; $line = 1;
            foreach ($item['details'] as $key=>$detail){
                $this_cur = $detail['payCurrencyID'];
                $rpt_cnv = $currency_det[$this_cur]['rpt']['conversion'];
                $loc_cnv = $currency_det[$this_cur]['loc']['conversion'];

                $total =  round($detail['gratuityAmount'], $dPlace);
                if($total == 0){
                    continue;
                }

                $rpt_total = round(($total / $rpt_cnv), $rpt_dPlace);
                $loc_total = round(($total / $loc_cnv), $loc_dPlace);
                $gr_tot_rpt += $rpt_total;
                $gr_tot_loc += $loc_total;

                $fix_pay = round($detail['totFixPayment'], $dPlace);
                $rpt_fix_pay = round(($fix_pay / $rpt_cnv), $rpt_dPlace);
                $loc_fix_pay = round(($fix_pay / $loc_cnv), $loc_dPlace);

                echo '<tr>
                          <td style="text-align: right">'.$line.'</td>
                          <td>'.$detail['ECode'].'</td>                 
                          <td><div style="width: 150px">'.$detail['Ename2'].'</div></td>
                          <td>'.$detail['designation'].'</td>
                          <td><div style="width: 60px">'.$detail['joinDate'].'</div></td>                 
                          <td style="text-align: center"><div style="width: 60px">'.$detail['totalWork'].'</div></td>
                          <td style="text-align: right" class="class_gross">'.number_format($rpt_fix_pay, $rpt_dPlace).'</td>';

                $local_str = ''; $n = 1;
                foreach ($item['slab_det'] as $slab_id => $row){
                    $amount = $detail['slab'][$slab_id];
                    $amount_rpt = round(($amount / $rpt_cnv), $rpt_dPlace);
                    $amount_loc = round(($amount / $loc_cnv), $loc_dPlace);
                    echo '<td style="text-align: right" class="class_'.$n.'">'.number_format($amount_rpt, $rpt_dPlace).'</td>';
                    $local_str .= '<td style="text-align: right" class="class_'.$n.'">'.number_format($amount_loc, $loc_dPlace).'</td>';
                    $n++;
                }

                echo '<td style="text-align: right" class="class_tot"><b>'.number_format($rpt_total, $rpt_dPlace).'</b></td> 
                      <td style="text-align: right" class="class_gross">'.number_format($loc_fix_pay, $loc_dPlace).'</td>
                      '.$local_str.'
                      <td style="text-align: right" class="class_tot"><b>'.number_format($loc_total, $loc_dPlace).'</b></td> 
                      </tr>';
                $line++;

            }
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <?php
                    echo '<td style="text-align: right" colspan="'.($cols_pan + 7).'"><div style="padding-right: 50px; width:100%">'.$this->lang->line('common_grand_total').'</div></td>                 
                          <td style="text-align: right" class="class_tot"><b>'.number_format($gr_tot_rpt, $rpt_dPlace).'</b></td>
                          <td colspan="'.($cols_pan + 1).'"></td>
                          <td style="text-align: right" class="class_tot"><b>'.number_format($gr_tot_loc, $rpt_dPlace).'</b></td>';
                    ?>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php } ?>

<script>
    $('.rpt-table').tableHeadFixer({
        head: true,
        foot: true,
        left: 0,
        right: 0,
        'z-index': 10
    });

    function generateReportExcel() {
        var form = document.getElementById('frm-rpt');
        form.target = '_blank';
        form.action = '<?php echo site_url('Report/get_gratuity_salary_report/Excel/Gratuity-salary'); ?>';
        form.submit();
    }
</script>

<?php
/**
 * Created by PhpStorm.
 * User: Nasik
 * Date: 3/27/2019
 * Time: 10:02 AM
 */