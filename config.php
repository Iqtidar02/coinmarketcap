<?php

$currencyPair = 'BTC/USDT';

/* database*/
$servername = "us-cdbr-iron-east-05.cleardb.net";
$username = "b043de9dd162f7";
$password = "6e89345b";
$database = "heroku_d2683a7c6e5419a";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function insert_market_data($data){
    global $conn;

    $data['3'] = str_replace(array('$',','), '',$data['3']);
    $data['4'] = str_replace(array('$',','), '',$data['4']);
    $data['5'] = str_replace(array('%'), '',$data['5']);

    $sql = "INSERT INTO marketsdata(source,pair,volume_24h,price,volume_percent) VALUES ('$data[1]','$data[2]','$data[3]',{$data[4]},{$data[5]})";

    $result = false;
    if ($conn->query($sql) === TRUE) {
        $result = true;
    }
    else{
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    return $result;
}

function get_market_data(){
    global $conn;
    $data = array();
    $max_date = date('Y-m-d h:i:s');
    $sql = "SELECT max(date) as date FROM marketsdata limit 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $max_date = $row['date'];
            break;
        }
    }
    $sql = "SELECT * FROM marketsdata where date >= '$max_date'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

?>