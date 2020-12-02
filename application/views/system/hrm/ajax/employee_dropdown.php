<?php
//echo '<pre>';print_r($countries); echo '</pre>'; die();
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('employee_master', $primaryLanguage);
$this->lang->load('common', $primaryLanguage);
?>

    <label for="supplierPrimaryCode"> <?php echo $this->lang->line('emp_employee_name');?><!--Employee Name--></label><br>
    <select name="employeeCode[]" class="form-control" id="employeeCode" onchange="callOTable('employeeCode')" multiple="multiple">
        <?php
        foreach ($employees as $val){
        ?>
        <option value="<?php echo $val['EIdNo'] ?>"><?php echo $val['Ename2'] ?></option>
        <?php
        }
        ?>
    </select>





<?php
/**
 * Created by PhpStorm.
 * User: Nasik
 * Date: 12/4/2016
 * Time: 1:14 PM
 */