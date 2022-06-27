<?php

require_once __SITE_PATH . '/app/util.php';

class ProjectsController extends BaseController
{
    public function index()
    {
        redirectIfNotLoggedIn();
        $ps = new ProjectsService();
        $this->registry->template->title = 'All projects';
        $this->registry->template->all_projects = $ps->allProjects();
        $this->registry->template->user_map = $ps->userMap();
        $this->registry->template->username = $_SESSION['username'];
        $this->registry->template->show('projects_index');
    }

    private function show_single($project_id) {
        $ps = new ProjectsService();
        $user_id = $_SESSION['id'];
        $ps = new ProjectsService();
        $this->registry->template->title = 'Single project';
        $this->registry->template->project = $ps->getProject($project_id);
        $this->registry->template->user_map = $ps->userMap();
        $this->registry->template->members = $ps->members($project_id);
        $this->registry->template->member_type = $ps->memberType($project_id, $user_id);
        $this->registry->template->username = $_SESSION['username'];
        $this->registry->template->show('single_project_index');
    }

    public function single()
    {
        redirectIfNotLoggedIn();
        $this->show_single($_GET['id']);
    }

    public function my()
    {
        redirectIfNotLoggedIn();
        $ps = new ProjectsService();
        $as = new AppInvService();
        $this->registry->template->title = 'My projects';
        $this->registry->template->my_projects = $ps->projectsForUser($_SESSION['id']);
        $applications = $as->applicationsForUser($_SESSION['id']);
        $this->registry->template->applications = $applications[0];
        $this->registry->template->project_has_applications = $applications[1];
        $this->registry->template->user_map = $ps->userMap();
        $this->registry->template->username = $_SESSION['username'];
        $this->registry->template->show('my_projects_index');
    }

    public function start()
    {
        redirectIfNotLoggedIn();
        $ps = new ProjectsService();
        $this->registry->template->title = 'Start';
        $this->registry->template->my_projects = $ps->projectsForUser($_SESSION['id']);
        $this->registry->template->user_map = $ps->userMap();
        $this->registry->template->username = $_SESSION['username'];

        if (
            isset($_POST['name']) && isset($_POST['description']) && isset($_POST['members'])
            && strlen($_POST['name']) > 0 && strlen($_POST['description']) > 0 && strlen($_POST['members']) > 0
        ) {
            $ps->startProject($_SESSION['id'], $_POST['name'], $_POST['description'], $_POST['members']);
            header('Location: ' . __SITE_URL . '/teamup.php?rt=projects/my');
        } elseif (isset($_POST['name']) || isset($_POST['description']) || isset($_POST['members'])) {
            $this->registry->template->errorMessage = "Complete the form";
        }

        $this->registry->template->show('start_new_index');
    }

    public function apply()
    {
        redirectIfNotLoggedIn();
        $project_id = $_GET['id'];
        $ps = new ProjectsService();
        $ps->apply($_SESSION['id'], $project_id);
        header('Location: ' . __SITE_URL . '/teamup.php?rt=projects/single&id=' . $project_id);
    }
};
