<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Settings';

function getSettingGroup($key) {
    if (strpos($key, 'site_') === 0 || $key === 'analytics_code' || $key === 'maintenance_mode') return 'General';
    if (strpos($key, 'home_') === 0 || strpos($key, 'about_') === 0) return 'Homepage';
    if (strpos($key, 'seo_') === 0 || $key === 'og_image' || $key === 'favicon') return 'SEO';
    if (in_array($key, ['facebook_url','instagram_url','twitter_url','youtube_url','linkedin_url'])) return 'Social Media';
    if (strpos($key, 'smtp_') === 0) return 'Email / SMTP';
    if (strpos($key, 'recaptcha_') === 0) return 'Security';
    if (strpos($key, 'whatsapp_') === 0) return 'WhatsApp';
    if (strpos($key, 'quote_') === 0 || strpos($key, 'contact_') === 0) return 'Forms';
    if (in_array($key, ['items_per_page','products_per_page','blogs_per_page','enable_registration'])) return 'Display';
    if (strpos($key, 'footer_') === 0 || strpos($key, 'copyright_') === 0) return 'Footer';
    if (strpos($key, 'site_logo') === 0) return 'Branding';
    return 'Other';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    $hasError = false;
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'setting_') === 0) {
            $settingKey = substr($key, 8);
            $existing = dbFetchOne("SELECT id FROM site_settings WHERE setting_key = ?", [$settingKey]);
            if ($existing) {
                dbExecute("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?", [trim($value), $settingKey]);
            } else {
                $type = strpos($settingKey, 'code') !== false || strpos($settingKey, 'pixel') !== false || strpos($settingKey, 'gtm') !== false || strpos($settingKey, 'custom') !== false ? 'textarea' : 'text';
                dbExecute("INSERT INTO site_settings (setting_key, setting_value, setting_type) VALUES (?, ?, ?)", [$settingKey, trim($value), $type]);
            }
        }
    }
    foreach ($_FILES as $key => $file) {
        if (strpos($key, 'setting_file_') === 0 && isset($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
            $settingKey = substr($key, 13);
            $result = uploadFile($file, 'settings', array_merge(ALLOWED_IMAGE_TYPES, ['ico']));
            if ($result['success']) {
                $existing = dbFetchOne("SELECT id FROM site_settings WHERE setting_key = ?", [$settingKey]);
                if ($existing) {
                    dbExecute("UPDATE site_settings SET setting_value = ?, setting_type = 'image' WHERE setting_key = ?", [$result['path'], $settingKey]);
                } else {
                    dbExecute("INSERT INTO site_settings (setting_key, setting_value, setting_type) VALUES (?, ?, 'image')", [$settingKey, $result['path']]);
                }
            } else {
                setFlash('error', $settingKey . ': ' . $result['error']);
                $hasError = true;
            }
        }
    }
    refreshSettings();
    if (!$hasError) {
        setFlash('success', 'Settings saved successfully.');
    }
    redirect('settings.php');
}

$settings = dbFetchAll("SELECT * FROM site_settings ORDER BY setting_key");
$groups = [];
foreach ($settings as $s) {
    $g = getSettingGroup($s['setting_key']);
    $groups[$g][] = $s;
}
$activeGroup = $_GET['group'] ?? array_key_first($groups);
include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header"><h1 class="content-title">Site Settings</h1></div>
    <?php echo showFlash(); ?>
    
    <div class="settings-tabs" style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:24px;">
        <?php foreach ($groups as $groupName => $groupSettings): ?>
        <a href="settings.php?group=<?php echo urlencode($groupName); ?>" class="btn <?php echo $activeGroup === $groupName ? 'btn-primary' : 'btn-outline'; ?> btn-sm"><?php echo e($groupName); ?> (<?php echo count($groupSettings); ?>)</a>
        <?php endforeach; ?>
    </div>
    
    <div class="admin-card">
        <form method="POST" action="settings.php" enctype="multipart/form-data">
            <?php echo csrfField(); ?>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(380px,1fr));gap:20px;">
                <?php foreach (($groups[$activeGroup] ?? []) as $s): ?>
                <div class="form-group">
                    <label class="form-label"><?php echo e(ucwords(str_replace(['home_', '_'], ['', ' '], $s['setting_key']))); ?></label>
                    <small style="display:block;color:var(--muted);font-size:11px;margin-bottom:4px;"><?php echo e($s['setting_key']); ?></small>
                    <?php if ($s['setting_type'] === 'textarea'): ?>
                    <textarea name="setting_<?php echo e($s['setting_key']); ?>" class="form-textarea" rows="3"><?php echo e($s['setting_value']); ?></textarea>
                    <?php elseif ($s['setting_type'] === 'image'): ?>
                    <input type="hidden" name="setting_<?php echo e($s['setting_key']); ?>" value="<?php echo e($s['setting_value']); ?>">
                    <?php if ($s['setting_value']): ?>
                    <div style="margin-bottom:8px;padding:8px;border:1px solid var(--border);border-radius:var(--radius-sm);display:inline-flex;background:var(--bg);">
                        <img src="<?php echo e($s['setting_value']); ?>" alt="" style="max-width:160px;max-height:70px;object-fit:contain;">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="setting_file_<?php echo e($s['setting_key']); ?>" class="form-input" accept=".jpg,.jpeg,.png,.webp,.gif,.ico">
                    <?php elseif ($s['setting_type'] === 'boolean'): ?>
                    <select name="setting_<?php echo e($s['setting_key']); ?>" class="form-select">
                        <option value="1" <?php echo $s['setting_value'] == '1' ? 'selected' : ''; ?>>Yes</option>
                        <option value="0" <?php echo $s['setting_value'] == '0' ? 'selected' : ''; ?>>No</option>
                    </select>
                    <?php elseif ($s['setting_type'] === 'number'): ?>
                    <input type="number" name="setting_<?php echo e($s['setting_key']); ?>" class="form-input" value="<?php echo e($s['setting_value']); ?>">
                    <?php else: ?>
                    <input type="<?php echo $s['setting_type'] === 'email' ? 'email' : ($s['setting_type'] === 'url' ? 'url' : 'text'); ?>" name="setting_<?php echo e($s['setting_key']); ?>" class="form-input" value="<?php echo e($s['setting_value']); ?>">
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <div style="margin-top:30px;"><button type="submit" class="btn btn-primary btn-lg">Save <?php echo e($activeGroup); ?> Settings</button></div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
