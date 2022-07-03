<?php

require_once __SITE_PATH . '/app/util.php';

class DioniceController extends BaseController
{
    private function index_with_error($error_message)
    {
        $this->registry->template->title = 'Dionice';
        $this->registry->template->errorMessage = $error_message;
        $this->registry->template->show('dionice_index');
    }

    public function index()
    {
        $this->index_with_error("");
    }
}
