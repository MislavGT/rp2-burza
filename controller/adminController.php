<?php

class AdminController extends BaseController
{
	public function index()
	{
		if (isset($_SESSION['username'])) {
            echo "here 1";
			// header('Location: ' . __SITE_URL . '/burza.php?rt=popis');
		}
		else {
            echo "here 2";
			// header('Location: ' . __SITE_URL . '/burza.php?rt=login');
		}
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
