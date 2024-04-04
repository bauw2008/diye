<?php
include "caches.class.php";
date_default_timezone_set('PRC');
//输出token
function out_token($path){
	$tt=cache("time_out_chk","cache_time_out",$path);
	//获取当前时间（后天）的00:00时间戳
	if (time()>=$tt) {
		Cache::$cache_path=$path."cache/";
		//设置缓存路径
		//删除除当前目录缓存文件
		Cache::dels();
		//重新写入当天时间缓存文件
		cache("time_out_chk","cache_time_out",$path);
	}
	$wjson=cache("token","get_token",$path,["token"]);
	return $wjson;
}
function cache($key,$f_name,$path,$ff=[]) {
	Cache::$cache_path=$path."cache/";
	//设置缓存路径
	$val=Cache::gets($key);
	if (!$val) {
		$data=call_user_func_array($f_name,$ff);
		Cache::put($key,$data);
		return $data;
	} else {
		return $val;
	}
}
function cache_time_out() {
	date_default_timezone_set("Asia/Shanghai");
	$tt=time() + 86400;
	return $tt;
}
function get_token($ip){
	return genToken(16);;
}

function genToken( $len = 32, $md5 = true ) {
	mt_srand( (double)microtime()*1000000 );
	$chars = array(
		'Q', '@', '8', 'y', '%', '^', '5', 'Z', '(', 'G', '_', 'O', '`',
		'S', '-', 'N', '<', 'D', '{', '}', '[', ']', 'h', ';', 'W', '.',
		'/', '|', ':', '1', 'E', 'L', '4', '&', '6', '7', '#', '9', 'a',
		'A', 'b', 'B', '~', 'C', 'd', '>', 'e', '2', 'f', 'P', 'g', ')',
		'?', 'H', 'i', 'X', 'U', 'J', 'k', 'r', 'l', '3', 't', 'M', 'n',
		'=', 'o', '+', 'p', 'F', 'q', '!', 'K', 'R', 's', 'c', 'm', 'T',
		'v', 'j', 'u', 'V', 'w', ',', 'x', 'I', '$', 'Y', 'z', '*'
		);
	$numChars = count($chars) - 1; $token = '';
	for ( $i=0; $i<$len; $i++ )
		$token .= $chars[ mt_rand(0, $numChars) ];
	if ( $md5 ) {
		$chunks = ceil( strlen($token) / 32 ); $md5token = '';
		for ( $i=1; $i<=$chunks; $i++ )
			$md5token .= md5( substr($token, $i * 32 - 32, 32) );
		$token = substr($md5token, 0, $len);
	} return $token;
}