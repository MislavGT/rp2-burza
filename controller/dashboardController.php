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

    public function portfolio(){
        redirectIfNotLoggedIn();
        $user_id = $_SESSION['id'];
        $ks= new KapitalService();
        $this->registry->template->title = 'Moj portfolio';
        $this->registry->template->neto = $ks->neto_vrijednosti();
        $this->registry->template->dnevnaZarada = $ks->dnevnaZarada($user_id);
        $this->registry->template->username = $_SESSION['username'];
        $this->registry->template->imovina = $ks->imovina($user_id);
        $this->registry->template->show('portfolio_index');
    }
}
