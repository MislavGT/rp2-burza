<?php

require_once __SITE_PATH . '/app/util.php';

class DioniceController extends BaseController
{
    private function index_with_error($error_message)
    {
        redirectIfNotLoggedIn();
        $ds = new DioniceService();
        $this->registry->template->title = 'Dionice';
        $this->registry->template->sve_dionice = $ds->sveDionice();
        $this->registry->template->username = $_SESSION['username'];
        $this->registry->template->errorMessage = $error_message;
        $this->registry->template->show('dionice_index');
    }


    private function show_single_with_error($dionica_id, $errorMessage) {
        $user_id = $_SESSION['id'];
        $ds = new DioniceService();
        $this->registry->template->title = 'Jedna dionica';
        $this->registry->template->dionica = $ds->jednaDionica($dionica_id);
        $this->registry->template->username = $_SESSION['username'];
        $this->registry->template->errorMessage = $errorMessage;
        $this->registry->template->show('jedna_dionica_index');
    }

    private function show_single($dionica_id) {
        if (isset($_GET['errorMessage'])) {
            $this->show_single_with_error($dionica_id, $_GET['errorMessage']);
        } else {
            $this->show_single_with_error($dionica_id, "");
        }
    }

    public function index()
    {
        $this->index_with_error("");
    }

    public function single()
    {
        redirectIfNotLoggedIn();
        $this->show_single($_GET['id']);
    }

    public function kupiProdaj(){
        //kontroler koji omogućuje prodavanje i kupnju dionica tako da obradi podatke iz forme jedna_dionica_index 
        redirectIfNotLoggedIn();
        $ls = new DioniceService();
        
        $user_id = $_SESSION[ 'id' ];
        $dionica_id = $_SESSION[ 'dionica' ];
        $kolicina=$_POST['kolicina'];
        $cijena=$_POST['cijena'];
        $tip=$_POST['tip'];

        $ls->kupiProdajOdmah( $user_id, $dionica_id, $kolicina, $cijena, $tip );
		header('Location: ' . __SITE_URL . '/burza.php?rt=dionice');
    }

    public function promijeni() {
        redirectIfNotLoggedIn();
        redirectIfNotAdmin();
        if (!isset($_GET['id'])) {
            header('Location: ' . __SITE_URL . '/burza.php?rt=dionice');
            exit();
        }
        if (isset($_POST['dividenda'])) {
            if (!ctype_digit($_POST['dividenda'])) {
                header('Location: ' . __SITE_URL . '/burza.php?rt=dionice/single&id=' . $_GET['id'] . '&errorMessage=vrijednost pocetnog kapitala mora biti broj');
                exit();
            }

            $ds = new DioniceService();
            $ds->postaviDividendu($_GET['id'], $_POST['dividenda']);
        }
        header('Location: ' . __SITE_URL . '/burza.php?rt=dionice/single&id=' . $_GET['id']);
    }
}
