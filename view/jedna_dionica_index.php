<?php require_once __SITE_PATH . '/view/_header.php'; ?>
<?php require_once __SITE_PATH . '/view/view_util.php'; ?>

<div class="contentcontainer">
    <div class="card">
        <?php
        print_dionica_meta($dionica);
        print_dionica_ime($dionica);
        print_dionica_description($dionica);
        ?>
    </div>
</div>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>