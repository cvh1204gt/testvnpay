<?php
require_once("vnpay_config.php");

$vnp_TxnRef = rand(10000,99999); // Mã đơn hàng
$vnp_OrderInfo = "Thanh toan VNPAY demo";
$vnp_OrderType = "billpayment";
$vnp_Amount = $_POST['amount'] * 100; // Nhân 100 vì đơn vị là VND x 100
$vnp_Locale = 'vn';
$vnp_BankCode = 'NCB';
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

$inputData = array(
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_Amount" => $vnp_Amount,
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => $vnp_IpAddr,
    "vnp_Locale" => $vnp_Locale,
    "vnp_OrderInfo" => $vnp_OrderInfo,
    "vnp_OrderType" => $vnp_OrderType,
    "vnp_ReturnUrl" => $vnp_Returnurl,
    "vnp_TxnRef" => $vnp_TxnRef
);

// ✅ Thêm thời gian hết hạn tại đây (trước khi ksort)
$inputData["vnp_ExpireDate"] = date('YmdHis', strtotime('+15 minutes'));

if (isset($vnp_BankCode) && $vnp_BankCode != "") {
    $inputData['vnp_BankCode'] = $vnp_BankCode;
}

// Tiếp tục xử lý như cũ
ksort($inputData);
$query = "";
$hashdata = "";
foreach ($inputData as $key => $value) {
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
    $hashdata .= $key . "=" . $value . '&';
}
$query = rtrim($query, '&');
$hashdata = rtrim($hashdata, '&');

$vnp_Url .= "?" . $query;
$vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
$vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;

header('Location: ' . $vnp_Url);
exit;
