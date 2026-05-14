<?php
$pending_items = $conn->query("SELECT i.*, COALESCE(c.name, 'N/A') as category FROM `item_list` i LEFT JOIN `category_list` c ON c.id = i.category_id WHERE i.status = 0 ORDER BY i.created_at DESC")->fetch_all(MYSQLI_ASSOC);
$pending_claims = $conn->query("SELECT cr.*, i.title as item_title, i.image_path FROM `claim_requests` cr LEFT JOIN `item_list` i ON i.id = cr.item_id WHERE cr.status = 0 ORDER BY cr.created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<div class="approval-page">
	<div class="row g-3 mb-3">
		<div class="col-lg-6 col-md-6 col-sm-12">
			<div class="card approval-summary-card border-0 shadow-sm">
				<div class="card-body d-flex align-items-center justify-content-between">
					<div>
						<span class="text-muted small fw-bold text-uppercase">Item Requests</span>
						<h3 class="mb-0"><?= format_num(count($pending_items)) ?></h3>
					</div>
					<i class="bi bi-hourglass-split text-warning"></i>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12">
			<div class="card approval-summary-card border-0 shadow-sm">
				<div class="card-body d-flex align-items-center justify-content-between">
					<div>
						<span class="text-muted small fw-bold text-uppercase">Claim Requests</span>
						<h3 class="mb-0"><?= format_num(count($pending_claims)) ?></h3>
					</div>
					<i class="bi bi-file-earmark-person text-primary"></i>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-outline rounded-0 card-navy mb-4">
		<div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
			<h3 class="card-title mb-0">Pending Item Approval</h3>
			<a href="<?= base_url ?>admin/?page=items&status=pending" class="btn btn-sm btn-outline-primary">Open Items List</a>
		</div>
		<div class="card-body">
			<?php if(count($pending_items) > 0): ?>
			<div class="table-responsive">
				<table class="table table-sm table-hover align-middle approval-table">
					<thead>
						<tr>
							<th>Submitted</th>
							<th>Item</th>
							<th>Type</th>
							<th>Category</th>
							<th class="text-end">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($pending_items as $row): ?>
						<tr>
							<td><?= date("Y-m-d g:i A", strtotime($row['created_at'])) ?></td>
							<td>
								<strong><?= htmlspecialchars($row['title']) ?></strong><br>
								<span class="text-muted small"><?= htmlspecialchars($row['fullname']) ?></span>
							</td>
							<td>
								<span class="badge <?= ($row['item_type'] ?? 'found') == 'missing' ? 'bg-warning text-dark' : 'bg-primary' ?>"><?= ucfirst($row['item_type'] ?? 'found') ?></span>
							</td>
							<td><?= htmlspecialchars($row['category']) ?></td>
							<td class="text-end">
								<a href="<?= base_url ?>admin/?page=items/view_item&id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">
									<i class="bi bi-eye"></i> Review Details
								</a>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php else: ?>
			<div class="approval-empty text-center text-muted py-4">
				<i class="bi bi-check2-circle"></i>
				<p class="mb-0">No pending item requests.</p>
			</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="card card-outline rounded-0 card-navy">
		<div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
			<h3 class="card-title mb-0">Pending Claim Approval</h3>
			<a href="<?= base_url ?>admin/?page=claims" class="btn btn-sm btn-outline-primary">Open Claims List</a>
		</div>
		<div class="card-body">
			<?php if(count($pending_claims) > 0): ?>
			<div class="table-responsive">
				<table class="table table-sm table-hover align-middle approval-table">
					<thead>
						<tr>
							<th>Requested</th>
							<th>Item</th>
							<th>Claimant</th>
							<th>Message</th>
							<th class="text-end">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($pending_claims as $row): ?>
						<tr>
							<td><?= date("Y-m-d g:i A", strtotime($row['created_at'])) ?></td>
							<td><?= htmlspecialchars($row['item_title'] ?? 'N/A') ?></td>
							<td>
								<strong><?= htmlspecialchars($row['name']) ?></strong><br>
								<span class="text-muted small"><?= htmlspecialchars($row['contact'] ?? '') ?></span>
							</td>
							<td><?= htmlspecialchars($row['message'] ?? '') ?></td>
							<td class="text-end">
								<a href="<?= base_url ?>admin/?page=items/view_item&id=<?= $row['item_id'] ?>" class="btn btn-sm btn-primary">
									<i class="bi bi-eye"></i> Review Details
								</a>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php else: ?>
			<div class="approval-empty text-center text-muted py-4">
				<i class="bi bi-check2-circle"></i>
				<p class="mb-0">No pending claim requests.</p>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<script>
$(function(){
})
</script>
