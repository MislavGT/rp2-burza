<?php require_once __SITE_PATH . '/view/_header.php'; ?>
<?php require_once __SITE_PATH . '/view/view_util.php'; ?>
<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
</head>
<body>
<div class="contentcontainer">
    <div class="card">
        <?php
        print_mojNeto($neto);
        echo '</br>';
        print_dnevnaZarada($dnevnaZarada);
        echo '</br>';
        ?>
    </div>

    <h3>Dionice</h3>

    <?php
    print_portfolio($imovina);
    ?>
</div>
<div id="orders">
</div>
<script type="text/javascript">
    $(document).ready( function()
    {
        let orders = $("#orders");
        let id_user = <?php echo json_encode($_SESSION['id']); ?>;

        $.ajax(
        {
            url: "../rp2-burza/app/orders.php",
            data:
            {
                id_korisnika: id_user
            },
            dataType: "json",
            type: "GET",
            success: function( data )
            {
                for(x in data){
                    let id_narudzbe = parseInt(data[x].id);
                    let id_dionica = parseInt(data[x].id_dionica);
                    let kolicina = parseInt(data[x].kolicina);
                    let cijena = parseInt(data[x].cijena);
                    let tip = data[x].tip;
                    let datum = data[x].datum;
                    let narudzba = $("<p>").prop({
                        id: id_narudzbe
                    });
                    orders.append(narudzba);
                    $.ajax(
                    {
                        url: "../rp2-burza/app/koja_je_dionica.php",
                        data:
                        {
                            id_dionica: id_dionica
                        },
                        dataType: "json",
                        type: "GET",
                        success: function( podaci )
                        {
                            narudzba.html(`${podaci.ticker} <br> ZADNJA CIJENA: ${podaci.zadnja_cijena} <br> CIJENA: ${cijena} <br> KOLICINA: ${kolicina} <br> ${tip} <br> ${datum} <br>`);
                            narudzba.css("cursor", "crosshair");
                            if (tip=="sell"){
                                narudzba.css("background-color", "rgb(179, 171, 138)");
                            }
                            else{
                                narudzba.css("background-color", "rgb(138, 110, 150)");
                                narudzba.css("color", "white");
                            }
                        }
                    }
                    )
                    narudzba.on('click', izbrisi);
                }
            }
        }
        )
    }
    )
    function izbrisi(){
        $.ajax(
        {
            url: "../rp2-burza/app/izbrisi_order.php",
            data:
            {
                id: this.id
            },
            dataType: "json",
            type: "GET",
        }
        )
        this.remove();
    }

    function 
</script>
</body>
</html>
<?php require_once __SITE_PATH . '/view/_footer.php'; ?>