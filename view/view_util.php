<?php

function print_project_meta($project, $user_map)
{
    echo '<div class="projectmeta">';
    echo "Autor: ";
    echo ucfirst($user_map[$project['id_user']]['username']);
    echo " (status: ";
    if (strcmp($project['status'], "open") === 0) {
        echo '<span class="statustext projectopen">';
    } else {
        echo '<span class="statustext projectclosed">';
    }
    echo $project['status'];
    echo '</span>';
    echo ")";
    echo '</div>';
}

function print_project_title($project)
{
    echo '<div class="projecttitle">';
    echo $project['title'];
    echo "</div>";
}

function print_project_description($project)
{
    echo '<div class="projectmeta upperspace">';
    echo "Description";
    echo '</div>';
    echo '<div class="textcontent">';
    echo $project['abstract'];
    echo '</div>';
}

function print_project_members($project, $user_map, $members)
{
    echo '<div class="projectmeta upperspace">';
    echo "Members";
    echo '</div>';
    echo '<div>';
    $num_members = count($members);
    if ($num_members > 1) {
        for ($i = 0; $i < $num_members-1; ++$i) {
            echo ucfirst($user_map[$members[$i]]['username']);
            echo ", ";
        }
    }
    echo ucfirst($user_map[$members[$num_members - 1]]['username']);
    echo '</div>';
}

function prettify_appinv_type($member_type) {
    $prettified = array(
        'application_pending' => 'application pending',
        'application_accepted' => 'application accepted',
        'invitation_pending' => 'invitation pending',
        'invitation_accepted' => 'invitation accepted',
    );
    return $prettified[$member_type];
}

function prettify_member_type($member_type) {
    $prettified = array(
        'member' => 'member',
        'application_pending' => 'application pending',
        'application_accepted' => 'member',
        'invitation_pending' => 'invitation pending',
        'invitation_accepted' => 'member',
    );
    return $prettified[$member_type];
}

function print_application_button($project_id) {
    echo '<br />';
    echo '<a class="linkbutton" href="';
    echo __SITE_URL . "/teamup.php?rt=projects/apply&id=" . $project_id;
    echo '" >apply</a>';
}

function print_accrej_button($project_id, $accrej) {
    $appinv = "invitations";
    echo '<a class="linkbutton" href="';
    echo __SITE_URL . '/teamup.php?rt=' . $appinv . '/' . $accrej . '&id=' . $project_id;
    echo '" >' . $accrej . '</a>';
}

function print_accrej_button_applications($project_id, $user_id, $accrej) {
    $appinv = "applications";
    echo '<a class="linkbutton" href="';
    echo __SITE_URL . '/teamup.php?rt=' . $appinv . '/' . $accrej . '&project_id=' . $project_id . '&user_id=' . $user_id;
    echo '" >' . $accrej . '</a>';
}

function print_membership_type($member_type) {
    echo '<br />';
    echo '<span class="projectmeta">';
    echo prettify_member_type($member_type);
    echo '</span>';
}

function print_appinv_type($member_type) {
    echo '<br />';
    echo '<span class="projectmeta">';
    echo prettify_appinv_type($member_type);
    echo '</span>';
}

function print_spacer() {
    echo '<span class="spacer">';
    echo '</span>';
}
