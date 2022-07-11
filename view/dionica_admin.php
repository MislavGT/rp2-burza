<form method="post" action="<?php echo __SITE_URL . '/burza.php?rt=dionice/promijeni&id=' . $dionica['id'] ?>">
    <ul class="form-style-1">
        <li>
            <label>Dividenda</label>
            <input type="text" name="dividenda" class="field-long" value="<?php echo $dionica['dividenda']; ?>" />
        </li>

        <li>
            <input class="linkbutton" type="submit" value="Postavi" />
        </li>
    </ul>
</form>
