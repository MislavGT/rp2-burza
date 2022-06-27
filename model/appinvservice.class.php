<?php

require_once __DIR__ . '/../app/database/db.class.php';

class AppInvService
{
    public function allProjectsAppInv($user_id, $appinv)
    {
        $in_string = '("' . $appinv . '_pending", "' . $appinv .  '_accepted")';
        $db = DB::getConnection();

        try {
            $st = $db->prepare('SELECT dz2_members.member_type, dz2_projects.id, dz2_projects.status, dz2_projects.title, dz2_projects.id_user, dz2_projects.abstract
                FROM dz2_members JOIN dz2_projects
                WHERE dz2_members.id_project=dz2_projects.id AND dz2_members.id_user=:user_id AND dz2_members.member_type IN ' . $in_string);
            $st->execute(array('user_id' => $user_id));
        } catch (PDOException $e) {
            exit('DB error (AppInvService.allProjectsAppInv): ' . $e->getMessage());
        }

        $projects = array();
        while ($row = $st->fetch()) {
            array_push($projects, $row);
        }
        return $projects;
    }

    public function allProjectsApplied($user_id)
    {
        return $this->allProjectsAppInv($user_id, 'application');
    }

    public function allProjectsInvited($user_id)
    {
        return $this->allProjectsAppInv($user_id, 'invitation');
    }

    public function applicationsForUser($user_id)
    {
        $db = DB::getConnection();

        try {
            $st = $db->prepare('SELECT dz2_members.id_project, dz2_members.id_user, sec.username
                FROM dz2_members JOIN dz2_projects JOIN dz2_users JOIN dz2_users AS sec
                WHERE dz2_members.id_project=dz2_projects.id
                AND dz2_users.id=:user_id
                AND sec.id=dz2_members.id_user
                AND dz2_projects.id_user=:user_id
                AND dz2_members.member_type="application_pending"');
            $st->execute(array('user_id' => $user_id));
        } catch (PDOException $e) {
            exit('DB error (AppInvService.applicationsForUser): ' . $e->getMessage());
        }

        $applications = array();
        $project_has_applications = array();
        while ($row = $st->fetch()) {
            array_push($applications, array($row['id_project'], $row['id_user'], $row['username']));
            $project_has_applications[$row['id_project']] = 1;
        }
        return array($applications, $project_has_applications);
    }

    public function correctStatus()
    {
        $db = DB::getConnection();

        try {
            $st = $db->prepare('UPDATE dz2_members JOIN dz2_projects
            SET dz2_projects.status="closed"
            WHERE 	dz2_projects.id = dz2_members.id_project
                AND dz2_projects.number_of_members <= 
                    (SELECT COUNT(*) FROM dz2_members AS mem 
                        WHERE 	dz2_members.id_project = mem.id_project
                        AND 	mem.member_type IN ("member", "application_accepted", "invitation_accepted"))');
            $st->execute(array());
        } catch (PDOException $e) {
            exit('DB error (AppInvService.correctStatus): ' . $e->getMessage());
        }
    }

    public function accept_appinv($project_id, $user_id, $appinv)
    {
        echo $project_id;
        echo $user_id;
        $appinv_accepted = $appinv . '_accepted';
        $appinv_pending = $appinv . '_pending';
        $db = DB::getConnection();

        try {
            $st = $db->prepare('UPDATE dz2_members JOIN dz2_projects
                SET dz2_members.member_type=:appinv_accepted
                WHERE   dz2_members.id_project=:project_id AND dz2_projects.id=:project_id
                AND     dz2_members.id_user=:user_id
                AND     dz2_members.member_type=:appinv_pending
                AND 	dz2_projects.status="open"');
            $st->execute(array(
                'user_id' => $user_id, 'project_id' => $project_id,
                'appinv_pending' => $appinv_pending,
                'appinv_accepted' => $appinv_accepted
            ));
        } catch (PDOException $e) {
            exit('DB error (AppInvService.accept_appinv): ' . $e->getMessage());
        }

        $st->debugDumpParams();

        $this->correctStatus();
    }

    public function reject_appinv($project_id, $user_id, $appinv)
    {
        $appinv_pending = $appinv . '_pending';
        $db = DB::getConnection();

        try {
            $st = $db->prepare('DELETE FROM dz2_members
                WHERE   dz2_members.id_project=:project_id
                AND     dz2_members.id_user=:user_id
                AND     dz2_members.member_type=:appinv_pending');
            $st->execute(array('user_id' => $user_id, 'project_id' => $project_id, 'appinv_pending' => $appinv_pending));
        } catch (PDOException $e) {
            exit('DB error (AppInvService.reject_appinv): ' . $e->getMessage());
        }

        $this->correctStatus();
    }
};
