<?php require_once __SITE_PATH . '/view/_header.php'; ?>
<?php require_once __SITE_PATH . '/view/view_util.php'; ?>  
<!DOCTYPE HTML>
<html>
<head>
<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.stock.min.js"></script>
<script>
$( document ).ready( function()
{
    let canvas = $("#canvas");
    let ctx = canvas.get(0).getContext("2d");
	$.ajax(
	{
		url: "../app/query.php",
		data:
		{
			id: var something=<?php echo json_encode($dionica['id']); ?>;
		},
		dataType: "json",
		success: function( data )
		{
            var dps1 = [], dps2= [];
            var stockChart = new CanvasJS.StockChart("chartContainer",{
                theme: "light2",
                exportEnabled: true,
                title:{
                text:""
                },
                subtitles: [{
                text: ""
                }],
                charts: [{
                axisX: {
                    crosshair: {
                    enabled: true,
                    snapToDataPoint: true
                    }
                },
                axisY: {
                    prefix: "$"
                },
                data: [{
                    type: "candlestick",
                    yValueFormatString: "$#,###.##",
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
                    dps1.push({x: new Date(data[i].date), y: [Number(data[i].open), Number(data[i].high), Number(data[i].low), Number(data[i].close)]});
                    dps2.push({x: new Date(data[i].date), y: Number(data[i].close)});
                }
                stockChart.render();
            };
            }
        )
    }
);
</script>
</head>
<body>
<div class="contentcontainer">
    <div class="card">
        <?php
        print_dionica_meta($dionica);
        print_dionica_ime($dionica);
        print_dionica_description($dionica);
        print_buy_sell_form();
        $_SESSION['dionica']=$dionica['id'];
        ?>
    </div>
</div>
<div id="chartContainer" style="height: 600px; width: 600px;"></div>
</body>
</html>
<?php require_once __SITE_PATH . '/view/_footer.php'; ?>
