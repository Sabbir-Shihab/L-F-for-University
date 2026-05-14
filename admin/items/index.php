<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<style>
	#list td:nth-child(5),
	#list td:nth-child(6){
		text-align:center !important;
	}
</style>
<div class="card card-outline rounded-0 card-navy">
	<div class="card-header ">
		<h3 class="card-title mb-2">Items Approval</h3>
		<div class="card-tools d-flex justify-content-end">
			<a href="<?= base_url ?>admin?page=items/manage_item" id="create_new" class="btn btn-flat btn-primary bg-gradient-teal border-0 rounded00"><span class="fas fa-plus"></span>  Create New</a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<?php
				$status_filter = $_GET['status'] ?? 'all';
				$status_where = "";
				if($status_filter === 'pending')
					$status_where = " where `status` = 0 ";
				elseif($status_filter === 'published')
					$status_where = " where `status` = 1 ";
				elseif($status_filter === 'claimed')
					$status_where = " where `status` = 2 ";
				elseif($status_filter === 'rejected')
					$status_where = " where `status` = 3 ";
				$status_counts = [
					'all' => $conn->query("SELECT id FROM `item_list`")->num_rows,
					'pending' => $conn->query("SELECT id FROM `item_list` where `status` = 0")->num_rows,
					'published' => $conn->query("SELECT id FROM `item_list` where `status` = 1")->num_rows,
					'claimed' => $conn->query("SELECT id FROM `item_list` where `status` = 2")->num_rows,
					'rejected' => $conn->query("SELECT id FROM `item_list` where `status` = 3")->num_rows,
				];
			?>
			<div class="approval-tabs mb-3">
				<a href="<?= base_url ?>admin?page=items" class="btn btn-sm <?= $status_filter == 'all' ? 'btn-primary' : 'btn-outline-primary' ?>">All <span class="badge bg-light text-dark ms-1"><?= $status_counts['all'] ?></span></a>
				<a href="<?= base_url ?>admin?page=items&status=pending" class="btn btn-sm <?= $status_filter == 'pending' ? 'btn-warning' : 'btn-outline-warning' ?>">Pending Approval <span class="badge bg-light text-dark ms-1"><?= $status_counts['pending'] ?></span></a>
				<a href="<?= base_url ?>admin?page=items&status=published" class="btn btn-sm <?= $status_filter == 'published' ? 'btn-success' : 'btn-outline-success' ?>">Published <span class="badge bg-light text-dark ms-1"><?= $status_counts['published'] ?></span></a>
				<a href="<?= base_url ?>admin?page=items&status=claimed" class="btn btn-sm <?= $status_filter == 'claimed' ? 'btn-secondary' : 'btn-outline-secondary' ?>">Claimed <span class="badge bg-light text-dark ms-1"><?= $status_counts['claimed'] ?></span></a>
				<a href="<?= base_url ?>admin?page=items&status=rejected" class="btn btn-sm <?= $status_filter == 'rejected' ? 'btn-danger' : 'btn-outline-danger' ?>">Rejected <span class="badge bg-light text-dark ms-1"><?= $status_counts['rejected'] ?></span></a>
			</div>
			<?php if($status_counts['pending'] > 0): ?>
			<div class="alert alert-warning d-flex align-items-center justify-content-between gap-3 flex-wrap">
				<span><strong><?= $status_counts['pending'] ?></strong> item<?= $status_counts['pending'] == 1 ? '' : 's' ?> waiting for approval.</span>
				<a href="<?= base_url ?>admin?page=items&status=pending" class="btn btn-sm btn-warning">Review Pending Items</a>
			</div>
			<?php endif; ?>
			<div class="table-responsive">
				<table class="table table-sm table-hover table-striped table-bordered" id="list">
					<colgroup>
						<col width="5%">
						<col width="20%">
						<col width="15%">
						<col width="35%">
						<col width="15">
						<col width="10%">
					</colgroup>
					<thead>
						<tr>
							<th>#</th>
							<th>Upload Date</th>
							<th>Image</th>
							<th>Title</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$i = 1;
						$qry = $conn->query("SELECT *, COALESCE((SELECT name FROM `category_list` where `category_list`.`id` = `item_list`.`category_id`  ), 'N/A') as category from `item_list` {$status_where} order by abs(unix_timestamp(`created_at`)) desc ");
						while($row = $qry->fetch_assoc()):
						?>
							<tr>
								<td class="align-items-center text-center"><?php echo $i++; ?></td>
								<td class="align-items-center"><?php echo date("Y-m-d g:i A",strtotime($row['created_at'])) ?></td>
								<td class="align-items-center"><?= $row['fullname'] ?></td>
								<td class="align-items-center">
									<?php if(isset($row['item_type'])): ?>
										<?php if($row['item_type'] == 'found'): ?>
											<span class="badge bg-primary px-2 me-2">Found</span>
										<?php else: ?>
											<span class="badge bg-warning text-dark px-2 me-2">Missing</span>
										<?php endif; ?>
									<?php endif; ?>
									<p class="text-center truncate-1" style="max-width:250px"><?= $row['title'] ?></p>
								</td>
								<td class="align-items-center justify-content-center text-center">
									<?php if($row['status'] == 1): ?>
										<span class="badge bg-primary px-3 rounded-pill">Published</span>
									<?php elseif($row['status'] == 2): ?>
										<span class="badge bg-success px-3 rounded-pill">Claimed</span>
									<?php elseif($row['status'] == 3): ?>
										<span class="badge bg-danger px-3 rounded-pill">Rejected</span>
									<?php else: ?>
										<span class="badge bg-secondary px-3 rounded-pill">Pending</span>
									<?php endif; ?>
								</td>
								<td class="align-items-center" align="center">
									<div class="dropdown admin-action-dropdown">
										<button type="button" class="btn btn-flat p-1 btn-default btn-sm border dropdown-toggle dropdown-icon admin-action-toggle" aria-expanded="false">
												Action
										</button>
										<div class="dropdown-menu admin-action-menu" role="menu">
											<a class="dropdown-item" href="./?page=items/view_item&id=<?php echo $row['id'] ?>"><span class="bi bi-card-text text-dark"></span> View</a>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item" href="./?page=items/manage_item&id=<?php echo $row['id'] ?>"><span class="bi bi-pencil-square text-primary"></span> Edit</a>
											<div class="dropdown-divider"></div>
											<?php if($row['status'] == 0): ?>
												<a class="dropdown-item" href="./?page=items/view_item&id=<?php echo $row['id'] ?>"><span class="bi bi-eye text-primary"></span> Review for Approval</a>
												<div class="dropdown-divider"></div>
											<?php endif; ?>
											<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="bi bi-trash text-danger"></span> Delete</a>
										</div>
									</div>
									<?php if($row['status'] == 0): ?>
									<a class="btn btn-sm btn-primary mt-1" href="./?page=items/view_item&id=<?php echo $row['id'] ?>"><i class="bi bi-eye"></i> Review</a>
									<?php endif; ?>
								</td>
							</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
	$(document).on('click','.admin-action-toggle',function(e){
		e.preventDefault();
		e.stopPropagation();
		const menu = $(this).closest('.admin-action-dropdown').find('.admin-action-menu').first();
		$('.admin-action-menu.show').not(menu).removeClass('show');
		menu.toggleClass('show');
		$(this).attr('aria-expanded', menu.hasClass('show') ? 'true' : 'false');
	})
	$(document).on('click','.admin-action-menu',function(e){
		e.stopPropagation();
	})
	$(document).on('click',function(){
		$('.admin-action-menu.show').removeClass('show');
		$('.admin-action-toggle[aria-expanded="true"]').attr('aria-expanded','false');
	})
	$(document).on('click','.delete_data',function(){
		_conf("Are you sure to delete this item permanently?","delete_item",[$(this).attr('data-id')])
	})
	$(document).on('click','.approve_data',function(){
		_conf("Approve this item and make it visible to users?","approve_item",[$(this).attr('data-id')])
	})
		const dT = new simpleDatatables.DataTable('#list')
	})
	function delete_item($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_item",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occurred while deleting item.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					alert_toast("Item deleted successfully.",'success');
					setTimeout(()=>{location.reload();},1000);
				}else{
					var msg = (resp.msg) ? resp.msg : "Failed to delete item";
					alert_toast(msg,'error');
					end_loader();
				}
			}
		})
	}
	function approve_item($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=approve_item",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occurred while approving item.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					alert_toast("Item approved successfully.",'success');
					setTimeout(()=>{location.reload();},1000);
				}else{
					var msg = (resp.msg) ? resp.msg : "Failed to approve item";
					alert_toast(msg,'error');
					end_loader();
				}
			}
		})
	}
</script>
