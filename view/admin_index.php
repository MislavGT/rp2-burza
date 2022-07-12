<?php require_once __SITE_PATH . '/view/_header.php'; ?>
<?php require_once __SITE_PATH . '/view/view_util.php'; ?>

<div class="contentcontainer">

    <form method="post" action="<?php echo __SITE_URL . '/burza.php?rt=admin/promijeni' ?>">
        <ul class="form-style-1">
            <li>
                <label>Pocetni kapital</label>
                <input type="text" name="pocetni_kapital" class="field-long" value="<?php echo $pocetni_kapital; ?>"/>
            </li>

            <li>
                <input class="linkbutton" type="submit" value="Postavi" />
            </li>
        </ul>
    </form>

    <a class="linkbutton" href="<?php echo __SITE_URL; ?>/burza.php?rt=admin/dividenda">Izdaj dividendu</a>

</div>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>