<?php
require_once 'config.php';
$fileName = str_replace('/' , '_' , $currencyPair).'_'.date('Y-m-d').'_'.time().'.csv';
$marketsData = get_latest_market_data();
$content = 'Source,Pair,Volume (24h),Price,Volume (%), Date'.PHP_EOL;
foreach ($marketsData as $row){
    unset($row[0]);unset($row[6]);
    $content .= implode(',' , $row).PHP_EOL;
}
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="'.$fileName.'"');
echo $content; exit();
?>