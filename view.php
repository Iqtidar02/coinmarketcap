<?php
require_once 'autoload.php';
$currencyPair = $_GET['pair'];$currencyPair = str_replace('_','/',$currencyPair);

/*-- Graph1 Start--*/
$marketData = get_latest_market_data();
$latestDataDate = '';
if(!empty($marketData[0])){$latestDataDate=$marketData[0]['date'];}
$marketDataByDate = get_last_week_market_data();
$marketVolumeDiffByDate = $marketDataByDate;
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
/*-- Graph1 End--*/


/*-- Graph2 Start--*/
$marketVolumeDiffDates = array();
$marketVolumeDiff = array();
for ($i=0;$i<sizeof($marketVolumeDiffByDate); $i++){
    $marketVolumeDiff[$i] = intval($marketVolumeDiffByDate[$i]['volume_24h']);
    $marketVolumeDiffDates[$i] = date('Y-m-d',strtotime($marketVolumeDiffByDate[$i]['date']));
}
$marketVolumeDiffCalculated = $marketVolumeDiff;
for ($i=0;$i<sizeof($marketVolumeDiff)-1; $i++){
    $marketVolumeDiffCalculated[$i+1] -= $marketVolumeDiff[$i];
}
/*-- Graph2 End--*/


/*-- Graph3 Start--*/
$dates = array();$dateVolumes = array(); $weekTotalVolume = 0;
foreach ($marketDataByDate as $row){
    $dates[] = date('Y-m-d',strtotime($row['date']));
    $weekTotalVolume+=intval($row['volume_24h']);
    $dateVolumes[] = intval($row['volume_24h']);
}
/*-- Graph3 End--*/

?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bitcoin Markets - Chart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">CoinMarketCap</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a href="index.php">HOME</a></li>
            <li><a href="view.php?pair=BTC_USDT">BTC/USDT</a></li>
            <li><a href="view.php?pair=ETH_USDT">ETH/USDT</a></li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">UPDATE DATA
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="get_data.php?pair=BTC_USDT">BTC</a></li>
                    <li><a href="get_data.php?pair=ETH_USDT">ETH</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<br>
<div id="containerBarChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<br><br>
<div id="containerDiffBarChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<br><br>
<div id="containerLineChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<script>

    Highcharts.chart('containerBarChart', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Market History <?=$currencyPair?>'
        },
        subtitle: {
            text: 'Source: coinmarketcap.com<br><?=$latestDataDate?>'
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
        /* tooltip: {
         headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
         pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
         '<td style="padding:0"><b>${point.y}</b></td></tr>',
         footerFormat: '</table>',
         shared: true,
         useHTML: true
         },*/
        tooltip: {
            formatter: function () {
                var price = this.points[0].y.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
                var volume = this.points[1].y.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
                var key = this.x;
                return '<span><b>'+key+'</b></span><br><table><tr><td>Price: $'+price+'</td></tr><br><tr><td>Volume: $'+volume+'</td></tr></table>';

            },
            shared: true
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

    Highcharts.chart('containerDiffBarChart', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Market Volume Difference <?=$currencyPair?>'
        },
        xAxis: {
            categories: <?=json_encode(array_values($marketVolumeDiffDates))?>
        },
        yAxis: {
            title: {
                text: 'Volume ($)'
            }
        },
        credits: {
            enabled: false
        },
        tooltip: {
            formatter: function () {
                var date = this.x;
                var volume = this.y.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
                return '<span>'+date+'</span><br><span>Volume: $'+volume+'</span>';

            },
            shared: true
        },
        series: [{
            name: 'Date',
            data: <?=json_encode(array_values($marketVolumeDiffCalculated))?>
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
            text: 'Source: coinmarketcap.com <br>Total Volume: $<?=number_format($weekTotalVolume)?>'
        },
        tooltip: {
            formatter: function () {
                var date = this.x;
                var volume = this.y.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
                return '<span>'+date+' - $'+volume+'</span>';

            },
            shared: true
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
                enableMouseTracking: true
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

</body>
</html>
