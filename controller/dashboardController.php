<?php

require_once __SITE_PATH . '/app/util.php';

class DashboardController extends BaseController
{
    private function index_with_error($error_message)
    {
        redirectIfNotLoggedIn();
        $this->registry->template->title = 'Dashboard';
        $this->registry->template->errorMessage = $error_message;
        $this->registry->template->show('dashboard_index');
    }

    public function index()
    {
        $this->index_with_error("");
    }

    private function show_rang(){
        $user_id = $_SESSION['id'];
        $ds= new KapitalService();
        $this->registry->template->title = 'Rang lista';
        $this->registry->template->neto = $ds->neto_vrijednosti();
        $this->registry->template->imena = $ds->imena();
        $this->registry->template->username = $_SESSION['username'];
        $this->registry->template->show('rang_index');

    }

    public function rang()
    {
        redirectIfNotLoggedIn();
        $this->show_rang();
    }
}
