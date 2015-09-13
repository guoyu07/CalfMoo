<?php
include 'Qiniu/Config.php';
include 'Qiniu/functions.php';
include 'Qiniu/Auth.php';

use Qiniu\Auth;

include 'config.php';

$auth = new Auth($accessKey, $secretKey);

$token = $auth->uploadToken($bucket);

$output = json_encode(["uptoken" => $token]);

echo $output;
