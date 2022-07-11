<?php

function print_dionica_meta($dionica)
{
    echo '<div class="projectmeta">';
    echo "Ticker: ";
    echo '<span class="statustext projectopen">';
    echo $dionica['ticker'];
    echo '</span>';
    echo '</div>';
}

function print_dionica_ime($dionica) {
    echo '<div class="projecttitle">';
    echo $dionica['ime'];
    echo "</div>";
}

function print_dionica_description($dionica)
{
    echo '<div class="projectmeta upperspace">';
    echo "Cijena";
    echo '</div>';
    echo '<div class="textcontent">';
    echo $dionica['zadnja_cijena'] . "kn";
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

function print_buy_sell_form(){
    echo '<form method="post" action="';
    echo  __SITE_URL . '/burza.php?rt=dionice/kupiProdaj';
    echo '">';
    echo 'količina: <input type="text" name="kolicina" >';
    echo '<br />';
    echo 'cijena: <input type="text" name="cijena" >';
    echo '<br />';
    echo '<input type="radio" name="tip" value="buy">kupi
    <input type="radio" name="tip" value="sell">prodaj' ;
    echo ' <input type="submit" style="min-width:100%" align="center" name="submit" value="SUBMIT">';
    echo '</form>';
}
