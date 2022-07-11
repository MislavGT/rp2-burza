<?php require_once __SITE_PATH . '/view/_header.php'; ?>
<?php require_once __SITE_PATH . '/view/view_util.php'; ?>

<div class="contentcontainer">
    <div class="card">
        <?php
        print_mojNeto($neto);
        print_mojPortfelj()
        print_dnevnaZarada($dnevnaZarada);

        ?>
    </div>
</div>


<?php require_once __SITE_PATH . '/view/_footer.php'; ?>