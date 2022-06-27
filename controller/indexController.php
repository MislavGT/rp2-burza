<?php

class IndexController extends BaseController
{
	public function index()
	{
		if (isset($_SESSION['username'])) {
			header('Location: ' . __SITE_URL . '/burza.php?rt=???'); // TODO
		}
		else {
			header('Location: ' . __SITE_URL . '/burza.php?rt=login');
		}
	}
};
