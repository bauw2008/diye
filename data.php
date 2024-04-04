<?php
require_once"aes.php";
require_once "config.php";
include_once "dl/live_proxy_token.php";
$db = Config::GetIntance();
$channelNumber = 1;

$ez_password="";

function echoJSON1($category, $alisname, $psw) {
    global $db, $channelNumber;
    if ($alisname == '我的收藏') {
        $channelname = $alisname;
    } else {
        $channelname = $db->mGet("itv_channels", "name", "where category='$category'");
    } 
    if (!empty($channelname)) {
        $result = $db->mQuery("SELECT name,url FROM itv_channels where category='$category' order by id");
        $nameArray = array();
        while ($row = mysqli_fetch_array($result)) {
            if (!in_array($row['name'], $nameArray)) {
                $nameArray[] = $row['name'];
            } 
            $sourceArray[$row['name']][] = $row['url'];
        } 
        $objCategory = (Object)null;
        $objChannel = (Object)null;
        $channelArray = array();
        for($i = 0;
            $i < count($nameArray);
            $i++) {
            $objChannel = (Object)null;
            $objChannel->num = $channelNumber;
            $objChannel->name = $nameArray[$i];
            foreach ( $sourceArray[$nameArray[$i]] as $k => $val ) {
                if(strpos($sourceArray[$nameArray[$i]][$k],"cibn")>0 || strpos($sourceArray[$nameArray[$i]][$k],"woniu")>0){
                    if(strpos($sourceArray[$nameArray[$i]][$k],"?")>0){
                        $sourceArray[$nameArray[$i]][$k] = $sourceArray[$nameArray[$i]][$k]."&token=".out_token("dl/");
                    }else{
                        $sourceArray[$nameArray[$i]][$k] = $sourceArray[$nameArray[$i]][$k]."?token=".out_token("dl/");
                    }
                }
            }
            $objChannel->source = $sourceArray[$nameArray[$i]];
            $channelArray[] = $objChannel;
            $channelNumber++;
        } 
        $objCategory->name = $alisname;
        $objCategory->psw = $psw;
        $objCategory->data = $channelArray;
        unset($row,$nameArray, $sourceArray, $objChannel);
        mysqli_free_result($result);
        return $objCategory;
    } 
} 

function echoJSON($category, $alisname, $psw) {
    global $db, $channelNumber;
    $channelname = $db->mGet("itv_channels", "name", "where category='$category'");
	$myres='';
    if (!empty($channelname)) {
        $result = $db->mQuery("SELECT name,url FROM itv_channels where category='$category' order by id");
		if(!empty($psw)){
			$category.='_'.$psw.',#genre#\r\n';
		}
		else
			$category.=',#genre#\r\n';
		$myres=$category;
		
        while ($row = mysqli_fetch_array($result)) {
			$myname=$row['name'];
			$mycurl=$row['url'];
			$myres.=$row['name']. ',' .$mycurl. '\r\n';
        } 
		unset($row);
        mysqli_free_result($result);
        return $myres;
    } 
} 

if (isset($_POST['data'])) {
    $obj = json_decode($_POST['data']);
    $region = $obj->region;
    $mac = $obj->mac;
    $androidid = $obj->androidid;
    $model = $obj->model;
    $nettype = $obj->nettype;
    //$appname = $obj->appname;
    $randkey = $obj->rand;
    // 查找当前用户对应的套餐
    $result = $db->mQuery("SELECT meal,exp,mac from itv_users where deviceid='$androidid'");
    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if (empty($row["meal"])) {
            $mid = 1000;
            mysqli_free_result($result);
        } else {
            $mid = $row["meal"];
            mysqli_free_result($result);
        } 
		
		if(isset($row["exp"]))
		{
			if($row["exp"]<time())$mid = 1000; //时间到期
		}
		else
			$mid = 1000;
	
	
		if(isset($row["mac"]))
		{
			if(strcmp($row["mac"],$mac)!=0)$mid = 1000; //mac必须一致
		}
		else
			$mid = 1000;
		
    } else {
        $mid = 1000;
        mysqli_free_result($result);
    } 
    // 检测套餐是否存在，收视内容是否为空
    $result = $db->mQuery("select content from itv_meals where status=1 and id=$mid");
    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if (empty($row["content"])) {
            $m_text = false;
            mysqli_free_result($result);
        } else {
            $m_text = $row["content"];
            mysqli_free_result($result);
        } 
    } else {
        $m_text = false;
        mysqli_free_result($result);
    } 
    // 增加我的收藏
    //$contents[] = echoJSON('', "我的收藏", ''); 
	$contents[] = ''; 
    // 默认套餐不输出运营商,即默认套餐的ID不等于1000输出运营商和各省的输出
    if ($mid != 1000) {
        if (!empty($nettype)) {
            // 添加运营商频道数据,自动分配联通 电信 移动 对应的节目表
            $result = $db->mQuery("SELECT name,id,psw FROM itv_category where enable=1 and type='$nettype' order by id");
            while ($row = mysqli_fetch_array($result)) {
                $pdname = $row['name'];
                $psw = $row['psw'];
                $contents[] = echoJSON($pdname, $pdname, $psw);
            } 
            unset($row);
            mysqli_free_result($result);
        } 
    } 
    // 授权的套餐的数据
    if ($m_text) {
        $m_str = explode("_", $m_text);
        foreach ($m_str as $id => $meal_content) {
            $result = $db->mQuery("SELECT name,id,psw FROM itv_category where enable=1 and name='$meal_content' ORDER BY id asc");
            if (!mysqli_num_rows($result)) {
                mysqli_free_result($result);
            } else {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $pdname = $row['name'];
                $psw = $row['psw'];
                $contents[] = echoJSON($pdname, $pdname, $psw);
                unset($row);
                mysqli_free_result($result);
            } 
        } 
        unset($m_str, $m_text);
    } 
	
    $str = json_encode($contents, JSON_UNESCAPED_UNICODE);
	$str = str_replace('","','',$str);
	$str = str_replace('["','',$str);
	$str = str_replace('"]','',$str);
    $str = preg_replace('#null,#', '', $str);
    $str = stripslashes($str);
    //$str = base64_encode(gzcompress($str));
    $key = md5($key . $randkey.time());
    $key = substr($key, 13, 22);
	//echo $key.'<>';
    $aes = new Aes($key);
    $encrypted = $aes->encrypt($str);
	$encrypted=bin2hex(base64_decode($encrypted));
	//$encrypted=base64_encode($encrypted);
	echo $key.$encrypted;
} else {
    exit();
}