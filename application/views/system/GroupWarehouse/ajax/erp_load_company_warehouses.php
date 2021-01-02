<?php
foreach($companyID as $val){
    $companyid= get_company_accoding_to_id($val);
    $customerval= get_group_warehouse_details($groupwareHouseAutoID,$val);
    ?>
    <div class="row">
        <div class="form-group col-sm-5" >
            <div class="">
                <input type="text" class="form-control" id="companyID" value="<?php echo $companyid['company_code'].' | '.$companyid['company_name'] ?>" name="companyID[]" readonly>
                <!--<input type="hidden" class="form-control" id="id" value="<?php /*echo $val */?>" name="id">-->
                <input type="hidden" class="form-control" id="companyIDgrp" value="<?php echo $val ?>" name="companyIDgrp[]">
            </div>
        </div>
        <div class="form-group col-sm-5" >
            <div class="">
                <?php
                if(!empty($customerval)){
                    echo form_dropdown('warehosueMasterID[]', dropdown_companywarehouses($val,$customerval['warehosueMasterID']), $customerval['warehosueMasterID'], 'class="form-control select2" id="warehosueMasterID_'.$val.'" required"');
                }else{
                    echo form_dropdown('warehosueMasterID[]', dropdown_companywarehouses($val), '', 'class="form-control select2" id="warehosueMasterID_'.$val.'" required"');
                }
                ?>
            </div>
        </div>
        <div class="form-group col-sm-2" >
            <div class="">
                <button  class="btn btn-default btn-xs" onclick="clearcustomer(<?php echo $val ?>)" type="button">Clear</button>
            </div>
        </div>
    </div>
    <?php
}
?>






