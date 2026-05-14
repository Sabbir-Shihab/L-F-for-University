<style>
    .user-avatar{
        width:3rem;
        height:3rem;
        object-fit:scale-down;
        object-position:center center;
    }
	#list td{
		vertical-align: middle;
	}
</style>
<div class="card card-outline rounded-0 card-navy">
	<div class="card-header">
		<div class="card-tools d-flex justify-content-end">
			<a href="<?= base_url ?>admin/?page=user/manage_user" id="create_new" class="btn btn-flat btn-primary bg-gradient-teal border-0 rounded-0"><span class="fas fa-plus"></span>  Create New</a>
		</div>
	</div>
	<div class="card-body pt-4">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="15%">
					<col width="25%">
					<col width="15%">
					<col width="10%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Date Updated</th>
						<th>Avatar</th>
						<th>Name</th>
						<th>Username</th>
						<th>Type</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT *, concat(firstname,' ', coalesce(concat(middlename,' '), '') , lastname) as `name` from `users` order by concat(firstname,' ', lastname) asc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo date("Y-m-d H:i",strtotime($row['date_updated'])) ?></td>
							<td class="text-center">
                                <img src="<?= validate_image($row['avatar']) ?>" alt="" class="img-thumbnail rounded-circle user-avatar">
                            </td>
							<td><?php echo $row['name'] ?></td>
							<td><?php echo $row['username'] ?></td>
							<td class="text-center">
                                <?php if($row['type'] == 1): ?>
                                    Administrator
                                <?php elseif($row['type'] == 2): ?>
                                    Staff
                                <?php else: ?>
									N/A
                                <?php endif; ?>
							</td>
							<td align="center">
								<div class="user-action-buttons">
									<a class="btn btn-sm btn-primary rounded-0 edit_data" href="./?page=user/manage_user&id=<?= $row['id'] ?>">
										<span class="bi bi-pencil-square"></span> Edit
									</a>
									<?php if((int)$row['id'] === (int)$_settings->userdata('id')): ?>
									<button type="button" class="btn btn-sm btn-secondary rounded-0" disabled title="You cannot delete the logged-in admin account">
										<span class="bi bi-trash"></span> Delete
									</button>
									<?php else: ?>
									<button type="button" class="btn btn-sm btn-danger rounded-0 delete_data" data-id="<?php echo $row['id'] ?>">
										<span class="bi bi-trash"></span> Delete
									</button>
									<?php endif; ?>
								</div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$(document).on('click','.delete_data',function(){
			const id = $(this).attr('data-id');
			if(typeof _conf === 'function'){
				_conf("Are you sure to delete this user permanently?","delete_user",[id])
			}else if(confirm("Are you sure to delete this user permanently?")){
				delete_user(id);
			}
		})
		if($.fn.dataTable){
			$('.table').dataTable({
				columnDefs: [
						{ orderable: false, targets: [6] }
				],
				order:[0,'asc']
			});
		}
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})
	function delete_user($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Users.php?f=delete",
			method:"POST",
			data:{id: $id},
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				resp = $.trim(resp);
				if(resp == 1){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>
