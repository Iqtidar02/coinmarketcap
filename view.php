<?php
require_once 'autoload.php';
$marketsData = get_market_data();
/*echo '<pre>';
print_r($marketsData);
echo '</pre>';
exit;*/

$sources = array();
foreach ($marketsData as $item){
    $sources[] = $item['source'];
}
$prices = array();
foreach ($marketsData as $item){
    $prices[] =  intval($item['price']);
}
$volumes = array();
foreach ($marketsData as $item){
    $volumes[] = intval($item['volume_24h']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bitcoin Markets - Chart</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
</head>
<body>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script>

    Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Bitcoin Markets <?=$currencyPair?>'
        },
        subtitle: {
            text: 'Source: coinmarketcap.com'
        },
        xAxis: {
            categories: <?=json_encode($sources)?>,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} $</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Price',
            data: <?=json_encode($prices)?>
        }, {
            name: 'Volume',
            data: <?=json_encode($volumes)?>
        }]
    });
</script>
<script>
    // tell the embed parent frame the height of the content
    if (window.parent && window.parent.parent){
        window.parent.parent.postMessage(["resultsFrame", {
            height: document.body.getBoundingClientRect().height,
            slug: "None"
        }], "*")
    }
</script>

</body>
</html>