<?php
require '../src/autoload.php';

// 参数选项
$options = [
  'appid' => 'xxxxx',
  'secret' => 'xxxxxxx',
  'gateway' => 'https://nr.xiaoxiaonb.club',
];

// 初始化
$Sdk = new YeThird\ThirdSdk($options);

// 调用的参数
$result = $Sdk->c2b([
  'amount' => 100,
  'order_no' => md5(time()),
  'uid' => uniqid(),
  'bank_code' => '20009',
  'notify_url' => 'http://xxxx.com/notify_url',
  'return_url' => 'http://xxxx.com/return_url',
]);

// echo $result;
var_dump($result);
