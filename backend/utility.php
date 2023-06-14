<?php
function getTimeStamp()
{
    $now = date("Y-m-d H:i:s");
    return $now;
}
function build_sql_update($table, $data, $where)
{
    $cols = array();

    foreach ($data as $key => $val) {
        if ($val != null) {
            // check if value is not null then only add that colunm to array
            $cols[] = "$key = '$val'";
        }
    }
    $sql = "UPDATE $table SET " . implode(', ', $cols) . " WHERE $where";

    return ($sql);
}
function generateOrderId($orderId)
{
    return date("dmy") . "" . str_pad($orderId, 5, "0", STR_PAD_LEFT);
}
function generateNumber($numberOfDigit)
{
    return rand(pow(10, $numberOfDigit - 1), pow(10, $numberOfDigit) - 1);
}
function generateOTP($phoneNo, $otp)
{
    $postdata = http_build_query(
        array(
            'apikey' => 'OX48xLmFWsBi0eLz',
            'senderid' => 'PMHLTD',
            'templateid' => '1707166452426330213',
            'number' => $phoneNo,
            'message' => 'Hi User, ' . $otp . ' is the otp for Meenera login. PMHLTD',
        )
    );
    $opts = array('https' => array(
        'method' => 'POST',
        'header' => 'Content-type: application/json',
        'content' => $postdata,
    ),
    );
    // $context = stream_context_create($opts);
    // $unformatUrl = 'https://sms.textspeed.in/vb/apikey.php?apikey=OX48xLmFWsBi0eLz&senderid=PMHLTD&templateid=1707166452426330213&number=' . $phoneNo . '&message=Hi User, ' . $otp . ' is the otp for Meenera login. PMHLTD';
    // $url = preg_replace("/ /", "%20", $unformatUrl);
    // $result = file_get_contents($url, false, $context);
    // return $result;

    //
    $ch = curl_init();
    $unformatUrl = 'https://sms.textspeed.in/vb/apikey.php';
    curl_setopt($ch, CURLOPT_URL, $unformatUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'apikey=OX48xLmFWsBi0eLz&senderid=PMHLTD&templateid=1707166452426330213&number=' . $phoneNo . '&message=Hi User, ' . $otp . ' is the otp for Meenera login. PMHLTD');
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
    //

}
function isOrderAmountMatched($orderId, $amount)
{
    $url = "https://www.meenera.com/newsite/Backend/api/orders.php";

    $curl = curl_init($url);
    $headers = array(
        "Accept: application/json",
        "Content-Type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $data = array(
        "do" => "isOrderAmountMatched",
        "params" => [
            "orderNo" => $orderId,
            "amount" => $amount,
        ],
    );

    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

    $resp = curl_exec($curl);
    curl_close($curl);
    return $resp;
}
function updateStatusBE($status, $orderId)
{
    $url = "https://www.meenera.com/newsite/Backend/api/orders.php";

    $curl = curl_init($url);
    $headers = array(
        "Accept: application/json",
        "Content-Type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $data = array(
        "do" => "updateOrderStatus",
        "params" => [
            "orderId" => $orderId,
            "orderStaus" => $status,
        ],
    );

    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

    $resp = curl_exec($curl);
    curl_close($curl);
    return $resp;
}

function rm_special_char($str)
{
    return preg_replace('/[^A-Za-z0-9\-]/', '', $str);
}
function findObjectByinArray($findKey, $findVal, $array = [])
{
    foreach ($array as $element) {
        if ($element->$findKey == $findVal) {
            return $element;
        }
    }
    return false;
}
