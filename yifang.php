<?php
include 'Qiniu/Config.php';
include 'Qiniu/functions.php';
include 'Qiniu/Auth.php';
include 'Qiniu/Processing/PersistentFop.php';
include 'Qiniu/Http/Client.php';
include 'Qiniu/Http/Request.php';
include 'Qiniu/Http/Response.php';
include 'Qiniu/Http/Error.php';
use Qiniu\Auth;
use Qiniu\Processing\PersistentFop;
use Qiniu\Http\Client;
use Qiniu\Http\Request;
use Qiniu\Http\Response;
use Qiniu\Http\Error;

include 'config.php';

$auth = new Auth($accessKey, $secretKey);


$token = $auth->uploadToken($bucket);
$key = urldecode($_GET['doc']);
$pfop = new PersistentFop($auth, $bucket);

$fops = 'yifangyun_preview|saveas/' . Qiniu\base64_urlSafeEncode($bucket . ':' . $key . '.pdf');

list($id, $err) = $pfop->execute($key, $fops);

if ($err != null) {
    var_dump($err);
} else {
    echo $id;
}
