
<?php
if($groupID)
?>
<label class=" control-label"> Sub Group</label>
<div class="">
<?php echo form_dropdown('subGroupID', dropdown_subGroup($groupID,$All), '', 'class="form-control" onchange="loadform()" id="subGroupID" required"'); ?>
</div>


