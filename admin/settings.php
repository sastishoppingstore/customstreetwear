<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Settings';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'setting_') === 0) {
            $settingKey = substr($key, 8);
            dbExecute("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?", [trim($value), $settingKey]);
        }
    }
    refreshSettings();
    setFlash('success', 'Settings saved successfully.');
    redirect('settings.php');
}

$settings = dbFetchAll("SELECT * FROM site_settings ORDER BY setting_key");
include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header"><h1 class="content-title">Site Settings</h1></div>
    <?php echo showFlash(); ?>
    <div class="admin-card">
        <form method="POST" action="settings.php">
            <?php echo csrfField(); ?>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(350px,1fr));gap:20px;">
                <?php foreach ($settings as $s): ?>
                <div class="form-group">
                    <label class="form-label"><?php echo e(str_replace('_', ' ', ucfirst($s['setting_key']))); ?></label>
                    <?php if ($s['setting_type'] === 'textarea'): ?>
                    <textarea name="setting_<?php echo e($s['setting_key']); ?>" class="form-textarea" rows="3"><?php echo e($s['setting_value']); ?></textarea>
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
            <div style="margin-top:30px;"><button type="submit" class="btn btn-primary btn-lg">Save Settings</button></div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
