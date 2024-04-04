<?php
include_once "config.php";
$db = Config::GetIntance();

$mytext=$db->mGet("itv_config", "value", "where name='adtext'");
$mytime=$db->mGet("itv_config", "value", "where name='showtime'");
$obj=(Object)null;
$obj->gonggao=$mytext;
if((int)$mytime>0)$obj->ggtime=$mytime;
echo json_encode($obj,JSON_UNESCAPED_UNICODE);
unset($obj);
?>