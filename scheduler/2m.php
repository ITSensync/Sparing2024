<?php
$conn=mysqli_connect("localhost","root","makanminggu12");
mysqli_select_db($conn,"sparingdb");

ini_set('display_errors', 1);
date_default_timezone_set('Asia/jakarta');

$tgl = date("Y-m-d H:i:s");
$min2 = date('Y-m-d H:i:s', strtotime($tgl . "-2 minutes"));
$unixx = strtotime(date('Y-m-d H:i:s'));
$secretkeys = file_get_contents('http://203.166.207.50/api/klhk/secret-sensor/');

function is_connected()
{
    $connected = @fsockopen("www.google.com", 80);
    //website, port  (try 80 or 443)
    if ($connected) {
        $is_conn = "ada"; //action when connected
        fclose($connected);
    } else {
        $is_conn = "tidak"; //action in connection failure

    }
    return $is_conn;
}

$uid = 12198973017007;
$connecz = is_connected();

$query = "SELECT round (AVG(cod),2) AS cod, round(AVG(tss),2) AS tss, round(AVG(nh3n),2) AS nh3n, round(AVG(ph),2) AS ph,SUM(debit) AS debit, SUM(debit2) AS debit2
          FROM rawtb WHERE `time` BETWEEN '$min2' AND '$tgl'";
$data = mysqli_query($conn, $query);

$d = mysqli_fetch_array($data, MYSQLI_ASSOC);
// $d = $_GET['debit'];
$debit = $d['debit'];

$debit2 = $d['debit2'] /2 ;

// JSON to KLHK
$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
$params = array(
    'uid' => $uid,
    'datetime' => $unixx,
    'pH' => $d['ph'],
    'cod' => $d['cod'],
    'tss' => $d['tss'],
    'nh3n' => $d['nh3n'],
    'debit' => $debit2,

);
$payload = json_encode($params);
// echo $payload;
$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secretkeys, true);
$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://203.166.207.50/api/server-uji');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,  http_build_query(array('token' => $jwt)));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // On dev server only!
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$result = curl_exec($ch);
// endjson to klhk 



// json to server sensync
$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
$params2 = array(
	'uid' => $uid,
	'datetime' => $unixx,
	'pH' => $d['ph'],
	'cod' => $d['cod'],
	'tss' => $d['tss'],
	'nh3n' => $d['nh3n'],
	'debit' => $d['debit2'],

);
$payload2 = json_encode($params2);
echo $payload2;
$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload2));
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secretkeys, true);
$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
// 

$ids = "sparing03";
array_push($params2, $result, $ids, $debit);
$jsons = json_encode($params2);
$ch3 = curl_init();
// curl_setopt($ch3, CURLOPT_URL, 'http://secure.getsensync.com/sparing2024/insert.php');
curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch3, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch3, CURLOPT_POST, 1);
curl_setopt($ch3, CURLOPT_POSTFIELDS, $jsons);
curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, false); // On dev server only!
curl_setopt($ch3, CURLOPT_SSL_VERIFYHOST, false);
$resultss = curl_exec($ch3);
$responses = json_decode($resultss, true);

//end json to sensync

$waktu = date("Y-m-d H:i:s", $unixx);
$insert = "INSERT INTO maintb (unixtime,`time`,cod,tss,nh3n,ph,debit,debit2,feedback,stat_conn)
                VALUES('$unixx','$waktu','$d[cod]', '$d[tss]','$d[nh3n]','$d[ph]', '$d[debit]','$d[debit2]','$result','$connecz')";
$asn = mysqli_query($conn, $insert);

$query2 = "INSERT INTO token (`time`,token) VALUES ('$waktu','$jwt')";
$inserts = mysqli_query($conn, $query2);

$ss = "UPDATE secretkey SET waktu='$waktu',`key`='$secretkeys' WHERE id=1";
$insertsc = mysqli_query($conn, $ss);
if ($asn) {
    echo "received";
} else {
    echo "gagal";
}
