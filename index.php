<?php
require_once('autoload.php');

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
    <h2>Home</h2>
    <br>
    <a href="view.php?pair=BTC_USDT" class="btn btn-default btn-lg">BTC - USDT</a>
    <a href="view.php?pair=ETH_USDT" class="btn btn-default btn-lg">ETH - USDT</a>
</div>

</body>
</html>

                     