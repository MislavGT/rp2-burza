<?php

require_once __SITE_PATH . '/app/util.php';

class RegisterController extends BaseController
{
    private function index_with_error($error_message)
    {
        $this->registry->template->title = 'Register';
        $this->registry->template->errorMessage = $error_message;
        $this->registry->template->show('register_index');
    }

    public function index()
    {
        $this->index_with_error("");
    }

    public function attempt()
    {
        if (!isset($_POST['username']) || !isset($_POST['password']) || strlen($_POST['username']) == 0 || strlen($_POST['password']) == 0) {
            $this->index_with_error('Upisite ime i lozinku');
            exit();
        }

        if (!preg_match('/^[a-zA-Z]{3,10}$/', $_POST['username'])) {
            $this->index_with_error('Korisničko ime treba imati između 3 i 10 slova.');
            exit();
        }

        $ls = new LoginService();
        $login_result = $ls->attempt_register($_POST['username'], $_POST['password']);

        if ($login_result->success()) {
            header('Location: ' . __SITE_URL . '/burza.php');
        } else {
            $this->index_with_error($login_result->error_message);
        }
    }

    public function verify()
    {
        if (!isset($_GET['niz']) || !preg_match('/^[a-z]{20}$/', $_GET['niz'])) {
            exit('Nešto ne valja s nizom.');
        }

        $ls = new LoginService();
        $verify_result = $ls->attempt_verify($_GET['niz']);

        if ($verify_result->success()) {
            $user_id = $ls->reg_seq_to_id($_GET['niz']);
            $ks = new KapitalService();
            $ks->setCapitalToInitial($user_id);
            header('Location: ' . __SITE_URL . '/burza.php');
        } else {
            $this->index_with_error($verify_result->error_message);
        }
    }

    public function logout()
    {
        $ls = new LoginService();
        $ls->logout();
        $this->index_with_error("");
    }
}
