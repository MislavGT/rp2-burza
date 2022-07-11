<?php

class AdminController extends BaseController
{
    private function index_with_error($error_message)
    {
        redirectIfNotLoggedIn();
        $as = new AdminService();
        $this->registry->template->title = 'Admin';
        $this->registry->template->pocetni_kapital = $as->get_initial_capital();
        $this->registry->template->errorMessage = $error_message;
        $this->registry->template->show('admin_index');
    }

    public function index()
    {
        if (isset($_GET['errorMessage'])) {
            $this->index_with_error($_GET['errorMessage']);
        } else {
            $this->index_with_error("");
        }
    }

    public function reset()
    {
        redirectIfNotLoggedIn();
		if (isset($_SESSION['username']) || true) { // TODO FIXME (disable whole button)
            reset_database();
            $ls = new LoginService();
            $ls->logout();
			header('Location: ' . __SITE_URL . '/burza.php?rt=login');
        } else {
			header('Location: ' . __SITE_URL . '/burza.php?rt=login&errorMessage=log in first');
        }
    }

    public function promijeni() {
        redirectIfNotLoggedIn();
        redirectIfNotAdmin();
        if (isset($_POST['pocetni_kapital'])) {
            if (!ctype_digit($_POST['pocetni_kapital'])) {
                header('Location: ' . __SITE_URL . '/burza.php?rt=admin&errorMessage=vrijednost pocetnog kapitala mora biti broj');
                exit();
            }
            $pocetni_kapital = intval($_POST['pocetni_kapital']);
            $as = new AdminService();
            $as->set_initial_capital($pocetni_kapital);
        }
        header('Location: ' . __SITE_URL . '/burza.php?rt=admin');
    }
};
