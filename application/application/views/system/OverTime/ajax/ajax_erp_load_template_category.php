
    <label  class="">Attendance Categories</label>
    <div >
        <?php echo form_dropdown('overtimeCategoryID[]', all_ot_category_drop($companyID), '', 'class="form-control" multiple onchange="diablebutton()"  id="overtimeCategoryID"'); ?>
    </div>


<script>
    $('#overtimeCategoryID').multiselect2({
        includeSelectAllOption: true,
        enableFiltering: true,
        onChange: function (element, checked) {
        }
    });
</script>

