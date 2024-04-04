<?php require_once "view.section.php";require_once "../apps/useradmin01Controller.php"; ?>

<script type="text/javascript">
	function submitForm(){
		var form = document.getElementById("recCounts");
		form.submit();
	}
	function submitjump(){
		var form = document.getElementById("jumpto");
		form.submit();
	}
	function quanxuan(a){
		var ck=document.getElementsByName("sn[]");
		for (var i = 0; i < ck.length; i++) {
			if(a.checked){
				ck[i].checked=true;
			}else{
				ck[i].checked=false;
			}
		}
	}
	function copytoclip(){
		var ck=document.getElementsByName("sn[]");
		var clipBoardContent="";
		for (var i = 0; i < ck.length; i++) {
			if(ck[i].checked){
				clipBoardContent+=ck[i].value+"\r\n";
			}

		}
		if (clipBoardContent === undefined || clipBoardContent.length == 0) {
			alert("请选择要复制的帐号");
			return false;
		}
		var oInput = document.createElement('textarea');
		oInput.value = clipBoardContent;
		document.body.appendChild(oInput);
    oInput.select(); // 选择对象
    document.execCommand("Copy"); // 执行浏览器复制命令
    oInput.className = 'oInput';
    document.body.removeChild(oInput);
    alert("复制成功，请粘贴到记事本");
}
</script>
<script type="text/javascript">
function submitForm(){
    var form = document.getElementById("recCounts");
    form.submit();
}
</script>

<!--页面主要内容-->
<main class="lyear-layout-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						<h4>账号列表</h4>
						<form  class="pull-right" method="POST">
							<button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#gensn1">生成账号</button>
							<button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#gensn2">批量生成</button>
						</form>
					</div>
					<div id="listctr" class="card-toolbar clearfix">
						<div class="btn-block" >
							<label>用户总数：<?php echo $userCount; ?></label>
							<label>今日上线：<?php echo $todayuserCount; ?></label>
							<label>今日授权：<?php echo $todayauthoruserCount; ?></label>
							<label>过期用户：<?php echo $expuserCount; ?></label>
						</div>
						<form class="pull-right search-bar" method="get" role="form">
							<div class="input-group">
								<div class="input-group-btn">
									<input class="form-control input-sm" style="width: 225px;" type="text" name="keywords" value="<?php echo $keywords;?>" placeholder="请输入名称">
									<button class="btn btn-sm btn-default" type="submit" name="submitsearch" >搜索</button>
								</div>
							</div>
						</form>
						<div class="toolbar-btn-action">
							<form class="pull-left" method="POST" id="recCounts">
								<label>每页</label>
								<select class="btn btn-sm btn-default dropdown-toggle" id="sel" name="recCounts" onchange="submitForm();">			
									<?php
									switch ($recCounts) {
										case '20':
											echo "<option value=\"20\" selected=\"selected\">20</option>";
											echo "<option value=\"50\">50</option>";
											echo "<option value=\"100\">100</option>";
											break;
										case '50':
											echo "<option value=\"20\">20</option>";
											echo "<option value=\"50\" selected=\"selected\">50</option>";
											echo "<option value=\"100\">100</option>";
											break;
										case '100':
											echo "<option value=\"20\">20</option>";
											echo "<option value=\"50\">50</option>";
											echo "<option value=\"100\" selected=\"selected\">100</option>";
											break;
										
										default:
											echo "<option value=\"20\" selected=\"selected\">20</option>";
											echo "<option value=\"50\">50</option>";
											echo "<option value=\"100\">100</option>";
											break;
									}
									?>			
								</select><label>&nbsp;条</label>
							</form>
							<form class="pull-left" method="post">
								<input type="text" name="jumpto" style="border-width: 0px;text-align: right;" size=2 value="<?php echo $page?>">/<?php echo $pageCount?>
								<button class="btn btn-xs btn-default" type="submit">跳转</button>
							</form>
						</div>
					</div>
					<div class="tab-content">
						<div class="tab-pane active">
							<div class="form-group">
								<div class="table-responsive" >
									<table class="table table-hover table-vcenter">
										<thead><tr>
											<th class="w-1">
												<label class="lyear-checkbox checkbox-primary">
													<input type="checkbox" onclick="quanxuan(this)">
													<span></span>
												</label>
											</th>
											<th class="w-5"><a href="?order=sn">账号<?php if($order=='sn') echo'↓'?></a></th>
											<th class="w-10">套餐</th>
											<th class="w-15"><a href="?order=regtime">激活时间<?php if($order=='regtime') echo'↓'?></a></th>
											<th class="w-15"><a href="?order=exp">授权天数<?php if($order=='exp') echo'↓'?></a></th>
											<th class="w-10"><a href="?order=author">管理员<?php if($order=='author') echo'↓'?></a></th>
											<th class="w-10"><a href="?order=enable">已激活<?php if($order=='enable') echo'↓'?></a></th>
											<th class="w-15"><a href="?order=createtime">生成时间<?php if($order=='createtime') echo'↓'?></a></th>
											<th class="w-15"><a href="?order=marks">备注<?php if($order=='marks') echo'↓'?></a></th>
										</tr></thead>
										<tbody>
											<form method="POST">
												<?php
													$recStart=$recCounts*($page-1);
													if($user=='admin'){
															$sql="select sn,regtime,exp,author,createtime,enable,marks,vip from itv_serialnum where 1=1 $searchparam order by $order desc limit $recStart,$recCounts";
														}else{
															$sql="select sn,regtime,exp,author,createtime,enable,marks,vip from itv_serialnum where author='$user' $searchparam order by $order desc limit $recStart,$recCounts";
														}
														//$result=mysqli_query($con,$sql);
														$result = $db->mQuery($sql);
														if (mysqli_num_rows($result)) {
															while($row=mysqli_fetch_array($result)){
																$days=$row['exp'];
																$sn=$row['sn'];
																$regtime=$row['regtime']==0?'':date("Y-m-d H:i:s",$row['regtime']);
																$author=$row['author'];
																$createtime=date("Y-m-d H:i:s",$row['createtime']);
																$marks=$row['marks'];
																$isactived=$row['enable']==0?'否':'是';
																$vip=$row['vip'];

																if($days==999)$days="永不到期";
																echo "<tr class=\"h-5\">
																<td><label class=\"lyear-checkbox checkbox-primary\">
																<input type=\"checkbox\" value=\"$sn\" name=\"sn[]\" ><span></span>
																</label></td>
																<td>".$sn."</td>
																<td>".$arr_meal[$vip]."</td>
																<td>".$regtime."</td>
																<td>".$days. "</td>
																<td>".$author. "</td>
																<td>" .$isactived."</td>
																<td>$createtime</td>
																<td>$marks</td>
																</tr>";
														}
														//unset($row,$arr_meal,$meal_select);
														unset($row);
													}else {
														echo "<tr><td colspan='8' align='center'><font color='red'>对不起，当前没有账号数据！</font></td></tr>";
													}
													mysqli_free_result($result);
													//mysqli_close($con);
												?>
												<div class="form-inline pull-left" >
													<tr>
														<td colspan="9">
															<div class="input-group">
																<div class="input-group-btn">
																	<label class="lyear-checkbox checkbox-inline checkbox-primary">
																		<input type="checkbox" onclick="quanxuan(this)"><span>全选</span>
																	</label>
																	<button class="btn btn-sm btn-primary m-r-10" onclick="copytoclip()">复制账号</button>
																	<button class="btn btn-sm btn-primary m-r-10" type="submit" name="submitdel" onclick="return confirm('确定删除选中用户吗？')">删除选中</button>
																	<input class="btn btn-default " style="width: 85px;height: 30px;" type="text" name="exp" size="3" value="365" placeholder="授权天数">
																	<button class="btn btn-sm btn-primary m-r-10" type="submit" name="submitadddays">修改天数</button>
																	<input class="btn btn-default " style="width: 115px;height: 30px;" type="text" name="marks" size="3" placeholder="请输入备注">
																	<button class="btn btn-sm btn-primary m-r-10" type="submit" name="submitmodifymarks">修改备注</button>
																	<button class="btn btn-sm btn-primary" type="submit" name="submitclear" onclick="return confirm('确定清空已激活用户吗？')">清空已激活</button>	
																</div>
															</div>
														</td>
													</tr>
												</div>
											</form>
										</tbody>
									</table>
									<div class="modal fade" id="gensn1" tabindex="-1" role="dialog">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
													<h4 class="modal-title">生成账号</h4>
												</div>
												<form method="post">
													<div class="modal-body">
														<div class="form-group">
															<label class="control-label">输入账号：</label>
															<input type="text" class="form-control" name="snNumber" placeholder="请输入账号">
														</div>
														<div class="form-group">
															<label class="control-label">授权天数：</label>
															<input type="text" class="form-control" name="exp" value="365" placeholder="请输入授权天数">
														</div>
														<div class="form-group">
															<label class="control-label">选择套餐：</label>
															<?php echo $meal_select;?>
														</div>
														<div class="form-group">
															<label class="control-label">备注：</label>
															<input type="text" class="form-control" name="marks" placeholder="请输入备注">
														</div>
													</div>
													<div class="modal-footer">
														<button class="btn btn-primary" type="submit" name="submitsn1" >生成</button>
														<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
													</div>
												</form>
											</div>
										</div>
									</div>
									
									<div class="modal fade" id="gensn2" tabindex="-1" role="dialog">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
													<h4 class="modal-title">批量生成</h4>
												</div>
												<form method="post">
													<div class="modal-body">
														<div class="form-group">
															<label class="control-label">生成数量：</label>
															<input type="text" class="form-control" name="snCount" placeholder="请输入数量">
														</div>
														<div class="form-group">
															<label class="control-label">授权天数：</label>
															<input type="text" class="form-control" name="exp" value="365" placeholder="请输入授权天数">
														</div>
														<div class="form-group">
															<label class="control-label">选择套餐：</label>
															<?php echo $meal_select;?>
														</div>
														<div class="form-group">
															<label class="control-label">备注：</label>
															<input type="text" class="form-control" name="marks" placeholder="请输入备注">
														</div>
													</div>
													<div class="modal-footer">
														<button class="btn btn-primary" type="submit" name="submitsn2" >生成</button>
														<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
							<nav>
								<ul class="pager">
									<li><a href="<?php if($page>1){$p=$page-1;}else{$p=1;} echo '?keywords='.$keywords.'&page='.$p?>">上一页</a></li>
									<li class="previous"><a href="<?php echo '?keywords='.$keywords.'&page=1'?>"><span aria-hidden="true">&larr;</span> 首页</a></li>
									<li class="next"><a href="<?php echo '?keywords='.$keywords.'&page='.$pageCount?>">尾页 <span aria-hidden="true">&rarr;</span></a></li>
									<li><a href="<?php if($page<$pageCount){$p=$page+1;} else {$p=$page;} echo '?keywords='.$keywords.'&page='.$p?>">下一页</a></li>
								</ul>
							</nav>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<!--End 页面主要内容-->
</div>
</div>