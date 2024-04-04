<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

require_once "aes.php";
require_once "config.php";
$db = Config::GetIntance();

$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
if (isset($_GET['id'])) {
    $androidid = $_GET['id'];
    $mealid = $db->mGet("itv_users", "meal", "where deviceid='$androidid'");
    $mealname = $db->mGet("itv_meals", "name", "where id='$mealid'");
    echo $mealname;
} 

if (isset($_POST['login'])) {
    $GetIP = new GetIP();
    $ip = $GetIP->getuserip();
    $num = $db->mGet("itv_users", "count(*)", "where ip='$ip'");
    if ($num >= $db->mGet("itv_config", "value", "where name='max_sameip_user'")) {
        header('HTTP/1.1 403 Forbidden');
        exit();
    } else {
        $json = $_POST['login'];
        $obj = json_decode($json);
        $region = $obj->region;
        $androidid = $obj->androidid;
        $mac = $obj->mac;
        $model = $obj->model;
        $nettype = $obj->nettype;
        //$appname = $obj->appname; 
        if ($ip == '' || $ip == '127.0.0.1') {
	        $ip = '127.0.0.1';
	        $region = 'localhost'; 
        }
        if (empty($region)) {
            $json = file_get_contents(dirname($url) . "/getIpInfo.php?ip=$ip");
            $len=mb_strpos($json,"\"region\":\"");
			$region = mb_substr($json,$len+10,mb_strpos($json,"\"",$len+10)-($len+10));
			$len=mb_strpos($json,"\"city\":\"");
			$region =$region.mb_substr($json,$len+8,mb_strpos($json,"\"",$len+8)-($len+8));
			$len=mb_strpos($json,"\"isp\":\"");
			$region =$region.mb_substr($json,$len+7,mb_strpos($json,"\"",$len+7)-($len+7));
        } 
        function genName() {
            global $db;
            $name = rand(10000000,99999999);
            $result = $db->mGet("itv_users", "*", "where name=$name");
            if (!$result) {
                unset($result);
                return $name;
            } else {
                genName();
            } 
        } 
        // status=1,正常用户；
        // status=0,停用用户;
        // status=-1,未授权用户
        // status=999为永不到期
        $nowtime = time(); 
        // 没有mac禁止登陆
	    if(strstr($mac,"获取地址失败") != false) {
	        header('HTTP/1.1 403 Forbidden');
	        exit();
	    }
        // mac是否匹配
        if ($row = $db->mCheckRow("itv_users", "name,status,exp,deviceid,mac,model,meal", "where mac='$mac'")) {
            // 匹配成功
            $days = ceil(($row['exp'] - time()) / 86400);
            $status = intval($row['status']);
            $name = $row['name'];
            $deviceid = $row['deviceid'];
            $mealid = $row['meal'];
            $exp = $row["exp"]; //收视期限，时间戳
            $status2 = $status;
            if ($days > 0 && $status == -1) {
                $status = 1;
            } else if ($status2 == -999) {
                $status = 1;
            } 
            if ($deviceid != $androidid){
            	$db->mSet("itv_users", "deviceid='$androidid',idchange=idchange+1", "where mac='$mac'"); 
            }
            // 更新位置，登陆时间
            $db->mSet("itv_users", "region='$region',ip='$ip',lasttime=$nowtime", "where mac='$mac'"); 
        } else {
            // 用户验证失败，识别用户信息存入后台
            $name = genName();
            $days = $db->mGet("itv_config", "value", "where name='trialdays'");
            if (empty($days)) {
                $days = 0;
            } 
            if ($days > 0) {
                $status = -1;
                $marks = '试用';
            } else if ($days == "-999") {
                $status = -999;
                $marks = '免费';
                $days = 3;
            } else {
                $status = -1;
                $marks = '未授权';
            } 
            $mealid = 1000;
            $status2 = $status;
            $exp = strtotime(date("Y-m-d"), time()) + 86400 * $days;
            $db->mInt("itv_users", "name,mac,deviceid,model,exp,ip,status,region,lasttime,marks", "$name,'$mac','$androidid','$model',$exp,'$ip',$status,'$region',$nowtime,'$marks'");
            if ($days > 0 && $status == -1) {
                $status = 1;
            } else if ($status2 == -999) {
                $status = 1;
            } 
        } 
        unset($row);
		
		$needauthor = $db->mGet("itv_config", "value", "where name='needauthor'");

		$adinfo = $db->mGet("itv_config", "value", "where name='adinfo'");
        $adtext = $db->mGet("itv_config", "value", "where name='adtext'");
        $showwea = $db->mGet("itv_config", "value", "where name='showwea'");
        $showtime = $db->mGet("itv_config", "value", "where name='showtime'");
		$imgstart = $db->mGet("itv_config", "value", "where name='imgstart'");
		$imgend = $db->mGet("itv_config", "value", "where name='imgend'");
		$epgurl = $db->mGet("itv_config", "value", "where name='epgurl'");
		$tmua = $db->mGet("itv_config", "value", "where name='tmua'");
        $dataurl = dirname($url) . "/data.php";

        if ($needauthor == 0 || ($status2 == -999)) {
            $status = 999;
        } 
			
		//$days=str_replace('-', '', $days.'');
		if($days<1 && $status != 999)
		{
			$showwea=0;
			$epgurl='';
			$tmua='';
			$days='过期';
		}
		else if($status == 999)
		{
			$days='永久';
		}
		else
			$days=$days.'天';
		
        $mealname = $db->mGet("itv_meals", "name", "where id='$mealid'");
        $adtext = '尊敬的用户，当前套餐为：' . $mealname . '，剩余时间：'. $days . '。' . $adtext;

        if ($showwea == 1) {
            $weaapi_id = $db->mGet("itv_config", "value", "where name='weaapi_id'");
            $weaapi_key = $db->mGet("itv_config", "value", "where name='weaapi_key'");
            $url = "https://www.tianqiapi.com/api?version=v6&appid=$weaapi_id&appsecret=$weaapi_key&ip=$ip";
			$url = "https://www.yiketianqi.com/free/day?appid=$weaapi_id&appsecret=$weaapi_key&unescape=1";
            $weajson = file_get_contents($url);
            $obj = json_decode($weajson);
            if (!empty($obj->city)) {
                $weather = date('今天n月d号') . $obj->week . '，' . $obj->city . '，' . $obj->tem . '℃' . $obj->wea . '，' . '气温:' . $obj->tem_night . '℃' . '～' . $obj->tem_day . '℃' . '，' . $obj->win . $obj->win_speed . '，' . '相对湿度:' . $obj->humidity . '，' . '空气质量:' . $obj->air .',气压:'.$obj->pressure;
                $adtext = $adtext . $weather;
            } 
        } 

        if ($status < 1) {
            $dataurl = '';
            $appUrl = '';
        } 

        $objres = array('adtext' => $adtext, 'showtime' => $showtime , 'imgstart' => $imgstart, 'imgend' => $imgend, 'epgurl' => $epgurl, 'tmua' => $tmua);

        $objres = str_replace("\\/", "/", json_encode($objres, JSON_UNESCAPED_UNICODE)); 
		echo $objres;
		/**
        $key = substr($key, 6, 17);
        $aes2 = new Aes($key);
        $encrypted = $aes2->encrypt($objres);
        mysqli_free_result($result);
        unset($arrprov, $objres);
        echo $encrypted;
		**/
    } 
} else {
    exit();
} 

?>