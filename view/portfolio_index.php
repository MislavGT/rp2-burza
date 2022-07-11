<?php require_once __SITE_PATH . '/view/_header.php'; ?>
<?php require_once __SITE_PATH . '/view/view_util.php'; ?>

<div class="contentcontainer">
    <div class="card">
        <?php
        print_mojNeto($neto);
        echo '</br>';
        print_dnevnaZarada($dnevnaZarada);
        echo '</br>';
        ?>
    </div>

    <h3>Dionice</h3>

    <?php
    print_portfolio($imovina);
    ?>
</div>


<?php require_once __SITE_PATH . '/view/_footer.php'; ?>