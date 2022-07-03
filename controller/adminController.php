<?php

class AdminController extends BaseController
{
    private function index_with_error($error_message)
    {
        $this->registry->template->title = 'Admin';
        $this->registry->template->errorMessage = $error_message;
        $this->registry->template->show('admin_index');
    }

    public function index()
    {
        $this->index_with_error("");
    }

    public function reset()
    {
		if (isset($_SESSION['username']) || true) { // TODO FIXME (disable whole button)
            reset_database();
            $ls = new LoginService();
            $ls->logout();
			header('Location: ' . __SITE_URL . '/burza.php?rt=login');
        } else {
			header('Location: ' . __SITE_URL . '/burza.php?rt=login&errorMessage=log in first');
        }
    }
};
