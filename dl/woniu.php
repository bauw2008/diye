<?php
//必要
include_once "live_proxy_token.php";
$token = out_token("./");

if($_GET['token']!=$token || is_null($_GET['token']) || empty($_GET['token'])){
	//echo "QQ群：937123294 EZ视频动态源验证错误 token为".$token;
	return 0;
}

$id = $_GET['id'];
$bstrURL = 'https://apiapp.hunancatv.com/api/live/playAddress?type=3&channelId='.$id;
$sign = md5('c=1&channelId='.$id.'&type=3&v=109&key=ae07e6df6a17c986cf11d36e3311a0dd');
$headers = array('c: 1','v: 109','sign: '.$sign);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $bstrURL);                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_USERAGENT, 'okhttp/3.14.4' );
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$data = curl_exec($ch);
curl_close($ch);
$json = json_decode($data);
$url = $json->d->playAddress;
$url = str_replace('\u003d','=',$url);
$url = str_replace('\u0026','&',$url);
header('location:'.$url);
?>
