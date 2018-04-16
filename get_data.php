<?php
require_once 'autoload.php';
$data = array('one','two','1.1','2.2','3.3');
$url = 'https://coinmarketcap.com/currencies/bitcoin/#markets';
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

echo "Success Count : $successCount<br>";
echo "Error Count : $errorCount<br>";
exit;
?>