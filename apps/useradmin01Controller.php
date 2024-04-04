<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

if ($_SESSION['useradmin'] == 0) {
    echo"<script>alert('你无权访问此页面！');history.go(-1);</script>";
}

function genName() {
	global $db;
	$name = rand(900000000,999999999);
	$result = $db->mGet("itv_users", "*", "where name=$name");
	if (!$result) {
		unset($result);
		return $name;
	} else {
		$result = $db->mGet("chzb_serialnum", "*", "where sn=$name");
		if(!$result){
			unset($result);
			genName();
		}else{
			return $name;
		}
	} 
}

//单个生成SN帐号
if(isset($_POST['submitsn1'])){
	$sn = $_POST['snNumber'];
	$exp = $_POST['exp'];
	$marks = $_POST['marks'];
	$vip = $_POST["meal_s"];   //套餐编号
	$snall = '';
	$nowtime=time();
	if (empty($sn) || empty($vip)) {
		echo("<script>lightyear.notify('要生成的账号或套餐不能为空！', 'danger', 3000);</script>");
	}else{
		$result = $db->mQuery("SELECT * from itv_users where name=$sn");
		if($row = mysqli_fetch_array($result)){
			unset($row);
			mysqli_free_result($result);
			echo("<script>lightyear.notify('该账号已经存在', 'danger', 3000);</script>");
			
		}else{
			$result = $db->mQuery("SELECT * from itv_serialnum where sn=$sn");
			if($row = mysqli_fetch_array($result)){
				unset($row);
				mysqli_free_result($result);
				echo("<script>lightyear.notify('该账号已经存在', 'danger', 3000);</script>");
				
			}else{
/* 				$sql = "INSERT into itv_serialnum (sn,exp,author,createtime,marks,vip,regid,regtime,enable) values($sn,$exp,'$user',$nowtime,'$marks',$vip,0,0,0)";
				$db->mQuery($sql);
				mysqli_free_result($result); */
				$db->mInt("itv_serialnum", "sn,exp,author,createtime,marks,vip,regid,regtime,enable", "$sn,$exp,'$user',$nowtime,'$marks',$vip,0,0,0");
				echo("<script>lightyear.notify('恭喜，账号已生成！', 'success', 3000);</script>");
			}
		}
	}
}

//批量生成SN帐号
if(isset($_POST['submitsn2'])){
	$snCount = $_POST['snCount'];
	if ($snCount<=0 || empty($_POST["meal_s"])) {
		echo("<script>lightyear.notify('要生成的账号或套餐不能为空！', 'danger', 3000);</script>");
	}
	$exp = $_POST['exp'];
	$marks = $_POST['marks'];
	$snall = '';
	$isvip = $_POST["meal_s"];   //套餐编号
	for ($i=0; $i <$snCount ; $i++) { 
		$sn = genName();
		$nowtime = time();
		$snall = $snall.$sn;
		//$sql="INSERT into itv_serialnum (sn,exp,author,createtime,marks,vip,regid,regtime,enable) values($sn,$exp,'$user',$nowtime,'$marks',$isvip,0,0,0)";
		//mysqli_query($con,$sql);
		$db->mInt("itv_serialnum", "sn,exp,author,createtime,marks,vip,regid,regtime,enable", "$sn,$exp,'$user',$nowtime,'$marks',$isvip,0,0,0");

	}
	echo("<script>lightyear.notify('恭喜，账号已生成完成！', 'success', 3000);</script>");
}

if(isset($_POST['submitdel'])){
	if (empty($_POST['sn'])) {
		echo("<script>lightyear.notify('请选择要删除的账号！', 'danger', 3000);</script>");
	}else {
		foreach ($_POST['sn'] as $sn){
			//$db->mQuery("delete from itv_serialnum where sn=$sn");
			$db->mDel("itv_serialnum", "where sn=$sn");
		}
		echo("<script>lightyear.notify('选中授权号已删除！', 'success', 3000);</script>");
	}
}

if(isset($_POST['submitclear'])){
	//$db->mQuery("delete from itv_serialnum where enable=1");
	$db->mDel("itv_serialnum", "where enable=1");
	echo("<script>lightyear.notify('已清空激活授权号！', 'success', 3000);</script>");
}

if(isset($_POST['submitadddays'])){
	if (empty($_POST['sn'])) {
		echo("<script>lightyear.notify('请选择要修改的账号！', 'danger', 3000);</script>");
	}else {
		$days=$_POST['exp'];
		foreach ($_POST['sn'] as $sn){
			//$db->mQuery("UPDATE itv_serialnum set exp=$days where sn=$sn and enable=0");
			$db->mSet("itv_serialnum", "exp=$days", "where sn=$sn and enable=0");
		}	
		echo("<script>lightyear.notify('选中用户天数已修改！', 'success', 3000);</script>");		
	}
}
if(isset($_POST['submitmodifymarks'])){
	if (empty($_POST['sn'])) {
		echo("<script>lightyear.notify('请选择要修改备注的账号！', 'danger', 3000);</script>");
	}else {
		$marks=$_POST['marks'];
		foreach ($_POST['sn'] as $sn){
			$db->mSet("itv_serialnum", "marks='$marks'", "where sn=$sn and enable=0");
			//$db->mQuery("UPDATE itv_serialnum set marks='$marks' where sn=$sn and enable=0");		
		}
		echo("<script>lightyear.notify('选中用户备注已修改！', 'success', 3000);</script>");
	}
}

// 搜索关键字
if (isset($_GET['keywords'])) {
    $keywords = trim($_GET['keywords']);
	$searchparam="and sn like '%$keywords%' or regtime like '%$keywords%' or regid like '%$keywords%' or exp like '%$keywords%' or author like '%$keywords%' or createtime like '%$keywords%' or marks like '%$keywords%'";
} 
$keywords = trim($_GET['keywords']);

//处理显示数量
if(isset($_POST['recCounts'])){
	$recCounts = $_POST['recCounts'];
	$db->mSet("itv_admin", "showcounts=$recCounts", "where name='$user'");
}

// 获取每页显示数量
if ($row = $db->mGetRow("itv_admin", "showcounts", "where name='$user'")) {
    $recCounts = $row['showcounts'];
} else {
    $recCounts = 100;
} 
unset($row);

// 获取当天上线用户总数
$todayTime = strtotime(date("Y-m-d"), time());
if ($row = $db->mGetRow("itv_users", "count(*)", "where status>-1 and lasttime>$todayTime")) {
    $todayuserCount = $row[0];
} else {
    $todayuserCount = 0;
} 
unset($row);

// 获取当天授权用户总数
if ($row = $db->mGetRow("itv_users", "count(*)", "where status>-1 and authortime>$todayTime")) {
    $todayauthoruserCount = $row[0];
} else {
    $todayauthoruserCount = 0;
} 
unset($row);

// 获取过期用户
$nowTime = time();
if ($row = $db->mGetRow("itv_users", "count(*)", "where status=1 and exp<$nowTime")) {
    $expuserCount = $row[0];
} else {
    $expuserCount = 0;
} 
unset($row);

//获取账号总数
if ($row = $db->mGetRow("itv_serialnum", "count(*)")) {
	$userCount=$row[0];
	$pageCount=ceil($row[0]/$recCounts);
}else{
	$userCount=0;
	$pageCount=1;
}
unset($row);

// 获取当前页
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
} 
// 获取排序依据
if (isset($_GET['order'])) {
    $order = $_GET['order'];
} else {
    $order = 'id';
} 

// 处理跳转逻辑
if (isset($_POST['jumpto'])) {
    $p = $_POST['jumpto'];
    if (($p <= $pageCount) && ($p > 0)) {
        echo "<script language=JavaScript>location.href='useradmin01.php' + '?page=$p&order=$order';</script>";
    } 
} 

//套餐数组
$arr_meal=[];
//获取已上线的套餐列表
$result = $db->mQuery("select id,name from itv_meals where status=1 ORDER BY id ASC");
if (!mysqli_num_rows($result)) {
	mysqli_free_result($result);
	$meal_select="<select class=\"form-control btn btn-default dropdown-toggle\" id=\"sel\" name=\"meal_s\"><option value=\"1000\" selected>默认/测试套餐</select>";
	$arr_meal[1000] = "默认/测试套餐";
}else {
	$meal_select="<select class=\"form-control btn btn-default dropdown-toggle\" id=\"sel\" name=\"meal_s\" > ";
	$meal_select .= "<option value=\"\">请选择套餐</option>";
	while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
		$meal_select .= "<option value=\"".$row["id"]."\">".$row["name"]."</option>";
		$arr_meal[$row["id"]]=$row["name"];
	}
	$meal_select.="</select>";
	mysqli_free_result($result);
}

?>