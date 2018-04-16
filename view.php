<?php
require_once 'autoload.php';
$currencyPair = $_GET['pair'];$currencyPair = str_replace('_','/',$currencyPair);
$marketData = get_latest_market_data();
$marketDataByDate = get_market_data_by_date();

$sources = array();
foreach ($marketData as $item){
    $sources[] = $item['source'];
}
$prices = array();
foreach ($marketData as $item){
    $prices[] =  intval($item['price']);
}
$volumes = array();
foreach ($marketData as $item){
    $volumes[] = intval($item['volume_24h']);
}
$dates = array();$dateVolumes = array();
foreach ($marketDataByDate as $row){
    $dates[] = date('Y-m-d',strtotime($row['date']));
    $dateVolumes[] = intval($row['volume_24h']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bitcoin Markets - Chart</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
</head>
<body>
<div class="text-center"><a href="downloadcsv.php">Download CSV File</a><br></div>
<div id="containerBarChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<br><br><br>
<div id="containerLineChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script>

    Highcharts.chart('containerBarChart', {
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
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
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


    Highcharts.chart('containerLineChart', {
        chart: {
            type: 'line'
        },
        title: {
            text: 'Volume Data - <?=$currencyPair?>'
        },
        subtitle: {
            text: 'Source: coinmarketcap.com'
        },
        xAxis: {
            categories: <?=json_encode(array_values($dates))?>
        },
        yAxis: {
            title: {
                text: 'Volume ($)'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: false
                },
                enableMouseTracking: false
            }
        },
        series: [{
            name: '<?=$currencyPair?>',
            data: <?=json_encode(array_values($dateVolumes))?>
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

<div class="footer"><a href="index.php">Home</a></div>
</body>
</html>
