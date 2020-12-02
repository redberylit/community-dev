<?php
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('config', $primaryLanguage);

$this->lang->load('common', $primaryLanguage);
?>

    <table id="company_policy_table" class="<?php echo table_class(); ?>">
        <thead>
        <tr>
            <th style="width: 35px;">#</th>
            <th><?php echo $this->lang->line('common_description');?><!--Description--></th>
            <th style="width: 80px;"><?php echo $this->lang->line('config_document_id');?><!--Document ID--></th>
            <th><?php echo $this->lang->line('config_default_value');?><!--Default Value--></th>
            <!--            <th style="width: 55px">Is Active</th>-->
        </tr>
        </thead>
        <tbody>
        <?php if($detail){
            $i=0;
            foreach($detail as $value){
$i++;

            ?>
            <tr>
                <td style="width: 35px;"><?php echo $i;?></td>
                <td><?php echo $value['companyPolicyDescription']?></td>
                <td style="width: 80px;"><?php echo $value['documentID']?></td>
                <td><?php echo get_policy($value['fieldType'], $value['companypolicymasterID'],$value['companyValue'],$value['documentID'],$value['isCompanyLevel'],$value['code']);  ?></td>
            </tr>
        <?php
        } }



        ?>
        </tbody>
    </table>



