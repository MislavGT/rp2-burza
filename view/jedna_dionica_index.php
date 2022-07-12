<?php require_once __SITE_PATH . '/view/_header.php'; ?>
<?php require_once __SITE_PATH . '/view/view_util.php'; ?>  
<!DOCTYPE HTML>
<html>
<head>
<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.stock.min.js"></script>
<style type="text/css">
    #chartContainer{
	position: absolute;
        right: 100px;
        top: 200px;
        height: 500px;
        width: 1000px;
    }
</style>

</head>
<body>
<script type="text/javascript">
$( document ).ready( function()
{
	$.ajax(
	{
		url: "../rp2-burza/app/query.php",
		data:
		{
			id: <?php echo json_encode($dionica['id']); ?>
		},
		dataType: "json",
        type: "GET",
		success: function( data )
		{
            console.log(data);
            var dps1 = [], dps2= [];
            var stockChart = new CanvasJS.StockChart("chartContainer",{
                theme: "light2",
                exportEnabled: true,
                title:{
                text: "Povijest cijena"
                },
                charts: [{
                axisX: {
                    crosshair: {
                    enabled: true,
                    snapToDataPoint: true
                    }
                },
                axisY: {
                    prefix: "kn"
                },
                data: [{
                    type: "candlestick",
                    yValueFormatString: "kn####.##",
                    dataPoints : dps1
                }]
                }],
                navigator: {
                data: [{
                    dataPoints: dps2
                }],
                slider: {
                    minimum: new Date(2022, 07, 01),
                    maximum: new Date(2022, 07, 31)
                }
                }
            });
                for(var i = 0; i < data.length; i++){
                    dps1.push({x: new Date(data.i.date), y: [Number(data.i.open), Number(data.i.high), Number(data.i.low), Number(data.i.close)]});
                    dps2.push({x: new Date(data.i.date), y: Number(data.i.close)});
                }
                stockChart.render();
            }
            }
        )
    }
);
</script>
<div class="contentcontainer">
    <div class="card">
        <?php
        print_dionica_meta($dionica);
        print_dionica_ime($dionica);
        print_dionica_description($dionica);
        print_buy_sell_form();
        $_SESSION['dionica'] = $dionica['id'];
        ?>
    </div>


    <?php
    $as = new AdminService();
    if ($as->is_admin($_SESSION['id'])) {
        require __SITE_PATH . '/view/dionica_admin.php';
    }
    ?>

</div>
<div id="chartContainer"></div>
</body>
</html>
<?php require_once __SITE_PATH . '/view/_footer.php'; ?>
