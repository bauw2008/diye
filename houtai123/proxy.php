<?php
require_once "view.section.php";

if(isset($_POST['src'])){
	$src=$_POST['src'];
	$proxy=$_POST['proxy'];
	//$sql="INSERT into itv_movie (name,api,state) values('$name','$api',1)";
	//$db->mQuery($sql);
	$db->mInt("itv_proxy", "src,proxy", "'$src','$proxy'");
}

if(isset($_POST['movie_edit_id'])){
	$id=$_POST['movie_edit_id'];
	$movie_edit_api=$_POST['movie_edit_api'];
	$movie_edit_name=$_POST['movie_edit_name'];
	//$sql="UPDATE itv_movie SET name='$movie_edit_name',api='$movie_edit_api' where id =".$id;
	//$db->mQuery($sql);
	$db->mSet("itv_movie", "name = '$movie_edit_name', api = '$movie_edit_api'", "where id = $id");
}

if(isset($_GET['delete'])){
	$id=$_GET['id'];
	//$sql="delete from itv_movie where id =".$id;
	//$db->mQuery($sql);
	$db->mDel("itv_movie", "where id = $id");
}

if(isset($_GET['toggle'])){
	$id=$_GET['id'];
	$state=$_GET['toggle'];
	//$sql="update itv_movie set state = ".$state." where id =".$id;
	//$db->mQuery($sql);
	$db->mSet("itv_movie", "state = '$state'", "where id = $id");
}

//getTable
$result=$db->mQuery("select * from itv_movie");
$tableBody="";
$index_table=0;
while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
	$index_table++;
	$state = $row["state"]=="1"?"<span class='label label-success'>上线</span>":"<span class='label label-danger'>停用</span>";
	$state_edit = $row["state"]=="1"?"<a class='btn btn-sm btn-danger' href='movie.php?toggle=0&id=".$row['id']."'>停用</a>":"<a class='btn btn-sm btn-success' href='movie.php?toggle=1&id=".$row['id']."'>上线</a>";
	$tableBody.="<tr align=\"center\"><td>".$index_table."</td><td>".$row["name"]."</td><td>".$state."</td><td>".$row["api"]."</td><td>".$state_edit."<button  class='btn btn-sm btn-primary' data-toggle='modal' data-target='#myModal' onclick='edit(\"".$row["name"]."\",\"".$row["api"]."\",".$row['id'].")'>编辑</button><a class='btn btn-sm btn-warning' href='movie.php?delete=1&id=".$row['id']."'>删除</a></td></tr>";
}
mysqli_free_result($result);
?>
<script type="text/javascript">
	function edit(arg,arg1,arg2) {
		// body...
		document.getElementById('sig_name').value = arg;
		document.getElementById('sig_id').value = arg2;
		document.getElementById('sig_api').value = arg1;
	}
</script>

<!--页面主要内容-->
<main class="lyear-layout-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header"><h4>接口列表</h4></div>
					<div class="tab-content">
						<div class="tab-pane active">
							<div class="form-group">
								<div class="table-responsive" >
									<?php require_once "mealsedit.php" ?>
									<table class="table table-hover table-vcenter">
										<tr>
											<td colspan="5">
												<form class="form-inline" method="post" action="?act=add">
													<label class="control-label">接口名称:</label>
													<input class="form-control input-sm" style="width: 10%;display: inline;" type="text" name="movie_name">
													<label class="control-label">接口地址:</label>
													<input class="form-control input-sm " style="width: 20%;display: inline;" type="text" name="movie_api">
													<button class="btn btn-sm btn-primary" type="submit">新增接口</button>
													<button class="btn btn-sm btn-primary" type="reset">重置</button>
												</form>
											</td>
										</tr>
										<tr align="center">
											<td>接口编号</td>
											<td>接口名称</td>
											<td>接口状态</td>
											<td>接口地址</td>
											<td>操作</td>
										</tr>
										<tbody>
											<?php echo $tableBody; ?>
										</tbody>
									</table>
									<!-- <table align="center">
										<td style="font-size:14px;font-weight:bold;color:red;">
											注：套餐新增加最大可以支持20个！
										</td>
									</table> -->
								</div>
							</div>
							<!-- <nav>
								<ul class="pager">
									<li><a href="<?php if($page>1){$p=$page-1;}else{$p=1;} echo '?page='.$p.'&order='.$order.'&keywords='.$keywords?>">上一页</a></li>
									<li class="previous"><a href="<?php echo '?page=1&order='.$order.'&keywords='.$keywords?>">首页</a></li>
									<li class="next"><a href="<?php echo '?page='.$pageCount.'&order='.$order.'&keywords='.$keywords?>">尾页</a></li>
									<li><a href="<?php if($page<$pageCount){$p=$page+1;} else {$p=$page;} echo '?page='.$p.'&order='.$order.'&keywords='.$keywords?>">下一页</a></li>
								</ul>
							</nav> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="myModalLabel">编辑</h4>
				</div>
				<form method="post">
					<div class="modal-body">
						<div class="input-group m-b-10" style="display: none;">
							<span class="input-group-addon" id="basic-addon3">接口名称：</span>
							<input type="text" class="form-control" id="sig_id" name="movie_edit_id" aria-describedby="basic-addon3">
						</div>
						<div class="input-group m-b-10">
							<span class="input-group-addon" id="basic-addon3">接口名称：</span>
							<input type="text" class="form-control" id="sig_name" name="movie_edit_name" placeholder="接口地址啦...比如EZ视屏网">
						</div>
						<div class="input-group m-b-10">
							<span class="input-group-addon" id="basic-addon3">接口地址：</span>
							<input type="text" class="form-control" id="sig_api" name="movie_edit_api" placeholder="http://..cms">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
						<button type="submit" class="btn btn-primary">点击保存</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</main>
<!--End 页面主要内容-->
</div>
</div>