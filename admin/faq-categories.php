<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'FAQ Categories';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add' || $action === 'edit') {
        $id = intval($_POST['id'] ?? 0);
        $name = trim($_POST['name']);
        $slug = $id ? $_POST['slug'] : createSlug($name);
        $desc = $_POST['description'] ?? '';
        $sort = intval($_POST['sort_order'] ?? 0);
        
        if ($id) {
            dbExecute("UPDATE faq_categories SET name=?, description=?, sort_order=? WHERE id=?", [$name, $desc, $sort, $id]);
        } else {
            $slug = generateUniqueSlug('faq_categories', $name);
            dbExecute("INSERT INTO faq_categories (name, slug, description, sort_order) VALUES (?,?,?,?)", [$name, $slug, $desc, $sort]);
        }
        setFlash('success', 'FAQ category saved.');
    } elseif ($action === 'delete') {
        dbExecute("DELETE FROM faq_categories WHERE id=?", [intval($_POST['id'])]);
        setFlash('success', 'FAQ category deleted.');
    }
    redirect('faq-categories.php');
}

$categories = dbFetchAll("SELECT fc.*, (SELECT COUNT(*) FROM faqs WHERE faq_category_id=fc.id) as faq_count FROM faq_categories ORDER BY sort_order");
include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header">
        <h1 class="content-title">FAQ Categories</h1>
        <button class="btn btn-primary" onclick="document.getElementById('addForm').classList.toggle('hidden')">Add Category</button>
    </div>
    <?php echo showFlash(); ?>
    
    <div id="addForm" class="admin-card hidden" style="margin-bottom:20px;">
        <form method="POST" action="faq-categories.php">
            <?php echo csrfField(); ?>
            <input type="hidden" name="action" value="add">
            <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:12px;">
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-input" value="0">
                </div>
                <button type="submit" class="btn btn-primary" style="align-self:end;">Save</button>
            </div>
            <div class="form-group" style="margin:8px 0 0;">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-textarea" rows="2"></textarea>
            </div>
        </form>
    </div>
    
    <div class="admin-card">
        <table class="admin-table">
            <thead><tr><th>Name</th><th>Slug</th><th>FAQs</th><th>Sort</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><strong><?php echo e($cat['name']); ?></strong></td>
                    <td><code><?php echo e($cat['slug']); ?></code></td>
                    <td><span class="badge badge-info"><?php echo intval($cat['faq_count']); ?></span></td>
                    <td><?php echo intval($cat['sort_order']); ?></td>
                    <td class="actions">
                        <form method="POST" action="faq-categories.php" style="display:inline;">
                            <?php echo csrfField(); ?>
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline" onclick="return confirm('Delete this category?')" style="color:var(--danger);">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<style>
.form-label { display:block; font-size:13px; font-weight:600; margin-bottom:4px; }
.form-input, .form-textarea { width:100%; padding:8px 12px; background:var(--bg); border:1px solid var(--border); border-radius:var(--radius-sm); color:var(--text); font-size:14px; }
.hidden { display:none !important; }
</style>
<?php include __DIR__ . '/includes/footer.php'; ?>
