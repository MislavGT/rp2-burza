<?php require_once __SITE_PATH . '/app/util.php' ?>

<?php
if (isset($errorMessage)) {
    display_error($errorMessage);
}
?>

<a class="linkbutton" href="<?php echo __SITE_URL; ?>/burza.php?rt=admin/reset">Reset database</a>

</div>

</body>

</html>
