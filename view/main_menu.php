<?php
require_once __SITE_PATH . '/app/util.php';
?>

<div class="mainmenu">
    <div class="menubutton <?php echo ifeq($title, 'All projects', 'underlined', ''); ?>">
        <a class="menubuttonlink <?php echo ifeq($title, 'All projects', 'onlink', ''); ?>" href="<?php echo __SITE_URL; ?>/teamup.php?rt=projects">All projects</a>
    </div>

    <div class="menubutton <?php echo ifeq($title, 'My projects', 'underlined', ''); ?>">
        <a class="menubuttonlink <?php echo ifeq($title, 'My projects', 'onlink', ''); ?>" href="<?php echo __SITE_URL; ?>/teamup.php?rt=projects/my">My projects</a>
    </div>

    <div class="menubutton <?php echo ifeq($title, 'Start', 'underlined', ''); ?>">
        <a class="menubuttonlink <?php echo ifeq($title, 'Start', 'onlink', ''); ?>" href="<?php echo __SITE_URL; ?>/teamup.php?rt=projects/start">Start a new project</a>
    </div>

    <div class="menubutton <?php echo ifeq($title, 'Invitations', 'underlined', ''); ?>">
        <a class="menubuttonlink <?php echo ifeq($title, 'Invitations', 'onlink', ''); ?>" href="<?php echo __SITE_URL; ?>/teamup.php?rt=invitations">Pending invitations</a>
    </div>

    <div class="menubutton <?php echo ifeq($title, 'Applications', 'underlined', ''); ?>">
        <a class="menubuttonlink <?php echo ifeq($title, 'Applications', 'onlink', ''); ?>" href="<?php echo __SITE_URL; ?>/teamup.php?rt=applications">Pending applications</a>
    </div>
</div>