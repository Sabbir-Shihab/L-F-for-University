<?php 
if(isset($_GET['cid'])){
    $category_qry = $conn->query("SELECT * FROM `category_list` where `id` = '{$_GET['cid']}'");
    if($category_qry->num_rows > 0){
        foreach($category_qry->fetch_assoc() as $k => $v){
            $cat[$k] = $v; 
        }
    }
}
?>
<h1 class="pageTitle text-center titleTxt">Lost and Found Items</h1>
<hr class="mx-auto bg-primary border-primary opacity-100" style="width:50px">

<div class="container-sm">
    <div class="row">
        <div class="col-12">
        <?php if(isset($cat['name'])): ?>
            <h3 class="titleTxt"><?= $cat['name'] ?></h3>
        <?php endif; ?>
        <?php if(isset($cat['description'])): ?>
            <div ><?= str_replace("\n", "<br>", htmlspecialchars_decode($cat['description'])) ?></div>
        <?php endif; ?>
            <?php 
            $where = "";
            if(isset($cat['id'])){
                $where = " and `category_id` = '{$cat['id']}'";
            }
            // show only published items on the public listing
            $items = $conn->query("SELECT * FROM `item_list` where `status` = 1 {$where} order by `title` asc")->fetch_all(MYSQLI_ASSOC);
            ?>
            <div class="items-filter-panel">
                <div class="items-filter-heading">
                    <div>
                        <span class="items-filter-kicker">Search Controls</span>
                        <h2>Filter Items</h2>
                    </div>
                    <span class="items-result-count"><strong id="visible-item-count"><?= count($items) ?></strong> item<?= count($items) == 1 ? '' : 's' ?> showing</span>
                </div>
                <div class="items-filter-grid">
                    <div class="items-filter-field">
                        <label for="item-type-filter">Item Type</label>
                        <select id="item-type-filter" class="form-select">
                            <option value="">All Items</option>
                            <option value="found">Found Items</option>
                            <option value="missing">Missing Items</option>
                        </select>
                    </div>
                    <div class="items-filter-field">
                        <label for="item-time-filter">Time Posted</label>
                        <select id="item-time-filter" class="form-select">
                            <option value="">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">Last 7 Days</option>
                            <option value="month">Last 30 Days</option>
                        </select>
                    </div>
                    <div class="items-filter-field">
                        <label for="item-sort-filter">Sort By</label>
                        <select id="item-sort-filter" class="form-select">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="title">Title A-Z</option>
                        </select>
                    </div>
                    <button type="button" id="item-filter-reset" class="btn items-filter-reset">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Reset
                    </button>
                </div>
            </div>
            <div id="item-list">
                <?php if(count($items) > 0): ?>
                <?php foreach($items as $row): ?>
                <div class="item-item text-decoration-none text-reset" data-href="<?= base_url.'?page=items/view&id='.$row['id'] ?>" data-item-type="<?= $row['item_type'] ?? 'found' ?>" data-created="<?= strtotime($row['created_at']) ?>" data-title="<?= htmlspecialchars(strtolower($row['title']), ENT_QUOTES) ?>">
                    <div class="card" style="cursor:pointer">
                        <div class="item-card-img">
                            <img src="<?= validate_image($row['image_path']) ?>" alt="">
                        </div>
                        <div class="card-body pt-3">
                            <h4 class="card-title"><?= $row['title'] ?></h4>
                            <small class="text-muted d-block mb-2">Uploaded on <?= date("F d, Y g:i A", strtotime($row['created_at'])) ?></small>
                            <?php if(isset($row['item_type'])): ?>
                                <?php if($row['item_type'] == 'found'): ?>
                                    <span class="badge bg-primary">Found</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Missing</span>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if($row['status'] == 2): ?>
                                <span class="badge bg-success">Owner Found</span>
                            <?php endif; ?>
                            <p class="truncate-3"><?= strip_tags(htmlspecialchars_decode($row['description'])) ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div id="item-filter-empty" class="items-empty-state text-center" style="display:none">
                <i class="bi bi-search"></i>
                <p>No items match the selected filters.</p>
            </div>
            <?php if(count($items) <= 0): ?>
                <div class="items-empty-state text-center">
                    <i class="bi bi-inbox"></i>
                    <p>No item listed yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Claim Modal -->
<div class="modal fade" id="claimModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Claim Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="claim-form">
                    <input type="hidden" name="item_id" id="claim-item-id" value="">
                    <div class="mb-2">
                        <label class="form-label">Your Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Contact</label>
                        <input type="text" class="form-control" name="contact">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Message (optional)</label>
                        <textarea class="form-control" name="message"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submit-claim">Send Claim</button>
            </div>
        </div>
    </div>
</div>

<script>
$(function(){
        var claimModal = new bootstrap.Modal(document.getElementById('claimModal'));
    function applyItemFilters(){
        var typeValue = $('#item-type-filter').val();
        var timeValue = $('#item-time-filter').val();
        var sortValue = $('#item-sort-filter').val();
        var now = Math.floor(Date.now() / 1000);
        var minCreated = 0;
        var visibleCount = 0;
        var items = $('.item-item').get();

        if(timeValue === 'today'){
            var today = new Date();
            today.setHours(0, 0, 0, 0);
            minCreated = Math.floor(today.getTime() / 1000);
        }else if(timeValue === 'week'){
            minCreated = now - (7 * 24 * 60 * 60);
        }else if(timeValue === 'month'){
            minCreated = now - (30 * 24 * 60 * 60);
        }

        items.sort(function(a, b){
            if(sortValue === 'oldest')
                return Number($(a).data('created')) - Number($(b).data('created'));
            if(sortValue === 'title')
                return String($(a).data('title')).localeCompare(String($(b).data('title')));
            return Number($(b).data('created')) - Number($(a).data('created'));
        });
        $('#item-list').append(items);

        $('.item-item').each(function(){
            var itemType = $(this).data('item-type') || 'found';
            var created = Number($(this).data('created')) || 0;
            var isVisible = (typeValue === '' || itemType === typeValue) && (minCreated === 0 || created >= minCreated);
            $(this).toggle(isVisible);
            if(isVisible) visibleCount++;
        });

        $('#visible-item-count').text(visibleCount);
        $('#item-filter-empty').toggle(visibleCount === 0 && $('.item-item').length > 0);
    }

    $(document).on('change', '#item-type-filter, #item-time-filter, #item-sort-filter', applyItemFilters);
    $(document).on('click', '#item-filter-reset', function(){
        $('#item-type-filter, #item-time-filter').val('');
        $('#item-sort-filter').val('newest');
        applyItemFilters();
    });
    applyItemFilters();
    // navigate to item view when card clicked (except when clicking interactive elements)
    $(document).on('click', '.item-item .card', function(e){
        if($(e.target).closest('.claim-btn, button, a, .btn').length > 0) return;
        var href = $(this).closest('.item-item').data('href');
        if(href) window.location.href = href;
    });
        $(document).on('click','.claim-btn',function(e){
                e.preventDefault();
                var id = $(this).data('id');
                $('#claim-item-id').val(id);
                claimModal.show();
        });
        $(document).on('click','#submit-claim',function(){
                var frm = $('#claim-form');
                if(!frm[0].checkValidity()){
                        frm[0].reportValidity();
                        return;
                }
                var data = frm.serialize();
                start_loader && start_loader();
                $.ajax({
                        url: _base_url_ + 'classes/Master.php?f=request_claim',
                        method: 'POST',
                        data: data,
                        dataType: 'json',
                        error: function(err){
                                end_loader && end_loader();
                                alert_toast && alert_toast('An error occurred.','error');
                        },
                        success: function(resp){
                                end_loader && end_loader();
                                if(resp.status == 'success'){
                                        claimModal.hide();
                                        alert_toast && alert_toast(resp.msg,'success');
                                }else{
                                        alert_toast && alert_toast(resp.msg || 'Failed to submit claim.','error');
                                }
                        }
                })
        })
})
</script>
