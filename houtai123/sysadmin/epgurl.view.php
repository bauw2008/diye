<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header"><h4>EPG和UA</h4></div>
			<div class="tab-content">
				<div class="tab-pane active">
					<form method="post">
						<div class="form-group">
							<label>EPG连接URL</label>
							<input class="form-control" type="text" name="epgurl" value="<?php echo $epgurl;?>" >
						</div>
						<div class="form-group">
							<label>UA</label>
							<input class="form-control" type="text" name="tmua" value="<?php echo $tmua;?>" >
						</div>
						<div class="form-group">
							<button class="btn btn-label btn-primary" type="submit" name="submit"><label><i class="mdi mdi-checkbox-marked-circle-outline"></i></label>确认提交</button>
						</div>
					</form>
					
				</div>
			</div>
		</div>
	</div>
</div>