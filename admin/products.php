<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$action = $_GET['action'] ?? 'list';
$id = intval($_GET['id'] ?? 0);
$pageTitle = 'Products';

// Handle delete
if ($action === 'delete' && $id) {
    requireCsrf();
    dbExecute("DELETE FROM products WHERE id = ?", [$id]);
    setFlash('success', 'Product deleted successfully.');
    redirect('products.php');
}

// Handle save
if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    $data = [
        'category_id' => intval($_POST['category_id']),
        'subcategory_id' => !empty($_POST['subcategory_id']) ? intval($_POST['subcategory_id']) : null,
        'title' => trim($_POST['title']),
        'slug' => generateUniqueSlug('products', $_POST['title'], $id),
        'sku' => trim($_POST['sku']),
        'short_description' => trim($_POST['short_description']),
        'full_description' => trim($_POST['full_description']),
        'features' => trim($_POST['features']),
        'specifications' => trim($_POST['specifications']),
        'sizes' => trim($_POST['sizes']),
        'colors' => trim($_POST['colors']),
        'customization_options' => trim($_POST['customization_options']),
        'status' => intval($_POST['status'] ?? 1),
        'is_featured' => intval($_POST['is_featured'] ?? 0),
        'is_best_seller' => intval($_POST['is_best_seller'] ?? 0),
        'seo_title' => trim($_POST['seo_title']),
        'seo_description' => trim($_POST['seo_description']),
    ];
    
    // Handle image upload
    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
        $result = uploadFile($_FILES['main_image'], 'products');
        if ($result['success']) $data['main_image'] = $result['path'];
    }
    
    if ($id) {
        $fields = [];
        $values = [];
        foreach ($data as $k => $v) { $fields[] = "$k = ?"; $values[] = $v; }
        $values[] = $id;
        dbExecute("UPDATE products SET " . implode(', ', $fields) . " WHERE id = ?", $values);
        setFlash('success', 'Product updated.');
    } else {
        $cols = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        dbInsert("INSERT INTO products ($cols) VALUES ($placeholders)", array_values($data));
        setFlash('success', 'Product created.');
    }
    redirect('products.php');
}

// Get data
$categories = getCategories();
$subcategories = getSubcategories();
$editItem = null;
if ($action === 'edit' && $id) {
    $editItem = dbFetchOne("SELECT * FROM products WHERE id = ?", [$id]);
}

// List
$page_num = max(1, intval($_GET['page'] ?? 1));
$per_page = 20;
$offset = ($page_num - 1) * $per_page;
$filter_cat = intval($_GET['category'] ?? 0);
$search = trim($_GET['search'] ?? '');

$where = "1=1";
$params = [];
if ($filter_cat) { $where .= " AND p.category_id = ?"; $params[] = $filter_cat; }
if ($search) { $where .= " AND (p.title LIKE ? OR p.sku LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }

$total = dbFetchOne("SELECT COUNT(*) as c FROM products p WHERE $where", $params)['c'];
$items = dbFetchAll("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE $where ORDER BY p.id DESC LIMIT ? OFFSET ?", array_merge($params, [$per_page, $offset]));

include __DIR__ . '/includes/header.php';
?>

<div class="admin-content">
    <div class="content-header">
        <h1 class="content-title">Products</h1>
        <div class="content-actions">
            <a href="products.php" class="btn btn-outline btn-sm">List</a>
            <a href="products.php?action=add" class="btn btn-primary btn-sm">+ Add Product</a>
        </div>
    </div>
    
    <?php echo showFlash(); ?>
    
    <?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="admin-card">
        <div class="admin-card-header"><h3 class="admin-card-title"><?php echo $id ? 'Edit' : 'Add'; ?> Product</h3></div>
        <form method="POST" action="products.php?action=save<?php echo $id ? '&id=' . $id : ''; ?>" enctype="multipart/form-data">
            <?php echo csrfField(); ?>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-input" required value="<?php echo e($editItem['title'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">SKU</label>
                    <input type="text" name="sku" class="form-input" value="<?php echo e($editItem['sku'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $c): ?>
                        <option value="<?php echo $c['id']; ?>" <?php echo ($editItem['category_id'] ?? '') == $c['id'] ? 'selected' : ''; ?>><?php echo e($c['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Subcategory</label>
                    <select name="subcategory_id" class="form-select">
                        <option value="">None</option>
                        <?php foreach ($subcategories as $s): ?>
                        <option value="<?php echo $s['id']; ?>" <?php echo ($editItem['subcategory_id'] ?? '') == $s['id'] ? 'selected' : ''; ?>><?php echo e($s['name']); ?> (<?php echo e($s['category_name']); ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Short Description</label>
                <textarea name="short_description" class="form-textarea" rows="3"><?php echo e($editItem['short_description'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Full Description</label>
                <textarea name="full_description" class="form-textarea" rows="6"><?php echo e($editItem['full_description'] ?? ''); ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Features (comma separated)</label>
                    <input type="text" name="features" class="form-input" value="<?php echo e($editItem['features'] ?? ''); ?>" placeholder="Feature 1, Feature 2, Feature 3">
                </div>
                <div class="form-group">
                    <label class="form-label">Sizes (comma separated)</label>
                    <input type="text" name="sizes" class="form-input" value="<?php echo e($editItem['sizes'] ?? ''); ?>" placeholder="S, M, L, XL, 2XL">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Colors (comma separated)</label>
                    <input type="text" name="colors" class="form-input" value="<?php echo e($editItem['colors'] ?? ''); ?>" placeholder="Black, White, Navy">
                </div>
                <div class="form-group">
                    <label class="form-label">Specifications</label>
                    <textarea name="specifications" class="form-textarea" rows="3" placeholder="Material: 100% Cotton&#10;Weight: 300 GSM"><?php echo e($editItem['specifications'] ?? ''); ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Main Image</label>
                <input type="file" name="main_image" class="form-input" accept="image/*">
                <?php if ($editItem): ?>
                <div style="margin-top: 10px;"><img src="<?php echo e($editItem['main_image'] ?: '/uploads/products/' . $editItem['slug'] . '.jpg'); ?>" onerror="this.src='/uploads/products/default.jpg';" style="width: 100px; height: 100px; object-fit: cover; border-radius: 6px;"></div>
                <?php endif; ?>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">SEO Title</label>
                    <input type="text" name="seo_title" class="form-input" value="<?php echo e($editItem['seo_title'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">SEO Description</label>
                    <input type="text" name="seo_description" class="form-input" value="<?php echo e($editItem['seo_description'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1" <?php echo ($editItem['status'] ?? 1) == 1 ? 'selected' : ''; ?>>Active</option>
                        <option value="0" <?php echo ($editItem['status'] ?? 1) == 0 ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                <div class="form-group" style="display: flex; gap: 20px; align-items: center; padding-top: 28px;">
                    <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; cursor: pointer;">
                        <input type="checkbox" name="is_featured" value="1" <?php echo ($editItem['is_featured'] ?? 0) ? 'checked' : ''; ?>> Featured
                    </label>
                    <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; cursor: pointer;">
                        <input type="checkbox" name="is_best_seller" value="1" <?php echo ($editItem['is_best_seller'] ?? 0) ? 'checked' : ''; ?>> Best Seller
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $id ? 'Update' : 'Create'; ?> Product</button>
        </form>
    </div>
    <?php else: ?>
    
    <!-- Filters -->
    <div class="filter-bar">
        <form method="GET" action="products.php" class="filter-bar" style="flex: 1; margin: 0;">
            <div class="form-group">
                <select name="category" class="form-select" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $c): ?>
                    <option value="<?php echo $c['id']; ?>" <?php echo $filter_cat == $c['id'] ? 'selected' : ''; ?>><?php echo e($c['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="search" class="form-input" placeholder="Search products..." value="<?php echo e($search); ?>">
            </div>
            <button type="submit" class="btn btn-outline btn-sm">Filter</button>
            <?php if ($filter_cat || $search): ?>
            <a href="products.php" class="btn btn-outline btn-sm">Clear</a>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr><th>Image</th><th>Title</th><th>Category</th><th>SKU</th><th>Status</th><th>Featured</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><img src="<?php echo e($item['main_image'] ?: '/uploads/products/' . $item['slug'] . '.jpg'); ?>" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:6px;" onerror="this.src='/uploads/products/default.jpg';"></td>
                    <td><?php echo e(truncate($item['title'], 40)); ?></td>
                    <td><?php echo e($item['category_name']); ?></td>
                    <td style="font-family:monospace;font-size:12px;color:var(--muted);"><?php echo e($item['sku']); ?></td>
                    <td><span class="badge badge-<?php echo $item['status'] ? 'new' : 'rejected'; ?>"><?php echo $item['status'] ? 'Active' : 'Inactive'; ?></span></td>
                    <td><?php echo $item['is_featured'] ? '★' : '-'; ?></td>
                    <td>
                        <a href="products.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline">Edit</a>
                        <a href="products.php?action=delete&id=<?php echo $item['id']; ?>&<?php echo CSRF_TOKEN_NAME; ?>=<?php echo csrfToken(); ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php echo pagination($total, $per_page, $page_num, 'products.php?page={page}&category=' . $filter_cat . '&search=' . urlencode($search)); ?>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
