

<?php
foreach ($newmembers as $key => $val) {
    if ($key <= 9) {
        $defaultImg = 'default.gif';
        $defaultImgFull = base_url('images/users/default.gif');
        ?>
        <li>
            <img
                src="../images/users/<?php echo !empty($val['EmpImage']) ? $val['EmpImage'] : $defaultImg ?>"
                alt="No Image">
            <a class="users-list-name" href="#" title="<?php echo $val['Ename2'] ?>"><?php echo $val['Ename2'] ?></a>
            <span class="users-list-date" style="cursor: pointer;"><?php echo trim_value($val['DesDescription'],15)  ?></span>
        </li>
        <?php
    }
}
?>


<script>


</script>
