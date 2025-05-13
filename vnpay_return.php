<?php
require_once("vnpay_config.php");

$vnp_SecureHash = $_GET['vnp_SecureHash'];
$inputData = array();
foreach ($_GET as $key => $value) {
    if ($key != "vnp_SecureHash" && $key != "vnp_SecureHashType") {
        $inputData[$key] = $value;
    }
}

ksort($inputData);
$hashData = "";
foreach ($inputData as $key => $value) {
    $hashData .= $key . "=" . $value . "&";
}
$hashData = rtrim($hashData, "&");

$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

if ($secureHash == $vnp_SecureHash) {
    if ($_GET['vnp_ResponseCode'] == '00') {
        echo "Thanh toán thành công!";
        // Lưu đơn hàng vào database tại đây nếu cần
    } else {
        echo "Thanh toán thất bại: " . $_GET['vnp_ResponseCode'];
    }
} else {
    echo "Chuỗi hash không hợp lệ!";
}
