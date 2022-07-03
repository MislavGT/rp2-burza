<?php
require_once __SITE_PATH . '/app/util.php';
?>

<div class="mainmenu">
    <div class="menubutton <?php echo ifeq($title, 'Dashboard', 'underlined', ''); ?>">
        <a class="menubuttonlink <?php echo ifeq($title, 'Dashboard', 'onlink', ''); ?>" href="<?php echo __SITE_URL; ?>/burza.php?rt=dashboard">Dashboard</a>
    </div>

    <div class="menubutton <?php echo ifeq($title, 'Dionice', 'underlined', ''); ?>">
        <a class="menubuttonlink <?php echo ifeq($title, 'Dionice', 'onlink', ''); ?>" href="<?php echo __SITE_URL; ?>/burza.php?rt=dionice">Dionice</a>
    </div>

    <?php
    $as = new AdminService();
    if ($as->is_admin($_SESSION['id'])) {
        require __SITE_PATH . '/view/main_menu_admin.php';
    }
    ?>
</div>