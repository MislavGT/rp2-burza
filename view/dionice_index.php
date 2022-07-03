<?php require_once __SITE_PATH . '/view/_header.php'; ?>
<?php require_once __SITE_PATH . '/view/view_util.php'; ?>

<div class="projectcontainer">
    <?php
    foreach ($all_projects_applied as $project) {
        echo '<div class="card textcontent">';

        echo '<a href="' . __SITE_URL . '/teamup.php?rt=projects/single&id=' . $project['id'] . '"> <span class="clickable"></span> </a>';

        print_project_meta($project, $user_map);
        print_project_title($project);
        print_appinv_type($project['member_type']);

        echo "</div>";
    }

    if (count($all_projects_applied) === 0) {
        echo "You have not yet applied to join any projects";
    }
    ?>

</div>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>