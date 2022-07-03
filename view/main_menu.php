<?php
require_once __SITE_PATH . '/app/util.php';
?>

<div class="mainmenu">
    <div class="menubutton <?php echo ifeq($title, 'Dashboard', 'underlined', ''); ?>">
        <a class="menubuttonlink <?php echo ifeq($title, 'Dashboard', 'onlink', ''); ?>" href="<?php echo __SITE_URL; ?>/burza.php?rt=dashboard">Dashboard</a>
    </div>

    <?php
    $as = new AdminService();
    if ($as->is_admin($_SESSION['id'])) {
        require __SITE_PATH . '/view/main_menu_admin.php';
    }
    ?>
</div>