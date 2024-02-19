<?php 
include "../koneksi/koneksi.php";
ini_set('display_errors',1);
date_default_timezone_set('Asia/Jakarta');
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
$connecz = is_connected();

$secretkeys = file_get_contents('http://203.166.207.50/api/klhk/secret-sensor/');
$ids = "sparing03";
$uid=1213131414;


$select = "SELECT * FROM maintb WHERE stat_conn=0 OR feedback= '' OR feedback IS NULL ORDER BY `time` DESC LIMIT 10";
$asd = mysqli_query($conn, $select);


while ($data = mysqli_fetch_array($asd)) {
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $params = array(
        'uid' => $uid,
        'datetime' => $data['unixtime'],
        'pH' => $data['ph'], 
        'cod' => $data['cod'],
        'tss' => $data['tss'],
        'nh3n' => $data['nh3n'],
        'debit' => $data['debit2'],
    );
    $payload = json_encode($params);
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secretkeys, true);
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, 'http://203.166.207.50/api/server-uji');
     //curl_setopt($ch, CURLOPT_URL, 'http://203.166.207.50/api/klhk');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,  http_build_query(array('token' => $jwt)));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // On dev server only!
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);

    array_push($params, $result, $ids, $data['debit2']);
    $jsons = json_encode($params);
    $ch3 = curl_init();
    curl_setopt($ch3, CURLOPT_URL, 'http://secure.getsensync.com/sparing2024/insert.php');
    curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch3, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch3, CURLOPT_POST, 1);
    curl_setopt($ch3, CURLOPT_POSTFIELDS, $jsons);
    curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, false); // On dev server only!
    curl_setopt($ch3, CURLOPT_SSL_VERIFYHOST, false);
    $resultss = curl_exec($ch3);

    $updates = "UPDATE maintb SET feedback='$result', stat_conn='$connecz' WHERE unixtime=$data[unixtime]";
    $upp = mysqli_query($conn, $updates);
  
  if ($upp) {
  echo "data berhasil di update <br>";
  } else {
  echo "data gagal di update <br>";
  }
  
   if ($resultss === false) {
        echo "Pengiriman data gagal. <br>";
    } else {
        echo "Data berhasil dikirim. <br>";
    }
}

?>



?>