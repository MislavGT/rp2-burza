<?php require_once __SITE_PATH . '/view/_header.php'; ?>
<?php require_once __SITE_PATH . '/view/view_util.php'; ?>

<div class="contentcontainer">
    <?php
    foreach ($sve_dionice as $dionica) {
        echo '<div class="card textcontent">';

        echo '<a href="' . __SITE_URL . '/burza.php?rt=dionice/single&id=' . $dionica['id'] . '"> <span class="clickable"></span> </a>';

        print_dionica_meta($dionica);
        print_dionica_ime($dionica);
        print_dionica_description($dionica);

        echo "</div>";
    }
    ?>

</div>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>