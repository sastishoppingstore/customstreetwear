<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Advanced SEO Manager';
$tab = $_GET['tab'] ?? 'dashboard';

include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header">
        <h1 class="content-title">Advanced SEO Manager</h1>
        <div style="display:flex;gap:8px;">
            <a href="seo-manager-v2.php?tab=analysis" class="btn btn-primary">SEO Analysis</a>
            <a href="seo-manager-v2.php?tab=settings" class="btn btn-outline">Global SEO Settings</a>
        </div>
    </div>
    <?php echo showFlash(); ?>

    <div class="seo-tabs" style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:24px;">
        <a href="seo-manager-v2.php?tab=dashboard" class="btn <?php echo $tab==='dashboard'?'btn-primary':'btn-outline';?> btn-sm">Dashboard</a>
        <a href="seo-manager-v2.php?tab=analysis" class="btn <?php echo $tab==='analysis'?'btn-primary':'btn-outline';?> btn-sm">SEO Analysis</a>
        <a href="seo-manager-v2.php?tab=redirects" class="btn <?php echo $tab==='redirects'?'btn-primary':'btn-outline';?> btn-sm">Redirects</a>
        <a href="seo-manager-v2.php?tab=sitemap" class="btn <?php echo $tab==='sitemap'?'btn-primary':'btn-outline';?> btn-sm">Sitemap</a>
        <a href="seo-manager-v2.php?tab=robots" class="btn <?php echo $tab==='robots'?'btn-primary':'btn-outline';?> btn-sm">Robots.txt</a>
        <a href="seo-manager-v2.php?tab=schema" class="btn <?php echo $tab==='schema'?'btn-primary':'btn-outline';?> btn-sm">Schema</a>
        <a href="seo-manager-v2.php?tab=settings" class="btn <?php echo $tab==='settings'?'btn-primary':'btn-outline';?> btn-sm">Settings</a>
        <a href="seo-manager.php" class="btn btn-outline btn-sm" style="margin-left:auto;">Simple SEO</a>
    </div>

    <?php if ($tab === 'dashboard'): ?>
    <div class="seo-dashboard">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-bottom:24px;">
            <?php
            $totalPages = dbFetchOne("SELECT COUNT(*) as c FROM pages WHERE status=1")['c'] ?? 0;
            $totalProducts = dbFetchOne("SELECT COUNT(*) as c FROM products WHERE status=1")['c'] ?? 0;
            $totalCategories = dbFetchOne("SELECT COUNT(*) as c FROM categories WHERE status=1")['c'] ?? 0;
            $totalBlogs = dbFetchOne("SELECT COUNT(*) as c FROM blogs WHERE status=1")['c'] ?? 0;
            $totalRedirects = dbFetchOne("SELECT COUNT(*) as c FROM redirects WHERE status=1")['c'] ?? 0;
            $pagesWithSeo = dbFetchOne("SELECT COUNT(*) as c FROM pages WHERE seo_title IS NOT NULL AND seo_title != ''")['c'] ?? 0;
            ?>
            <div class="admin-card" style="text-align:center;padding:20px;">
                <div style="font-size:28px;font-weight:700;color:var(--accent);"><?php echo $totalPages; ?></div>
                <div style="font-size:13px;color:var(--muted);">Pages</div>
                <div style="font-size:11px;color:var(--muted);"><?php echo $pagesWithSeo; ?> with SEO</div>
            </div>
            <div class="admin-card" style="text-align:center;padding:20px;">
                <div style="font-size:28px;font-weight:700;color:var(--accent);"><?php echo $totalProducts; ?></div>
                <div style="font-size:13px;color:var(--muted);">Products</div>
            </div>
            <div class="admin-card" style="text-align:center;padding:20px;">
                <div style="font-size:28px;font-weight:700;color:var(--accent);"><?php echo $totalCategories; ?></div>
                <div style="font-size:13px;color:var(--muted);">Categories</div>
            </div>
            <div class="admin-card" style="text-align:center;padding:20px;">
                <div style="font-size:28px;font-weight:700;color:var(--accent);"><?php echo $totalBlogs; ?></div>
                <div style="font-size:13px;color:var(--muted);">Blog Posts</div>
            </div>
            <div class="admin-card" style="text-align:center;padding:20px;">
                <div style="font-size:28px;font-weight:700;color:var(--accent);"><?php echo $totalRedirects; ?></div>
                <div style="font-size:13px;color:var(--muted);">Active Redirects</div>
            </div>
        </div>

        <div class="admin-card">
            <h3 style="margin-bottom:16px;">SEO Checklist</h3>
            <?php
            $checks = [
                ['Site Title', getSetting('seo_title') ?: DEFAULT_SEO_TITLE, 'Site has SEO title', 30, 60],
                ['Site Description', getSetting('seo_description') ?: '', 'Site has meta description', 50, 160],
                ['OG Image', getSetting('og_image') ?: '', 'Site has OpenGraph image', null, null],
                ['Favicon', getSetting('favicon') ?: '', 'Site has favicon', null, null],
                ['Google Verification', getSetting('seo_google_verification') ?: '', 'Google Search Console verified', null, null],
                ['Sitemap', getSetting('seo_enable_sitemap') ?: '1', 'XML Sitemap enabled', null, null],
                ['Robots.txt', getSetting('seo_enable_robots') ?: '1', 'Robots.txt enabled', null, null],
                ['SSL', isset($_SERVER['HTTPS']) ? '1' : '0', 'HTTPS enabled', null, null],
                ['Analytics', getSetting('analytics_code') ?: '', 'Analytics code installed', null, null],
            ];
            foreach ($checks as $check):
                $value = $check[1];
                $pass = ($check[2] === 'Site has SEO title' || $check[2] === 'Site has meta description')
                    ? (strlen($value) >= ($check[3] ?? 0))
                    : !empty($value) && $value !== '0';
            ?>
            <div style="display:flex;align-items:center;gap:12px;padding:8px 0;border-bottom:1px solid var(--border);">
                <span style="width:20px;height:20px;border-radius:50%;background:<?php echo $pass ? 'var(--success)#39ff14' : 'var(--danger)#ff4444'; ?>;display:flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0;">
                    <?php echo $pass ? '✓' : '✗'; ?>
                </span>
                <span style="flex:1;font-size:14px;"><?php echo $check[2]; ?></span>
                <?php if (!$pass): ?>
                <a href="settings.php" class="btn btn-sm btn-outline" style="font-size:11px;">Fix</a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php elseif ($tab === 'analysis'): ?>
    <div class="admin-card">
        <div style="display:flex;gap:16px;align-items:center;margin-bottom:20px;flex-wrap:wrap;">
            <form method="GET" action="seo-manager-v2.php" style="display:flex;gap:8px;flex:1;">
                <input type="hidden" name="tab" value="analysis">
                <select name="type" class="form-select" style="width:auto;">
                    <option value="pages">Pages</option>
                    <option value="products">Products</option>
                    <option value="categories">Categories</option>
                    <option value="blogs">Blogs</option>
                </select>
                <input type="text" name="search" class="form-input" style="flex:1;min-width:150px;" placeholder="Search..." value="<?php echo e($_GET['search'] ?? ''); ?>">
                <button type="submit" class="btn btn-primary btn-sm">Analyze</button>
            </form>
        </div>
        <?php
        $type = $_GET['type'] ?? 'pages';
        $search = trim($_GET['search'] ?? '');

        $tableMap = ['pages' => ['table' => 'pages', 'title' => 'title', 'slug' => 'slug'],
                     'products' => ['table' => 'products', 'title' => 'title', 'slug' => 'slug'],
                     'categories' => ['table' => 'categories', 'title' => 'name', 'slug' => 'slug'],
                     'blogs' => ['table' => 'blogs', 'title' => 'title', 'slug' => 'slug']];
        $info = $tableMap[$type] ?? $tableMap['pages'];
        $where = $search ? "AND {$info['title']} LIKE ?" : '';
        $params = $search ? ["%$search%"] : [];
        $items = dbFetchAll("SELECT id, {$info['title']} as title, slug, seo_title, seo_description, focus_keyword FROM {$info['table']} WHERE status=1 $where ORDER BY {$info['title']} LIMIT 50", $params);
        ?>
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead><tr>
                    <th>Title</th><th>SEO Title</th><th>Desc</th><th>Keyword</th><th>Score</th><th>Actions</th>
                </tr></thead>
                <tbody>
                    <?php foreach ($items as $item):
                        $metaTitle = $item['seo_title'] ?: $item['title'];
                        $metaDesc = $item['seo_description'] ?: '';
                        $kw = $item['focus_keyword'] ?? '';

                        $score = 0;
                        if (!empty($metaTitle)) {
                            $tLen = mb_strlen($metaTitle);
                            if ($tLen >= 30 && $tLen <= 60) $score += 25;
                            elseif ($tLen > 10) $score += 15;
                            if ($kw && stripos($metaTitle, $kw) !== false) $score += 15;
                        }
                        if (!empty($metaDesc)) {
                            $dLen = mb_strlen($metaDesc);
                            if ($dLen >= 50 && $dLen <= 160) $score += 25;
                            elseif ($dLen > 20) $score += 15;
                            if ($kw && stripos($metaDesc, $kw) !== false) $score += 15;
                        }
                        if (!empty($kw)) $score += 10;

                        $grade = $score >= 80 ? 'A' : ($score >= 60 ? 'B' : ($score >= 40 ? 'C' : ($score >= 20 ? 'D' : 'F')));
                        $gradeColor = $grade === 'A' ? '#39ff14' : ($grade === 'B' ? '#00ccff' : ($grade === 'C' ? '#ffaa00' : '#ff4444'));
                    ?>
                    <tr>
                        <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;">
                            <a href="<?php echo $type === 'categories' ? 'categories.php' : ($type === 'products' ? 'products.php' : ($type === 'blogs' ? 'blogs.php' : 'pages.php')); ?>?action=edit&id=<?php echo $item['id']; ?>"><?php echo e(truncate($item['title'], 50)); ?></a>
                        </td>
                        <td style="max-width:160px;font-size:13px;"><?php echo e(truncate($metaTitle, 45)); ?><br><small style="color:var(--muted);"><?php echo mb_strlen($metaTitle); ?> chars</small></td>
                        <td style="max-width:180px;font-size:13px;"><?php echo e(truncate($metaDesc, 60)); ?><br><small style="color:var(--muted);"><?php echo mb_strlen($metaDesc); ?> chars</small></td>
                        <td style="font-size:13px;"><?php echo $kw ? e(truncate($kw, 20)) : '<span style="color:var(--muted);">—</span>'; ?></td>
                        <td><span style="display:inline-flex;align-items:center;gap:6px;font-weight:700;font-size:18px;color:<?php echo $gradeColor; ?>;"><?php echo $grade; ?> <small style="font-size:11px;font-weight:400;">(<?php echo $score; ?>/100)</small></span></td>
                        <td>
                            <a href="<?php echo $type === 'categories' ? 'categories.php' : ($type === 'products' ? 'products.php' : ($type === 'blogs' ? 'blogs.php' : 'pages.php')); ?>?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline">Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($items)): ?>
                    <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted);">No items found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php elseif ($tab === 'redirects'):
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        requireCsrf();
        if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
            $oldUrl = trim($_POST['old_url']);
            $newUrl = trim($_POST['new_url']);
            $type = $_POST['redirect_type'] ?? '301';
            $id = intval($_POST['id'] ?? 0);
            if ($id) {
                dbExecute("UPDATE redirects SET old_url=?, new_url=?, redirect_type=? WHERE id=?", [$oldUrl, $newUrl, $type, $id]);
            } else {
                dbExecute("INSERT INTO redirects (old_url, new_url, redirect_type) VALUES (?,?,?)", [$oldUrl, $newUrl, $type]);
            }
            setFlash('success', 'Redirect saved.');
        } elseif ($_POST['action'] === 'delete') {
            dbExecute("DELETE FROM redirects WHERE id=?", [intval($_POST['id'])]);
            setFlash('success', 'Redirect deleted.');
        }
        redirect('seo-manager-v2.php?tab=redirects');
    }
    $redirects = dbFetchAll("SELECT * FROM redirects ORDER BY old_url");
    ?>
    <div class="admin-card">
        <div style="margin-bottom:20px;">
            <button class="btn btn-primary" onclick="document.getElementById('redirectForm').classList.toggle('hidden')">Add Redirect</button>
        </div>
        <form id="redirectForm" method="POST" action="seo-manager-v2.php?tab=redirects" class="hidden" style="margin-bottom:24px;padding:20px;background:var(--bg-card);border-radius:var(--radius-md);">
            <?php echo csrfField(); ?>
            <input type="hidden" name="action" value="add">
            <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:12px;align-items:end;">
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Old URL (from)</label>
                    <input type="text" name="old_url" class="form-input" required placeholder="/old-page">
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">New URL (to)</label>
                    <input type="text" name="new_url" class="form-input" required placeholder="/new-page">
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Type</label>
                    <select name="redirect_type" class="form-select">
                        <option value="301">301 Permanent</option>
                        <option value="302">302 Temporary</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm" style="margin-top:12px;">Save Redirect</button>
        </form>
        <?php if (empty($redirects)): ?>
        <p style="color:var(--muted);text-align:center;padding:40px;">No redirects configured.</p>
        <?php else: ?>
        <table class="admin-table">
            <thead><tr><th>Old URL</th><th>New URL</th><th>Type</th><th>Hits</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($redirects as $r): ?>
                <tr>
                    <td><code><?php echo e($r['old_url']); ?></code></td>
                    <td><code><?php echo e($r['new_url']); ?></code></td>
                    <td><span class="badge badge-new"><?php echo e($r['redirect_type']); ?></span></td>
                    <td><?php echo intval($r['hits']); ?></td>
                    <td>
                        <form method="POST" action="seo-manager-v2.php?tab=redirects" style="display:inline;">
                            <?php echo csrfField(); ?>
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline" style="color:var(--danger);" onclick="return confirm('Delete this redirect?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <?php elseif ($tab === 'sitemap'): ?>
    <div class="admin-card">
        <h3 style="margin-bottom:16px;">XML Sitemap Settings</h3>
        <form method="POST" action="settings.php">
            <?php echo csrfField(); ?>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:600px;">
                <div class="form-group">
                    <label class="form-label">Enable Sitemap</label>
                    <select name="setting_seo_enable_sitemap" class="form-select">
                        <option value="1" <?php echo getSetting('seo_enable_sitemap','1')=='1'?'selected':'';?>>Enabled</option>
                        <option value="0" <?php echo getSetting('seo_enable_sitemap','1')=='0'?'selected':'';?>>Disabled</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Sitemap URL</label>
                    <input type="text" class="form-input" value="<?php echo SITE_URL; ?>/sitemap.xml.php" readonly>
                </div>
            </div>
            <div class="form-group" style="max-width:600px;">
                <label class="form-label">Exclude from Sitemap (one per line)</label>
                <textarea name="setting_seo_sitemap_exclude" class="form-textarea" rows="4"><?php echo e(getSetting('seo_sitemap_exclude', '')); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>

    <?php elseif ($tab === 'robots'): ?>
    <div class="admin-card">
        <h3 style="margin-bottom:16px;">Robots.txt Settings</h3>
        <form method="POST" action="settings.php">
            <?php echo csrfField(); ?>
            <div class="form-group" style="max-width:600px;">
                <label class="form-label">Enable Dynamic Robots.txt</label>
                <select name="setting_seo_enable_robots" class="form-select">
                    <option value="1" <?php echo getSetting('seo_enable_robots','1')=='1'?'selected':'';?>>Enabled</option>
                    <option value="0" <?php echo getSetting('seo_enable_robots','1')=='0'?'selected':'';?>>Disabled</option>
                </select>
            </div>
            <div class="form-group" style="max-width:600px;">
                <label class="form-label">Custom Robots.txt Rules</label>
                <textarea name="setting_seo_robots_custom" class="form-textarea" rows="8" placeholder="User-agent: *&#10;Disallow: /admin&#10;Allow: /"><?php echo e(getSetting('seo_robots_custom', '')); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
        <div style="margin-top:24px;">
            <h4 style="margin-bottom:8px;">Preview</h4>
            <pre style="background:var(--bg-card);padding:16px;border-radius:var(--radius-sm);font-size:13px;overflow-x:auto;">
User-agent: *
Disallow: /admin/
Disallow: /includes/
Disallow: /config/
Disallow: /api/
Disallow: /uploads/
Allow: /uploads/sliders/
Allow: /uploads/products/
Allow: /uploads/categories/

Sitemap: <?php echo SITE_URL; ?>/sitemap.xml.php
            </pre>
        </div>
    </div>

    <?php elseif ($tab === 'schema'): ?>
    <div class="admin-card">
        <h3 style="margin-bottom:16px;">Schema Markup Settings</h3>
        <form method="POST" action="settings.php">
            <?php echo csrfField(); ?>
            <div class="form-group">
                <label class="form-label">Custom Organization Schema (JSON-LD)</label>
                <textarea name="setting_seo_schema_organization" class="form-textarea" rows="8" style="font-family:monospace;font-size:13px;"><?php echo e(getSetting('seo_schema_organization', '')); ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Custom Website Schema (JSON-LD)</label>
                <textarea name="setting_seo_schema_website" class="form-textarea" rows="8" style="font-family:monospace;font-size:13px;"><?php echo e(getSetting('seo_schema_website', '')); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Schema</button>
        </form>
        <div style="margin-top:24px;">
            <h4 style="margin-bottom:8px;">Active Schema Types</h4>
            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                <span class="badge badge-new">Organization</span>
                <span class="badge badge-new">WebSite</span>
                <span class="badge badge-new">BreadcrumbList</span>
                <span class="badge badge-new">Product</span>
                <span class="badge badge-new">FAQPage</span>
                <span class="badge badge-new">BlogPosting</span>
                <span class="badge badge-new">LocalBusiness</span>
            </div>
        </div>
    </div>

    <?php elseif ($tab === 'settings'): ?>
    <div class="admin-card">
        <h3 style="margin-bottom:16px;">Global SEO Settings</h3>
        <form method="POST" action="settings.php">
            <?php echo csrfField(); ?>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:800px;">
                <div class="form-group">
                    <label class="form-label">Default SEO Title</label>
                    <input type="text" name="setting_seo_title" class="form-input" value="<?php echo e(getSetting('seo_title', DEFAULT_SEO_TITLE)); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Twitter Handle</label>
                    <input type="text" name="setting_seo_twitter_handle" class="form-input" value="<?php echo e(getSetting('seo_twitter_handle', '@customstreetwear')); ?>">
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Default Meta Description</label>
                    <textarea name="setting_seo_description" class="form-textarea" rows="3"><?php echo e(getSetting('seo_description', DEFAULT_SEO_DESC)); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Google Search Console Verification</label>
                    <input type="text" name="setting_seo_google_verification" class="form-input" value="<?php echo e(getSetting('seo_google_verification', '')); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Bing Webmaster Verification</label>
                    <input type="text" name="setting_seo_bing_verification" class="form-input" value="<?php echo e(getSetting('seo_bing_verification', '')); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Facebook Page ID</label>
                    <input type="text" name="setting_seo_facebook_page_id" class="form-input" value="<?php echo e(getSetting('seo_facebook_page_id', '')); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Facebook Pixel Code</label>
                    <textarea name="setting_seo_facebook_pixel" class="form-textarea" rows="3" placeholder="Paste full pixel code here"><?php echo e(getSetting('seo_facebook_pixel', '')); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">TikTok Pixel Code</label>
                    <textarea name="setting_seo_tiktok_pixel" class="form-textarea" rows="3" placeholder="Paste full TikTok pixel code here"><?php echo e(getSetting('seo_tiktok_pixel', '')); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Google Tag Manager (Head)</label>
                    <textarea name="setting_seo_gtm_head" class="form-textarea" rows="3"><?php echo e(getSetting('seo_gtm_head', '')); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Google Tag Manager (Body)</label>
                    <textarea name="setting_seo_gtm_body" class="form-textarea" rows="3"><?php echo e(getSetting('seo_gtm_body', '')); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Analytics Code</label>
                    <textarea name="setting_analytics_code" class="form-textarea" rows="3"><?php echo e(getSetting('analytics_code', '')); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Custom Head Code</label>
                    <textarea name="setting_seo_custom_head_code" class="form-textarea" rows="3" placeholder="Extra code before closing head tag"><?php echo e(getSetting('seo_custom_head_code', '')); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Custom Body/Footer Code</label>
                    <textarea name="setting_seo_custom_body_code" class="form-textarea" rows="3" placeholder="Extra code before closing body tag"><?php echo e(getSetting('seo_custom_body_code', '')); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">OG Image</label>
                    <div style="display:flex;gap:8px;align-items:center;">
                        <input type="text" name="setting_og_image" class="form-input" value="<?php echo e(getSetting('og_image', '/uploads/settings/og-image.jpg')); ?>">
                        <button type="button" class="btn btn-outline btn-sm" onclick="alert('Use Media Manager to upload images')">Browse</button>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top:16px;">Save SEO Settings</button>
        </form>
    </div>

    <?php endif; ?>
</div>

<style>
.seo-dashboard .admin-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md); }
.seo-tabs .btn { font-size: 13px; padding: 6px 14px; }
.admin-content { padding: 24px; }
.form-group { margin-bottom: 16px; }
.form-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px; color: var(--text); }
.form-input, .form-select, .form-textarea { width: 100%; padding: 8px 12px; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text); font-size: 14px; }
.form-textarea { resize: vertical; min-height: 80px; }
.admin-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.admin-table th, .admin-table td { padding: 10px 12px; text-align: left; border-bottom: 1px solid var(--border); }
.admin-table th { font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); }
.hidden { display: none !important; }
.badge-new { background: #39ff14; color: #000; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; }
</style>
<?php include __DIR__ . '/includes/footer.php'; ?>
