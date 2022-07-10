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


    private function show_single($dionica_id) {
        $user_id = $_SESSION['id'];
        $ds = new DioniceService();
        $this->registry->template->title = 'Jedna dionica';
        $this->registry->template->dionica = $ds->jednaDionica($dionica_id);
        $this->registry->template->username = $_SESSION['username'];
        $this->registry->template->show('jedna_dionica_index');
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
        $ls = new DioniceService();
        

        $user_id = $_SESSION[ 'id' ];
        $dionica_id = $_SESSION[ 'dionica' ];
        $kolicina=$_POST['kolicina'];
        $cijena=$_POST['cijena'];
        $tip=$_POST['tip'];

        $bool_vrijednost=$ls->kupiProdajOdmah( $user_id, $dionica_id, $kolicina, $cijena, $tip );

        if(!$bool_vrijednost){
            if(  ( $_POST['tip'] )=='buy' ){
                $ls->kupiDionice( $user_id, $dionica_id, $kolicina, $cijena );} 
            if( ( $_POST['tip'] )=='sell' ){
                $ls->prodajDionice( $user_id, $dionica_id, $kolicina, $cijena );} 
        }
    }

    
}
