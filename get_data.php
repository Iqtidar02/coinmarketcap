<?php
require_once 'autoload.php';
$currencyPair = $_GET['pair'];$currencyPair = str_replace('_','/',$currencyPair);
$data = array('one','two','1.1','2.2','3.3');
$url = 'https://coinmarketcap.com/currencies/bitcoin/#markets';
$urls = array(
    'BTC_USDT' => 'https://coinmarketcap.com/currencies/bitcoin/#markets',
    'ETH_USDT' => 'https://coinmarketcap.com/currencies/ethereum/#markets',
);
$url = $urls[$_GET['pair']];
$marketsData = array();
$html = file_get_html($url);
foreach($html->find('tr') as $element)
{
    $row = array();
    foreach($element->find('td') as $mytd)
    {
        $row[] = $mytd->plaintext;
    }
    if(!empty($row)){
        if($row[2] != $currencyPair){continue;}
        $marketsData[] = $row;
    }
}

/*$file_name = "excel/".time().".csv";
$file = fopen($file_name,"w");
fputcsv($file,array('Source','Pair,Volume (24h)','Price,Volume (%)', 'Date'));
foreach ($marketsData as $row){
    unset($row[0]);unset($row[6]);
    fputcsv($file,$row);
}*/


$successCount = 0;
$errorCount = 0;
foreach ($marketsData as $row){
    if(insert_market_data($row)){
        $successCount++;
    }
    else{
        $errorCount++;
    }
}

//echo "Success Count : $successCount<br>";
//echo "Error Count : $errorCount<br>";
//echo "<a href='downloadcsv.php?pair=".$_GET['pair']."'>Download CSV</a><br>";
//echo "<a href='index.php'>Home</a><br>";
//exit;

?>



<!DOCTYPE html>
<html>
<head>
    <title>CoinMarketCap</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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

<div class="container-fluid text-center">
    <h2>Updated Data Successfully...</h2>
    <br>
    <p>Success Count : <?=$successCount?> <span style="border: 1px solid;margin: 10px;"></span> Error Count :  <?=$errorCount?></p>
    <a href="view.php?pair=<?=$_GET['pair']?>" class="btn btn-default btn-lg">View Graph</a>
    <a href="downloadcsv.php?pair=<?=$_GET['pair']?>" class="btn btn-default btn-lg">Get CSV</a>
</div>

</body>
</html>

