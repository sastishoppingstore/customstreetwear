<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'SEO Manager';
$tab = $_GET['tab'] ?? 'pages';
$search = trim($_GET['search'] ?? '');

function getSeoStatus($title, $desc) {
    $status = 'good';
    $issues = [];
    $titleLen = mb_strlen($title);
    $descLen = mb_strlen($desc);
    if ($titleLen === 0) { $status = 'missing'; $issues[] = 'Missing title'; }
    elseif ($titleLen < 30) { $status = 'warning'; $issues[] = 'Title too short (' . $titleLen . ' chars)'; }
    elseif ($titleLen > 60) { $status = 'warning'; $issues[] = 'Title too long (' . $titleLen . ' chars)'; }
    if ($descLen === 0) { if ($status !== 'missing') $status = 'warning'; $issues[] = 'Missing description'; }
    elseif ($descLen < 50) { $status = 'warning'; $issues[] = 'Description too short (' . $descLen . ' chars)'; }
    elseif ($descLen > 160) { $status = 'warning'; $issues[] = 'Description too long (' . $descLen . ' chars)'; }
    return ['status' => $status, 'issues' => $issues, 'title_len' => $titleLen, 'desc_len' => $descLen];
}

include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header"><h1 class="content-title">SEO Manager</h1></div>
    <?php echo showFlash(); ?>
    
    <div class="admin-card" style="margin-bottom: 20px;">
        <div class="seo-tabs" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px;">
            <a href="seo-manager.php?tab=pages<?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="btn <?php echo $tab === 'pages' ? 'btn-primary' : 'btn-outline'; ?> btn-sm">Pages</a>
            <a href="seo-manager.php?tab=products<?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="btn <?php echo $tab === 'products' ? 'btn-primary' : 'btn-outline'; ?> btn-sm">Products</a>
            <a href="seo-manager.php?tab=categories<?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="btn <?php echo $tab === 'categories' ? 'btn-primary' : 'btn-outline'; ?> btn-sm">Categories</a>
            <a href="seo-manager.php?tab=blogs<?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="btn <?php echo $tab === 'blogs' ? 'btn-primary' : 'btn-outline'; ?> btn-sm">Blogs</a>
            <a href="seo-manager.php?tab=subcategories<?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="btn <?php echo $tab === 'subcategories' ? 'btn-primary' : 'btn-outline'; ?> btn-sm">Subcategories</a>
            <span style="flex:1;"></span>
            <form method="GET" action="seo-manager.php" style="display:flex;gap:8px;">
                <input type="hidden" name="tab" value="<?php echo e($tab); ?>">
                <input type="text" name="search" class="form-input" style="width:200px;padding:6px 10px;font-size:13px;" placeholder="Search..." value="<?php echo e($search); ?>">
                <button type="submit" class="btn btn-outline btn-sm">Search</button>
            </form>
        </div>
        
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>SEO Title</th>
                    <th>SEO Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $limit = 50;
                $where = $search ? "AND (title LIKE ? OR seo_title LIKE ? OR seo_description LIKE ?)" : "";
                $params = $search ? ["%$search%", "%$search%", "%$search%"] : [];

                if ($tab === 'pages') {
                    $items = dbFetchAll("SELECT id, title, seo_title, seo_description, slug, 'page' as entity_type FROM pages WHERE status = 1 $where ORDER BY title LIMIT $limit", $params);
                } elseif ($tab === 'products') {
                    $items = dbFetchAll("SELECT id, title, seo_title, seo_description, slug, 'product' as entity_type FROM products WHERE status = 1 $where ORDER BY title LIMIT $limit", $params);
                } elseif ($tab === 'categories') {
                    $items = dbFetchAll("SELECT id, name as title, seo_title, seo_description, slug, 'category' as entity_type FROM categories WHERE status = 1 $where ORDER BY name LIMIT $limit", $params);
                } elseif ($tab === 'blogs') {
                    $items = dbFetchAll("SELECT id, title, seo_title, seo_description, slug, 'blog' as entity_type FROM blogs WHERE status = 1 $where ORDER BY title LIMIT $limit", $params);
                } elseif ($tab === 'subcategories') {
                    $items = dbFetchAll("SELECT id, name as title, seo_title, seo_description, slug, 'subcategory' as entity_type FROM subcategories WHERE status = 1 $where ORDER BY name LIMIT $limit", $params);
                } else {
                    $items = [];
                }

                if (empty($items)): ?>
                <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:40px;">No items found.</td></tr>
                <?php endif; ?>

                <?php foreach ($items as $item): 
                    $metaTitle = $item['seo_title'] ?: $item['title'];
                    $metaDesc = $item['seo_description'] ?: '';
                    $analysis = getSeoStatus($metaTitle, $metaDesc);
                    $badgeClass = $analysis['status'] === 'good' ? 'badge-new' : ($analysis['status'] === 'warning' ? 'badge-warning' : 'badge-rejected');
                    $badgeText = $analysis['status'] === 'good' ? 'Good' : ($analysis['status'] === 'warning' ? 'Warning' : 'Missing');
                    
                    $urlMap = ['page' => 'pages.php', 'product' => 'products.php', 'category' => 'categories.php', 'blog' => 'blogs.php', 'subcategory' => 'subcategories.php'];
                    $editUrl = ($urlMap[$item['entity_type']] ?? 'pages.php') . '?action=edit&id=' . $item['id'];
                ?>
                <tr>
                    <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;"><a href="<?php echo $editUrl; ?>"><?php echo e(truncate($item['title'], 50)); ?></a></td>
                    <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;font-size:13px;">
                        <span title="Length: <?php echo $analysis['title_len']; ?> chars"><?php echo e(truncate($metaTitle, 50)); ?></span>
                        <span style="display:block;font-size:11px;color:var(--muted);"><?php echo $analysis['title_len']; ?> chars</span>
                    </td>
                    <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;font-size:13px;">
                        <span title="Length: <?php echo $analysis['desc_len']; ?> chars"><?php echo e(truncate($metaDesc, 80)); ?></span>
                        <span style="display:block;font-size:11px;color:var(--muted);"><?php echo $analysis['desc_len']; ?> chars</span>
                    </td>
                    <td>
                        <span class="badge <?php echo $badgeClass; ?>" title="<?php echo e(implode(', ', $analysis['issues'])); ?>"><?php echo $badgeText; ?></span>
                    </td>
                    <td>
                        <a href="<?php echo $editUrl; ?>" class="btn btn-sm btn-outline">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div style="margin-top:16px;font-size:12px;color:var(--muted);">
            <span style="display:inline-flex;align-items:center;gap:6px;margin-right:16px;"><span class="badge badge-new">Good</span> 30-60 chars title, 50-160 chars desc</span>
            <span style="display:inline-flex;align-items:center;gap:6px;margin-right:16px;"><span class="badge badge-warning">Warning</span> Needs optimization</span>
            <span style="display:inline-flex;align-items:center;gap:6px;"><span class="badge badge-rejected">Missing</span> No SEO data</span>
        </div>
    </div>
</div>

<style>
.badge-warning { background: var(--warning, #f59e0b); color: #000; }
.admin-table td { vertical-align: middle; }
</style>
<?php include __DIR__ . '/includes/footer.php'; ?>
