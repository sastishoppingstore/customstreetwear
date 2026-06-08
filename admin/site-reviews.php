<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Site Reviews';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add' || $action === 'edit') {
        $id = intval($_POST['id'] ?? 0);
        $data = [
            $_POST['platform'] ?? 'google',
            $_POST['reviewer_name'],
            floatval($_POST['rating'] ?? 5),
            $_POST['review_text'] ?? '',
            $_POST['review_url'] ?? '',
            $_POST['review_date'] ?? date('Y-m-d'),
            intval($_POST['sort_order'] ?? 0)
        ];
        if ($id) {
            dbExecute("UPDATE site_reviews SET platform=?, reviewer_name=?, rating=?, review_text=?, review_url=?, review_date=?, sort_order=? WHERE id=?", array_merge($data, [$id]));
        } else {
            dbExecute("INSERT INTO site_reviews (platform, reviewer_name, rating, review_text, review_url, review_date, sort_order) VALUES (?,?,?,?,?,?,?)", $data);
        }
        setFlash('success', 'Review saved.');
    } elseif ($action === 'delete') {
        dbExecute("DELETE FROM site_reviews WHERE id=?", [intval($_POST['id'])]);
        setFlash('success', 'Review deleted.');
    }
    redirect('site-reviews.php');
}

$reviews = dbFetchAll("SELECT * FROM site_reviews ORDER BY sort_order");
include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header">
        <h1 class="content-title">Site Reviews (Social Proof)</h1>
        <button class="btn btn-primary" onclick="document.getElementById('addForm').classList.toggle('hidden')">Add Review</button>
    </div>
    <?php echo showFlash(); ?>
    
    <div id="addForm" class="admin-card hidden" style="margin-bottom:20px;">
        <form method="POST" action="site-reviews.php">
            <?php echo csrfField(); ?>
            <input type="hidden" name="action" value="add">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:12px;">
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Reviewer Name</label>
                    <input type="text" name="reviewer_name" class="form-input" required>
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Platform</label>
                    <select name="platform" class="form-select">
                        <option value="google">Google</option>
                        <option value="facebook">Facebook</option>
                        <option value="trustpilot">Trustpilot</option>
                        <option value="yelp">Yelp</option>
                    </select>
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Rating (1-5)</label>
                    <select name="rating" class="form-select">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                        <option value="<?php echo $i; ?>" <?php echo $i === 5 ? 'selected' : ''; ?>><?php echo $i; ?> stars</option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="align-self:end;">Save</button>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:8px;">
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Review Text</label>
                    <textarea name="review_text" class="form-textarea" rows="2"></textarea>
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Review URL</label>
                    <input type="url" name="review_url" class="form-input" placeholder="https://">
                </div>
            </div>
        </form>
    </div>
    
    <div class="admin-card">
        <table class="admin-table">
            <thead><tr><th>Name</th><th>Platform</th><th>Rating</th><th>Text</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($reviews as $r): ?>
                <tr>
                    <td><strong><?php echo e($r['reviewer_name']); ?></strong></td>
                    <td><span class="badge badge-info"><?php echo e($r['platform']); ?></span></td>
                    <td><span style="color:#ffaa00;"><?php echo str_repeat('★', intval($r['rating'])); ?></span> <?php echo e($r['rating']); ?></td>
                    <td style="max-width:250px;overflow:hidden;text-overflow:ellipsis;font-size:13px;color:var(--muted);"><?php echo e(truncate($r['review_text'] ?? '', 100)); ?></td>
                    <td class="actions">
                        <form method="POST" action="site-reviews.php" style="display:inline;">
                            <?php echo csrfField(); ?>
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline" onclick="return confirm('Delete?')" style="color:var(--danger);">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($reviews)): ?>
                <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--muted);">No reviews yet. Add reviews to build social proof.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<style>
.form-label { display:block; font-size:13px; font-weight:600; margin-bottom:4px; }
.form-input, .form-select, .form-textarea { width:100%; padding:8px 12px; background:var(--bg); border:1px solid var(--border); border-radius:var(--radius-sm); color:var(--text); font-size:14px; }
.hidden { display:none !important; }
</style>
<?php include __DIR__ . '/includes/footer.php'; ?>
