<?php
//echo '<pre>';print_r($countries); echo '</pre>'; die();
$primaryLanguage = getPrimaryLanguage();
$this->lang->load('communityngo', $primaryLanguage);

?>
    <div>
        <select name="FamMasterID[]" class="form-control" id="FamMasterID" multiple="multiple">
            <?php
            foreach ($famMasDrop as $val){
                ?>
                <option value="<?php echo $val['FamMasterID'] ?>"><?php echo trim($val['FamilySystemCode']) . ' |' . trim($val['CName_with_initials'])?></option>
                <?php
            }
            ?>
        </select>
    </div>


<?php
/**
 * Created by PhpStorm.
 * User: Moufiya
 * Date: 10/12/2018
 * Time: 12:08 PM
 */
